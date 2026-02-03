<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/proyinstal-styles.css') }}">

    <div class="dashboard-container">
        <div class="table-container">
            <div class="table-header table-header-flex">
                <div>
                    <h2 class="table-title">Clientes</h2>
                    <p class="table-header-subtitle">
                        Busca por nombre, empresa, teléfono, email y estado.
                    </p>
                </div>

                <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-auto">
                    ← Volver al dashboard
                </a>
            </div>

            {{-- Filtros --}}
            <div class="filtros-container">
                <form method="GET" action="{{ route('usuarios.index') }}" class="form-grid filtros-grid">
                    <div class="form-group form-group-no-mb">
                        <label class="form-label">Nombre</label>
                        <input class="form-input" name="nombre" value="{{ request('nombre') }}" placeholder="Ej: Juan">
                    </div>

                    <div class="form-group form-group-no-mb">
                        <label class="form-label">Empresa</label>
                        <input class="form-input" name="empresa" value="{{ request('empresa') }}" placeholder="Ej: Construcciones">
                    </div>

                    <div class="form-group form-group-no-mb">
                        <label class="form-label">Teléfono</label>
                        <input class="form-input" name="telefono" value="{{ request('telefono') }}" placeholder="Ej: 666">
                    </div>

                    <div class="form-group form-group-no-mb">
                        <label class="form-label">Email</label>
                        <input class="form-input" name="email" value="{{ request('email') }}" placeholder="Ej: cliente@...">
                    </div>

                    <div class="form-group form-group-no-mb">
                        <label class="form-label">Estado</label>
                        <select class="form-input" name="activo">
                            <option value="">Todos</option>
                            <option value="1" @selected(request('activo')==='1')>Activos</option>
                            <option value="0" @selected(request('activo')==='0')>Inactivos</option>
                        </select>
                    </div>

                    <div class="form-group-full filtros-actions">
                        <button class="btn btn-primary btn-auto">Buscar</button>
                        <a class="btn btn-secondary btn-auto" href="{{ route('usuarios.index') }}">Limpiar</a>
                    </div>
                </form>
            </div>

            {{-- Tabla --}}
            <div class="tabla-overflow">
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
                            <td class="td-nombre">
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
                                <a href="{{ route('usuarios.show', $u) }}" class="enlace-ver-detalles">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="td-vacia">
                                No hay usuarios que coincidan con los filtros.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginacion-wrap">
                {{ $usuarios->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
