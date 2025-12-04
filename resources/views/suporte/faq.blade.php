<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central de Ajuda FAQ - RPG System</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" relstylesheet>
    <style>
        /* VARIÁVEIS FUNDAMENTOS - Idênticos ao Dashboard */
        :root {
            --bg-dark: #0a0f14;
            --card: #1f2a33;
            --muted: #8b9ba8;
            --accent: #22c55e;
            --accent-light: #16a34a;
            --accent-dark: #15803d;
            --hero-green: #052e16;
            --text-on-primary: #e6eef6;
            --transition-speed: 600ms;
            --header-bg: rgba(10, 15, 20, 0.75);
            --btn-gradient-start: #22c55e;
            --btn-gradient-end: #16a34a;
            --accent-border: rgba(34, 197, 94, 0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(145deg, #0a0f14f4, #141c23f2);
            color: var(--text-on-primary);
            -webkit-font-smoothing: antialiased;
            line-height: 1.5;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }


          .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--muted);
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 24px;
        transition: all 0.2s;
    }

    .back-link:hover {
        color: var(--accent);
        transform: translateX(-5px);
    }

        /* HEADER - Mesmo estilo do Dashboard */
        .page-header {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            margin-bottom: 30px;
            animation: slideDown 0.5s ease;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-content {
            text-align: center;
        }

        .header-content h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 36px;
            color: #fff;
            margin-bottom: 12px;
            font-weight: 900;
            letter-spacing: -0.5px;
        }

        .header-subtitle {
            color: var(--muted);
            font-size: 16px;
            margin-bottom: 32px;
        }

        /* Search Box */
        .search-wrapper {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }

        .search-input {
            width: 100%;
            padding: 16px 20px 16px 50px;
            background: rgba(10, 15, 20, 0.6);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 12px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-input::placeholder {
            color: var(--muted);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
            transform: translateY(-2px);
        }

        .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            transition: color 0.3s ease;
        }

        .search-input:focus + .search-icon {
            color: var(--accent);
        }

        /* ============================================
   SCROLLBAR PERSONALIZADA - IDÊNTICA AO SHOW.BLADE.PHP
   ============================================ */

::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: var(--bg-dark);
}

::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.3);
    border-radius: 5px;
    transition: background 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(34, 197, 94, 0.5);
}

/* Scrollbar para modais */
.modal-body::-webkit-scrollbar,
.rpg-modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track,
.rpg-modal-body::-webkit-scrollbar-track {
    background: rgba(17, 24, 39, 0.6);
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb,
.rpg-modal-body::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.3);
    border-radius: 4px;
    transition: background 0.3s ease;
}

.modal-body::-webkit-scrollbar-thumb:hover,
.rpg-modal-body::-webkit-scrollbar-thumb:hover {
    background: rgba(34, 197, 94, 0.5);
}

/* Scrollbar para listas */
.notification-list::-webkit-scrollbar {
    width: 6px;
}

.notification-list::-webkit-scrollbar-track {
    background: rgba(17, 24, 39, 0.4);
    border-radius: 3px;
}

.notification-list::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.3);
    border-radius: 3px;
    transition: background 0.3s ease;
}

