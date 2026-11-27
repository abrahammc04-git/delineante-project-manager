<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Proyecto;

class MigrarCarpetasArchivos extends Command
{
    protected $signature = 'archivos:migrar-carpetas {--dry-run : Muestra los cambios sin ejecutarlos}';

    protected $description = 'Renombra las carpetas de archivos de Str::slug(nombre) al valor de carpeta_archivos en BD';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $disk   = Storage::disk('proyectos');

        $proyectos = Proyecto::whereNotNull('carpeta_archivos')->get();
        $migrados  = 0;
        $errores   = 0;

        foreach ($proyectos as $proyecto) {
            $carpetaDestino = $proyecto->carpeta_archivos;
            // Nombre antiguo que generaba el código original
            $carpetaOrigen  = 'proyecto_' . Str::slug($proyecto->nombre_proyecto);

            if ($carpetaOrigen === $carpetaDestino) {
                continue; // ya coincide, nada que hacer
            }

            if (!$disk->exists($carpetaOrigen)) {
                // La carpeta antigua no existe (ya migrado o sin archivos)
                continue;
            }

            if ($dryRun) {
                $this->line("  [dry-run] Renombrar: {$carpetaOrigen} → {$carpetaDestino}");
                $migrados++;
                continue;
            }

            // Mover todos los archivos uno a uno (Storage no tiene rename de directorio)
            $archivos = $disk->files($carpetaOrigen);
            foreach ($archivos as $archivo) {
                $nombre = basename($archivo);
                try {
                    $disk->move($archivo, "{$carpetaDestino}/{$nombre}");
                } catch (\Exception $e) {
                    $this->error("  Error al mover {$archivo}: " . $e->getMessage());
                    $errores++;
                }
            }

            // Borrar directorio origen (vacío tras mover)
            $disk->deleteDirectory($carpetaOrigen);
            $this->info("  Migrado: {$carpetaOrigen} → {$carpetaDestino}");
            $migrados++;
        }

        $accion = $dryRun ? '[dry-run]' : 'Migrados';
        $this->info("{$accion}: {$migrados} carpeta(s). Errores: {$errores}.");

        return $errores > 0 ? self::FAILURE : self::SUCCESS;
    }
}
