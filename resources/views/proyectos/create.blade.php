<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Proyecto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- Botón Volver --}}
                    <div class="mb-6">
                        <a href="{{ route('proyectos.index') }}" class="text-gray-500 hover:text-gray-700">← Volver al listado</a>
                    </div>

                    {{-- Mostrar errores de validación --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong>¡Ups! Revisa los siguientes campos:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('proyectos.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Nombre del Proyecto --}}
                            <div class="col-span-2">
                                <label for="nombre_proyecto" class="block text-sm font-medium text-gray-700">Nombre del Proyecto *</label>
                                <input type="text" name="nombre_proyecto" id="nombre_proyecto" value="{{ old('nombre_proyecto') }}" required 
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Ej: Reforma Integral Calle Mayor">
                            </div>

                            {{-- Cliente (Solo Admin ve esto) --}}
                            <div>
                                <label for="id_usuario" class="block text-sm font-medium text-gray-700">Asignar Cliente *</label>
                                <select name="id_usuario" id="select_cliente" required class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Selecciona un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id_usuario }}" {{ old('id_usuario') == $cliente->id_usuario ? 'selected' : '' }}>
                                            {{ $cliente->nombre }} {{ $cliente->apellidos }} ({{ $cliente->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tipo de Proyecto --}}
                            <div class="mb-4">
                                <label for="tipo_proyecto" class="block text-gray-700 text-sm font-bold mb-2">Tipo de Proyecto *</label>
                                
                                <select name="tipo_proyecto" id="tipo" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm sm:text-sm">
                                    
                                    {{-- Opción por defecto (deshabilitada) --}}
                                    <option value="" disabled {{ old('tipo_proyecto') ? '' : 'selected' }}>Selecciona un tipo</option>

                                    {{-- Opción: Industrial --}}
                                    <option value="Industrial" {{ old('tipo_proyecto') == 'Industrial' ? 'selected' : '' }}>
                                        Industrial
                                    </option>

                                    {{-- Opción: Comercial --}}
                                    <option value="Comercial" {{ old('tipo_proyecto') == 'Comercial' ? 'selected' : '' }}>
                                        Comercial
                                    </option>
                                    
                                    {{-- Opción: Residencial --}}
                                    <option value="Residencial" {{ old('tipo_proyecto') == 'Residencial' ? 'selected' : '' }}>
                                        Residencial
                                    </option>
                                    
                                    {{-- Opción: Reforma --}}
                                    <option value="Reforma" {{ old('tipo_proyecto') == 'Reforma' ? 'selected' : '' }}>
                                        Reforma
                                    </option>

                                    {{-- Si tienes más tipos, añade más líneas aquí --}}

                                </select>

                                {{-- Mensaje de error --}}
                                @error('tipo')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Fechas --}}
                            <div>
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha Inicio *</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio') }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label for="fecha_fin_prevista" class="block text-sm font-medium text-gray-700">Fecha Fin Prevista</label>
                                <input type="date" name="fecha_fin_prevista" id="fecha_fin_prevista" value="{{ old('fecha_fin_prevista') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            {{-- Estado Inicial --}}
                            <div class="mb-4">
                                <label for="estado" class="block text-gray-700 text-sm font-bold mb-2">Estado Inicial *</label>
                                <select name="estado" id="estado" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm sm:text-sm">
                                    
                                    {{-- Opción por defecto (deshabilitada) --}}
                                    <option value="" disabled {{ old('estado') ? '' : 'selected' }}>Selecciona un estado</option>

                                    {{-- Opción: Pendiente --}}
                                    <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>
                                        Pendiente
                                    </option>
                                    
                                    {{-- Opción: En proceso --}}
                                    <option value="En proceso" {{ old('estado') == 'En proceso' ? 'selected' : '' }}>
                                        En proceso
                                    </option>
                                    
                                    {{-- Opción: Completado --}}
                                    <option value="Completado" {{ old('estado') == 'Completado' ? 'selected' : '' }}>
                                        Completado
                                    </option>

                                    {{-- Opción: Cancelado --}}
                                    <option value="Cancelado" {{ old('estado') == 'Cancelado' ? 'selected' : '' }}>
                                        Cancelado
                                    </option>

                                </select>
    @error('estado')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>

                            {{-- Localización --}}
                            <div>
                                <label for="localizacion" class="block text-sm font-medium text-gray-700">Localización / Dirección</label>
                                <input type="text" name="localizacion" id="localizacion" value="{{ old('localizacion') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            {{-- Descripción --}}
                            <div class="col-span-2">
                                <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                                <textarea name="descripcion" id="descripcion" rows="3" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Detalles del proyecto...">{{ old('descripcion') }}</textarea>
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('proyectos.index') }}" class="mr-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded shadow">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition duration-150 ease-in-out">
                                Guardar Proyecto
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

