<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $slug = $user->slug;
        $theme_atual = $user->theme_name;
        $themes = [
            ['id' => 'clean', 'name' => 'Minimalista', 'primary' => '#1a1a1a', 'bg' => '#f8f8f8', 'dark' => false],
            ['id' => 'ocean', 'name' => 'Oceano', 'primary' => '#0ea5e9', 'bg' => '#f1f5f9', 'dark' => false],
            ['id' => 'sunset', 'name' => 'Sunset', 'primary' => '#f97316', 'bg' => '#fffbeb', 'dark' => false],
            ['id' => 'midnight', 'name' => 'Midnight', 'primary' => '#a855f7', 'bg' => '#0f172a', 'dark' => true],
            ['id' => 'forest', 'name' => 'Floresta', 'primary' => '#166534', 'bg' => '#f0fdf4', 'dark' => false],
            ['id' => 'premium', 'name' => 'Premium Luxo', 'primary' => '#d4af37', 'bg' => '#171717', 'dark' => true],
            ['id' => 'lavender', 'name' => 'Lavender', 'primary' => '#a78bfa', 'bg' => '#fdf2f8', 'dark' => false],
            ['id' => 'terracotta', 'name' => 'Terracotta', 'primary' => '#b45309', 'bg' => '#fdf8f6', 'dark' => false],
            ['id' => 'cyber', 'name' => 'Cyber Tech', 'primary' => '#22d3ee', 'bg' => '#0f172a', 'dark' => true],
        ];

        return view('admin.change_theme', compact('themes', 'theme_atual'));
    }

    public function themeUpdate(Request $request)
    {
        $request->validate([
            'theme_id' => 'required',
        ]);

        $user = auth()->user();
        $user->theme_name = $request->theme_id;
        $user->save();

        return redirect()->route('theme.index', ['slug' => $user->slug]);
    }
}
