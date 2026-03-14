<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = session('carrito', []);
        return view('carrito.index', compact('carrito'));
    }

    public function agregar(Request $request)
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

        return redirect()->route('carrito.index')->with('success', 'Producto agregado al carrito.');
    }

    public function actualizar(Request $request, string $id)
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

        return redirect()->route('carrito.index')->with('success', 'Cantidad actualizada.');
    }

    public function eliminar(string $id)
    {
        $carrito = session('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session(['carrito' => $carrito]);
        }

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function vaciar()
    {
        session()->forget('carrito');

        return redirect()->route('carrito.index')->with('success', 'Carrito vaciado.');
    }
}
