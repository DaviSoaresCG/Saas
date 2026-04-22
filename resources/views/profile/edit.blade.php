<x-admin-layout active="profile" title="Perfil" subtitle="Altere informações sobre seu perfil">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--text-base)] leading-tight">
            Perfil
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/15 sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/15 sm:rounded-lg">
                <div class="max-w-xl" id="slug">
                    @include('profile.partials.update-slug-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 rounded-2xl border border-[var(--color-primary)]/80 bg-[var(--bg-card)] overflow-hidden shadow-xl shadow-black/15 sm:rounded-lg">
                <div class="max-w-xl" id="password">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            
            {{--  
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            --}}
        </div>
    </div>
</x-admin-layout>
