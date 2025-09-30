<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    // Exibir formulário de login
    public function loginForm()
    {
        return view('usuarios.login');
    }

    // Processar login (REMOVIDO update de data_ultimo_login)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('email', $request->email)
            ->where('status', 'ativo')
            ->first();

        if ($usuario && Hash::check($request->password, $usuario->senha_hash)) {
            Auth::login($usuario);
            
            Log::info('Login realizado com sucesso', ['user_id' => $usuario->id, 'email' => $usuario->email]);
            
            return redirect()->route('usuarios.index')->with('success', 'Login realizado com sucesso!');
        }

        Log::warning('Tentativa de login falhada', ['email' => $request->email]);
        return back()->withErrors(['email' => 'Credenciais inválidas.']);
    }

    // Logout
    public function logout(Request $request)
    {
        $userId = Auth::id();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Logout realizado', ['user_id' => $userId]);
        return redirect()->route('usuarios.login')->with('success', 'Logout realizado com sucesso!');
    }

    // Listar usuários
    public function index()
    {
        $usuarios = Usuario::where('status', 'ativo')
            ->orderBy('data_criacao', 'desc')
            ->paginate(15);

        Log::info('Listando usuários', ['total' => $usuarios->count()]);
        return view('usuarios.index', compact('usuarios'));
    }

    // Exibir formulário de cadastro
    public function create()
    {
        return view('usuarios.create');
    }

    // Processar cadastro (CORRIGIDO)
    public function store(Request $request)
    {
        Log::info('Iniciando cadastro de usuário', ['email' => $request->email]);
        
        $request->validate([
            'username' => 'required|string|max:50|unique:usuarios',
            'nickname' => 'nullable|string|max:50',
            'email' => 'required|email|max:100|unique:usuarios',
            'password' => ['required', Password::min(8)->letters()->numbers()],
            'bio' => 'nullable|string',
            'data_de_nascimento' => 'required|date|before:today',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $avatarPath = null;
        
        // Processar upload da foto de perfil
        if ($request->hasFile('avatar')) {
            Log::info('Processando upload de avatar no cadastro');
            $avatarPath = $this->uploadAvatar($request->file('avatar'));
        }

        $usuario = Usuario::create([
            'username' => $request->username,
            'nickname' => $request->nickname,
            'email' => $request->email,
            'senha_hash' => Hash::make($request->password),
            'bio' => $request->bio,
            'data_de_nascimento' => $request->data_de_nascimento,
            'avatar_url' => $avatarPath,
            'data_criacao' => now(),
            'status' => 'ativo',
        ]);

        Log::info('Usuário cadastrado com sucesso', ['user_id' => $usuario->id, 'avatar_path' => $avatarPath]);
        
        Auth::login($usuario);

        return redirect()->route('usuarios.index')->with('success', 'Usuário cadastrado com sucesso!');
    }

    // Exibir usuário específico
    public function show(Usuario $usuario)
    {
        Log::info('Exibindo perfil do usuário', ['user_id' => $usuario->id]);
        return view('usuarios.show', compact('usuario'));
    }

    // Exibir formulário de edição
    public function edit(Usuario $usuario)
    {
        $this->authorize('update', $usuario);
        Log::info('Exibindo formulário de edição', ['user_id' => $usuario->id]);
        return view('usuarios.edit', compact('usuario'));
    }

    // Processar atualização (CORRIGIDO)
    public function update(Request $request, Usuario $usuario)
    {
        $this->authorize('update', $usuario);
        Log::info('Iniciando atualização do usuário', ['user_id' => $usuario->id]);

        $request->validate([
            'username' => 'required|string|max:50|unique:usuarios,username,' . $usuario->id,
            'nickname' => 'nullable|string|max:50',
            'email' => 'required|email|max:100|unique:usuarios,email,' . $usuario->id,
            'password' => ['nullable', Password::min(8)->letters()->numbers()],
            'bio' => 'nullable|string',
            'data_de_nascimento' => 'required|date|before:today',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except(['password', 'avatar']);

        // Atualizar senha se fornecida
        if ($request->filled('password')) {
            $data['senha_hash'] = Hash::make($request->password);
            Log::info('Senha atualizada', ['user_id' => $usuario->id]);
        }

        // Processar upload de nova foto de perfil
        if ($request->hasFile('avatar')) {
            Log::info('Processando upload de novo avatar');
            
            // Deletar avatar antigo
            $deleted = $usuario->deleteOldAvatar();
            Log::info('Avatar antigo deletado', ['deleted' => $deleted, 'user_id' => $usuario->id]);
            
            // Upload novo avatar
            $data['avatar_url'] = $this->uploadAvatar($request->file('avatar'));
        }

        $usuario->update($data);
        Log::info('Usuário atualizado com sucesso', ['user_id' => $usuario->id]);

        return redirect()->route('usuarios.show', $usuario)->with('success', 'Usuário atualizado com sucesso!');
    }

    // Excluir usuário
    public function destroy(Usuario $usuario)
    {
        $this->authorize('delete', $usuario);
        
        Log::info('Iniciando desativação do usuário', ['user_id' => $usuario->id]);
        
        // Deletar avatar antes de inativar
        $deleted = $usuario->deleteOldAvatar();
        Log::info('Avatar deletado antes da desativação', ['deleted' => $deleted, 'user_id' => $usuario->id]);
        
        $usuario->update(['status' => 'inativo']);

        if (Auth::id() === $usuario->id) {
            Auth::logout();
            Log::info('Usuário deslogado após desativação própria', ['user_id' => $usuario->id]);
            return redirect()->route('usuarios.login')->with('success', 'Conta desativada com sucesso!');
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuário removido com sucesso!');
    }

    // Método privado para upload de avatar (CORRIGIDO COM NOMES ÚNICOS)
    private function uploadAvatar($file)
    {
        // Garantir que a pasta existe
        $avatarsPath = storage_path('app/public/avatars');
        if (!file_exists($avatarsPath)) {
            mkdir($avatarsPath, 0755, true);
            Log::info('Pasta avatars criada', ['path' => $avatarsPath]);
        }
        
        // CORRIGIDO: Gerar nome único sempre, mesmo que o arquivo seja igual
        $fileName = Str::random(40) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $destinationPath = $avatarsPath . '/' . $fileName;
        
        Log::info('Fazendo upload do avatar', [
            'original_name' => $file->getClientOriginalName(),
            'new_name' => $fileName,
            'destination' => $destinationPath,
            'file_size' => $file->getSize()
        ]);
        
        // Mover o arquivo diretamente para o local correto
        $moved = $file->move($avatarsPath, $fileName);
        
        if ($moved) {
            chmod($destinationPath, 0644);
            Log::info('Avatar uploaded com sucesso', ['path' => 'avatars/' . $fileName]);
            // Retornar apenas o caminho relativo para salvar no banco
            return 'avatars/' . $fileName;
        } else {
            Log::error('Falha no upload do avatar');
            return null;
        }
    }



  // Método para deletar avatar
public function deleteAvatar(Usuario $usuario)
{
    $this->authorize('update', $usuario);
    
    Log::info('Iniciando deleção manual do avatar', ['user_id' => $usuario->id]);
    
    $deleted = $usuario->deleteOldAvatar();
    $usuario->update(['avatar_url' => null]);
    
    Log::info('Avatar removido manualmente', ['deleted' => $deleted, 'user_id' => $usuario->id]);
    
    return back()->with('success', 'Foto de perfil removida com sucesso!');
}

    // ========== MÉTODOS PARA RECUPERAÇÃO DE SENHA COM TOKEN DE 6 DÍGITOS ==========
    
    /**
     * Exibir formulário "Esqueci minha senha"
     */
    public function showForgotPasswordForm()
    {
        return view('usuarios.forgot-password');
    }

    /**
     * Enviar email com token de 6 dígitos
     */
    public function sendResetToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:usuarios,email'
        ], [
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Digite um email válido.',
            'email.exists' => 'Este email não está cadastrado no sistema.'
        ]);

        // Verificar se email está bloqueado temporariamente
        if (Usuario::isEmailBlocked($request->email)) {
            $usuario = Usuario::where('email', $request->email)->first();
            $minutesRemaining = $usuario->getBlockTimeRemaining();
            
            return back()->withErrors([
                'email' => "Muitas tentativas. Tente novamente em {$minutesRemaining} minutos."
            ]);
        }

        $usuario = Usuario::where('email', $request->email)
                         ->where('status', 'ativo')
                         ->first();

        if (!$usuario) {
            return back()->withErrors(['email' => 'Usuário não encontrado ou inativo.']);
        }

        // Gerar token de 6 dígitos
        $token = $usuario->setResetToken();

        // Enviar email com token
        try {
            $this->sendResetTokenEmail($usuario, $token);
            
            Log::info('Token de recuperação enviado', [
                'email' => $request->email,
                'user_id' => $usuario->id,
                'token_expires_at' => $usuario->reset_token_expires_at
            ]);

            return redirect()->route('usuarios.verify.token.form')
                           ->with('email', $request->email)
                           ->with('status', 'Token de 6 dígitos enviado para seu email!');
            
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email com token', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);
            
            return back()->withErrors(['email' => 'Erro ao enviar email. Tente novamente.']);
        }
    }

    /**
     * Exibir formulário de verificação do token
     */
    public function showVerifyTokenForm()
    {
        if (!session('email')) {
            return redirect()->route('usuarios.forgot.form')
                           ->withErrors(['error' => 'Sessão expirada. Solicite um novo token.']);
        }

        return view('usuarios.verify-token');
    }

    /**
     * Verificar token de 6 dígitos
     */
    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => 'required|digits:6',
            'email' => 'required|email'
        ], [
            'token.required' => 'Digite o código de 6 dígitos.',
            'token.digits' => 'O código deve ter exatamente 6 dígitos.',
            'email.required' => 'Email é obrigatório.'
        ]);

        $usuario = Usuario::where('email', $request->email)
                         ->where('status', 'ativo')
                         ->first();

        if (!$usuario) {
            return back()->withErrors(['token' => 'Usuário não encontrado.']);
        }

        // Verificar se ainda pode tentar
        if (!$usuario->canAttemptReset()) {
            $minutesRemaining = $usuario->getBlockTimeRemaining();
            return back()->withErrors([
                'token' => "Muitas tentativas incorretas. Tente novamente em {$minutesRemaining} minutos."
            ]);
        }

        // Verificar se o token está correto e válido
        if (!$usuario->isValidResetToken($request->token)) {
            $usuario->incrementResetAttempts();
            
            Log::warning('Token incorreto ou expirado', [
                'email' => $request->email,
                'token_provided' => $request->token,
                'attempts' => $usuario->reset_attempts + 1
            ]);

            return back()->withErrors(['token' => 'Código incorreto ou expirado.'])
                        ->with('email', $request->email);
        }

        // Token válido - redirecionar para definir nova senha
        Log::info('Token verificado com sucesso', [
            'email' => $request->email,
            'user_id' => $usuario->id
        ]);

        return redirect()->route('usuarios.reset.password.form')
                       ->with('verified_email', $request->email)
                       ->with('verified_token', $request->token);
    }

    /**
     * Exibir formulário de nova senha
     */
    public function showResetPasswordForm()
    {
        if (!session('verified_email') || !session('verified_token')) {
            return redirect()->route('usuarios.forgot.form')
                           ->withErrors(['error' => 'Sessão expirada. Comece o processo novamente.']);
        }

        return view('usuarios.reset-password');
    }

    /**
     * Processar nova senha
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|digits:6',
            'password' => 'required|confirmed|min:6',
        ], [
            'password.required' => 'Nova senha é obrigatória.',
            'password.confirmed' => 'Confirmação de senha não confere.',
            'password.min' => 'Senha deve ter no mínimo 6 caracteres.'
        ]);

        $usuario = Usuario::where('email', $request->email)
                         ->where('status', 'ativo')
                         ->first();

        if (!$usuario) {
            return back()->withErrors(['password' => 'Usuário não encontrado.']);
        }

        // Verificar token uma última vez
        if (!$usuario->isValidResetToken($request->token)) {
            return redirect()->route('usuarios.forgot.form')
                           ->withErrors(['error' => 'Token inválido. Solicite um novo.']);
        }

        // Atualizar senha e limpar dados de reset
        $usuario->update(['senha_hash' => Hash::make($request->password)]);
        $usuario->clearResetData();

        Log::info('Senha redefinida com sucesso', [
            'user_id' => $usuario->id,
            'email' => $usuario->email
        ]);

        // Limpar sessão
        session()->forget(['verified_email', 'verified_token', 'email']);

        return redirect()->route('usuarios.login')
                        ->with('success', 'Senha alterada com sucesso! Faça login com sua nova senha.');
    }

    /**
     * Reenviar token (se expirou)
     */
    public function resendToken(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $usuario = Usuario::where('email', $request->email)
                         ->where('status', 'ativo')
                         ->first();

        if (!$usuario) {
            return back()->withErrors(['email' => 'Usuário não encontrado.']);
        }

        // Verificar se não está bloqueado
        if (!$usuario->canAttemptReset()) {
            $minutesRemaining = $usuario->getBlockTimeRemaining();
            return back()->withErrors([
                'email' => "Aguarde {$minutesRemaining} minutos para solicitar novo token."
            ]);
        }

        // Gerar novo token
        $token = $usuario->setResetToken();

        try {
            $this->sendResetTokenEmail($usuario, $token);
            
            Log::info('Token reenviado', [
                'email' => $request->email,
                'user_id' => $usuario->id
            ]);

            return back()->with('status', 'Novo código enviado para seu email!');
            
        } catch (\Exception $e) {
            Log::error('Erro ao reenviar token', ['error' => $e->getMessage()]);
            return back()->withErrors(['email' => 'Erro ao enviar email.']);
        }
    }

    /**
     * Enviar email com token de 6 dígitos
     */
    private function sendResetTokenEmail($usuario, $token)
    {
        $data = [
            'usuario' => $usuario,
            'token' => $token,
            'expires_at' => $usuario->reset_token_expires_at
        ];

        Mail::send('emails.reset-token', $data, function($message) use ($usuario) {
            $message->to($usuario->email, $usuario->username)
                   ->subject('🔐 Código de Recuperação - Ambience RPG');
        });
    }




}