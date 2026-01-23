<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Proyectos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Encabezado y Botones --}}
            <div class="flex justify-between items-center mb-6 px-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900">Listado de Proyectos</h3>

                <div class="flex space-x-3">
                    {{-- Botón Dashboard --}}
                    <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow">
                        Ir al Dashboard
                    </a>

                    {{-- Botón Nuevo Proyecto (Solo Admin) --}}
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('proyectos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                            + Nuevo Proyecto
                        </a>
                    @endif
                </div>
            </div>

            {{-- Mensajes de Éxito --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 mx-4 sm:mx-0" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- Tabla de Proyectos --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                    <table class="min-w-full w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyecto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fechas</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($proyectos as $proyecto)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $proyecto->nombre_proyecto }}</div>
                                        <div class="text-sm text-gray-500">{{ $proyecto->tipo_proyecto }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $proyecto->usuario->nombre ?? 'N/A' }} {{ $proyecto->usuario->apellidos ?? '' }}</div>
                                        <div class="text-sm text-gray-500">{{ $proyecto->usuario->email ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $proyecto->estado === 'Completado' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $proyecto->estado === 'En proceso' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $proyecto->estado === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $proyecto->estado === 'Cancelado' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $proyecto->estado }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>In: {{ $proyecto->fecha_inicio ? $proyecto->fecha_inicio->format('d/m/Y') : '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('proyectos.show', $proyecto->id_proyecto) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                                        
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('proyectos.edit', $proyecto->id_proyecto) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Editar</a>
                                            
                                            <form action="{{ route('proyectos.destroy', $proyecto->id_proyecto) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar proyecto?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No hay proyectos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>