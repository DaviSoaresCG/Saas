<x-superadmin-layout title="Painel Super Admin - Usuários e Lojas">
    <div class="space-y-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        <i data-lucide="shield-alert" class="h-3 w-3"></i>
                        Acesso Restrito
                    </span>
                    <span class="text-xs text-slate-500">davisoaresgigante@gmail.com</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Painel Geral do Super Admin
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Gerencie todos os lojistas cadastrados, visualize métricas e execute ações de limpeza/exclusão em massa.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('counpons.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-purple-600 hover:bg-purple-500 shadow-lg shadow-purple-600/25 transition-all">
                    <i data-lucide="ticket" class="h-4 w-4"></i>
                    Gerenciar Cupons
                </a>
            </div>
        </div>

        <!-- Global Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Lojas -->
            <div class="p-5 rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm relative overflow-hidden group hover:border-slate-700 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total de Lojas</p>
                        <p class="text-3xl font-black text-white mt-1.5 tabular-nums">{{ $stats['total_users'] }}</p>
                        <div class="flex items-center gap-2 mt-2 text-[11px] text-slate-400">
                            <span class="inline-flex items-center gap-1 text-blue-400"><i data-lucide="user" class="h-3 w-3"></i> {{ $stats['total_direct'] }} Diretos</span>
                            <span class="text-slate-600">•</span>
                            <span class="inline-flex items-center gap-1 text-purple-400"><i data-lucide="cpu" class="h-3 w-3"></i> {{ $stats['total_erp'] }} ERP</span>
                        </div>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="store" class="h-6 w-6"></i>
                    </div>
                </div>
            </div>

            <!-- Total Produtos -->
            <div class="p-5 rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm relative overflow-hidden group hover:border-slate-700 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Produtos no Sistema</p>
                        <p class="text-3xl font-black text-white mt-1.5 tabular-nums">{{ $stats['total_products'] }}</p>
                        <p class="text-[11px] text-slate-500 mt-2">Em todas as lojas cadastradas</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center shrink-0">
                        <i data-lucide="package" class="h-6 w-6"></i>
                    </div>
                </div>
            </div>

            <!-- Total Pedidos -->
            <div class="p-5 rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm relative overflow-hidden group hover:border-slate-700 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Pedidos Realizados</p>
                        <p class="text-3xl font-black text-white mt-1.5 tabular-nums">{{ $stats['total_pedidos'] }}</p>
                        <p class="text-[11px] text-slate-500 mt-2">Total de pedidos gerados</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                        <i data-lucide="shopping-bag" class="h-6 w-6"></i>
                    </div>
                </div>
            </div>

            <!-- Total Categorias & Catálogos -->
            <div class="p-5 rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm relative overflow-hidden group hover:border-slate-700 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Grupos e Catálogos</p>
                        <p class="text-3xl font-black text-white mt-1.5 tabular-nums">{{ $stats['total_grupos'] + $stats['total_catalogos'] }}</p>
                        <div class="flex items-center gap-2 mt-2 text-[11px] text-slate-400">
                            <span>{{ $stats['total_grupos'] }} Grupos</span>
                            <span class="text-slate-600">•</span>
                            <span>{{ $stats['total_catalogos'] }} Catálogos</span>
                        </div>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                        <i data-lucide="layers" class="h-6 w-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="p-5 rounded-2xl border border-slate-800 bg-slate-900/40 backdrop-blur-md">
            <form method="GET" action="{{ route('superadmin.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                <!-- Search text -->
                <div class="lg:col-span-5">
                    <label for="search" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
                        Buscar Lojista / Loja
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i data-lucide="search" class="h-4 w-4"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ $search }}"
                               placeholder="Nome, e-mail, nome da loja, slug ou WhatsApp..."
                               class="w-full pl-10 pr-4 py-2 text-xs rounded-xl bg-slate-950/80 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Tipo de Cliente -->
                <div class="lg:col-span-3">
                    <label for="tipo_cliente" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
                        Tipo de Cliente
                    </label>
                    <select name="tipo_cliente" id="tipo_cliente"
                            class="w-full px-3 py-2 text-xs rounded-xl bg-slate-950/80 border border-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Todos os tipos</option>
                        <option value="direct" {{ $tipoCliente === 'direct' ? 'selected' : '' }}>Cliente Direto (Pix)</option>
                        <option value="erp" {{ $tipoCliente === 'erp' ? 'selected' : '' }}>Integrado ao ERP</option>
                    </select>
                </div>

                <!-- Status -->
                <div class="lg:col-span-2">
                    <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">
                        Status da Conta
                    </label>
                    <select name="status" id="status"
                            class="w-full px-3 py-2 text-xs rounded-xl bg-slate-950/80 border border-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Todos os status</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Ativa</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inativa</option>
                        <option value="suspended" {{ $status === 'suspended' ? 'selected' : '' }}>Suspensa</option>
                    </select>
                </div>

                <!-- Action buttons -->
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-purple-600 hover:bg-purple-500 transition-colors shadow-md cursor-pointer">
                        <i data-lucide="filter" class="h-3.5 w-3.5"></i>
                        Filtrar
                    </button>
                    @if(!empty($search) || !empty($tipoCliente) || !empty($status))
                        <a href="{{ route('superadmin.index') }}"
                           class="inline-flex items-center justify-center px-3 py-2 rounded-xl text-xs font-semibold text-slate-400 bg-slate-800 hover:bg-slate-700 hover:text-white transition-colors"
                           title="Limpar filtros">
                            <i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Users Table Card -->
        <div class="rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm overflow-hidden shadow-2xl">
            <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <i data-lucide="users" class="h-4 w-4 text-purple-400"></i>
                        Lista de Lojas e Usuários
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Clique em <strong class="text-slate-300">"Gerenciar Dados"</strong> para excluir produtos, pedidos, etc. de um usuário específico.</p>
                </div>
                <span class="text-xs text-slate-400 tabular-nums font-medium">
                    Mostrando {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} de {{ $users->total() }} lojas
                </span>
            </div>

            @if($users->isEmpty())
                <div class="py-16 text-center">
                    <div class="h-16 w-16 mx-auto rounded-full bg-slate-800/80 flex items-center justify-center text-slate-500 mb-3">
                        <i data-lucide="search-x" class="h-8 w-8"></i>
                    </div>
                    <p class="text-base font-bold text-slate-300">Nenhum lojista encontrado</p>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Tente alterar os termos da busca ou limpar os filtros de tipo e status.</p>
                    <a href="{{ route('superadmin.index') }}" class="inline-flex items-center gap-1.5 mt-4 text-xs font-semibold text-purple-400 hover:text-purple-300">
                        <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i>
                        Limpar todos os filtros
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-950/60 text-slate-400 uppercase tracking-wider text-[10px] border-b border-slate-800">
                            <tr>
                                <th class="py-3 px-4 font-semibold">Loja / Responsável</th>
                                <th class="py-3 px-4 font-semibold">Tipo &amp; Status</th>
                                <th class="py-3 px-4 font-semibold">Dados Cadastrados</th>
                                <th class="py-3 px-4 font-semibold">Data Cadastro</th>
                                <th class="py-3 px-4 font-semibold text-right">Ações de Gestão</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach($users as $u)
                                @php
                                    $isSuperAdm = $u->isSuperAdmin();
                                    $isAtiva = $u->isLojaAtiva();
                                    $publicUrl = $u->slug ? 'http://' . $u->slug . '.' . env('APP_DOMAIN', 'lvh.me') : null;
                                @endphp
                                <tr class="hover:bg-slate-800/30 transition-colors {{ $isSuperAdm ? 'bg-amber-500/[0.02]' : '' }}">
                                    <!-- Loja e Usuário -->
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            @if($u->logo_url)
                                                <img src="{{ $u->logo_url }}" alt="{{ $u->store_name }}"
                                                     class="h-10 w-10 rounded-xl object-cover border border-slate-700 shrink-0">
                                            @else
                                                <div class="h-10 w-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-300 font-bold shrink-0 text-sm">
                                                    {{ strtoupper(substr($u->store_name ?? $u->name ?? 'L', 0, 2)) }}
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="font-bold text-white text-sm truncate">{{ $u->store_name ?? 'Sem Nome de Loja' }}</span>
                                                    @if($isSuperAdm)
                                                        <span class="text-[9px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                                            👑 Super Adm
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2 mt-0.5 text-slate-400 text-xs">
                                                    <span class="text-slate-300">{{ $u->name }}</span>
                                                    <span>•</span>
                                                    <span class="font-mono text-purple-300">{{ $u->slug ?? 'sem-slug' }}</span>
                                                </div>
                                                <div class="flex items-center gap-3 mt-1 text-[11px] text-slate-500">
                                                    <span class="flex items-center gap-1"><i data-lucide="mail" class="h-3 w-3"></i> {{ $u->email }}</span>
                                                    @if($u->whatsapp)
                                                        <span class="flex items-center gap-1"><i data-lucide="phone" class="h-3 w-3"></i> {{ $u->whatsapp }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Tipo & Status -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            <div>
                                                @if($u->tipo_cliente === 'erp')
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-300 border border-purple-500/20">
                                                        <i data-lucide="cpu" class="h-3 w-3"></i> Integrado ERP
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-300 border border-blue-500/20">
                                                        <i data-lucide="credit-card" class="h-3 w-3"></i> Direto (Pix)
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                @if($isAtiva)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Loja Ativa
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-300 border border-rose-500/20">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span> Inativa / Vencida
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Contadores de Dados -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="grid grid-cols-2 gap-x-3 gap-y-1 text-[11px]">
                                            <span class="inline-flex items-center gap-1 text-slate-300">
                                                <i data-lucide="package" class="h-3 w-3 text-purple-400"></i>
                                                <strong class="text-white">{{ $u->products_count }}</strong> produtos
                                            </span>
                                            <span class="inline-flex items-center gap-1 text-slate-300">
                                                <i data-lucide="shopping-bag" class="h-3 w-3 text-emerald-400"></i>
                                                <strong class="text-white">{{ $u->pedidos_count }}</strong> pedidos
                                            </span>
                                            <span class="inline-flex items-center gap-1 text-slate-400">
                                                <i data-lucide="layers" class="h-3 w-3 text-amber-400"></i>
                                                <span>{{ $u->grupos_count }} grupos</span>
                                            </span>
                                            <span class="inline-flex items-center gap-1 text-slate-400">
                                                <i data-lucide="folder" class="h-3 w-3 text-blue-400"></i>
                                                <span>{{ $u->catalogos_count }} catálogos</span>
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Data de Cadastro -->
                                    <td class="py-3.5 px-4 whitespace-nowrap text-slate-400">
                                        <span>{{ $u->created_at ? $u->created_at->format('d/m/Y H:i') : '-' }}</span>
                                    </td>

                                    <!-- Ações -->
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5">
                                            @if($publicUrl)
                                                <a href="{{ $publicUrl }}" target="_blank"
                                                   class="p-2 rounded-xl text-slate-400 hover:text-white bg-slate-800/80 hover:bg-slate-700 transition-colors"
                                                   title="Abrir Loja Pública em nova aba">
                                                    <i data-lucide="external-link" class="h-4 w-4"></i>
                                                </a>
                                            @endif

                                            <a href="{{ route('superadmin.users.show', $u) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-md shadow-purple-600/20 transition-all hover:scale-[1.02]">
                                                <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                Gerenciar Dados
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/40">
                        {{ $users->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
</x-superadmin-layout>
