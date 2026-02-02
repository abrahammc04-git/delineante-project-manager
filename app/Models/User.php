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
}