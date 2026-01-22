<x-guest-layout>
    <div class="register-container">
        <h1>Registro</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Nombre -->
            <div>
                <x-input-label for="nombre" value="Nombre" />
                <x-text-input id="nombre" type="text" name="nombre" :value="old('nombre')" required />
                <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
            </div>

            <!-- Apellidos -->
            <div class="mt-4">
                <x-input-label for="apellidos" value="Apellidos" />
                <x-text-input id="apellidos" type="text" name="apellidos" :value="old('apellidos')" required />
                <x-input-error :messages="$errors->get('apellidos')" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Teléfono (opcional) -->
            <div class="mt-4">
                <x-input-label for="telefono" value="Teléfono (opcional)" />
                <x-text-input id="telefono" type="text" name="telefono" :value="old('telefono')" />
                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
            </div>

            <!-- Empresa (opcional) -->
            <div class="mt-4">
                <x-input-label for="empresa" value="Empresa (opcional)" />
                <x-text-input id="empresa" type="text" name="empresa" :value="old('empresa')" />
                <x-input-error :messages="$errors->get('empresa')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" value="Contraseña" />
                <x-text-input id="password" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" value="Confirmar Contraseña" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-4">
                <a href="{{ route('login') }}">
                    ¿Ya tienes cuenta?
                </a>

                <x-primary-button class="ms-4">
                    Registrarse
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>