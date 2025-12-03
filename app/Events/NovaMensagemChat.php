<?php

namespace App\Events;

use App\Models\MensagemChat;
use App\Models\Sala;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NovaMensagemChat implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mensagem;
    public $sala;

    public function __construct(MensagemChat $mensagem, Sala $sala)
    {
        // ✅ CARREGAR RELACIONAMENTOS ANTES DE TUDO
        $mensagem->load(['usuario', 'anexos']);
        
        $this->mensagem = $mensagem;
        $this->sala = $sala;
        
        Log::info('[Evento] NovaMensagemChat criado', [
            'mensagem_id' => $mensagem->id,
            'sala_id' => $sala->id,
            'tem_usuario' => $mensagem->usuario ? 'sim' : 'não',
            'anexos_count' => $mensagem->anexos->count()
        ]);
    }

    public function broadcastOn()
    {
        $canal = new PresenceChannel('sala.' . $this->sala->id);
        
        Log::info('[Evento] broadcastOn chamado', [
            'canal' => 'presence-sala.' . $this->sala->id
        ]);
        
        return $canal;
    }

    public function broadcastAs()
    {
        Log::info('[Evento] broadcastAs chamado - retornando "nova.mensagem"');
        return 'nova.mensagem';
    }

    public function broadcastWith()
    {
        Log::info('[Evento] broadcastWith chamado - preparando dados');
        
        try {
            $data = [
                'id' => $this->mensagem->id,
                'usuario' => [
                    'id' => $this->mensagem->usuario->id,
                    'username' => $this->mensagem->usuario->username,
                    'avatar_url' => $this->mensagem->usuario->avatar_url ?? '/images/default-avatar.png'
                ],
                'mensagem' => $this->mensagem->mensagem,
                'mensagem_original' => $this->mensagem->mensagem_original,
                'censurada' => $this->mensagem->censurada ?? false,
                'flags_detectadas' => $this->mensagem->flags_detectadas ?? [],
                'anexos' => $this->mensagem->anexos->map(function ($anexo) {
                    return [
                        'id' => $anexo->id,
                        'url' => $anexo->getUrl(),
                        'nome' => $anexo->nome_original,
                        'tamanho' => $anexo->getTamanhoFormatado(),
                        'eh_imagem' => $anexo->ehImagem(),
                        'nsfw_detectado' => $anexo->nsfw_detectado ?? false
                    ];
                })->toArray(),
                'created_at' => $this->mensagem->created_at->toIso8601String(),
                'timestamp_formatado' => $this->mensagem->getTimestampFormatado(),
                'editada' => false
            ];
            
            Log::info('[Evento] broadcastWith - dados preparados com sucesso', [
                'data_keys' => array_keys($data)
            ]);
            
            return $data;
            
        } catch (\Exception $e) {
            Log::error('[Evento] ERRO em broadcastWith', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}