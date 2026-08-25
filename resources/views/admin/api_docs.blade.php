<x-admin-layout active="api-docs" title="Documentação da API (Postman Style)" subtitle="Coleção oficial de endpoints para integração do seu ERP com o ZapCatálogo.">
    <div class="space-y-8" x-data="{
        activeEndpoint: 'auth',
        lang: 'curl',
        copiedToken: false,
        copiedCode: false,
        scrollTo(id) {
            this.activeEndpoint = id;
            const el = document.getElementById(id);
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }">

        {{-- BANNER DE TOPO - COLEÇÃO POSTMAN --}}
        <div class="rounded-3xl border border-[var(--color-primary)]/30 bg-[var(--bg-card)] p-6 sm:p-8 shadow-2xl backdrop-blur-sm relative overflow-hidden">
            <div class="absolute -top-20 -right-20 h-56 w-56 rounded-full bg-orange-500/10 blur-3xl pointer-events-none"></div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-xl bg-orange-500/15 border border-orange-500/30 px-3 py-1 text-xs font-extrabold text-orange-400">
                            <i data-lucide="box" class="h-3.5 w-3.5"></i>
                            Postman API Collection
                        </span>
                        <span class="rounded-xl bg-emerald-500/15 border border-emerald-500/30 px-3 py-1 text-xs font-bold text-emerald-400">v1.0.0</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-[var(--text-base)] tracking-tight">ZapCatálogo Integration API</h1>
                    <p class="text-sm text-[var(--text-muted)] max-w-2xl">
                        Documentação técnica oficial para comunicação entre seu ERP e o catálogo digital. Utilize o cabeçalho <code class="text-orange-400 font-mono">Authorization: Bearer</code> com seu token abaixo.
                    </p>
                </div>

                {{-- CHAVE DE API / TOKEN --}}
                <div class="w-full lg:w-auto min-w-[320px] rounded-2xl border border-[var(--color-primary)]/20 bg-[var(--bg-page)]/80 p-4 shadow-inner">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] flex items-center gap-1.5">
                            <i data-lucide="key" class="h-3.5 w-3.5 text-[var(--color-primary)]"></i>
                            Seu API Token
                        </span>
                        @if(!auth()->user()->api_token)
                            <a href="{{ route('profile.edit') }}" class="text-xs font-bold text-amber-400 hover:underline">Gerar Token</a>
                        @endif
                    </div>
                    
                    @if (auth()->user()->api_token)
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ auth()->user()->api_token }}"
                                class="w-full bg-transparent font-mono text-xs text-orange-300 outline-none select-all truncate">
                            <button type="button"
                                @click="navigator.clipboard.writeText('{{ auth()->user()->api_token }}'); copiedToken = true; setTimeout(() => copiedToken = false, 2000)"
                                class="flex h-8 shrink-0 items-center gap-1 rounded-xl bg-orange-500 hover:bg-orange-600 px-3 text-xs font-bold text-slate-950 transition-all cursor-pointer">
                                <i data-lucide="copy" class="h-3.5 w-3.5" x-show="!copiedToken"></i>
                                <i data-lucide="check" class="h-3.5 w-3.5" x-show="copiedToken" x-cloak></i>
                                <span x-text="copiedToken ? 'Copiado!' : 'Copiar'"></span>
                            </button>
                        </div>
                    @else
                        <p class="text-xs font-medium text-amber-400">Nenhum token gerado no seu perfil.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- CORPO DA DOCUMENTAÇÃO POSTMAN (ESTRUTURA DE DOCK / DUAL COLUMN) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- INDICE SIDEBAR DO POSTMAN --}}
            <aside class="lg:col-span-3 sticky top-20 rounded-3xl border border-[var(--color-primary)]/20 bg-[var(--bg-card)] p-4 shadow-xl backdrop-blur-sm space-y-2">
                <p class="px-3 py-2 text-xs font-extrabold uppercase tracking-wider text-[var(--text-muted)] flex items-center justify-between">
                    <span>Coleção</span>
                    <span class="text-[10px] text-orange-400 bg-orange-500/10 px-2 py-0.5 rounded-md">2 Endpoints</span>
                </p>

                <nav class="space-y-1">
                    <button type="button" @click="scrollTo('sec-auth')"
                        :class="activeEndpoint === 'sec-auth' ? 'bg-[var(--color-primary)]/20 text-[var(--color-primary)] font-bold border-[var(--color-primary)]/30' : 'text-[var(--text-muted)] hover:text-[var(--text-base)] hover:bg-[var(--bg-page)]/50'"
                        class="w-full text-left flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-xs transition-all border border-transparent">
                        <i data-lucide="shield-check" class="h-4 w-4 shrink-0 text-orange-400"></i>
                        <span>Autenticação & URL Base</span>
                    </button>

                    <button type="button" @click="scrollTo('sec-sync-products')"
                        :class="activeEndpoint === 'sec-sync-products' ? 'bg-[var(--color-primary)]/20 text-[var(--color-primary)] font-bold border-[var(--color-primary)]/30' : 'text-[var(--text-muted)] hover:text-[var(--text-base)] hover:bg-[var(--bg-page)]/50'"
                        class="w-full text-left flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-xs transition-all border border-transparent">
                        <span class="rounded-md bg-blue-600 px-1.5 py-0.5 text-[10px] font-black text-white shrink-0">POST</span>
                        <span class="truncate">Sincronizar Produtos</span>
                    </button>

                    <button type="button" @click="scrollTo('sec-sync-orders')"
                        :class="activeEndpoint === 'sec-sync-orders' ? 'bg-[var(--color-primary)]/20 text-[var(--color-primary)] font-bold border-[var(--color-primary)]/30' : 'text-[var(--text-muted)] hover:text-[var(--text-base)] hover:bg-[var(--bg-page)]/50'"
                        class="w-full text-left flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-xs transition-all border border-transparent">
                        <span class="rounded-md bg-emerald-600 px-1.5 py-0.5 text-[10px] font-black text-white shrink-0">POST</span>
                        <span class="truncate">Sincronizar Pedidos</span>
                    </button>
                </nav>
            </aside>

            {{-- SEÇÃO PRINCIPAL DE CONTEÚDO --}}
            <main class="lg:col-span-9 space-y-12">

                {{-- SEÇÃO 1: AUTENTICAÇÃO E URL BASE --}}
                <section id="sec-auth" class="rounded-3xl border border-[var(--color-primary)]/20 bg-[var(--bg-card)] p-6 sm:p-8 shadow-xl space-y-6 scroll-mt-24">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-2xl bg-orange-500/15 border border-orange-500/30 flex items-center justify-center text-orange-400">
                            <i data-lucide="shield-check" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-[var(--text-base)]">Autenticação & Conexão</h2>
                            <p class="text-xs text-[var(--text-muted)]">Configurações globais para todas as requisições à API</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-page)]/60 p-4 space-y-1">
                            <span class="text-xs font-bold text-orange-400 uppercase tracking-wider block">Base URL da API</span>
                            <code class="text-xs font-mono text-[var(--text-base)] font-bold select-all">{{ url('/api') }}</code>
                        </div>
                        <div class="rounded-2xl border border-[var(--color-primary)]/15 bg-[var(--bg-page)]/60 p-4 space-y-1">
                            <span class="text-xs font-bold text-orange-400 uppercase tracking-wider block">Header Obrigatório de Resposta</span>
                            <code class="text-xs font-mono text-[var(--text-base)] font-bold select-all">Accept: application/json</code>
                        </div>
                    </div>
                </section>

                {{-- ENDPOINT 1: SYNC-PRODUCTS (ESTILO POSTMAN 2-COLUMAS) --}}
                <section id="sec-sync-products" class="rounded-3xl border border-[var(--color-primary)]/20 bg-[var(--bg-card)] p-6 sm:p-8 shadow-xl space-y-6 scroll-mt-24">
                    
                    {{-- CABEÇALHO DO ENDPOINT POSTMAN --}}
                    <div class="space-y-3 pb-6 border-b border-[var(--color-primary)]/10">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="rounded-xl bg-blue-600 px-3 py-1 text-xs font-black text-white tracking-widest uppercase shadow-md shadow-blue-600/30">POST</span>
                            <span class="font-mono text-sm sm:text-base font-bold text-[var(--text-base)] select-all">{{ url('/api/sync-products') }}</span>
                        </div>
                        <h2 class="text-2xl font-black text-[var(--text-base)]">Sincronizar Produtos e Categorias</h2>
                        <p class="text-sm text-[var(--text-muted)] leading-relaxed">
                            Insere ou atualiza produtos no catálogo digital a partir do seu ERP (método <code class="font-mono text-orange-400">upsert</code> com identificação por <code class="font-mono text-orange-400">erp_id</code>). Se a categoria enviada em <code class="font-mono text-orange-400">group</code> não existir, ela será criada automaticamente.
                        </p>
                    </div>

                    {{-- POSTMAN REQUEST & RESPONSE LAYOUT (GRID DUAL) --}}
                    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
                        
                        {{-- ESQUERDA: PARÂMETROS E HEADERS --}}
                        <div class="xl:col-span-7 space-y-6">
                            
                            {{-- HEADERS DO REQUEST --}}
                            <div class="space-y-2">
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-[var(--text-muted)] flex items-center gap-1.5">
                                    <i data-lucide="layers" class="h-3.5 w-3.5 text-blue-400"></i>
                                    Request Headers
                                </h3>
                                <div class="rounded-2xl border border-[var(--color-primary)]/15 overflow-hidden">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-[var(--bg-page)]/80 font-bold border-b border-[var(--color-primary)]/15 text-[var(--text-base)]">
                                            <tr>
                                                <th class="p-2.5">Header</th>
                                                <th class="p-2.5">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-[var(--color-primary)]/10 text-[var(--text-muted)] font-mono">
                                            <tr>
                                                <td class="p-2.5 text-orange-400 font-bold">Authorization</td>
                                                <td class="p-2.5">Bearer {{ auth()->user()->api_token ? '••••••••' : '{SEU_TOKEN}' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 text-orange-400 font-bold">Content-Type</td>
                                                <td class="p-2.5">application/json</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 text-orange-400 font-bold">Accept</td>
                                                <td class="p-2.5">application/json</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- BODY PARAMS (ESTRUTURA HIERÁRQUICA LIMPA) --}}
                            <div class="space-y-2">
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-[var(--text-muted)] flex items-center gap-1.5">
                                    <i data-lucide="file-json" class="h-3.5 w-3.5 text-blue-400"></i>
                                    Request Body Schema (JSON)
                                </h3>
                                <div class="rounded-2xl border border-[var(--color-primary)]/15 overflow-x-auto">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-[var(--bg-page)]/80 font-bold border-b border-[var(--color-primary)]/15 text-[var(--text-base)]">
                                            <tr>
                                                <th class="p-2.5">Campo</th>
                                                <th class="p-2.5">Tipo</th>
                                                <th class="p-2.5">Descrição</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-[var(--color-primary)]/10 text-[var(--text-base)]">
                                            <tr class="bg-[var(--bg-page)]/30">
                                                <td class="p-2.5 font-mono text-blue-400 font-bold">products</td>
                                                <td class="p-2.5 font-mono text-purple-400">Array de Objetos</td>
                                                <td class="p-2.5 text-xs">Lista de produtos (máx: 100 por envio)</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-6 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> id</td>
                                                <td class="p-2.5 font-mono text-xs">Integer / String</td>
                                                <td class="p-2.5 text-xs font-semibold text-red-400">Obrigatório. ID do produto no ERP</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-6 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> sku</td>
                                                <td class="p-2.5 font-mono text-xs">String</td>
                                                <td class="p-2.5 text-xs font-semibold text-red-400">Obrigatório. Código SKU</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-6 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> name</td>
                                                <td class="p-2.5 font-mono text-xs">String</td>
                                                <td class="p-2.5 text-xs font-semibold text-red-400">Obrigatório. Nome do produto</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-6 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> price</td>
                                                <td class="p-2.5 font-mono text-xs">Numeric</td>
                                                <td class="p-2.5 text-xs font-semibold text-red-400">Obrigatório. Preço base (ex: 49.90)</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-6 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> peso</td>
                                                <td class="p-2.5 font-mono text-xs">Numeric</td>
                                                <td class="p-2.5 text-xs font-semibold text-red-400">Obrigatório. Peso em KG (ex: 0.500)</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-6 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> status</td>
                                                <td class="p-2.5 font-mono text-xs">Boolean</td>
                                                <td class="p-2.5 text-xs font-semibold text-red-400">Obrigatório. true (ativo) / false (inativo)</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-6 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> description</td>
                                                <td class="p-2.5 font-mono text-xs">String</td>
                                                <td class="p-2.5 text-xs font-semibold text-red-400">Obrigatório. Descrição detalhada</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-6 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> image_base64</td>
                                                <td class="p-2.5 font-mono text-xs">String</td>
                                                <td class="p-2.5 text-xs text-[var(--text-muted)]">Opcional. Foto em Base64</td>
                                            </tr>
                                            <tr class="bg-[var(--bg-page)]/20">
                                                <td class="p-2.5 font-mono pl-6 text-blue-400 font-bold flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> group</td>
                                                <td class="p-2.5 font-mono text-purple-400">Objeto</td>
                                                <td class="p-2.5 text-xs font-semibold text-red-400">Obrigatório. Categoria no ERP</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-10 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> id</td>
                                                <td class="p-2.5 font-mono text-xs">Integer / String</td>
                                                <td class="p-2.5 text-xs font-semibold text-red-400">ID da categoria no ERP</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-10 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> name</td>
                                                <td class="p-2.5 font-mono text-xs">String</td>
                                                <td class="p-2.5 text-xs font-semibold text-red-400">Nome da categoria</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- DIREITA: POSTMAN CODE SNIPPET & RESPONSE PREVIEW --}}
                        <div class="xl:col-span-5 space-y-4">
                            
                            {{-- PAINEL DE EXEMPLO DE PAYLOAD JSON --}}
                            <div class="rounded-2xl border border-slate-800 bg-slate-950 p-4 space-y-2 shadow-2xl">
                                <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                        <i data-lucide="code-2" class="h-3.5 w-3.5 text-blue-400"></i>
                                        Example Request Body
                                    </span>
                                    <span class="text-[10px] font-mono text-slate-500">JSON</span>
                                </div>
                                <pre class="font-mono text-xs text-emerald-400 overflow-x-auto select-all p-1 leading-relaxed">
{
  "products": [
    {
      "id": 101,
      "sku": "CAM-001",
      "status": true,
      "peso": 0.350,
      "name": "Camisa Polo Masculina",
      "description": "Camisa polo em algodão piquet",
      "price": 79.90,
      "image_base64": "/9j/4AAQSkZJRg...",
      "group": {
        "id": 10,
        "name": "Moda Masculina"
      }
    }
  ]
}</pre>
                            </div>

                            {{-- PAINEL DE RESPOSTA POSTMAN (202 ACCEPTED) --}}
                            <div class="rounded-2xl border border-slate-800 bg-slate-950 p-4 space-y-2 shadow-2xl">
                                <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                        <i data-lucide="terminal" class="h-3.5 w-3.5 text-emerald-400"></i>
                                        Response Example
                                    </span>
                                    <span class="inline-flex items-center gap-1 rounded-md bg-emerald-500/20 px-2 py-0.5 text-[10px] font-bold text-emerald-400 border border-emerald-500/30">
                                        202 Accepted • 45 ms
                                    </span>
                                </div>
                                <pre class="font-mono text-xs text-sky-300 overflow-x-auto select-all p-1 leading-relaxed">
{
  "message": "1 produtos processados com sucesso. Imagens sendo processadas em segundo plano."
}</pre>
                            </div>

                        </div>

                    </div>
                </section>

                {{-- ENDPOINT 2: SYNC-ORDERS (ESTILO POSTMAN 2-COLUMAS) --}}
                <section id="sec-sync-orders" class="rounded-3xl border border-[var(--color-primary)]/20 bg-[var(--bg-card)] p-6 sm:p-8 shadow-xl space-y-6 scroll-mt-24">
                    
                    {{-- CABEÇALHO DO ENDPOINT POSTMAN --}}
                    <div class="space-y-3 pb-6 border-b border-[var(--color-primary)]/10">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="rounded-xl bg-emerald-600 px-3 py-1 text-xs font-black text-white tracking-widest uppercase shadow-md shadow-emerald-600/30">POST</span>
                            <span class="font-mono text-sm sm:text-base font-bold text-[var(--text-base)] select-all">{{ url('/api/sync-orders') }}</span>
                        </div>
                        <h2 class="text-2xl font-black text-[var(--text-base)]">Sincronizar Pedidos para o ERP</h2>
                        <p class="text-sm text-[var(--text-muted)] leading-relaxed">
                            Retorna todos os novos pedidos realizados no catálogo onde <code class="font-mono text-orange-400">sync = false</code>. Na mesma transação, atualiza o status desses pedidos para <code class="font-mono text-orange-400">sync = true</code> para evitar downloads duplicados.
                        </p>
                    </div>

                    {{-- POSTMAN REQUEST & RESPONSE LAYOUT (GRID DUAL) --}}
                    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
                        
                        {{-- ESQUERDA: ESTRUTURA DE RESPOSTA --}}
                        <div class="xl:col-span-7 space-y-6">
                            
                            {{-- HEADERS DO REQUEST --}}
                            <div class="space-y-2">
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-[var(--text-muted)] flex items-center gap-1.5">
                                    <i data-lucide="layers" class="h-3.5 w-3.5 text-emerald-400"></i>
                                    Request Headers
                                </h3>
                                <div class="rounded-2xl border border-[var(--color-primary)]/15 overflow-hidden">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-[var(--bg-page)]/80 font-bold border-b border-[var(--color-primary)]/15 text-[var(--text-base)]">
                                            <tr>
                                                <th class="p-2.5">Header</th>
                                                <th class="p-2.5">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-[var(--color-primary)]/10 text-[var(--text-muted)] font-mono">
                                            <tr>
                                                <td class="p-2.5 text-orange-400 font-bold">Authorization</td>
                                                <td class="p-2.5">Bearer {{ auth()->user()->api_token ? '••••••••' : '{SEU_TOKEN}' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 text-orange-400 font-bold">Accept</td>
                                                <td class="p-2.5">application/json</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- RESPONSE SCHEMA (JSON RESPONSE HIERARCHY) --}}
                            <div class="space-y-2">
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-[var(--text-muted)] flex items-center gap-1.5">
                                    <i data-lucide="file-json" class="h-3.5 w-3.5 text-emerald-400"></i>
                                    Response Body Schema (JSON)
                                </h3>
                                <div class="rounded-2xl border border-[var(--color-primary)]/15 overflow-x-auto">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-[var(--bg-page)]/80 font-bold border-b border-[var(--color-primary)]/15 text-[var(--text-base)]">
                                            <tr>
                                                <th class="p-2.5">Campo / Propriedade</th>
                                                <th class="p-2.5">Tipo</th>
                                                <th class="p-2.5">Descrição</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-[var(--color-primary)]/10 text-[var(--text-base)]">
                                            <tr class="bg-[var(--bg-page)]/30">
                                                <td class="p-2.5 font-mono text-emerald-400 font-bold">pedidos</td>
                                                <td class="p-2.5 font-mono text-purple-400">Array de Objetos</td>
                                                <td class="p-2.5 text-xs">Lista de pedidos pendentes de sincronização</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-6 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> id</td>
                                                <td class="p-2.5 font-mono text-xs">Integer</td>
                                                <td class="p-2.5 text-xs">ID único do pedido no ZapCatálogo</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-6 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> total</td>
                                                <td class="p-2.5 font-mono text-xs">String</td>
                                                <td class="p-2.5 text-xs">Valor total do pedido (ex: "159.80")</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-6 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> cliente_nome</td>
                                                <td class="p-2.5 font-mono text-xs">String</td>
                                                <td class="p-2.5 text-xs">Nome completo do cliente</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-6 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> cliente_phone</td>
                                                <td class="p-2.5 font-mono text-xs">String</td>
                                                <td class="p-2.5 text-xs">WhatsApp / Telefone do cliente</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-6 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> sync</td>
                                                <td class="p-2.5 font-mono text-xs">Boolean</td>
                                                <td class="p-2.5 text-xs">Status da sincronização (atualizado para true nesta chamada)</td>
                                            </tr>
                                            <tr class="bg-[var(--bg-page)]/20">
                                                <td class="p-2.5 font-mono pl-6 text-emerald-400 font-bold flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> iten_pedido</td>
                                                <td class="p-2.5 font-mono text-purple-400">Array de Objetos</td>
                                                <td class="p-2.5 text-xs">Itens comprados neste pedido</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-10 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> id</td>
                                                <td class="p-2.5 font-mono text-xs">Integer</td>
                                                <td class="p-2.5 text-xs">ID do item do pedido</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-10 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> quantidade</td>
                                                <td class="p-2.5 font-mono text-xs">Integer</td>
                                                <td class="p-2.5 text-xs">Quantidade comprada do produto</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-10 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> value</td>
                                                <td class="p-2.5 font-mono text-xs">String</td>
                                                <td class="p-2.5 text-xs">Valor unitário do item (ex: "79,90")</td>
                                            </tr>
                                            <tr class="bg-[var(--bg-page)]/20">
                                                <td class="p-2.5 font-mono pl-10 text-emerald-400 font-bold flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> product</td>
                                                <td class="p-2.5 font-mono text-purple-400">Objeto</td>
                                                <td class="p-2.5 text-xs">Dados do produto vinculado</td>
                                            </tr>
                                            <tr class="bg-purple-500/10 font-bold">
                                                <td class="p-2.5 font-mono pl-14 text-purple-400 flex items-center gap-1"><span class="text-purple-400">↳</span> erp_id</td>
                                                <td class="p-2.5 font-mono text-purple-300">String / Integer</td>
                                                <td class="p-2.5 text-xs text-purple-300 font-bold">ID do produto correspondente no seu ERP (cadastrado no sync-products)</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-14 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> sku</td>
                                                <td class="p-2.5 font-mono text-xs">String</td>
                                                <td class="p-2.5 text-xs">Código SKU do produto</td>
                                            </tr>
                                            <tr>
                                                <td class="p-2.5 font-mono pl-14 flex items-center gap-1"><span class="text-[var(--text-muted)]">↳</span> nome</td>
                                                <td class="p-2.5 font-mono text-xs">String</td>
                                                <td class="p-2.5 text-xs">Nome do produto no catálogo</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- DIREITA: POSTMAN RESPONSE PREVIEW --}}
                        <div class="xl:col-span-5 space-y-4">
                            
                            {{-- PAINEL DE RESPOSTA POSTMAN (200 OK) --}}
                            <div class="rounded-2xl border border-slate-800 bg-slate-950 p-4 space-y-2 shadow-2xl">
                                <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                        <i data-lucide="terminal" class="h-3.5 w-3.5 text-emerald-400"></i>
                                        Response Example (JSON)
                                    </span>
                                    <span class="inline-flex items-center gap-1 rounded-md bg-emerald-500/20 px-2 py-0.5 text-[10px] font-bold text-emerald-400 border border-emerald-500/30">
                                        200 OK • 28 ms
                                    </span>
                                </div>
                                <pre class="font-mono text-xs text-sky-300 overflow-x-auto select-all p-1 leading-relaxed">
{
  "pedidos": [
    {
      "id": 1,
      "user_id": {{ auth()->user()->id }},
      "total": "159.80",
      "cliente_nome": "Maria Oliveira",
      "cliente_phone": "11988887777",
      "sync": true,
      "iten_pedido": [
        {
          "id": 15,
          "pedido_id": 1,
          "product_id": 5,
          "quantidade": 2,
          "value": "79,90",
          "product": {
            "id": 5,
            "erp_id": "101",
            "sku": "CAM-001",
            "nome": "Camisa Polo Masculina",
            "preco_base": "79.90",
            "peso": "0.35"
          }
        }
      ]
    }
  ]
}</pre>
                            </div>

                        </div>

                    </div>
                </section>

                {{-- POSTMAN CODE GENERATOR (SELETOR DE LINGUAGENS DO POSTMAN) --}}
                <section class="rounded-3xl border border-[var(--color-primary)]/20 bg-[var(--bg-card)] p-6 sm:p-8 shadow-xl space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-[var(--color-primary)]/10 pb-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="code" class="h-5 w-5 text-orange-400"></i>
                            <h2 class="text-lg font-extrabold text-[var(--text-base)]">Postman Code Snippets Generator</h2>
                        </div>

                        {{-- SELETOR DE LINGUAGEM IGUAL AO POSTMAN --}}
                        <div class="flex items-center gap-2 bg-[var(--bg-page)]/80 p-1 rounded-2xl border border-[var(--color-primary)]/15">
                            <button type="button" @click="lang = 'curl'"
                                :class="lang === 'curl' ? 'bg-orange-500 text-slate-950 font-extrabold' : 'text-[var(--text-muted)] hover:text-[var(--text-base)]'"
                                class="rounded-xl px-3 py-1.5 text-xs transition-all cursor-pointer">
                                cURL
                            </button>
                            <button type="button" @click="lang = 'php'"
                                :class="lang === 'php' ? 'bg-orange-500 text-slate-950 font-extrabold' : 'text-[var(--text-muted)] hover:text-[var(--text-base)]'"
                                class="rounded-xl px-3 py-1.5 text-xs transition-all cursor-pointer">
                                PHP (Guzzle)
                            </button>
                            <button type="button" @click="lang = 'js'"
                                :class="lang === 'js' ? 'bg-orange-500 text-slate-950 font-extrabold' : 'text-[var(--text-muted)] hover:text-[var(--text-base)]'"
                                class="rounded-xl px-3 py-1.5 text-xs transition-all cursor-pointer">
                                JavaScript (Fetch)
                            </button>
                        </div>
                    </div>

                    {{-- SNIPPET cURL --}}
                    <div x-show="lang === 'curl'">
                        <pre class="rounded-2xl border border-slate-800 bg-slate-950 p-4 text-xs font-mono text-emerald-400 overflow-x-auto select-all leading-relaxed">