.notification-list::-webkit-scrollbar-thumb:hover {
    background: rgba(34, 197, 94, 0.5);
}

        /* CATEGORY TABS - Estilo dos botões do Dashboard */
        .category-tabs {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 30px;
            animation: fadeInUp 0.5s ease 0.2s backwards;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .tab-btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: 1px solid rgba(34, 197, 94, 0.3);
            background: rgba(34, 197, 94, 0.1);
            color: var(--accent);
        }

        .tab-btn:hover {
            background: rgba(34, 197, 94, 0.2);
            transform: translateY(-2px);
            border-color: var(--accent);
        }

        .tab-btn.active {
            background: linear-gradient(to right, var(--btn-gradient-start), var(--btn-gradient-end));
            color: var(--hero-green);
            border: none;
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
        }

        /* STATS GRID - Cards informativos do topo */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 22px;
            margin-bottom: 30px;
            animation: fadeInUp 0.5s ease 0.3s backwards;
        }

        .stat-card {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            border: 1px solid rgba(34, 197, 94, 0.1);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
            border-color: rgba(34, 197, 94, 0.3);
        }

        .stat-card.border-green { border-left: 4px solid #22c55e; }
        .stat-card.border-blue { border-left: 4px solid #3b82f6; }
        .stat-card.border-purple { border-left: 4px solid #a855f7; }
        .stat-card.border-yellow { border-left: 4px solid #f97316; }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .stat-info h3 {
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            line-height: 1;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon.green {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.1));
            color: #22c55e;
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.1));
            color: #3b82f6;
        }

        .stat-icon.purple {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.2), rgba(147, 51, 234, 0.1));
            color: #a855f7;
        }

        .stat-icon.yellow {
            background: linear-gradient(135deg, rgba(249, 115, 22, 0.2), rgba(234, 88, 12, 0.1));
            color: #f97316;
        }

        .stat-icon svg {
            width: 24px;
            height: 24px;
        }

        /* FAQ ACCORDION - Cards do Dashboard adaptados */
        .faq-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .faq-item {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            border-radius: 16px;
            border: 1px solid rgba(34, 197, 94, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            animation: fadeInUp 0.5s ease backwards;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .faq-item:nth-child(1) { animation-delay: 0.4s; }
        .faq-item:nth-child(2) { animation-delay: 0.5s; }
        .faq-item:nth-child(3) { animation-delay: 0.6s; }
        .faq-item:nth-child(4) { animation-delay: 0.7s; }
        .faq-item:nth-child(5) { animation-delay: 0.8s; }
        .faq-item:nth-child(6) { animation-delay: 0.9s; }

        .faq-item:hover {
            transform: translateY(-2px);
            border-color: rgba(34, 197, 94, 0.3);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
        }

        .faq-item.active {
            border-left: 4px solid var(--accent);
            border-color: rgba(34, 197, 94, 0.4);
            box-shadow: 0 4px 25px rgba(34, 197, 94, 0.15);
        }

        .faq-question {
            width: 100%;
            padding: 20px 24px;
            background: transparent;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            text-align: left;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background: rgba(34, 197, 94, 0.05);
        }

        .question-content {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
        }

        .question-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.05));
            color: var(--accent);
            transition: all 0.3s ease;
        }

        .faq-item.active .question-icon {
            background: linear-gradient(135deg, var(--btn-gradient-start), var(--btn-gradient-end));
            color: var(--hero-green);
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.4);
        }

        .question-icon svg {
            width: 24px;
            height: 24px;
        }

        .question-text {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
        }

        .toggle-icon {
            color: var(--muted);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            flex-shrink: 0;
        }

        .faq-item.active .toggle-icon {
            transform: rotate(180deg);
            color: var(--accent);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0, 1, 0, 1);
            background: rgba(0, 0, 0, 0.2);
        }

        .answer-content {
            padding: 0 24px 24px 88px;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.7;
        }

        /* CODE BLOCKS */
        .code-block {
            background: #0d1117;
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-top: 16px;
            position: relative;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #e6edf3;
            overflow-x: auto;
        }

        .code-block pre {
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .copy-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 8px;
            color: var(--accent);
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .copy-btn:hover {
            background: linear-gradient(to right, var(--btn-gradient-start), var(--btn-gradient-end));
            color: var(--hero-green);
            border: none;
            transform: translateY(-2px);
        }

        /* NO RESULTS STATE */
        .no-results {
            text-align: center;
            padding: 60px 40px;
            color: var(--muted);
            display: none;
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            border-radius: 16px;
            border: 1px dashed rgba(34, 197, 94, 0.2);
            margin-top: 20px;
        }

        .no-results svg {
            width: 64px;
            height: 64px;
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .no-results p {
            font-size: 16px;
        }

        /* QUICK LINKS - Cards de atalhos úteis */
        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            animation: fadeInUp 0.5s ease 1s backwards;
        }

        .quick-link-card {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(34, 197, 94, 0.1);
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .quick-link-card:hover {
            transform: translateY(-5px);
            border-color: rgba(34, 197, 94, 0.3);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
        }

        .quick-link-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.1));
            color: var(--accent);
        }

        .quick-link-icon svg {
            width: 28px;
            height: 28px;
        }

        .quick-link-info h3 {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
        }

        .quick-link-info p {
            font-size: 13px;
            color: var(--muted);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }

            .page-header {
                padding: 30px 20px;
            }

            .header-content h1 {
                font-size: 28px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .category-tabs {
                flex-direction: column;
            }

            .tab-btn {
                justify-content: center;
            }

            .answer-content {
                padding: 0 24px 24px 24px;
            }

            .question-content {
                gap: 12px;
            }

            .question-icon {
                width: 40px;
                height: 40px;
            }

            .question-icon svg {
                width: 20px;
                height: 20px;
            }

            .question-text {
                font-size: 14px;
            }

            .quick-links {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
                <!-- Botão de voltar para HOME (/) -->
<a href="{{ auth()->user()->isStaff() ? '/' : '/' }}" class="back-link">


    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Voltar
</a>
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <h1>Central de Suporte</h1>
                <p class="header-subtitle">Documentação completa, guias e respostas para suas dúvidas.</p>
                <div class="search-wrapper">
                    <input type="text" id="faqSearch" class="search-input" placeholder="Pesquisar por Sistema, Ban, Conta...">
                    <svg class="search-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stats Info Cards -->
        <div class="stats-grid">
            <div class="stat-card border-green">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Artigos Disponíveis</h3>
                        <div class="stat-value">6</div>
                    </div>
                    <div class="stat-icon green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="stat-card border-blue">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Tempo Médio de Resposta</h3>
                        <div class="stat-value">24h</div>
                    </div>
                    <div class="stat-icon blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="stat-card border-purple">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Tickets Resolvidos</h3>
                        <div class="stat-value">100%</div>
                    </div>
                    <div class="stat-icon purple">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <!-- Category Tabs -->
        <div class="category-tabs" id="categoryTabs">
            <button class="tab-btn active" data-category="all">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                Todos
            </button>
            <button class="tab-btn" data-category="conta">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Conta & Acesso
            </button>
            <button class="tab-btn" data-category="tecnico">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                </svg>
                Técnico
            </button>
            <button class="tab-btn" data-category="moderacao">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Moderação
            </button>
        </div>

        <!-- FAQ Accordion -->
        <div class="faq-grid" id="faqGrid">
            <!-- Pergunta 1 -->
            <div class="faq-item" data-category="all">
                <button class="faq-question">
                    <div class="question-content">
                        <div class="question-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="question-text">O que é o sistema Ambience RPG?</span>
                    </div>
                    <svg class="toggle-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="faq-answer">
                    <div class="answer-content">
                        O Ambience RPG é uma plataforma integrada desenvolvida em Laravel e React para gerenciamento completo de campanhas de RPG. Oferecemos ferramentas para mestres e jogadores, incluindo sistema de guildas, fichas de personagens customizáveis, combates em tempo real, inventário, progresso de níveis e muito mais. Nossa plataforma foi projetada para facilitar a organização e tornar suas sessões de RPG mais dinâmicas e imersivas.
                    </div>
                </div>
            </div>

            <!-- Pergunta 2 -->
            <div class="faq-item" data-category="tecnico">
                <button class="faq-question">
                    <div class="question-content">
                        <div class="question-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="question-text">Requisitos de Sistema e Compatibilidade</span>
                    </div>
                    <svg class="toggle-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="faq-answer">
                    <div class="answer-content">
                        Por ser uma aplicação web moderna SPA, recomendamos o uso de navegadores atualizados (Google Chrome v90+, Firefox 88+, Microsoft Edge 90+ ou Safari 14+). Para a renderização dos modelos 3D dos personagens, necessário uma placa de vídeo com suporte a WebGL 2.0 e aceleração de hardware ativada nas configurações do navegador. A plataforma totalmente responsiva e funciona em tablets e dispositivos móveis modernos.
                    </div>
                </div>
            </div>

            <!-- Pergunta 3 -->
            <div class="faq-item" data-category="conta">
                <button class="faq-question">
                    <div class="question-content">
                        <div class="question-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        <span class="question-text">Como funciona o Login Social?</span>
                    </div>
                    <svg class="toggle-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="faq-answer">
                    <div class="answer-content">
                        Utilizamos o Laravel Socialite para autenticação segura via OAuth 2.0. Ao clicar em "Entrar com Google", você será redirecionado para a página de login do Google, onde pode autorizar o acesso. Criamos automaticamente uma conta vinculada ao seu e-mail. Não armazenamos sua senha do Google, apenas tokens de acesso temporários criptografados. Seus dados são protegidos conforme a LGPD e você pode revogar o acesso a qualquer momento.
                    </div>
                </div>
            </div>

           



            <!-- Pergunta 6 -->
            <div class="faq-item" data-category="moderacao">
                <button class="faq-question">
                    <div class="question-content">
                        <div class="question-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <span class="question-text">Sistema de Warnings e Banimentos</span>
                    </div>
                    <svg class="toggle-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="faq-answer">
                    <div class="answer-content">
                        Nosso sistema de moderação segue um processo gradual:<br><br>
                        <strong>1 Warning</strong> - Advertência registrada no histórico da conta.<br>
                        <strong>2 Warnings</strong> - Suspenso temporária de 7 dias.<br>
                        <strong>3 Warnings</strong> - Suspenso de 30 dias.<br>
                        <strong>4 Warnings</strong> - Banimento permanente da plataforma.<br><br>
                        Para infrações graves (spam, discurso de ódio, fraude), aplicamos banimento imediato. Você pode contestar punições abrindo um ticket de suporte dentro de 15 dias corridos. Todas as decisões são revisadas por um administrador sênior.
                    </div>
                </div>
            </div>

            <!-- Pergunta 7 -->
            <div class="faq-item" data-category="conta">
                <button class="faq-question">
                    <div class="question-content">
                        <div class="question-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </div>
                        <span class="question-text">Como recuperar minha senha?</span>
                    </div>
                    <svg class="toggle-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="faq-answer">
                    <div class="answer-content">
                        Na tela de login, clique em "Esqueci minha senha" e insira seu e-mail cadastrado. Você receberá um link de redefinição válido por 60 minutos. Se não receber o e-mail, verifique sua caixa de spam. O link pode ser usado apenas uma vez. Após criar a nova senha, todos os dispositivos conectados serão desconectados automaticamente por segurança.
                    </div>
                </div>
            </div>

            <!-- Pergunta 8 -->
            <div class="faq-item" data-category="all">
                <button class="faq-question">
                    <div class="question-content">
                        <div class="question-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <span class="question-text">Como abrir um Ticket de Suporte?</span>
                    </div>
                    <svg class="toggle-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="faq-answer">
                    <div class="answer-content">
                        Se não encontrou sua dúvida aqui, você pode abrir um ticket de suporte:<br><br>
                        1. Acesse o menu <strong>Suporte</strong> no dashboard principal<br>
                        2. Clique em <strong>Novo Ticket</strong><br>
                        3. Selecione a categoria adequada (Técnico, Denúncia, Conta, etc.)<br>
                        4. Descreva seu problema de forma detalhada<br>
                        5. Anexe prints se necessário<br><br>
                        Nossa equipe de moderação responde geralmente em até 24 horas úteis. Tickets urgentes (bugs críticos, denúncias graves) são priorizados e respondidos em até 4 horas.
                    </div>
                </div>
            </div>
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="no-results">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <p>Nenhum resultado encontrado para sua busca.</p>
            <p style="margin-top: 8px; font-size: 14px;">Tente usar palavras-chave diferentes ou abra um ticket de suporte.</p>
        </div>

        <!-- Quick Links Section -->
        <div style="margin-top: 40px;">
            <h2 style="font-family: 'Montserrat', sans-serif; font-size: 24px; font-weight: 900; color: #fff; margin-bottom: 20px; text-align: center;">Links úteis</h2>
            <div class="quick-links">
                <a href="{{ route('suporte.index') }}" class="quick-link-card">
                    <div class="quick-link-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <div class="quick-link-info">
                        <h3>Abrir Ticket</h3>
                        <p>Contate nossa equipe de suporte</p>
                    </div>
                </a>
                <a href="#docs/api" class="quick-link-card">
                    <div class="quick-link-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="quick-link-info">
                        <h3>Documentação API</h3>
                        <p>Referência completa da API</p>
                    </div>
                </a>
                <a href="{{ route('comunidade.feed') }}" class="quick-link-card">
                    <div class="quick-link-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="quick-link-info">
                        <h3>Comunidade</h3>
                        <p>Participe da nossa comunidade</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. ACCORDION LOGIC - Abrir/Fechar Perguntas
            const faqItems = document.querySelectorAll('.faq-item');
            faqItems.forEach(item => {
                const header = item.querySelector('.faq-question');
                header.addEventListener('click', () => {
                    const isOpen = item.classList.contains('active');
                    
                    // Fecha todos os outros itens (comportamento de accordion exclusivo)
                    faqItems.forEach(i => {
                        if (i !== item) {
                            i.classList.remove('active');
                            i.querySelector('.faq-answer').style.maxHeight = null;
                        }
                    });
                    
                    // Toggle do item atual
                    if (!isOpen) {
                        item.classList.add('active');
                        const answer = item.querySelector('.faq-answer');
                        answer.style.maxHeight = answer.scrollHeight + 'px';
                    } else {
                        item.classList.remove('active');
                        item.querySelector('.faq-answer').style.maxHeight = null;
                    }
                });
            });

            // 2. CATEGORY TABS - Filtro por Categoria
            const tabBtns = document.querySelectorAll('.tab-btn');
            const items = document.querySelectorAll('.faq-item');
            
            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Atualiza visual dos botões
                    tabBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    
                    const category = btn.getAttribute('data-category');
                    
                    // Filtra os itens
                    items.forEach(item => {
                        // Fecha todos ao trocar de categoria
                        item.classList.remove('active');
                        item.querySelector('.faq-answer').style.maxHeight = null;
                        
                        // Mostra/esconde baseado na categoria
                        if (category === 'all' || item.getAttribute('data-category') === category) {
                            item.style.display = 'block';
                            // Reinicia animação
                            item.style.animation = 'none';
                            item.offsetHeight; // trigger reflow
                            item.style.animation = 'fadeInUp 0.4s ease forwards';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    
                    // Esconde mensagem sem resultados ao trocar de aba
                    document.getElementById('noResults').style.display = 'none';
                });
            });

            // 3. SEARCH FUNCTIONALITY - Busca em Tempo Real
            const searchInput = document.getElementById('faqSearch');
            const noResults = document.getElementById('noResults');
            
            searchInput.addEventListener('input', (e) => {
                const term = e.target.value.toLowerCase().trim();
                let hasResults = false;
                
                if (term.length === 0) {
                    // Limpa a busca, restaura estado normal
                    tabBtns.forEach(b => {
                        b.style.opacity = '1';
                        b.style.pointerEvents = 'auto';
                    });
                    noResults.style.display = 'none';
                    // Simula clique na aba ativa para restaurar filtro
                    const activeTab = document.querySelector('.tab-btn.active');
                    if (activeTab) activeTab.click();
                    return;
                }
                
                // Desativa as abas visualmente durante a busca
                tabBtns.forEach(b => {
                    b.style.opacity = '0.5';
                    b.style.pointerEvents = 'none';
                });
                
                items.forEach(item => {
                    const question = item.querySelector('.question-text').innerText.toLowerCase();
                    const answer = item.querySelector('.answer-content').innerText.toLowerCase();
                    
                    if (question.includes(term) || answer.includes(term)) {
                        item.style.display = 'block';
                        // Abre automaticamente os resultados encontrados
                        if (!item.classList.contains('active')) {
                            item.classList.add('active');
                            const ans = item.querySelector('.faq-answer');
                            ans.style.maxHeight = ans.scrollHeight + 'px';
                        }
                        hasResults = true;
                    } else {
                        item.style.display = 'none';
                        item.classList.remove('active');
                        item.querySelector('.faq-answer').style.maxHeight = null;
                    }
                });
                
                // Mostra mensagem se não houver resultados
                noResults.style.display = hasResults ? 'none' : 'block';
            });

            // 4. COPY CODE BUTTON
            window.copyCode = function(btn) {
                const pre = btn.parentElement.querySelector('pre');
                const textToCopy = pre.innerText;
                
                navigator.clipboard.writeText(textToCopy).then(() => {
                    const originalText = btn.innerText;
                    btn.innerText = 'Copiado!';
                    btn.style.background = 'linear-gradient(to right, #22c55e, #16a34a)';
                    btn.style.color = '#052e16';
                    btn.style.border = 'none';
                    
                    setTimeout(() => {
                        btn.innerText = originalText;
                        btn.style.background = '';
                        btn.style.color = '';
                        btn.style.border = '';
                    }, 2000);
                }).catch(err => {
                    console.error('Erro ao copiar:', err);
                    btn.innerText = 'Erro';
                    setTimeout(() => {
                        btn.innerText = 'Copiar';
                    }, 2000);
                });
            }
        });
    </script>
</body>
</html>

