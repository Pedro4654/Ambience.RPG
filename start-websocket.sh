#!/bin/bash
# start-websocket.sh

echo "🚀 Iniciando sistema WebSocket completo..."

# Parar processos existentes
echo "🛑 Parando processos anteriores..."
pkill -f "reverb:start" || true
pkill -f "queue:work" || true

# Limpar cache
echo "🧹 Limpando cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Migrar banco de dados
echo "📊 Executando migrações..."
php artisan migrate

# Instalar/atualizar dependências JS
echo "📦 Instalando dependências JavaScript..."
npm install

# Build dos assets
echo "🏗️ Compilando assets..."
npm run build

# Iniciar Reverb WebSocket Server
echo "🌐 Iniciando servidor WebSocket Reverb..."
php artisan reverb:start --host=0.0.0.0 --port=8080 &

# Iniciar worker de filas
echo "⚡ Iniciando worker de filas..."
php artisan queue:work --daemon &

# Iniciar scheduler (apenas em produção)
if [ "$APP_ENV" = "production" ]; then
    echo "⏰ Iniciando scheduler..."
    php artisan schedule:run &
fi

echo "✅ Sistema WebSocket iniciado com sucesso!"
echo "📡 WebSocket Server: http://localhost:8080"
echo "🌐 Aplicação: http://localhost:8000"

# Manter script rodando
wait
