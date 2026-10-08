<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div class="dashboard-container">
        
        <!-- Box superior mejorado -->
        <div class="page-box">
            <div class="page-box-inner">
                <div class="page-box-titulo">
                    <h1>Gestión de Clientes</h1>
                    <p>Administra y busca clientes registrados en el sistema</p>
                </div>
                <div class="page-box-botones">
                    <a href="{{ route('dashboard') }}" class="btn-box-dashboard">
                        ← Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Buscador principal (fuera de la tabla) -->
        <div class="usuarios-buscador-wrap">
            <form method="GET" action="{{ route('usuarios.index') }}" class="usuarios-buscador-form">
                <div class="usuarios-buscador-grid">
                    <input class="usuarios-input" name="nombre" value="{{ request('nombre') }}" 
                           placeholder="Nombre o apellidos">
                    
                    <input class="usuarios-input" name="empresa" value="{{ request('empresa') }}" 
                           placeholder="Empresa">
                    
                    <input class="usuarios-input" name="telefono" value="{{ request('telefono') }}" 
                           placeholder="Teléfono">
                    
                    <input class="usuarios-input" name="email" value="{{ request('email') }}" 
                           placeholder="Email">
                    
                    <select class="usuarios-input" name="activo">
                        <option value="">Todos los estados</option>
                        <option value="1" @selected(request('activo')==='1')>Activos</option>
                        <option value="0" @selected(request('activo')==='0')>Inactivos</option>
                    </select>
                </div>
                
                <div class="usuarios-buscador-acciones">
                    <button type="submit" class="btn-usuarios-buscar">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Buscar
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn-usuarios-limpiar">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabla limpia sin filtros -->
        <div class="table-container" style="margin: 0 1.5rem;">
            <div class="tabla-overflow">
                <table>
                    <thead>
                        <tr>
                            <th>NOMBRE</th>
                            <th>EMAIL</th>
                            <th>TELÉFONO</th>
                            <th>EMPRESA</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $u)
                        <tr>
                            <td class="td-nombre">
                                {{ $u->nombre }} {{ $u->apellidos }}
                            </td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->telefono ?? '-' }}</td>
                            <td>{{ $u->empresa ?? '-' }}</td>
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