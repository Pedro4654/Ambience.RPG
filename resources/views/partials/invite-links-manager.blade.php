{{-- resources/views/partials/invite-links-manager.blade.php --}}
{{-- Modal Premium de Gerenciamento de Links de Convite --}}
{{-- Design: Dark Premium SaaS com Parallax 3D, Glassmorphism e Microinterações --}}

<!-- ========== MODAL ESTRUTURA BOOTSTRAP (Compatibilidade Total) ========== -->
<div class="modal fade"
    id="inviteLinksModal"
    tabindex="-1"
    aria-labelledby="inviteLinksModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="true"
    data-modal-premium>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content invite-modal-premium" data-parallax-container>

            <!-- ========== CAMADA DE PARALLAX 3D ========== -->
            <div class="parallax-layers" aria-hidden="true">
                <!-- Layer 1: Gradient de fundo animado -->
                <div class="parallax-layer parallax-bg" data-parallax-depth="0.1"></div>

                <!-- Layer 2: Efeito de brilho -->
                <div class="parallax-layer parallax-glow" data-parallax-depth="0.3"></div>

                <!-- Layer 3: Partículas flutuantes -->
                <div class="parallax-layer parallax-particles" data-parallax-depth="0.5">
                    <div class="particle" style="left: 10%; top: 20%;"></div>
                    <div class="particle" style="left: 70%; top: 40%;"></div>
                    <div class="particle" style="left: 30%; top: 70%;"></div>
                    <div class="particle" style="left: 85%; top: 15%;"></div>
                    <div class="particle" style="left: 50%; top: 85%;"></div>
                </div>

                <!-- Layer 4: Grid sutil -->
                <div class="parallax-layer parallax-grid" data-parallax-depth="0.2"></div>
            </div>

            <!-- ========== HEADER COM GLASSMORPHISM ========== -->
            <div class="modal-header invite-modal-header" data-parallax-content>
                <div class="header-content">
                    <div class="header-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                        </svg>
                    </div>
                    <div class="header-text">
                        <h5 class="modal-title" id="inviteLinksModalLabel">Gerenciar Links de Convite</h5>
                        <p class="header-subtitle">Crie, gerencie e monitore seus links de acesso</p>
                    </div>
                </div>

                <div class="header-actions">
                    <button type="button"
                        class="btn-icon btn-refresh"
                        onclick="window.loadInviteLinks()"
                        title="Atualizar lista"
                        data-tooltip="Atualizar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2" />
                        </svg>
                    </button>
                    <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Fechar"></button>
                </div>
            </div>

            <!-- ========== BODY COM SCROLL SUAVE ========== -->
            <div class="modal-body invite-modal-body" data-parallax-content>

                <!-- ========== SEÇÃO: CRIAR NOVO LINK ========== -->
                <div class="create-link-section card-premium">
                    <div class="section-header">
                        <div class="section-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="16" />
                                <line x1="8" y1="12" x2="16" y2="12" />
                            </svg>
                        </div>
                        <h6 class="section-title">Criar Novo Link</h6>
                    </div>

                    <form id="createInviteLinkForm" class="create-link-form">
                        <div class="form-grid">
                            <!-- Validade -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    Validade
                                </label>
                                <select class="form-control form-select-premium" name="validade" required>
                                    <option value="1h">1 hora</option>
                                    <option value="1d" selected>1 dia</option>
                                    <option value="1w">1 semana</option>
                                    <option value="1m">1 mês</option>
                                    <option value="never">Nunca expira</option>
                                </select>
                            </div>

                            <!-- Máximo de Usos -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="8.5" cy="7" r="4" />
                                        <polyline points="17 11 19 13 23 9" />
                                    </svg>
                                    Máximo de Usos
                                </label>
                                <select class="form-control form-select-premium" name="max_usos">
                                    <option value="">Ilimitado</option>
                                    <option value="1">1 uso (único)</option>
                                    <option value="5">5 usos</option>
                                    <option value="10">10 usos</option>
                                    <option value="25">25 usos</option>
                                    <option value="50">50 usos</option>
                                    <option value="100">100 usos</option>
                                </select>
                            </div>
                        </div>

                        <!-- Botão de Criação -->
                        <button type="submit" class="btn-premium btn-create-link" data-loading="false">
    <span class="btn-content">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 5v14M5 12h14" />
        </svg>
        <span class="btn-text">Gerar Link de Convite</span>
    </span>
    <span class="btn-loading">
        <span class="spinner"></span>
        <span class="btn-text">Gerando...</span>
    </span>
</button>
                    </form>

                    <!-- Alert de Feedback -->
                    <div id="createLinkAlert" class="alert-premium d-none" role="alert">
                        <div class="alert-icon"></div>
                        <div class="alert-content">
                            <div class="alert-title"></div>
                            <div class="alert-message"></div>
                        </div>
                        <button type="button" class="alert-close">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- ========== SEÇÃO: LISTA DE LINKS ========== -->
                <div class="links-list-section card-premium">
                    <div class="section-header">
                        <div class="section-icon">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 6h11"></path>
        <path d="M9 12h11"></path>
        <path d="M9 18h11"></path>
        <circle cx="4" cy="6" r="1" fill="currentColor"></circle>
        <circle cx="4" cy="12" r="1" fill="currentColor"></circle>
        <circle cx="4" cy="18" r="1" fill="currentColor"></circle>
    </svg>
</div>
                        <div class="section-title-wrapper">
                            <h6 class="section-title">Links Ativos</h6>
                            <span class="links-count" id="linksCountBadge">0 links</span>
                        </div>
                    </div>

                    <!-- Filtros e Busca -->
                    <div class="filters-bar">
                        <div class="search-wrapper">
                            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
                            </svg>
                            <input type="text"
                                id="inviteSearchInput"
                                class="search-input"
                                placeholder="Buscar por código, criador ou data..."
                                autocomplete="off">
                            <button type="button" class="search-clear" id="searchClearBtn" style="display: none;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18" />
                                    <line x1="6" y1="6" x2="18" y2="18" />
                                </svg>
                            </button>
                        </div>

                        <div class="filter-group">
                            <select id="inviteFilterStatus" class="filter-select">
                                <option value="all">Todos os status</option>
                                <option value="active">Apenas ativos</option>
                                <option value="expired">Apenas expirados</option>
                            </select>

                            <select id="inviteFilterSort" class="filter-select">
                                <option value="newest">Mais recentes</option>
                                <option value="oldest">Mais antigos</option>
                                <option value="most_used">Mais usados</option>
                            </select>
                        </div>
                    </div>

                    <!-- Container da Lista -->
                    <div id="inviteLinksContainer" class="links-container">
                        <!-- Loading State Inicial -->
                        <div class="loading-state">
                            <div class="loading-spinner">
                                <div class="spinner-ring"></div>
                                <div class="spinner-ring"></div>
                                <div class="spinner-ring"></div>
                            </div>
                            <p class="loading-text">Carregando links...</p>
                        </div>
                    </div>
                </div>

                <!-- ========== SEÇÃO: ESTATÍSTICAS ========== -->
                <div class="stats-section">
                    <div class="stat-card card-premium">
                        <div class="stat-icon stat-icon-success">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value" id="statTotalLinks">0</div>
                            <div class="stat-label">Total de Links</div>
                        </div>
                    </div>

                    <div class="stat-card card-premium">
                        <div class="stat-icon stat-icon-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value" id="statActiveLinks">0</div>
                            <div class="stat-label">Links Ativos</div>
                        </div>
                    </div>

                    <div class="stat-card card-premium">
                        <div class="stat-icon stat-icon-danger">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="15" y1="9" x2="9" y2="15" />
                                <line x1="9" y1="9" x2="15" y2="15" />
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value" id="statExpiredLinks">0</div>
                            <div class="stat-label">Links Expirados</div>
                        </div>
                    </div>

                    <div class="stat-card card-premium">
                        <div class="stat-icon stat-icon-warning">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value" id="statTotalUses">0</div>
                            <div class="stat-label">Total de Usos</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ========== FOOTER COM AÇÕES ========== -->
            <div class="modal-footer invite-modal-footer" data-parallax-content>
                <button type="button" class="btn-premium btn-outline" data-bs-dismiss="modal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                    <span>Fechar</span>
                </button>

                <button type="button" class="btn-premium btn-secondary" id="btnBulkRevoke">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                    </svg>
                    <span>Revogar Expirados</span>
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ========== TOAST NOTIFICATION SYSTEM ========== -->
<div id="inviteToastContainer" class="toast-container" aria-live="polite" aria-atomic="true"></div>

