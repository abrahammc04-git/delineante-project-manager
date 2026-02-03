<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/proyinstal-styles.css') }}">

    <div class="dashboard-container">
        <div class="table-container">
            <div class="table-header" style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h2 class="table-title">Clientes</h2>
                    <p style="color: var(--proyinstal-gray-600); margin-top: .25rem;">
                        Busca por nombre, empresa, teléfono, email y estado.
                    </p>
                </div>

                <a href="{{ route('dashboard') }}" class="btn btn-secondary" style="width:auto;">
                    ← Volver al dashboard
                </a>
            </div>


            {{-- Filtros --}}
            <div style="padding: 1.25rem; border-bottom: 1px solid var(--proyinstal-gray-200); background: var(--proyinstal-gray-50);">
                <form method="GET" action="{{ route('usuarios.index') }}" class="form-grid" style="grid-template-columns: repeat(5, 1fr); gap: 1rem;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Nombre</label>
                        <input class="form-input" name="nombre" value="{{ request('nombre') }}" placeholder="Ej: Juan">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Empresa</label>
                        <input class="form-input" name="empresa" value="{{ request('empresa') }}" placeholder="Ej: Construcciones">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Teléfono</label>
                        <input class="form-input" name="telefono" value="{{ request('telefono') }}" placeholder="Ej: 666">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Email</label>
                        <input class="form-input" name="email" value="{{ request('email') }}" placeholder="Ej: cliente@...">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Estado</label>
                        <select class="form-input" name="activo">
                            <option value="">Todos</option>
                            <option value="1" @selected(request('activo')==='1' )>Activos</option>
                            <option value="0" @selected(request('activo')==='0' )>Inactivos</option>
                        </select>
                    </div>

                    <div class="form-group-full" style="display:flex; gap:.75rem; margin-top:.75rem;">
                        <button class="btn btn-primary" style="width:auto;">Buscar</button>
                        <a class="btn btn-secondary" style="width:auto;" href="{{ route('usuarios.index') }}">Limpiar</a>
                    </div>
                </form>
            </div>

            {{-- Tabla --}}
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Empresa</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $u)
                        <tr>
                            <td style="font-weight: 600; color: var(--proyinstal-dark);">
                                {{ $u->nombre }} {{ $u->apellidos }}
                            </td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->telefono }}</td>
                            <td>{{ $u->empresa }}</td>
                            <td>
                                @if($u->activo)
                                <span class="badge badge-completed">Activo</span>
                                @else
                                <span class="badge badge-cancelled">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('usuarios.show', $u) }}"
                                    class="link"
                                    style="font-size: 0.875rem;">
                                    Ver detalles
                                </a>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="padding: 1.5rem; color: var(--proyinstal-gray-600);">
                                No hay usuarios que coincidan con los filtros.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding: 1rem 1.5rem;">
                {{ $usuarios->links() }}
            </div>
        </div>
    </div>
</x-app-layout>