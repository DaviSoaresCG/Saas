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
        public ?string $title = null,
    ) {

    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $tenant = app(User::class);
        $theme = $tenant->theme_name ?? 'default';
        $logoUrl = $this->logoUrl ?? $tenant->logo_url ?? null;
        $title = $this->title ?? ($tenant->store_name ? $tenant->store_name . ' - Catálogo Online' : config('app.name', 'ZapCatálogo'));

        return view('layouts.app', compact('theme', 'logoUrl', 'title'));
    }
}