<!-- ========== ESTILOS PREMIUM ========== -->
<style>
    /* ========== VARIÁVEIS CSS ========== */
    :root {
        --modal-bg-primary: #0f1113;
        --modal-bg-secondary: #1a1d21;
        --modal-bg-card: rgba(31, 41, 55, 0.6);
        --modal-border: rgba(55, 65, 81, 0.3);
        --modal-accent: #22c55e;
        --modal-accent-dark: #16a34a;
        --modal-accent-light: rgba(34, 197, 94, 0.1);
        --modal-text-primary: #e6eef6;
        --modal-text-secondary: #9aa0a6;
        --modal-text-muted: #6b7280;
        --modal-danger: #ef4444;
        --modal-warning: #f59e0b;
        --modal-info: #3b82f6;
        --modal-success: #10b981;
        --modal-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --modal-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        --modal-shadow-lg: 0 25px 80px rgba(0, 0, 0, 0.7);
    }

    /* ========== RESET & BASE ========== */
    .invite-modal-premium {
        background: var(--modal-bg-primary) !important;
        border: 1px solid var(--modal-border) !important;
        border-radius: 24px !important;
        overflow: hidden;
        position: relative;
        box-shadow: var(--modal-shadow-lg);
        backdrop-filter: blur(20px);
    }

    .invite-modal-premium * {
        box-sizing: border-box;
    }

    /* ========== PARALLAX LAYERS ========== */
    .parallax-layers {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
        z-index: 0;
        border-radius: 24px;
    }

    .parallax-layer {
        position: absolute;
        inset: 0;
        transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }

    /* Layer 1: Gradient Background */
    .parallax-bg {
        background: linear-gradient(135deg,
                rgba(34, 197, 94, 0.03) 0%,
                rgba(59, 130, 246, 0.02) 50%,
                rgba(139, 92, 246, 0.03) 100%);
        animation: parallaxGradient 20s ease infinite;
    }

    @keyframes parallaxGradient {

        0%,
        100% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }
    }

    /* Layer 2: Glow Effect */
    .parallax-glow {
        background: radial-gradient(circle at 50% 50%,
                rgba(34, 197, 94, 0.08) 0%,
                transparent 70%);
        filter: blur(40px);
        opacity: 0.6;
        animation: parallaxPulse 8s ease-in-out infinite;
    }

    @keyframes parallaxPulse {

        0%,
        100% {
            opacity: 0.4;
            transform: scale(1);
        }

        50% {
            opacity: 0.7;
            transform: scale(1.1);
        }
    }

    /* Layer 3: Floating Particles */
    .parallax-particles .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: var(--modal-accent);
        border-radius: 50%;
        opacity: 0.2;
        animation: particleFloat 15s ease-in-out infinite;
        box-shadow: 0 0 10px var(--modal-accent);
    }

    .parallax-particles .particle:nth-child(2) {
        animation-delay: 3s;
    }

    .parallax-particles .particle:nth-child(3) {
        animation-delay: 6s;
    }

    .parallax-particles .particle:nth-child(4) {
        animation-delay: 9s;
    }

    .parallax-particles .particle:nth-child(5) {
        animation-delay: 12s;
    }

    @keyframes particleFloat {

        0%,
        100% {
            transform: translate(0, 0) scale(1);
            opacity: 0.2;
        }

        25% {
            transform: translate(20px, -30px) scale(1.2);
            opacity: 0.4;
        }

        50% {
            transform: translate(-15px, -60px) scale(0.8);
            opacity: 0.6;
        }

        75% {
            transform: translate(25px, -40px) scale(1.1);
            opacity: 0.3;
        }
    }

    /* Layer 4: Grid Pattern */
    .parallax-grid {
        background-image:
            linear-gradient(var(--modal-border) 1px, transparent 1px),
            linear-gradient(90deg, var(--modal-border) 1px, transparent 1px);
        background-size: 50px 50px;
        opacity: 0.1;
    }

    /* Conteúdo sobre parallax */
    [data-parallax-content] {
        position: relative;
        z-index: 1;
    }

    /* ========== HEADER ========== */
    .invite-modal-header {
        background: linear-gradient(135deg,
                rgba(31, 41, 55, 0.8) 0%,
                rgba(17, 24, 39, 0.9) 100%) !important;
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--modal-border) !important;
        padding: 1.5rem 2rem !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .header-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--modal-accent), var(--modal-accent-dark));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #052e16;
        box-shadow: 0 4px 12px var(--modal-accent-light);
        animation: headerIconFloat 3s ease-in-out infinite;
    }

    @keyframes headerIconFloat {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }
    }

    .header-text {
        flex: 1;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--modal-text-primary);
        margin: 0;
        letter-spacing: -0.02em;
    }

    .header-subtitle {
        font-size: 0.875rem;
        color: var(--modal-text-secondary);
        margin: 0.25rem 0 0;
        font-weight: 400;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-icon {
        width: 40px;
        height: 40px;
        background: rgba(55, 65, 81, 0.4);
        border: 1px solid var(--modal-border);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--modal-text-secondary);
        cursor: pointer;
        transition: var(--modal-transition);
        position: relative;
    }

    .btn-content {
    display: flex;
    flex-direction: row; /* IMPORTANTE: força o layout horizontal */
    align-items: center;
    justify-content: center;
    gap: 0.5rem; /* Espaço entre o ícone e o texto */
}

.btn-content svg {
    flex-shrink: 0; /* Evita que o ícone encolha */
    width: 18px;
    height: 18px;
}

/* Garanta que o texto também está alinhado: */
.btn-text {
    display: inline-block;
    line-height: 1;
}

    .btn-icon:hover {
        background: rgba(55, 65, 81, 0.6);
        border-color: var(--modal-accent);
        color: var(--modal-accent);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
    }

    .btn-icon:active {
        transform: translateY(0);
    }

    .btn-refresh svg {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-refresh:active svg {
        transform: rotate(360deg);
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
        opacity: 0.7;
        transition: var(--modal-transition);
    }

    .btn-close-white:hover {
        opacity: 1;
        transform: scale(1.1);
    }

    /* Tooltip */
    [data-tooltip] {
        position: relative;
    }

    [data-tooltip]::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%) scale(0.9);
        background: rgba(0, 0, 0, 0.9);
        color: white;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
    }

    [data-tooltip]:hover::after {
        opacity: 1;
        transform: translateX(-50%) scale(1);
    }

    /* ========== BODY ========== */
    .invite-modal-body {
        padding: 2rem !important;
        background: transparent !important;
        overflow-y: auto;
        max-height: calc(90vh - 200px);
    }

    .invite-modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .invite-modal-body::-webkit-scrollbar-track {
        background: rgba(55, 65, 81, 0.2);
        border-radius: 4px;
    }

    .invite-modal-body::-webkit-scrollbar-thumb {
        background: rgba(34, 197, 94, 0.3);
        border-radius: 4px;
    }

    .invite-modal-body::-webkit-scrollbar-thumb:hover {
        background: rgba(34, 197, 94, 0.5);
    }

    /* ========== CARD PREMIUM ========== */
    .card-premium {
        background: var(--modal-bg-card);
        backdrop-filter: blur(10px);
        border: 1px solid var(--modal-border);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: var(--modal-transition);
        position: relative;
        overflow: hidden;
    }

    .card-premium::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg,
                transparent,
                var(--modal-accent),
                transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .card-premium:hover::before {
        opacity: 1;
    }

    .card-premium:hover {
        border-color: rgba(34, 197, 94, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }

    /* ========== SECTION HEADER ========== */
    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--modal-border);
    }

    .section-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg,
            rgba(34, 197, 94, 0.15),
            rgba(34, 197, 94, 0.05));
    border: 1px solid rgba(34, 197, 94, 0.3);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--modal-accent);
    flex-shrink: 0;
}

