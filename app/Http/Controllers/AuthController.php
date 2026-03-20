<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
        ], [
            'password.regex' => 'La contraseña debe incluir mayúsculas, minúsculas y números.',
        ]);

        $payload = array_merge($validated, [
            'password_confirmation' => (string) $request->input('password_confirmation'),
        ]);

        $response = Http::acceptJson()->post($this->endpoint('register'), $payload);

        if ($response->failed()) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', $this->apiError($response, 'No se pudo completar el registro.'));
        }

        $token = $this->extractToken($response);
        if (!$token) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'La API no devolvió un token válido.');
        }

        $request->session()->put('auth_token', $token);
        $request->session()->put('auth_user', $this->extractUser($response, $payload));

        return redirect()->route('profile.show')->with('success', 'Registro exitoso. ¡Bienvenido!');
    }

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        $response = Http::acceptJson()->post($this->endpoint('login'), $payload);

        if ($response->failed()) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', $this->apiError($response, 'Credenciales inválidas.'));
        }

        $token = $this->extractToken($response);
        if (!$token) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'La API no devolvió un token válido.');
        }

        $request->session()->put('auth_token', $token);
        $request->session()->put('auth_user', $this->extractUser($response, $payload));

        return redirect()->route('profile.show')->with('success', 'Sesión iniciada correctamente.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $token = (string) $request->session()->get('auth_token', '');

        if ($token !== '') {
            Http::acceptJson()
                ->withToken($token)
                ->post($this->endpoint('logout'));
        }

        $request->session()->forget(['auth_token', 'auth_user']);
        $request->session()->regenerateToken();

        return redirect()->route('inicio')->with('success', 'Has cerrado sesión.');
    }

    private function endpoint(string $key): string
    {
        $base = rtrim((string) config('services.auth_api.url', 'http://127.0.0.1:8001/api'), '/');
        $path = (string) config("services.auth_api.{$key}");

        return $base.'/'.ltrim($path, '/');
    }

    private function extractToken(Response $response): ?string
    {
        $data = $response->json();

        return $data['token']
            ?? $data['access_token']
            ?? data_get($data, 'data.token')
            ?? data_get($data, 'data.access_token');
    }

    private function extractUser(Response $response, array $payload): array
    {
        $data = $response->json();
        $user = $data['user'] ?? data_get($data, 'data.user') ?? [];

        if (!is_array($user)) {
            $user = [];
        }

        if (empty($user['email']) && isset($payload['email'])) {
            $user['email'] = $payload['email'];
        }

        if (empty($user['name']) && isset($payload['name'])) {
            $user['name'] = $payload['name'];
        }

        return $user;
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
