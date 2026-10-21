<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LayoutController extends Controller
{
    /**
     * Display the mobile layout configuration page.
     */
    public function index()
    {
        $user = Auth::user();
        $colunas_mobile = (int) ($user->colunas_mobile ?? 1);

        return view('admin.layout_mobile', compact('colunas_mobile'));
    }

    /**
     * Update the mobile layout configuration.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'colunas_mobile' => 'required|integer|in:1,2',
        ], [
            'colunas_mobile.required' => 'Selecione a quantidade de colunas.',
            'colunas_mobile.in' => 'A opção de colunas mobile deve ser 1 ou 2.',
        ]);

        $user = Auth::user();
        $user->colunas_mobile = (int) $validated['colunas_mobile'];
        $user->save();

        return redirect()->route('layout.index', ['slug' => $user->slug])
            ->with('success', 'Layout mobile atualizado com sucesso!');
    }
}
