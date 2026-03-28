<?php

namespace App\Http\Controllers;

use App\Http\Concerns\InteractsWithOrdersApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PedidoPagoController extends Controller
{
    use InteractsWithOrdersApi;

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

        $estado = (string) ($order['estado'] ?? '');
        $paymentStatus = (string) ($order['payment_status'] ?? '');

        if ($estado === 'cancelado') {
            return redirect()->route('pedidos.show', ['id' => $id])->with('error', 'Este pedido está cancelado y no admite pago.');
        }

        if ($estado === 'pagado' || $paymentStatus === 'pagado') {
            return redirect()->route('pedidos.show', ['id' => $id])->with('success', 'Este pedido ya está pagado.');
        }

        if ($estado !== 'creado') {
            return redirect()->route('pedidos.show', ['id' => $id])->with('error', 'Este pedido no está disponible para pago en línea.');
        }

        return view('pedidos.pagar', ['order' => $order, 'orderId' => $id]);
    }

    public function prepare(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $clientId = $this->clientId($request);
        if (!$clientId) {
            return response()->json(['message' => 'Sesión expirada.'], 401);
        }

        $response = $this->apiRequest($request)->post($this->endpoint('payment_prepare', $id), [
            'client_id' => $clientId,
        ]);

        if ($response->status() === 401) {
            return response()->json(['message' => 'Sesión expirada.'], 401);
        }

        return response()->json($response->json(), $response->status());
    }

    public function confirm(Request $request, int $id): JsonResponse|RedirectResponse
    {
        $clientId = $this->clientId($request);
        if (!$clientId) {
            return response()->json(['message' => 'Sesión expirada.'], 401);
        }

        $validated = $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        $response = $this->apiRequest($request)->post($this->endpoint('payment_confirm', $id), [
            'client_id' => $clientId,
            'payment_intent_id' => $validated['payment_intent_id'],
        ]);

        if ($response->status() === 401) {
            return response()->json(['message' => 'Sesión expirada.'], 401);
        }

        return response()->json($response->json(), $response->status());
    }

    /**
     * Abre Stripe Checkout (pantalla alojada); los códigos promocionales dependen de la API (allow_promotion_codes).
     */
    public function checkout(Request $request, int $id): RedirectResponse
    {
        $clientId = $this->clientId($request);
        if (!$clientId) {
            return $this->expiredSession($request);
        }

        $successUrl = route('pedidos.pago.checkout.return', ['id' => $id], true)
            . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = route('pedidos.show', ['id' => $id], true);

        $response = $this->apiRequest($request)->post($this->endpoint('payment_checkout_session', $id), [
            'client_id' => $clientId,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);

        if ($response->status() === 401) {
            return $this->expiredSession($request);
        }

        if ($response->failed()) {
            return redirect()->route('pedidos.show', $id)->with('error', $this->apiError($response, 'No se pudo abrir el pago con Stripe.'));
        }

        $url = $response->json('url');
        if (!is_string($url) || $url === '') {
            return redirect()->route('pedidos.show', $id)->with('error', 'Stripe no devolvió la URL de pago.');
        }

        return redirect()->away($url);
    }

    /**
     * Tras volver de Stripe Checkout: verifica la sesión en la API y marca el pedido pagado.
     */
    public function checkoutReturn(Request $request, int $id): RedirectResponse
    {
        $clientId = $this->clientId($request);
        if (!$clientId) {
            return $this->expiredSession($request);
        }

        $sessionId = $request->query('session_id');
        if (!is_string($sessionId) || $sessionId === '') {
            return redirect()->route('pedidos.show', $id)->with('error', 'Datos de pago incompletos.');
        }

        $response = $this->apiRequest($request)->post($this->endpoint('payment_checkout_verify', $id), [
            'client_id' => $clientId,
            'session_id' => $sessionId,
        ]);

        if ($response->status() === 401) {
            return $this->expiredSession($request);
        }

        if ($response->failed()) {
            return redirect()->route('pedidos.show', $id)->with('error', $this->apiError($response, 'No se pudo confirmar el pago.'));
        }

        return redirect()->to(route('pedidos.show', ['id' => $id]).'?paid=1')->with('success', 'Pago registrado correctamente.');
    }
}
