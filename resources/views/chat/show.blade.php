<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mensajería Interna') }}
        </h2>
    </x-slot>

    <div class="page-box">
        <div class="page-box-inner">
            <div class="page-box-titulo">
                <h1>Chats</h1>
                <p>Administra tus conversaciones</p>
            </div>
            <div class="page-box-botones">
                <a href="{{ route('dashboard') }}" class="btn-box-dashboard">
                    ← Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="dashboard-container" style="padding-top: 2rem;">

        <div class="chat-container">
            
            <div class="chat-sidebar">
                <div class="chat-sidebar-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: none;">
                    <h3 class="chat-sidebar-title">Tus Chats</h3>
                    
                    @if(auth()->user()->isAdmin())
                        <button type="button" onclick="document.getElementById('modalNuevoChat').style.display='flex'" style="background: var(--color-primary-dark); color: white; border: none; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; font-weight: bold; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">+</button>
                    @endif
                </div>

                <div class="chat-search-container" style="padding: 0 1.5rem 1rem 1.5rem; border-bottom: 1px solid var(--color-gray-200);">
                    <input type="text" id="chatSearch" placeholder="Buscar conversación..." style="width: 100%; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid var(--color-gray-300); font-size: 0.875rem;">
                </div>
                
                <ul class="chat-list" id="listaChats">
                    @foreach($conversaciones as $conv)
                        <li class="chat-list-item" style="{{ $conv->id_conversacion === $conversacion->id_conversacion ? 'background-color: var(--color-gray-100); border-left: 4px solid var(--color-primary-dark);' : 'border-left: 4px solid transparent;' }}">
                            <a href="{{ route('chat.show', $conv->id_conversacion) }}" class="chat-item-link">
                                <p class="chat-item-name">
                                    @if(auth()->user()->isAdmin())
                                        {{ $conv->usuario->nombre }} {{ $conv->usuario->apellidos }}
                                    @else
                                        Delineador
                                    @endif
                                </p>
                                <p class="chat-item-subtitle">
                                    @if($conv->mensajes->isNotEmpty())
                                        {{ Str::limit($conv->mensajes->last()->contenido ?? '📎 Archivo adjunto', 35) }}
                                    @else
                                        Sin mensajes aún
                                    @endif
                                </p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="chat-active-area">
                
                <div class="chat-header">
                    <h3 class="chat-sidebar-title" style="margin:0; font-size: 1.1rem;">
                        @if(auth()->user()->isAdmin())
                            <span style="color: var(--color-primary-dark);">{{ $conversacion->usuario->nombre }} {{ $conversacion->usuario->apellidos }}</span>
                        @else
                            {{ $conversacion->titulo ?? 'Delineador' }}
                        @endif
                    </h3>
                </div>

                <div class="chat-messages-container" id="cajaMensajes">
                    @if($conversacion->mensajes->isEmpty())
                        <div style="height: 100%; display: flex; align-items: center; justify-content: center;">
                            <p style="color: var(--color-gray-500); background: var(--color-white); padding: 0.5rem 1rem; border-radius: 20px; box-shadow: var(--shadow-sm);">El chat está vacío. ¡Escribe el primer mensaje!</p>
                        </div>
                    @else
                        @foreach($conversacion->mensajes as $mensaje)
                            @php
                                $esMio = $mensaje->id_remitente === auth()->user()->id_usuario;
                            @endphp

                            <div class="chat-bubble {{ $esMio ? 'bubble-sent' : 'bubble-received' }}" style="position: relative; group">
                                
                                @if($esMio)
                                    <form action="{{ route('chat.mensaje.eliminar', $mensaje->id_mensaje) }}" method="POST" style="position: absolute; top: -8px; right: -8px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete-msg" title="Eliminar mensaje" style="background: white; border: 1px solid #ef4444; color: #ef4444; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                            <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                        </button>
                                    </form>
                                @endif

                                @if(!$esMio)
                                    <div style="font-size: 0.75rem; font-weight: bold; margin-bottom: 0.25rem; color: var(--color-primary-dark);">
                                        {{ $mensaje->remitente->nombre }}
                                    </div>
                                @endif

                                @if($mensaje->contenido)
                                    <p style="margin: 0; word-break: break-word;">{{ $mensaje->contenido }}</p>
                                @endif

                                @if($mensaje->tipo === 'archivo' && $mensaje->archivos->count() > 0)
                                    <div style="margin-top: 0.5rem; display: flex; flex-direction: column; gap: 0.25rem;">
                                        @foreach($mensaje->archivos as $archivo)
                                            <div style="padding: 0.5rem; background: {{ $esMio ? 'rgba(0,0,0,0.1)' : 'rgba(0,0,0,0.05)' }}; border-radius: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                                                <svg style="width: 16px; height: 16px; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                <a href="{{ route('chat.descargar', $archivo->id_archivo) }}" style="color: inherit; text-decoration: underline; font-size: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                                    {{ $archivo->nombre_original }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div style="font-size: 0.65rem; text-align: right; margin-top: 0.25rem; opacity: 0.7;">
                                    {{ $mensaje->created_at->format('H:i') }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="chat-input-wrapper" style="display: flex; flex-direction: column; background: var(--color-white); border-top: 1px solid var(--color-gray-200); padding: 1rem 1.5rem;">
                    
                    <div id="previewContainer" style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.5rem; empty-cells: hide;"></div>

                    <form action="{{ route('chat.store') }}" method="POST" enctype="multipart/form-data" class="chat-input-form" style="display: flex; gap: 1rem; align-items: center; background: var(--color-gray-100); padding: 0.5rem 0.5rem 0.5rem 1rem; border-radius: 30px;">
                        @csrf
                        <input type="hidden" name="id_conversacion" value="{{ $conversacion->id_conversacion }}">
                        
                        <div class="btn-attachment" style="position: relative; cursor: pointer; color: var(--color-gray-500); transition: 0.2s;">
                            <svg style="width: 22px; height: 22px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <input type="file" name="archivo[]" id="archivoInput" multiple style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;" title="Adjuntar archivos">
                        </div>

                        <input type="text" name="contenido" placeholder="Escribe un mensaje..." style="flex: 1; border: none; background: transparent; outline: none; box-shadow: none; font-size: 0.95rem;" autocomplete="off">

                        <button type="submit" class="btn-send-chat" style="background: var(--color-primary-dark); color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 20px; font-weight: bold; cursor: pointer; transition: 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            Enviar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
        <div id="modalNuevoChat" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
            <div style="background: white; padding: 2rem; border-radius: var(--radius-xl); width: 100%; max-width: 400px; box-shadow: var(--shadow-xl);">
                <h3 style="font-size: var(--text-lg); font-weight: bold; margin-bottom: 1rem; color: var(--color-primary-dark);">Iniciar Nuevo Chat</h3>
                <form action="{{ route('chat.nueva') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1.5rem;">
                        <select name="id_usuario" required style="width: 100%; border-radius: 0.5rem; border: 1px solid var(--color-gray-300); padding: 0.75rem;">
                            <option value="">-- Elige un cliente --</option>
                            @foreach($usuariosParaChat as $cliente)
                                <option value="{{ $cliente->id_usuario }}">{{ $cliente->nombre }} {{ $cliente->apellidos }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                        <button type="button" onclick="document.getElementById('modalNuevoChat').style.display='none'" style="background: transparent; border: none; padding: 0.5rem 1rem; cursor: pointer;">Cancelar</button>
                        <button type="submit" style="background: var(--color-primary-dark); color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer; font-weight: bold;">Crear Chat</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Auto-scroll al fondo
            var caja = document.getElementById('cajaMensajes');
            if(caja) caja.scrollTop = caja.scrollHeight;

            // 2. Lógica del Buscador
            var searchInput = document.getElementById('chatSearch');
            if(searchInput) {
                searchInput.addEventListener('keyup', function(e) {
                    let text = e.target.value.toLowerCase();
                    let items = document.querySelectorAll('.chat-list-item');
                    
                    items.forEach(function(item) {
                        let name = item.querySelector('.chat-item-name').innerText.toLowerCase();
                        if(name.indexOf(text) > -1) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            // 3. Previsualización de Archivos con opción de Borrar
            var fileInput = document.getElementById('archivoInput');
            var previewContainer = document.getElementById('previewContainer');
            
            // Usamos DataTransfer como un "carrito de compras" virtual para nuestros archivos
            var currentFiles = new DataTransfer();

            if(fileInput && previewContainer) {
                fileInput.addEventListener('change', function(e) {
                    // Cada vez que el usuario selecciona archivos nuevos, reiniciamos el carrito
                    currentFiles = new DataTransfer();
                    
                    if(this.files && this.files.length > 0) {
                        for(let i = 0; i < this.files.length; i++) {
                            currentFiles.items.add(this.files[i]);
                        }
                    }
                    actualizarVistaArchivos();
                });

                function actualizarVistaArchivos() {
                    previewContainer.innerHTML = ''; // Limpiar anteriores
                    
                    if(currentFiles.files.length > 0) {
                        Array.from(currentFiles.files).forEach(function(file, index) {
                            var tag = document.createElement('div');
                            tag.style.cssText = 'background: #e5edff; color: #1e40af; padding: 0.2rem 0.4rem 0.2rem 0.6rem; border-radius: 12px; font-size: 0.75rem; display: flex; align-items: center; gap: 6px; border: 1px solid #bfdbfe; transition: 0.2s;';
                            
                            tag.innerHTML = `
                                <svg style="width:12px; height:12px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                <span style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${file.name}</span>
                                <button type="button" class="btn-remove-file" data-index="${index}" title="Quitar archivo" style="background: rgba(239, 68, 68, 0.1); border: none; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #ef4444; margin-left: 2px;">
                                    <svg style="width:12px; height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            `;
                            previewContainer.appendChild(tag);
                        });

                        // Activar los botones de "X" que acabamos de crear
                        document.querySelectorAll('.btn-remove-file').forEach(function(btn) {
                            btn.addEventListener('click', function() {
                                const indexToRemove = parseInt(this.getAttribute('data-index'));
                                
                                // Lo quitamos de nuestro carrito virtual
                                currentFiles.items.remove(indexToRemove);
                                
                                // Actualizamos el input real del formulario para que no se envíe al servidor
                                fileInput.files = currentFiles.files;
                                
                                // Volvemos a pintar las etiquetas
                                actualizarVistaArchivos();
                            });
                        });
                    }
                }
            }
        });
    </script>
</x-app-layout>