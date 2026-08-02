<?php

namespace App\Http\Controllers;

use App\Models\Counpons;
use App\Models\User;
use Illuminate\Http\Request;

class CounponsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->email !== 'davisoaresgigante@gmail.com'){
            abort(403);
        }
        $counpons = Counpons::all();
        return view('counpons.index', compact('counpons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->user()->email !== 'davisoaresgigante@gmail.com'){
            abort(403);
        }
        return view('counpons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->user()->email !== 'davisoaresgigante@gmail.com'){
            abort(403);
        }
        $request->validate([
            'code' => 'required|string|max:255|unique:counpons',
            'description' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        $counpon = Counpons::create([
            'code' => strtoupper(trim($request->code)),
            'description' => $request->description,
            'active' => (int) $request->active,
        ]);

        return redirect()->route('counpons.index')->with('success', 'Cupom criado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Counpons $counpon)
    {
        if(auth()->user()->email !== 'davisoaresgigante@gmail.com'){
            abort(403);
        }
        return view('counpons.edit', compact('counpon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Counpons $counpon)
    {
        if(auth()->user()->email !== 'davisoaresgigante@gmail.com'){
            abort(403);
        }
        $request->validate([
            'code' => 'required|string|max:255|unique:counpons,code,' . $counpon->id,
            'description' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        $counpon->update([
            'code' => strtoupper(trim($request->code)),
            'description' => $request->description,
            'active' => (int) $request->active,
        ]);

        return redirect()->route('counpons.index')->with('success', 'Cupom atualizado com sucesso!');
    }

    public function validar(Request $request)
    {
        $request->validate([
            'code' => 'string|required|max:20',
        ]);

        $counpon = Counpons::where('code', mb_strtoupper(trim($request->code), 'UTF-8'))->first();

        if($counpon){
           $user = auth()->user();
           $user->slug = $this->generateUniqueSlug($user->nome_loja);
           $user->tipo_cliente = 'erp';
           $user->status = 'active';
           $user->save();
           return redirect()->route('subscription.success');
        }

        return redirect()->route('checkout.index')->with('error', 'Cupom inválido!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Counpons $counpon)
    {
        if(auth()->user()->email !== 'davisoaresgigante@gmail.com'){
            abort(403);
        }
        $counpon->delete();
        return redirect()->route('counpons.index')->with('success', 'Cupom removido com sucesso!');
    }

    public function generateUniqueSlug($slug)
    {
        $count = User::where('slug', 'LIKE', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
