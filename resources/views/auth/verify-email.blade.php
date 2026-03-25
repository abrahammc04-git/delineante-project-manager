<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div class="guest-layout">
        <div class="auth-container">
            <div class="logo-container">
                <img src="{{ asset('images/proyinstal-logo.png') }}" alt="PROYINSTAL">
            </div>

            <h1 class="auth-title">Verifica tu Email</h1>
            <p class="auth-subtitle">
                Gracias por registrarte. Antes de continuar, haz clic en el enlace de verificación
                que te hemos enviado por correo electrónico.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                </div>
            @endif

            <div style="display:flex; flex-direction:column; gap:0.75rem; margin-top:1.5rem;">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <span style="position:relative;z-index:1;">Reenviar Email de Verificación</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-auto" style="width:100%;">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
