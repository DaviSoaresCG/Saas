@if($errors->any())
    <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-400">
        <div class="flex items-center gap-2 font-bold mb-1">
            <i data-lucide="alert-circle" class="h-5 w-5"></i>
            Por favor, corrija os erros abaixo:
        </div>
        <ul class="list-disc list-inside space-y-1 ml-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6">
    <!-- Código do Cupom -->
    <div>
        <label for="code" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
            Código do Cupom
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                <i data-lucide="ticket" class="h-5 w-5"></i>
            </div>
            <input type="text" 
                   name="code" 
                   id="code" 
                   required 
                   placeholder="EX: PROMO10"
                   value="{{ old('code', $counpon->code ?? '') }}" 
                   oninput="this.value = this.value.toUpperCase()"
                   class="w-full bg-slate-950/60 border border-slate-700/80 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl pl-11 pr-4 py-3 text-slate-100 placeholder-slate-500 font-mono tracking-wider font-semibold uppercase text-sm transition-all">
        </div>
        <p class="mt-1 text-xs text-slate-500">O código será convertido automaticamente para maiúsculas (ex: NATAL2026).</p>
    </div>

    <!-- Descrição -->
    <div>
        <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
            Descrição do Cupom
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                <i data-lucide="align-left" class="h-5 w-5"></i>
            </div>
            <input type="text" 
                   name="description" 
                   id="description" 
                   required 
                   placeholder="EX: 10% de desconto no primeiro pedido"
                   value="{{ old('description', $counpon->description ?? '') }}" 
                   class="w-full bg-slate-950/60 border border-slate-700/80 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl pl-11 pr-4 py-3 text-slate-100 placeholder-slate-500 text-sm transition-all">
        </div>
    </div>

    <!-- Status -->
    <div>
        <label for="active" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
            Status do Cupom
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                <i data-lucide="power" class="h-5 w-5"></i>
            </div>
            @php
                $currentActive = old('active', isset($counpon) && $counpon->active !== null ? $counpon->active->value : 1);
            @endphp
            <select name="active" 
                    id="active" 
                    class="w-full bg-slate-950/60 border border-slate-700/80 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 rounded-xl pl-11 pr-10 py-3 text-slate-100 text-sm appearance-none cursor-pointer transition-all">
                <option value="1" @selected($currentActive == 1)>Ativo - Cupom disponível para uso</option>
                <option value="0" @selected($currentActive == 0)>Inativo - Cupom desativado</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-500">
                <i data-lucide="chevron-down" class="h-4 w-4"></i>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 pt-6 border-t border-slate-800 flex items-center justify-end gap-3">
    <a href="{{ route('counpons.index') }}" 
       class="px-5 py-2.5 rounded-xl border border-slate-700 bg-slate-800/80 hover:bg-slate-700 text-slate-300 text-sm font-semibold transition-all">
        Cancelar
    </a>
    <button type="submit" 
            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-lg shadow-blue-600/25 hover:scale-[1.02] active:scale-95 transition-all">
        <i data-lucide="check" class="h-4 w-4"></i>
        {{ isset($counpon) ? 'Salvar Alterações' : 'Criar Cupom' }}
    </button>
</div>