<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $slug = Request::route('slug');
        if (!$slug) {
            $host = Request::getHost();
            $base = env('APP_DOMAIN');
            $slug = str_replace('.' . $base, '', $host);
            if ($slug == $host) {
                $slug = null;
            }
        }

        if ($slug) {
            $user = User::where('slug', $slug)->first();
            $theme = $user->theme_name;
        } else {
            $theme = session('theme', 'cyber');
        }


        return view('layouts.guest', compact('theme'));
    }
}
