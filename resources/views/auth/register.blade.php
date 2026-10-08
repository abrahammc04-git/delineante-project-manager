<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    
    <div class="guest-layout">
        <div class="auth-container" style="max-width: 750px;">
            <!-- Logo -->
            <div class="logo-container">
                <img src="{{ asset('images/proyinstal-logo.png') }}" alt="PROYINSTAL">
            </div>
            
            <!-- Título -->
            <h1 class="auth-title">Crear Cuenta</h1>
            <p class="auth-subtitle">Completa el formulario para registrarte en la plataforma</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-grid">
                    <!-- Nombre -->
                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input 
                            id="nombre" 
                            type="text" 
                            name="nombre" 
                            class="form-input" 
                            value="{{ old('nombre') }}" 
                            required 
                            autofocus 
                            autocomplete="given-name"
                            placeholder="Juan"
                        >
                        @error('nombre')
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

                    <!-- Apellidos -->
                    <div class="form-group">
                        <label for="apellidos" class="form-label">Apellidos *</label>
                        <input 
                            id="apellidos" 
                            type="text" 
                            name="apellidos" 
                            class="form-input" 
                            value="{{ old('apellidos') }}" 
                            required 
                            autocomplete="family-name"
                            placeholder="García López"
                        >
                        @error('apellidos')
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

                    <!-- Email - Ancho completo -->
                    <div class="form-group form-group-full">
                        <label for="email" class="form-label">Correo Electrónico *</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            class="form-input" 
                            value="{{ old('email') }}" 
                            required 
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

                    <!-- Teléfono -->
                    <div class="form-group">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input 
                            id="telefono" 
                            type="text" 
                            name="telefono" 
                            class="form-input" 
                            value="{{ old('telefono') }}" 
                            autocomplete="tel"
                            placeholder="+34 600 000 000"
                        >
                        @error('telefono')
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

                    <!-- Empresa -->
                    <div class="form-group">
                        <label for="empresa" class="form-label">Empresa</label>
                        <input 
                            id="empresa" 
                            type="text" 
                            name="empresa" 
                            class="form-input" 
                            value="{{ old('empresa') }}" 
                            autocomplete="organization"
                            placeholder="Mi Empresa S.L."
                        >
                        @error('empresa')
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
                        <label for="password" class="form-label">Contraseña *</label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            class="form-input" 
                            required 
                            autocomplete="new-password"
                            placeholder="Mínimo 8 caracteres"
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

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña *</label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            class="form-input" 
                            required 
                            autocomplete="new-password"
                            placeholder="Repite la contraseña"
                        >
                        @error('password_confirmation')
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
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary" style="margin-top: 2rem;">
                    <span style="position: relative; z-index: 1;">Crear Cuenta</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="auth-footer">
                ¿Ya tienes una cuenta? 
                <a href="{{ route('login') }}" class="link">Inicia sesión aquí</a>
            </div>
        </div>
    </div>

    <script>
        // Validación en tiempo real de contraseñas
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');

        passwordConfirmation.addEventListener('input', function() {
            if (password.value !== passwordConfirmation.value) {
                passwordConfirmation.setCustomValidity('Las contraseñas no coinciden');
            } else {
                passwordConfirmation.setCustomValidity('');
            }
        });
    </script>
</x-guest-layout>