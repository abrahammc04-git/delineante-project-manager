<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proyectos') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/chatbot-styles.css') }}">

    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- BOX SUPERIOR: Título izquierda + Botones derecha -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <div style="padding: 1.5rem 1.5rem 0 1.5rem;">
        <div style="
            background: linear-gradient(135deg, #0033CC, #001F7A);
            border-radius: 14px;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        ">
            <!-- Izquierda: texto -->
            <div>
                <h1 style="color: white; font-size: 1.5rem; font-weight: 700; margin: 0 0 0.25rem 0;">
                    Todos los Proyectos
                </h1>
                <p style="color: rgba(255,255,255,0.8); font-size: 0.9rem; margin: 0;">
                    Gestiona y consulta todos los proyectos registrados
                </p>
            </div>

            <!-- Derecha: botones -->
            <div style="display: flex; gap: 0.65rem; align-items: center; flex-shrink: 0;">
                <a href="{{ route('dashboard') }}" style="
                    background: rgba(255,255,255,0.15);
                    color: white;
                    padding: 0.6rem 1.15rem;
                    border-radius: 8px;
                    font-weight: 600;
                    font-size: 0.875rem;
                    text-decoration: none;
                    border: 1px solid rgba(255,255,255,0.3);
                    transition: background 0.2s;
                ">← Dashboard</a>

                @if(Auth::user()->isAdmin())
                    <button id="btnToggleChatbot" class="btn-toggle-chatbot">
                        💬 Asistente IA
                    </button>

                    <a href="{{ route('proyectos.create') }}" style="
                        background: white;
                        color: #0033CC;
                        padding: 0.6rem 1.15rem;
                        border-radius: 8px;
                        font-weight: 600;
                        font-size: 0.875rem;
                        text-decoration: none;
                        transition: box-shadow 0.2s;
                    ">+ Nuevo Proyecto</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Layout principal: 2 columnas (tabla + chat) -->
    <div class="proyectos-chat-layout">
        
        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- PANEL IZQUIERDO: Tabla de Proyectos -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <div class="proyectos-panel">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="proyectos-panel-header">
                <button id="btnLimpiarFiltro" class="btn-limpiar-filtro" style="display: none;">
                    🔄 Mostrar Todos
                </button>
            </div>

            <!-- Buscador -->
            <div class="buscador-container" style="margin-bottom: 1rem;">
                <input 
                    type="text" 
                    id="buscadorProyectos" 
                    placeholder="🔍 Buscar por proyecto, cliente o empresa..."
                    style="width: 100%; padding: 0.72rem 1rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.9rem; outline: none;"
                    autocomplete="off"
                >
            </div>

            <!-- Tabla de Proyectos -->
            <div class="proyectos-tabla-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>PROYECTO</th>
                            <th>CLIENTE</th>
                            <th>EMPRESA</th>
                            <th>ESTADO</th>
                            <th>TIPO</th>
                            <th>FECHA INICIO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="tablaProyectos">
                        @forelse($proyectos as $proyecto)
                            <tr class="fila-proyecto" data-id="{{ $proyecto->id_proyecto }}">
                                <td data-nombre="{{ strtolower($proyecto->nombre_proyecto) }}">
                                    <strong>{{ $proyecto->nombre_proyecto }}</strong>
                                </td>
                                <td data-cliente="{{ strtolower($proyecto->usuario->nombre . ' ' . $proyecto->usuario->apellidos) }}">
                                    {{ $proyecto->usuario->nombre }} {{ $proyecto->usuario->apellidos }}
                                </td>
                                <td data-empresa="{{ strtolower($proyecto->usuario->empresa ?? '') }}">
                                    {{ $proyecto->usuario->empresa ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($proyecto->estado == 'Completado') badge-completado
                                        @elseif($proyecto->estado == 'En proceso') badge-en-proceso
                                        @elseif($proyecto->estado == 'Pendiente') badge-pendiente
                                        @elseif($proyecto->estado == 'Pausado') badge-pausado
                                        @elseif($proyecto->estado == 'Cancelado') badge-cancelado
                                        @endif">
                                        {{ $proyecto->estado }}
                                    </span>
                                </td>
                                <td>{{ $proyecto->tipo_proyecto }}</td>
                                <td>{{ $proyecto->fecha_inicio ? $proyecto->fecha_inicio->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('proyectos.show', $proyecto->id_proyecto) }}" 
                                       style="color: #0033CC; font-weight: 600; text-decoration: none;">
                                        Ver Detalles
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr id="filaVacia">
                                <td colspan="7" style="text-align: center; padding: 2rem; color: #6b7280;">
                                    No hay proyectos disponibles.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- PANEL DERECHO: Chatbot (Solo Admin) - COLAPSABLE -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        @if(Auth::user()->isAdmin())
        <div class="chat-panel" id="chatPanel" style="display: none;">
            
            <!-- Header del Chat -->
            <div class="chat-panel-header">
                <div style="display: flex; align-items: center; gap: 0.75rem; flex: 1;">
                    <div class="chat-avatar-header">IA</div>
                    <div class="chat-header-info">
                        <div class="nombre">Asistente PROYINSTAL</div>
                        <div class="estado">
                            <span class="punto-verde"></span>
                            En línea
                        </div>
                    </div>
                </div>
                <button id="btnCerrarChat" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; line-height: 1;">
                    ×
                </button>
            </div>

            <!-- Mensajes del Chat -->
            <div class="chat-mensajes" id="chatMensajes">
                <div class="mensaje mensaje-bot">
                    <div class="mensaje-avatar">IA</div>
                    <div class="mensaje-globo">
                        <p>¡Hola! Soy el Asistente IA de PROYINSTAL. Puedo <strong>filtrar proyectos en la tabla</strong> o responderte preguntas. Por ejemplo:</p>
                        <ul>
                            <li>📊 "Muéstrame los proyectos completados"</li>
                            <li>📅 "Proyectos de 2019"</li>
                            <li>👤 "Proyectos del cliente Juan"</li>
                            <li>🏢 "Proyectos de tipo Industrial"</li>
                            <li>❓ "¿Cuántos proyectos hay en proceso?"</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Input del Chat -->
            <div class="chat-input-area">
                <form id="chatForm" class="chat-input-row">
                    @csrf
                    <input 
                        type="text" 
                        id="chatInput"
                        placeholder="Escribe un mensaje..."
                        autocomplete="off"
                    >
                    <button type="submit" class="btn-enviar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" />
                        </svg>
                    </button>
                </form>
            </div>

        </div>
        @endif

    </div>

    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- Estilos -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <style>
        .btn-toggle-chatbot {
            background: rgba(255,255,255,0.15);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 0.6rem 1.15rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-toggle-chatbot:hover {
            background: rgba(255,255,255,0.25);
        }
        .btn-toggle-chatbot.activo {
            background: #059669;
            border-color: #059669;
        }

        .btn-limpiar-filtro {
            background: #fff;
            color: #059669;
            border: 2px solid #059669;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-limpiar-filtro:hover {
            background: #059669;
            color: #fff;
        }

        #buscadorProyectos:focus {
            border-color: #0033CC;
            box-shadow: 0 0 0 3px rgba(0,51,204,0.1);
        }

        .chat-panel {
            transition: all 0.3s ease-in-out;
        }
    </style>

    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- JavaScript: Buscador -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <script>
        const buscador = document.getElementById('buscadorProyectos');
        const filas = document.querySelectorAll('.fila-proyecto');
        const btnLimpiarFiltro = document.getElementById('btnLimpiarFiltro');

        // Restaurar filtro del chatbot al cargar
        window.addEventListener('DOMContentLoaded', function() {
            const filtroGuardado = sessionStorage.getItem('filtroProyectos');
            if (filtroGuardado) {
                aplicarFiltroTabla(JSON.parse(filtroGuardado));
                btnLimpiarFiltro.style.display = 'inline-block';
            }
        });

        // Limpiar filtro
        if (btnLimpiarFiltro) {
            btnLimpiarFiltro.addEventListener('click', function() {
                sessionStorage.removeItem('filtroProyectos');
                mostrarTodosProyectos();
                btnLimpiarFiltro.style.display = 'none';
                buscador.value = '';
            });
        }

        function mostrarTodosProyectos() {
            filas.forEach(fila => fila.style.display = '');
            const filaSinResultados = document.getElementById('filaSinResultados');
            if (filaSinResultados) filaSinResultados.remove();
        }

        // Buscador manual
        buscador.addEventListener('input', function() {
            sessionStorage.removeItem('filtroProyectos');
            btnLimpiarFiltro.style.display = 'none';

            const texto = this.value.toLowerCase().trim();
            let hayResultados = false;

            filas.forEach(fila => {
                const nombre  = fila.querySelector('[data-nombre]')?.getAttribute('data-nombre') || '';
                const cliente = fila.querySelector('[data-cliente]')?.getAttribute('data-cliente') || '';
                const empresa = fila.querySelector('[data-empresa]')?.getAttribute('data-empresa') || '';

                const coincide = nombre.includes(texto) || cliente.includes(texto) || empresa.includes(texto);

                if (coincide || texto === '') {
                    fila.style.display = '';
                    hayResultados = true;
                } else {
                    fila.style.display = 'none';
                }
            });

            const filaSinResultados = document.getElementById('filaSinResultados');
            if (!hayResultados && texto !== '') {
                if (!filaSinResultados) {
                    const tbody = document.getElementById('tablaProyectos');
                    const nuevaFila = document.createElement('tr');
                    nuevaFila.id = 'filaSinResultados';
                    nuevaFila.innerHTML = '<td colspan="7" style="text-align: center; padding: 2rem; color: #6b7280;">No se encontraron proyectos con ese criterio.</td>';
                    tbody.appendChild(nuevaFila);
                }
            } else if (filaSinResultados) {
                filaSinResultados.remove();
            }
        });

        function aplicarFiltroTabla(ids) {
            let hayResultados = false;

            filas.forEach(fila => {
                const id = parseInt(fila.getAttribute('data-id'));
                if (ids.includes(id)) {
                    fila.style.display = '';
                    hayResultados = true;
                } else {
                    fila.style.display = 'none';
                }
            });

            const tbody = document.getElementById('tablaProyectos');
            const filaSinResultados = document.getElementById('filaSinResultados');

            if (!hayResultados && !filaSinResultados) {
                const nuevaFila = document.createElement('tr');
                nuevaFila.id = 'filaSinResultados';
                nuevaFila.innerHTML = '<td colspan="7" style="text-align: center; padding: 2rem; color: #6b7280;">No se encontraron proyectos con ese criterio.</td>';
                tbody.appendChild(nuevaFila);
            } else if (hayResultados && filaSinResultados) {
                filaSinResultados.remove();
            }
        }
    </script>

    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- JavaScript: Toggle Chatbot -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    @if(Auth::user()->isAdmin())
    <script>
        const btnToggleChatbot = document.getElementById('btnToggleChatbot');
        const btnCerrarChat    = document.getElementById('btnCerrarChat');
        const chatPanel        = document.getElementById('chatPanel');

        btnToggleChatbot.addEventListener('click', function() {
            if (chatPanel.style.display === 'none') {
                chatPanel.style.display = 'flex';
                btnToggleChatbot.classList.add('activo');
                btnToggleChatbot.textContent = '✓ Asistente IA';
            } else {
                chatPanel.style.display = 'none';
                btnToggleChatbot.classList.remove('activo');
                btnToggleChatbot.textContent = '💬 Asistente IA';
            }
        });

        btnCerrarChat.addEventListener('click', function() {
            chatPanel.style.display = 'none';
            btnToggleChatbot.classList.remove('activo');
            btnToggleChatbot.textContent = '💬 Asistente IA';
        });
    </script>
    @endif

    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- JavaScript: Chatbot mensajes -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    @if(Auth::user()->isAdmin())
    <script>
        const chatForm      = document.getElementById('chatForm');
        const chatInput     = document.getElementById('chatInput');
        const chatMensajes  = document.getElementById('chatMensajes');

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const mensaje = chatInput.value.trim();
            if (!mensaje) return;

            agregarMensaje(mensaje, 'usuario');
            chatInput.value = '';

            const typingDiv = mostrarEscribiendo();

            try {
                const response = await fetch("{{ route('chatbot.consulta') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ mensaje })
                });

                const data = await response.json();
                typingDiv.remove();

                if (data.tipo === 'filtro') {
                    filtrarTablaDesdeChat(data.ids);
                    agregarMensaje(data.respuesta + ' 📊', 'bot');
                } else if (data.respuesta) {
                    agregarMensaje(data.respuesta, 'bot');
                } else {
                    agregarMensaje('No se pudo procesar la consulta.', 'bot');
                }

            } catch (error) {
                typingDiv.remove();
                agregarMensaje('Error de conexión. Inténtalo de nuevo.', 'bot');
            }
        });

        function filtrarTablaDesdeChat(ids) {
            sessionStorage.setItem('filtroProyectos', JSON.stringify(ids));
            document.getElementById('btnLimpiarFiltro').style.display = 'inline-block';
            aplicarFiltroTabla(ids);
            document.querySelector('.proyectos-tabla-wrap').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function agregarMensaje(texto, tipo) {
            const div = document.createElement('div');
            div.className = `mensaje mensaje-${tipo}`;
            div.innerHTML = `
                <div class="mensaje-avatar">${tipo === 'bot' ? 'IA' : 'TÚ'}</div>
                <div class="mensaje-globo">${texto}</div>
            `;
            chatMensajes.appendChild(div);
            chatMensajes.scrollTop = chatMensajes.scrollHeight;
        }

        function mostrarEscribiendo() {
            const div = document.createElement('div');
            div.className = 'typing-row';
            div.innerHTML = `
                <div class="mensaje-avatar">IA</div>
                <div class="typing-globo">
                    <span class="dot"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            `;
            chatMensajes.appendChild(div);
            chatMensajes.scrollTop = chatMensajes.scrollHeight;
            return div;
        }
    </script>
    @endif

</x-app-layout>