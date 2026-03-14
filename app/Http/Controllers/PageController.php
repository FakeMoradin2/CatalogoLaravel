<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function inicio()
    {
        return view('inicio');
    }

    public function nosotros()
    {
        return view('nosotros');
    }

    public function contacto()
    {
        return view('contacto');
    }

    public function enviarContacto(Request $request)
    {
        $validado = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string|max:5000',
        ]);

        // Aquí puedes agregar el envío de email, guardar en BD, etc.
        // Por ejemplo: Mail::to('contacto@micatalogo.com')->send(new ContactoMailable($validado));

        return redirect()->route('contacto')
            ->with('success', '¡Gracias por tu mensaje! Te responderemos pronto.');
    }
}
