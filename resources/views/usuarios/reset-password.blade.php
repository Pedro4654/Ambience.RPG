@extends('layout.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">🔐 Definir Nova Senha</h4>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-key fa-3x text-success"></i>
                        </div>
                        <p class="text-muted">
                            Código verificado! Agora defina sua nova senha para:<br>
                            <strong class="text-success">{{ session('verified_email') }}</strong>
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-triangle"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('usuarios.reset.password') }}" id="resetForm">
                        @csrf
                        
                        <input type="hidden" name="email" value="{{ session('verified_email') }}">
                        <input type="hidden" name="token" value="{{ session('verified_token') }}">
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Nova Senha:
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Digite sua nova senha (mínimo 6 caracteres)"
                                   minlength="6"
                                   required
                                   autocomplete="new-password">
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Mínimo 6 caracteres
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock"></i> Confirmar Nova Senha:
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Digite novamente sua nova senha"
                                   minlength="6"
                                   required
                                   autocomplete="new-password">
                            <div id="password-match-feedback" class="form-text"></div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-check"></i> Alterar Senha
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('usuarios.login') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left"></i> Voltar para Login
                        </a>
                    </div>

                    <hr class="my-4">
                    
                    <div class="alert alert-success">
                        <h6><i class="fas fa-shield-alt"></i> Segurança:</h6>
                        <small>
                            • Use uma senha forte e única<br>
                            • Não compartilhe sua senha com ninguém<br>
                            • Considere usar letras, números e símbolos
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validação em tempo real da confirmação de senha
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    const feedback = document.getElementById('password-match-feedback');
    const submitBtn = document.getElementById('submitBtn');
    
    if (confirmation === '') {
        feedback.textContent = '';
        feedback.className = 'form-text';
        return;
    }
    
    if (password === confirmation) {
        feedback.textContent = '✓ Senhas coincidem';
        feedback.className = 'form-text text-success';
        submitBtn.disabled = false;
    } else {
        feedback.textContent = '✗ Senhas não coincidem';
        feedback.className = 'form-text text-danger';
        submitBtn.disabled = true;
    }
});

// Verificação de força da senha
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const feedback = document.getElementById('password-match-feedback');
    
    if (password.length >= 6) {
        this.classList.add('is-valid');
        this.classList.remove('is-invalid');
    } else {
        this.classList.add('is-invalid');
        this.classList.remove('is-valid');
    }
});
</script>
@endsection
