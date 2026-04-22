<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public function __construct(
        public bool $adminShell = false,
    ) {

    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $theme = app(User::class)->theme_name;

        return view('layouts.app', compact('theme'));
    }
}
