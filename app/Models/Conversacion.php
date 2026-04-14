<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversacion extends Model
{
    use HasFactory;

    protected $table = 'conversaciones';
    protected $primaryKey = 'id_conversacion';

    protected $fillable = [
        'id_usuario',
        'titulo',
        'activo',
    ];

    // Relación: Una conversación pertenece a un usuario (el cliente destinatario)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    // Relación: Una conversación tiene muchos mensajes
    public function mensajes()
    {
        return $this->hasMany(Mensaje::class, 'id_conversacion', 'id_conversacion');
    }

    // Scope para filtrar rápido las conversaciones de un cliente concreto
    public function scopeConversacionesPara($query, $usuario)
    {
        // Si es Admin, en principio puede ver todas, pero si quieres filtrar por cliente:
        if ($usuario->isAdmin()) {
            return $query; 
        }
        
        // Si es cliente, solo ve las suyas
        return $query->where('id_usuario', $usuario->id_usuario);
    }
}