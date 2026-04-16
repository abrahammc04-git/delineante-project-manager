<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                <a href="{{ route('dashboard') }}" class="btn-box-dashboard">← Dashboard</a>
            </div>
        </div>
    </div>

    <div class="dashboard-container pt-8">
        
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '¡Hecho!',
                        text: "{{ session('success') }}",
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        borderRadius: '1rem'
                    });
                });
            </script>
        @endif

        <div class="flex h-[600px] bg-white rounded-3xl shadow-md border border-gray-200 overflow-hidden mb-8 max-md:flex-col max-md:h-screen">
            
            {{-- 1. SIDEBAR --}}
            <div class="w-1/3 flex flex-col bg-gray-50 border-r border-gray-200 max-md:w-full max-md:h-1/3 max-md:border-r-0 max-md:border-b">
                
                <div class="flex justify-between items-center p-5 bg-white">
                    <h3 class="text-lg font-bold text-gray-700 m-0">Tus Chats</h3>
                    @if(auth()->user()->isAdmin())
                        <button type="button" id="btnAbrirModal" class="w-7 h-7 flex items-center justify-center bg-[var(--color-primary-dark)] text-white rounded-full font-bold shadow-sm hover:scale-110 transition-transform" title="Crear nueva conversación">+</button>
                    @endif
                </div>

                @if(auth()->user()->isAdmin())
                <div class="px-6 pb-4 bg-white border-b border-gray-200">
                    <input type="text" id="chatSearch" placeholder="Buscar conversación..." class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm bg-gray-50 focus:outline-none focus:border-blue-500 focus:bg-white focus:shadow-sm transition-all">
                </div>
                @endif

                @if(auth()->user()->isAdmin())
                <div class="flex border-b border-gray-200">
                    <button id="tabActivos" onclick="switchTab('activos')" class="flex-1 p-3 font-bold text-sm text-[var(--color-primary-dark)] border-b-2 border-[var(--color-primary-dark)] bg-white transition-colors">Activos</button>
                    <button id="tabArchivados" onclick="switchTab('archivados')" class="flex-1 p-3 font-bold text-sm text-gray-500 border-b-2 border-transparent bg-gray-50 transition-colors">Archivados</button>
                </div>
                @endif
                
                <div class="flex-1 overflow-y-auto chat-scroll">
                    <ul class="m-0 p-0 list-none" id="listaChatsActivos">
                        @forelse($chatsActivos as $conv)
                            <li class="relative border-l-4 border-transparent border-b border-gray-100 transition-colors hover:bg-gray-50 group" id="item-chat-{{ $conv->id_conversacion }}">
                                <a href="javascript:void(0)" onclick="cargarChatSPA({{ $conv->id_conversacion }}, this)" class="block py-4 pl-6 pr-12 no-underline">
                                    <p class="m-0 py-1 text-sm font-semibold text-[var(--color-primary-dark)] truncate">{{ auth()->user()->isAdmin() ? $conv->usuario->nombre . ' ' . $conv->usuario->apellidos : 'Delineante' }}</p>
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <button type="button" onclick="confirmarBorradoChat({{ $conv->id_conversacion }})" class="absolute right-3 top-1/2 -translate-y-1/2 bg-transparent border-none text-gray-400 cursor-pointer hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100" title="Borrar Chat">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @endif
                            </li>
                        @empty
                            <li class="p-8 text-center text-gray-500 text-sm">No hay chats activos.</li>
                        @endforelse
                    </ul>

                    @if(auth()->user()->isAdmin())
                    <ul class="m-0 p-0 list-none hidden" id="listaChatsArchivados">
                        @forelse($chatsArchivados as $conv)
                            <li class="relative border-l-4 border-transparent border-b border-gray-100 transition-colors hover:bg-gray-50 group" id="item-chat-{{ $conv->id_conversacion }}">
                                <a href="javascript:void(0)" onclick="cargarChatSPA({{ $conv->id_conversacion }}, this)" class="block py-4 pl-6 pr-12 no-underline opacity-70">
                                    <p class="m-0 py-1 text-sm font-semibold text-[var(--color-primary-dark)] truncate">{{ $conv->usuario->nombre }} {{ $conv->usuario->apellidos }}</p>
                                </a>
                                <button type="button" onclick="confirmarBorradoChat({{ $conv->id_conversacion }})" class="absolute right-3 top-1/2 -translate-y-1/2 bg-transparent border-none text-gray-400 cursor-pointer hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100" title="Borrar Chat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </li>
                        @empty
                            <li class="p-8 text-center text-gray-500 text-sm">No hay chats archivados.</li>
                        @endforelse
                    </ul>
                    @endif
                </div>
            </div>

            {{-- 2. MAIN AREA: PANTALLA VACÍA --}}
            <div id="chatEmptyArea" class="w-2/3 flex flex-col items-center justify-center bg-slate-50 border-l border-gray-200 max-md:w-full max-md:h-2/3 max-md:border-l-0">
                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <p class="text-lg text-gray-500 m-0">Selecciona un chat para empezar a escribir</p>
            </div>

            {{-- 3. MAIN AREA: CHAT ACTIVO --}}
            <div id="chatActiveArea" class="w-2/3 hidden flex-col bg-slate-50 border-l border-gray-200 max-md:w-full max-md:h-2/3 max-md:border-l-0">
                
                <div class="flex justify-between items-center p-5 bg-white border-b border-gray-200">
                    <h3 class="m-0 text-lg font-bold">
                        <span id="headerChatName" class="text-[var(--color-primary-dark)]"></span>
                    </h3>
                    @if(auth()->user()->isAdmin())
                        <button type="button" id="btnToggleArchivo" onclick="archivarDesarchivarSPA()" class="flex items-center gap-2 bg-white border border-gray-300 px-3 py-1.5 rounded-lg text-xs text-gray-600 hover:bg-gray-50 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            <span id="txtBtnArchivo">Archivar</span>
                        </button>
                    @endif
                </div>

                <div class="flex-1 p-6 overflow-y-auto flex flex-col gap-4 chat-scroll" id="cajaMensajes"></div>

                <div class="flex flex-col bg-white border-t border-gray-200 p-4 px-6">
                    <div id="previewContainer" class="flex flex-wrap gap-2 mb-2 empty:hidden"></div>

                    <form id="chatForm" action="{{ route('chat.store') }}" method="POST" enctype="multipart/form-data" class="flex gap-4 items-center bg-gray-100 p-2 pl-4 rounded-full">
                        @csrf
                        <input type="hidden" name="id_conversacion" id="inputHiddenIdConv" value="">
                        
                        <div class="relative cursor-pointer text-gray-500 hover:text-[var(--color-primary-dark)] hover:scale-110 hover:-rotate-6 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <input type="file" name="archivo[]" id="archivoInput" multiple accept="*/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>

                        <input type="text" name="contenido" id="chatContenido" maxlength="400" placeholder="Escribe un mensaje..." autocomplete="off" class="flex-1 border-none bg-transparent outline-none shadow-none text-sm text-gray-700 focus:ring-0 p-0">

                        <button type="submit" id="btnSubmitChat" class="bg-[var(--color-primary-dark)] text-white border-none py-2.5 px-5 rounded-full font-bold cursor-pointer transition-all hover:-translate-y-0.5 hover:brightness-110 hover:shadow-md disabled:opacity-50">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <div id="modalNuevoChat" class="fixed inset-0 w-full h-full bg-black/60 z-[9999] items-center justify-center hidden">
        <div class="bg-white p-8 rounded-2xl w-full max-w-sm shadow-xl chat-modal-animate">
            <h3 class="text-xl font-bold mb-4 text-[var(--color-primary-dark)]">Iniciar Nuevo Chat</h3>
            <form action="{{ route('chat.nueva') }}" method="POST">
                @csrf
                <div class="mb-6">
                    @if($usuariosParaChat->isEmpty())
                        <p class="text-gray-500 text-sm">Todos los clientes ya tienen una conversación activa.</p>
                    @else
                        <select name="id_usuario" required class="w-full rounded-lg border border-gray-300 p-3 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                            <option value="">-- Elige un cliente --</option>
                            @foreach($usuariosParaChat as $cliente)
                                <option value="{{ $cliente->id_usuario }}">{{ $cliente->nombre }} {{ $cliente->apellidos }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" id="btnCerrarModal" class="bg-transparent border border-gray-300 px-4 py-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors text-sm font-semibold text-gray-600">Cancelar</button>
                    @if(!$usuariosParaChat->isEmpty())
                        <button type="submit" class="bg-[var(--color-primary-dark)] text-white border-none px-4 py-2 rounded-lg cursor-pointer font-bold hover:brightness-110 transition-all text-sm shadow-sm">Crear Chat</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @endif

    <script>
        var currentFiles = new DataTransfer();
        var miUsuarioId = parseInt("{{ auth()->user()->id_usuario }}");
        var isAdmin = {{ auth()->user()->isAdmin() ? 'true' : 'false' }};
        var tokenCsrf = document.querySelector('input[name="_token"]')?.value;
        var chatActivoId = null;
        var canalReverbActual = null;

        function switchTab(tab) {
            const listActivos = document.getElementById('listaChatsActivos');
            const listArchivados = document.getElementById('listaChatsArchivados');
            const tabActivos = document.getElementById('tabActivos');
            const tabArchivados = document.getElementById('tabArchivados');

            if(tab === 'activos') {
                listActivos.classList.remove('hidden');
                if(listArchivados) listArchivados.classList.add('hidden');
                
                tabActivos.classList.add('text-[var(--color-primary-dark)]', 'border-[var(--color-primary-dark)]', 'bg-white');
                tabActivos.classList.remove('text-gray-500', 'border-transparent', 'bg-gray-50');
                
                if(tabArchivados) {
                    tabArchivados.classList.add('text-gray-500', 'border-transparent', 'bg-gray-50');
                    tabArchivados.classList.remove('text-[var(--color-primary-dark)]', 'border-[var(--color-primary-dark)]', 'bg-white');
                }
            } else {
                listActivos.classList.add('hidden');
                if(listArchivados) listArchivados.classList.remove('hidden');
                
                if(tabArchivados) {
                    tabArchivados.classList.add('text-[var(--color-primary-dark)]', 'border-[var(--color-primary-dark)]', 'bg-white');
                    tabArchivados.classList.remove('text-gray-500', 'border-transparent', 'bg-gray-50');
                }
                
                tabActivos.classList.add('text-gray-500', 'border-transparent', 'bg-gray-50');
                tabActivos.classList.remove('text-[var(--color-primary-dark)]', 'border-[var(--color-primary-dark)]', 'bg-white');
            }
        }

        window.cargarChatSPA = function(id, element) {
            chatActivoId = id;
            document.getElementById('inputHiddenIdConv').value = id;

            // Manejo visual de chat seleccionado (Tailwind classes)
            document.querySelectorAll('.chat-list-item').forEach(el => {
                el.classList.remove('bg-gray-100', 'border-l-[var(--color-primary-dark)]');
                el.classList.add('border-transparent');
            });
            let parentLi = document.getElementById('item-chat-' + id);
            if(parentLi) {
                parentLi.classList.remove('border-transparent');
                parentLi.classList.add('bg-gray-100', 'border-l-[var(--color-primary-dark)]');
            }

            document.getElementById('chatEmptyArea').classList.add('hidden');
            document.getElementById('chatEmptyArea').classList.remove('flex');
            
            document.getElementById('chatActiveArea').classList.remove('hidden');
            document.getElementById('chatActiveArea').classList.add('flex');
            
            document.getElementById('cajaMensajes').innerHTML = '<p class="text-center text-gray-400 mt-8 text-sm">Cargando mensajes...</p>';

            if (canalReverbActual && typeof window.Echo !== 'undefined') window.Echo.leave(canalReverbActual);

            fetch('/chat/api/conversacion/' + id)
            .then(res => res.json())
            .then(data => {
                if(isAdmin) {
                    document.getElementById('headerChatName').innerText = data.conversacion.usuario.nombre + ' ' + data.conversacion.usuario.apellidos;
                    let btnArchivo = document.getElementById('btnToggleArchivo');
                    let txtArchivo = document.getElementById('txtBtnArchivo');
                    if(data.conversacion.archivada) {
                        txtArchivo.innerText = 'Desarchivar';
                        btnArchivo.classList.add('bg-amber-100', 'border-amber-300', 'text-amber-800');
                        btnArchivo.classList.remove('bg-white', 'text-gray-600');
                    } else {
                        txtArchivo.innerText = 'Archivar';
                        btnArchivo.classList.remove('bg-amber-100', 'border-amber-300', 'text-amber-800');
                        btnArchivo.classList.add('bg-white', 'text-gray-600');
                    }
                } else {
                    document.getElementById('headerChatName').innerText = 'Delineante';
                }

                document.getElementById('cajaMensajes').innerHTML = '';
                if(data.mensajes.length === 0) {
                    document.getElementById('cajaMensajes').innerHTML = '<div class="h-full flex items-center justify-center"><p class="text-gray-500 bg-white px-4 py-2 rounded-full shadow-sm text-sm m-0">El chat está vacío. ¡Escribe el primer mensaje!</p></div>';
                } else {
                    data.mensajes.forEach(msj => renderizarBurbuja(msj, msj.id_remitente === miUsuarioId));
                }

                canalReverbActual = 'chat.' + id;
                if (typeof window.Echo !== 'undefined') {
                    window.Echo.private(canalReverbActual)
                        .listen('MensajeEnviado', (e) => {
                            if (e.mensaje.id_remitente !== miUsuarioId) renderizarBurbuja(e.mensaje, false);
                        })
                        .listen('MensajeEliminado', (e) => {
                            let msgBox = document.getElementById('msg-' + e.id_mensaje);
                            if(msgBox) msgBox.remove();
                        });
                }
            });
        };

        window.archivarDesarchivarSPA = function() {
            if(!chatActivoId) return;
            fetch('/chat/api/conversacion/' + chatActivoId + '/archivar', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': tokenCsrf, 'Accept': 'application/json' }
            }).then(res => res.json()).then(data => {
                if(data.success) {
                    Swal.fire({
                        title: 'Éxito', text: data.mensaje, icon: 'success', timer: 1500, showConfirmButton: false, borderRadius: '1rem'
                    }).then(() => window.location.reload());
                }
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('abrir_chat'))
                setTimeout(() => {
                    let newId = {{ session('abrir_chat') }};
                    cargarChatSPA(newId, document.getElementById('item-chat-' + newId));
                }, 100);
            @endif

            const btnAbrir = document.getElementById('btnAbrirModal');
            const btnCerrar = document.getElementById('btnCerrarModal');
            const modal = document.getElementById('modalNuevoChat');
            
            if (btnAbrir && modal) btnAbrir.addEventListener('click', (e) => { 
                e.preventDefault(); 
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
            if (btnCerrar && modal) btnCerrar.addEventListener('click', (e) => { 
                e.preventDefault(); 
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
            window.addEventListener('click', (e) => { 
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });

            document.getElementById('chatSearch')?.addEventListener('keyup', function(e) {
                let text = e.target.value.toLowerCase();
                document.querySelectorAll('.chat-list-item').forEach(function(item) {
                    let name = item.querySelector('.chat-item-name').innerText.toLowerCase();
                    if (name.includes(text)) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });

            var fileInput = document.getElementById('archivoInput');
            var previewContainer = document.getElementById('previewContainer');
            if(fileInput && previewContainer) {
                fileInput.addEventListener('change', function(e) {
                    if(this.files && this.files.length > 0) {
                        for(let i = 0; i < this.files.length; i++) currentFiles.items.add(this.files[i]);
                    }
                    actualizarVistaArchivos();
                });

                function actualizarVistaArchivos() {
                    previewContainer.innerHTML = '';
                    if(currentFiles.files.length > 0) {
                        Array.from(currentFiles.files).forEach(function(file, index) {
                            var tag = document.createElement('div');
                            tag.className = 'flex items-center gap-1.5 bg-blue-50 text-blue-800 px-3 py-1 rounded-full text-xs border border-blue-200 shadow-sm';
                            tag.innerHTML = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                             <span class="max-w-[150px] truncate">${file.name}</span>
                                             <button type="button" class="btn-remove-preview bg-red-100/50 text-red-500 rounded-full w-4 h-4 flex items-center justify-center cursor-pointer hover:bg-red-200 transition-colors" data-index="${index}"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>`;
                            previewContainer.appendChild(tag);
                        });
                        document.querySelectorAll('.btn-remove-preview').forEach(function(btn) {
                            btn.addEventListener('click', function() {
                                currentFiles.items.remove(parseInt(this.getAttribute('data-index')));
                                fileInput.files = currentFiles.files;
                                actualizarVistaArchivos();
                            });
                        });
                    }
                }
            }

            document.getElementById('chatForm')?.addEventListener('submit', function(e) {
                e.preventDefault(); 
                let formData = new FormData(this);
                let btn = document.getElementById('btnSubmitChat');
                btn.disabled = true; btn.innerText = '...';

                let fetchHeaders = { 'Accept': 'application/json' };
                if (typeof window.Echo !== 'undefined' && window.Echo.socketId()) { fetchHeaders['X-Socket-Id'] = window.Echo.socketId(); }

                fetch(this.action, { method: 'POST', body: formData, headers: fetchHeaders })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        renderizarBurbuja(data.mensaje, true);
                        document.getElementById('chatContenido').value = '';
                        previewContainer.innerHTML = '';
                        currentFiles = new DataTransfer(); fileInput.files = currentFiles.files;
                    }
                }).finally(() => { btn.disabled = false; btn.innerText = 'Enviar'; });
            });
        });

        function renderizarBurbuja(msj, esMio) {
            let cajaMensajes = document.getElementById('cajaMensajes');
            let emptyMsg = document.getElementById('emptyChat');
            if (emptyMsg) emptyMsg.remove();

            let archivosHtml = '';
            if (msj.tipo === 'archivo' && msj.archivos && msj.archivos.length > 0) {
                archivosHtml = `<div class="flex flex-col gap-1.5 mt-2">`;
                msj.archivos.forEach(archivo => {
                    archivosHtml += `<div class="flex items-center gap-2 p-2 rounded-lg ${esMio ? 'bg-black/10' : 'bg-black/5'}">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                        <a href="/chat/descargar/${archivo.id_archivo}" class="text-inherit underline text-xs font-semibold truncate max-w-[200px] hover:text-blue-800">${archivo.nombre_original}</a>
                                     </div>`;
                });
                archivosHtml += `</div>`;
            }

            let f = new Date(msj.created_at);
            let hora = f.getHours().toString().padStart(2, '0') + ':' + f.getMinutes().toString().padStart(2, '0');

            let btnBorrar = esMio ? `<button type="button" class="absolute -top-2 -right-2 bg-white border border-red-500 text-red-500 rounded-full w-5 h-5 flex items-center justify-center cursor-pointer shadow-sm hover:bg-red-100 transition-colors opacity-0 group-hover:opacity-100" onclick="borrarMensaje('${msj.id_mensaje}')" title="Eliminar mensaje"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>` : '';
            let nombreMostrar = isAdmin ? (msj.remitente ? msj.remitente.nombre : '') : 'Delineante';

            let claseBurbuja = esMio 
                ? 'self-end bg-blue-800 text-white rounded-br-none shadow-sm' 
                : 'self-start bg-white border border-gray-200 text-gray-800 rounded-bl-none shadow-sm';

            let burbuja = `
                <div id="msg-${msj.id_mensaje}" class="relative group max-w-[75%] p-3 rounded-2xl text-sm leading-relaxed ${claseBurbuja}">
                    ${btnBorrar}
                    ${!esMio && msj.remitente ? `<div class="text-xs font-bold mb-1 text-[var(--color-primary-dark)]">${nombreMostrar}</div>` : ''}
                    ${msj.contenido ? `<p class="m-0 break-words">${msj.contenido}</p>` : ''}
                    ${archivosHtml}
                    <div class="text-[10px] text-right mt-1 opacity-70">${hora}</div>
                </div>`;

            cajaMensajes.insertAdjacentHTML('beforeend', burbuja);
            cajaMensajes.scrollTop = cajaMensajes.scrollHeight;
        }

        window.confirmarBorradoChat = function(id) {
            Swal.fire({
                title: '¿Eliminar conversación?', text: "Se borrarán todos los mensajes.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: 'Eliminar', borderRadius: '1rem'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/chat/conversacion/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': tokenCsrf } }).then(() => window.location.reload());
                }
            });
        };

        window.borrarMensaje = function(id) {
            Swal.fire({
                title: '¿Borrar mensaje?', text: "Se eliminará para ambos.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#6b7280', confirmButtonText: 'Borrar', borderRadius: '1rem'
            }).then((result) => {
                if (result.isConfirmed) {
                    let h = { 'X-CSRF-TOKEN': tokenCsrf, 'Accept': 'application/json' };
                    if (window.Echo && window.Echo.socketId()) h['X-Socket-Id'] = window.Echo.socketId();
                    fetch('/chat/mensaje/' + id, { method: 'DELETE', headers: h }).then(r => r.json()).then(data => {
                        if(data.success) { let box = document.getElementById('msg-'+id); if(box) box.remove(); }
                    });
                }
            });
        };
    </script>
</x-app-layout>