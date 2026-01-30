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
        <div class="min-h-screen" style="background: linear-gradient(135deg, #F9FAFB 0%, #E5E7EB 100%);">
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
                    confirmButtonColor: '#3B82F6', // Azul Tailwind
                    timer: 3000
                });
            @endif

            // 2. Si hay mensajes de ERROR desde el controlador (with('error', ...))
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '¡Ups!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#EF4444', // Rojo Tailwind
                });
            @endif

            // 3. Si hay errores de VALIDACIÓN (como archivo muy grande detectado por Request)
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

            // 4. FUNCIÓN PARA CONFIRMAR BORRADO (La usaremos en tus botones)
            function confirmarBorrado(event, nombreElemento) {
                event.preventDefault(); // Detiene el envío del formulario
                const form = event.target; // Captura el formulario

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Vas a eliminar: " + nombreElemento + ". Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444', // Rojo
                    cancelButtonColor: '#6B7280',  // Gris
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Si dice que sí, enviamos el formulario manualmente
                    }
                });
            }
        </script>
    </body>
</html>
