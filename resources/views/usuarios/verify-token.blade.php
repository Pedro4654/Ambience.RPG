@extends('layout.app')

@section('content')

<style>
/* Body com gradiente verde + imagem */
body {
    background: 
        linear-gradient(to bottom, rgba(5, 46, 22, 0.90), rgba(6, 78, 59, 0.85)),
        url('{{ asset("images/1.jpg") }}');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    min-height: 100vh;
}

/* Container */
.verify-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 2rem 1rem;
}

.verify-card {
    background: rgba(17, 24, 39, 0.95);
    backdrop-filter: blur(10px);
    padding: 3rem 2.5rem;
    border-radius: 1.5rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    width: 100%;
    max-width: 550px;
    text-align: center;
}

.verify-title {
    font-size: 1.75rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    color: #f9fafb;
}

.verify-icon {
    text-align: center;
    font-size: 3rem;
    margin-bottom: 1.5rem;
    color: #10b981;
}

.verify-subtitle {
    font-size: 0.95rem;
    color: #9ca3af;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.verify-subtitle strong {
    color: #10b981;
    font-weight: 600;
}

/* Caixas de código individuais */
.code-boxes {
    display: flex;
    justify-content: center;
    gap: 0.75rem;
    margin: 2rem 0;
}

.code-box {
    width: 60px;
    height: 70px;
    font-size: 2rem;
    font-weight: bold;
    text-align: center;
    border: 3px solid #374151;
    border-radius: 12px;
    background: #1f2937;
    color: #f9fafb;
    transition: all 0.2s;
    outline: none;
}

.code-box:focus {
    border-color: #10b981;
    background: #111827;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
    transform: scale(1.05);
}

.code-box.filled {
    border-color: #10b981;
    background: #064e3b;
    color: #10b981;
}

.code-box.error {
    border-color: #ef4444;
    background: #450a0a;
    animation: shake 0.3s;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Botões */
.btn-verify {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    transition: all 0.2s;
    margin-bottom: 1rem;
}

.btn-verify:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.btn-verify:active {
    transform: translateY(0);
}

.btn-resend {
    width: 100%;
    padding: 0.875rem 1rem;
    background: transparent;
    border: 2px solid #374151;
    color: #9ca3af;
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
    margin-bottom: 1rem;
}

.btn-resend:hover {
    border-color: #10b981;
    color: #10b981;
    background: #064e3b;
}

.back-link {
    display: inline-block;
    color: #10b981;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: color 0.2s;
}

.back-link:hover {
    color: #22c55e;
    text-decoration: underline;
}

/* Alertas */
.alert {
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    font-weight: 500;
}

.alert-success {
    background: #064e3b;
    border: 2px solid #10b981;
    color: #10b981;
}

.alert-danger {
    background: #450a0a;
    border: 2px solid #ef4444;
    color: #ef4444;
}

.alert ul {
    margin: 0;
    padding-left: 1.25rem;
    text-align: left;
}

.alert li {
    margin-bottom: 0.25rem;
}

/* Info box */
.info-box {
    background: #1f2937;
    border: 2px solid #374151;
    border-left: 4px solid #f59e0b;
    border-radius: 12px;
    padding: 1rem;
    text-align: left;
}

.info-box h6 {
    color: #f59e0b;
    font-size: 0.9rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.info-box small {
    display: block;
    color: #d1d5db;
    line-height: 1.6;
    font-size: 0.85rem;
}

.info-box strong {
    color: #f59e0b;
}

.divider {
    border: 0;
    border-top: 2px solid #374151;
    margin: 1.5rem 0;
}

/* Responsivo */
@media (max-width: 640px) {
    .verify-card {
        padding: 2rem 1.5rem;
    }
    
    .code-box {
        width: 50px;
        height: 60px;
        font-size: 1.75rem;
    }
    
    .code-boxes {
        gap: 0.5rem;
    }
}
</style>

<div class="verify-container">
    <div class="verify-card">
        <h1 class="verify-title">Estamos quase lá!</h1>
        <p class="verify-subtitle">
            Um código de verificação foi enviado para<br>
            <strong>{{ session('email') }}</strong><br>
            Insira o código para prosseguir.
        </p>

        @if (session('status'))
        <div class="alert alert-success">
            ✓ {{ session('status') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>✗ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('usuarios.verify.token') }}" id="tokenForm">
            @csrf
            <input type="hidden" name="email" value="{{ session('email') }}">
            <input type="hidden" id="token" name="token" value="">
            
            <div class="code-boxes">
                <input type="text" class="code-box" id="box1" maxlength="1" autocomplete="off">
                <input type="text" class="code-box" id="box2" maxlength="1" autocomplete="off">
                <input type="text" class="code-box" id="box3" maxlength="1" autocomplete="off">
                <input type="text" class="code-box" id="box4" maxlength="1" autocomplete="off">
                <input type="text" class="code-box" id="box5" maxlength="1" autocomplete="off">
                <input type="text" class="code-box" id="box6" maxlength="1" autocomplete="off">
            </div>

            <button type="submit" class="btn-verify" id="submitBtn">ENVIAR</button>
        </form>

        <form method="POST" action="{{ route('usuarios.resend.token') }}">
            @csrf
            <input type="hidden" name="email" value="{{ session('email') }}">
            <button type="submit" class="btn-resend">
                🔄 Reenviar Código
            </button>
        </form>

        <a href="{{ route('usuarios.forgot.form') }}" class="back-link">Voltar</a>

        <hr class="divider">
        
        <div class="info-box">
            <h6>⏰ Importante:</h6>
            <small>
                • O código expira em <strong>15 minutos</strong><br>
                • Máximo de <strong>5 tentativas por hora</strong><br>
                • Verifique também a pasta de spam
            </small>
        </div>
    </div>
</div>

<script>
const boxes = [
    document.getElementById('box1'),
    document.getElementById('box2'),
    document.getElementById('box3'),
    document.getElementById('box4'),
    document.getElementById('box5'),
    document.getElementById('box6')
];
const tokenInput = document.getElementById('token');
const form = document.getElementById('tokenForm');

boxes[0].focus();

boxes.forEach((box, index) => {
    box.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        
        if (this.value) {
            this.classList.add('filled');
            if (index < 5) {
                boxes[index + 1].focus();
            }
        } else {
            this.classList.remove('filled');
        }
        
        updateToken();
        
        if (index === 5 && this.value) {
            setTimeout(() => form.submit(), 300);
        }
    });
    
    box.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && !this.value && index > 0) {
            boxes[index - 1].focus();
        }
        
        if (e.key === 'ArrowLeft' && index > 0) {
            boxes[index - 1].focus();
        }
        if (e.key === 'ArrowRight' && index < 5) {
            boxes[index + 1].focus();
        }
    });
    
    box.addEventListener('paste', function(e) {
        e.preventDefault();
        const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
        
        for (let i = 0; i < pastedData.length && i < 6; i++) {
            boxes[i].value = pastedData[i];
            boxes[i].classList.add('filled');
        }
        
        updateToken();
        
        const lastIndex = Math.min(pastedData.length, 6) - 1;
        if (lastIndex >= 0) {
            boxes[lastIndex].focus();
        }
        
        if (pastedData.length === 6) {
            setTimeout(() => form.submit(), 300);
        }
    });
});

