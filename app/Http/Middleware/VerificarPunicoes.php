<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\DeviceFingerprint;

class VerificarPunicoes
{
    /**
     * Rotas que podem ser acessadas mesmo com punição ativa
     * IMPORTANTE: Estas rotas são as ÚNICAS que usuários punidos podem acessar
     */
    protected $rotasPermitidas = [
        // Rotas de moderação (telas de punição)
        'moderacao.warning.show',
        'moderacao.warning.reativar',
        'moderacao.ban.show',
        'moderacao.ip-ban.show',
        'moderacao.account-deleted.show',
        
        // Logout sempre permitido
        'usuarios.logout',
        
        // Suporte - usuário punido pode criar ticket
        'suporte.index',
        'suporte.create',
        'suporte.store',
        'suporte.show',
        'suporte.responder'
    ];

    /**
     * Handle an incoming request.
     */
    /**
 * Handle an incoming request.
 */
public function handle($request, Closure $next)
{
    // ------------------------------------------------------------------
    // 1) Whitelist de rotas que devem ser acessíveis mesmo com punição
    //    - Mantemos a lista de propriedade ($this->rotasPermitidas)
    //    - Também acrescentamos rotas/paths extras que frequentemente causam loop
    // ------------------------------------------------------------------
    $permitidas = array_merge(
    $this->rotasPermitidas,
    [
        'ip-ban.recurso.*',        // <<<<<<<<<<<<<< AQUI FUNCIONA DE VERDADE
        'public.ip_ban',
        'moderacao.ip-ban.show',
        'moderacao.ban.show',
        'home',
        'logout',
        'assets.*',
        'api.*',
    ]
);

// Se estiver na rota de recurso, permitir IMEDIATAMENTE
if ($request->routeIs('ip-ban.recurso.*')) {
    return $next($request);
}

// Permitir por path (para garantir que NUNCA bloqueie)
if ($request->is('ip-ban/recurso*')) {
    return $next($request);
}

    // Se a rota atual for nula (ex: assets sem route name), tratamos pelo path abaixo
    $route = $request->route();
    $routeName = $route ? $route->getName() : null;

    // Se a rota atual bater com qualquer padrão da whitelist, deixa passar
    if ($routeName) {
        foreach ($permitidas as $pattern) {
            // routeIs suporta curingas tipo 'suporte.*'
            if ($request->routeIs($pattern)) {
                return $next($request);
            }
        }
    }

    // Permitir por path também (cobre casos sem nome de rota)
    if (
        $request->is('logout') ||
        $request->is('suporte*') ||
        $request->is('ban-ip') ||
        $request->is('moderacao/ban*') ||
        $request->is('moderacao/ip-ban*') ||
        $request->is('assets/*') ||
        $request->is('api/*')
    ) {
        return $next($request);
    }

    // ------------------------------------------------------------------
    // 2) Verificar IP BAN primeiro (aplica-se também a guests)
    //    - verificarIpBan() cuidará do logout forçado quando necessário
    //    - evitamos redirecionamento em loop permitindo a rota de ban/ip-ban
    // ------------------------------------------------------------------
    if ($this->verificarIpBan($request)) {
        // Se já estamos na rota de IP ban ou logout, permitir (evita loop)
        if ($request->routeIs('moderacao.ip-ban.show') || $request->routeIs('public.ip_ban') || $request->routeIs('usuarios.logout')) {
            return $next($request);
        }

        // Caso guest (ou redirecionamento inicial), enviar para rota pública de IP ban
        // Preferimos route pública para evitar exigir auth e criar loop login->ban->login
        if (!Auth::check()) {
            return redirect()->route('public.ip_ban');
        }

        // Usuário autenticado: já foi feito logout forçado em verificarIpBan quando necessário,
        // então redirecionamos para a rota pública também (ou para a view de ip-ban interna, se preferir)
        return redirect()->route('public.ip_ban');
    }

    // ------------------------------------------------------------------
    // 3) Se não estiver autenticado, segue normalmente (não há mais checagens)
    // ------------------------------------------------------------------
    if (!Auth::check()) {
        return $next($request);
    }

    $usuario = Auth::user();

    // ------------------------------------------------------------------
    // 4) Staff nunca é afetado por punições (early return)
    // ------------------------------------------------------------------
    if (method_exists($usuario, 'isStaff') && $usuario->isStaff()) {
        return $next($request);
    }

    // ------------------------------------------------------------------
    // 5) Se estiver tentando acessar uma rota permitida, permitir.
    //    Porém, se o usuário deveria estar em outra tela de punição,
    //    redirecionamos para a punição correta.
    // ------------------------------------------------------------------
    foreach ($this->rotasPermitidas as $rotaPermitida) {
        if ($request->routeIs($rotaPermitida)) {
            if ($this->deveMostrarTelaEspecifica($usuario, $request)) {
                return $this->redirecionarParaPunicaoCorreta($usuario);
            }
            return $next($request);
        }
    }

    // ------------------------------------------------------------------
    // 6) Verificar punições na ordem de prioridade
    //    - account_deleted (hard delete depois de período)
    //    - ban (temporário / permanente)
    //    - warning
    // ------------------------------------------------------------------

    // 6.1 Account Deleted (prioridade máxima)
    if ($usuario->account_deleted_at) {
        return $this->handleAccountDeleted($usuario);
    }

    // 6.2 Ban (temporário ou permanente)
    if ($usuario->ban_tipo) {
        // Se tem ban, verificar se está na tela de ban; se não, redirecionar
        // O método handleBan trata expiração e remoção de ban temporário
        return $this->handleBan($usuario);
    }

    // 6.3 Warning ativo
    if ($usuario->warning_ativo) {
        // Se está tentando acessar outra coisa que não a tela de warning, será redirecionado pelo bloco acima
        return redirect()->route('moderacao.warning.show');
    }

    // ------------------------------------------------------------------
    // 7) Se chegou aqui, não há punição ativa. Deixa passar.
    // ------------------------------------------------------------------
    return $next($request);
}

