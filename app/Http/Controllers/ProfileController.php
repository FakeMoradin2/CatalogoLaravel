<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $response = $this->apiRequest($request)->get($this->endpoint('profile'));

        if ($response->status() === 401) {
            return $this->expiredSession($request);
        }

        if ($response->failed()) {
            return redirect()
                ->route('inicio')
                ->with('error', $this->apiError($response, 'No se pudo cargar el perfil.'));
        }

        $profile = $response->json('user')
            ?? data_get($response->json(), 'data.user')
            ?? $response->json();

        if (!is_array($profile)) {
            $profile = [];
        }

        $request->session()->put('auth_user', $profile);

        return view('profile.show', ['user' => $profile]);
    }

    public function updateInfo(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
        ]);

        $response = $this->apiRequest($request)->put($this->endpoint('profile'), $payload);

        if ($response->status() === 401) {
            return $this->expiredSession($request);
        }

        if ($response->failed()) {
            return back()->withInput()->with('error', $this->apiError($response, 'No se pudo actualizar la información.'));
        }

        $updatedUser = $response->json('user')
            ?? data_get($response->json(), 'data.user')
            ?? $payload;

        if (is_array($updatedUser)) {
            $request->session()->put('auth_user', $updatedUser);
        }

        return back()->with('success', 'Información general actualizada.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $file = $request->file('avatar');
        $stream = fopen($file->getRealPath(), 'r');
        if ($stream === false) {
            return back()->with('error', 'No fue posible leer la imagen seleccionada.');
        }

        $response = $this->apiRequest($request)
            ->attach('avatar', $stream, $file->getClientOriginalName())
            ->post($this->endpoint('avatar'));

        if (is_resource($stream)) {
            fclose($stream);
        }

        if ($response->status() === 401) {
            return $this->expiredSession($request);
        }

        if ($response->failed()) {
            return back()->with('error', $this->apiError($response, 'No se pudo actualizar la imagen de perfil.'));
        }

        $currentUser = $request->session()->get('auth_user', []);
        $updatedUser = $response->json('user') ?? data_get($response->json(), 'data.user');
        if (is_array($updatedUser)) {
            $currentUser = array_merge($currentUser, $updatedUser);
            $request->session()->put('auth_user', $currentUser);
        }

        return back()->with('success', 'Imagen de perfil actualizada.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|string|min:8',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
        ], [
            'password.regex' => 'La nueva contraseña debe incluir mayúsculas, minúsculas y números.',
        ]);

        $payload = array_merge($validated, [
            'password_confirmation' => (string) $request->input('password_confirmation'),
        ]);

        $response = $this->apiRequest($request)->put($this->endpoint('password'), $payload);

        if ($response->status() === 401) {
            return $this->expiredSession($request);
        }

        if ($response->failed()) {
            return back()->with('error', $this->apiError($response, 'No se pudo actualizar la contraseña.'));
        }

        $newToken = $response->json('token') ?? data_get($response->json(), 'data.token');
        if (is_string($newToken) && $newToken !== '') {
            $request->session()->put('auth_token', $newToken);
        }

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }

    private function apiRequest(Request $request)
    {
        return Http::acceptJson()->withToken((string) $request->session()->get('auth_token'));
    }

    private function endpoint(string $key): string
    {
        $base = rtrim((string) config('services.auth_api.url', 'http://127.0.0.1:8001/api'), '/');
        $path = (string) config("services.auth_api.{$key}");

        return $base.'/'.ltrim($path, '/');
    }

    private function expiredSession(Request $request): RedirectResponse
    {
        $request->session()->forget(['auth_token', 'auth_user']);

        return redirect()
            ->route('auth.login.form')
            ->with('error', 'Tu sesión ha expirado. Inicia sesión nuevamente.');
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
