<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Proyecto') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div style="max-width: 1280px; margin: 0 auto; padding: 1.5rem;">
        
        <!-- Header como box flotante (igual que index) -->
        <div style="background: linear-gradient(135deg, #0033CC, #001F7A); border-radius: 14px; padding: 1.5rem 2rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
            <div>
                <h1 style="color: white; font-size: 1.5rem; font-weight: 700; margin: 0 0 0.25rem 0;">
                    Información General
                </h1>
                <p style="color: rgba(255,255,255,0.8); font-size: 0.9rem; margin: 0;">
                    Detalles completos del proyecto y gestión de documentos
                </p>
            </div>
            <div style="display: flex; gap: 0.65rem; align-items: center; flex-shrink: 0;">
                <a href="{{ route('proyectos.index') }}" 
                   style="background: rgba(255,255,255,0.15); color: white; padding: 0.6rem 1.15rem; border-radius: 8px; font-weight: 600; text-decoration: none; border: 1px solid rgba(255,255,255,0.3); transition: all 0.2s;">
                    ← Volver
                </a>
                
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('proyectos.edit', $proyecto->id_proyecto) }}" 
                       style="background: white; color: #0033CC; padding: 0.6rem 1.15rem; border-radius: 8px; font-weight: 600; text-decoration: none; transition: all 0.2s;">
                        Editar
                    </a>
                @endif
            </div>
        </div>

        <!-- Grid de 2 columnas -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            
            <!-- Columna Izquierda: Info Principal -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,51,204,0.08); border: 1px solid #e5e7eb; padding: 1.5rem;">
                <!-- NOMBRE DEL PROYECTO como título principal -->
                <h3 style="font-size: 1.5rem; font-weight: 700; color: #001F7A; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid #e5e7eb;">
                    {{ $proyecto->nombre_proyecto }}
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Cliente</p>
                        <p style="font-weight: 600; color: #111827; font-size: 1rem;">{{ $proyecto->usuario->nombre }} {{ $proyecto->usuario->apellidos }}</p>
                        <p style="font-size: 0.875rem; color: #6b7280;">{{ $proyecto->usuario->email }}</p>
                    </div>
                    
                    <div>
                        <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Empresa</p>
                        <p style="font-weight: 600; color: #111827; font-size: 1rem;">{{ $proyecto->usuario->empresa ?? 'No especificada' }}</p>
                    </div>
                    
                    <div>
                        <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Tipo de Proyecto</p>
                        <p style="font-weight: 600; color: #111827; font-size: 1rem;">{{ $proyecto->tipo_proyecto }}</p>
                    </div>
                    
                    <div>
                        <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Estado Actual</p>
                        <span style="display: inline-flex; padding: 0.375rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
                            @if($proyecto->estado == 'Completado') background: #D1FAE5; color: #065F46;
                            @elseif($proyecto->estado == 'En proceso') background: #DBEAFE; color: #1E40AF;
                            @elseif($proyecto->estado == 'Pendiente') background: #FEF3C7; color: #92400E;
                            @elseif($proyecto->estado == 'Pausado') background: #E5E7EB; color: #374151;
                            @elseif($proyecto->estado == 'Cancelado') background: #FEE2E2; color: #991B1B;
                            @endif">
                            {{ $proyecto->estado }}
                        </span>
                    </div>
                    
                    <div style="grid-column: span 2;">
                        <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Ubicación</p>
                        <p style="font-weight: 600; color: #111827; font-size: 1rem;">{{ $proyecto->localizacion ?? 'No especificada' }}</p>
                        @if($proyecto->direccion)
                            <p style="font-size: 0.875rem; color: #6b7280;">{{ $proyecto->direccion }}</p>
                        @endif
                    </div>
                </div>

                @if($proyecto->descripcion)
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                    <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Descripción</p>
                    <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; color: #374151; line-height: 1.6;">
                        {{ $proyecto->descripcion }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Columna Derecha: Cronograma y Acciones -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                
                <!-- Tarjeta Cronograma -->
                <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,51,204,0.08); border: 1px solid #e5e7eb; padding: 1.5rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 700; color: #001F7A; margin-bottom: 1.25rem;">
                        Cronograma
                    </h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <p style="font-size: 0.8rem; color: #6b7280; margin-bottom: 0.25rem;">Fecha Inicio</p>
                            <p style="font-weight: 600; color: #111827;">{{ $proyecto->fecha_inicio ? $proyecto->fecha_inicio->format('d/m/Y') : '-' }}</p>
                        </div>
                        
                        <div>
                            <p style="font-size: 0.8rem; color: #6b7280; margin-bottom: 0.25rem;">Fin Previsto</p>
                            <p style="font-weight: 600; color: #111827;">{{ $proyecto->fecha_fin_prevista ? $proyecto->fecha_fin_prevista->format('d/m/Y') : '-' }}</p>
                        </div>
                        
                        @if($proyecto->fecha_fin_real)
                        <div>
                            <p style="font-size: 0.8rem; color: #6b7280; margin-bottom: 0.25rem;">Fin Real</p>
                            <p style="font-weight: 600; color: #059669;">{{ $proyecto->fecha_fin_real->format('d/m/Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Acciones Admin -->
                @if(Auth::user()->isAdmin())
                <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,51,204,0.08); border: 1px solid #e5e7eb; padding: 1.5rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 700; color: #001F7A; margin-bottom: 1.25rem;">
                        Acciones
                    </h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <a href="{{ route('proyectos.edit', $proyecto->id_proyecto) }}" 
                           style="background: linear-gradient(135deg, #0033CC, #001F7A); color: white; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 600; text-decoration: none; text-align: center; transition: all 0.2s;">
                            Editar Proyecto
                        </a>
                        
                        <form action="{{ route('proyectos.destroy', $proyecto->id_proyecto) }}" 
                              method="POST" 
                              onsubmit="return confirm('¿Estás seguro de eliminar este proyecto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    style="width: 100%; background: #dc2626; color: white; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s;">
                                Eliminar Proyecto
                            </button>
                        </form>
                    </div>
                </div>
                @endif
                
            </div>
        </div>

        <!-- Sección de Documentos -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,51,204,0.08); border: 1px solid #e5e7eb; padding: 1.5rem;">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #001F7A;">
                    Gestión de Documentos
                </h3>
                
                @if(Auth::user()->isAdmin())
                <button id="toggleUploadBtn" type="button" 
                        style="background: linear-gradient(135deg, #0033CC, #001F7A); color: white; padding: 0.65rem 1.25rem; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="height: 1.25rem; width: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Subir Nuevo Archivo
                </button>
                @endif
            </div>

            @if(Auth::user()->isAdmin())
            <!-- Formulario de subida (oculto por defecto) -->
            <div id="uploadContainer" class="hidden" style="margin-bottom: 1.5rem; padding: 1.5rem; background: #f9fafb; border-radius: 8px; border: 2px dashed #d1d5db;">
                <form action="{{ route('proyectos.archivos.subir', ['id' => $proyecto->id_proyecto]) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    
                    <div id="dropzone" style="padding: 2rem; text-align: center; cursor: pointer; border-radius: 8px; background: white; border: 2px dashed #d1d5db;">
                        <input type="file" name="archivos[]" id="archivoInput" class="hidden" multiple accept=".pdf,.dwg,.dxf,.jpg,.jpeg,.png">
                        
                        <div id="dropContent">
                            <svg style="margin: 0 auto 1rem; height: 3rem; width: 3rem; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div style="display: flex; justify-content: center; font-size: 0.875rem; color: #6b7280;">
                                <label for="archivoInput" style="color: #0033CC; font-weight: 600; cursor: pointer;">
                                    Selecciona archivos
                                </label>
                                <span style="padding-left: 0.25rem;"> o arrastra y suelta aquí</span>
                            </div>
                            <p style="color: #9ca3af; font-size: 0.875rem; margin-top: 0.5rem;">
                                PDF, Imágenes, CAD (Máx 10MB)
                            </p>
                        </div>

                        <div id="fileListPreview" class="hidden" style="margin-top: 1rem; text-align: left;">
                            <p style="font-weight: 600; margin-bottom: 0.5rem; font-size: 0.875rem;">Archivos listos para subir:</p>
                            <ul id="filesList" style="background: white; border-radius: 8px; border: 1px solid #e5e7eb; padding: 1rem;"></ul>
                        </div>
                    </div>
                    
                    <div id="uploadActions" class="hidden" style="margin-top: 1rem; text-align: right;">
                        <button type="submit" 
                                style="background: #0033CC; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Subir Archivos
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Header de lista de archivos con botones de programación -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e5e7eb;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <h4 style="font-size: 1rem; font-weight: 600; color: #111827;">Archivos Adjuntos</h4>
                    
                    @if(isset($archivos) && count($archivos) > 0)
                    <!-- Checkbox para habilitar selección múltiple -->
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.875rem; color: #374151;">
                        <input type="checkbox" id="toggleSelectionMode" onchange="toggleSelectionMode()" 
                               style="width: 1rem; height: 1rem; cursor: pointer; border-radius: 4px;">
                        <span>Seleccionar varios</span>
                    </label>
                    @endif
                </div>
                
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <!-- Botón descargar seleccionados (oculto por defecto) -->
                    @if(isset($archivos) && count($archivos) > 0)
                    <button type="button" id="btnDescargarSeleccionados" onclick="descargarSeleccionados()" 
                            style="display: none; background: #059669; color: white; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; border: none; cursor: pointer; align-items: center; gap: 0.25rem;">
                        <svg style="width: 1rem; height: 1rem; display: inline;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Descargar seleccionados (<span id="contadorSeleccionados">0</span>)
                    </button>
                    @endif
                    
                    @if(Auth::user()->isAdmin() && isset($archivos) && count($archivos) > 0)
                    <button type="button" onclick="openShowModal()" 
                            style="background: #dbeafe; color: #1e40af; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; border: 1px solid #3b82f6; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Programar mostrar
                    </button>
                    
                    <button type="button" onclick="openScheduleModal()" 
                            style="background: #f3f4f6; color: #374151; padding: 0.375rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; border: 1px solid #d1d5db; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Programar ocultar
                    </button>
                    @endif
                </div>
            </div>

            <!-- Lista de archivos -->
            @if(isset($archivos) && count($archivos) > 0)
                <div style="background: #f9fafb; border-radius: 8px; padding: 0.5rem;">
                    @foreach($archivos as $archivo)
                        @php $nombre = $archivo['nombre']; @endphp
                        
                        <div style="background: white; padding: 1rem; margin-bottom: 0.75rem; border-radius: 8px; border: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                            <!-- Checkbox de selección (oculto por defecto) -->
                            <input type="checkbox" class="archivo-checkbox" data-nombre="{{ $nombre }}" 
                                   style="display: none; width: 1.25rem; height: 1.25rem; cursor: pointer; margin-right: 0.75rem; flex-shrink: 0;"
                                   onchange="actualizarContador()">
                            
                            <div style="display: flex; align-items: center; gap: 0.75rem; flex: 1;">
                                <span style="font-size: 1.25rem; flex-shrink: 0;">
                                    @if(Str::endsWith(Str::lower($nombre), ['.jpg', '.png', '.jpeg', '.gif'])) 🖼️
                                    @elseif(Str::endsWith(Str::lower($nombre), ['.pdf'])) 📄
                                    @elseif(Str::endsWith(Str::lower($nombre), ['.dwg', '.dxf'])) 📐
                                    @else 📎
                                    @endif
                                </span>
                                
                                <div style="flex: 1; min-width: 0;">
                                    <p style="font-weight: 600; color: #111827; margin-bottom: 0.25rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $nombre }}</p>
                                    <p style="font-size: 0.875rem; color: #6b7280;">{{ $archivo['size'] }} KB • {{ $archivo['fecha'] }}</p>
                                </div>

                                <!-- Badges de programación -->
                                @if(isset($archivo['programado_mostrar']) && $archivo['programado_mostrar'])
                                <div style="display: flex; align-items: center; background: #dbeafe; color: #1e40af; border-radius: 6px; padding: 0.25rem 0.5rem; font-size: 0.7rem; font-weight: 600;">
                                    <span style="margin-right: 0.5rem;">Visible: {{ \Carbon\Carbon::parse($archivo['programado_mostrar'])->format('d/m H:i') }}</span>
                                    @if(Auth::user()->isAdmin())
                                        <form action="{{ route('proyectos.archivos.cancelar_publicacion', ['id' => $proyecto->id_proyecto]) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="nombre_archivo" value="{{ $nombre }}">
                                            <button type="submit" style="background: none; border: none; color: #1e40af; cursor: pointer; padding: 0; font-weight: 700;">×</button>
                                        </form>
                                    @endif
                                </div>
                                @endif

                                @if($archivo['programado'])
                                <div style="display: flex; align-items: center; background: #fef3c7; color: #92400e; border-radius: 6px; padding: 0.25rem 0.5rem; font-size: 0.7rem; font-weight: 600;">
                                    <span style="margin-right: 0.5rem;">{{ \Carbon\Carbon::parse($archivo['programado'])->format('d/m H:i') }}</span>
                                    @if(Auth::user()->isAdmin())
                                        <form action="{{ route('proyectos.archivos.cancelar', ['id' => $proyecto->id_proyecto]) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="nombre_archivo" value="{{ $nombre }}">
                                            <button type="submit" style="background: none; border: none; color: #92400e; cursor: pointer; padding: 0; font-weight: 700;">×</button>
                                        </form>
                                    @endif
                                </div>
                                @endif
                            </div>
                            
                            <div style="display: flex; gap: 0.5rem; align-items: center; flex-shrink: 0; margin-left: 1rem;">
                                <!-- Botón toggle visibilidad (Ojo) -->
                                @if(Auth::user()->isAdmin())
                                    <form action="{{ route('proyectos.archivos.toggle', ['id' => $proyecto->id_proyecto]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="nombre_archivo" value="{{ $nombre }}">
                                        <button type="submit" style="background: none; border: none; padding: 0.25rem; cursor: pointer; border-radius: 4px;" title="{{ $archivo['visible'] ? 'Visible para cliente' : 'Oculto para cliente' }}">
                                            @if($archivo['visible'])
                                                <svg style="width: 1.25rem; height: 1.25rem; color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            @else
                                                <svg style="width: 1.25rem; height: 1.25rem; color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 013.999-5.42m5.06-2.106c.361-.055.733-.085 1.11-.085 4.478 0 8.268 2.943 9.542 7a10.057 10.057 0 01-2.029 3.56M15 12a3 3 0 01-3 3m0 0a3 3 0 01-3-3m0 0a3 3 0 013-3m-3 3l-6.364-6.364M21 21l-6.364-6.364"></path>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                @endif

                                <!-- Botón Descargar -->
                                <a href="{{ route('proyectos.archivos.descargar', ['id' => $proyecto->id_proyecto, 'nombreArchivo' => $nombre]) }}" 
                                   style="background: #0033CC; color: white; padding: 0.5rem 0.75rem; border-radius: 6px; font-weight: 600; text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                                
                                <!-- Botón Eliminar -->
                                @if(Auth::user()->isAdmin())
                                <form action="{{ route('proyectos.archivos.eliminar', ['id' => $proyecto->id_proyecto, 'nombreArchivo' => $nombre]) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('¿Eliminar este archivo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="background: #dc2626; color: white; padding: 0.5rem 0.75rem; border-radius: 6px; font-weight: 600; border: none; cursor: pointer; font-size: 0.875rem;">
                                        Eliminar
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #9ca3af; background: #f9fafb; border-radius: 8px; border: 2px dashed #e5e7eb;">
                    <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">No hay documentos adjuntos</p>
                    <p style="font-size: 0.875rem;">Los archivos del proyecto aparecerán aquí</p>
                </div>
            @endif
        </div>
        
    </div>

    {{-- MODAL: Programar MOSTRAR archivos --}}
    <div id="scheduleShowModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div style="display: flex; align-items: flex-end; justify-content: center; min-height: 100vh; padding: 1rem; text-align: center;">
            <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5);" onclick="closeShowModal()"></div>
            <span style="display: inline-block; height: 100vh; vertical-align: middle;"></span>

            <div style="display: inline-block; background: white; border-radius: 12px; text-align: left; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); max-width: 32rem; width: 100%; position: relative; z-index: 10;">
                <form method="POST" action="{{ route('proyectos.archivos.programar_publicacion', ['id' => $proyecto->id_proyecto]) }}">
                    @csrf
                    <div style="padding: 1.5rem;">
                        <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e40af; margin-bottom: 1rem;">
                            Programar aparición de archivos
                        </h3>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Fecha y Hora para mostrarse</label>
                            <input type="datetime-local" name="fecha_publicacion" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Selecciona los archivos:</label>
                            <div style="max-height: 12rem; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb; padding: 0.5rem;">
                                @if(isset($archivos))
                                    @foreach($archivos as $archivo)
                                        <div style="display: flex; align-items: center; padding: 0.25rem 0;">
                                            <input id="chk_show_{{ $loop->index }}" name="archivos_seleccionados[]" value="{{ $archivo['nombre'] }}" type="checkbox" style="width: 1rem; height: 1rem; color: #1e40af; border-radius: 4px; cursor: pointer;">
                                            <label for="chk_show_{{ $loop->index }}" style="margin-left: 0.5rem; font-size: 0.875rem; color: #111827; cursor: pointer; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $archivo['nombre'] }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div style="background: #f9fafb; padding: 0.75rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                        <button type="button" onclick="closeShowModal()" style="background: white; color: #374151; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; border: 1px solid #d1d5db; cursor: pointer;">Cancelar</button>
                        <button type="submit" style="background: #1e40af; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL: Programar OCULTAR archivos --}}
    <div id="scheduleModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div style="display: flex; align-items: flex-end; justify-content: center; min-height: 100vh; padding: 1rem; text-align: center;">
            <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5);" onclick="closeScheduleModal()"></div>
            <span style="display: inline-block; height: 100vh; vertical-align: middle;"></span>

            <div style="display: inline-block; background: white; border-radius: 12px; text-align: left; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); max-width: 32rem; width: 100%; position: relative; z-index: 10;">
                <form method="POST" action="{{ route('proyectos.archivos.programar', ['id' => $proyecto->id_proyecto]) }}">
                    @csrf
                    <div style="padding: 1.5rem;">
                        <h3 style="font-size: 1.125rem; font-weight: 700; color: #92400e; margin-bottom: 1rem;">
                            Programar ocultación de archivos
                        </h3>
                        
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Fecha y Hora de ocultación</label>
                            <input type="datetime-local" name="fecha_ocultacion" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Selecciona los archivos:</label>
                            <div style="max-height: 12rem; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb; padding: 0.5rem;">
                                @if(isset($archivos))
                                    @foreach($archivos as $archivo)
                                        <div style="display: flex; align-items: center; padding: 0.25rem 0;">
                                            <input id="chk_{{ $loop->index }}" name="archivos_seleccionados[]" value="{{ $archivo['nombre'] }}" type="checkbox" style="width: 1rem; height: 1rem; color: #92400e; border-radius: 4px; cursor: pointer;">
                                            <label for="chk_{{ $loop->index }}" style="margin-left: 0.5rem; font-size: 0.875rem; color: #111827; cursor: pointer; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $archivo['nombre'] }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div style="background: #f9fafb; padding: 0.75rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem;">
                        <button type="button" onclick="closeScheduleModal()" style="background: white; color: #374151; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; border: 1px solid #d1d5db; cursor: pointer;">Cancelar</button>
                        <button type="submit" style="background: #92400e; color: white; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ═══════════════════════════════════════════════════════════
        // SELECCIÓN MÚLTIPLE Y DESCARGA MASIVA
        // ═══════════════════════════════════════════════════════════
        
        function toggleSelectionMode() {
            const checkbox = document.getElementById('toggleSelectionMode');
            const checkboxes = document.querySelectorAll('.archivo-checkbox');
            const btnDescargar = document.getElementById('btnDescargarSeleccionados');
            
            if (checkbox.checked) {
                // Mostrar todos los checkboxes
                checkboxes.forEach(cb => cb.style.display = 'block');
                btnDescargar.style.display = 'inline-flex';
            } else {
                // Ocultar checkboxes y desmarcar todos
                checkboxes.forEach(cb => {
                    cb.style.display = 'none';
                    cb.checked = false;
                });
                btnDescargar.style.display = 'none';
                actualizarContador();
            }
        }
        
        function actualizarContador() {
            const checkboxes = document.querySelectorAll('.archivo-checkbox:checked');
            const contador = document.getElementById('contadorSeleccionados');
            if (contador) {
                contador.textContent = checkboxes.length;
            }
        }
        
        function descargarSeleccionados() {
            const checkboxes = document.querySelectorAll('.archivo-checkbox:checked');
            
            if (checkboxes.length === 0) {
                alert('Selecciona al menos un archivo');
                return;
            }
            
            // Descargar cada archivo con un pequeño delay para no sobrecargar
            checkboxes.forEach((checkbox, index) => {
                setTimeout(() => {
                    const nombreArchivo = checkbox.getAttribute('data-nombre');
                    const url = `{{ route('proyectos.archivos.descargar', ['id' => $proyecto->id_proyecto, 'nombreArchivo' => '__NOMBRE__']) }}`.replace('__NOMBRE__', encodeURIComponent(nombreArchivo));
                    
                    // Crear enlace temporal y hacer click
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = nombreArchivo;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }, index * 300); // 300ms de delay entre cada descarga
            });
            
            // Opcional: Desmarcar todos después de descargar
            setTimeout(() => {
                checkboxes.forEach(cb => cb.checked = false);
                actualizarContador();
            }, checkboxes.length * 300 + 500);
        }

        // ═══════════════════════════════════════════════════════════
        // FORMULARIO DE SUBIDA
        // ═══════════════════════════════════════════════════════════
        
        // Toggle formulario de subida
        const toggleBtn = document.getElementById('toggleUploadBtn');
        const uploadContainer = document.getElementById('uploadContainer');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                uploadContainer.classList.toggle('hidden');
                if (!uploadContainer.classList.contains('hidden')) {
                    toggleBtn.textContent = 'Cancelar';
                } else {
                    toggleBtn.innerHTML = `<svg style="height: 1.25rem; width: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg> Subir Nuevo Archivo`;
                    storedFiles = [];
                    updateUI();
                }
            });
        }

        // Drag & Drop
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('archivoInput');
        const dropContent = document.getElementById('dropContent');
        const fileListPreview = document.getElementById('fileListPreview');
        const filesListUl = document.getElementById('filesList');
        const uploadActions = document.getElementById('uploadActions');

        let storedFiles = [];

        if (dropzone && fileInput) {
            dropzone.addEventListener('click', (e) => {
                if(e.target.closest('button')) return;
                fileInput.click();
            });
            
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.style.background = '#e5e7eb';
            });
            
            dropzone.addEventListener('dragleave', () => {
                dropzone.style.background = 'white';
            });
            
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.style.background = 'white';
                handleFiles(e.dataTransfer.files);
            });
            
            fileInput.addEventListener('change', (e) => {
                handleFiles(e.target.files);
            });
        }

        function handleFiles(files) {
            for (let file of files) {
                if (file.size > 10 * 1024 * 1024) {
                    alert(`${file.name} supera los 10MB`);
                    continue;
                }
                storedFiles.push(file);
            }
            updateUI();
        }

        function removeFile(index) {
            storedFiles.splice(index, 1);
            updateUI();
            if (storedFiles.length === 0) {
                fileInput.value = "";
            }
        }

        function updateUI() {
            filesListUl.innerHTML = ''; 

            if (storedFiles.length === 0) {
                dropContent.classList.remove('hidden');
                fileListPreview.classList.add('hidden');
                uploadActions.classList.add('hidden');
                return;
            }

            dropContent.classList.add('hidden');
            fileListPreview.classList.remove('hidden');
            uploadActions.classList.remove('hidden');

            storedFiles.forEach((file, index) => {
                const li = document.createElement('li');
                li.style = "display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #f3f4f6;";
                
                li.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="font-size: 1.25rem;">📄</span>
                        <span style="font-weight: 500;">${file.name}</span>
                        <span style="font-size: 0.875rem; color: #9ca3af;">(${(file.size/1024/1024).toFixed(2)} MB)</span>
                    </div>
                    <button type="button" onclick="removeFile(${index})" style="color: #dc2626; background: none; border: none; cursor: pointer; font-size: 1.25rem;">
                        ×
                    </button>
                `;
                filesListUl.appendChild(li);
            });

            // Sincronizar con el input real
            const dataTransfer = new DataTransfer();
            storedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }

        // ═══════════════════════════════════════════════════════════
        // MODALES
        // ═══════════════════════════════════════════════════════════
        
        function openShowModal() {
            document.getElementById('scheduleShowModal').classList.remove('hidden');
        }
        function closeShowModal() {
            document.getElementById('scheduleShowModal').classList.add('hidden');
        }
        function openScheduleModal() {
            document.getElementById('scheduleModal').classList.remove('hidden');
        }
        function closeScheduleModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
        }
    </script>
</x-app-layout>