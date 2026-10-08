<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Especificar la tabla
    protected $table = 'usuarios';
    
    // Especificar la clave primaria
    protected $primaryKey = 'id_usuario';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'empresa',
        'password_hash',
        'rol',
        'activo',
    ];

    // Campos ocultos (no se devuelven en JSON)
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    // Especificar que 'password_hash' es la contraseña
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Deshabilitar timestamps automáticos de Laravel
    public $timestamps = false;

    // Castear tipos
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'activo' => 'boolean',
        ];
    }
    
    // Relación con proyectos
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'id_usuario', 'id_usuario');
    }
    
    // Helper: verificar si es admin
    public function isAdmin()
    {
        return $this->rol === 'admin';
    }
    
    // Helper: verificar si es cliente
    public function isCliente()
    {
        return $this->rol === 'cliente';
    }

    // 🔑 Indicar a Laravel cuál es la clave primaria para Auth
    public function getAuthIdentifierName()
    {
        return 'id_usuario';
    }

    // --- NUEVAS RELACIONES MULTI-EMPRESA (Multi-enterprise) ---

    // Relación: Un usuario pertenece a una empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

    // Relación: Proyectos donde este usuario es el responsable directo
    public function proyectosPropios()
    {
        return $this->hasMany(Proyecto::class, 'id_usuario', 'id_usuario'); 
    }

    // --- HELPER DE PERMISOS ---
    
    // Función para saber si el usuario tiene permiso para ver un proyecto específico
    public function puedeVerProyecto($proyecto)
    {
        // 1. Si es admin/CEO, ve todo
        if ($this->isAdmin()) {
            return true;
        }

        // 2. Si el proyecto pertenece a su misma empresa
        if ($this->id_empresa && $proyecto->id_empresa === $this->id_empresa) {
            return true;
        }

        // 3. Si es el creador/responsable directo del proyecto
        return $proyecto->id_usuario === $this->id_usuario;
    }

}