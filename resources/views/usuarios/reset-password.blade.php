@extends('layout.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>🔐 Redefinir Senha</h4>
                    <p class="mb-0 text-muted">Digite sua nova senha</p>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>❌ {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('usuarios.reset.password') }}">
                        @csrf
                        
                        <input type="hidden" name="token" value="{{ $token }}">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">📧 Email:</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="{{ $email ?? old('email') }}"
                                   required readonly
                                   style="background-color: #f8f9fa;">
                            <small class="text-muted">Email não pode ser alterado</small>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">🔒 Nova Senha:</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Digite sua nova senha (mínimo 6 caracteres)"
                                   required
                                   minlength="6">
                            <small class="text-muted">Mínimo 6 caracteres</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">🔒 Confirmar Nova Senha:</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Digite novamente sua nova senha"
                                   required
                                   minlength="6">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                ✅ Alterar Minha Senha
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('usuarios.login') }}" class="text-decoration-none">
                            ← Voltar para Login
                        </a>
                    </div>

                    <div class="alert alert-warning mt-3">
                        <small>
                            <strong>⚠️ Importante:</strong> Este link expira em 1 hora. 
                            Após alterar a senha, você será redirecionado para fazer login.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validar se senhas coincidem
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;
    
    if (password !== confirm) {
        this.setCustomValidity('Senhas não coincidem');
        this.style.borderColor = '#dc3545';
    } else {
        this.setCustomValidity('');
        this.style.borderColor = '#28a745';
    }
});
</script>
@endsection
