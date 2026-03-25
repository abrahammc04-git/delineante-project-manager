<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div class="dashboard-container">

        <div class="page-box">
            <div class="page-box-inner">
                <div class="page-box-titulo">
                    <h1>Detalles del Cliente</h1>
                    <p>Edita los datos del cliente o elimina su cuenta</p>
                </div>
                <div class="page-box-botones">
                    <a href="{{ route('usuarios.index') }}" class="btn-box-dashboard">
                        ← Volver a clientes
                    </a>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success" style="margin: 0 1.5rem 1.5rem;">
                {{ session('status') }}
            </div>
        @endif

        <div class="table-container container-pad" style="margin: 0 1.5rem;">
            <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                @csrf
                @method('PATCH')

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="nombre">Nombre</label>
                        <input class="form-input" id="nombre" name="nombre" type="text"
                               value="{{ old('nombre', $usuario->nombre) }}" required>
                        @error('nombre') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="apellidos">Apellidos</label>
                        <input class="form-input" id="apellidos" name="apellidos" type="text"
                               value="{{ old('apellidos', $usuario->apellidos) }}" required>
                        @error('apellidos') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-input" id="email" name="email" type="email"
                               value="{{ old('email', $usuario->email) }}" required>
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="telefono">Teléfono</label>
                        <input class="form-input" id="telefono" name="telefono" type="text"
                               value="{{ old('telefono', $usuario->telefono) }}">
                        @error('telefono') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="empresa">Empresa</label>
                        <input class="form-input" id="empresa" name="empresa" type="text"
                               value="{{ old('empresa', $usuario->empresa) }}">
                        @error('empresa') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="activo">Estado</label>
                        <select class="form-input" id="activo" name="activo" required>
                            <option value="1" @selected(old('activo', $usuario->activo) == 1)>Activo</option>
                            <option value="0" @selected(old('activo', $usuario->activo) == 0)>Inactivo</option>
                        </select>
                        @error('activo') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-auto">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>

        <div class="danger-zone">
            <h3 class="danger-title">Eliminar usuario</h3>
            <p class="danger-description">
                Esta acción es irreversible. Se borrará el usuario y su acceso al sistema.
            </p>
            <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}"
                  onsubmit="confirmarBorrado(event, '{{ $usuario->nombre }} {{ $usuario->apellidos }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    Eliminar usuario
                </button>
            </form>
        </div>

    </div>
</x-app-layout>
