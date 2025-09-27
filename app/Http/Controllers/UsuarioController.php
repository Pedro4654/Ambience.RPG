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

// ========== MÉTODOS PARA RECUPERAÇÃO DE SENHA ==========
    
    /**
     * Exibir formulário "Esqueci minha senha"
     */
    public function showForgotPasswordForm()
    {
        return view('usuarios.forgot-password');
    }

    /**
     * Enviar email de recuperação de senha
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:usuarios,email'
        ], [
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Digite um email válido.',
            'email.exists' => 'Este email não está cadastrado no sistema.'
        ]);

        $usuario = Usuario::where('email', $request->email)
                         ->where('status', 'ativo')
                         ->first();

        if (!$usuario) {
            return back()->withErrors(['email' => 'Usuário não encontrado ou inativo.']);
        }

        // Gerar token único
        $token = Str::random(60);
        $expiresAt = Carbon::now()->addHour(); // Token expira em 1 hora

        // Salvar token na tabela password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        // Atualizar campos no usuário
        $usuario->update([
            'password_reset_token' => $token,
            'password_reset_expires_at' => $expiresAt
        ]);

        // Enviar email
        try {
            $this->sendResetPasswordEmail($usuario, $token);
            
            Log::info('Email de recuperação enviado', [
                'email' => $request->email,
                'user_id' => $usuario->id
            ]);

            return back()->with('status', 'Link de recuperação enviado para seu email!');
            
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de recuperação', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);
            
            return back()->withErrors(['email' => 'Erro ao enviar email. Tente novamente.']);
        }
    }

    /**
     * Exibir formulário de nova senha
     */
    public function showResetPasswordForm(Request $request, $token)
    {
        // Validar se o token existe e não expirou
        $usuario = Usuario::where('password_reset_token', $token)
                         ->where('password_reset_expires_at', '>', Carbon::now())
                         ->where('status', 'ativo')
                         ->first();

        if (!$usuario) {
            return redirect()->route('usuarios.login')
                           ->withErrors(['error' => 'Token inválido ou expirado.']);
        }

        return view('usuarios.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Processar nova senha
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ], [
            'token.required' => 'Token é obrigatório.',
            'email.required' => 'Email é obrigatório.',
            'email.email' => 'Email deve ser válido.',
            'password.required' => 'Nova senha é obrigatória.',
            'password.confirmed' => 'Confirmação de senha não confere.',
            'password.min' => 'Senha deve ter no mínimo 6 caracteres.'
        ]);

        // Buscar usuário pelo token
        $usuario = Usuario::where('password_reset_token', $request->token)
                         ->where('email', $request->email)
                         ->where('password_reset_expires_at', '>', Carbon::now())
                         ->where('status', 'ativo')
                         ->first();

        if (!$usuario) {
            return back()->withErrors(['email' => 'Token inválido, expirado ou usuário não encontrado.']);
        }

        // Atualizar senha
        $usuario->update([
            'senha_hash' => Hash::make($request->password),
            'password_reset_token' => null,
            'password_reset_expires_at' => null
        ]);

        // Remover token da tabela password_reset_tokens
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        Log::info('Senha redefinida com sucesso', [
            'user_id' => $usuario->id,
            'email' => $usuario->email
        ]);

        return redirect()->route('usuarios.login')
                        ->with('success', 'Senha alterada com sucesso! Faça login com sua nova senha.');
    }

    /**
     * Enviar email de recuperação
     */
    private function sendResetPasswordEmail($usuario, $token)
    {
        $resetUrl = route('usuarios.reset.form', ['token' => $token, 'email' => $usuario->email]);
        
        $data = [
            'usuario' => $usuario,
            'resetUrl' => $resetUrl,
            'token' => $token
        ];

        Mail::send('emails.reset-password', $data, function($message) use ($usuario) {
            $message->to($usuario->email, $usuario->username)
                   ->subject('Recuperação de Senha - Ambience RPG');
        });
    }
}



