<?php

namespace App\Http\Concerns;

use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait InteractsWithOrdersApi
{
    use RemoteApiHttp;

    protected function clientId(Request $request): ?int
    {
        $user = $request->session()->get('auth_user', []);
        $id = $user['id'] ?? null;

        return is_numeric($id) ? (int) $id : null;
    }

    protected function apiRequest(Request $request)
    {
        return $this->remoteHttp()->withToken((string) $request->session()->get('auth_token'));
    }

    protected function endpoint(string $key, ?int $id = null): string
    {
        $base = rtrim((string) config('services.orders_api.url', config('services.auth_api.url', 'http://127.0.0.1:8000/api')), '/');
        $path = (string) config("services.orders_api.{$key}");

        if ($id !== null) {
            $path = str_replace('{id}', (string) $id, $path);
        }

        return $base.'/'.ltrim($path, '/');
    }

    protected function expiredSession(Request $request): RedirectResponse
    {
        $request->session()->forget(['auth_token', 'auth_user']);

        return redirect()->route('auth.login.form')->with('error', 'Tu sesión ha expirado. Inicia sesión nuevamente.');
    }

    protected function apiError(Response $response, string $fallback): string
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
