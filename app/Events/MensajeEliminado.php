<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MensajeEliminado implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id_mensaje;
    public $id_conversacion;

    public function __construct($id_mensaje, $id_conversacion)
    {
        $this->id_mensaje = $id_mensaje;
        $this->id_conversacion = $id_conversacion;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->id_conversacion),
        ];
    }
}