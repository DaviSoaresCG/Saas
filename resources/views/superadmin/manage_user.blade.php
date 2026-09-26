<x-superadmin-layout title="Gerenciar Dados: {{ $user->store_name ?? $user->name }}">
    <div class="space-y-8" x-data="{
        modalOpen: false,
        modalTitle: '',
        modalMessage: '',
        modalCount: 0,
        modalFormAction: '',
        modalRequireTyping: false,
        confirmInput: '',
        openModal(title, message, count, action, requireTyping = false) {
            this.modalTitle = title;
            this.modalMessage = message;
            this.modalCount = count;
            this.modalFormAction = action;
            this.modalRequireTyping = requireTyping;
            this.confirmInput = '';
            this.modalOpen = true;
        }
    }">

        <!-- Top Breadcrumbs & Back Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <nav class="flex items-center gap-2 text-xs text-slate-400">
                <a href="{{ route('superadmin.index') }}" class="hover:text-white transition-colors flex items-center gap-1">
                    <i data-lucide="crown" class="h-3.5 w-3.5 text-amber-400"></i>
                    Super Admin
                </a>
                <i data-lucide="chevron-right" class="h-3.5 w-3.5 text-slate-600"></i>
                <a href="{{ route('superadmin.index') }}" class="hover:text-white transition-colors">Lojas</a>
                <i data-lucide="chevron-right" class="h-3.5 w-3.5 text-slate-600"></i>
                <span class="text-slate-200 font-semibold truncate">{{ $user->store_name ?? $user->name }}</span>
            </nav>

            <a href="{{ route('superadmin.index') }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-300 bg-slate-800/80 hover:bg-slate-700/80 border border-slate-700 hover:text-white transition-all w-fit">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Voltar à Lista de Lojas
            </a>
        </div>

        <!-- Store Overview Header Card -->
        <div class="p-6 rounded-3xl border border-slate-800 bg-slate-900/60 backdrop-blur-md relative overflow-hidden shadow-2xl">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <!-- Left: Store info -->
                <div class="flex items-start sm:items-center gap-4">
                    @if($user->logo_url)
                        <img src="{{ $user->logo_url }}" alt="{{ $user->store_name }}"
                             class="h-16 w-16 sm:h-20 sm:w-20 rounded-2xl object-cover border-2 border-slate-700 shrink-0 shadow-lg">
                    @else
                        <div class="h-16 w-16 sm:h-20 sm:w-20 rounded-2xl bg-gradient-to-tr from-purple-700 to-indigo-700 border-2 border-slate-700 flex items-center justify-center text-white font-black text-2xl shrink-0 shadow-lg">
                            {{ strtoupper(substr($user->store_name ?? $user->name ?? 'L', 0, 2)) }}
                        </div>
                    @endif
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                                {{ $user->store_name ?? 'Sem Nome de Loja' }}
                            </h1>
                            @if($user->isSuperAdmin())
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                    👑 Super Admin
                                </span>
                            @endif
                            @if($user->isLojaAtiva())
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Loja Ativa
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/10 text-rose-300 border border-rose-500/20">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span> Inativa
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 mt-2 flex-wrap text-xs text-slate-300">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="user" class="h-3.5 w-3.5 text-slate-500"></i>
                                <strong>{{ $user->name }}</strong>
                            </span>
                            <span class="text-slate-600">•</span>
                            <span class="flex items-center gap-1.5 text-slate-400 font-mono">
                                <i data-lucide="globe" class="h-3.5 w-3.5 text-slate-500"></i>
                                {{ $user->slug ?? 'sem-slug' }}
                            </span>
                            <span class="text-slate-600">•</span>
                            <span class="flex items-center gap-1.5 text-slate-400">
                                <i data-lucide="mail" class="h-3.5 w-3.5 text-slate-500"></i>
                                {{ $user->email }}
                            </span>
                            @if($user->whatsapp)
                                <span class="text-slate-600">•</span>
                                <span class="flex items-center gap-1.5 text-slate-400">
                                    <i data-lucide="phone" class="h-3.5 w-3.5 text-emerald-400"></i>
                                    {{ $user->whatsapp }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 mt-3 text-[11px] text-slate-400 flex-wrap">
                            <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 font-medium">
                                Tipo: {{ $user->tipo_cliente === 'erp' ? 'Integrado ERP' : 'Cliente Direto' }}
                            </span>
                            @if($user->documento)
                                <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 font-mono">
                                    Doc: {{ $user->documento }}
                                </span>
                            @endif
                            @if($user->plano_expira_em)
                                <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300">
                                    Validade: {{ $user->plano_expira_em->format('d/m/Y H:i') }}
                                </span>
                            @endif
                            <span class="text-slate-500">Cadastrado em {{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right: External store link -->
                @if($user->slug)
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="http://{{ $user->slug }}.{{ env('APP_DOMAIN', 'lvh.me') }}" target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 shadow-md transition-all">
                            <i data-lucide="external-link" class="h-4 w-4 text-blue-400"></i>
                            Ver Loja no Ar
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <!-- Produtos -->
            <div class="p-4 rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Produtos</span>
                    <i data-lucide="package" class="h-4 w-4 text-purple-400"></i>
                </div>
                <p class="text-2xl font-black text-white mt-1 tabular-nums">{{ $counts['products'] }}</p>
                <span class="text-[10px] text-slate-500">Cadastrados</span>
            </div>

            <!-- Pedidos -->
            <div class="p-4 rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pedidos</span>
                    <i data-lucide="shopping-bag" class="h-4 w-4 text-emerald-400"></i>
                </div>
                <p class="text-2xl font-black text-white mt-1 tabular-nums">{{ $counts['pedidos'] }}</p>
                <span class="text-[10px] text-emerald-400 font-semibold truncate block">R$ {{ number_format($counts['total_revenue'], 2, ',', '.') }}</span>
            </div>

            <!-- Grupos -->
            <div class="p-4 rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Grupos</span>
                    <i data-lucide="layers" class="h-4 w-4 text-amber-400"></i>
                </div>
                <p class="text-2xl font-black text-white mt-1 tabular-nums">{{ $counts['grupos'] }}</p>
                <span class="text-[10px] text-slate-500">Categorias</span>
            </div>

            <!-- Catálogos -->
            <div class="p-4 rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Catálogos</span>
                    <i data-lucide="folder" class="h-4 w-4 text-blue-400"></i>
                </div>
                <p class="text-2xl font-black text-white mt-1 tabular-nums">{{ $counts['catalogos'] }}</p>
                <span class="text-[10px] text-slate-500">Promocionais</span>
            </div>

            <!-- Atributos -->
            <div class="p-4 rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Atributos</span>
                    <i data-lucide="tag" class="h-4 w-4 text-rose-400"></i>
                </div>
                <p class="text-2xl font-black text-white mt-1 tabular-nums">{{ $counts['atributos'] }}</p>
                <span class="text-[10px] text-slate-500">Variações</span>
            </div>

            <!-- Cliques -->
            <div class="p-4 rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Visualizações</span>
                    <i data-lucide="eye" class="h-4 w-4 text-indigo-400"></i>
                </div>
                <p class="text-2xl font-black text-white mt-1 tabular-nums">{{ $counts['clicks'] }}</p>
                <span class="text-[10px] text-slate-500">Cliques totais</span>
            </div>
        </div>

        <!-- ZONE OF DELETION & PURGE (ZONA DE EXCLUSÃO E LIMPEZA) -->
        <div class="rounded-3xl border border-rose-900/40 bg-gradient-to-b from-rose-950/20 via-slate-900/40 to-slate-900/60 p-6 sm:p-8 backdrop-blur-md shadow-2xl relative">
            <div class="flex items-center gap-3 mb-2">
                <div class="h-10 w-10 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center justify-center shrink-0">
                    <i data-lucide="flame" class="h-6 w-6"></i>
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-black text-rose-100">
                        Zona de Exclusão e Limpeza de Dados
                    </h2>
                    <p class="text-xs text-rose-300/80">
                        Como Super Administrador, você pode excluir coleções de dados específicas ou zerar completamente a loja deste usuário.
                    </p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                <!-- 1. Excluir Todos os Produtos -->
                <div class="p-5 rounded-2xl border border-slate-800 bg-slate-950/60 flex flex-col justify-between hover:border-rose-500/40 transition-colors">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="h-9 w-9 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center">
                                <i data-lucide="package-x" class="h-5 w-5"></i>
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300 tabular-nums">
                                {{ $counts['products'] }} cadastrados
                            </span>
                        </div>
                        <h3 class="text-sm font-bold text-white">Excluir Todos os Produtos</h3>
                        <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">
                            Apaga todos os produtos deste lojista, deleta as fotos físicas armazenadas no disco, desvincula categorias e zera cliques.
                        </p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-900">
                        <button type="button"
                                @click="openModal('Excluir Todos os Produtos', 'Deseja realmente excluir todos os {{ $counts['products'] }} produto(s) de \'{{ $user->store_name }}\'? Todas as fotos associadas no disco serão permanentemente removidas.', {{ $counts['products'] }}, '{{ route('superadmin.users.delete-products', $user) }}')"
                                @disabled($counts['products'] === 0)
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-rose-200 bg-rose-950/60 hover:bg-rose-900/60 border border-rose-800/60 disabled:opacity-40 disabled:cursor-not-allowed transition-all cursor-pointer">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                            Excluir Produtos ({{ $counts['products'] }})
                        </button>
                    </div>
                </div>

                <!-- 2. Excluir Todos os Pedidos -->
                <div class="p-5 rounded-2xl border border-slate-800 bg-slate-950/60 flex flex-col justify-between hover:border-rose-500/40 transition-colors">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="h-9 w-9 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                                <i data-lucide="shopping-bag" class="h-5 w-5"></i>
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300 tabular-nums">
                                {{ $counts['pedidos'] }} pedidos
                            </span>
                        </div>
                        <h3 class="text-sm font-bold text-white">Excluir Todos os Pedidos</h3>
                        <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">
                            Remove todo o histórico de pedidos e itens de pedidos registrados pelos clientes nesta loja.
                        </p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-900">
                        <button type="button"
                                @click="openModal('Excluir Todos os Pedidos', 'Deseja realmente excluir todos os {{ $counts['pedidos'] }} pedido(s) de \'{{ $user->store_name }}\'? Todos os itens de pedido e dados de clientes associados serão deletados.', {{ $counts['pedidos'] }}, '{{ route('superadmin.users.delete-pedidos', $user) }}')"
                                @disabled($counts['pedidos'] === 0)
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-rose-200 bg-rose-950/60 hover:bg-rose-900/60 border border-rose-800/60 disabled:opacity-40 disabled:cursor-not-allowed transition-all cursor-pointer">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                            Excluir Pedidos ({{ $counts['pedidos'] }})
                        </button>
                    </div>
                </div>

                <!-- 3. Excluir Todos os Grupos (Categorias) -->
                <div class="p-5 rounded-2xl border border-slate-800 bg-slate-950/60 flex flex-col justify-between hover:border-rose-500/40 transition-colors">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="h-9 w-9 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                                <i data-lucide="layers" class="h-5 w-5"></i>
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300 tabular-nums">
                                {{ $counts['grupos'] }} grupos
                            </span>
                        </div>
                        <h3 class="text-sm font-bold text-white">Excluir Todos os Grupos</h3>
                        <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">
                            Remove todos os grupos de categorias criados pelo lojista, além de apagar suas respectivas imagens do armazenamento.
                        </p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-900">
                        <button type="button"
                                @click="openModal('Excluir Grupos / Categorias', 'Deseja realmente excluir todas as {{ $counts['grupos'] }} categoria(s) de \'{{ $user->store_name }}\'? As fotos de capa das categorias serão apagadas do disco.', {{ $counts['grupos'] }}, '{{ route('superadmin.users.delete-grupos', $user) }}')"
                                @disabled($counts['grupos'] === 0)
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-rose-200 bg-rose-950/60 hover:bg-rose-900/60 border border-rose-800/60 disabled:opacity-40 disabled:cursor-not-allowed transition-all cursor-pointer">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                            Excluir Grupos ({{ $counts['grupos'] }})
                        </button>
                    </div>
                </div>

                <!-- 4. Excluir Todos os Catálogos Promocionais -->
                <div class="p-5 rounded-2xl border border-slate-800 bg-slate-950/60 flex flex-col justify-between hover:border-rose-500/40 transition-colors">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="h-9 w-9 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
                                <i data-lucide="folder" class="h-5 w-5"></i>
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300 tabular-nums">
                                {{ $counts['catalogos'] }} catálogos
                            </span>
                        </div>
                        <h3 class="text-sm font-bold text-white">Excluir Todos os Catálogos</h3>
                        <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">
                            Remove os links de catálogos promocionais (com hash) e configurações de desconto indexadas.
                        </p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-900">
                        <button type="button"
                                @click="openModal('Excluir Catálogos Promocionais', 'Deseja realmente excluir todos os {{ $counts['catalogos'] }} catálogo(s) de \'{{ $user->store_name }}\'?', {{ $counts['catalogos'] }}, '{{ route('superadmin.users.delete-catalogos', $user) }}')"
                                @disabled($counts['catalogos'] === 0)
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-rose-200 bg-rose-950/60 hover:bg-rose-900/60 border border-rose-800/60 disabled:opacity-40 disabled:cursor-not-allowed transition-all cursor-pointer">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                            Excluir Catálogos ({{ $counts['catalogos'] }})
                        </button>
                    </div>
                </div>

                <!-- 5. Excluir Todos os Atributos -->
                <div class="p-5 rounded-2xl border border-slate-800 bg-slate-950/60 flex flex-col justify-between hover:border-rose-500/40 transition-colors">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="h-9 w-9 rounded-xl bg-pink-500/10 text-pink-400 flex items-center justify-center">
                                <i data-lucide="tag" class="h-5 w-5"></i>
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300 tabular-nums">
                                {{ $counts['atributos'] }} atributos
                            </span>
                        </div>
                        <h3 class="text-sm font-bold text-white">Excluir Todos os Atributos</h3>
                        <p class="text-xs text-slate-400 mt-1.5 leading-relaxed">
                            Remove variações customizadas cadastradas (tamanho, cor, etc.) e desvincula dos produtos.
                        </p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-slate-900">
                        <button type="button"
                                @click="openModal('Excluir Atributos', 'Deseja realmente excluir todos os {{ $counts['atributos'] }} atributo(s) customizado(s) de \'{{ $user->store_name }}\'?', {{ $counts['atributos'] }}, '{{ route('superadmin.users.delete-atributos', $user) }}')"
                                @disabled($counts['atributos'] === 0)
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-rose-200 bg-rose-950/60 hover:bg-rose-900/60 border border-rose-800/60 disabled:opacity-40 disabled:cursor-not-allowed transition-all cursor-pointer">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                            Excluir Atributos ({{ $counts['atributos'] }})
                        </button>
                    </div>
                </div>

                <!-- 6. ZERAR LOJA COMPLETA (Reset Total) -->
                <div class="p-5 rounded-2xl border-2 border-red-600/60 bg-gradient-to-br from-red-950/40 to-slate-950/90 flex flex-col justify-between shadow-xl shadow-red-950/40">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="h-9 w-9 rounded-xl bg-red-600/20 text-red-400 flex items-center justify-center border border-red-500/30">
                                <i data-lucide="bomb" class="h-5 w-5"></i>
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-600 text-white shadow-md">
                                Reset Total
                            </span>
                        </div>
                        <h3 class="text-sm font-black text-white flex items-center gap-1.5">
                            🔥 Zerar Loja Completa
                        </h3>
                        <p class="text-xs text-red-200/80 mt-1.5 leading-relaxed">
                            Limpa TODOS os produtos (+ fotos), pedidos (+ itens), categorias, catálogos, atributos e cliques. A conta do usuário permanece, porém 100% limpa.
                        </p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-red-900/60">
                        <button type="button"
                                @click="openModal('Zerar Loja Completa', 'ATENÇÃO MÁXIMA: Esta ação apagará TODOS os produtos, pedidos, fotos, categorias e catálogos de \'{{ $user->store_name }}\'. Para confirmar, digite ZERAR abaixo:', 0, '{{ route('superadmin.users.wipe', $user) }}', true)"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black text-white bg-red-600 hover:bg-red-500 shadow-lg shadow-red-600/30 transition-all hover:scale-[1.02] cursor-pointer">
                            <i data-lucide="alert-triangle" class="h-4 w-4"></i>
                            Zerar Loja Agora
                        </button>
                    </div>
                </div>

            </div>

            <!-- Opção Secundária: Excluir Usuário Inteiro -->
            @if(!$user->isSuperAdmin())
                <div class="mt-6 pt-6 border-t border-slate-800/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h4 class="text-xs font-bold text-slate-300">Excluir Conta do Usuário Permanentemente</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Remove a conta do usuário, credenciais de login, loja e todos os dados associados da plataforma.</p>
                    </div>
                    <button type="button"
                            @click="openModal('Excluir Conta do Usuário', 'ATENÇÃO: A conta de \'{{ $user->name }}\' ({{ $user->email }}) será excluída permanentemente juntamente com toda a loja. Esta ação não pode ser desfeita. Digite ZERAR para confirmar:', 0, '{{ route('superadmin.users.delete-account', $user) }}', true)"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-rose-400 hover:text-white bg-rose-500/10 hover:bg-rose-600 border border-rose-500/20 transition-all cursor-pointer">
                        <i data-lucide="user-x" class="h-4 w-4"></i>
                        Excluir Conta de Usuário
                    </button>
                </div>
            @endif
        </div>

        <!-- Preview Tables of Current Data -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Produtos Recentes -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i data-lucide="package" class="h-4 w-4 text-purple-400"></i>
                        Amostra de Produtos ({{ $counts['products'] }} total)
                    </h3>
                </div>
                @if($recentProducts->isEmpty())
                    <div class="py-10 text-center text-xs text-slate-500">
                        Nenhum produto cadastrado para este usuário.
                    </div>
                @else
                    <div class="divide-y divide-slate-800/60 max-h-96 overflow-y-auto">
                        @foreach($recentProducts as $prod)
                            <div class="p-3.5 flex items-center justify-between gap-3 hover:bg-slate-800/20 transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img src="{{ $prod->path }}" alt="{{ $prod->nome }}"
                                         class="h-10 w-10 rounded-lg object-cover border border-slate-700 shrink-0">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-white truncate">{{ $prod->nome }}</p>
                                        <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-0.5">
                                            @if($prod->sku) <span>SKU: {{ $prod->sku }}</span> <span>•</span> @endif
                                            <span>Estoque: {{ $prod->estoque ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-xs font-bold text-emerald-400">R$ {{ $prod->preco_base }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Pedidos Recentes -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i data-lucide="shopping-bag" class="h-4 w-4 text-emerald-400"></i>
                        Amostra de Pedidos ({{ $counts['pedidos'] }} total)
                    </h3>
                </div>
                @if($recentPedidos->isEmpty())
                    <div class="py-10 text-center text-xs text-slate-500">
                        Nenhum pedido realizado nesta loja ainda.
                    </div>
                @else
                    <div class="divide-y divide-slate-800/60 max-h-96 overflow-y-auto">
                        @foreach($recentPedidos as $ped)
                            <div class="p-3.5 flex items-center justify-between gap-3 hover:bg-slate-800/20 transition-colors">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-white">#{{ $ped->id }} - {{ $ped->cliente_nome ?? 'Cliente não informado' }}</span>
                                        @if($ped->sync)
                                            <span class="text-[9px] px-1.5 py-0.2 rounded bg-purple-500/20 text-purple-300 font-semibold">ERP</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-0.5">
                                        @if($ped->cliente_phone) <span>{{ $ped->cliente_phone }}</span> <span>•</span> @endif
                                        <span>{{ $ped->created_at ? $ped->created_at->format('d/m/Y H:i') : '-' }}</span>
                                        <span>•</span>
                                        <span>{{ $ped->iten_pedido->count() }} item(ns)</span>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-xs font-black text-emerald-400">R$ {{ $ped->total }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Groups & Catalogs list -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Grupos / Categorias -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i data-lucide="layers" class="h-4 w-4 text-amber-400"></i>
                        Categorias / Grupos ({{ $counts['grupos'] }} total)
                    </h3>
                </div>
                @if($grupos->isEmpty())
                    <div class="py-8 text-center text-xs text-slate-500">
                        Nenhuma categoria cadastrada.
                    </div>
                @else
                    <div class="divide-y divide-slate-800/60 max-h-60 overflow-y-auto">
                        @foreach($grupos as $g)
                            <div class="p-3 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-2.5">
                                    @if($g->foto_url)
                                        <img src="{{ $g->foto_url }}" class="h-7 w-7 rounded-lg object-cover">
                                    @else
                                        <div class="h-7 w-7 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400">
                                            <i data-lucide="tag" class="h-3.5 w-3.5"></i>
                                        </div>
                                    @endif
                                    <span class="font-semibold text-slate-200">{{ $g->nome }}</span>
                                </div>
                                <span class="text-[11px] text-slate-400 tabular-nums">{{ $g->products_count }} produtos</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Catálogos Promocionais -->
            <div class="rounded-2xl border border-slate-800 bg-slate-900/60 backdrop-blur-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i data-lucide="folder" class="h-4 w-4 text-blue-400"></i>
                        Catálogos Promocionais ({{ $counts['catalogos'] }} total)
                    </h3>
                </div>
                @if($catalogos->isEmpty())
                    <div class="py-8 text-center text-xs text-slate-500">
                        Nenhum catálogo promocional criado.
                    </div>
                @else
                    <div class="divide-y divide-slate-800/60 max-h-60 overflow-y-auto">
                        @foreach($catalogos as $cat)
                            <div class="p-3 flex items-center justify-between gap-3 text-xs">
                                <div class="min-w-0">
                                    <span class="font-semibold text-slate-200 block truncate">{{ $cat->nome }}</span>
                                    <span class="font-mono text-[10px] text-purple-300">Hash: {{ $cat->hash }}</span>
                                </div>
                                @if($cat->desconto_index > 0)
                                    <span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 font-bold text-[10px]">
                                        {{ $cat->desconto_index }}% desc.
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- REUSABLE DELETION CONFIRMATION MODAL -->
        <div x-show="modalOpen" x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity"
                 x-show="modalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalOpen = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-slate-900 border border-rose-500/40 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-6 sm:p-7"
                     x-show="modalOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <!-- Modal Header -->
                    <div class="flex items-start gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center justify-center shrink-0">
                            <i data-lucide="alert-triangle" class="h-6 w-6"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-black text-white" id="modal-title" x-text="modalTitle"></h3>
                            <p class="text-xs text-slate-300 mt-2 leading-relaxed" x-text="modalMessage"></p>
                        </div>
                    </div>

                    <!-- Extra confirmation input for Wipe or Account Deletion -->
                    <template x-if="modalRequireTyping">
                        <div class="mt-4 p-3.5 rounded-xl bg-slate-950/80 border border-rose-500/30">
                            <label class="block text-xs font-bold text-rose-300 mb-1.5">
                                Digite <span class="bg-rose-500/20 text-white px-1.5 py-0.5 rounded font-mono">ZERAR</span> para confirmar:
                            </label>
                            <input type="text" x-model="confirmInput"
                                   placeholder="ZERAR"
                                   class="w-full px-3 py-2 text-xs rounded-lg bg-slate-900 border border-slate-700 text-white font-mono uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-rose-500">
                        </div>
                    </template>

                    <!-- Form for Deletion -->
                    <form :action="modalFormAction" method="POST" class="mt-6 flex flex-col sm:flex-row items-center justify-end gap-2.5">
                        @csrf
                        @method('DELETE')

                        <button type="button" @click="modalOpen = false"
                                class="w-full sm:w-auto px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 transition-colors cursor-pointer">
                            Cancelar
                        </button>

                        <button type="submit"
                                :disabled="modalRequireTyping && confirmInput.trim() !== 'ZERAR'"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs font-black text-white bg-rose-600 hover:bg-rose-500 disabled:opacity-40 disabled:cursor-not-allowed shadow-lg shadow-rose-600/30 transition-all cursor-pointer">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                            Confirmar Exclusão
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
</x-superadmin-layout>
