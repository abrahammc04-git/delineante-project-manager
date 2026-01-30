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

            {{-- Tabla de Proyectos --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                    <table class="min-w-full w-full">
                        <thead class="bg-gray-50">
                            <thead class="bg-gray-50 border-b border-gray-200">

        {{-- BARRA DE FILTROS Y BÚSQUEDA --}}
    <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <form action="{{ route('proyectos.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            
            {{-- 1. Buscador (Texto) --}}
            <div class="col-span-1 md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Barra de búsqueda</label>
                
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="{{ Auth::user()->isAdmin() ? 'Filtrar por Proyecto, Cliente o Email.' : 'Filtrar por nombre de proyecto.' }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div class="flex gap-2">
                {{-- Botón oculto para el Enter --}}
                <button type="submit" class="hidden"></button>

                {{-- Botón FILTRAR --}}
                {{-- w-32 define un ancho fijo. justify-center centra el texto --}}
                <button type="submit" class="w-32 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded shadow transition flex items-center justify-center text-center">
                    Filtrar
                </button>
                
                {{-- Botón LIMPIAR --}}
                {{-- w-32 define el MISMO ancho fijo --}}
                <a href="{{ route('proyectos.index') }}" class="w-32 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded shadow transition flex items-center justify-center text-center">
                    Limpiar
                </a>
            </div>
        </form>
    </div>
        
    <tr>
        {{-- 1. PROYECTO --}}
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition">
            <a href="{{ route('proyectos.index', array_merge(request()->all(), ['sort' => 'nombre_proyecto', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
               class="flex items-center group w-full h-full">
                Proyecto
                <span class="ml-1 text-gray-400">
                    @if(request('sort') == 'nombre_proyecto')
                        {{ request('direction') == 'asc' ? '▲' : '▼' }}
                    @else
                        <span class="opacity-0 group-hover:opacity-50">⇅</span>
                    @endif
                </span>
            </a>
        </th>

        {{-- 2. CLIENTE (Ordena por ID de usuario para agruparlos) --}}
        @if(auth()->user()->isAdmin())
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition">
                <a href="{{ route('proyectos.index', array_merge(request()->all(), ['sort' => 'id_usuario', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                   class="flex items-center group w-full h-full">
                    Cliente
                    <span class="ml-1 text-gray-400">
                        @if(request('sort') == 'id_usuario')
                            {{ request('direction') == 'asc' ? '▲' : '▼' }}
                        @else
                            <span class="opacity-0 group-hover:opacity-50">⇅</span>
                        @endif
                    </span>
                </a>
            </th>
        @endif

        {{-- 3. ESTADO --}}
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition">
            <a href="{{ route('proyectos.index', array_merge(request()->all(), ['sort' => 'estado', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
               class="flex items-center group w-full h-full">
                Estado
                <span class="ml-1 text-gray-400">
                    @if(request('sort') == 'estado')
                        {{ request('direction') == 'asc' ? '▲' : '▼' }}
                    @else
                        <span class="opacity-0 group-hover:opacity-50">⇅</span>
                    @endif
                </span>
            </a>
        </th>

        {{-- 4. FECHAS --}}
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition">
            <a href="{{ route('proyectos.index', array_merge(request()->all(), ['sort' => 'fecha_inicio', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
               class="flex items-center group w-full h-full">
                Fechas
                <span class="ml-1 text-gray-400">
                    @if(request('sort') == 'fecha_inicio')
                        {{ request('direction') == 'asc' ? '▲' : '▼' }}
                    @else
                        <span class="opacity-0 group-hover:opacity-50">⇅</span>
                    @endif
                </span>
            </a>
        </th>

        {{-- 5. ACCIONES (Sin ordenar) --}}
        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
            Acciones
        </th>
    </tr>
</thead>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($proyectos as $proyecto)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $proyecto->nombre_proyecto }}</div>
                                        <div class="text-sm text-gray-500">{{ $proyecto->tipo_proyecto }}</div>
                                    </td>
                                    @if(auth()->user()->isAdmin())
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $proyecto->usuario->nombre ?? 'N/A' }} {{ $proyecto->usuario->apellidos ?? '' }}</div>
                                        <div class="text-sm text-gray-500">{{ $proyecto->usuario->email ?? '' }}</div>
                                    </td>
                                    @endif
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
                                        <a href="{{ route('proyectos.show', $proyecto->id_proyecto) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver detalles</a>
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