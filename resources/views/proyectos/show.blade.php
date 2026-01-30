<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Proyecto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Encabezado y Botón Volver --}}
            <div class="flex justify-between items-center mb-6 px-4 sm:px-0">
                <h3 class="text-lg font-bold text-gray-900">
                    {{ $proyecto->nombre_proyecto }}
                </h3>
                <a href="{{ route('proyectos.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                    &larr; Volver al listado
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Columna Izquierda: Información Principal --}}
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h4 class="text-lg font-bold text-gray-700 border-b pb-2 mb-4">Información General</h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Cliente</p>
                                <p class="font-medium text-gray-900">{{ $proyecto->usuario->nombre ?? 'N/A' }} {{ $proyecto->usuario->apellidos ?? '' }}</p>
                                <p class="text-xs text-gray-500">{{ $proyecto->usuario->email ?? '' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tipo de Proyecto</p>
                                <p class="font-medium text-gray-900">{{ $proyecto->tipo_proyecto }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Ubicación</p>
                                <p class="font-medium text-gray-900">{{ $proyecto->localizacion ?? 'No especificada' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Estado Actual</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $proyecto->estado === 'Completado' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $proyecto->estado === 'En proceso' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $proyecto->estado === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $proyecto->estado === 'Cancelado' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $proyecto->estado }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <p class="text-sm text-gray-500 mb-1">Descripción</p>
                            <div class="bg-gray-50 p-4 rounded-md text-gray-700 text-sm">
                                {{ $proyecto->descripcion ?? 'Sin descripción.' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Columna Derecha: Fechas y Acciones --}}
                <div class="space-y-6">
                    {{-- Tarjeta Cronograma --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h4 class="text-lg font-bold text-gray-700 border-b pb-2 mb-4">Cronograma</h4>
                        <ul class="space-y-4">
                            <li class="flex justify-between">
                                <span class="text-sm text-gray-500">Fecha Inicio:</span>
                                <span class="font-medium">{{ $proyecto->fecha_inicio ? $proyecto->fecha_inicio->format('d/m/Y') : '-' }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-sm text-gray-500">Fin Previsto:</span>
                                <span class="font-medium">{{ $proyecto->fecha_fin_prevista ? $proyecto->fecha_fin_prevista->format('d/m/Y') : '-' }}</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Botones de Acción (Solo Admin) --}}
                    @if(auth()->user()->isAdmin())
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h4 class="text-lg font-bold text-gray-700 mb-4">Acciones</h4>
                            <div class="flex flex-col gap-3">
                                {{-- 1. Editar --}}
                                <a href="{{ route('proyectos.edit', $proyecto->id_proyecto) }}" class="w-full text-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded shadow transition duration-150 ease-in-out">
                                    Editar Proyecto
                                </a>

                                {{-- 2. Eliminar --}}
                                <form action="{{ route('proyectos.destroy', $proyecto->id_proyecto) }}" 
                                    method="POST" 
                                    onsubmit="return confirmarBorrado(event, 'este proyecto completo')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full mt-4">
                                        Eliminar Proyecto
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ZONA DE MENSAJES --}}
            <div class="max-w-7xl mx-auto mt-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">¡Éxito!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error:</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <strong class="font-bold">Revisa los siguientes errores:</strong>
                        <ul class="list-disc list-inside mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- SECCIÓN DE DOCUMENTOS --}}
            @if(auth()->user()->isAdmin())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                
                {{-- CABECERA CON BOTÓN TOGGLE --}}
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Gestión de Documentos</h3>
                    
                    <button id="toggleUploadBtn" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Subir Nuevo Archivo
                    </button>
                </div>

                {{-- CONTENEDOR OCULTO (hidden por defecto) --}}
                <div id="uploadContainer" class="hidden mb-6 transition-all duration-300 ease-in-out">
                    
                    {{-- 1. FORMULARIO DRAG & DROP (MÚLTIPLE) --}}
                    <form action="{{ route('proyectos.archivos.subir', ['id' => $proyecto->id_proyecto]) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        
                        {{-- ... (Aquí sigue todo tu código del dropzone igual que antes) ... --}}
                        {{-- Zona de Drop --}}
                        <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:bg-gray-50 transition cursor-pointer relative bg-gray-50">
                            {{-- ... contenido del dropzone ... --}}
                            
                            {{-- IMPORTANTE: COPIA AQUÍ EL CONTENIDO INTERNO DE TU DROPZONE ACTUAL --}}
                             <input type="file" name="archivos[]" id="archivoInput" class="hidden" multiple accept=".pdf,.dwg,.dxf,.jpg,.jpeg,.png">
                            
                            <div class="space-y-1" id="dropContent">
                                <svg class="mx-auto h-12 w-12 text-gray-400" width="48" height="80" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="archivoInput" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Selecciona archivos</span>
                                    </label>
                                    <p class="pl-1">o arrastra y suelta aquí</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PDF, Imágenes, CAD (Máx 10MB)
                                </p>
                            </div>

                            <div id="fileListPreview" class="hidden mt-4 text-left w-full">
                                <p class="text-sm font-medium text-gray-700 mb-2">Archivos listos para subir:</p>
                                <ul id="filesList" class="text-sm bg-white rounded-md border border-gray-200 px-4"></ul>
                            </div>
                        </div>
                        
                        <div id="uploadActions" class="hidden mt-4 text-right">
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center py-2 px-6 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Subir Archivos
                            </button>
                        </div>
                    </form> 
                </div>
                @endif

                {{-- 2. LISTA DE ARCHIVOS EXISTENTES --}}
<div class="mt-8 border-t border-gray-200 pt-6">
    
    {{-- CABECERA DE LA LISTA CON BOTÓN DE PROGRAMAR --}}
    <div class="flex justify-between items-center mb-4">
        <h4 class="text-sm font-medium text-gray-900">Archivos Adjuntos</h4>
        
        @if(auth()->user()->isAdmin() && isset($archivos) && count($archivos) > 0)
            <button type="button" onclick="openScheduleModal()" class="text-xs flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-1 px-3 rounded border border-gray-300 transition">
                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Programar ocultar archivos
            </button>
        @endif
    </div>

    @if(isset($archivos) && count($archivos) > 0)
        <ul class="border border-gray-200 rounded-md divide-y divide-gray-200 bg-white shadow-sm">
            @foreach($archivos as $archivo)
                @php $nombre = $archivo['nombre']; @endphp

                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm hover:bg-gray-50 transition">
                    {{-- IZQUIERDA: Icono + (Nombre y Metadatos en vertical) + Badge --}}
                    <div class="flex items-center flex-1 w-0 mr-4">
                        {{-- 1. Icono --}}
                        <span class="flex-shrink-0 h-5 w-5 text-gray-400 text-lg">
                            @if(Str::endsWith(Str::lower($nombre), ['.jpg', '.png', '.jpeg', '.gif'])) 📷
                            @elseif(Str::endsWith(Str::lower($nombre), ['.pdf'])) 📄
                            @elseif(Str::endsWith(Str::lower($nombre), ['.dwg', '.dxf'])) 📐
                            @else 📎
                            @endif
                        </span>
                        
                        {{-- 2. COLUMNA: Nombre + Metadatos (Aquí está el cambio) --}}
                        <div class="ml-3 flex flex-col flex-1 min-w-0">
                            <span class="truncate text-gray-700 font-medium" title="{{ $nombre }}">
                                {{ $nombre }}
                            </span>
                            {{-- METADATOS: Debajo del nombre y siempre visibles --}}
                            <span class="text-xs text-gray-400">
                                {{ $archivo['size'] }} KB • {{ $archivo['fecha'] }}
                            </span>
                        </div>
                        
                        {{-- 3. Badge Programado (si existe) --}}
                        @if($archivo['programado'])
                            <div class="flex items-center bg-yellow-100 text-yellow-800 rounded px-2 py-0.5 ml-3 flex-shrink-0">
                                <span class="text-[10px] font-medium mr-2" title="Se ocultará: {{ \Carbon\Carbon::parse($archivo['programado'])->format('d/m/Y H:i') }}">
                                    🕒 {{ \Carbon\Carbon::parse($archivo['programado'])->format('d/m H:i') }}
                                </span>
                                
                                @if(auth()->user()->isAdmin())
                                    <form action="{{ route('proyectos.archivos.cancelar', ['id' => $proyecto->id_proyecto]) }}" method="POST" class="inline-flex">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="nombre_archivo" value="{{ $nombre }}">
                                        <button type="submit" class="text-yellow-600 hover:text-red-600 focus:outline-none transition" title="Cancelar programación">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    {{-- DERECHA: Botones de Acción (Ojo, Descargar, Eliminar) --}}
                    <div class="flex items-center flex-shrink-0 ml-4">
                        <div class="flex items-center space-x-2">
                            
                            {{-- Botón OJO --}}
                            @if(auth()->user()->isAdmin())
                                <form action="{{ route('proyectos.archivos.toggle', ['id' => $proyecto->id_proyecto]) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="nombre_archivo" value="{{ $nombre }}">
                                    
                                    <button type="submit" class="p-1 rounded-full hover:bg-gray-100 transition focus:outline-none" title="{{ $archivo['visible'] ? 'Visible para cliente' : 'Oculto para cliente' }}">
                                        @if($archivo['visible'])
                                            <svg class="w-5 h-5 text-green-500 hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        @else
                                            <svg class="w-5 h-5 text-red-500 hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 013.999-5.42m5.06-2.106c.361-.055.733-.085 1.11-.085 4.478 0 8.268 2.943 9.542 7a10.057 10.057 0 01-2.029 3.56M15 12a3 3 0 01-3 3m0 0a3 3 0 01-3-3m0 0a3 3 0 013-3m-3 3l-6.364-6.364M21 21l-6.364-6.364"></path></svg>
                                        @endif
                                    </button>
                                </form>
                            @endif

                            {{-- Botón Descargar --}}
                            <a href="{{ route('proyectos.archivos.descargar', ['id' => $proyecto->id_proyecto, 'nombreArchivo' => $nombre]) }}" 
                               class="font-medium text-blue-600 hover:text-blue-800 flex items-center transition group mr-2 ml-2" 
                               title="Descargar">
                               <svg class="w-4 h-4 mr-1 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                               <span class="hidden sm:inline">Descargar</span>
                            </a>

                            {{-- Botón Eliminar --}}
                            @if(auth()->user()->isAdmin())
                                <form action="{{ route('proyectos.archivos.eliminar', ['id' => $proyecto->id_proyecto, 'nombreArchivo' => $nombre]) }}" 
                                      method="POST" 
                                      class="flex items-center" 
                                      onsubmit="return confirmarBorrado(event, '{{ $nombre }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition p-1 rounded hover:bg-red-50" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="text-center border-2 border-gray-100 border-dashed rounded-lg p-6 bg-gray-50">
            <p class="text-sm text-gray-500">No hay documentos subidos todavía.</p>
        </div>
    @endif
</div>

{{-- MODAL DE PROGRAMACIÓN MASIVA --}}
<div id="scheduleModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- Overlay oscuro --}}
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeScheduleModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" action="{{ route('proyectos.archivos.programar', ['id' => $proyecto->id_proyecto]) }}">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                        Programar ocultación de archivos
                    </h3>
                    
                    {{-- 1. Selector de Fecha --}}
                    <div class="mb-4">
                        <label for="fecha_ocultacion" class="block text-sm font-medium text-gray-700 mb-1">Fecha y Hora de ocultación</label>
                        <input type="datetime-local" name="fecha_ocultacion" id="fecha_ocultacion" required
                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>

                    {{-- 2. Lista de Archivos (Checkboxes) --}}
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona los archivos:</label>
                        <div class="max-h-48 overflow-y-auto border border-gray-200 rounded-md bg-gray-50 p-2 space-y-2">
                            @if(isset($archivos))
                                @foreach($archivos as $archivo)
                                    <div class="flex items-center">
                                        <input id="chk_{{ $loop->index }}" name="archivos_seleccionados[]" value="{{ $archivo['nombre'] }}" type="checkbox" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                                        <label for="chk_{{ $loop->index }}" class="ml-2 block text-sm text-gray-900 cursor-pointer truncate" title="{{ $archivo['nombre'] }}">
                                            {{ $archivo['nombre'] }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Footer del Modal --}}
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Guardar
                    </button>
                    <button type="button" onclick="closeScheduleModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    {{-- SCRIPTS --}}
    <script>
    // --- 1. VARIABLES GLOBALES ---
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.querySelector('input[type="file"]');
    const uploadActions = document.getElementById('uploadActions');
    const dropContent = document.getElementById('dropContent');
    const fileListPreview = document.getElementById('fileListPreview');
    const filesListUl = document.getElementById('filesList');
    
    // Array "memoria" donde guardaremos todos los archivos válidos
    let storedFiles = [];

    // --- 2. LÓGICA DEL MODAL DE PROGRAMACIÓN ---
    function openScheduleModal() {
        const modal = document.getElementById('scheduleModal');
        if(modal) modal.classList.remove('hidden');
    }
    function closeScheduleModal() {
        const modal = document.getElementById('scheduleModal');
        if(modal) modal.classList.add('hidden');
    }

    // --- 3. LÓGICA TOGGLE (ABRIR / CERRAR Y LIMPIAR SUBIDA) ---
    const toggleBtn = document.getElementById('toggleUploadBtn');
    const uploadContainer = document.getElementById('uploadContainer');

    if (toggleBtn && uploadContainer) {
        toggleBtn.addEventListener('click', () => {
            uploadContainer.classList.toggle('hidden');
            
            if (!uploadContainer.classList.contains('hidden')) {
                // AL ABRIR
                toggleBtn.innerText = "Cancelar Subida";
                toggleBtn.classList.add('bg-gray-100', 'text-gray-900');
            } else {
                // AL CERRAR (CANCELAR)
                toggleBtn.innerHTML = `<svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Subir Nuevo Archivo`;
                toggleBtn.classList.remove('bg-gray-100', 'text-gray-900');

                // LIMPIEZA TOTAL
                storedFiles = []; 
                updateUI();       
                updateInput();    
            }
        });
    }

    // --- 4. DRAG & DROP Y SELECCIÓN DE ARCHIVOS ---

    // A) Click en la zona abre el selector
    if (dropzone) {
        dropzone.addEventListener('click', (e) => {
            if(e.target.closest('button')) return; // Evitar click si damos a borrar
            fileInput.click();
        });

        // B) Efectos Visuales Drag
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-indigo-500', 'bg-indigo-50');
        });

    // --- LÓGICA TOGGLE (ABRIR / CERRAR Y LIMPIAR) ---
    const toggleBtn = document.getElementById('toggleUploadBtn');
    const uploadContainer = document.getElementById('uploadContainer');

        // C) Soltar archivos (DROP)
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
            if (e.dataTransfer.files.length) {
                handleFiles(e.dataTransfer.files);
            }
        });
    }

    // D) Seleccionar archivos (CLICK INPUT)
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files.length) {
                handleFiles(this.files);
            }
        });
    }

    // --- 5. FUNCIÓN PRINCIPAL: PROCESAR ARCHIVOS ---
    function handleFiles(files) {
        for (let i = 0; i < files.length; i++) {
            let file = files[i];
            
            // Validación: Máximo 10MB
            if (file.size > 10 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Archivo muy pesado',
                    text: 'El archivo "' + file.name + '" pesa demasiado (Max 10MB).',
                    confirmButtonColor: '#EF4444',
                });
                continue; // Saltamos este archivo, pero seguimos con los demás
            }

            // Si pasa la validación, lo guardamos en memoria
            storedFiles.push(file);
        }
        
        // Actualizamos la vista y el input real
        updateUI();
        updateInput();
    }

    // --- 6. FUNCIONES AUXILIARES ---

    function removeFile(index) {
        storedFiles.splice(index, 1);
        updateUI();
        updateInput();
    }

    // Esta función sincroniza nuestro array con el input invisible que se envía al servidor
    function updateInput() {
        const dataTransfer = new DataTransfer();
        storedFiles.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;

        // Si vaciamos la lista, limpiamos el value para permitir resubir el mismo archivo
        if (storedFiles.length === 0) {
            fileInput.value = "";
        }
    }

    // Esta función pinta la lista en pantalla
    function updateUI() {
        filesListUl.innerHTML = ''; 

        // Estado: Sin archivos
        if (storedFiles.length === 0) {
            dropContent.classList.remove('hidden');
            fileListPreview.classList.add('hidden');
            uploadActions.classList.add('hidden');
            return;
        }

        // Estado: Con archivos
        dropContent.classList.add('hidden');
        fileListPreview.classList.remove('hidden');
        uploadActions.classList.remove('hidden');

        storedFiles.forEach((file, index) => {
            const li = document.createElement('li');
            li.className = "flex justify-between items-center py-2 border-b border-gray-100 last:border-0";
            
            li.innerHTML = `
                <div class="flex items-center">
                    <span class="text-gray-400 mr-2 text-lg">📄</span>
                    <span class="text-gray-700 font-medium truncate max-w-xs" title="${file.name}">${file.name}</span>
                    <span class="text-gray-400 text-xs ml-2">(${(file.size/1024/1024).toFixed(2)} MB)</span>
                </div>
                <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700 transition p-1 rounded-md hover:bg-red-50" title="Quitar de la lista">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;
            filesListUl.appendChild(li);
        });
    }
</script>
</x-app-layout>