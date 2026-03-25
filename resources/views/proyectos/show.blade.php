<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div class="dashboard-container">

        {{-- ── Box cabecera ──────────────────────────────────── --}}
        <div class="page-box">
            <div class="page-box-inner">
                <div class="page-box-titulo">
                    <h1>Información General</h1>
                    <p>Detalles completos del proyecto y gestión de documentos</p>
                </div>
                <div class="page-box-botones">
                    <a href="{{ route('proyectos.index') }}" class="btn-box-dashboard">← Volver</a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('proyectos.edit', $proyecto->id_proyecto) }}" class="btn-box-nuevo">
                            ✏️ Editar
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Grid principal: info + sidebar ────────────────── --}}
        <div class="show-grid">

            {{-- ── Columna izquierda: datos del proyecto ── --}}
            <div class="show-card">
                <h3 class="show-card-title">{{ $proyecto->nombre_proyecto }}</h3>

                <div class="show-data-grid">
                    <div class="show-field">
                        <p class="show-field-label">Cliente</p>
                        <p class="show-field-value">{{ $proyecto->usuario->nombre }} {{ $proyecto->usuario->apellidos }}</p>
                        <p class="show-field-sub">{{ $proyecto->usuario->email }}</p>
                    </div>

                    <div class="show-field">
                        <p class="show-field-label">Empresa</p>
                        <p class="show-field-value">{{ $proyecto->usuario->empresa ?? 'No especificada' }}</p>
                    </div>

                    <div class="show-field">
                        <p class="show-field-label">Tipo de Proyecto</p>
                        <p class="show-field-value">{{ $proyecto->tipo_proyecto }}</p>
                    </div>

                    <div class="show-field">
                        <p class="show-field-label">Estado Actual</p>
                        <span class="badge
                            @if($proyecto->estado == 'Completado') badge-completado
                            @elseif($proyecto->estado == 'En proceso') badge-en-proceso
                            @elseif($proyecto->estado == 'Pendiente') badge-pendiente
                            @elseif($proyecto->estado == 'Pausado') badge-pausado
                            @elseif($proyecto->estado == 'Cancelado') badge-cancelado
                            @endif">
                            {{ $proyecto->estado }}
                        </span>
                    </div>

                    <div class="show-field show-field-full">
                        <p class="show-field-label">Ubicación</p>
                        <p class="show-field-value">{{ $proyecto->localizacion ?? 'No especificada' }}</p>
                        @if($proyecto->direccion)
                            <p class="show-field-sub">{{ $proyecto->direccion }}</p>
                        @endif
                    </div>
                </div>

                @if($proyecto->descripcion)
                    <div class="show-description">
                        <p class="show-field-label">Descripción</p>
                        <div class="show-description-body">{{ $proyecto->descripcion }}</div>
                    </div>
                @endif
            </div>

            {{-- ── Columna derecha: cronograma + acciones ── --}}
            <div class="show-sidebar">

                {{-- Cronograma --}}
                <div class="show-card">
                    <h3 class="show-card-subtitle">Cronograma</h3>
                    <div class="show-dates">
                        <div class="show-field">
                            <p class="show-field-label">Fecha Inicio</p>
                            <p class="show-field-value">{{ $proyecto->fecha_inicio ? $proyecto->fecha_inicio->format('d/m/Y') : '—' }}</p>
                        </div>
                        <div class="show-field">
                            <p class="show-field-label">Fin Previsto</p>
                            <p class="show-field-value">{{ $proyecto->fecha_fin_prevista ? $proyecto->fecha_fin_prevista->format('d/m/Y') : '—' }}</p>
                        </div>
                        @if($proyecto->fecha_fin_real)
                            <div class="show-field">
                                <p class="show-field-label">Fin Real</p>
                                <p class="show-field-value show-field-success">{{ $proyecto->fecha_fin_real->format('d/m/Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Acciones admin --}}
                @if(Auth::user()->isAdmin())
                    <div class="show-card">
                        <h3 class="show-card-subtitle">Acciones</h3>
                        <div class="show-actions">
                            <a href="{{ route('proyectos.edit', $proyecto->id_proyecto) }}"
                               class="btn btn-primary btn-auto" style="width:100%; justify-content:center;">
                                Editar Proyecto
                            </a>
                            <form action="{{ route('proyectos.destroy', $proyecto->id_proyecto) }}"
                                  method="POST"
                                  onsubmit="confirmarBorrado(event, '{{ $proyecto->nombre_proyecto }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" style="width:100%; justify-content:center;">
                                    Eliminar Proyecto
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- ── Sección de Documentos ────────────────────────── --}}
        <div class="show-card show-docs">

            <div class="show-docs-header">
                <h3 class="show-card-subtitle">Gestión de Documentos</h3>
                @if(Auth::user()->isAdmin())
                    <button id="toggleUploadBtn" type="button" class="btn btn-primary btn-auto">
                        <svg style="height:1.25rem;width:1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Subir Nuevo Archivo
                    </button>
                @endif
            </div>

            {{-- Formulario de subida --}}
            @if(Auth::user()->isAdmin())
                <div id="uploadContainer" class="hidden show-upload-zone">
                    <form action="{{ route('proyectos.archivos.subir', ['id' => $proyecto->id_proyecto]) }}"
                          method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div id="dropzone" class="show-dropzone">
                            <input type="file" name="archivos[]" id="archivoInput" class="hidden" multiple accept=".pdf,.dwg,.dxf,.jpg,.jpeg,.png">

                            <div id="dropContent">
                                <svg style="margin:0 auto 1rem;height:3rem;width:3rem;color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="show-dropzone-text">
                                    <label for="archivoInput" class="show-dropzone-link">Selecciona archivos</label>
                                    <span> o arrastra y suelta aquí</span>
                                </div>
                                <p class="show-dropzone-hint">PDF, Imágenes, CAD (Máx 10MB)</p>
                            </div>

                            <div id="fileListPreview" class="hidden show-file-preview">
                                <p class="show-file-preview-title">Archivos listos para subir:</p>
                                <ul id="filesList" class="show-file-list"></ul>
                            </div>
                        </div>

                        <div id="uploadActions" class="hidden show-upload-actions">
                            <button type="submit" class="btn btn-primary btn-auto">
                                <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Subir Archivos
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- Header de lista de archivos --}}
            <div class="show-files-bar">
                <div class="show-files-bar-left">
                    <h4 class="show-files-title">Archivos Adjuntos</h4>
                    @if(isset($archivos) && count($archivos) > 0)
                        <label class="show-select-label">
                            <input type="checkbox" id="toggleSelectionMode" onchange="toggleSelectionMode()">
                            <span>Seleccionar varios</span>
                        </label>
                    @endif
                </div>

                <div class="show-files-bar-right">
                    @if(isset($archivos) && count($archivos) > 0)
                        <button type="button" id="btnDescargarSeleccionados" onclick="descargarSeleccionados()"
                                class="show-btn-download-sel" style="display:none;">
                            <svg style="width:1rem;height:1rem;display:inline;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Descargar (<span id="contadorSeleccionados">0</span>)
                        </button>
                    @endif

                    @if(Auth::user()->isAdmin() && isset($archivos) && count($archivos) > 0)
                        <button type="button" onclick="openShowModal()" class="show-btn-schedule show">
                            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Programar mostrar
                        </button>
                        <button type="button" onclick="openScheduleModal()" class="show-btn-schedule hide">
                            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Programar ocultar
                        </button>
                    @endif
                </div>
            </div>

            {{-- Lista de archivos --}}
            @if(isset($archivos) && count($archivos) > 0)
                <div class="show-file-items">
                    @foreach($archivos as $archivo)
                        @php $nombre = $archivo['nombre']; @endphp

                        <div class="show-file-item">
                            <input type="checkbox" class="archivo-checkbox" data-nombre="{{ $nombre }}"
                                   style="display:none;" onchange="actualizarContador()">

                            <div class="show-file-info">
                                <span class="show-file-icon">
                                    @if(Str::endsWith(Str::lower($nombre), ['.jpg','.png','.jpeg','.gif'])) 🖼️
                                    @elseif(Str::endsWith(Str::lower($nombre), ['.pdf'])) 📄
                                    @elseif(Str::endsWith(Str::lower($nombre), ['.dwg','.dxf'])) 📐
                                    @else 📎
                                    @endif
                                </span>
                                <div class="show-file-meta">
                                    <p class="show-file-name">{{ $nombre }}</p>
                                    <p class="show-file-size">{{ $archivo['size'] }} KB • {{ $archivo['fecha'] }}</p>
                                </div>

                                {{-- Badges de programación --}}
                                @if(isset($archivo['programado_mostrar']) && $archivo['programado_mostrar'])
                                    <span class="badge badge-en-proceso show-badge-schedule">
                                        Visible: {{ \Carbon\Carbon::parse($archivo['programado_mostrar'])->format('d/m H:i') }}
                                        @if(Auth::user()->isAdmin())
                                            <form action="{{ route('proyectos.archivos.cancelar_publicacion', ['id' => $proyecto->id_proyecto]) }}" method="POST" style="display:inline;">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="nombre_archivo" value="{{ $nombre }}">
                                                <button type="submit" class="show-badge-cancel">×</button>
                                            </form>
                                        @endif
                                    </span>
                                @endif

                                @if($archivo['programado'])
                                    <span class="badge badge-pendiente show-badge-schedule">
                                        {{ \Carbon\Carbon::parse($archivo['programado'])->format('d/m H:i') }}
                                        @if(Auth::user()->isAdmin())
                                            <form action="{{ route('proyectos.archivos.cancelar', ['id' => $proyecto->id_proyecto]) }}" method="POST" style="display:inline;">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="nombre_archivo" value="{{ $nombre }}">
                                                <button type="submit" class="show-badge-cancel">×</button>
                                            </form>
                                        @endif
                                    </span>
                                @endif
                            </div>

                            <div class="show-file-actions">
                                @if(Auth::user()->isAdmin())
                                    <form action="{{ route('proyectos.archivos.toggle', ['id' => $proyecto->id_proyecto]) }}" method="POST" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="nombre_archivo" value="{{ $nombre }}">
                                        <button type="submit" class="show-btn-eye" title="{{ $archivo['visible'] ? 'Visible' : 'Oculto' }}">
                                            @if($archivo['visible'])
                                                <svg style="width:1.25rem;height:1.25rem;color:#059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            @else
                                                <svg style="width:1.25rem;height:1.25rem;color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 013.999-5.42m5.06-2.106c.361-.055.733-.085 1.11-.085 4.478 0 8.268 2.943 9.542 7a10.057 10.057 0 01-2.029 3.56M15 12a3 3 0 01-3 3m0 0a3 3 0 01-3-3m0 0a3 3 0 013-3m-3 3l-6.364-6.364M21 21l-6.364-6.364"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('proyectos.archivos.descargar', ['id' => $proyecto->id_proyecto, 'nombreArchivo' => $nombre]) }}"
                                   class="show-btn-download">
                                    <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>

                                @if(Auth::user()->isAdmin())
                                    <form action="{{ route('proyectos.archivos.eliminar', ['id' => $proyecto->id_proyecto, 'nombreArchivo' => $nombre]) }}"
                                          method="POST" onsubmit="return confirm('¿Eliminar este archivo?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="show-btn-delete">Eliminar</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="show-empty-docs">
                    <p class="show-empty-title">No hay documentos adjuntos</p>
                    <p class="show-empty-sub">Los archivos del proyecto aparecerán aquí</p>
                </div>
            @endif
        </div>

    </div>{{-- /dashboard-container --}}

    {{-- ── MODAL: Programar MOSTRAR ───────────────────────── --}}
    <div id="scheduleShowModal" class="show-modal hidden">
        <div class="show-modal-overlay" onclick="closeShowModal()"></div>
        <div class="show-modal-box">
            <form method="POST" action="{{ route('proyectos.archivos.programar_publicacion', ['id' => $proyecto->id_proyecto]) }}">
                @csrf
                <div class="show-modal-body">
                    <h3 class="show-modal-title info">Programar aparición de archivos</h3>
                    <div class="form-group">
                        <label class="form-label">Fecha y Hora para mostrarse</label>
                        <input class="form-input" type="datetime-local" name="fecha_publicacion" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Selecciona los archivos:</label>
                        <div class="show-modal-filelist">
                            @if(isset($archivos))
                                @foreach($archivos as $archivo)
                                    <label class="show-modal-file-row">
                                        <input name="archivos_seleccionados[]" value="{{ $archivo['nombre'] }}" type="checkbox">
                                        <span>{{ $archivo['nombre'] }}</span>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="show-modal-footer">
                    <button type="button" onclick="closeShowModal()" class="btn btn-secondary btn-auto">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-auto">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL: Programar OCULTAR ───────────────────────── --}}
    <div id="scheduleModal" class="show-modal hidden">
        <div class="show-modal-overlay" onclick="closeScheduleModal()"></div>
        <div class="show-modal-box">
            <form method="POST" action="{{ route('proyectos.archivos.programar', ['id' => $proyecto->id_proyecto]) }}">
                @csrf
                <div class="show-modal-body">
                    <h3 class="show-modal-title warning">Programar ocultación de archivos</h3>
                    <div class="form-group">
                        <label class="form-label">Fecha y Hora de ocultación</label>
                        <input class="form-input" type="datetime-local" name="fecha_ocultacion" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Selecciona los archivos:</label>
                        <div class="show-modal-filelist">
                            @if(isset($archivos))
                                @foreach($archivos as $archivo)
                                    <label class="show-modal-file-row">
                                        <input name="archivos_seleccionados[]" value="{{ $archivo['nombre'] }}" type="checkbox">
                                        <span>{{ $archivo['nombre'] }}</span>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="show-modal-footer">
                    <button type="button" onclick="closeScheduleModal()" class="btn btn-secondary btn-auto">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-auto">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── JavaScript ─────────────────────────────────────── --}}
    <script>
    // ─── Selección múltiple ───────────────────────────────
    function toggleSelectionMode() {
        const on = document.getElementById('toggleSelectionMode').checked;
        document.querySelectorAll('.archivo-checkbox').forEach(cb => {
            cb.style.display = on ? 'block' : 'none';
            if (!on) cb.checked = false;
        });
        document.getElementById('btnDescargarSeleccionados').style.display = on ? 'inline-flex' : 'none';
        actualizarContador();
    }

    function actualizarContador() {
        const n = document.querySelectorAll('.archivo-checkbox:checked').length;
        const el = document.getElementById('contadorSeleccionados');
        if (el) el.textContent = n;
    }

    function descargarSeleccionados() {
        const checked = document.querySelectorAll('.archivo-checkbox:checked');
        if (!checked.length) { alert('Selecciona al menos un archivo'); return; }
        checked.forEach((cb, i) => {
            setTimeout(() => {
                const url = `{{ route('proyectos.archivos.descargar', ['id' => $proyecto->id_proyecto, 'nombreArchivo' => '__N__']) }}`
                    .replace('__N__', encodeURIComponent(cb.dataset.nombre));
                const a = document.createElement('a');
                a.href = url; a.download = cb.dataset.nombre;
                document.body.appendChild(a); a.click(); document.body.removeChild(a);
            }, i * 300);
        });
        setTimeout(() => { checked.forEach(cb => cb.checked = false); actualizarContador(); }, checked.length * 300 + 500);
    }

    // ─── Toggle formulario de subida ─────────────────────
    const toggleBtn = document.getElementById('toggleUploadBtn');
    const uploadContainer = document.getElementById('uploadContainer');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            uploadContainer.classList.toggle('hidden');
            toggleBtn.textContent = uploadContainer.classList.contains('hidden')
                ? '+ Subir Nuevo Archivo' : 'Cancelar';
            if (uploadContainer.classList.contains('hidden')) { storedFiles = []; updateUI(); }
        });
    }

    // ─── Drag & Drop ─────────────────────────────────────
    const dropzone   = document.getElementById('dropzone');
    const fileInput  = document.getElementById('archivoInput');
    const dropContent    = document.getElementById('dropContent');
    const fileListPreview = document.getElementById('fileListPreview');
    const filesListUl    = document.getElementById('filesList');
    const uploadActions  = document.getElementById('uploadActions');
    let storedFiles = [];

    if (dropzone && fileInput) {
        dropzone.addEventListener('click', e => { if (!e.target.closest('button')) fileInput.click(); });
        dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('dragging'); });
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragging'));
        dropzone.addEventListener('drop', e => { e.preventDefault(); dropzone.classList.remove('dragging'); handleFiles(e.dataTransfer.files); });
        fileInput.addEventListener('change', e => handleFiles(e.target.files));
    }

    function handleFiles(files) {
        for (const f of files) {
            if (f.size > 10 * 1024 * 1024) { alert(`${f.name} supera los 10MB`); continue; }
            storedFiles.push(f);
        }
        updateUI();
    }

    function removeFile(i) {
        storedFiles.splice(i, 1);
        updateUI();
        if (!storedFiles.length) fileInput.value = '';
    }

    function updateUI() {
        filesListUl.innerHTML = '';
        if (!storedFiles.length) {
            dropContent.classList.remove('hidden');
            fileListPreview.classList.add('hidden');
            uploadActions.classList.add('hidden');
            return;
        }
        dropContent.classList.add('hidden');
        fileListPreview.classList.remove('hidden');
        uploadActions.classList.remove('hidden');
        storedFiles.forEach((f, i) => {
            const li = document.createElement('li');
            li.className = 'show-file-list-item';
            li.innerHTML = `<span>📄 <strong>${f.name}</strong> <small>(${(f.size/1024/1024).toFixed(2)} MB)</small></span>
                <button type="button" onclick="removeFile(${i})" class="show-file-remove">×</button>`;
            filesListUl.appendChild(li);
        });
        const dt = new DataTransfer();
        storedFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
    }

    // ─── Modales ─────────────────────────────────────────
    function openShowModal()      { document.getElementById('scheduleShowModal').classList.remove('hidden'); }
    function closeShowModal()     { document.getElementById('scheduleShowModal').classList.add('hidden'); }
    function openScheduleModal()  { document.getElementById('scheduleModal').classList.remove('hidden'); }
    function closeScheduleModal() { document.getElementById('scheduleModal').classList.add('hidden'); }
    </script>
</x-app-layout>