curl --location '{{ url('/api/sync-products') }}' \
--header 'Authorization: Bearer {{ auth()->user()->api_token ?? 'SEU_TOKEN_AQUI' }}' \
--header 'Content-Type: application/json' \
--header 'Accept: application/json' \
--data '{
  "products": [
    {
      "id": 101,
      "sku": "CAM-001",
      "status": true,
      "peso": 0.350,
      "name": "Camisa Polo Masculina",
      "description": "Camisa polo em algodão",
      "price": 79.90,
      "group": { "id": 10, "name": "Moda Masculina" }
    }
  ]
}'</pre>
                    </div>

                    {{-- SNIPPET PHP --}}
                    <div x-show="lang === 'php'" x-cloak>
                        <pre class="rounded-2xl border border-slate-800 bg-slate-950 p-4 text-xs font-mono text-sky-300 overflow-x-auto select-all leading-relaxed">
&lt;?php

$client = new \GuzzleHttp\Client();
$response = $client->request('POST', '{{ url('/api/sync-products') }}', [
  'headers' => [
    'Authorization' => 'Bearer {{ auth()->user()->api_token ?? 'SEU_TOKEN_AQUI' }}',
    'Content-Type' => 'application/json',
    'Accept' => 'application/json'
  ],
  'body' => json_encode([
    'products' => [
      [
        'id' => 101,
        'sku' => 'CAM-001',
        'status' => true,
        'peso' => 0.350,
        'name' => 'Camisa Polo Masculina',
        'description' => 'Camisa polo em algodão',
        'price' => 79.90,
        'group' => [ 'id' => 10, 'name' => 'Moda Masculina' ]
      ]
    ]
  ])
]);

