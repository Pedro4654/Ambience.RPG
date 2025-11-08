<!-- VIEW 8: components/comment-item.blade.php -->
<!-- resources/views/comunidade/components/comment-item.blade.php -->

<div class="bg-gray-50 rounded-lg p-4">
    <div class="flex gap-3">
        <!-- Avatar -->
        <a href="{{ route('perfil.show', $comentario->autor->username) }}" class="flex-shrink-0">
            <img 
                src="{{ $comentario->autor->avatar_url ?? asset('images/default-avatar.png') }}" 
                alt="{{ $comentario->autor->username }}"
                class="w-8 h-8 rounded-full object-cover"
            >
        </a>

        <!-- Conteúdo -->
        <div class="flex-1">
            <a href="{{ route('perfil.show', $comentario->autor->username) }}" class="font-semibold text-gray-800 hover:text-blue-600">
                {{ $comentario->autor->username }}
            </a>
            <p class="text-gray-500 text-xs">{{ $comentario->created_at->diffForHumans() }}</p>
            <p class="text-gray-700 mt-2">{{ $comentario->conteudo }}</p>

            <!-- Respostas -->
            @if($comentario->respostas->count() > 0)
                <div class="mt-3 ml-4 space-y-2 border-l-2 border-gray-300 pl-3">
                    @foreach($comentario->respostas as $resposta)
                        @include('comunidade.components.comment-item', ['comentario' => $resposta])
                    @endforeach
                </div>
            @endif

            <!-- Ações -->
            <div class="flex gap-2 mt-3">
                <button class="text-xs text-gray-600 hover:text-blue-600" onclick="replyTo({{ $comentario->id }})">
                    💬 Responder
                </button>
                @if(Auth::check() && Auth::id() === $comentario->usuario_id)
                    <form action="{{ route('comunidade.comentario.destroy', $comentario->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 hover:text-red-800" onclick="return confirm('Tem certeza?')">
                            🗑️ Deletar
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>