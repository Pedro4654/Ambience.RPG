@extends('layout.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        
        <!-- Header de Busca -->
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">🔍 Buscar Postagens</h1>
            
            <!-- Formulário de Busca Avançado -->
            <form action="{{ route('comunidade.buscar') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Campo de Busca -->
                    <div class="md:col-span-2">
                        <input 
                            type="text" 
                            name="q" 
                            value="{{ $termo ?? '' }}"
                            placeholder="Digite título ou conteúdo da postagem..."
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all"
                        >
                    </div>
                    
                    <!-- Filtro por Tipo -->
                    <div>
                        <select 
                            name="tipo" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all"
                        >
                            <option value="">Todos os tipos</option>
                            <option value="texto" {{ $tipo == 'texto' ? 'selected' : '' }}>📝 Texto</option>
                            <option value="imagem" {{ $tipo == 'imagem' ? 'selected' : '' }}>🖼️ Imagem</option>
                            <option value="video" {{ $tipo == 'video' ? 'selected' : '' }}>🎬 Vídeo</option>
                            <option value="modelo_3d" {{ $tipo == 'modelo_3d' ? 'selected' : '' }}>🎮 Modelo 3D</option>
                            <option value="ficha" {{ $tipo == 'ficha' ? 'selected' : '' }}>📋 Ficha RPG</option>
                            <option value="outros" {{ $tipo == 'outros' ? 'selected' : '' }}>📦 Outros</option>
                        </select>
                    </div>
                </div>
                
                <!-- Botões -->
                <div class="flex gap-3">
                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg font-bold transition-all"
                    >
                        🔍 Buscar
                    </button>
                    <a 
                        href="{{ route('comunidade.feed') }}" 
                        class="px-8 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-bold transition-all"
                    >
                        ← Voltar ao Feed
                    </a>
                </div>
            </form>
        </div>

        <!-- Resultados -->
        @if(isset($termo) && $termo !== '')
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-6">
                <p class="text-gray-800">
                    <span class="font-bold">{{ $posts->total() }}</span> resultado(s) encontrado(s) para:
                    <span class="font-bold text-blue-600">"{{ $termo }}"</span>
                    @if($tipo)
                        | Tipo: <span class="font-bold text-purple-600">{{ ucfirst(str_replace('_', ' ', $tipo)) }}</span>
                    @endif
                </p>
            </div>
        @endif

        <!-- Grid de Resultados -->
        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-2xl overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                        
                        <!-- Preview de Mídia -->
                        @if($post->arquivos->first())
                            @if($post->arquivos->first()->tipo === 'imagem')
                                <div class="relative h-48 overflow-hidden bg-gray-900">
                                    <img 
                                        src="{{ $post->arquivos->first()->url }}" 
                                        alt="{{ $post->titulo }}"
                                        class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                                    >
                                </div>
                            @elseif($post->arquivos->first()->tipo === 'video')
                                <div class="h-48 flex items-center justify-center bg-gradient-to-b from-gray-800 to-black">
                                    <span class="text-white text-6xl opacity-70">🎬</span>
                                </div>
                            @else
                                <div class="h-48 flex items-center justify-center bg-gradient-to-b from-purple-200 to-blue-300">
                                    <span class="text-6xl">📦</span>
                                </div>
                            @endif
                        @else
                            <div class="h-48 flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600">
                                <span class="text-white text-6xl">📝</span>
                            </div>
                        @endif

                        <!-- Conteúdo do Card -->
                        <div class="p-5">
                            <!-- Badge do Tipo -->
                            <span class="inline-block px-3 py-1 text-xs font-bold bg-blue-100 text-blue-700 rounded-full mb-3">
                                {{ ucfirst(str_replace('_', ' ', $post->tipo_conteudo)) }}
                            </span>
                            
                            <!-- Título -->
                            <h3 class="font-bold text-gray-800 text-lg mb-2 line-clamp-2 hover:text-blue-600 transition-colors">
                                {{ $post->titulo }}
                            </h3>
                            
                            <!-- Conteúdo -->
                            <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $post->conteudo }}</p>
                            
                            <!-- Footer -->
                            <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                <div class="flex gap-3 text-xs text-gray-500">
                                    <span>❤️ {{ $post->total_curtidas }}</span>
                                    <span>💬 {{ $post->total_comentarios }}</span>
                                </div>
                                <a 
                                    href="{{ route('comunidade.post.show', $post->slug) }}" 
                                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-xs font-bold rounded-lg hover:shadow-lg transition-all"
                                >
                                    Ver →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="mt-8">
                {{ $posts->appends(['q' => $termo, 'tipo' => $tipo])->links() }}
            </div>
        @else
            <!-- Nenhum resultado -->
            <div class="bg-white rounded-2xl shadow-md p-16 text-center">
                <p class="text-6xl mb-4">🔍</p>
                <p class="text-gray-500 text-xl font-bold mb-2">Nenhum resultado encontrado</p>
                @if(isset($termo))
                    <p class="text-gray-400 mb-6">Tente buscar por outros termos</p>
                    <a 
                        href="{{ route('comunidade.buscar') }}" 
                        class="inline-block px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg font-bold transition-all"
                    >
                        Nova Busca
                    </a>
                @else
                    <p class="text-gray-400">Digite algo para buscar</p>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection