<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documentos';
    protected $fillable = ['id_proyecto', 'nombre_archivo', 'visible', 'fecha_ocultacion', 'fecha_publicacion'];
    
    // Casting para que las fechas sean objetos Carbon automáticamente
    protected $casts = [
        'visible' => 'boolean',
        'fecha_ocultacion' => 'datetime',
        'fecha_publicacion' => 'datetime',
    ];
}
