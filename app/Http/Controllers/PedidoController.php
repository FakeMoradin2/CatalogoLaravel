<?php

namespace App\Http\Controllers;

use App\Http\Concerns\InteractsWithOrdersApi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PedidoController extends Controller
{
    use InteractsWithOrdersApi;

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

        $payload = [
            'client_id' => $clientId,
            'items' => $items,
        ];

        $cuponCodigo = $request->session()->get('carrito_cupon_codigo');
        if (is_string($cuponCodigo) && $cuponCodigo !== '') {
            $payload['coupon_code'] = $cuponCodigo;
        }

        $response = $this->apiRequest($request)->post($this->endpoint('store'), $payload);

        if ($response->status() === 401) {
            return $this->expiredSession($request);
        }

        if ($response->failed()) {
            return redirect()->route('carrito.index')->with('error', $this->apiError($response, 'No se pudo crear el pedido.'));
        }

        $request->session()->forget(['carrito', 'carrito_cupon_codigo', 'carrito_cupon_resumen']);

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
}
