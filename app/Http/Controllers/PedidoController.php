<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $clientId = $this->clientId($request);
        if (!$clientId) {
            return $this->expiredSession($request);
        }

        $response = $this->apiRequest($request)->get($this->endpoint('index'), [
            'client_id' => $clientId,
        ]);

        if ($response->status() === 401) {
            return $this->expiredSession($request);
        }

        if ($response->failed()) {
            return redirect()->route('inicio')->with('error', $this->apiError($response, 'No se pudieron cargar los pedidos.'));
        }

        $orders = $response->json('orders') ?? data_get($response->json(), 'data.orders') ?? [];
        if (!is_array($orders)) {
            $orders = [];
        }

        return view('pedidos.index', ['orders' => $orders]);
    }

    public function store(Request $request): RedirectResponse
    {
        $clientId = $this->clientId($request);
        if (!$clientId) {
            return $this->expiredSession($request);
        }

        $carrito = session('carrito', []);
        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío. No hay pedido para crear.');
        }

        $items = [];
        foreach ($carrito as $item) {
            $items[] = [
                'id' => (int) ($item['id'] ?? 0),
                'quantity' => (int) ($item['quantity'] ?? 0),
            ];
        }

        $response = $this->apiRequest($request)->post($this->endpoint('store'), [
            'client_id' => $clientId,
            'items' => $items,
        ]);

        if ($response->status() === 401) {
            return $this->expiredSession($request);
        }

        if ($response->failed()) {
            return redirect()->route('carrito.index')->with('error', $this->apiError($response, 'No se pudo crear el pedido.'));
        }

        session()->forget('carrito');

        $orderId = $response->json('order.id') ?? data_get($response->json(), 'data.order.id');

        if (is_numeric($orderId)) {
            return redirect()->route('pedidos.show', ['id' => (int) $orderId])->with('success', 'Pedido creado correctamente.');
        }

        return redirect()->route('pedidos.index')->with('success', 'Pedido creado correctamente.');
    }

    public function show(Request $request, int $id): View|RedirectResponse
    {
        $clientId = $this->clientId($request);
        if (!$clientId) {
            return $this->expiredSession($request);
        }

        $response = $this->apiRequest($request)->get($this->endpoint('show', $id), [
            'client_id' => $clientId,
        ]);

        if ($response->status() === 401) {
            return $this->expiredSession($request);
        }

        if ($response->failed()) {
            return redirect()->route('pedidos.index')->with('error', $this->apiError($response, 'No se pudo cargar el pedido.'));
        }

        $order = $response->json('order') ?? data_get($response->json(), 'data.order') ?? [];
        if (!is_array($order)) {
            $order = [];
        }

        return view('pedidos.show', ['order' => $order]);
    }

    public function cancel(Request $request, int $id): RedirectResponse
    {
        $clientId = $this->clientId($request);
        if (!$clientId) {
            return $this->expiredSession($request);
        }

        $response = $this->apiRequest($request)->put($this->endpoint('cancel', $id), [
            'client_id' => $clientId,
        ]);

        if ($response->status() === 401) {
            return $this->expiredSession($request);
        }

        if ($response->failed()) {
            return back()->with('error', $this->apiError($response, 'No se pudo cancelar el pedido.'));
        }

        return back()->with('success', 'Pedido cancelado correctamente.');
    }

    private function clientId(Request $request): ?int
    {
        $user = $request->session()->get('auth_user', []);
        $id = $user['id'] ?? null;

        return is_numeric($id) ? (int) $id : null;
    }

    private function apiRequest(Request $request)
    {
        return Http::acceptJson()->withToken((string) $request->session()->get('auth_token'));
    }

    private function endpoint(string $key, ?int $id = null): string
    {
        $base = rtrim((string) config('services.orders_api.url', config('services.auth_api.url', 'http://127.0.0.1:8001/api')), '/');
        $path = (string) config("services.orders_api.{$key}");

        if ($id !== null) {
            $path = str_replace('{id}', (string) $id, $path);
        }

        return $base.'/'.ltrim($path, '/');
    }

    private function expiredSession(Request $request): RedirectResponse
    {
        $request->session()->forget(['auth_token', 'auth_user']);

        return redirect()->route('auth.login.form')->with('error', 'Tu sesión ha expirado. Inicia sesión nuevamente.');
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
