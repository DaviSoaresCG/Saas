<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|min:3',
            'email' => 'required|email|unique:tenants',
            'password' => 'required|max:255|min:3|same:password_confirmation',
            'whatsapp' => 'required|max:255|min:10',
            'password_confirmation' => 'required'
        ], [
            'required' => 'O campo :attribute é obrigatório',
            'email.email' => 'Preencha o email corretamente',
            'whatsapp.min' => 'Mínimo de 10 caracteres',
            'max' => 'Máximo de 255 caracteres',
            'min' => 'Mínimo de 3 caracteres',
            'password.same' => 'As senhas não batem'
        ]);
    }
}
