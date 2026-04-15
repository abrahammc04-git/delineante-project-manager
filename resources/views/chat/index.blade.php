<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mensajería Interna') }}
        </h2>
    </x-slot>

    <!-- Box superior mejorado -->
        <div class="page-box">
            <div class="page-box-inner">
                <div class="page-box-titulo">
                    <h1>Chats</h1>
                    <p>Administra tus conversaciones</p>
                </div>
                <div class="page-box-botones">
                    <a href="{{ route('dashboard') }}" class="btn-box-dashboard">
                        ← Dashboard
                    </a>
                </div>
            </div>
        </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Contenedor Principal --}}
            <div class="chat-container">
                
                {{-- SIDEBAR: Lista de conversaciones --}}
                <div class="chat-sidebar">
                    <div class="chat-sidebar-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 class="chat-sidebar-title">Tus Chats</h3>

                        @if(auth()->user()->isAdmin())
                            <button type="button" onclick="document.getElementById('modalNuevoChat').style.display='flex'" style="background: var(--color-primary-dark); color: white; border: none; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; cursor: pointer; box-shadow: var(--shadow-sm);">
                                +
                            </button>
                        @endif
                    </div>

                    @if(auth()->user()->isAdmin())
                        <div id="modalNuevoChat" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
                            <div style="background: white; padding: 2rem; border-radius: var(--radius-xl); width: 100%; max-width: 400px; box-shadow: var(--shadow-xl);">
                                <h3 style="font-size: var(--text-lg); font-weight: bold; margin-bottom: 1rem; color: var(--color-primary-dark);">Iniciar Nuevo Chat</h3>
                                
                                <form action="{{ route('chat.nueva') }}" method="POST">
                                    @csrf
                                    <div style="margin-bottom: 1.5rem;">
                                        <label style="display: block; font-size: var(--text-sm); font-weight: 600; margin-bottom: 0.5rem; color: var(--color-gray-700);">Selecciona un Cliente:</label>
                                        <select name="id_usuario" required style="width: 100%; border-radius: 0.5rem; border: 1px solid var(--color-gray-300); padding: 0.5rem;">
                                            <option value="">-- Elige un cliente --</option>
                                            @foreach($usuariosParaChat as $cliente)
                                                <option value="{{ $cliente->id_usuario }}">{{ $cliente->nombre }} {{ $cliente->apellidos }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                                        <button type="button" onclick="document.getElementById('modalNuevoChat').style.display='none'" style="background: transparent; border: 1px solid var(--color-gray-300); padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer;">Cancelar</button>
                                        <button type="submit" style="background: var(--color-primary-dark); color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer; font-weight: bold;">Crear Chat</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    
                    <ul class="divide-y divide-gray-200">
                        @forelse($conversaciones as $conv)
                            <li>
                                <a href="{{ route('chat.show', $conv->id_conversacion) }}" class="chat-item-link">
                                    <div class="flex items-center justify-between">
                                        <p class="chat-item-subtitle">
                                            @if($conv->mensajes->isNotEmpty())
                                                {{ Str::limit($conv->mensajes->last()->contenido ?? '📎 Archivo enviado', 40) }}
                                            @else
                                                Sin mensajes aún
                                            @endif
                                        </p>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="p-4 text-sm text-gray-500 text-center">No tienes conversaciones activas.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- MAIN: Área de mensajes (Vacía en el index) --}}
                <div class="chat-main-area">
                    <svg class="chat-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    <p class="chat-empty-text">Selecciona un chat para empezar a escribir</p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>