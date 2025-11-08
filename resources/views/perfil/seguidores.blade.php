<!-- ============================================================ -->
<!-- VIEW SEGUIDORES - perfil/seguidores.blade.php -->
<!-- resources/views/perfil/seguidores.blade.php -->
<!-- ============================================================ -->

@extends('layout.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-green-50">
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-3xl shadow-lg p-8 mb-8 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-2">👥 Seguidores</h1>
                    <p class="text-green-100">{{ $usuario->username }} tem {{ $seguidores->total() }} seguidor(es)</p>
                </div>
                <a href="{{ route('perfil.show', $usuario->username) }}" class="px-6 py-3 bg-white text-green-600 rounded-lg hover:bg-gray-100 font-bold transition-all">
                    ← Voltar ao Perfil
                </a>
            </div>
        </div>

        <!-- Lista de Seguidores -->
        @if($seguidores->count() > 0)
            <div class="space-y-4">
                @foreach($seguidores as $item)
                    @php $usuario_seguidor = $item->seguidor; @endphp
                    <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between">
                            
                            <!-- Informações do Usuário -->
                            <div class="flex items-center gap-4 flex-1">
                                <a href="{{ route('perfil.show', $usuario_seguidor->username) }}">
                                    <img 
                                        src="{{ $usuario_seguidor->avatar_url ?? asset('images/default-avatar.png') }}" 
                                        alt="{{ $usuario_seguidor->username }}"
                                        class="w-16 h-16 rounded-full object-cover ring-2 ring-green-200"
                                    >
                                </a>
                                
                                <div class="flex-1">
                                    <a href="{{ route('perfil.show', $usuario_seguidor->username) }}" class="font-bold text-gray-800 text-lg hover:text-green-600">
                                        {{ $usuario_seguidor->username }}
                                    </a>
                                    <p class="text-gray-500 text-sm">{{ $usuario_seguidor->bio ?? 'Sem bio' }}</p>
                                    
                                    <!-- Stats -->
                                    <div class="flex gap-4 mt-2 text-xs text-gray-600">
                                        <span>📝 {{ $usuario_seguidor->posts()->count() ?? 0 }} posts</span>
                                        <span>👥 {{ $usuario_seguidor->total_seguidores ?? 0 }} seguidores</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botão de Ação -->
                            @auth
                                @if(Auth::id() !== $usuario_seguidor->id)
                                    @php
                                        $ja_sigo = \App\Models\UserFollower::where('seguidor_id', Auth::id())
                                            ->where('seguido_id', $usuario_seguidor->id)
                                            ->exists();
                                    @endphp
                                    
                                    @if($ja_sigo)
                                        <form action="{{ route('perfil.deixar_de_seguir', $usuario_seguidor->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-bold transition-all">
                                                ✓ Deixar de Seguir
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('perfil.seguir', $usuario_seguidor->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold transition-all">
                                                + Seguir
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="mt-8">
                {{ $seguidores->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-md p-12 text-center">
                <p class="text-6xl mb-4">👥</p>
                <p class="text-gray-500 text-xl font-bold mb-2">Sem seguidores ainda</p>
                <p class="text-gray-400 mb-6">{{ $usuario->username }} ainda não tem seguidores</p>
                <a href="{{ route('comunidade.feed') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-xl hover:shadow-lg font-bold transition-all">
                    Explorar Comunidade →
                </a>
            </div>
        @endif
    </div>
</div>
@endsection