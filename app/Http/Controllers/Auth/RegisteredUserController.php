<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required','min:8', 'max:255', 'confirmed', Rules\Password::defaults()],
            'store_name' => ['required', 'string', 'max:255'],
        ], [
            'required' => 'Este campo é obrigatório',
            'whatsapp.max' => 'Máximo de 20 caracteres',
            'name.max' => 'Máximo de 255 caracteres',
            'email.email' => "Digite um endereço de email válido",
            'email.max' => 'Máximo de 255 caracteres',
            'string' => 'Nao digite apenas numeros',
            'password.min' => 'Mínimo de caracteres: 8',
            'password.max' => 'Máximo de caracteres: 255',
            'password.confirmed' => 'As senhas não são iguais'
        ]);

        $whatsapp_limpo = preg_replace('/\D/', '', $request->whatsapp);

        $user = User::create([
            'name' => $request->name,            
            'whatsapp' => $whatsapp_limpo,
            'email' => $request->email,
            'store_name' => $request->store_name,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
