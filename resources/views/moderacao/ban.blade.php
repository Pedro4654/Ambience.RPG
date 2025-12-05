<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conta Banida - Ambience RPG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #0a0f14;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-pink-600 p-8 text-white">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-white/20 rounded-full p-4">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-center mb-2">Conta Banida</h1>
            <p class="text-center text-white/90 text-lg">
                @if($usuario->ban_tipo === 'permanente')
                    Banimento Permanente
                @else
                    Banimento Temporário
                @endif
            </p>
        </div>

        <!-- Content -->
        <div class="p-8">
            <!-- Tipo de Ban -->
            @if($usuario->ban_tipo === 'permanente')
                <div class="bg-red-50 border-l-4 border-red-600 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-semibold">
                                Esta conta foi banida <strong>permanentemente</strong> da plataforma Ambience RPG
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-orange-700 font-semibold">
                                Esta conta foi banida temporariamente da plataforma Ambience RPG
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Motivo -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Motivo do Banimento:</h3>
                <p class="text-gray-800 text-lg leading-relaxed">{{ $usuario->ban_motivo }}</p>
            </div>

            <!-- Detalhes do Ban -->
            <div class="grid grid-cols-1 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 mb-1">Aplicado por:</p>
                    <p class="font-semibold text-gray-800">
                        {{ $moderador ? $moderador->username : 'Sistema' }}
                        @if($moderador)
                            <span class="text-xs px-2 py-1 rounded-full {{ $moderador->nivel_usuario === 'admin' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst($moderador->nivel_usuario) }}
                            </span>
                        @endif
                    </p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-500 mb-1">Data do Banimento:</p>
                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($usuario->ban_inicio)->format('d/m/Y H:i') }}</p>
                </div>

                @if($usuario->ban_tipo === 'temporario' && $usuario->ban_fim)
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <p class="text-blue-700 mb-1">Término do Banimento:</p>
                        <p class="font-bold text-blue-900 text-xl">{{ \Carbon\Carbon::parse($usuario->ban_fim)->format('d/m/Y H:i') }}</p>
                    </div>

                    @if($tempo_restante)
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-500 p-6 rounded-lg text-white text-center">
                            <p class="text-sm opacity-90 mb-2">Tempo Restante:</p>
                            <p class="text-4xl font-bold">
                                @if($tempo_restante->days > 0)
                                    {{ $tempo_restante->days }} {{ $tempo_restante->days === 1 ? 'dia' : 'dias' }}
                                @endif
                                {{ str_pad($tempo_restante->h, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($tempo_restante->i, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($tempo_restante->s, 2, '0', STR_PAD_LEFT) }}
                            </p>
                            <p class="text-xs opacity-75 mt-2">Dias : Horas : Minutos : Segundos</p>
                        </div>
                    @endif
                @else
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                        <svg class="w-12 h-12 text-red-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <p class="text-red-900 font-bold text-lg">Banimento Permanente</p>
                        <p class="text-red-700 text-sm mt-2">Esta conta não possui data de retorno</p>
                    </div>
                @endif
            </div>

            <!-- Informações -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    O que isso significa?
                </h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Você não pode acessar nenhuma área da plataforma durante o banimento.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Tentativas de criar novas contas podem resultar em <strong>banimento permanente</strong>.</span>
                    </li>
                    @if($usuario->ban_tipo === 'temporario')
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Após o término do período, você poderá acessar sua conta normalmente.</span>
                        </li>
                    @else
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Apenas um administrador pode remover um banimento permanente.</span>
                        </li>
                    @endif
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Futuros banimentos podem ser mais severos e duradouros.</span>
                    </li>
                </ul>
            </div>

            <!-- Botão de Logout -->
            <form method="POST" action="{{ route('usuarios.logout') }}">
                @csrf
                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl mb-4">
                    Sair da Conta
                </button>
            </form>

            <!-- Link de Suporte -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Acha que isso foi um erro? 
                    <a href="{{ route('suporte.index') }}" class="text-red-600 hover:text-red-700 font-semibold hover:underline">
                        Entre em contato com o suporte
                    </a>
                </p>
            </div>
        </div>
    </div>

    @if($usuario->ban_tipo === 'temporario' && $tempo_restante)
    <script>
        // Atualizar contador em tempo real
        setInterval(function() {
            location.reload();
        }, 60000); // Recarregar a cada 1 minuto
    </script>
    @endif
</body>
</html>