<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;
use Illuminate\View\View;

class AdminLayout extends Component
{
    public string $catalogUrl;
    public string $tabTitle;

    public function __construct(
        public string $active = 'dashboard',
        public ?string $title = null,
        public ?string $subtitle = null,
        public bool $showBackBar = true,
    ) {
        $tenant = app(User::class);
        $baseDomain = config('app.domain', env('APP_DOMAIN', 'lvh.me'));
        $this->catalogUrl = 'https://'.$tenant->slug.'.'.$baseDomain;
        $storeName = $tenant->store_name ?? 'Painel';

        $this->tabTitle = !empty($this->title)
            ? "{$this->title} - {$storeName}"
            : "Painel Administrativo - {$storeName}";
    }

    public function render(): View
    {
        $tenant = app(User::class);
        $theme = $tenant->theme_name;
        $logoUrl = $tenant->logo_url;
        $tabTitle = $this->tabTitle;
        return view('components.admin-layout', compact('theme', 'logoUrl', 'tabTitle'));
    }
}
