/// <reference types="react" />
import React, { useState, useEffect } from 'react';
import axios from 'axios';

interface IniciarSessaoButtonProps {
    salaId: number;
    temPermissao: boolean;
    userId: number;
}

interface Sessao {
    id: number;
    nome_sessao: string;
    status: string;
    mestre_id: number;
}

const IniciarSessaoButton: React.FC<IniciarSessaoButtonProps> = ({
    salaId,
    temPermissao,
    userId
}) => {
    const [loading, setLoading] = useState(false);
    const [sessaoAtiva, setSessaoAtiva] = useState<Sessao | null>(null);
    const [showModal, setShowModal] = useState(false);
    const [nomeSessao, setNomeSessao] = useState('');
    const [error, setError] = useState('');

    // Verificar se há sessão ativa
    useEffect(() => {
        verificarSessaoAtiva();
    }, [salaId]);

    const verificarSessaoAtiva = async () => {
        try {
            const response = await axios.get(`/salas/${salaId}/sessao-ativa`);
            if (response.data.success && response.data.sessao) {
                setSessaoAtiva(response.data.sessao);
            } else {
                setSessaoAtiva(null);
            }
        } catch (err) {
            console.error('Erro ao verificar sessão ativa:', err);
        }
    };

    const handleIniciarSessao = async () => {
        setLoading(true);
        setError('');
        
        try {
            const response = await axios.post(`/salas/${salaId}/iniciar-sessao`, {
                nome_sessao: nomeSessao || undefined,
                configuracoes: {}
            });

            if (response.data.success) {
                console.log('[IniciarSessao] Sessão iniciada com sucesso!');
                console.log('[IniciarSessao] Aguardando redirecionamento via WebSocket...');
                
                // NÃO redirecione aqui! O WebSocket vai fazer isso
                // O evento session.started será capturado pelo script na página da sala
            }
        } catch (err: any) {
            console.error('Erro ao iniciar sessão:', err);
            const errorMsg = err.response?.data?.message || 'Erro ao iniciar sessão. Tente novamente.';
            setError(errorMsg);
            setLoading(false);
        }
    };

    // Não mostrar o botão se não tem permissão
    if (!temPermissao) {
        return null;
    }

    // Se já existe sessão ativa, não mostrar nada (será tratado na view da sala)
    if (sessaoAtiva) {
        return null;
    }

    return (
        <>
            {/* Botão para abrir modal */}
            <button
                onClick={() => setShowModal(true)}
                disabled={loading}
                className="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                🎮 Iniciar Sessão
            </button>

            {/* Modal de configuração da sessão */}
            {showModal && (
                <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div className="bg-white rounded-lg shadow-2xl max-w-md w-full p-6">
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-2xl font-bold text-gray-800">🎲 Iniciar Nova Sessão</h2>
                            <button
                                onClick={() => setShowModal(false)}
                                disabled={loading}
                                className="text-gray-400 hover:text-gray-600 text-2xl leading-none"
                            >
                                ×
                            </button>
                        </div>

                        {error && (
                            <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                                {error}
                            </div>
                        )}

                        <div className="mb-4">
                            <label className="block text-gray-700 font-semibold mb-2">
                                Nome da Sessão (Opcional)
                            </label>
                            <input
                                type="text"
                                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Ex: Aventura no Reino Perdido"
                                value={nomeSessao}
                                onChange={(e) => setNomeSessao(e.target.value)}
                                maxLength={100}
                                disabled={loading}
                            />
                            <p className="text-sm text-gray-500 mt-1">
                                Se deixar em branco, será gerado automaticamente.
                            </p>
                        </div>

                        <div className="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                            <p className="text-sm text-blue-700">
                                ℹ️ Ao iniciar a sessão, todos os participantes online serão
                                automaticamente redirecionados para a sala de jogo.
                            </p>
                        </div>

                        <div className="flex gap-3">
                            <button
                                onClick={() => setShowModal(false)}
                                disabled={loading}
                                className="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition disabled:opacity-50"
                            >
                                Cancelar
                            </button>
                            <button
                                onClick={handleIniciarSessao}
                                disabled={loading}
                                className="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition disabled:opacity-50 flex items-center justify-center gap-2"
                            >
                                {loading ? (
                                    <>
                                        <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Aguardando...
                                    </>
                                ) : (
                                    <>
                                        🚀 Iniciar Sessão
                                    </>
                                )}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
};

export default IniciarSessaoButton;
