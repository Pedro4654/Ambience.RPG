<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DeviceFingerprint extends Model
{
    protected $fillable = [
        'usuario_id',
        'fingerprint',
        'ip_address',
        'user_agent',
        'accept_language',
        'accept_encoding',
        'conta_criada_neste_dispositivo',
        'primeiro_acesso',
        'ultimo_acesso'
    ];

    protected $casts = [
        'conta_criada_neste_dispositivo' => 'boolean',
        'primeiro_acesso' => 'datetime',
        'ultimo_acesso' => 'datetime'
    ];

    /**
     * Relacionamento com usuário
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Gerar fingerprint único da máquina
     */
    public static function generateFingerprint(Request $request): string
    {
        $data = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'accept_language' => $request->header('Accept-Language'),
            'accept_encoding' => $request->header('Accept-Encoding'),
        ];
        
        return hash('sha256', json_encode($data));
    }

    /**
     * Registrar acesso do dispositivo
     */
    public static function registrarAcesso(int $usuarioId, Request $request, bool $contaCriada = false)
    {
        $fingerprint = self::generateFingerprint($request);

        $device = self::where('usuario_id', $usuarioId)
            ->where('fingerprint', $fingerprint)
            ->first();

        if ($device) {
            // Atualizar último acesso
            $device->update(['ultimo_acesso' => now()]);
        } else {
            // Criar novo registro
            self::create([
                'usuario_id' => $usuarioId,
                'fingerprint' => $fingerprint,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'accept_language' => $request->header('Accept-Language'),
                'accept_encoding' => $request->header('Accept-Encoding'),
                'conta_criada_neste_dispositivo' => $contaCriada,
                'primeiro_acesso' => now(),
                'ultimo_acesso' => now()
            ]);
        }

        return $fingerprint;
    }

    /**
     * Verificar se dispositivo está banido
     */
    public static function isDeviceBanned(Request $request): bool
    {
        $fingerprint = self::generateFingerprint($request);

        // Verificar se existe alguma conta criada neste dispositivo com IP ban
        return Usuario::where('ip_ban_ativo', true)
            ->where('ip_ban_fingerprint', $fingerprint)
            ->exists();
    }

    /**
     * Verificar se conta foi criada neste dispositivo
     */
    public static function foiCriadaNeste(int $usuarioId, Request $request): bool
    {
        $fingerprint = self::generateFingerprint($request);

        return self::where('usuario_id', $usuarioId)
            ->where('fingerprint', $fingerprint)
            ->where('conta_criada_neste_dispositivo', true)
            ->exists();
    }
}