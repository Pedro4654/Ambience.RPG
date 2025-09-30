@extends('layout.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">🔐 Esqueci Minha Senha</h4>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-envelope fa-3x text-muted"></i>
                        </div>
                        <p class="text-muted">
                            Digite seu email para receber um <strong>código de 6 dígitos</strong> 
                            para recuperar sua senha.
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

                    <form method="POST" action="{{ route('usuarios.forgot.send') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email:
                            </label>
                            <input type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="Digite seu email cadastrado"
                                   required
                                   autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> Enviar Código
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('usuarios.login') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left"></i> Voltar para Login
                        </a>
                    </div>

                    <hr class="my-4">
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Como funciona:</h6>
                        <small>
                            1. Digite seu email cadastrado<br>
                            2. Receba um código de 6 dígitos por email<br>
                            3. Digite o código na próxima tela<br>
                            4. Defina sua nova senha
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