    /**
     * Verificar se o dispositivo está banido (IP Ban)
     * REGRA: Apenas contas CRIADAS no dispositivo banido são afetadas
     * Staff NUNCA é afetado
     */
    protected function verificarIpBan(Request $request): bool
    {
        // Se não está autenticado, verifica se pode criar conta
        if (!Auth::check()) {
            $fingerprint = DeviceFingerprint::generateFingerprint($request);
            
            // Verificar se este dispositivo tem alguma conta banida que foi criada nele
            $dispositivoBanido = DeviceFingerprint::where('fingerprint', $fingerprint)
                ->where('conta_criada_neste_dispositivo', true)
                ->whereHas('usuario', function ($query) {
                    $query->where('ip_ban_ativo', true);
                })
                ->exists();
            
            return $dispositivoBanido;
        }

        $usuario = Auth::user();

        // Staff NUNCA é afetado por IP ban
        if ($usuario->isStaff()) {
            return false;
        }

        $fingerprint = DeviceFingerprint::generateFingerprint($request);

        // Verificar se este dispositivo está banido
        $deviceBanido = DeviceFingerprint::where('fingerprint', $fingerprint)
            ->where('conta_criada_neste_dispositivo', true)
            ->whereHas('usuario', function ($query) {
                $query->where('ip_ban_ativo', true);
            })
            ->exists();

        if (!$deviceBanido) {
            return false;
        }

        // Verificar se a conta ATUAL foi criada neste dispositivo
        $contaCriadaNeste = DeviceFingerprint::where('usuario_id', $usuario->id)
            ->where('fingerprint', $fingerprint)
            ->where('conta_criada_neste_dispositivo', true)
            ->exists();

        if ($contaCriadaNeste) {
            // Esta conta foi criada neste dispositivo banido
            Log::warning('Usuário tentando acessar de dispositivo banido onde conta foi criada', [
                'usuario_id' => $usuario->id,
                'fingerprint' => $fingerprint,
                'ip' => $request->ip()
            ]);

            // Fazer logout forçado
            Auth::logout();
            return true;
        }

        // Conta não foi criada neste dispositivo, pode acessar normalmente
        return false;
    }

    /**
     * Verificar se deve mostrar tela específica
     * Evita que usuário acesse tela errada (ex: tela de ban quando tem warning)
     */
    protected function deveMostrarTelaEspecifica($usuario, $request): bool
    {
        // Se tem account deleted, deve estar na tela de account deleted
        if ($usuario->account_deleted_at && !$request->routeIs('moderacao.account-deleted.show')) {
            return true;
        }

        // Se tem ban, deve estar na tela de ban
        if ($usuario->ban_tipo && !$request->routeIs('moderacao.ban.show')) {
            return true;
        }

        // Se tem warning, deve estar na tela de warning
        if ($usuario->warning_ativo && !$request->routeIs('moderacao.warning.show') && !$request->routeIs('moderacao.warning.reativar')) {
            return true;
        }

        return false;
    }

    /**
     * Redirecionar para a punição correta
     */
    protected function redirecionarParaPunicaoCorreta($usuario)
    {
        if ($usuario->account_deleted_at) {
            return redirect()->route('moderacao.account-deleted.show');
        }

        if ($usuario->ban_tipo) {
            return redirect()->route('moderacao.ban.show');
        }

        if ($usuario->warning_ativo) {
            return redirect()->route('moderacao.warning.show');
        }

        return redirect()->route('home');
    }

    /**
     * Handle account deleted
     */
    protected function handleAccountDeleted($usuario)
    {
        // Verificar se já passou 30 dias para hard delete
        $hardDeleteDate = Carbon::parse($usuario->account_hard_delete_at);
        
        if (Carbon::now()->greaterThanOrEqualTo($hardDeleteDate)) {
            // Tempo esgotado - hard delete
            Log::info('Hard delete automático da conta', ['usuario_id' => $usuario->id]);
            
            // Deletar avatar se existir
            if ($usuario->avatar_url) {
                $usuario->deleteOldAvatar();
            }
            
            // Hard delete
            $usuario->forceDelete();
            Auth::logout();
            
            return redirect()->route('usuarios.login')
                ->with('info', 'Sua conta foi permanentemente excluída.');
        }
        
        // Ainda em período de soft delete
        return redirect()->route('moderacao.account-deleted.show');
    }

    /**
     * Handle ban (temporário ou permanente)
     */
    protected function handleBan($usuario)
    {
        // Se for ban temporário, verificar se já expirou
        if ($usuario->ban_tipo === 'temporario' && $usuario->ban_fim) {
            if (Carbon::now()->greaterThanOrEqualTo($usuario->ban_fim)) {
                // Ban expirou - remover
                $usuario->update([
                    'ban_tipo' => null,
                    'ban_motivo' => null,
                    'ban_inicio' => null,
                    'ban_fim' => null,
                    'ban_aplicado_por' => null
                ]);
                
                Log::info('Ban temporário expirado automaticamente', ['usuario_id' => $usuario->id]);
                
                // Redirecionar para home com mensagem
                return redirect()->route('home')
                    ->with('success', 'Seu banimento temporário expirou. Bem-vindo de volta!');
            }
        }
        
        // Ban ainda ativo
        return redirect()->route('moderacao.ban.show');
    }
}