<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversacion;

Broadcast::channel('chat.{id_conversacion}', function ($user, $id_conversacion) {
    // Si es admin, puede escuchar cualquier chat
    if ($user->isAdmin()) {
        return true;
    }
    
    // Si es cliente, verificamos que el chat sea suyo
    $conversacion = Conversacion::find($id_conversacion);
    return $conversacion && $conversacion->id_usuario === $user->id_usuario;
});