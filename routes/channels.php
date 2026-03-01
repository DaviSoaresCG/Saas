<?php

use Illuminate\Support\Facades\Broadcast;

//verifica se quem esta tentando acessar o canal tem o mesmo ID de quem criou
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
