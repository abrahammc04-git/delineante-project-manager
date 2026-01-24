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

                {{-- Columna Derecha: Fechas --}}
                <div class="space-y-6">
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
                            <a href="{{ route('proyectos.edit', $proyecto->id_proyecto) }}" class="w-full text-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded shadow">
                                Editar Proyecto
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>