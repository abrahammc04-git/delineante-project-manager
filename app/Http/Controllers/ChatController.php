<?php

namespace App\Http\Controllers;

use App\Models\Conversacion;
use App\Models\Mensaje;
use App\Models\ArchivoChat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Listado de conversaciones
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            // El Admin ve todas las conversaciones activas, con el nombre del cliente
            $conversaciones = Conversacion::with('usuario')->where('activo', true)->get();
            
            // También necesitamos la lista de usuarios para que el admin pueda iniciar un nuevo chat
            $usuariosParaChat = User::where('rol', 'cliente')->where('activo', true)->get();
            
            return view('chat.index', compact('conversaciones', 'usuariosParaChat'));
        }

        // El Cliente solo ve su conversación (o conversaciones si tuviera varias)
        $conversaciones = Conversacion::where('id_usuario', $user->id_usuario)
            ->where('activo', true)
            ->get();

        return view('chat.index', compact('conversaciones'));
    }

    /**
     * Ver una conversación específica
     */
    public function show($id)
    {
        $conversacion = Conversacion::with(['mensajes.remitente', 'mensajes.archivos'])->findOrFail($id);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin() && $conversacion->id_usuario !== $user->id_usuario) {
            abort(403, 'No tienes permiso para acceder a este chat.');
        }

        $conversacion->mensajes()->where('id_remitente', '!=', $user->id_usuario)->update(['leido' => true]);

        if ($user->isAdmin()) {
            $conversaciones = Conversacion::with('usuario')->where('activo', true)->get();

            $usuariosParaChat = User::where('rol', 'cliente')->where('activo', true)->get();
        } else {
            $conversaciones = Conversacion::where('id_usuario', $user->id_usuario)->where('activo', true)->get();
            $usuariosParaChat = collect();
        }

        return view('chat.show', compact('conversacion', 'conversaciones', 'usuariosParaChat'));
    }

    /**
     * Enviar un mensaje (y opcionalmente un archivo)
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_conversacion' => 'required|exists:conversaciones,id_conversacion',
            'contenido'       => 'required_without:archivo|nullable|string',
            'archivo.*'       => 'nullable|file|max:10240',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $mensaje = Mensaje::create([
                'id_conversacion' => $request->id_conversacion,
                'id_remitente'    => $user->id_usuario,
                'contenido'       => $request->contenido,
                'tipo'            => $request->hasFile('archivo') ? 'archivo' : 'texto',
            ]);

            if ($request->hasFile('archivo')) {
                foreach ($request->file('archivo') as $file) {
                    $nombreOriginal = $file->getClientOriginalName();
                    $ruta = $file->store('chat_files/' . $request->id_conversacion, 'local');

                    ArchivoChat::create([
                        'id_mensaje'      => $mensaje->id_mensaje,
                        'nombre_original' => $nombreOriginal,
                        'ruta_storage'    => $ruta,
                        'tamano'          => round($file->getSize() / 1024, 2),
                        'mime_type'       => $file->getMimeType(),
                    ]);
                }
            }

            DB::commit();
            return back(); 

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al enviar el mensaje.');
        }
    }

    /**
     * Descarga segura de archivos
     */
    public function download($id_archivo)
    {
        $archivo = ArchivoChat::with('mensaje.conversacion')->findOrFail($id_archivo);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // SEGURIDAD: Solo admin o participantes del chat descargan
        $idDueñoChat = $archivo->mensaje->conversacion->id_usuario;
        if (!$user->isAdmin() && $user->id_usuario !== $idDueñoChat) {
            abort(403);
        }

        if (!Storage::disk('local')->exists($archivo->ruta_storage)) {
            abort(404, 'El archivo no existe en el servidor.');
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('local');
        
        return $disk->download($archivo->ruta_storage, $archivo->nombre_original);
    }

    /**
     * Crear una nueva conversación (Solo Admin)
     */
    public function storeConversacion(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403, 'Solo el administrador puede iniciar conversaciones.');
        }

        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario' // o 'users' si tu tabla se llama users
        ]);

        // Evitamos duplicados: firstOrCreate busca la conversación, si no existe, la crea
        $conversacion = Conversacion::firstOrCreate(
            ['id_usuario' => $request->id_usuario],
            ['activo' => true]
        );

        return redirect()->route('chat.show', $conversacion->id_conversacion);
    }

    public function eliminarMensaje($id)
{
    $mensaje = Mensaje::findOrFail($id);
    
    // Seguridad: Solo el que lo envió puede borrarlo
    if ($mensaje->id_remitente !== auth()->user()->id_usuario) {
        abort(403);
    }

    $mensaje->delete(); // Esto borrará también los archivos por el 'cascade' de la BD
    return back();
}

}