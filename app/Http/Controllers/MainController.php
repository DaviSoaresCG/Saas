<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function loginPage()
    {
        return view('login');
    }

    public function loginSubmit($id)
    {
        //login direto
        $user = User::findOrFail($id);
        if($user){
            Auth::login($user);
            echo "LOGADO COM SUCESSO: ".$user->name;
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
