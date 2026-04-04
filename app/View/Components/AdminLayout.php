<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;
use Illuminate\View\View;

class AdminLayout extends Component
{
    public string $catalogUrl;

    public function __construct(
        public string $active = 'dashboard',
        public ?string $title = null,
        public ?string $subtitle = null,
        public bool $showBackBar = true,
    ) {
        $tenant = app(User::class);
        $this->catalogUrl = 'https://'.$tenant->slug.'.'.env('APP_DOMAIN');
    }

    public function render(): View
    {
        return view('components.admin-layout');
    }
}
