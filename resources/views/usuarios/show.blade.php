<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div class="dashboard-container">
        
        <!-- Box flotante de información del cliente -->
        <div style="background: linear-gradient(135deg, #0033CC, #001F7A); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="color: white; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                        Detalles del Cliente
                    </h1>
                    <p style="color: rgba(255,255,255,0.9); font-size: 0.95rem;">
                        Edita los datos del cliente o elimina su cuenta
                    </p>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="{{ route('usuarios.index') }}" 
                       style="background: rgba(255,255,255,0.2); color: white; padding: 0.65rem 1.25rem; border-radius: 8px; font-weight: 600; text-decoration: none; border: 1px solid rgba(255,255,255,0.3); transition: all 0.2s; display: inline-block;">
                        ← Volver a clientes
                    </a>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="table-container container-pad">
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

        <!-- Sección Danger: Eliminar usuario -->
        <div class="danger-zone">
            <h3 class="danger-title">Eliminar usuario</h3>
            <p class="danger-description">
                Esta acción es irreversible. Se borrará el usuario y su acceso al sistema.
            </p>

            <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}"
                  onsubmit="return confirm('¿Seguro que quieres eliminar este usuario?');">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">
                    Eliminar usuario
                </button>
            </form>
        </div>
    </div>
</x-app-layout>