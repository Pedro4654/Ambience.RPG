<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Conta Excluída - Ambience RPG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0a0f14;
        }
    </style>
</head>
<body class=" min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-700 to-gray-900 p-8 text-white">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-white/20 rounded-full p-4">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-center mb-2">Conta Excluída</h1>
            <p class="text-center text-white/90 text-lg">Sua conta está em processo de exclusão permanente</p>
        </div>

        <!-- Content -->
        <div class="p-8">
            <!-- Alerta Principal -->
            <div class="bg-red-50 border-l-4 border-red-600 p-6 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-red-800 mb-2">Conta Marcada para Exclusão</h3>
                        <p class="text-sm text-red-700">
                            Esta conta foi marcada para exclusão permanente pela equipe de moderação devido a violações dos Termos de Uso.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contador de Tempo -->
            <div class="bg-gradient-to-r from-red-500 to-pink-500 p-8 rounded-lg text-white text-center mb-6 shadow-lg">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm opacity-90 mb-2">Tempo Restante até Exclusão Permanente:</p>
                <p class="text-5xl font-bold mb-2">{{ $dias_restantes }}</p>
                <p class="text-xl opacity-90">{{ $dias_restantes === 1 ? 'dia' : 'dias' }}</p>
                <div class="mt-4 pt-4 border-t border-white/20">
                    <p class="text-xs opacity-75">Data de Exclusão Final:</p>
                    <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($usuario->account_hard_delete_at)->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <!-- Motivo -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Motivo da Exclusão:</h3>
                <p class="text-gray-800 text-lg leading-relaxed">{{ $usuario->account_deleted_motivo }}</p>
            </div>

            <!-- Detalhes -->
            <div class="grid grid-cols-2 gap-4 mb-6">
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
                    <p class="text-gray-500 mb-1">Data da Exclusão:</p>
                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($usuario->account_deleted_at)->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <!-- O que acontecerá -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-orange-900 mb-4 flex items-center text-lg">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    O que acontecerá?
                </h3>
                
                <div class="space-y-4">
                    <!-- Fase 1: Agora -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-orange-200 rounded-full flex items-center justify-center font-bold text-orange-800">
                            1
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="font-semibold text-orange-900 mb-1">Período Atual ({{ $dias_restantes }} dias restantes)</h4>
                            <ul class="text-sm text-orange-800 space-y-1">
                                <li>• Sua conta está <strong>inacessível</strong></li>
                                <li>• Você não pode fazer login</li>
                                <li>• Seus dados ainda existem no sistema</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Fase 2: Após 30 dias -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-200 rounded-full flex items-center justify-center font-bold text-red-800">
                            2
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="font-semibold text-red-900 mb-1">Após 30 Dias</h4>
                            <ul class="text-sm text-red-800 space-y-1">
                                <li>• Conta será <strong>permanentemente excluída</strong></li>
                                <li>• Todos os seus dados serão deletados</li>
                                <li>• <strong>Não será possível recuperar</strong> a conta</li>
                                <li>• Avatar, posts e comentários serão removidos</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avisos Importantes -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-blue-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    ⚠️ Informações Importantes
                </h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Durante o período de 30 dias, sua conta permanece <strong>completamente inacessível</strong>.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Tentativas de criar novas contas podem resultar em <strong>banimento de IP</strong>.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Após a exclusão permanente, <strong>não há forma de recuperar</strong> os dados.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Apenas um administrador pode <strong>cancelar este processo</strong> antes dos 30 dias.</span>
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
            <div class="text-center bg-gradient-to-r from-purple-50 to-blue-50 p-6 rounded-lg">
                <svg class="w-12 h-12 mx-auto mb-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Acredita que isso foi um erro?</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Nossa equipe de suporte pode revisar seu caso e ajudar caso necessário.
                </p>
                <a href="{{ route('suporte.index') }}" class="inline-block bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200 shadow-lg hover:shadow-xl">
                    📧 Contatar Suporte
                </a>
            </div>
        </div>
    </div>
</body>
</html>