<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $table = 'mensajes';
    protected $primaryKey = 'id_mensaje';

    protected $fillable = [
        'id_conversacion',
        'id_remitente',
        'contenido',
        'tipo', // 'texto' o 'archivo'
        'leido',
    ];

    // Relación: El mensaje pertenece a una conversación
    public function conversacion()
    {
        return $this->belongsTo(Conversacion::class, 'id_conversacion', 'id_conversacion');
    }

    // Relación: El mensaje pertenece a quien lo envió (Admin o Cliente)
    public function remitente()
    {
        return $this->belongsTo(User::class, 'id_remitente', 'id_usuario');
    }

    // Relación: Un mensaje puede tener varios archivos (o uno)
    public function archivos()
    {
        return $this->hasMany(ArchivoChat::class, 'id_mensaje', 'id_mensaje');
    }
}