function updateToken() {
    const code = boxes.map(box => box.value).join('');
    tokenInput.value = code;
}

@if ($errors->any())
    boxes.forEach(box => {
        box.classList.add('error');
        setTimeout(() => box.classList.remove('error'), 500);
    });
    boxes[0].focus();
@endif

// ==================== REENVIAR CÓDIGO SEM SAIR DA TELA ====================
const resendForm = document.querySelector('form[action="{{ route('usuarios.resend.token') }}"]');
const resendBtn = resendForm.querySelector('button[type="submit"]');

resendForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Desabilitar botão
    resendBtn.disabled = true;
    const originalText = resendBtn.textContent;
    resendBtn.textContent = '⏳ Reenviando...';
    
    try {
        const formData = new FormData(this);
        
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            // Mostrar mensagem de sucesso
            showAlert('✓ Código reenviado com sucesso! Verifique seu email.', 'success');
            
            // Limpar as caixas
            boxes.forEach(box => {
                box.value = '';
                box.classList.remove('filled');
            });
            updateToken();
            boxes[0].focus();
            
        } else {
            // Mostrar mensagem de erro
            const errorMsg = data.message || 'Erro ao reenviar código. Tente novamente.';
            showAlert('✗ ' + errorMsg, 'danger');
        }
        
    } catch (error) {
        console.error('Erro:', error);
        showAlert('✗ Erro de conexão. Tente novamente.', 'danger');
    } finally {
        // Reabilitar botão após 3 segundos
        setTimeout(() => {
            resendBtn.disabled = false;
            resendBtn.textContent = originalText;
        }, 3000);
    }
});

function showAlert(message, type) {
    // Remove alertas anteriores
    const oldAlert = document.querySelector('.dynamic-alert');
    if (oldAlert) {
        oldAlert.remove();
    }
    
    // Cria novo alerta
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} dynamic-alert`;
    alert.textContent = message;
    alert.style.animation = 'slideIn 0.3s ease';
    
    // Insere antes das caixas de código
    const codeBoxes = document.querySelector('.code-boxes');
    codeBoxes.parentNode.insertBefore(alert, codeBoxes);
    
    // Remove após 5 segundos
    setTimeout(() => {
        alert.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => alert.remove(), 300);
    }, 5000);
}
</script>

<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideOut {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-10px);
    }
}

.dynamic-alert {
    animation: slideIn 0.3s ease;
}
</style>


@endsection