.section-icon svg {
    display: block;
    margin: auto;
    flex-shrink: 0;
}

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--modal-text-primary);
        margin: 0;
    }

    .section-title-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
    }

    .links-count {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.2);
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--modal-accent);
        letter-spacing: 0.02em;
    }

    /* ========== FORM PREMIUM ========== */
    .create-link-form {
        margin-top: 1.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--modal-text-secondary);
        margin: 0;
    }

    .form-label svg {
        color: var(--modal-accent);
    }

    .form-control,
    .form-select-premium,
    .filter-select,
    .search-input {
        background: rgba(17, 24, 39, 0.8) !important;
        border: 2px solid var(--modal-border) !important;
        border-radius: 10px !important;
        color: var(--modal-text-primary) !important;
        padding: 0.75rem 1rem !important;
        font-size: 0.9375rem;
        transition: var(--modal-transition);
        outline: none !important;
    }

    .form-control:focus,
    .form-select-premium:focus,
    .filter-select:focus,
    .search-input:focus {
        border-color: var(--modal-accent) !important;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1) !important;
        background: rgba(17, 24, 39, 0.95) !important;
    }

    .form-select-premium,
.filter-select {
    cursor: pointer;
    position: relative;
    padding-right: 3rem !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    transition: var(--modal-transition);
}

.form-select-premium::-ms-expand,
.filter-select::-ms-expand {
    display: none;
}

/* Wrapper customizado para o ícone */
.select-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
}

.select-wrapper select {
    width: 100%;
}

.select-wrapper::after {
    content: '';
    position: absolute;
    right: 1rem;
    top: 50%;
    width: 16px;
    height: 16px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none'%3E%3Cpath d='M6 9l6 6 6-6' stroke='%2322c55e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
    transform: translateY(-50%);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
    z-index: 1;
}

/* Animação de rotação quando o select está aberto */
.select-wrapper.active::after {
    transform: translateY(-50%) rotate(180deg);
}

    .form-select-premium option,
    .filter-select option {
        background: #1a1d21;
        color: var(--modal-text-primary);
        padding: 0.5rem;
    }

    /* ========== BOTÕES PREMIUM ========== */
    .btn-premium {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-size: 0.9375rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--modal-transition);
        position: relative;
        overflow: hidden;
        white-space: nowrap;
    }

    .btn-content,
.btn-loading {
    display: flex;
    flex-direction: row; /* Garante layout horizontal */
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-content svg,
.btn-loading svg {
    flex-shrink: 0;
}

.btn-text {
    white-space: nowrap;
}

    .btn-premium::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-premium:hover::before {
        width: 300px;
        height: 300px;
    }

    .link-badge svg {
    display: block;
    width: 20px;
    height: 20px;
    margin: auto;
}

    .btn-premium svg {
        position: relative;
        z-index: 1;
    }

    .btn-premium span {
        position: relative;
        z-index: 1;
    }

    .btn-create-link {
        width: 100%;
        background: linear-gradient(135deg, var(--modal-accent), var(--modal-accent-dark));
        color: #052e16;
        box-shadow: 0 4px 14px rgba(34, 197, 94, 0.4);
    }

    .btn-create-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(34, 197, 94, 0.5);
    }

    .btn-create-link:active {
        transform: translateY(0);
    }

    .btn-create-link[data-loading="true"] .btn-content {
        display: none;
    }

    .btn-create-link[data-loading="false"] .btn-loading {
        display: none;
    }

    .btn-loading {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .spinner {
        width: 16px;
        height: 16px;
        border: 2px solid rgba(5, 46, 22, 0.2);
        border-top-color: #052e16;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .btn-outline {
        background: transparent;
        border: 1px solid var(--modal-border) !important;
        color: var(--modal-text-secondary);
    }

    .btn-outline:hover {
        background: rgba(55, 65, 81, 0.4);
        border-color: var(--modal-accent) !important;
        color: var(--modal-accent);
    }

    .btn-secondary {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3) !important;
        color: var(--modal-danger);
    }

    .btn-secondary:hover {
        background: rgba(239, 68, 68, 0.2);
        border-color: var(--modal-danger) !important;
    }

    /* ========== ALERT PREMIUM ========== */
    .alert-premium {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
        background: var(--modal-bg-secondary);
        border: 1px solid var(--modal-border);
        border-radius: 10px;
        margin-top: 1rem;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-premium.alert-success {
        background: rgba(16, 185, 129, 0.1);
        border-color: rgba(16, 185, 129, 0.3);
    }

    .alert-premium.alert-success .alert-icon::before {
        content: '✓';
        color: var(--modal-success);
    }

    .alert-premium.alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border-color: rgba(239, 68, 68, 0.3);
    }

    .alert-premium.alert-danger .alert-icon::before {
        content: '✕';
        color: var(--modal-danger);
    }

    .alert-premium.alert-warning {
        background: rgba(245, 158, 11, 0.1);
        border-color: rgba(245, 158, 11, 0.3);
    }

    .alert-premium.alert-warning .alert-icon::before {
        content: '⚠';
        color: var(--modal-warning);
    }

    .alert-premium.alert-info {
        background: rgba(59, 130, 246, 0.1);
        border-color: rgba(59, 130, 246, 0.3);
    }

    .alert-premium.alert-info .alert-icon::before {
        content: 'ℹ';
        color: var(--modal-info);
    }

    .alert-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .alert-content {
        flex: 1;
        min-width: 0;
    }

    .alert-title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--modal-text-primary);
        margin-bottom: 0.25rem;
    }

    .alert-message {
        font-size: 0.875rem;
        color: var(--modal-text-secondary);
        line-height: 1.5;
    }

    .alert-close {
        background: transparent;
        border: none;
        padding: 0.25rem;
        cursor: pointer;
        color: var(--modal-text-muted);
        transition: var(--modal-transition);
    }

    .alert-close:hover {
        color: var(--modal-text-primary);
    }

    /* ========== FILTERS BAR ========== */
    .filters-bar {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.search-wrapper {
    position: relative;
    flex: 1;
    min-width: 250px;
    max-width: 100%; /* Adicionar limite máximo */
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--modal-text-muted);
    pointer-events: none;
    z-index: 2; /* Garantir que fica acima */
}

.search-input {
    padding-left: 3rem !important;
    padding-right: 3rem !important;
    width: 100%; /* Forçar largura total */
}

.search-clear {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    padding: 0.25rem;
    cursor: pointer;
    color: var(--modal-text-muted);
    transition: var(--modal-transition);
    z-index: 2; /* Garantir que fica acima */
    display: flex; /* Adicionar */
    align-items: center; /* Adicionar */
    justify-content: center; /* Adicionar */
}

.search-clear:hover {
    color: var(--modal-text-primary);
}

