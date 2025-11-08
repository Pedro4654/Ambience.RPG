<!-- ============================================================ -->
<!-- VIEW MELHORADA: saved.blade.php -->
<!-- resources/views/comunidade/saved.blade.php -->
<!-- ============================================================ -->

@extends('layout.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-3xl shadow-lg p-8 mb-8 text-white">
            <h1 class="text-3xl font-bold mb-2">💾 Meus Posts Salvos</h1>
            <p class="text-yellow-100">{{ $saved_posts->total() }} postagens salvas</p>
        </div>

        <!-- Grid de Posts Salvos -->
        @if($saved_posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($saved_posts as $saved)
                    @php $post = $saved->post; @endphp
                    
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-2xl overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                        
                        <!-- Preview -->
                        @if($post->arquivos->first())
                            @if($post->arquivos->first()->tipo === 'imagem')
                                <div class="relative h-48 overflow-hidden bg-gray-900">
                                    <img 
                                        src="{{ $post->arquivos->first()->url }}" 
                                        alt="{{ $post->titulo }}"
                                        class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                                    >
                                    <!-- Badge de Salvo -->
                                    <div class="absolute top-3 right-3 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                        💾 Salvo
                                    </div>
                                </div>
                            @else
                                <div class="h-48 flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 relative">
                                    <span class="text-white text-6xl">📦</span>
                                    <div class="absolute top-3 right-3 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                        💾 Salvo
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="h-48 flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 relative">
                                <span class="text-white text-6xl">📝</span>
                                <div class="absolute top-3 right-3 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                    💾 Salvo
                                </div>
                            </div>
                        @endif

                        <!-- Conteúdo -->
                        <div class="p-5">
                            <span class="inline-block px-3 py-1 text-xs font-bold bg-purple-100 text-purple-700 rounded-full mb-3">
                                {{ ucfirst(str_replace('_', ' ', $post->tipo_conteudo)) }}
                            </span>
                            
                            <h3 class="font-bold text-gray-800 text-lg mb-2 line-clamp-2">
                                {{ $post->titulo }}
                            </h3>
                            
                            <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $post->conteudo }}</p>
                            
                            <!-- Footer -->
                            <div class="flex gap-2 pt-3 border-t border-gray-200">
                                <a 
                                    href="{{ route('comunidade.post.show', $post->slug) }}" 
                                    class="flex-1 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-bold rounded-lg hover:shadow-lg transition-all text-center"
                                >
                                    Ver Post
                                </a>
                                <form action="{{ route('comunidade.desalvar', $post->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="w-full py-2 bg-red-100 text-red-600 text-sm font-bold rounded-lg hover:bg-red-200 transition-all"
                                        onclick="return confirm('Remover dos salvos?')"
                                    >
                                        🗑️ Remover
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="mt-8">
                {{ $saved_posts->links() }}
            </div>
        @else
            <!-- Vazio -->
            <div class="bg-white rounded-2xl shadow-md p-16 text-center">
                <p class="text-6xl mb-4">💾</p>
                <p class="text-gray-500 text-xl font-bold mb-2">Nenhuma postagem salva ainda</p>
                <p class="text-gray-400 mb-6">Salve suas postagens favoritas para acessá-las rapidamente</p>
                <a 
                    href="{{ route('comunidade.feed') }}" 
                    class="inline-block px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg font-bold transition-all"
                >
                    Explorar Feed →
                </a>
            </div>
        @endif
    </div>
</div>
@endsection