<x-app-layout>
    <div class="dashboard-container">

        <div class="page-box">
            <div class="page-box-inner">
                <div class="page-box-titulo">
                    <h1>Gestión de Empresas</h1>
                    <p>Administra las empresas registradas en el sistema</p>
                </div>
                <div class="page-box-botones">
                    <a href="{{ route('dashboard') }}" class="btn-box-dashboard">← Dashboard</a>
                    <a href="{{ route('empresas.create') }}" class="btn-box-primary">+ Nueva Empresa</a>
                </div>
            </div>
        </div>

        <div class="content-box">
            @if($empresas->isEmpty())
                <p>No hay empresas registradas.</p>
            @else
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>CIF</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($empresas as $empresa)
                        <tr>
                            <td>{{ $empresa->nombre_empresa }}</td>
                            <td>{{ $empresa->cif ?? '—' }}</td>
                            <td>{{ $empresa->telefono ?? '—' }}</td>
                            <td>{{ $empresa->email_contacto ?? '—' }}</td>
                            <td>{{ $empresa->activo ? 'Activa' : 'Inactiva' }}</td>
                            <td>
                                <a href="{{ route('empresas.show', $empresa->id_empresa) }}">Ver</a>
                                <a href="{{ route('empresas.edit', $empresa->id_empresa) }}">Editar</a>
                                <form method="POST" action="{{ route('empresas.destroy', $empresa->id_empresa) }}" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</x-app-layout>
