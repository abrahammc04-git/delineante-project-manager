<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PROYINSTAL') }} - Gestión de Proyectos</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen app-bg">
            <!-- Page Content -->
            {{ $slot }}
        </div>
        {{-- SweetAlert2 CDN --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- SCRIPT GLOBAL PARA ALERTAS Y CONFIRMACIONES --}}
        <script>
            // 1. Si hay mensajes de ÉXITO desde el controlador (with('success', ...))
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Hecho!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3B82F6',
                    timer: 3000
                });
            @endif

            // 2. Si hay mensajes de ERROR desde el controlador (with('error', ...))
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '¡Ups!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#EF4444',
                });
            @endif

            // 3. Si hay errores de VALIDACIÓN
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Revisa los campos',
                    html: `
                        <ul style="text-align: left;">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    `,
                    confirmButtonColor: '#EF4444',
                });
            @endif

            // 4. FUNCIÓN PARA CONFIRMAR BORRADO
            function confirmarBorrado(event, nombreElemento) {
                event.preventDefault();
                const form = event.target;

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Vas a eliminar: " + nombreElemento + ". Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        </script>
    </body>
</html>
