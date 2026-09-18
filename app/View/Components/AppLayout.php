<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public function __construct(
        public bool $adminShell = false,
        public ?string $logoUrl = null,
    ) {

    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $theme = app(User::class)->theme_name;
        $logoUrl = $this->logoUrl ?? app(User::class)->logo_url;

        return view('layouts.app', compact('theme', 'logoUrl'));
    }
}
