<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivoChat extends Model
{
    use HasFactory;

    protected $table = 'archivos_chat';
    protected $primaryKey = 'id_archivo';

    protected $fillable = [
        'id_mensaje',
        'nombre_original',
        'ruta_storage',
        'tamano',
        'mime_type',
    ];

    // Relación: El archivo pertenece a un mensaje concreto
    public function mensaje()
    {
        return $this->belongsTo(Mensaje::class, 'id_mensaje', 'id_mensaje');
    }
}