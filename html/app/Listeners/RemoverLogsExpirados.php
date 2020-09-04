<?php

namespace App\Listeners;

use Artisan;

use App\Events\UserLoggedIn;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Renault\Services\ParametroService;

/**
 * Classe Listener, chamada ao ser disparado o evento UserLoggedIn
 * Relação definida em EventServiceProvider.php
 */
class RemoverLogsExpirados
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserLoggedIn  $event
     * @return void
     */
    public function handle(UserLoggedIn $event)
    {
        Artisan::call('activitylog:clean --days ' . ParametroService::retrieve()->periodo_log * 30);
    }
}
