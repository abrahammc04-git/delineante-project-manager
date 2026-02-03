<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/proyinstal-styles.css') }}">

    <div class="dashboard-container">
        <div class="table-container" style="margin-bottom: 1.5rem;">
            <div class="table-header" style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h2 class="table-title">Detalles del cliente</h2>
                    <p style="color: var(--proyinstal-gray-600); margin-top: .25rem;">
                        Edita los datos del cliente o elimina su cuenta.
                    </p>
                </div>

                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary" style="width:auto;">
                    ← Volver a clientes
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success" style="margin-bottom: 1rem;">
                {{ session('status') }}
            </div>
        @endif

        <div class="table-container" style="padding: 1.5rem;">
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

                <div style="display:flex; gap: 1rem; justify-content:flex-end; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary" style="width:auto;">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>

        <div class="table-container" style="margin-top: 1.5rem; padding: 1.5rem; border: 1px solid #FECACA;">
            <h3 style="font-weight:700; color:#991B1B; margin-bottom:.5rem;">Eliminar usuario</h3>
            <p style="color: var(--proyinstal-gray-600); margin-bottom: 1rem;">
                Esta acción es irreversible. Se borrará el usuario y su acceso al sistema.
            </p>

            <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}"
                  onsubmit="return confirm('¿Seguro que quieres eliminar este usuario?');">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn" style="width:auto; background:#DC2626; color:white;">
                    Eliminar usuario
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
