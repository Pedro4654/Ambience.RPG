<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convite Inválido - Ambience RPG</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .error-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            padding: 50px 40px;
            text-align: center;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 20px;
            animation: bounce 1s ease-in-out;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .error-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 12px;
        }

        .error-message {
            color: #6c757d;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .error-details {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 30px;
            font-size: 0.9rem;
            color: #495057;
        }

        .btn-back {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5);
            color: white;
        }

        @media (max-width: 576px) {
            .error-card {
                padding: 40px 25px;
            }

            .error-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>

        <h1 class="error-title">Convite Inválido</h1>

        <p class="error-message">
            {{ $mensagem ?? 'Este link de convite não é válido ou expirou.' }}
        </p>

        @if(isset($motivo))
            <div class="error-details">
                <strong>Motivo:</strong> {{ ucfirst($motivo) }}
            </div>
        @endif

        <a href="{{ route('salas.index') }}" class="btn-back">
            <i class="fas fa-home me-2"></i>
            Voltar para Salas
        </a>

        @if(isset($sala))
            <div class="mt-3">
                <a href="{{ route('salas.show', ['id' => $sala->id]) }}" class="text-decoration-none">
                    <small class="text-muted">Ver sala: {{ $sala->nome }}</small>
                </a>
            </div>
        @endif
    </div>
</body>
</html>