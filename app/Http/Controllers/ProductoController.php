<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductoController extends Controller
{
    private function getApiBase(): string
    {
        return rtrim(config('services.productos_api.url', 'http://127.0.0.1:8001/api'), '/') . '/products';
    }

    public function index()
    {
        $response = Http::get($this->getApiBase());
        $data = $response->json();
        $productos = $data['products'] ?? [];
        return view('productos.index', compact('productos'));
    }

    public function show(string $id)
    {
        $response = Http::get($this->getApiBase() . '/' . $id);
        if ($response->failed()) {
            abort(404, 'Producto no encontrado');
        }
        $producto = $response->json();
        return view('productos.show', compact('producto'));
    }
}
