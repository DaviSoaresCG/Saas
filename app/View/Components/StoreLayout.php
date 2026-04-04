<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;
use Illuminate\View\View;

class StoreLayout extends Component
{
    public string $storeName;

    public function __construct(
        public ?string $pageTitle = null,
    ) {
        $this->storeName = app(User::class)->store_name ?? 'Loja';
    }

    public function render(): View
    {
        return view('components.store-layout');
    }
}
