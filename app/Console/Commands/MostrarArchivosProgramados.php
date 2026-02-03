<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Documento;
use Carbon\Carbon;

class MostrarArchivosProgramados extends Command
{
    protected $signature = 'archivos:mostrar';
    protected $description = 'Hace visibles los archivos cuya fecha de publicación ha llegado';

    public function handle()
    {
        // Buscamos archivos con fecha programada QUE YA HAYA PASADO
        $afectados = Documento::whereNotNull('fecha_publicacion')
            ->where('fecha_publicacion', '<=', Carbon::now())
            ->update([
                'visible' => true,             // Lo hacemos visible
                'fecha_publicacion' => null    // Limpiamos la fecha porque ya se cumplió
            ]);

        if ($afectados > 0) {
            $this->info("Se han hecho visibles {$afectados} archivos.");
        } else {
            $this->info("No hay archivos para mostrar.");
        }
    }
}