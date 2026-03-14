<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductoController extends Controller
{
    private const API_BASE = 'https://dummyjson.com/products';

    public function index()
    {
        $response = Http::get(self::API_BASE);
        $data = $response->json();
        $productos = $data['products'] ?? [];
        return view('productos.index', compact('productos'));
    }

    public function show(string $id)
    {
        $response = Http::get(self::API_BASE . '/' . $id);
        if ($response->failed()) {
            abort(404, 'Producto no encontrado');
        }
        $producto = $response->json();
        return view('productos.show', compact('producto'));
    }
}
