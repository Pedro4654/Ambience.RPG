<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UsuarioController extends Controller
{
    use AuthorizesRequests;
    // Exibir formulário de login
    public function loginForm()
    {
        return view('usuarios.login');
    }

    // Processar login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]
    );

$usuario = Usuario::where('email', $request->email)
                     ->where('status', 'ativo')
                     ->first();
        if ($usuario && Hash::check($request->password, $usuario->senha_hash)) {
            Auth::login($usuario);
            $usuario->update(['data_ultimo_login' => now()]);
            return redirect()->route('usuarios.index')->with('success', 'Login realizado com sucesso!');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas.']);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('usuarios.login')->with('success', 'Logout realizado com sucesso!');
    }

    // Listar usuários
    public function index()
    {
        $usuarios = Usuario::where('status', 'ativo') // ← AQUI: só mostra ativos
                      ->orderBy('data_criacao', 'desc')
                      ->paginate(15);
    
    return view('usuarios.index', compact('usuarios'));
    }

    // Exibir formulário de cadastro
    public function create()
    {
        return view('usuarios.create');
    }

    // Processar cadastro
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:usuarios',
            'nickname' => 'nullable|string|max:50',
            'email' => 'required|email|max:100|unique:usuarios',
            'password' => ['required', Password::min(8)->letters()->numbers()],
            'nome_completo' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'data_de_nascimento' => 'required|date|before:today',
        ]);

        $usuario = Usuario::create([
    'username' => $request->username,
    'nickname' => $request->nickname,
    'email' => $request->email,
    'senha_hash' => Hash::make($request->password),
    'nome_completo' => $request->nome_completo,
    'bio' => $request->bio,
    'data_de_nascimento' => $request->data_de_nascimento,
    'data_criacao' => now(),
]);

        Auth::login($usuario);

        return redirect()->route('usuarios.index')->with('success', 'Usuário cadastrado com sucesso!');
    }

    // Exibir usuário específico
    public function show(Usuario $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    // Exibir formulário de edição
    public function edit(Usuario $usuario)
    {
        $this->authorize('update', $usuario);
        return view('usuarios.edit', compact('usuario'));
    }

    // Processar atualização
    public function update(Request $request, Usuario $usuario)
    {
        $this->authorize('update', $usuario);

        $request->validate([
            'username' => 'required|string|max:50|unique:usuarios,username,' . $usuario->id,
            'nickname' => 'nullable|string|max:50',
            'email' => 'required|email|max:100|unique:usuarios,email,' . $usuario->id,
            'password' => ['nullable', Password::min(8)->letters()->numbers()],
            'nome_completo' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'data_de_nascimento' => 'required|date|before:today',
        ]);

        $data = $request->except(['password']);
        if ($request->filled('password')) {
            $data['senha_hash'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.show', $usuario)->with('success', 'Usuário atualizado com sucesso!');
    }

    // Excluir usuário
    public function destroy(Usuario $usuario)
    {
        $this->authorize('delete', $usuario);

        $usuario->update(['status' => 'inativo']);

        if (Auth::id() === $usuario->id) {
            Auth::logout();
            return redirect()->route('usuarios.login')->with('success', 'Conta desativada com sucesso!');
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuário removido com sucesso!');
    }
}