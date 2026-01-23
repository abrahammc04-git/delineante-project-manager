<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/proyinstal-styles.css') }}">
    
    <div class="guest-layout">
        <div class="auth-container">
            <!-- Logo -->
            <div class="logo-container">
                <img src="{{ asset('images/proyinstal-logo.png') }}" alt="PROYINSTAL">
            </div>
            
            <!-- Título -->
            <h1 class="auth-title">Recuperar Contraseña</h1>
            <p class="auth-subtitle">
                Introduce tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña
            </p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        class="form-input" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        placeholder="tu@email.com"
                    >
                    @error('email')
                        <p class="form-error">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary" style="margin-top: 1.5rem;">
                    <span style="position: relative; z-index: 1;">Enviar Enlace de Recuperación</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="auth-footer">
                ¿Recordaste tu contraseña? 
                <a href="{{ route('login') }}" class="link">Volver a iniciar sesión</a>
            </div>
        </div>
    </div>
</x-guest-layout>
