<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dispositivo Bloqueado - Ambience RPG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0a0f14;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-800 to-red-800 p-8 text-white">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-white/20 rounded-full p-4">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-center mb-2">🔒 Dispositivo Bloqueado</h1>
            <p class="text-center text-white/90 text-lg">Acesso Negado - IP/Máquina Banida</p>
        </div>

        <!-- Content -->
        <div class="p-8">
            <!-- Alerta Principal -->
            <div class="bg-red-50 border-l-4 border-red-600 p-6 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-red-800 mb-2">Dispositivo Banido Permanentemente</h3>
                        <p class="text-sm text-red-700">
                            Este dispositivo foi identificado como origem de violações graves dos Termos de Uso da plataforma Ambience RPG e foi permanentemente bloqueado.
                        </p>
                    </div>
                </div>
            </div>

            <!-- O que isso significa -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center text-lg">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    O que isso significa?
                </h3>
                <ul class="space-y-3 text-sm text-gray-700">
                    <li class="flex items-start">
                        <span class="text-red-500 font-bold mr-3">✗</span>
                        <span>Este dispositivo/máquina está <strong>permanentemente bloqueado</strong> de acessar a plataforma.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-500 font-bold mr-3">✗</span>
                        <span>Contas criadas neste dispositivo <strong>não podem ser acessadas</strong>.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-500 font-bold mr-3">✗</span>
                        <span>Você <strong>não pode criar novas contas</strong> a partir deste dispositivo.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-red-500 font-bold mr-3">✗</span>
                        <span>Tentativas de burlar esta restrição podem resultar em <strong>ações legais</strong>.</span>
                    </li>
                </ul>
            </div>

            <!-- Informações Técnicas -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-blue-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414zM11 12a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    Informações Técnicas
                </h3>
                <div class="space-y-2 text-sm text-blue-800">
                    <p><strong>IP:</strong> {{ request()->ip() }}</p>
                    <p><strong>Identificador:</strong> <span class="font-mono text-xs bg-blue-100 px-2 py-1 rounded">{{ substr(hash('sha256', request()->ip() . request()->userAgent()), 0, 16) }}...</span></p>
                    <p class="text-xs opacity-75 mt-3">Este bloqueio é baseado em fingerprint único do dispositivo, incluindo IP, navegador e outras características técnicas.</p>
                </div>
            </div>

            <!-- Avisos Importantes -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 mb-6">
                <h3 class="font-semibold text-yellow-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    ⚠️ Avisos Importantes
                </h3>
                <ul class="space-y-2 text-sm text-yellow-800">
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Contas acessadas <strong>de outros dispositivos não são afetadas</strong> por este bloqueio.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Se você possui uma conta que <strong>não foi criada neste dispositivo</strong>, pode acessá-la de outro lugar.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>Este bloqueio <strong>não afeta membros da staff</strong> (moderadores e administradores).</span>
                    </li>
                </ul>
            </div>

            <!-- Possíveis Soluções -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-purple-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                    </svg>
                    O que fazer?
                </h3>
                <div class="space-y-3 text-sm text-purple-800">
                    <div class="flex items-start">
                        <span class="bg-purple-200 rounded-full w-6 h-6 flex items-center justify-center font-bold mr-3 flex-shrink-0">1</span>
                        <p>Se você acredita que este bloqueio foi um erro, <strong>entre em contato com o suporte</strong> usando outro dispositivo.</p>
                    </div>
                    <div class="flex items-start">
                        <span class="bg-purple-200 rounded-full w-6 h-6 flex items-center justify-center font-bold mr-3 flex-shrink-0">2</span>
                        <p>Se você possui uma conta que <strong>não foi criada neste dispositivo</strong>, acesse-a de outro computador ou dispositivo móvel.</p>
                    </div>
                    <div class="flex items-start">
                        <span class="bg-purple-200 rounded-full w-6 h-6 flex items-center justify-center font-bold mr-3 flex-shrink-0">3</span>
                        <p>Revise nossos <strong>Termos de Uso</strong> para entender melhor as políticas da plataforma.</p>
                    </div>
                </div>
            </div>

            <!-- Link de Suporte -->
            <div class="text-center bg-gradient-to-r from-indigo-500 to-purple-600 p-6 rounded-lg text-white">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <h3>🛡️ Você pode solicitar revisão do banimento</h3>
    <p>Se você acredita que o banimento foi injusto, clique no botão abaixo para enviar um recurso:</p>
    <a href="{{ route('ip-ban.recurso.form') }}" class="btn btn-primary">
        Solicitar Revisão do Banimento
    </a>
            </div>
        </div>
    </div>
</body>
</html>