echo $response->getBody();</pre>
                    </div>

                    {{-- SNIPPET JS --}}
                    <div x-show="lang === 'js'" x-cloak>
                        <pre class="rounded-2xl border border-slate-800 bg-slate-950 p-4 text-xs font-mono text-amber-300 overflow-x-auto select-all leading-relaxed">
const myHeaders = new Headers();
myHeaders.append("Authorization", "Bearer {{ auth()->user()->api_token ?? 'SEU_TOKEN_AQUI' }}");
myHeaders.append("Content-Type", "application/json");
myHeaders.append("Accept", "application/json");

const raw = JSON.stringify({
  "products": [
    {
      "id": 101,
      "sku": "CAM-001",
      "status": true,
      "peso": 0.350,
      "name": "Camisa Polo Masculina",
      "description": "Camisa polo em algodão",
      "price": 79.90,
      "group": { "id": 10, "name": "Moda Masculina" }
    }
  ]
});

const requestOptions = {
  method: "POST",
  headers: myHeaders,
  body: raw,
  redirect: "follow"
};

fetch("{{ url('/api/sync-products') }}", requestOptions)
  .then((response) => response.json())
  .then((result) => console.log(result))
  .catch((error) => console.error(error));</pre>
                    </div>

                </section>

            </main>

        </div>

    </div>
</x-admin-layout>
