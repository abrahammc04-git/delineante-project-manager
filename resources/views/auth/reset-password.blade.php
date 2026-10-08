<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div class="guest-layout">
        <div class="auth-container">
            <div class="logo-container">
                <img src="{{ asset('images/proyinstal-logo.png') }}" alt="PROYINSTAL">
            </div>

            <h1 class="auth-title">Restablecer Contraseña</h1>
            <p class="auth-subtitle">Introduce tu nueva contraseña</p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="form-group">
                    <label class="form-label" for="email">Correo Electrónico</label>
                    <input class="form-input" id="email" type="email" name="email"
                           value="{{ old('email', $request->email) }}"
                           required autofocus autocomplete="username" placeholder="tu@email.com">
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Nueva Contraseña</label>
                    <input class="form-input" id="password" type="password" name="password"
                           required autocomplete="new-password" placeholder="Mínimo 8 caracteres">
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirmar Contraseña</label>
                    <input class="form-input" id="password_confirmation" type="password"
                           name="password_confirmation" required autocomplete="new-password"
                           placeholder="Repite la contraseña">
                    @error('password_confirmation')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top:1.5rem;">
                    <span style="position:relative;z-index:1;">Restablecer Contraseña</span>
                </button>
            </form>

            <div class="auth-footer">
                ¿Recuerdas tu contraseña?
                <a href="{{ route('login') }}" class="link">Volver al inicio de sesión</a>
            </div>
        </div>
    </div>
</x-guest-layout>
