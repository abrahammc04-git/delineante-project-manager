<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div class="dashboard-container">

        <div class="page-box">
            <div class="page-box-inner">
                <div class="page-box-titulo">
                    <h1>Editar Empresa</h1>
                    <p>Modifica los datos de {{ $empresa->nombre_empresa }}</p>
                </div>
                <div class="page-box-botones">
                    <a href="{{ route('empresas.index') }}" class="btn-box-dashboard">
                        ← Volver al listado
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" style="margin: 0 1.5rem 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error" style="margin: 0 1.5rem 1.5rem;">
                <strong>¡Ups! Revisa los siguientes campos:</strong>
                <ul style="margin-top: 0.5rem; padding-left: 1.25rem; list-style: disc;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-container container-pad" style="margin: 0 1.5rem;">
            <form action="{{ route('empresas.update', $empresa->id_empresa) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-grid">

                    <div class="form-group form-group-full">
                        <label class="form-label" for="nombre_empresa">Nombre de la empresa *</label>
                        <input class="form-input" type="text" id="nombre_empresa" name="nombre_empresa"
                               value="{{ old('nombre_empresa', $empresa->nombre_empresa) }}" required>
                        @error('nombre_empresa') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="cif">CIF</label>
                        <input class="form-input" type="text" id="cif" name="cif"
                               value="{{ old('cif', $empresa->cif) }}">
                        @error('cif') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="telefono">Teléfono</label>
                        <input class="form-input" type="text" id="telefono" name="telefono"
                               value="{{ old('telefono', $empresa->telefono) }}">
                        @error('telefono') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email_contacto">Email de contacto</label>
                        <input class="form-input" type="email" id="email_contacto" name="email_contacto"
                               value="{{ old('email_contacto', $empresa->email_contacto) }}">
                        @error('email_contacto') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group form-group-full">
                        <label class="form-label" for="direccion">Dirección</label>
                        <input class="form-input" type="text" id="direccion" name="direccion"
                               value="{{ old('direccion', $empresa->direccion) }}">
                        @error('direccion') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="checkbox" name="activo" value="1"
                                   {{ old('activo', $empresa->activo) ? 'checked' : '' }}>
                            <span>Empresa activa</span>
                        </label>
                    </div>

                </div>

                <div class="form-actions">
                    <a href="{{ route('empresas.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary btn-auto">Guardar cambios</button>
                </div>
            </form>
        </div>

        <div class="danger-zone">
            <h3 class="danger-title">Eliminar empresa</h3>
            <p class="danger-description">
                Esta acción es irreversible. Se eliminarán también todos los proyectos y usuarios asociados.
            </p>
            <form method="POST" action="{{ route('empresas.destroy', $empresa->id_empresa) }}"
                  onsubmit="return confirm('¿Seguro que quieres eliminar {{ $empresa->nombre_empresa }}? Esta acción no se puede deshacer.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Eliminar empresa</button>
            </form>
        </div>

    </div>
</x-app-layout>
