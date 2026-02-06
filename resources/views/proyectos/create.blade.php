<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div class="dashboard-container">
        
        <!-- Box flotante estilo página -->
        <div class="page-box">
            <div class="page-box-inner">
                <div class="page-box-titulo">
                    <h1>Crear Nuevo Proyecto</h1>
                    <p>Completa los datos del nuevo proyecto</p>
                </div>
                <div class="page-box-botones">
                    <a href="{{ route('proyectos.index') }}" class="btn-box-dashboard">
                        ← Volver al listado
                    </a>
                </div>
            </div>
        </div>

        {{-- Mostrar errores de validación --}}
        @if ($errors->any())
            <div class="alert alert-error" style="margin: 0 1.5rem 1.5rem 1.5rem;">
                <strong>¡Ups! Revisa los siguientes campos:</strong>
                <ul style="margin-top: 0.5rem; padding-left: 1.25rem; list-style: disc;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-container container-pad" style="margin: 0 1.5rem;">
            <form action="{{ route('proyectos.store') }}" method="POST">
                @csrf

                <div class="form-grid">
                    
                    {{-- Nombre del Proyecto --}}
                    <div class="form-group form-group-full">
                        <label class="form-label" for="nombre_proyecto">Nombre del Proyecto *</label>
                        <input class="form-input" type="text" name="nombre_proyecto" id="nombre_proyecto" 
                               value="{{ old('nombre_proyecto') }}" required
                               placeholder="Ej: Reforma Integral Calle Mayor">
                        @error('nombre_proyecto') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Cliente --}}
                    <div class="form-group">
                        <label class="form-label" for="id_usuario">Asignar Cliente *</label>
                        <select name="id_usuario" id="select_cliente" required class="form-input">
                            <option value="">Selecciona un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id_usuario }}" {{ old('id_usuario') == $cliente->id_usuario ? 'selected' : '' }}>
                                    {{ $cliente->nombre }} {{ $cliente->apellidos }} ({{ $cliente->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_usuario') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Tipo de Proyecto --}}
                    <div class="form-group">
                        <label class="form-label" for="tipo_proyecto">Tipo de Proyecto *</label>
                        <select name="tipo_proyecto" id="tipo_proyecto" class="form-input">
                            <option value="" disabled {{ old('tipo_proyecto') ? '' : 'selected' }}>Selecciona un tipo</option>
                            <option value="Industrial" {{ old('tipo_proyecto') == 'Industrial' ? 'selected' : '' }}>Industrial</option>
                            <option value="Comercial" {{ old('tipo_proyecto') == 'Comercial' ? 'selected' : '' }}>Comercial</option>
                            <option value="Residencial" {{ old('tipo_proyecto') == 'Residencial' ? 'selected' : '' }}>Residencial</option>
                            <option value="Reforma" {{ old('tipo_proyecto') == 'Reforma' ? 'selected' : '' }}>Reforma</option>
                        </select>
                        @error('tipo_proyecto') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Fecha Inicio --}}
                    <div class="form-group">
                        <label class="form-label" for="fecha_inicio">Fecha Inicio *</label>
                        <input class="form-input" type="date" name="fecha_inicio" id="fecha_inicio" 
                               value="{{ old('fecha_inicio') }}" required>
                        @error('fecha_inicio') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Fecha Fin Prevista --}}
                    <div class="form-group">
                        <label class="form-label" for="fecha_fin_prevista">Fecha Fin Prevista</label>
                        <input class="form-input" type="date" name="fecha_fin_prevista" id="fecha_fin_prevista" 
                               value="{{ old('fecha_fin_prevista') }}">
                        @error('fecha_fin_prevista') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Estado Inicial --}}
                    <div class="form-group">
                        <label class="form-label" for="estado">Estado Inicial *</label>
                        <select name="estado" id="estado" class="form-input">
                            <option value="" disabled {{ old('estado') ? '' : 'selected' }}>Selecciona un estado</option>
                            <option value="Pendiente" {{ old('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="En proceso" {{ old('estado') == 'En proceso' ? 'selected' : '' }}>En proceso</option>
                            <option value="Completado" {{ old('estado') == 'Completado' ? 'selected' : '' }}>Completado</option>
                            <option value="Cancelado" {{ old('estado') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        @error('estado') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Localización --}}
                    <div class="form-group">
                        <label class="form-label" for="localizacion">Localización / Dirección</label>
                        <input class="form-input" type="text" name="localizacion" id="localizacion" 
                               value="{{ old('localizacion') }}">
                        @error('localizacion') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group form-group-full">
                        <label class="form-label" for="descripcion">Descripción</label>
                        <textarea class="form-input" name="descripcion" id="descripcion" rows="3" 
                                  placeholder="Detalles del proyecto...">{{ old('descripcion') }}</textarea>
                        @error('descripcion') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                </div>

                <div class="form-actions">
                    <a href="{{ route('proyectos.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary btn-auto">
                        Guardar Proyecto
                    </button>
                </div>
            </form>
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

