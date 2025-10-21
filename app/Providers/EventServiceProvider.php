<?php

namespace App\Providers;

// Importe o evento e o listener
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

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
        Registered::class => [ // <-- Adicione este bloco
            SendEmailVerificationNotification::class,
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
     * * Vamos desativar para forçar o Laravel a usar nosso array $listen.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}