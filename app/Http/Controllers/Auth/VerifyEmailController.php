<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);
        //verificar se o email da url bate com o do usuario
        if(! hash_equals((string) $hash, sha1($user->getEmailForVerification()))){
            abort(403, "Código de verificação inválido ou expirado");
        }

        //verifica se ja verificou o email 
        if($user->hasVerifiedEmail()){
            return redirect()->route('login')->with('warning', "Vocẽ já verificou o email, faça o login");
        }

        $user->markEmailAsVerified();
        event(new Verified($user));
        

        if(auth()->check()){
            return view('auth.verify-email-success', ['user' => $user]);
        }

        return redirect()->route('login')->with('success', "Email Verificado com sucesso! Faça o seu login.");

    }
}
