@extends('layout.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">🔢 Verificar Código</h4>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-shield-alt fa-3x text-warning"></i>
                        </div>
                        <p class="text-muted">
                            Digite o <strong>código de 6 dígitos</strong> que enviamos para:<br>
                            <strong class="text-primary">{{ session('email') }}</strong>
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-triangle"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('usuarios.verify.token') }}" id="tokenForm">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') }}">
                        
                        <div class="mb-4">
                            <label for="token" class="form-label text-center d-block">
                                <strong>Código de 6 dígitos:</strong>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg text-center @error('token') is-invalid @enderror" 
                                   id="token" 
                                   name="token" 
                                   value="{{ old('token') }}"
                                   placeholder="123456"
                                   maxlength="6"
                                   pattern="[0-9]{6}"
                                   style="font-size: 2rem; letter-spacing: 0.5rem; font-weight: bold;"
                                   required
                                   autocomplete="off">
                            @error('token')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-check"></i> Verificar Código
                            </button>
                        </div>
                    </form>

                    <div class="text-center mb-3">
                        <form method="POST" action="{{ route('usuarios.resend.token') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('email') }}">
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fas fa-redo"></i> Reenviar Código
                            </button>
                        </form>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('usuarios.forgot.form') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>

                    <hr class="my-4">
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-clock"></i> Importante:</h6>
                        <small>
                            • O código expira em <strong>15 minutos</strong><br>
                            • Máximo de <strong>5 tentativas por hora</strong><br>
                            • Verifique também a pasta de spam
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-focus no campo de token e permitir apenas números
document.getElementById('token').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
    
    // Auto-submit quando completar 6 dígitos
    if (this.value.length === 6) {
        document.getElementById('tokenForm').submit();
    }
});

// Focus automático
document.getElementById('token').focus();
</script>
@endsection
