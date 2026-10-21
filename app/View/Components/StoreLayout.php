<?php

namespace App\View\Components;

use App\Models\Catalogo;
use App\Models\User;
use Illuminate\View\Component;
use Illuminate\View\View;

class StoreLayout extends Component
{
    public string $storeName;
    public ?string $catalogoName = null;
    public ?string $logoUrl = null;
    public string $tabTitle;
    public bool $mostrarNome = true;

    public function __construct(
        public ?string $pageTitle = null,
        public ?string $catalogoHash = null,
    ) {
        $tenantUser = app(User::class);
        $this->storeName = $tenantUser->store_name ?? 'Loja';
        $this->logoUrl = $tenantUser->logo_url ?? null;

        // Identifica se há um catálogo variante ativo (via prop, rota ou sessão)
        $hash = $this->catalogoHash ?? request()->route('hash') ?? session('catalog_hash');

        if ($hash) {
            $this->catalogoName = session('catalog_nome');
            $this->mostrarNome = session()->has('catalog_mostrar_nome')
                ? (bool) session('catalog_mostrar_nome')
                : true;

            if (empty($this->catalogoName)) {
                $catalogo = Catalogo::where('hash', $hash)->first();
                $this->catalogoName = $catalogo?->nome;
                $this->mostrarNome = (bool) ($catalogo?->mostrar_nome ?? true);
            }
        }

        // Título dinâmico para a guia do navegador
        if (!empty($this->catalogoName) && $this->mostrarNome) {
            $this->tabTitle = "{$this->catalogoName} - {$this->storeName}";
        } elseif (!empty($this->pageTitle)) {
            $this->tabTitle = "{$this->pageTitle} - {$this->storeName}";
        } else {
            $this->tabTitle = "{$this->storeName} - Catálogo Online";
        }
    }

    public function render(): View
    {
        $tenantUser = app(User::class);
        $theme = $tenantUser->theme_name;
        $modalCarrinho = (bool) ($tenantUser->modal_carrinho ?? false);
        $storeLogo = $this->logoUrl;
        $tabTitle = $this->tabTitle;
        return view('components.store-layout', compact('theme', 'modalCarrinho', 'storeLogo', 'tabTitle'));
    }
}
