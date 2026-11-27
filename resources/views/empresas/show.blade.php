<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div class="dashboard-container">

        <div class="page-box">
            <div class="page-box-inner">
                <div class="page-box-titulo">
                    <h1>{{ $empresa->nombre_empresa }}</h1>
                    <p>Datos de la empresa y recursos asociados</p>
                </div>
                <div class="page-box-botones">
                    <a href="{{ route('empresas.index') }}" class="btn-box-dashboard">← Volver</a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('empresas.edit', $empresa->id_empresa) }}" class="btn-box-nuevo">
                            ✏️ Editar
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Datos de la empresa --}}
        <div class="show-grid" style="grid-template-columns: 1fr;">
            <div class="show-card">
                <h3 class="show-card-title">Información general</h3>
                <div class="show-data-grid">

                    <div class="show-field">
                        <p class="show-field-label">Nombre</p>
                        <p class="show-field-value">{{ $empresa->nombre_empresa }}</p>
                    </div>

                    <div class="show-field">
                        <p class="show-field-label">CIF</p>
                        <p class="show-field-value">{{ $empresa->cif ?? '—' }}</p>
                    </div>

                    <div class="show-field">
                        <p class="show-field-label">Teléfono</p>
                        <p class="show-field-value">{{ $empresa->telefono ?? '—' }}</p>
                    </div>

                    <div class="show-field">
                        <p class="show-field-label">Email de contacto</p>
                        <p class="show-field-value">{{ $empresa->email_contacto ?? '—' }}</p>
                    </div>

                    <div class="show-field">
                        <p class="show-field-label">Dirección</p>
                        <p class="show-field-value">{{ $empresa->direccion ?? '—' }}</p>
                    </div>

                    <div class="show-field">
                        <p class="show-field-label">Estado</p>
                        @if($empresa->activo)
                            <span class="badge badge-completed">Activa</span>
                        @else
                            <span class="badge badge-cancelled">Inactiva</span>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- Usuarios de la empresa --}}
        <div class="table-container" style="margin: 0 1.5rem 1.5rem;">
            <div class="table-header">
                <h2 class="table-title">Usuarios ({{ $empresa->usuarios->count() }})</h2>
            </div>
            <div class="tabla-overflow">
                <table>
                    <thead>
                        <tr>
                            <th>NOMBRE</th>
                            <th>EMAIL</th>
                            <th>TELÉFONO</th>
                            <th>ESTADO</th>
                            @if(Auth::user()->isAdmin())
                                <th>ACCIONES</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empresa->usuarios as $u)
                        <tr>
                            <td class="td-nombre">{{ $u->nombre }} {{ $u->apellidos }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->telefono ?? '—' }}</td>
                            <td>
                                @if($u->activo)
                                    <span class="badge badge-completed">Activo</span>
                                @else
                                    <span class="badge badge-cancelled">Inactivo</span>
                                @endif
                            </td>
                            @if(Auth::user()->isAdmin())
                            <td>
                                <a href="{{ route('usuarios.show', $u->id_usuario) }}" class="enlace-ver-detalles">
                                    Ver detalles
                                </a>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="td-vacia">No hay usuarios en esta empresa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Proyectos de la empresa --}}
        <div class="table-container" style="margin: 0 1.5rem 1.5rem;">
            <div class="table-header">
                <h2 class="table-title">Proyectos ({{ $empresa->proyectos->count() }})</h2>
            </div>
            <div class="tabla-overflow">
                <table>
                    <thead>
                        <tr>
                            <th>NOMBRE</th>
                            <th>TIPO</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empresa->proyectos as $p)
                        @php
                            $estado     = strtolower(trim($p->estado ?? 'pendiente'));
                            $badgeClass = match($estado) {
                                'pendiente'               => 'badge badge-pending',
                                'en_proceso','en proceso' => 'badge badge-in-progress',
                                'completado'              => 'badge badge-completed',
                                'pausado'                 => 'badge badge-paused',
                                default                   => 'badge badge-paused',
                            };
                            $estadoLabel = match($estado) {
                                'pendiente'               => 'Pendiente',
                                'en_proceso','en proceso' => 'En Proceso',
                                'completado'              => 'Completado',
                                'pausado'                 => 'Pausado',
                                default                   => ucfirst(str_replace('_', ' ', $estado)),
                            };
                        @endphp
                        <tr>
                            <td class="td-nombre">{{ $p->nombre_proyecto }}</td>
                            <td>{{ $p->tipo_proyecto ?? '—' }}</td>
                            <td><span class="{{ $badgeClass }}">{{ $estadoLabel }}</span></td>
                            <td>
                                <a href="{{ route('proyectos.show', $p->id_proyecto) }}" class="enlace-ver-detalles">
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="td-vacia">No hay proyectos en esta empresa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
