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
                                <form action="{{ route('proyectos.destroy', $proyecto->id_proyecto) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este proyecto definitivamente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150 ease-in-out">
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
                <h3 class="text-lg font-bold text-gray-900 mb-4">Gestión de Documentos</h3>

                {{-- 1. FORMULARIO DRAG & DROP (MÚLTIPLE) --}}
                <form action="{{ route('proyectos.archivos.subir', ['id' => $proyecto->id_proyecto]) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    
                    {{-- Zona de Drop --}}
                    <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:bg-gray-50 transition cursor-pointer relative">
                        
                        <input type="file" name="archivos[]" id="archivoInput" class="hidden" multiple accept=".pdf,.dwg,.dxf,.jpg,.jpeg,.png">
                        
                        <div class="space-y-1" id="dropContent">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
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

                        {{-- Lista de Preview antes de subir --}}
                        <div id="fileListPreview" class="hidden mt-4 text-left">
                            <p class="text-sm font-medium text-gray-700 mb-2">Archivos listos para subir:</p>
                            <ul id="filesList" class="text-sm text-gray-500 list-disc pl-5 space-y-1"></ul>
                        </div>
                    </div>
                    
                    {{-- BOTÓN DE CONFIRMAR SUBIDA --}}
                    <div id="uploadActions" class="hidden mt-4 text-right">
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex justify-center items-center py-2 px-6 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Subir Archivos
                        </button>
                    </div>
                </form> {{-- <--- ¡AQUÍ ESTABA EL ERROR! AHORA CIERRA ANTES DE LA LISTA --}}
                @endif

                {{-- 2. LISTA DE ARCHIVOS EXISTENTES --}}
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Archivos Adjuntos</h4>

                    @if(isset($archivos) && count($archivos) > 0)
                        <ul class="border border-gray-200 rounded-md divide-y divide-gray-200 bg-white">
                            @foreach($archivos as $archivo)
                            @php $nombre = $archivo['nombre']; @endphp
                                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm hover:bg-gray-50 transition">
                                    <div class="w-0 flex-1 flex items-center">
                                        {{-- Icono --}}
                                        <span class="flex-shrink-0 h-5 w-5 text-gray-400">
                                            @if(Str::endsWith(Str::lower($archivo['nombre']), ['.jpg', '.png', '.jpeg', '.gif'])) 📷
                                            @elseif(Str::endsWith(Str::lower($archivo['nombre']), ['.pdf'])) 📄
                                            @elseif(Str::endsWith(Str::lower($archivo['nombre']), ['.dwg', '.dxf'])) 📐
                                            @else 📎
                                            @endif
                                        </span>
                                        
                                        <span class="ml-2 flex-1 w-0 truncate text-gray-700 font-medium" title="{{ $archivo['nombre'] }}">
                                            {{ $archivo['nombre'] }}
                                        </span>
                                        
                                        <span class="ml-2 text-gray-500 text-xs hidden sm:inline-block">
                                            {{ $archivo['size'] }} KB - {{ $archivo['fecha'] }}
                                        </span>
                                    </div>
                                    
                                    <div class="ml-4 flex-shrink-0 flex space-x-3 items-center">
                                        {{-- Descargar --}}
                                        <a href="{{ route('proyectos.archivos.descargar', ['id' => $proyecto->id_proyecto, 'nombreArchivo' => $archivo['nombre']]) }}" 
                                           class="font-medium text-blue-600 hover:text-blue-500 flex items-center" 
                                           title="Descargar">
                                           <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                           <span class="hidden sm:inline">Descargar</span>
                                        </a>

                                        {{-- Eliminar --}}
                                        @if(auth()->user()->isAdmin())
                                            <form action="{{ route('proyectos.archivos.eliminar', ['id' => $proyecto->id_proyecto, 'nombreArchivo' => $archivo['nombre']]) }}" 
                                                  method="POST" 
                                                  class="inline-block" 
                                                  onsubmit="return confirm('¿Estás seguro de borrar {{ $nombre }}? Esta acción no se puede deshacer.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:text-red-500 flex items-center ml-2" title="Eliminar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center border-2 border-gray-100 border-dashed rounded-lg p-6">
                            <svg class="mx-auto h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <p class="mt-2 text-sm text-gray-500">No hay documentos subidos todavía.</p>
                        </div>
                    @endif
                </div>
            </div> {{-- Fin del contenedor de documentos --}}

        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('archivoInput');
        const uploadActions = document.getElementById('uploadActions');
        const dropContent = document.getElementById('dropContent');
        const fileListPreview = document.getElementById('fileListPreview');
        const filesListUl = document.getElementById('filesList');

        // Click en la zona abre el selector
        dropzone.addEventListener('click', () => fileInput.click());

        // Efectos Drag
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-indigo-500', 'bg-indigo-50');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files; 
                handleFiles(e.dataTransfer.files);
            }
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                handleFiles(fileInput.files);
            }
        });

        function handleFiles(files) {
            filesListUl.innerHTML = ''; // Limpiar lista anterior
            let valid = true;

            // Recorrer archivos
            Array.from(files).forEach(file => {
                if (file.size > 10485760) { // 10MB
                    alert(`El archivo "${file.name}" pesa demasiado (Max 10MB).`);
                    valid = false;
                } else {
                    const li = document.createElement('li');
                    li.textContent = `${file.name} (${(file.size/1024/1024).toFixed(2)} MB)`;
                    filesListUl.appendChild(li);
                }
            });

            if (!valid) {
                fileInput.value = ""; 
                uploadActions.classList.add('hidden');
                fileListPreview.classList.add('hidden');
                return;
            }

            dropContent.classList.add('hidden');
            fileListPreview.classList.remove('hidden');
            uploadActions.classList.remove('hidden');
        }
    </script>
</x-app-layout>