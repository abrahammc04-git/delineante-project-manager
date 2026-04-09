<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';
    protected $primaryKey = 'id_empresa';

    protected $fillable = [
        'nombre_empresa',
        'cif',
        'direccion',
        'telefono',
        'email_contacto',
        'activo'
    ];

    // Relación: Una empresa tiene muchos usuarios
    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_empresa', 'id_empresa');
    }

    // Relación: Una empresa tiene muchos proyectos
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'id_empresa', 'id_empresa');
    }

    // Scope para filtrar rápidamente solo las empresas activas: Empresa::activas()->get();
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
}