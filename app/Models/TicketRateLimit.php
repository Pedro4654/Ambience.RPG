<?php

// ========== TicketRateLimit.php ==========

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TicketRateLimit extends Model
{
    use HasFactory;

    protected $table = 'ticket_rate_limits';

    protected $fillable = [
        'usuario_id',
        'ip_address',
        'tentativas',
        'primeira_tentativa',
        'ultima_tentativa',
        'liberado_em'
    ];

    protected $casts = [
        'primeira_tentativa' => 'datetime',
        'ultima_tentativa' => 'datetime',
        'liberado_em' => 'datetime',
        'tentativas' => 'integer'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Verificar se usuário está bloqueado
     */
    public static function estaBloqueado($usuarioId, $ip)
    {
        $rateLimit = self::where('usuario_id', $usuarioId)
            ->where('ip_address', $ip)
            ->first();

        if (!$rateLimit) {
            return false;
        }

        if ($rateLimit->liberado_em && Carbon::now()->lt($rateLimit->liberado_em)) {
            return true;
        }

        return false;
    }

    /**
     * Registrar tentativa de criação de ticket
     */
    public static function registrarTentativa($usuarioId, $ip)
    {
        $maxTentativas = SuporteConfiguracao::getValor('tickets_por_hora', 3);
        $tempoBloqueio = SuporteConfiguracao::getValor('tempo_bloqueio_spam_minutos', 60);

        $rateLimit = self::firstOrNew([
            'usuario_id' => $usuarioId,
            'ip_address' => $ip
        ]);

        $agora = Carbon::now();

        // Se passou mais de 1 hora desde a primeira tentativa, resetar contador
        if ($rateLimit->primeira_tentativa && $agora->diffInHours($rateLimit->primeira_tentativa) >= 1) {
            $rateLimit->tentativas = 1;
            $rateLimit->primeira_tentativa = $agora;
            $rateLimit->liberado_em = null;
        } else {
            $rateLimit->tentativas = ($rateLimit->tentativas ?? 0) + 1;
            
            if (!$rateLimit->primeira_tentativa) {
                $rateLimit->primeira_tentativa = $agora;
            }

            // Se excedeu limite, bloquear
            if ($rateLimit->tentativas > $maxTentativas) {
                $rateLimit->liberado_em = $agora->addMinutes($tempoBloqueio);
            }
        }

        $rateLimit->ultima_tentativa = $agora;
        $rateLimit->save();

        return $rateLimit;
    }

    /**
     * Obter tempo restante de bloqueio
     */
    public function getTempoRestante()
    {
        if (!$this->liberado_em) {
            return 0;
        }

        $agora = Carbon::now();
        
        if ($agora->gte($this->liberado_em)) {
            return 0;
        }

        return $agora->diffInMinutes($this->liberado_em);
    }
}