<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-logo">
                <img src="{{ asset('images/proyinstal-logo.png') }}" alt="PROYINSTAL">
            </div>

            <div class="navbar-right">
                <div class="navbar-user">
                    <span class="navbar-name">{{ auth()->user()->nombre }}</span>
                    <span class="navbar-role">{{ auth()->user()->rol }}</span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="navbar-link navbar-logout">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </nav>


    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-hero">
            <div class="dashboard-hero-content">
                <div>
                    <h1 class="dashboard-title">Hola, {{ auth()->user()->nombre }}</h1>
                    <p class="dashboard-subtitle">
                        Aquí tienes un resumen de tus proyectos y actividad reciente
                    </p>
                </div>

                <div class="dashboard-hero-actions">
                    <a class="btn btn-hero" href="{{ route('proyectos.index') }}">
                        Ver proyectos
                    </a>

                    @if(auth()->user()->rol === 'admin')
                    <a class="btn btn-hero-outline" href="{{ route('usuarios.index') }}">
                        Usuarios
                    </a>
                    @endif

                    <a class="btn btn-hero-outline" href="{{ route('chat.index') }}">
                        Chats
                    </a>

                    <a class="btn btn-hero-outline" href="{{ route('profile.edit') }}">
                        Mi perfil
                    </a>
                </div>
            </div>
        </div>


        <!-- Stats Grid -->
        <div class="stats-grid">
            <!-- Total Proyectos -->
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <h3 class="stat-title">Total Proyectos</h3>
                        <p class="stat-value">{{ $total }}</p>
                        <p class="stat-change">Proyectos totales</p>
                    </div>
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- En Proceso -->
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <h3 class="stat-title">En Proceso</h3>
                        <p class="stat-value">{{ $enProceso }}</p>
                        <p class="stat-change">Activos actualmente</p>
                    </div>
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completados -->
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <h3 class="stat-title">Completados</h3>
                        <p class="stat-value">{{ $completados }}</p>
                        <p class="stat-change">
                            {{ $porcentajeCompletados }}% completados
                        </p>

                    </div>
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pendientes -->
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <h3 class="stat-title">Pendientes</h3>
                        <p class="stat-value">{{ $pendientes }}</p>
                        <p class="stat-change">Por iniciar</p>
                    </div>
                    <div class="stat-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proyectos Recientes -->
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Proyectos Recientes</h2>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        @if(auth()->user()->rol === 'admin')
                        <th>Cliente</th>
                        @endif
                        <th>Estado</th>
                        <th>Tipo</th>
                        <th>Última Actualización</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proyectosRecientes as $p)
                    @php
                    // ✅ Columnas reales de tu tabla proyectos
                    $nombre = $p->nombre_proyecto ?? 'Sin nombre';
                    $cliente = $p->usuario
                    ? trim(($p->usuario->nombre ?? '') . ' ' . ($p->usuario->apellidos ?? ''))
                    : '—';

                    $tipo = $p->tipo_proyecto ?? '—';

                    $estado = strtolower(trim($p->estado ?? 'pendiente'));

                    // ✅ Mismo mapeo de colores (clases CSS que YA tienes)
                    $badgeClass = match ($estado) {
                    'pendiente' => 'badge badge-pending',
                    'en_proceso', 'en proceso' => 'badge badge-in-progress',
                    'completado' => 'badge badge-completed',
                    'pausado' => 'badge badge-paused',
                    'cancelado', 'cancelado/a', 'cancelada' => 'badge badge-cancelled',
                    default => 'badge badge-paused',
                    };

                    $estadoLabel = match ($estado) {
                    'pendiente' => 'Pendiente',
                    'en_proceso', 'en proceso' => 'En Proceso',
                    'completado' => 'Completado',
                    'pausado' => 'Pausado',
                    'cancelado', 'cancelado/a', 'cancelada' => 'Cancelado',
                    default => ucfirst(str_replace('_', ' ', $estado)),
                    };

                    // ✅ Tu columna real de fecha
                    $ultima = $p->ultima_actualizacion
                    ? \Carbon\Carbon::parse($p->ultima_actualizacion)->isFuture()
                    ? 'hace 0 minutos'
                    : \Carbon\Carbon::parse($p->ultima_actualizacion)->diffForHumans()
                    : '—';




                    // ✅ Tu PK real
                    $idProyecto = $p->id_proyecto;
                    @endphp

                    <tr>
                        <td class="td-nombre">
                            {{ $nombre }}
                        </td>
                        @if(auth()->user()->rol === 'admin')
                        <td>{{ $cliente }}</td>
                        @endif
                        <td><span class="{{ $badgeClass }}">{{ $estadoLabel }}</span></td>
                        <td>{{ $tipo }}</td>
                        <td>{{ $ultima }}</td>
                        <td>
                            @if($idProyecto)
                            <a href="{{ route('proyectos.show', $idProyecto) }}" class="enlace-ver-detalles">
                                Ver detalles
                            </a>
                            @else
                            <span class="td-vacia" style="padding:0;">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    {{-- Si no hay proyectos, no mostramos filas "inventadas" --}}
                    <tr>
                        <td colspan="6" class="td-vacia">
                            No hay proyectos todavía.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</x-app-layout>