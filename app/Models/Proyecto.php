<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Proyecto extends Model
{
    use HasFactory;

    // Especificar la tabla
    protected $table = 'proyectos';
    
    // Especificar la clave primaria
    protected $primaryKey = 'id_proyecto';

    // Deshabilitar timestamps automáticos de Laravel (usamos fecha_creacion y ultima_actualizacion)
    public $timestamps = false;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'id_usuario',
        'nombre_proyecto',
        'descripcion',
        'tipo_proyecto',
        'estado',
        'fase_actual',
        'fecha_inicio',
        'fecha_fin_prevista',
        'fecha_fin_real',
        'localizacion',
        'direccion',
        'carpeta_archivos',
        'notas',
    ];

    // Castear tipos
    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin_prevista' => 'date',
            'fecha_fin_real' => 'date',
            'fecha_creacion' => 'datetime',
            'ultima_actualizacion' => 'datetime',
        ];
    }

    /**
     * Relación: Un proyecto pertenece a un usuario
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Helper: Verificar si el proyecto está activo
     */
    public function estaActivo()
    {
        return in_array($this->estado, ['Pendiente', 'En proceso', 'En revisión']);
    }

    /**
     * Helper: Verificar si el proyecto está completado
     */
    public function estaCompletado()
    {
        return $this->estado === 'Completado';
    }

    /**
     * Helper: Obtener el color del badge según el estado
     */
    public function getColorEstado()
    {
        return match($this->estado) {
            'Pendiente' => 'badge-pending',
            'En proceso' => 'badge-in-progress',
            'En revisión' => 'badge-in-progress',
            'Completado' => 'badge-completed',
            'Pausado' => 'badge-paused',
            'Cancelado' => 'badge-cancelled',
            default => 'badge-pending',
        };
    }

    /**
     * Helper: Obtener la ruta de la carpeta de archivos
     */
    public function getRutaArchivos()
    {
        return storage_path("app/private/proyectos/{$this->carpeta_archivos}");
    }

    /**
     * Scope: Filtrar por usuario
     */
    public function scopeDelUsuario($query, $idUsuario)
    {
        return $query->where('id_usuario', $idUsuario);
    }

    /**
     * Scope: Filtrar por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope: Solo proyectos activos
     */
    public function scopeActivos($query)
    {
        return $query->whereIn('estado', ['Pendiente', 'En proceso', 'En revisión']);
    }
}