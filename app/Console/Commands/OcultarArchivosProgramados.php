<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Documento;
use Carbon\Carbon;

class OcultarArchivosProgramados extends Command
{
    // ESTA LÍNEA ES LA "LLAVE". Si está mal escrita, el comando no existe.
    protected $signature = 'archivos:ocultar';

    protected $description = 'Oculta archivos cuya fecha programada ha vencido';

    public function handle()
    {
        $afectados = Documento::whereNotNull('fecha_ocultacion')
            ->where('fecha_ocultacion', '<=', Carbon::now())
            ->update([
                'visible' => false,
                'fecha_ocultacion' => null
            ]);

        if ($afectados > 0) {
            $this->info("Mantenimiento realizado. Se han procesado {$afectados} archivos programados.");
        } else {
            $this->info("No hay archivos pendientes de ocultar.");
        }
            
    }
}