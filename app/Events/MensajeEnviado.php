<?php

namespace App\Events;

use App\Models\Mensaje;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MensajeEnviado implements ShouldBroadcastNow // Usamos ShouldBroadcastNow para que se envíe al instante sin necesitar colas de trabajo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mensaje;

    public function __construct(Mensaje $mensaje)
    {
        // Cargamos las relaciones para que JavaScript tenga el nombre y los archivos
        $this->mensaje = $mensaje->load(['remitente', 'archivos']);
    }

    // Emitimos en un canal PRIVADO único para esta conversación
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->mensaje->id_conversacion),
        ];
    }
}