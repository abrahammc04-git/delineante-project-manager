<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div class="dashboard-container">

        <div class="page-box">
            <div class="page-box-inner">
                <div class="page-box-titulo">
                    <h1>Nueva Empresa</h1>
                    <p>Rellena los datos para registrar una nueva empresa cliente</p>
                </div>
                <div class="page-box-botones">
                    <a href="{{ route('empresas.index') }}" class="btn-box-dashboard">
                        ← Volver al listado
                    </a>
                </div>
            </div>
        </div>

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
            <form action="{{ route('empresas.store') }}" method="POST">
                @csrf

                <div class="form-grid">

                    <div class="form-group form-group-full">
                        <label class="form-label" for="nombre_empresa">Nombre de la empresa *</label>
                        <input class="form-input" type="text" id="nombre_empresa" name="nombre_empresa"
                               value="{{ old('nombre_empresa') }}" required
                               placeholder="Ej: Tech Solutions S.L.">
                        @error('nombre_empresa') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="cif">CIF</label>
                        <input class="form-input" type="text" id="cif" name="cif"
                               value="{{ old('cif') }}"
                               placeholder="Ej: B12345678">
                        @error('cif') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="telefono">Teléfono</label>
                        <input class="form-input" type="text" id="telefono" name="telefono"
                               value="{{ old('telefono') }}"
                               placeholder="Ej: 912345678">
                        @error('telefono') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email_contacto">Email de contacto</label>
                        <input class="form-input" type="email" id="email_contacto" name="email_contacto"
                               value="{{ old('email_contacto') }}"
                               placeholder="Ej: contacto@empresa.com">
                        @error('email_contacto') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group form-group-full">
                        <label class="form-label" for="direccion">Dirección</label>
                        <input class="form-input" type="text" id="direccion" name="direccion"
                               value="{{ old('direccion') }}"
                               placeholder="Ej: Calle Mayor 10, Madrid">
                        @error('direccion') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Estado</label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="checkbox" name="activo" value="1"
                                   {{ old('activo', true) ? 'checked' : '' }}>
                            <span>Empresa activa</span>
                        </label>
                    </div>

                </div>

                <div class="form-actions">
                    <a href="{{ route('empresas.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary btn-auto">Crear empresa</button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
