@extends('layout.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl shadow-lg p-8 mb-8 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-2">👥 Seguindo</h1>
                    <p class="text-blue-100">{{ $usuario->username }} segue {{ $seguindo->total() }} usuário(s)</p>
                </div>
                <a href="{{ route('perfil.show', $usuario->username) }}" class="px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-gray-100 font-bold transition-all">
                    ← Voltar ao Perfil
                </a>
            </div>
        </div>

        <!-- Lista de Usuários Seguindo -->
        @if($seguindo->count() > 0)
            <div class="space-y-4">
                @foreach($seguindo as $item)
                    @php $usuario_seguido = $item->seguido; @endphp
                    <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between">
                            
                            <!-- Informações do Usuário -->
                            <div class="flex items-center gap-4 flex-1">
                                <a href="{{ route('perfil.show', $usuario_seguido->username) }}">
                                    <img 
                                        src="{{ $usuario_seguido->avatar_url ?? asset('images/default-avatar.png') }}" 
                                        alt="{{ $usuario_seguido->username }}"
                                        class="w-16 h-16 rounded-full object-cover ring-2 ring-blue-200"
                                    >
                                </a>
                                
                                <div class="flex-1">
                                    <a href="{{ route('perfil.show', $usuario_seguido->username) }}" class="font-bold text-gray-800 text-lg hover:text-blue-600">
                                        {{ $usuario_seguido->username }}
                                    </a>
                                    <p class="text-gray-500 text-sm">{{ $usuario_seguido->bio ?? 'Sem bio' }}</p>
                                    
                                    <!-- Stats -->
                                    <div class="flex gap-4 mt-2 text-xs text-gray-600">
                                        <span>📝 {{ $usuario_seguido->posts()->count() ?? 0 }} posts</span>
                                        <span>👥 {{ $usuario_seguido->total_seguidores ?? 0 }} seguidores</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botão de Ação -->
                            @auth
                                @if(Auth::id() === $usuario->id)
                                    <form action="{{ route('perfil.deixar_de_seguir', $usuario_seguido->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-bold transition-all"
                                            onclick="return confirm('Deixar de seguir?')"
                                        >
                                            ✓ Deixar de Seguir
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="mt-8">
                {{ $seguindo->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-md p-12 text-center">
                <p class="text-6xl mb-4">👥</p>
                <p class="text-gray-500 text-xl font-bold mb-2">Ninguém sendo seguido ainda</p>
                <p class="text-gray-400 mb-6">{{ $usuario->username }} ainda não segue ninguém</p>
                <a href="{{ route('comunidade.feed') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg font-bold transition-all">
                    Explorar Comunidade →
                </a>
            </div>
        @endif
    </div>
</div>
@endsection