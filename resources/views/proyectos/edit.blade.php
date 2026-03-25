<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div class="dashboard-container">
        
        <!-- Box flotante estilo página -->
        <div class="page-box">
            <div class="page-box-inner">
                <div class="page-box-titulo">
                    <h1>Editar Proyecto</h1>
                    <p>{{ $proyecto->nombre_proyecto }}</p>
                </div>
                <div class="page-box-botones">
                    <a href="{{ route('proyectos.show', $proyecto->id_proyecto) }}" class="btn-box-dashboard">
                        ← Volver al proyecto
                    </a>
                </div>
            </div>
        </div>

        {{-- Mostrar errores de validación --}}
        @if ($errors->any())
            <div class="alert alert-error" style="margin: 0 1.5rem 1.5rem 1.5rem;">
                <strong>¡Ups! Revisa los campos:</strong>
                <ul style="margin-top: 0.5rem; padding-left: 1.25rem; list-style: disc;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-container container-pad" style="margin: 0 1.5rem;">
            <form action="{{ route('proyectos.update', $proyecto->id_proyecto) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    
                    {{-- Nombre del Proyecto --}}
                    <div class="form-group form-group-full">
                        <label class="form-label" for="nombre_proyecto">Nombre del Proyecto *</label>
                        <input class="form-input" type="text" name="nombre_proyecto" id="nombre_proyecto" 
                               value="{{ old('nombre_proyecto', $proyecto->nombre_proyecto) }}" required>
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
                                <option value="{{ $cliente->id_usuario }}" 
                                    {{ (old('id_usuario', $proyecto->id_usuario) == $cliente->id_usuario) ? 'selected' : '' }}>
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
                        <select name="tipo_proyecto" id="tipo_proyecto" required class="form-input">
                            @foreach(['Residencial', 'Comercial', 'Industrial', 'Reforma'] as $tipo)
                                <option value="{{ $tipo }}" {{ (old('tipo_proyecto', $proyecto->tipo_proyecto) == $tipo) ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_proyecto') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Fecha Inicio --}}
                    <div class="form-group">
                        <label class="form-label" for="fecha_inicio">Fecha Inicio *</label>
                        <input class="form-input" type="date" name="fecha_inicio" id="fecha_inicio" 
                               value="{{ old('fecha_inicio', optional($proyecto->fecha_inicio)->format('Y-m-d')) }}" required>
                        @error('fecha_inicio') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Fecha Fin Prevista --}}
                    <div class="form-group">
                        <label class="form-label" for="fecha_fin_prevista">Fecha Fin Prevista</label>
                        <input class="form-input" type="date" name="fecha_fin_prevista" id="fecha_fin_prevista" 
                               value="{{ old('fecha_fin_prevista', optional($proyecto->fecha_fin_prevista)->format('Y-m-d')) }}">
                        @error('fecha_fin_prevista') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Estado --}}
                    <div class="form-group">
                        <label class="form-label" for="estado">Estado *</label>
                        <select name="estado" id="estado" required class="form-input">
                            @foreach(['Pendiente', 'En proceso', 'Completado', 'Cancelado'] as $estado)
                                <option value="{{ $estado }}" {{ (old('estado', $proyecto->estado) == $estado) ? 'selected' : '' }}>
                                    {{ $estado }}
                                </option>
                            @endforeach
                        </select>
                        @error('estado') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Localización --}}
                    <div class="form-group">
                        <label class="form-label" for="localizacion">Localización / Dirección</label>
                        <input class="form-input" type="text" name="localizacion" id="localizacion" 
                               value="{{ old('localizacion', $proyecto->localizacion) }}">
                        @error('localizacion') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group form-group-full">
                        <label class="form-label" for="descripcion">Descripción</label>
                        <textarea class="form-input" name="descripcion" id="descripcion" rows="3">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                        @error('descripcion') 
                            <div class="form-error">{{ $message }}</div> 
                        @enderror
                    </div>

                </div>

                <div class="form-actions">
                    <a href="{{ route('proyectos.show', $proyecto->id_proyecto) }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary btn-auto">
                        Actualizar Proyecto
                    </button>
                </div>
            </form>
        </div>

        <!-- Sección Danger: Eliminar proyecto -->
        <div class="danger-zone" style="margin: 1.5rem 1.5rem 0 1.5rem;">
            <h3 class="danger-title">Eliminar proyecto</h3>
            <p class="danger-description">
                Esta acción es irreversible. Se borrará el proyecto y todos sus datos asociados.
            </p>

            <form method="POST" action="{{ route('proyectos.destroy', $proyecto->id_proyecto) }}"
                  onsubmit="return confirm('¿Seguro que quieres eliminar este proyecto?');">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">
                    Eliminar proyecto
                </button>
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

