<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div class="dashboard-container">

        <div class="page-box">
            <div class="page-box-inner">
                <div class="page-box-titulo">
                    <h1>Gestión de Empresas</h1>
                    <p>Administra las empresas registradas en el sistema</p>
                </div>
                <div class="page-box-botones">
                    <a href="{{ route('dashboard') }}" class="btn-box-dashboard">← Dashboard</a>
                    <a href="{{ route('empresas.create') }}" class="btn-box-nuevo">+ Nueva Empresa</a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" style="margin: 0 1.5rem 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-container" style="margin: 0 1.5rem;">
            <div class="tabla-overflow">
                <table>
                    <thead>
                        <tr>
                            <th>NOMBRE</th>
                            <th>CIF</th>
                            <th>TELÉFONO</th>
                            <th>EMAIL</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empresas as $empresa)
                        <tr>
                            <td class="td-nombre">{{ $empresa->nombre_empresa }}</td>
                            <td>{{ $empresa->cif ?? '—' }}</td>
                            <td>{{ $empresa->telefono ?? '—' }}</td>
                            <td>{{ $empresa->email_contacto ?? '—' }}</td>
                            <td>
                                @if($empresa->activo)
                                    <span class="badge badge-completed">Activa</span>
                                @else
                                    <span class="badge badge-cancelled">Inactiva</span>
                                @endif
                            </td>
                            <td style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <a href="{{ route('empresas.show', $empresa->id_empresa) }}" class="enlace-ver-detalles">
                                    Ver
                                </a>
                                <a href="{{ route('empresas.edit', $empresa->id_empresa) }}" class="enlace-ver-detalles">
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('empresas.destroy', $empresa->id_empresa) }}"
                                      onsubmit="return confirm('¿Eliminar {{ $empresa->nombre_empresa }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" style="font-size: 0.8rem; padding: 0.25rem 0.75rem;">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="td-vacia">No hay empresas registradas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
