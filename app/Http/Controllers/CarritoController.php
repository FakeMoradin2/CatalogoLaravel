<?php

namespace App\Http\Controllers;

use App\Http\Concerns\RemoteApiHttp;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarritoController extends Controller
{
    use RemoteApiHttp;

    public function index(): View
    {
        $carrito = session('carrito', []);

        return view('carrito.index', compact('carrito'));
    }

    public function agregar(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|string',
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string|max:500',
            'quantity' => 'nullable|integer|min:1|max:99',
        ]);

        $carrito = session('carrito', []);
        $id = $request->id;
        $cantidad = $request->input('quantity', 1);

        if (isset($carrito[$id])) {
            $carrito[$id]['quantity'] += $cantidad;
        } else {
            $carrito[$id] = [
                'id' => $id,
                'title' => $request->title,
                'price' => (float) $request->price,
                'image' => $request->image ?? '',
                'quantity' => $cantidad,
            ];
        }

        session(['carrito' => $carrito]);
        $this->forgetCuponSession($request);

        return redirect()->route('carrito.index')->with('success', 'Producto agregado al carrito.');
    }

    public function actualizar(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $carrito = session('carrito', []);

        if (!isset($carrito[$id])) {
            return redirect()->route('carrito.index')->with('error', 'Producto no encontrado en el carrito.');
        }

        $carrito[$id]['quantity'] = $request->quantity;
        session(['carrito' => $carrito]);
        $this->forgetCuponSession($request);

        return redirect()->route('carrito.index')->with('success', 'Cantidad actualizada.');
    }

    public function eliminar(Request $request, string $id): RedirectResponse
    {
        $carrito = session('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session(['carrito' => $carrito]);
        }

        $this->forgetCuponSession($request);

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function vaciar(Request $request): RedirectResponse
    {
        session()->forget('carrito');
        $this->forgetCuponSession($request);

        return redirect()->route('carrito.index')->with('success', 'Carrito vaciado.');
    }

    public function aplicarCupon(Request $request): RedirectResponse
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        $token = $request->session()->get('auth_token');
        $user = $request->session()->get('auth_user', []);
        $clientId = $user['id'] ?? null;

        if (!is_numeric($clientId) || !$token) {
            return redirect()->route('auth.login.form')->with('error', 'Inicia sesión para usar cupones.');
        }

        $clientId = (int) $clientId;
        $carrito = session('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío.');
        }

        $items = [];
        foreach ($carrito as $item) {
            $items[] = [
                'id' => (int) ($item['id'] ?? 0),
                'quantity' => (int) ($item['quantity'] ?? 0),
            ];
        }

        try {
            $response = $this->remoteHttp()->withToken((string) $token)->post($this->couponValidateUrl(), [
                'client_id' => $clientId,
                'items' => $items,
                'coupon_code' => $request->input('coupon_code'),
            ]);
        } catch (ConnectionException $e) {
            return redirect()->route('carrito.index')->with('error', 'No se pudo conectar con la API. ¿Está CatalogoAPI en ejecución?');
        }

        if ($response->status() === 401) {
            $request->session()->forget(['auth_token', 'auth_user']);

            return redirect()->route('auth.login.form')->with('error', 'Tu sesión ha expirado. Inicia sesión nuevamente.');
        }

        if ($response->failed()) {
            return redirect()->route('carrito.index')->with('error', $this->apiError($response, 'No se pudo aplicar el cupón.'));
        }

        $data = $response->json();
        $codigo = $data['cupon_codigo'] ?? null;

        if (!is_string($codigo) || $codigo === '') {
            return redirect()->route('carrito.index')->with('error', 'Cupón no válido.');
        }

        session([
            'carrito_cupon_codigo' => $codigo,
            'carrito_cupon_resumen' => is_array($data) ? $data : [],
        ]);

        return redirect()->route('carrito.index')->with('success', 'Cupón aplicado correctamente.');
    }

    public function quitarCupon(Request $request): RedirectResponse
    {
        $this->forgetCuponSession($request);

        return redirect()->route('carrito.index')->with('success', 'Se quitó el cupón del carrito.');
    }

    private function forgetCuponSession(Request $request): void
    {
        $request->session()->forget(['carrito_cupon_codigo', 'carrito_cupon_resumen']);
    }

    private function couponValidateUrl(): string
    {
        $base = rtrim((string) config('services.orders_api.url', config('services.auth_api.url', 'http://127.0.0.1:8001/api')), '/');
        $path = ltrim((string) config('services.orders_api.coupon_validate', '/coupon/validate'), '/');

        return $base.'/'.$path;
    }

    private function apiError(Response $response, string $fallback): string
    {
        $data = $response->json();
        $message = $data['message'] ?? null;

        if (is_string($message) && $message !== '') {
            return $message;
        }

        $firstError = data_get($data, 'errors');
        if (is_array($firstError)) {
            foreach ($firstError as $messages) {
                if (is_array($messages) && isset($messages[0]) && is_string($messages[0])) {
                    return $messages[0];
                }
            }
        }

        return $fallback;
    }
}