.filter-group {
    display: flex;
    gap: 0.75rem;
    flex-shrink: 0; /* Evitar que encolha */
}

    .filter-select {
        min-width: 160px;
    }

    /* ========== LINKS CONTAINER ========== */
    .links-container {
        min-height: 300px;
        max-height: 500px;
        overflow-y: auto;
        padding: 0.5rem;
    }

    .links-container::-webkit-scrollbar {
        width: 6px;
    }

    .links-container::-webkit-scrollbar-track {
        background: rgba(55, 65, 81, 0.2);
        border-radius: 3px;
    }

    .links-container::-webkit-scrollbar-thumb {
        background: rgba(34, 197, 94, 0.3);
        border-radius: 3px;
    }

    .links-container::-webkit-scrollbar-thumb:hover {
        background: rgba(34, 197, 94, 0.5);
    }

    /* ========== LOADING STATE ========== */
    .loading-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 4rem 2rem;
        text-align: center;
    }

    .loading-spinner {
        position: relative;
        width: 60px;
        height: 60px;
        margin-bottom: 1.5rem;
    }

    .spinner-ring {
        position: absolute;
        inset: 0;
        border: 3px solid transparent;
        border-top-color: var(--modal-accent);
        border-radius: 50%;
        animation: spinRing 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    }

    .spinner-ring:nth-child(2) {
        border-top-color: rgba(34, 197, 94, 0.6);
        animation-delay: 0.15s;
    }

    .spinner-ring:nth-child(3) {
        border-top-color: rgba(34, 197, 94, 0.3);
        animation-delay: 0.3s;
    }

    @keyframes spinRing {
        to {
            transform: rotate(360deg);
        }
    }

    .loading-text {
        font-size: 0.9375rem;
        color: var(--modal-text-secondary);
        margin: 0;
    }

    /* ========== EMPTY STATE ========== */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg,
                rgba(34, 197, 94, 0.1),
                rgba(34, 197, 94, 0.05));
        border: 2px dashed rgba(34, 197, 94, 0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: var(--modal-accent);
    }

    .empty-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--modal-text-primary);
        margin-bottom: 0.5rem;
    }

    .empty-description {
        font-size: 0.875rem;
        color: var(--modal-text-secondary);
        margin: 0;
    }

    /* ========== LINK ITEM ========== */
    .link-item {
        background: rgba(31, 41, 55, 0.4);
        border: 1px solid var(--modal-border);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 0.75rem;
        transition: var(--modal-transition);
        position: relative;
        overflow: hidden;
    }

    .link-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--modal-accent);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .link-item:hover {
        background: rgba(31, 41, 55, 0.6);
        border-color: rgba(34, 197, 94, 0.4);
        transform: translateX(4px);
    }

    .link-item:hover::before {
        opacity: 1;
    }

    .link-item.expired {
        opacity: 0.6;
    }

    .link-item.expired::before {
        background: var(--modal-danger);
    }

    .link-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .link-code-wrapper {
        flex: 1;
        min-width: 0;
    }

    .link-code {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .link-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg,
                rgba(34, 197, 94, 0.2),
                rgba(34, 197, 94, 0.1));
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--modal-accent);
        letter-spacing: 0.05em;
    }

    .link-code-text {
        font-family: 'Courier New', 'Consolas', monospace;
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--modal-text-primary);
        word-break: break-all;
    }

    .link-url {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        background: rgba(17, 24, 39, 0.6);
        border: 1px solid rgba(34, 197, 94, 0.2);
        border-radius: 8px;
        font-family: 'Courier New', 'Consolas', monospace;
        font-size: 0.8125rem;
        color: var(--modal-accent);
        word-break: break-all;
    }

    .link-status {
        display: flex;
        gap: 0.5rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge.active {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: var(--modal-success);
    }

    .status-badge.expired {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: var(--modal-danger);
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .link-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8125rem;
        color: var(--modal-text-secondary);
    }

    .meta-item svg {
        color: var(--modal-accent);
        flex-shrink: 0;
    }

    .meta-value {
        color: var(--modal-text-primary);
        font-weight: 600;
    }

    .link-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-link-action {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.875rem;
        background: rgba(55, 65, 81, 0.4);
        border: 1px solid var(--modal-border);
        border-radius: 8px;
        font-size: 0.8125rem;
        font-weight: 500;
        color: var(--modal-text-secondary);
        cursor: pointer;
        transition: var(--modal-transition);
    }

    .btn-link-action:hover {
        background: rgba(55, 65, 81, 0.6);
        border-color: var(--modal-accent);
        color: var(--modal-accent);
        transform: translateY(-1px);
    }

    .btn-link-action:active {
        transform: translateY(0);
    }

    .btn-link-action.btn-copy {
        border-color: rgba(34, 197, 94, 0.3);
    }

    .btn-link-action.btn-copy:hover {
        background: rgba(34, 197, 94, 0.1);
        color: var(--modal-accent);
    }

    .btn-link-action.btn-revoke {
        border-color: rgba(239, 68, 68, 0.3);
        color: var(--modal-danger);
    }

    .btn-link-action.btn-revoke:hover {
        background: rgba(239, 68, 68, 0.1);
        border-color: var(--modal-danger);
    }

    /* ========== STATS SECTION ========== */
    .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-icon-success {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: var(--modal-success);
    }

    .stat-icon-primary {
        background: rgba(59, 130, 246, 0.15);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: var(--modal-info);
    }

    .stat-icon-danger {
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: var(--modal-danger);
    }

    .stat-icon-warning {
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: var(--modal-warning);
    }

    .stat-content {
        flex: 1;
        min-width: 0;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--modal-text-primary);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.8125rem;
        color: var(--modal-text-secondary);
        font-weight: 500;
    }

    /* ========== FOOTER ========== */
    .invite-modal-footer {
        background: rgba(31, 41, 55, 0.4) !important;
        backdrop-filter: blur(10px);
        border-top: 1px solid var(--modal-border) !important;
        padding: 1.25rem 2rem !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    /* ========== TOAST NOTIFICATIONS ========== */
    .toast-container {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        pointer-events: none;
    }

    .toast-notification {
        background: linear-gradient(135deg,
                rgba(31, 41, 55, 0.98),
                rgba(17, 24, 39, 0.98));
        backdrop-filter: blur(20px);
        border: 1px solid var(--modal-border);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        min-width: 320px;
        max-width: 400px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        pointer-events: auto;
        animation: toastSlideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    @keyframes toastSlideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .toast-notification.toast-exit {
        animation: toastSlideOut 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes toastSlideOut {
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }

    .toast-notification::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }

    .toast-notification.toast-success::before {
        background: var(--modal-success);
    }

    .toast-notification.toast-error::before {
        background: var(--modal-danger);
    }

    .toast-notification.toast-warning::before {
        background: var(--modal-warning);
    }

    .toast-notification.toast-info::before {
        background: var(--modal-info);
    }

    .toast-content {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .toast-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.125rem;
    }

    .toast-success .toast-icon {
        background: rgba(16, 185, 129, 0.15);
        color: var(--modal-success);
    }

    .toast-error .toast-icon {
        background: rgba(239, 68, 68, 0.15);
        color: var(--modal-danger);
    }

    .toast-warning .toast-icon {
        background: rgba(245, 158, 11, 0.15);
        color: var(--modal-warning);
    }

    .toast-info .toast-icon {
        background: rgba(59, 130, 246, 0.15);
        color: var(--modal-info);
    }

    .toast-body {
        flex: 1;
        min-width: 0;
    }

    .toast-title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--modal-text-primary);
        margin-bottom: 0.25rem;
    }

    .toast-message {
        font-size: 0.8125rem;
        color: var(--modal-text-secondary);
        line-height: 1.4;
    }

    .toast-close {
        background: transparent;
        border: none;
        padding: 0.25rem;
        cursor: pointer;
        color: var(--modal-text-muted);
        transition: var(--modal-transition);
        flex-shrink: 0;
    }

    .toast-close:hover {
        color: var(--modal-text-primary);
    }

    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: rgba(255, 255, 255, 0.1);
        overflow: hidden;
    }

    .toast-progress-bar {
        height: 100%;
        background: currentColor;
        animation: toastProgress 5s linear;
    }

    @keyframes toastProgress {
        from {
            width: 100%;
        }

        to {
            width: 0%;
        }
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 992px) {
        .modal-dialog {
            margin: 1rem;
        }

        .invite-modal-header {
            padding: 1.25rem 1.5rem !important;
        }

        .invite-modal-body {
            padding: 1.5rem !important;
        }

        .invite-modal-footer {
            padding: 1rem 1.5rem !important;
            flex-direction: column;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .filters-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-wrapper {
            width: 100%;
        }

        .filter-group {
            width: 100%;
            flex-direction: column;
        }

        .filter-select {
            width: 100%;
        }

        .stats-section {
            grid-template-columns: repeat(2, 1fr);
        }

        .link-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .link-status {
            width: 100%;
            justify-content: flex-start;
        }

        .link-meta {
            gap: 1rem;
        }

        .link-actions {
            width: 100%;
        }

        .btn-link-action {
            flex: 1;
        }
    }

    @media (max-width: 576px) {
        .modal-dialog {
            margin: 0.5rem;
        }

        .invite-modal-header {
            padding: 1rem !important;
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-icon {
            width: 40px;
            height: 40px;
        }

        .modal-title {
            font-size: 1.25rem;
        }

        .header-subtitle {
            font-size: 0.8125rem;
        }

        .invite-modal-body {
            padding: 1rem !important;
        }

        .card-premium {
            padding: 1rem;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .stats-section {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .toast-notification {
            min-width: auto;
            width: calc(100vw - 2rem);
            margin: 0 1rem;
        }

        .toast-container {
            right: 0;
            left: 0;
        }
    }

    /* ========== ACESSIBILIDADE ========== */
    .btn-premium:focus-visible,
    .btn-link-action:focus-visible,
    .btn-icon:focus-visible,
    .form-control:focus-visible,
    .form-select-premium:focus-visible,
    .filter-select:focus-visible,
    .search-input:focus-visible {
        outline: 2px solid var(--modal-accent);
        outline-offset: 2px;
    }

    @media (prefers-reduced-motion: reduce) {

        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    /* ========== PRINT ========== */
    @media print {
        .invite-modal-premium {
            box-shadow: none;
            border: 1px solid #ccc;
        }

        .parallax-layers,
        .btn-icon,
        .header-actions,
        .link-actions,
        .invite-modal-footer,
        .toast-container {
            display: none !important;
        }
    }
</style>

.stats-section {
    grid-template-columns: 1fr;
}

.stat-card {
    padding: 1rem;
}

.stat-value {
    font-size: 1.5rem;
}

.toast-notification {
    min-width: auto;
    width: calc(100vw - 2rem);
    margin: 0 1rem;
}

.toast-container {
    right: 0;
    left: 0;
}
}
/* ========== ACESSIBILIDADE ========== */
.btn-premium:focus-visible,
.btn-link-action:focus-visible,
.btn-icon:focus-visible,
.form-control:focus-visible,
.form-select-premium:focus-visible,
.filter-select:focus-visible,
.search-input:focus-visible {
outline: 2px solid var(--modal-accent);
outline-offset: 2px;
}
@media (prefers-reduced-motion: reduce) {
*,
*::before,
*::after {
animation-duration: 0.01ms !important;
animation-iteration-count: 1 !important;
transition-duration: 0.01ms !important;
}
}
/* ========== PRINT ========== */
@media print {
.invite-modal-premium {
box-shadow: none;
border: 1px solid #ccc;
}
.parallax-layers,
.btn-icon,
.header-actions,
.link-actions,
.invite-modal-footer,
.toast-container {
    display: none !important;
}
}
</style>
<!-- ========== JAVASCRIPT FUNCIONAL COMPLETO ========== -->
<script>
(function() {
    'use strict';

    // ========== CONFIGURAÇÃO E VARIÁVEIS GLOBAIS ==========
    const SALA_ID = "{{ $sala->id ?? '' }}";
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    // URLs das rotas
    const ROUTES = {
        create: `/salas/${SALA_ID}/links-convite`,
        list: `/salas/${SALA_ID}/links-convite`,
        revoke: (linkId) => `/salas/${SALA_ID}/links-convite/${linkId}`,
    };

    // Elementos do DOM
    const elements = {
        modal: document.getElementById('inviteLinksModal'),
        createForm: document.getElementById('createInviteLinkForm'),
        createAlert: document.getElementById('createLinkAlert'),
        linksContainer: document.getElementById('inviteLinksContainer'),
        searchInput: document.getElementById('inviteSearchInput'),
        searchClearBtn: document.getElementById('searchClearBtn'),
        filterStatus: document.getElementById('inviteFilterStatus'),
        filterSort: document.getElementById('inviteFilterSort'),
        linksCountBadge: document.getElementById('linksCountBadge'),
        toastContainer: document.getElementById('inviteToastContainer'),
        btnBulkRevoke: document.getElementById('btnBulkRevoke'),
        // Stats
        statTotalLinks: document.getElementById('statTotalLinks'),
        statActiveLinks: document.getElementById('statActiveLinks'),
        statExpiredLinks: document.getElementById('statExpiredLinks'),
        statTotalUses: document.getElementById('statTotalUses'),
    };

    // Estado da aplicação
    let state = {
        links: [],
        filteredLinks: [],
        isLoading: false,
    };

    // ========== INICIALIZAÇÃO ==========
    function init() {
        console.log('[Invite Links Premium] 🚀 Inicializando...');
        
        if (!elements.modal) {
            console.error('[Invite Links Premium] ❌ Modal não encontrado!');
            return;
        }

        setupEventListeners();
        setupParallax();
        setupModalEvents();
        setupSelectAnimations();
        
        console.log('[Invite Links Premium] ✅ Inicializado com sucesso!');
    }

    // ========== EVENT LISTENERS ==========
    function setupEventListeners() {
        // Form de criação
        if (elements.createForm) {
            elements.createForm.addEventListener('submit', handleCreateLink);
        }

        // Busca
        if (elements.searchInput) {
            elements.searchInput.addEventListener('input', debounce(handleSearch, 300));
        }

        if (elements.searchClearBtn) {
            elements.searchClearBtn.addEventListener('click', handleClearSearch);
        }

        // Filtros
        if (elements.filterStatus) {
            elements.filterStatus.addEventListener('change', handleFilterChange);
        }

        if (elements.filterSort) {
            elements.filterSort.addEventListener('change', handleFilterChange);
        }

        // Bulk revoke
        if (elements.btnBulkRevoke) {
            elements.btnBulkRevoke.addEventListener('click', handleBulkRevoke);
        }

        // Alert close
        const alertClose = elements.createAlert?.querySelector('.alert-close');
        if (alertClose) {
            alertClose.addEventListener('click', () => {
                elements.createAlert.classList.add('d-none');
            });
        }
    }

    // ========== MODAL EVENTS ==========
    function setupModalEvents() {
        if (!elements.modal) return;

        // Quando o modal é aberto
        elements.modal.addEventListener('show.bs.modal', function() {
            console.log('[Invite Links Premium] 📂 Modal abrindo...');
        });

        elements.modal.addEventListener('shown.bs.modal', function() {
            console.log('[Invite Links Premium] ✅ Modal aberto, carregando dados...');
            loadLinks();
        });

        // Quando o modal é fechado
        elements.modal.addEventListener('hide.bs.modal', function() {
            console.log('[Invite Links Premium] 🚪 Modal fechando...');
        });

        elements.modal.addEventListener('hidden.bs.modal', function() {
            console.log('[Invite Links Premium] 🔒 Modal fechado');
            cleanupModal();
        });
    }

    function setupSelectAnimations() {
    const selects = document.querySelectorAll('.form-select-premium, .filter-select');
    
    selects.forEach(select => {
        // Criar wrapper se não existir
        if (!select.parentElement.classList.contains('select-wrapper')) {
            const wrapper = document.createElement('div');
            wrapper.className = 'select-wrapper';
            select.parentNode.insertBefore(wrapper, select);
            wrapper.appendChild(select);
        }
        
        const wrapper = select.parentElement;
        
        // Adicionar classe quando abrir
        select.addEventListener('focus', function() {
            wrapper.classList.add('active');
        });
        
        // Remover classe quando fechar
        select.addEventListener('blur', function() {
            wrapper.classList.remove('active');
        });
        
        // Controlar com click/mousedown
        select.addEventListener('mousedown', function() {
            if (!wrapper.classList.contains('active')) {
                wrapper.classList.add('active');
            }
        });
        
        // Remover ao selecionar opção (change event)
        select.addEventListener('change', function() {
            setTimeout(() => {
                wrapper.classList.remove('active');
            }, 200);
        });
    });
}

    // ========== PARALLAX 3D EFFECT ==========
    function setupParallax() {
        const container = elements.modal?.querySelector('[data-parallax-container]');
        if (!container) return;

        const layers = container.querySelectorAll('[data-parallax-depth]');
        
        container.addEventListener('mousemove', function(e) {
            const rect = container.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;

            layers.forEach(layer => {
                const depth = parseFloat(layer.getAttribute('data-parallax-depth')) || 0;
                const moveX = x * depth * 50;
                const moveY = y * depth * 50;
                layer.style.transform = `translate3d(${moveX}px, ${moveY}px, 0)`;
            });
        });

        container.addEventListener('mouseleave', function() {
            layers.forEach(layer => {
                layer.style.transform = 'translate3d(0, 0, 0)';
            });
        });
    }

    // ========== CRIAR NOVO LINK ==========
    async function handleCreateLink(e) {
        e.preventDefault();

        const submitBtn = elements.createForm.querySelector('button[type="submit"]');
        const formData = new FormData(elements.createForm);
        
        const data = {
            validade: formData.get('validade'),
            max_usos: formData.get('max_usos') ? parseInt(formData.get('max_usos')) : null,
        };

        console.log('[Invite Links Premium] 📝 Criando link:', data);

        // Loading state
        submitBtn.setAttribute('data-loading', 'true');
        submitBtn.disabled = true;

        try {
            const response = await fetch(ROUTES.create, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();

            if (result.success) {
    showAlert('success', 'Link Criado!', result.message || 'Link de convite criado com sucesso!');
    elements.createForm.reset();
    await loadLinks();
    
    // Copiar URL automaticamente com validação
    if (result.link && result.link.url && result.link.url.trim() !== '') {
        const copied = await copyToClipboard(result.link.url);
        if (copied) {
            showToast('success', 'Sucesso!', 'Link criado e copiado para área de transferência.');
        } else {
            showToast('warning', 'Link Criado', 'Link criado com sucesso! Clique em "Copiar Link" para copiar manualmente.');
        }
    } else {
        showToast('success', 'Sucesso!', 'Link criado com sucesso!');
    }
} else {
    showAlert('danger', 'Erro', result.message || 'Erro ao criar link de convite.');
    showToast('error', 'Erro', result.message || 'Não foi possível criar o link.');
}
} catch (error) {
    console.error('[Invite Links Premium] ❌ Erro ao criar:', error);
    showAlert('danger', 'Erro', 'Erro de conexão ao criar link.');
    showToast('error', 'Erro', 'Falha na comunicação com o servidor.');
} finally {
    submitBtn.setAttribute('data-loading', 'false');
    submitBtn.disabled = false;
}
    }

    // ========== CARREGAR LINKS ==========
    async function loadLinks() {
        console.log('[Invite Links Premium] 📥 Carregando links...');

        state.isLoading = true;
        showLoadingState();

        try {
            const response = await fetch(ROUTES.list, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            const result = await response.json();

            if (result.success && result.links) {
                state.links = result.links;
                state.filteredLinks = result.links;
                updateStats();
                renderLinks();
                console.log('[Invite Links Premium] ✅ Links carregados:', result.links.length);
            } else {
                throw new Error(result.message || 'Erro ao carregar links');
            }
        } catch (error) {
            console.error('[Invite Links Premium] ❌ Erro ao carregar:', error);
            showErrorState(error.message);
            showToast('error', 'Erro', 'Não foi possível carregar os links.');
        } finally {
            state.isLoading = false;
        }
    }

    // ========== RENDERIZAR LINKS ==========
    function renderLinks() {
        if (!elements.linksContainer) return;

        const links = state.filteredLinks;

        if (links.length === 0) {
            showEmptyState();
            return;
        }

        const html = links.map(link => createLinkItemHTML(link)).join('');
        elements.linksContainer.innerHTML = html;

        // Bind events aos botões
        bindLinkActions();
    }

    // ========== CRIAR HTML DO LINK ITEM ==========
    function createLinkItemHTML(link) {
        const isExpired = !link.esta_valido;
        const code = link.code || '';
        const badge = code.substring(0, 3).toUpperCase();
        const statusClass = isExpired ? 'expired' : 'active';
        const statusText = isExpired ? 'Expirado' : 'Ativo';

        return `
            <div class="link-item ${isExpired ? 'expired' : ''}" data-link-id="${link.id}">
        <div class="link-header">
            <div class="link-code-wrapper">
                <div class="link-code">
                    <div class="link-badge">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                        </svg>
                    </div>
                            <div class="link-code-text">${escapeHtml(code)}</div>
                        </div>
                        <div class="link-url" id="link-url-${link.id}">
                            ${escapeHtml(link.url || '')}
                        </div>
                    </div>
                    <div class="link-status">
                        <span class="status-badge ${statusClass}">
                            <span class="status-dot"></span>
                            ${statusText}
                        </span>
                    </div>
                </div>

                <div class="link-meta">
                    <div class="meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span>Criado por: <span class="meta-value">${escapeHtml(link.criador || 'Desconhecido')}</span></span>
                    </div>
                    <div class="meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span>${escapeHtml(link.validade || 'Sem validade')}</span>
                    </div>
                    <div class="meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span><span class="meta-value">${link.usos_atual || 0}</span>${link.max_usos ? ' / ' + link.max_usos : ''} usos</span>
                    </div>
                </div>

                <div class="link-actions">
                    <button type="button" class="btn-link-action btn-copy" data-action="copy" data-link-id="${link.id}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                        </svg>
                        Copiar Link
                    </button>
                    <button type="button" class="btn-link-action btn-revoke" data-action="revoke" data-link-id="${link.id}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        Revogar
                    </button>
                </div>
            </div>
        `;
    }

    // ========== BIND ACTIONS DOS LINKS ==========
    function bindLinkActions() {
        const copyButtons = elements.linksContainer.querySelectorAll('[data-action="copy"]');
        const revokeButtons = elements.linksContainer.querySelectorAll('[data-action="revoke"]');

        copyButtons.forEach(btn => {
            btn.addEventListener('click', handleCopyLink);
        });

        revokeButtons.forEach(btn => {
            btn.addEventListener('click', handleRevokeLink);
        });
    }

    // ========== COPIAR LINK ==========
    // ========== COPIAR LINK ==========
async function handleCopyLink(e) {
    const btn = e.currentTarget;
    const linkId = btn.getAttribute('data-link-id');
    
    // Buscar o link diretamente no state
    const link = state.links.find(l => l.id == linkId);
    
    if (!link || !link.url) {
        console.error('[Invite Links Premium] ❌ Link não encontrado:', linkId);
        showToast('error', 'Erro', 'Não foi possível encontrar o link.');
        return;
    }

    const text = link.url.trim();
    
    console.log('[Invite Links Premium] 📋 Copiando:', text);
    
    // Encontrar o container do link
    const linkItem = btn.closest('.link-item');
    
    // Guardar HTML original
    const originalHTML = btn.innerHTML;
    const originalBg = btn.style.background;
    const originalColor = btn.style.color;
    const originalBorder = btn.style.borderColor;
    
    // Funções de feedback
    function showSuccess() {
        btn.innerHTML = `
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            <span>Copiado!</span>
        `;
        btn.style.background = 'rgba(16, 185, 129, 0.2)';
        btn.style.color = 'var(--modal-success)';
        btn.style.borderColor = 'var(--modal-success)';
        btn.disabled = true;
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.style.background = originalBg;
            btn.style.color = originalColor;
            btn.style.borderColor = originalBorder;
            btn.disabled = false;
        }, 2000);
        
        showToast('success', 'Copiado!', 'Link copiado para área de transferência.');
    }
    
    function showError() {
        btn.innerHTML = `
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
            <span>Erro</span>
        `;
        btn.style.background = 'rgba(239, 68, 68, 0.2)';
        btn.style.color = 'var(--modal-danger)';
        btn.style.borderColor = 'var(--modal-danger)';
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.style.background = originalBg;
            btn.style.color = originalColor;
            btn.style.borderColor = originalBorder;
        }, 2000);
    }

    // Tentar Clipboard API primeiro
    if (navigator.clipboard && navigator.clipboard.writeText) {
        try {
            await navigator.clipboard.writeText(text);
            console.log('[Invite Links Premium] ✅ Copiado com Clipboard API');
            showSuccess();
            return;
        } catch (err) {
            console.warn('[Invite Links Premium] ⚠️ Clipboard API falhou:', err);
        }
    }

    // Fallback com textarea
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.left = '-9999px';
    textarea.style.top = '0';
    textarea.style.opacity = '0';
    textarea.setAttribute('readonly', '');

    // Adicionar no container do link ou no body
    const container = linkItem || document.body;
    container.appendChild(textarea);

    textarea.focus();
    textarea.select();

    let success = false;
    try {
        success = document.execCommand('copy');
        console.log('[Invite Links Premium] execCommand result:', success);
    } catch (err) {
        console.error('[Invite Links Premium] ❌ Erro no execCommand:', err);
    } finally {
        // Aguardar um momento antes de remover
        setTimeout(() => {
            if (container.contains(textarea)) {
                container.removeChild(textarea);
            }
        }, 100);
    }

    if (success) {
        console.log('[Invite Links Premium] ✅ Copiado com fallback');
        showSuccess();
    } else {
        console.error('[Invite Links Premium] ❌ Falha ao copiar');
        showError();
        prompt('Copie manualmente (Ctrl+C):', text);
    }
}

    // ========== REVOGAR LINK ==========
    async function handleRevokeLink(e) {
        const btn = e.currentTarget;
        const linkId = btn.getAttribute('data-link-id');

        if (!confirm('Tem certeza que deseja revogar este link? Esta ação não pode ser desfeita.')) {
            return;
        }

        console.log('[Invite Links Premium] 🗑️ Revogando link:', linkId);

        btn.disabled = true;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = `
            <span class="spinner"></span>
            Revogando...
        `;

        try {
            const response = await fetch(ROUTES.revoke(linkId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
            });

            const result = await response.json();

            if (result.success) {
                showToast('success', 'Link Revogado', 'Link removido com sucesso.');
                await loadLinks();
            } else {
                throw new Error(result.message || 'Erro ao revogar link');
            }
        } catch (error) {
            console.error('[Invite Links Premium] ❌ Erro ao revogar:', error);
            showToast('error', 'Erro', error.message || 'Não foi possível revogar o link.');
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    }

    // ========== BULK REVOKE ==========
    async function handleBulkRevoke() {
        const expiredLinks = state.links.filter(link => !link.esta_valido);

        if (expiredLinks.length === 0) {
            showToast('info', 'Nenhum Link', 'Não há links expirados para revogar.');
            return;
        }

        if (!confirm(`Deseja revogar ${expiredLinks.length} link(s) expirado(s)?`)) {
            return;
        }

        console.log('[Invite Links Premium] 🗑️ Bulk revoke:', expiredLinks.length);

        const btn = elements.btnBulkRevoke;
        btn.disabled = true;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = `
            <span class="spinner"></span>
            <span>Revogando...</span>
        `;

        let successCount = 0;
        let errorCount = 0;

        for (const link of expiredLinks) {
            try {
                const response = await fetch(ROUTES.revoke(link.id), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                });

                const result = await response.json();
                if (result.success) {
                    successCount++;
                } else {
                    errorCount++;
                }
            } catch (error) {
                console.error('[Invite Links Premium] ❌ Erro no bulk revoke:', error);
                errorCount++;
            }
        }

        btn.disabled = false;
        btn.innerHTML = originalHTML;

        if (successCount > 0) {
            showToast('success', 'Concluído', `${successCount} link(s) revogado(s) com sucesso.`);
            await loadLinks();
        }

        if (errorCount > 0) {
            showToast('warning', 'Atenção', `${errorCount} link(s) falharam ao revogar.`);
        }
    }

    // ========== BUSCA ==========
    function handleSearch() {
        const query = elements.searchInput.value.toLowerCase().trim();

        if (query) {
            elements.searchClearBtn.style.display = 'block';
        } else {
            elements.searchClearBtn.style.display = 'none';
        }

        applyFilters();
    }

    function handleClearSearch() {
        elements.searchInput.value = '';
        elements.searchClearBtn.style.display = 'none';
        applyFilters();
    }

    // ========== FILTROS ==========
    function handleFilterChange() {
        applyFilters();
    }

    function applyFilters() {
        const query = elements.searchInput.value.toLowerCase().trim();
        const statusFilter = elements.filterStatus.value;
        const sortFilter = elements.filterSort.value;

        let filtered = [...state.links];

        // Filtro de busca
        if (query) {
            filtered = filtered.filter(link => {
                const code = (link.code || '').toLowerCase();
                const criador = (link.criador || '').toLowerCase();
                const validade = (link.validade || '').toLowerCase();
                
                return code.includes(query) || 
                       criador.includes(query) || 
                       validade.includes(query);
            });
        }

        // Filtro de status
        if (statusFilter === 'active') {
            filtered = filtered.filter(link => link.esta_valido);
        } else if (statusFilter === 'expired') {
            filtered = filtered.filter(link => !link.esta_valido);
        }

        // Ordenação
        if (sortFilter === 'newest') {
            filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        } else if (sortFilter === 'oldest') {
            filtered.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
        } else if (sortFilter === 'most_used') {
            filtered.sort((a, b) => (b.usos_atual || 0) - (a.usos_atual || 0));
        }

        state.filteredLinks = filtered;
        renderLinks();
    }

    // ========== ESTATÍSTICAS ==========
    function updateStats() {
        const total = state.links.length;
        const active = state.links.filter(link => link.esta_valido).length;
        const expired = total - active;
        const totalUses = state.links.reduce((sum, link) => sum + (link.usos_atual || 0), 0);

        if (elements.statTotalLinks) elements.statTotalLinks.textContent = total;
        if (elements.statActiveLinks) elements.statActiveLinks.textContent = active;
        if (elements.statExpiredLinks) elements.statExpiredLinks.textContent = expired;
        if (elements.statTotalUses) elements.statTotalUses.textContent = totalUses;

        if (elements.linksCountBadge) {
            elements.linksCountBadge.textContent = `${total} link${total !== 1 ? 's' : ''}`;
        }
    }

    // ========== STATES ==========
    function showLoadingState() {
        if (!elements.linksContainer) return;

        elements.linksContainer.innerHTML = `
            <div class="loading-state">
                <div class="loading-spinner">
                    <div class="spinner-ring"></div>
                    <div class="spinner-ring"></div>
                    <div class="spinner-ring"></div>
                </div>
                <p class="loading-text">Carregando links...</p>
            </div>
        `;
    }

    function showEmptyState() {
        if (!elements.linksContainer) return;

        elements.linksContainer.innerHTML = `
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                </div>
                <h6 class="empty-title">Nenhum link encontrado</h6>
                <p class="empty-description">
                    ${state.links.length === 0 
                        ? 'Crie seu primeiro link de convite usando o formulário acima.' 
                        : 'Tente ajustar os filtros ou a busca.'}
                </p>
            </div>
        `;
    }

    function showErrorState(message) {
        if (!elements.linksContainer) return;

        elements.linksContainer.innerHTML = `
            <div class="empty-state">
                <div class="empty-icon" style="border-color: rgba(239, 68, 68, 0.3); background: rgba(239, 68, 68, 0.1);">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
                <h6 class="empty-title">Erro ao carregar links</h6>
                <p class="empty-description">${escapeHtml(message)}</p>
            </div>
        `;
    }

    // ========== ALERT SYSTEM ==========
    function showAlert(type, title, message) {
        if (!elements.createAlert) return;

        const alertTypes = {
            success: 'alert-success',
            danger: 'alert-danger',
            warning: 'alert-warning',
            info: 'alert-info',
        };

        elements.createAlert.className = `alert-premium ${alertTypes[type] || 'alert-info'}`;
        
        const titleEl = elements.createAlert.querySelector('.alert-title');
        const messageEl = elements.createAlert.querySelector('.alert-message');
        
        if (titleEl) titleEl.textContent = title;
        if (messageEl) messageEl.textContent = message;
        
        elements.createAlert.classList.remove('d-none');

        // Auto hide após 5s
        setTimeout(() => {
            elements.createAlert.classList.add('d-none');
        }, 5000);
    }

    // ========== TOAST SYSTEM ==========
    function showToast(type, title, message, duration = 5000) {
        if (!elements.toastContainer) return;

        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ',
        };

        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <div class="toast-icon">${icons[type] || 'ℹ'}</div>
                <div class="toast-body">
                    <div class="toast-title">${escapeHtml(title)}</div>
                    <div class="toast-message">${escapeHtml(message)}</div>
                </div>
                <button type="button" class="toast-close">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div class="toast-progress">
                <div class="toast-progress-bar" style="color: currentColor;"></div>
            </div>
        `;

        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => removeToast(toast));

        elements.toastContainer.appendChild(toast);

        // Auto remove
        setTimeout(() => removeToast(toast), duration);
    }

    function removeToast(toast) {
        toast.classList.add('toast-exit');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    // ========== UTILITIES ==========
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // ========== CLEANUP MODAL ==========
    function cleanupModal() {
        console.log('[Invite Links Premium] 🧹 Limpando modal...');
        
        if (!elements.modal) return;
        
        // Remover todos os backdrops
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => {
            console.log('[Invite Links Premium] 🗑️ Removendo backdrop');
            backdrop.remove();
        });
        
        // Limpar classes e estilos do body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        // Garantir que o modal está escondido
        elements.modal.classList.remove('show');
        elements.modal.style.display = 'none';
        elements.modal.setAttribute('aria-hidden', 'true');
        elements.modal.removeAttribute('aria-modal');
        
        console.log('[Invite Links Premium] ✅ Modal limpo');
    }

    // ========== FUNÇÕES GLOBAIS EXPOSTAS ==========
    window.loadInviteLinks = loadLinks;

    // ========== INICIALIZAÇÃO ==========
    init();

    console.log('[Invite Links Premium] ✅ Sistema totalmente inicializado!');
    console.log('[Invite Links Premium] 📊 Estatísticas:', {
        salaId: SALA_ID,
        elementosEncontrados: Object.keys(elements).filter(k => elements[k]).length,
        totalElementos: Object.keys(elements).length
    });

})();
</script>

<!-- ========== GARANTIA DE COMPATIBILIDADE COM SHOW.BLADE.PHP ========== -->
<script>
// ========== CORREÇÃO DE CONFLITOS DE MODAL ==========
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 [Compatibility] Aplicando correções de compatibilidade...');
    
    // Garantir que o modal de links está no body
    const inviteModal = document.getElementById('inviteLinksModal');
    
    if (inviteModal && inviteModal.parentElement !== document.body) {
        console.log('📦 Movendo modal de links para o body...');
        document.body.appendChild(inviteModal);
    }
    
    // Prevenir conflitos com outros modais
    const allModals = document.querySelectorAll('.modal');
    allModals.forEach(modal => {
        modal.addEventListener('show.bs.modal', function(e) {
            // Fechar outros modais quando um for aberto
            allModals.forEach(otherModal => {
                if (otherModal !== modal && otherModal.classList.contains('show')) {
                    const instance = bootstrap.Modal.getInstance(otherModal);
                    if (instance) instance.hide();
                }
            });
        });
        
        modal.addEventListener('hidden.bs.modal', function() {
            // Limpar backdrops órfãos
            const backdrops = document.querySelectorAll('.modal-backdrop');
            const visibleModals = document.querySelectorAll('.modal.show');
            
            if (visibleModals.length === 0) {
                backdrops.forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }
        });
    });
    
    console.log('✅ [Compatibility] Correções aplicadas com sucesso!');
});

// ========== PREVENIR MÚLTIPLOS BACKDROPS ==========
(function() {
    const observer = new MutationObserver(function(mutations) {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        
        if (backdrops.length > 1) {
            console.warn('⚠️ Múltiplos backdrops detectados! Limpando...');
            
            // Manter apenas o último backdrop
            for (let i = 0; i < backdrops.length - 1; i++) {
                backdrops[i].remove();
            }
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: false
    });
})();

// ========== INTEGRAÇÃO COM ECHO/WEBSOCKET ==========
if (window.Echo && typeof SALA_ID !== 'undefined') {
    console.log('🔗 [Invite Links] Conectando ao canal de links...');
    
    window.Echo.private(`sala.${SALA_ID}.links`)
        .listen('.link.created', function(data) {
            console.log('🔗 Novo link criado:', data);
            
            if (typeof window.loadInviteLinks === 'function') {
                window.loadInviteLinks();
            }
            
            // Toast notification
            if (typeof window.showToast === 'function') {
                window.showToast('info', 'Novo Link', 'Um novo link de convite foi criado.');
            }
        })
        .listen('.link.revoked', function(data) {
            console.log('🗑️ Link revogado:', data);
            
            if (typeof window.loadInviteLinks === 'function') {
                window.loadInviteLinks();
            }
            
            // Toast notification
            if (typeof window.showToast === 'function') {
                window.showToast('warning', 'Link Revogado', 'Um link de convite foi revogado.');
            }
        })
        .listen('.link.used', function(data) {
            console.log('✅ Link usado:', data);
            
            if (typeof window.loadInviteLinks === 'function') {
                window.loadInviteLinks();
            }
        });
}

// ========== ATALHOS DE TECLADO ==========
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('inviteLinksModal');
    
    if (!modal || !modal.classList.contains('show')) return;
    
    // ESC para fechar
    if (e.key === 'Escape') {
        const instance = bootstrap.Modal.getInstance(modal);
        if (instance) instance.hide();
    }
    
    // Ctrl/Cmd + R para recarregar links
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        if (typeof window.loadInviteLinks === 'function') {
            window.loadInviteLinks();
        }
    }
    
    // Ctrl/Cmd + N para criar novo link
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        const createForm = document.getElementById('createInviteLinkForm');
        if (createForm) {
            const firstInput = createForm.querySelector('select, input');
            if (firstInput) firstInput.focus();
        }
    }
});

// ========== VISIBILIDADE DA PÁGINA ==========
document.addEventListener('visibilitychange', function() {
    const modal = document.getElementById('inviteLinksModal');
    
    if (!document.hidden && modal && modal.classList.contains('show')) {
        console.log('👁️ Página visível novamente, atualizando links...');
        
        if (typeof window.loadInviteLinks === 'function') {
            window.loadInviteLinks();
        }
    }
});

// ========== AUTO-REFRESH DE LINKS (OPCIONAL) ==========
(function() {
    let refreshInterval = null;
    
    const modal = document.getElementById('inviteLinksModal');
    
    if (!modal) return;
    
    modal.addEventListener('shown.bs.modal', function() {
        // Atualizar links a cada 30 segundos quando modal estiver aberto
        refreshInterval = setInterval(function() {
            if (typeof window.loadInviteLinks === 'function') {
                console.log('🔄 Auto-refresh de links...');
                window.loadInviteLinks();
            }
        }, 1000000); // 100 segundos
    });
    
    modal.addEventListener('hidden.bs.modal', function() {
        // Limpar intervalo quando modal fechar
        if (refreshInterval) {
            clearInterval(refreshInterval);
            refreshInterval = null;
        }
    });
})();

console.log('✅ [Invite Links] Sistema de compatibilidade carregado!');
</script>

<!-- ========== ESTILOS ADICIONAIS DE COMPATIBILIDADE ========== -->
<style>
/* Garantir z-index correto */
.modal-backdrop {
    z-index: 1040;
}

.modal {
    z-index: 1050;
}

#inviteLinksModal {
    z-index: 1055 !important;
}

/* Prevenir scroll duplo */
body.modal-open {
    overflow: hidden !important;
}

/* Animações suaves */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
}

/* Loading states melhorados */
.loading-state,
.empty-state {
    min-height: 200px;
}

/* Responsividade melhorada */
@media (max-width: 768px) {
    #inviteLinksModal .modal-dialog {
        margin: 0.5rem;
        max-width: calc(100% - 1rem);
    }
    
    #inviteLinksModal .modal-body {
        padding: 1rem !important;
    }
    
    .link-item {
        padding: 0.875rem;
    }
    
    .link-actions {
        flex-direction: column;
    }
    
    .btn-link-action {
        width: 100%;
    }
}

/* Melhorias de acessibilidade */
.btn-link-action:focus-visible,
.btn-premium:focus-visible,
.filter-select:focus-visible,
.search-input:focus-visible {
    outline: 2px solid var(--modal-accent, #22c55e);
    outline-offset: 2px;
}

/* Estados de hover melhorados */
.link-item:hover {
    transform: translateX(4px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Indicador de carregamento */
.btn-premium[data-loading="true"] {
    pointer-events: none;
    opacity: 0.7;
}
</style>