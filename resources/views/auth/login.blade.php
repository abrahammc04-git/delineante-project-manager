<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/proyinstal-styles.css') }}">
    
    <div class="guest-layout">
        <div class="auth-container">
            <!-- Logo -->
            <div class="logo-container">
                <img src="{{ asset('images/proyinstal-logo.png') }}" alt="PROYINSTAL">
            </div>
            
            <!-- Título -->
            <h1 class="auth-title">Bienvenido</h1>
            <p class="auth-subtitle">Inicia sesión para acceder a tu panel de proyectos</p>
            
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

            <form method="POST" action="{{ route('login') }}">
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
                        autocomplete="username"
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

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        class="form-input" 
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••"
                    >
                    @error('password')
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

                <!-- Remember Me -->
                <div class="form-checkbox-group">
                    <input 
                        id="remember_me" 
                        type="checkbox" 
                        name="remember" 
                        class="form-checkbox"
                    >
                    <label for="remember_me" class="form-checkbox-label">
                        Recuérdame en este dispositivo
                    </label>
                </div>

                <!-- Forgot Password Link -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    @if (Route::has('password.request'))
                        <a class="link" href="{{ route('password.request') }}" style="font-size: 0.875rem;">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">
                    <span style="position: relative; z-index: 1;">Iniciar Sesión</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="auth-footer">
                ¿No tienes una cuenta? 
                <a href="{{ route('register') }}" class="link">Regístrate aquí</a>
            </div>
        </div>
    </div>
</x-guest-layout>
