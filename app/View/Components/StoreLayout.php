<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;
use Illuminate\View\View;

class StoreLayout extends Component
{
    public string $storeName;
    public ?string $logoUrl = null;

    public function __construct(
        public ?string $pageTitle = null,
    ) {
        $tenantUser = app(User::class);
        $this->storeName = $tenantUser->store_name ?? 'Loja';
        $this->logoUrl = $tenantUser->logo_url ?? null;
    }

    public function render(): View
    {
        $tenantUser = app(User::class);
        $theme = $tenantUser->theme_name;
        $modalCarrinho = (bool) ($tenantUser->modal_carrinho ?? false);
        $storeLogo = $this->logoUrl;
        return view('components.store-layout', compact('theme', 'modalCarrinho', 'storeLogo'));
    }
}
