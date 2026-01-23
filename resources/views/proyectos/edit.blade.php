<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Proyecto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- Encabezado --}}
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-800">Editar: {{ $proyecto->nombre_proyecto }}</h2>
                        <a href="{{ route('proyectos.index') }}" class="text-gray-500 hover:text-gray-700">← Volver al listado</a>
                    </div>

                    {{-- Errores --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong>¡Ups! Revisa los campos:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('proyectos.update', $proyecto->id_proyecto) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- IMPORTANTE: Esto le dice a Laravel que es una actualización --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Nombre del Proyecto --}}
                            <div class="col-span-2">
                                <label for="nombre_proyecto" class="block text-sm font-medium text-gray-700">Nombre del Proyecto *</label>
                                <input type="text" name="nombre_proyecto" id="nombre_proyecto" 
                                    value="{{ old('nombre_proyecto', $proyecto->nombre_proyecto) }}" required 
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            {{-- Cliente --}}
                            <div>
                                <label for="id_usuario" class="block text-sm font-medium text-gray-700">Asignar Cliente *</label>
                                <select name="id_usuario" id="select_cliente" required class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Selecciona un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id_usuario }}" 
                                            {{ (old('id_usuario', $proyecto->id_usuario) == $cliente->id_usuario) ? 'selected' : '' }}>
                                            {{ $cliente->nombre }} {{ $cliente->apellidos }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tipo de Proyecto --}}
                            <div>
                                <label for="tipo_proyecto" class="block text-sm font-medium text-gray-700">Tipo *</label>
                                <select name="tipo_proyecto" id="tipo_proyecto" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm sm:text-sm">
                                    @foreach(['Residencial', 'Comercial', 'Industrial', 'Reforma'] as $tipo)
                                        <option value="{{ $tipo }}" {{ (old('tipo_proyecto', $proyecto->tipo_proyecto) == $tipo) ? 'selected' : '' }}>
                                            {{ $tipo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Fechas --}}
                            <div>
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha Inicio *</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" 
                                    value="{{ old('fecha_inicio', optional($proyecto->fecha_inicio)->format('Y-m-d')) }}" required 
                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label for="fecha_fin_prevista" class="block text-sm font-medium text-gray-700">Fecha Fin Prevista</label>
                                <input type="date" name="fecha_fin_prevista" id="fecha_fin_prevista" 
                                    value="{{ old('fecha_fin_prevista', optional($proyecto->fecha_fin_prevista)->format('Y-m-d')) }}" 
                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            {{-- Estado --}}
                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700">Estado *</label>
                                <select name="estado" id="estado" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm sm:text-sm">
                                    @foreach(['Pendiente', 'En proceso', 'Completado', 'Cancelado'] as $estado)
                                        <option value="{{ $estado }}" {{ (old('estado', $proyecto->estado) == $estado) ? 'selected' : '' }}>
                                            {{ $estado }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Localización --}}
                            <div>
                                <label for="localizacion" class="block text-sm font-medium text-gray-700">Localización</label>
                                <input type="text" name="localizacion" id="localizacion" 
                                    value="{{ old('localizacion', $proyecto->localizacion) }}" 
                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            {{-- Descripción --}}
                            <div class="col-span-2">
                                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea name="descripcion" id="descripcion" rows="3" 
                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('proyectos.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded shadow">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded shadow">
                                Actualizar Proyecto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#select_cliente').select2({
            placeholder: "Escribe para buscar un cliente...",
            allowClear: true,
            width: '100%',
            
            minimumInputLength: 1, 

            language: {
                inputTooShort: function () {
                    return "Escribe al menos una letra para buscar.";
                },
                noResults: function () {
                    return "No se ha encontrado ningún cliente";
                },
                searching: function () {
                    return "Buscando...";
                }
            }
        });
    });
</script>

<style>
    .select2-container .select2-selection--single {
        height: 42px !important;
        padding-top: 6px;
        border-color: #d1d5db !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px; 
    }
</style>