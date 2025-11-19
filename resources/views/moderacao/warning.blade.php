<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>⚠️ Aviso de Moderação - Ambience RPG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-yellow-50 to-orange-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-8 text-white">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-white/20 rounded-full p-4">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-center mb-2">⚠️ Aviso de Moderação</h1>
            <p class="text-center text-white/90 text-lg">Sua conta foi temporariamente desativada</p>
        </div>

        <!-- Content -->
        <div class="p-8">
            <!-- Informações -->
            <div class="mb-8">
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 font-semibold">
                                Você recebeu um aviso por violação dos nossos Termos de Uso
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Motivo -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Motivo do Aviso:</h3>
                    <p class="text-gray-800 text-lg leading-relaxed">{{ $usuario->warning_motivo }}</p>
                </div>

                <!-- Detalhes -->
                <div class="grid grid-cols-2 gap-4 text-sm">
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
                        <p class="text-gray-500 mb-1">Data:</p>
                        <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($usuario->warning_data)->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Informações Importantes -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-blue-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Informações Importantes
                </h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Este é um <strong>aviso oficial</strong> da equipe de moderação.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Você deve <strong>reconhecer este aviso</strong> para reativar sua conta.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Novos avisos ou violações podem resultar em <strong>suspensão ou banimento</strong>.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Por favor, leia nossos <a href="/termos" class="text-blue-600 hover:underline font-semibold">Termos de Uso</a> para evitar futuras violações.</span>
                    </li>
                </ul>
            </div>

            <!-- Botão de Reativação -->
            <form method="POST" action="{{ route('moderacao.warning.reativar') }}" id="form-reativar">
                @csrf
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" id="aceitar-termos" class="mt-1 mr-3 w-5 h-5 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                        <span class="text-sm text-gray-700">
                            Li e compreendi o motivo deste aviso. Confirmo que estou ciente das consequências de futuras violações e concordo em seguir os Termos de Uso da plataforma.
                        </span>
                    </label>
                </div>

                <button type="submit" id="btn-reativar" disabled class="w-full bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 text-white font-bold py-4 px-6 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl">
                    Entendi e Reativar Minha Conta
                </button>
            </form>

            <!-- Link de Suporte -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Acha que isso foi um erro? 
                    <a href="{{ route('suporte.index') }}" class="text-orange-600 hover:text-orange-700 font-semibold hover:underline">
                        Entre em contato com o suporte
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        const checkbox = document.getElementById('aceitar-termos');
        const btnReativar = document.getElementById('btn-reativar');

        checkbox.addEventListener('change', function() {
            btnReativar.disabled = !this.checked;
        });

        document.getElementById('form-reativar').addEventListener('submit', function(e) {
            if (!checkbox.checked) {
                e.preventDefault();
                alert('Por favor, confirme que leu e compreendeu o aviso.');
            }
        });
    </script>
</body>
</html>