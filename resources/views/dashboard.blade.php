<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/proyinstal-styles.css') }}">

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
                    <h1 class="dashboard-title">Hola, {{ auth()->user()->nombre }} 👋</h1>
                    <p class="dashboard-subtitle">Aquí tienes un resumen de tus proyectos y actividad reciente</p>
                </div>

                <div class="dashboard-hero-actions">
                    <a class="btn btn-hero" href="{{ route('proyectos.index') }}">Ver proyectos</a>
                    <a class="btn btn-hero-outline" href="{{ route('profile.edit') }}">Mi perfil</a>

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
                        <p class="stat-value">12</p>
                        <p class="stat-change">+2 este mes</p>
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
                        <p class="stat-value">5</p>
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
                        <p class="stat-value">7</p>
                        <p class="stat-change">58% tasa de éxito</p>
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
                        <p class="stat-value">3</p>
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
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th>Tipo</th>
                        <th>Última Actualización</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Ejemplo de fila -->
                    <tr>
                        <td style="font-weight: 600; color: var(--proyinstal-dark);">
                            Vivienda Unifamiliar Pamplona
                        </td>
                        <td>Juan García López</td>
                        <td>
                            <span class="badge badge-in-progress">En Proceso</span>
                        </td>
                        <td>Vivienda</td>
                        <td>Hace 2 días</td>
                        <td>
                            <a href="#" class="link" style="font-size: 0.875rem;">Ver detalles</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: var(--proyinstal-dark);">
                            Reforma Local Comercial
                        </td>
                        <td>María Sánchez</td>
                        <td>
                            <span class="badge badge-completed">Completado</span>
                        </td>
                        <td>Reforma</td>
                        <td>Hace 5 días</td>
                        <td>
                            <a href="#" class="link" style="font-size: 0.875rem;">Ver detalles</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: var(--proyinstal-dark);">
                            Edificio Residencial
                        </td>
                        <td>Pedro Martínez</td>
                        <td>
                            <span class="badge badge-pending">Pendiente</span>
                        </td>
                        <td>Edificio</td>
                        <td>Hace 1 semana</td>
                        <td>
                            <a href="#" class="link" style="font-size: 0.875rem;">Ver detalles</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: var(--proyinstal-dark);">
                            Topografía Terreno Industrial
                        </td>
                        <td>Construcciones García S.L.</td>
                        <td>
                            <span class="badge badge-in-progress">En Proceso</span>
                        </td>
                        <td>Topografía</td>
                        <td>Hace 3 días</td>
                        <td>
                            <a href="#" class="link" style="font-size: 0.875rem;">Ver detalles</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600; color: var(--proyinstal-dark);">
                            Instalaciones Eléctricas
                        </td>
                        <td>Ana López</td>
                        <td>
                            <span class="badge badge-paused">Pausado</span>
                        </td>
                        <td>Instalaciones</td>
                        <td>Hace 2 semanas</td>
                        <td>
                            <a href="#" class="link" style="font-size: 0.875rem;">Ver detalles</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
