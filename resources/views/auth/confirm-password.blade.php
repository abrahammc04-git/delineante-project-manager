<x-guest-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <div class="guest-layout">
        <div class="auth-container">
            <div class="logo-container">
                <img src="{{ asset('images/proyinstal-logo.png') }}" alt="PROYINSTAL">
            </div>

            <h1 class="auth-title">Confirmar Contraseña</h1>
            <p class="auth-subtitle">
                Estás accediendo a una zona segura. Confirma tu contraseña para continuar.
            </p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="password">Contraseña</label>
                    <input class="form-input" id="password" type="password" name="password"
                           required autocomplete="current-password" placeholder="••••••••">
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top:1.5rem;">
                    <span style="position:relative;z-index:1;">Confirmar</span>
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
