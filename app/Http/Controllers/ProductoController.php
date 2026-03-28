<?php

namespace App\Http\Controllers;

use App\Http\Concerns\RemoteApiHttp;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    use RemoteApiHttp;

    private function getApiBase(): string
    {
        return rtrim(config('services.productos_api.url', 'http://127.0.0.1:8000/api'), '/') . '/products';
    }

    public function index()
    {
        $response = $this->remoteHttp()->get($this->getApiBase());
        $data = $response->json();
        $productos = $data['products'] ?? [];
        return view('productos.index', compact('productos'));
    }

    public function show(string $id)
    {
        $response = $this->remoteHttp()->get($this->getApiBase() . '/' . $id);
        if ($response->failed()) {
            abort(404, 'Producto no encontrado');
        }
        $producto = $response->json();
        return view('productos.show', compact('producto'));
    }
}
