<?php

namespace App\Providers;

// 1. IMPORTE APENAS O 'Registered'

use App\Listeners\SendEmailVerificationNotification;
use Illuminate\Auth\Events\Registered;
// NÃO importe o SendEmailVerificationNotification aqui

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * O mapeamento de evento/listener para a aplicação.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // 2. O ARRAY DEVE ESTAR VAZIO.
        Registered::class => [
            SendEmailVerificationNotification::class
            // Isso garante que NENHUM listener deste arquivo
            // será disparado quando 'Registered' acontecer.
            // O Breeze/Fortify vai pegar o evento internamente.
        ],
    ];

    /**
     * Registre quaisquer eventos para sua aplicação.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine se os eventos e listeners devem ser descobertos automaticamente.
     * 3. ISSO DEVE SER 'false'
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}