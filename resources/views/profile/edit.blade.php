<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">


    <div class="bg-white shadow sm:rounded-lg p-4 sm:p-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Perfil</h1>
            <p class="text-sm text-gray-600 mt-1">Gestiona tu información y tu contraseña.</p>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-auto">
            ← Volver al dashboard
        </a>
    </div>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>