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
use App\Events\MensajeEnviado;

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
            // Ya no necesitamos traer el último mensaje porque hemos quitado la preview
            $chatsActivos = Conversacion::with('usuario')->where('activo', true)->where('archivada', false)->get();
            $chatsArchivados = Conversacion::with('usuario')->where('activo', true)->where('archivada', true)->get();
                            
            // SACAMOS A LOS USUARIOS QUE YA TIENEN CHAT
            $clientesConChat = Conversacion::pluck('id_usuario')->toArray();
            $usuariosParaChat = \App\Models\User::where('rol', 'cliente')
                                    ->where('activo', true)
                                    ->whereNotIn('id_usuario', $clientesConChat)
                                    ->get();
            
            return view('chat.index', compact('chatsActivos', 'chatsArchivados', 'usuariosParaChat'));
        }

        $chatsActivos = Conversacion::with('usuario')->where('id_usuario', $user->id_usuario)->where('activo', true)->get();
        $chatsArchivados = collect(); 
        
        return view('chat.index', compact('chatsActivos', 'chatsArchivados'));
    }

    public function storeConversacion(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin()) abort(403);

        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario' 
        ]);

        $conversacion = Conversacion::firstOrCreate(
            ['id_usuario' => $request->id_usuario],
            ['activo' => true, 'archivada' => false]
        );

        // Volvemos a la vista principal, indicando al JS qué chat debe abrir automáticamente
        return redirect()->route('chat.index')->with('abrir_chat', $conversacion->id_conversacion);
    }

    public function obtenerChatApi($id) // Devuelve el chat en JSON
    {
        $conversacion = Conversacion::with(['mensajes.remitente', 'mensajes.archivos', 'usuario'])->findOrFail($id);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && $conversacion->id_usuario !== $user->id_usuario) abort(403);

        return response()->json([
            'success' => true,
            'conversacion' => $conversacion,
            'mensajes' => $conversacion->mensajes
        ]);
    }

    public function toggleArchivarApi($id) // Archiva/Desarchiva
    {
        /** @var \App\Models\User $user */
        if (!Auth::user()->isAdmin()) abort(403);

        $conversacion = Conversacion::findOrFail($id);
        $conversacion->archivada = !$conversacion->archivada;
        $conversacion->save();

        return response()->json([
            'success' => true, 
            'archivada' => $conversacion->archivada,
            'mensaje' => $conversacion->archivada ? 'Chat archivado' : 'Chat desarchivado'
        ]);
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
            'contenido'       => 'required_without:archivo|nullable|string|max:400', 
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

            broadcast(new \App\Events\MensajeEnviado($mensaje))->toOthers();

            // RESPUESTA AJAX (Sin recargar página)
            if ($request->wantsJson() || $request->ajax()) {
                $mensaje->load(['remitente', 'archivos']);
                return response()->json(['success' => true, 'mensaje' => $mensaje]);
            }

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

    public function eliminarMensaje(Request $request, $id)
    {
        $mensaje = Mensaje::findOrFail($id);
        
        if ($mensaje->id_remitente !== auth()->user()->id_usuario) abort(403);

        $id_conversacion = $mensaje->id_conversacion;
        $mensaje->delete(); 

        // Avisar al otro usuario de que lo borre de su pantalla
        broadcast(new \App\Events\MensajeEliminado($id, $id_conversacion))->toOthers();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }
        return back();
    }

    public function eliminarConversacion($id)
    {
        $conversacion = Conversacion::findOrFail($id);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Solo admin o el dueño pueden borrarla
        if (!$user->isAdmin() && $conversacion->id_usuario !== $user->id_usuario) abort(403);

        $conversacion->delete();

        return redirect()->route('chat.index')->with('success', 'Conversación eliminada.');
    }

}