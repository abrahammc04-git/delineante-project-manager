<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Proyecto;

class ChatbotController extends Controller
{
    /**
     * Procesar consulta del chatbot usando Groq API (GRATIS)
     */
    public function consulta(Request $request)
    {
        $request->validate([
            'mensaje' => 'required|string|max:500'
        ]);

        $mensajeUsuario = $request->input('mensaje');

        // 1. Obtener todos los proyectos de la BD
        $proyectos = Proyecto::with('usuario')->get();

        // 2. Crear contexto con los datos de proyectos
        $contextoProyectos = $this->generarContextoProyectos($proyectos);

        // 3. Llamar a Groq API
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . config('services.groq.key'),
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile', // Modelo gratuito de Groq
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Eres un asistente experto en gestión de proyectos para PROYINSTAL. 

Tienes acceso a los siguientes proyectos:

{$contextoProyectos}

IMPORTANTE - FORMATO DE RESPUESTA:
- Si el usuario pide filtrar/buscar proyectos (ej: 'proyectos completados', 'proyectos de 2019', 'proyectos de Juan'), responde ÚNICAMENTE con los IDs de proyecto en este formato exacto:
  FILTER_IDS: 1,5,8,12
  
- Si el usuario hace una pregunta general (ej: '¿cuántos proyectos hay?', '¿qué tipos hay?'), responde normalmente en texto.

Ejemplos:
Usuario: 'dame los proyectos completados'
Respuesta: FILTER_IDS: 1,2,4,5,7

Usuario: '¿cuántos proyectos hay en total?'
Respuesta: Hay 14 proyectos en total en el sistema.

Usuario: 'proyectos de 2019'
Respuesta: FILTER_IDS: 10,11,12,13,14"
                    ],
                    [
                        'role' => 'user',
                        'content' => $mensajeUsuario
                    ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $respuesta = $data['choices'][0]['message']['content'] ?? 'No pude generar una respuesta.';
                
                // Detectar si es una respuesta de filtrado
                if (preg_match('/FILTER_IDS:\s*([0-9,\s]+)/', $respuesta, $matches)) {
                    $ids = array_map('trim', explode(',', $matches[1]));
                    $ids = array_filter($ids, 'is_numeric');
                    
                    return response()->json([
                        'tipo' => 'filtro',
                        'ids' => array_map('intval', $ids),
                        'respuesta' => 'He filtrado los proyectos según tu consulta.'
                    ]);
                }
                
                // Respuesta normal de texto
                return response()->json([
                    'tipo' => 'texto',
                    'respuesta' => $respuesta
                ]);
            } else {
                return response()->json([
                    'tipo' => 'texto',
                    'respuesta' => 'Error al conectar con el asistente IA. Código: ' . $response->status()
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'tipo' => 'texto',
                'respuesta' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar contexto con información de proyectos
     */
    private function generarContextoProyectos($proyectos)
    {
        if ($proyectos->isEmpty()) {
            return "No hay proyectos registrados en el sistema.";
        }

        $contexto = "LISTA DE PROYECTOS:\n\n";

        foreach ($proyectos as $proyecto) {
            $contexto .= "─────────────────────────────────\n";
            $contexto .= "🆔 ID: {$proyecto->id_proyecto}\n";
            $contexto .= "📋 Proyecto: {$proyecto->nombre_proyecto}\n";
            $contexto .= "👤 Cliente: {$proyecto->usuario->nombre} {$proyecto->usuario->apellidos}\n";
            $contexto .= "🏢 Empresa: " . ($proyecto->usuario->empresa ?? 'Sin empresa') . "\n";
            $contexto .= "📊 Estado: {$proyecto->estado}\n";
            $contexto .= "🔧 Tipo: {$proyecto->tipo_proyecto}\n";
            $contexto .= "📅 Fecha Inicio: " . ($proyecto->fecha_inicio ? $proyecto->fecha_inicio->format('d/m/Y') : 'Sin fecha') . "\n";
            $contexto .= "📍 Localización: " . ($proyecto->localizacion ?? 'Sin localización') . "\n";
            
            if ($proyecto->descripcion) {
                $contexto .= "📝 Descripción: {$proyecto->descripcion}\n";
            }
            
            $contexto .= "\n";
        }

        return $contexto;
    }
}