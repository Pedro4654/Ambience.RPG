<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sala->nome }} - Ambience RPG</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Vite -->
    @vite('resources/js/app.tsx')

    <style>
        :root {
            --bg-dark: #0a0f14;
            --bg-card: #1f2937;
            --bg-elevated: #111827;
            --border-subtle: rgba(34, 197, 94, 0.1);
            --border-hover: rgba(34, 197, 94, 0.3);
            --text-primary: #e6eef6;
            --text-secondary: #8b9ba8;
            --text-muted: #6b7280;
            --accent: #22c55e;
            --accent-light: #16a34a;
            --accent-dark: #15803d;
            --accent-glow: rgba(34, 197, 94, 0.2);
            --gradient-start: #052e16;
            --gradient-end: #065f46;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Inter, system-ui, -apple-system, sans-serif;
            background: linear-gradient(180deg, var(--bg-dark) 0%, #0d1419 100%);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ========== HEADER ========== */
        .header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(10, 15, 20, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-subtle);
            padding: 1rem 0;
        }

        .header-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    height: 70px;
}

        .loading-screen {
    position: fixed;
    inset: 0;
    background: linear-gradient(135deg, #0a0f14 0%, #0d1419 100%);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 1;
    transition: opacity 0.6s ease;
}

.loading-screen.fade-out {
    opacity: 0;
    pointer-events: none;
}

.loading-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
    max-width: 500px;
    padding: 2rem;
}

/* ========== LOGO ANIMADO ========== */
.loading-logo {
    position: relative;
    width: 120px;
    height: 120px;
    animation: logoFloat 3s ease-in-out infinite;
}

@keyframes logoFloat {
    0%, 100% {
        transform: translateY(0px) scale(1);
    }
    50% {
        transform: translateY(-10px) scale(1.05);
    }
}

.loading-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    position: relative;
    z-index: 2;
    filter: drop-shadow(0 0 20px rgba(34, 197, 94, 0.5));
}

.loading-logo-glow {
    position: absolute;
    inset: -20px;
    background: radial-gradient(circle, rgba(34, 197, 94, 0.3), transparent 70%);
    animation: glowPulse 2s ease-in-out infinite;
    z-index: 1;
}

@keyframes glowPulse {
    0%, 100% {
        opacity: 0.5;
        transform: scale(1);
    }
    50% {
        opacity: 1;
        transform: scale(1.2);
    }
}

/* ========== BARRA DE PROGRESSO ========== */
/* ========== LOADING SCREEN ========== */
.loading-screen {
    position: fixed;
    inset: 0;
    background: linear-gradient(135deg, #0a0f14 0%, #0d1419 100%);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 1;
    transition: opacity 0.8s ease;
}

.loading-screen.fade-out {
    opacity: 0;
    pointer-events: none;
}

.loading-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2.5rem;
    max-width: 500px;
    padding: 2rem;
}

/* ========== LOGO ANIMADO ========== */
.loading-logo {
    position: relative;
    width: 120px;
    height: 120px;
    animation: logoFloat 3s ease-in-out infinite;
}

@keyframes logoFloat {
    0%, 100% {
        transform: translateY(0px) scale(1);
    }
    50% {
        transform: translateY(-10px) scale(1.05);
    }
}

.loading-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    position: relative;
    z-index: 2;
    filter: drop-shadow(0 0 20px rgba(34, 197, 94, 0.5));
}

.loading-logo-glow {
    position: absolute;
    inset: -20px;
    background: radial-gradient(circle, rgba(34, 197, 94, 0.3), transparent 70%);
    animation: glowPulse 2s ease-in-out infinite;
    z-index: 1;
}

@keyframes glowPulse {
    0%, 100% {
        opacity: 0.5;
        transform: scale(1);
    }
    50% {
        opacity: 1;
        transform: scale(1.2);
    }
}

/* ========== BARRA DE PROGRESSO ========== */
.loading-progress-container {
    width: 100%;
    height: 4px;
    background: rgba(55, 65, 81, 0.4);
    border-radius: 2px;
    overflow: hidden;
    position: relative;
}

.loading-progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #22c55e, #16a34a);
    border-radius: 2px;
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
    position: relative;
    z-index: 2;
}

.loading-progress-shine {
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: progressShine 2s ease-in-out infinite;
    z-index: 3;
}

@keyframes progressShine {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(200%);
    }
}

/* ========== STATUS DE CARREGAMENTO - PADRONIZADO ========== */
.loading-status {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;
}

.loading-status-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    background: rgba(31, 41, 55, 0.4);
    border: 1px solid rgba(55, 65, 81, 0.5);
    border-radius: 12px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    opacity: 0.6;
}

.loading-status-item.active {
    background: rgba(34, 197, 94, 0.1);
    border-color: rgba(34, 197, 94, 0.3);
    opacity: 1;
    transform: translateX(4px);
}

.loading-status-item.completed {
    background: rgba(34, 197, 94, 0.15);
    border-color: rgba(34, 197, 94, 0.4);
    opacity: 1;
}

/* ========== ÍCONES PADRONIZADOS ========== */
.loading-status-icon {
    width: 24px;
    height: 24px;
    position: relative;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.loading-status-icon svg {
    width: 100%;
    height: 100%;
    stroke: var(--text-muted);
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
    fill: none;
    transition: all 0.3s;
}

/* Estado ATIVO - Spinner girando */
.loading-status-item.active .loading-status-icon svg {
    stroke: var(--accent);
    animation: iconSpin 1s linear infinite;
}

/* Estado COMPLETO - Checkmark */
.loading-status-item.completed .loading-status-icon svg {
    stroke: var(--accent);
    animation: checkmarkPop 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes iconSpin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes checkmarkPop {
    0% {
        transform: scale(0.5);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* ========== TEXTOS DOS STATUS ========== */
.loading-status-item span {
    color: var(--text-muted);
    font-size: 0.95rem;
    font-weight: 500;
    transition: color 0.3s;
}

.loading-status-item.active span {
    color: var(--text-primary);
    font-weight: 600;
}

.loading-status-item.completed span {
    color: var(--accent);
    font-weight: 600;
}

/* ========== DICA DE CARREGAMENTO ========== */
.loading-tip {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 12px;
    animation: tipFadeIn 0.5s ease;
}

.loading-tip span {
    transition: opacity 0.3s ease;
}

@keyframes tipFadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.loading-tip svg {
    width: 20px;
    height: 20px;
    stroke: #3b82f6;
    flex-shrink: 0;
}

.loading-tip span {
    color: #93c5fd;
    font-size: 0.875rem;
    font-weight: 500;
}

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            width: 100%;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 19px;
            color: #fff;
            text-decoration: none;
            flex-shrink: 0;
        }

        .logo-img {
            height: 50px;
            width: auto;
            display: block;
        }

        .nav-links {
            flex: 1;
            display: flex;
            gap: 32px;
            align-items: center;
            justify-content: center;
            margin: 0 2rem;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: color 0.2s;
            position: relative;
        }

        .nav-links a:hover {
            color: var(--accent);
        }

        .nav-links a.active {
            color: var(--accent);
        }

        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--accent);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
            margin-left: auto;
        }

        .notification-btn {
            position: relative;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(34, 197, 94, 0.08);
            border: 1px solid rgba(34, 197, 94, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s;
        }

        .notification-btn:hover {
            background: rgba(34, 197, 94, 0.15);
            border-color: rgba(34, 197, 94, 0.3);
            transform: translateY(-2px);
        }

        .notification-btn svg {
            width: 20px;
            height: 20px;
            stroke: var(--accent);
            fill: none;
            stroke-width: 2;
        }

        .user-avatar-wrapper {
            position: relative;
            cursor: pointer;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid rgba(34, 197, 94, 0.2);
            transition: all 0.25s;
        }

        .user-avatar:hover {
            border-color: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        /* ========== MAIN CONTENT ========== */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* ========== BANNER DA SALA ========== */
        .sala-banner-section {
            position: relative;
            width: 100%;
            height: 320px;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .sala-banner {
            width: 100%;
            height: 100%;
            background-position: center;
            background-size: cover;
            position: relative;
        }

        .banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.7) 100%);
        }

        .banner-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 2rem;
            z-index: 2;
        }

        .banner-edit-btn {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 10;
        }

        .banner-edit-btn .btn {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            transition: var(--transition);
        }

        .banner-edit-btn .btn:hover {
            background: rgba(0, 0, 0, 0.8);
            border-color: var(--accent);
            transform: translateY(-2px);
        }

        .banner-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 700;
            font-size: 2rem;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        }

        /* Inputs com foco */
#modalEditarSala .form-control:focus,
#modalEditarSala .form-select:focus {
    border-color: rgba(34, 197, 94, 0.5);
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    outline: none;
}

/* Botão de salvar hover */
#btnSalvarEdicao:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(34, 197, 94, 0.5);
}

/* Botão de excluir hover */
#btnMostrarExclusao:hover {
    background: rgba(239, 68, 68, 0.25);
    border-color: #ef4444;
    transform: translateY(-2px);
}

/* Botão de confirmar exclusão quando habilitado */
#btnConfirmarExclusao:not(:disabled):hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

/* Ícones nos labels */
#modalEditarSala label svg {
    flex-shrink: 0;
}

/* Select customizado para parecer com input */
#modalEditarSala .form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2322c55e' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
}

/* Smooth transitions */
#modalEditarSala input,
#modalEditarSala textarea,
#modalEditarSala select,
#modalEditarSala button {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

        /* ========== PROFILE SECTION ========== */
        .sala-profile-section {
            display: flex;
            align-items: flex-end;
            gap: 1.5rem;
            margin-top: -4rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 3;
        }

        .sala-profile-photo-wrapper {
            position: relative;
        }

        .sala-profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            border: 4px solid var(--bg-dark);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
            background-position: center;
            background-size: cover;
            position: relative;
        }

        .photo-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 700;
            color: white;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        }

        .photo-edit-btn {
            position: absolute;
            bottom: -8px;
            right: -8px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent);
            border: 3px solid var(--bg-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            color: #052e16;
        }

        .photo-edit-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px var(--accent-glow);
        }

        .sala-info {
            flex: 1;
        }

        .sala-name {
            font-family: Montserrat, sans-serif;
            font-size: 2rem;
            font-weight: 900;
            color: #fff;
            margin-bottom: 0.5rem;
            letter-spacing: 0.5px;
        }

        .sala-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .sala-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .sala-meta-item svg {
            width: 18px;
            height: 18px;
            stroke: var(--accent);
        }

        .tipo-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem; /* Espaço entre ícone e texto */
}

.tipo-badge svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    stroke: currentColor;
    fill: none;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.tipo-publica {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1));
    color: var(--accent);
    border: 1px solid var(--accent);
}

.tipo-privada {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1));
    color: #ef4444;
    border: 1px solid #ef4444;
}

        /* ========== ACTION BUTTONS ========== */
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .btn-action svg {
            width: 18px;
            height: 18px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #052e16;
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.5);
        }

        .btn-secondary {
            background: rgba(55, 65, 81, 0.6);
            color: #d1d5db;
            border: 1px solid rgba(55, 65, 81, 0.8);
        }

        .btn-secondary:hover {
            background: rgba(55, 65, 81, 0.8);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.25);
            border-color: #ef4444;
            transform: translateY(-2px);
        }

        /* ========== GRID LAYOUT ========== */
        .sala-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            margin-top: 2rem;
        }

        .sala-main {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .sala-sidebar {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* ========== CARDS ========== */
        .card-modern {
            background: linear-gradient(145deg, rgba(31, 42, 51, 0.6), rgba(20, 28, 35, 0.4));
            border: 1px solid var(--border-subtle);
            border-radius: 20px;
            padding: 1.5rem;
            transition: var(--transition);
        }

        .card-modern:hover {
            border-color: var(--border-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-title svg {
            width: 24px;
            height: 24px;
            stroke: var(--accent);
        }

        /* ========== DESCRIPTION ========== */
        .sala-description {
            background: rgba(17, 24, 39, 0.6);
            border: 1px solid rgba(55, 65, 81, 0.5);
            border-radius: 16px;
            padding: 1.5rem;
            color: var(--text-secondary);
            line-height: 1.8;
        }

        /* ========== PARTICIPANTS ========== */
        .participants-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }

        .participant-card {
            background: rgba(17, 24, 39, 0.6);
            border: 1px solid rgba(55, 65, 81, 0.5);
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: var(--transition);
            justify-content: space-between; /* importante para manter o right à direita */
  gap: 0.9rem;                     /* mantém espaçamento entre colunas */
}

.participant-left {
  flex: 0 0 auto;
}
  

        .participant-card:hover {
            background: rgba(17, 24, 39, 0.8);
            border-color: rgba(34, 197, 94, 0.3);
            transform: translateX(4px);
        }

        .participant-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(34, 197, 94, 0.2);
        }

        .participant-avatar-fallback {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }

        .participant-info {
  flex: 1 1 auto;      /* <-- essencial: ocupa espaço e empurra o right para a borda */
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 6px;
  margin: 0;            /* zera margens que poderiam empurrar coisas */
  padding: 0;
}

.participant-right {
  flex: 0 0 auto;
  display: flex;
  flex-direction: column;
  align-items: flex-end;   /* alinha status e botões à direita do bloco direito */
  gap: 8px;
  min-width: 90px;         /* opcional: evita encolher demais; ajuste se necessário */
}

        .participant-name {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  width: auto;
  max-width: 100%;
  white-space: nowrap;      /* evita wrap que poderia criar desalinhamento inesperado */
  align-self: flex-start;   /* reforça alinhamento à esquerda */
}

        .participant-role {
  display: inline-flex;
  align-items: center;
  margin: 0;                /* remove margem que desloca pra direita */
  padding: 0;               /* remove padding extra do container do badge */
  align-self: flex-start;   /* alinhamento à esquerda */
}

        .role-mestre {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
        }

        .role-admin_sala {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .role-jogador {
            background: rgba(107, 114, 128, 0.2);
            color: #9ca3af;
        }

        .participant-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #6b7280;
        }

        .status-dot.online {
            background: var(--accent);
            box-shadow: 0 0 8px var(--accent-glow);
        }

        .status-text {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* ========== PERMISSIONS ========== */
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .permission-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: rgba(17, 24, 39, 0.6);
            border: 1px solid rgba(55, 65, 81, 0.5);
            border-radius: 12px;
        }

        .permission-item.enabled {
            border-color: rgba(34, 197, 94, 0.3);
            background: rgba(34, 197, 94, 0.05);
        }

        .permission-icon {
            width: 20px;
            height: 20px;
            stroke: var(--text-muted);
        }

        .permission-item.enabled .permission-icon {
            stroke: var(--accent);
        }

        .permission-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .permission-item.enabled .permission-label {
            color: var(--accent);
            font-weight: 600;
        }

        /* ========== CHAT CONTAINER ========== */
        .chat-sidebar {
            position: sticky;
            top: 100px;
            height: calc(100vh - 120px);
        }

        /* ========== FOOTER ========== */
        .footer {
            border-top: 1px solid rgba(34, 197, 94, 0.1);
            padding: 48px 0 24px;
            background: #0d1419;
            margin-top: 80px;
        }

        .footer-columns {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            max-width: 1400px;
            margin: 0 auto 32px;
            padding: 0 32px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            font-weight: 800;
            font-size: 18px;
        }

        .footer h4 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 14px;
            color: var(--accent);
        }

        .footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer ul li {
            margin: 8px 0;
            color: var(--text-muted);
            font-size: 14px;
            cursor: pointer;
            transition: color 0.2s;
        }

        .footer ul li:hover {
            color: var(--accent);
        }

        /* ========== NAV STYLES ========== */
        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 0;
            height: 70px;
            width: 100%;
        }

        /* ========== USER DROPDOWN ========== */
        .user-dropdown {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            width: 280px;
            background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.98));
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 16px;
            padding: 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            z-index: 200;
            display: none;
            animation: slideDown 0.25s ease;
            overflow: hidden;
        }

        .user-dropdown.active {
            display: block;
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

        .user-dropdown-header {
            padding: 20px;
            border-bottom: 1px solid rgba(34, 197, 94, 0.15);
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.08), transparent);
        }

        .user-dropdown-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .user-dropdown-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(34, 197, 94, 0.3);
            background: linear-gradient(135deg, #064e3b, #052e16);
        }

        .user-dropdown-avatar-default {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #064e3b, #052e16);
            border: 2px solid rgba(34, 197, 94, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            color: var(--accent);
        }

        .user-dropdown-details {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }

        .user-dropdown-details h4 {
    font-size: 16px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 4px 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

        .user-dropdown-details p {
    font-size: 13px;
    color: var(--text-muted);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 100%;
    margin: 0;
}

        .user-dropdown-menu {
            padding: 8px 0;
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #d1d5db;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
        }

        .user-dropdown-item:hover {
            background: rgba(34, 197, 94, 0.1);
            color: var(--accent);
        }

        .user-dropdown-item svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .user-dropdown-divider {
            height: 1px;
            background: rgba(34, 197, 94, 0.15);
            margin: 8px 0;
        }

        .user-dropdown-item.logout {
            color: #ef4444;
        }

        .user-dropdown-item.logout:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* ========== NOTIFICATION MODAL ========== */
        .notification-modal {
            position: fixed;
            top: 80px;
            right: 32px;
            width: 360px;
            background: linear-gradient(145deg, rgba(31, 42, 51, 0.95), rgba(20, 28, 35, 0.95));
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            z-index: 200;
            display: none;
            animation: slideDown 0.25s ease;
        }

        .notification-modal.active {
            display: block;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(34, 197, 94, 0.15);
        }

        .notification-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
        }

        .notification-close {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            background: transparent;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .notification-close:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .notification-close svg {
            width: 16px;
            height: 16px;
            stroke: #8b9ba8;
            stroke-width: 2;
        }

        .notification-empty {
            text-align: center;
            padding: 32px 16px;
            color: var(--text-muted);
        }

        .notification-empty svg {
            width: 48px;
            height: 48px;
            margin: 0 auto 12px;
            stroke: var(--text-muted);
            opacity: 0.4;
        }

        .notification-empty p {
            font-size: 14px;
            line-height: 1.6;
        }

.notification-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 18px;
    height: 18px;
    background: #ef4444;
    border-radius: 50%;
    border: 2px solid var(--bg-dark);
    font-size: 10px;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ========== ESTILOS PARA NOTIFICAÇÕES ========== */
.notification-list {
    max-height: 400px;
    overflow-y: auto;
    padding: 0;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 20px;
    border-bottom: 1px solid rgba(34, 197, 94, 0.1);
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.notification-item:hover {
    background: rgba(34, 197, 94, 0.05);
}

.notification-item.nao-lida {
    background: rgba(34, 197, 94, 0.08);
}

.notification-item.nao-lida::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: var(--accent);
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification-icon.blue {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.notification-icon.green {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
}

.notification-icon.red {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.notification-icon.yellow {
    background: rgba(234, 179, 8, 0.1);
    color: #eab308;
}

.notification-icon.purple {
    background: rgba(168, 85, 247, 0.1);
    color: #a855f7;
}

.notification-icon svg {
    width: 20px;
    height: 20px;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-message {
    font-size: 14px;
    color: #e5e7eb;
    margin-bottom: 4px;
    line-height: 1.4;
}

.notification-time {
    font-size: 12px;
    color: var(--text-muted);
}

.notification-actions {
    display: flex;
    gap: 6px;
    opacity: 0;
    transition: opacity 0.2s;
}

.notification-item:hover .notification-actions {
    opacity: 1;
}

/* ========== PLACEHOLDERS DOS INPUTS ========== */
#modalEditarSala input::placeholder,
#modalEditarSala textarea::placeholder,
#modalEditarSala select::placeholder {
    color: #9ca3af !important;
    opacity: 1;
}

#modalEditarSala input::-webkit-input-placeholder,
#modalEditarSala textarea::-webkit-input-placeholder {
    color: #9ca3af !important;
}

#modalEditarSala input::-moz-placeholder,
#modalEditarSala textarea::-moz-placeholder {
    color: #9ca3af !important;
    opacity: 1;
}

#modalEditarSala input:-ms-input-placeholder,
#modalEditarSala textarea:-ms-input-placeholder {
    color: #9ca3af !important;
}

#btnEditarSala {
    background: linear-gradient(135deg, #22c55e, #16a34a) !important;
    color: #052e16 !important;
    border: none !important;
    box-shadow: 0 4px 14px rgba(34, 197, 94, 0.4) !important;
}

#btnEditarSala:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(34, 197, 94, 0.5) !important;
}

/* ========== ALERTAS CUSTOMIZADOS PARA MODAIS ========== */
#modalEditarSala .alert-success {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(34, 197, 94, 0.08));
    border: 1px solid rgba(34, 197, 94, 0.3);
    border-left: 4px solid var(--accent);
    color: var(--accent);
    border-radius: 12px;
    padding: 1rem 1.25rem;
    font-size: 0.95rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    animation: slideDown 0.3s ease;
}

#modalEditarSala .alert-success::before {
    content: '';
    display: inline-block;
    width: 20px;
    height: 20px;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' fill='none' stroke='%2322c55e' stroke-width='2' xmlns='http://www.w3.org/2000/svg'%3E%3Cpolyline points='20 6 9 17 4 12'/%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
    flex-shrink: 0;
}

#modalEditarSala .alert-danger {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.08));
    border: 1px solid rgba(239, 68, 68, 0.3);
    border-left: 4px solid #ef4444;
    color: #fca5a5;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    font-size: 0.95rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    animation: slideDown 0.3s ease;
}

#modalEditarSala .alert-danger::before {
    content: '';
    display: inline-block;
    width: 20px;
    height: 20px;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' fill='none' stroke='%23ef4444' stroke-width='2' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='12' cy='12' r='10'/%3E%3Cline x1='15' y1='9' x2='9' y2='15'/%3E%3Cline x1='9' y1='9' x2='15' y2='15'/%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
    flex-shrink: 0;
}

/* Alertas de exclusão */
#deleteConfirmSection .alert-success {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(34, 197, 94, 0.08));
    border: 1px solid rgba(34, 197, 94, 0.3);
    border-left: 4px solid var(--accent);
    color: var(--accent);
    border-radius: 12px;
    padding: 1rem 1.25rem;
    font-size: 0.95rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    animation: slideDown 0.3s ease;
}

#deleteConfirmSection .alert-success::before {
    content: '';
    display: inline-block;
    width: 20px;
    height: 20px;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' fill='none' stroke='%2322c55e' stroke-width='2' xmlns='http://www.w3.org/2000/svg'%3E%3Cpolyline points='20 6 9 17 4 12'/%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
    flex-shrink: 0;
}

#deleteConfirmSection .alert-danger {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.08));
    border: 1px solid rgba(239, 68, 68, 0.3);
    border-left: 4px solid #ef4444;
    color: #fca5a5;
    border-radius: 12px;
    padding: 1rem 1.25rem;
    font-size: 0.95rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    animation: slideDown 0.3s ease;
}

#deleteConfirmSection .alert-danger::before {
    content: '';
    display: inline-block;
    width: 20px;
    height: 20px;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' fill='none' stroke='%23ef4444' stroke-width='2' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='12' cy='12' r='10'/%3E%3Cline x1='15' y1='9' x2='9' y2='15'/%3E%3Cline x1='9' y1='9' x2='15' y2='15'/%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
    flex-shrink: 0;
}

/* Animação suave */
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

.notification-action-btn {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: transparent;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    color: var(--text-muted);
}

.notification-action-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
}

.notification-action-btn.marcar-lida:hover {
    color: var(--accent);
}

.notification-action-btn.remover:hover {
    color: #ef4444;
}

.notification-action-btn svg {
    width: 14px;
    height: 14px;
}

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

        /* Scrollbar para modais (mais fina) */
        .modal-body::-webkit-scrollbar,
        .rpg-modal-body::-webkit-scrollbar,
        .permissions-modal .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track,
        .rpg-modal-body::-webkit-scrollbar-track,
        .permissions-modal .modal-body::-webkit-scrollbar-track {
            background: rgba(17, 24, 39, 0.6);
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb,
        .rpg-modal-body::-webkit-scrollbar-thumb,
        .permissions-modal .modal-body::-webkit-scrollbar-thumb {
            background: rgba(34, 197, 94, 0.3);
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .modal-body::-webkit-scrollbar-thumb:hover,
        .rpg-modal-body::-webkit-scrollbar-thumb:hover,
        .permissions-modal .modal-body::-webkit-scrollbar-thumb:hover {
            background: rgba(34, 197, 94, 0.5);
        }

        /* Scrollbar para listas de notificações (ainda mais fina) */
        .notification-list::-webkit-scrollbar,
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .notification-list::-webkit-scrollbar-track,
        .chat-messages::-webkit-scrollbar-track {
            background: rgba(17, 24, 39, 0.4);
            border-radius: 3px;
        }

        .notification-list::-webkit-scrollbar-thumb,
        .chat-messages::-webkit-scrollbar-thumb {
            background: rgba(34, 197, 94, 0.3);
            border-radius: 3px;
            transition: background 0.3s ease;
        }

        .notification-list::-webkit-scrollbar-thumb:hover,
        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: rgba(34, 197, 94, 0.5);
        }

        .footer-bottom {
            text-align: center;
            color: var(--muted);
            font-size: 13px;
            padding-top: 24px;
            border-top: 1px solid rgba(34, 197, 94, 0.1);
            max-width: 1400px;
            margin: 0 auto;
            padding: 24px 32px 0;
        }

        .custom-select-wrapper {
    position: relative;
}

.custom-select-wrapper select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
}

.custom-select-wrapper select:hover {
    border-color: rgba(34, 197, 94, 0.5);
}

.custom-select-wrapper select:focus {
    border-color: rgba(34, 197, 94, 0.5);
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    outline: none;
}

/* Animação suave do ícone */
#tipo-icon {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Botão de excluir com texto separado */
#btnMostrarExclusao span {
    flex: 1;
    text-align: center;
}

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1200px) {
            .sala-grid {
                grid-template-columns: 1fr;
            }

            .chat-sidebar {
                position: relative;
                height: 600px;
                top: 0;
            }
        }

        @media (max-width: 768px) {
            .sala-banner-section {
                height: 200px;
            }

            .sala-profile-section {
                flex-direction: column;
                align-items: flex-start;
                margin-top: -3rem;
            }

            .sala-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .action-buttons {
                width: 100%;
            }

            .btn-action {
                flex: 1;
                justify-content: center;
            }

            .participants-grid {
                grid-template-columns: 1fr;
            }

            .nav-links {
                display: none;
            }

            .footer-columns {
                grid-template-columns: 1fr;
            }
        }

        /* ========== ANIMATIONS ========== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease;
        }

        /* ========== SESSION ALERT ========== */
        .session-alert {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(34, 197, 94, 0.05));
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .session-alert-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .session-info h3 {
            color: var(--accent);
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .session-info p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .permissions-modal .modal-dialog {
    max-width: 540px;
}

.permissions-modal .modal-content {
    background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.95));
    border: 1px solid rgba(34, 197, 94, 0.2);
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
}

.permissions-modal .modal-header {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
    border-bottom: 1px solid rgba(34, 197, 94, 0.2);
    padding: 1.75rem 2rem;
}

.permissions-modal .modal-title {
    color: #fff;
    font-weight: 800;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.permissions-modal .modal-title svg {
    width: 28px;
    height: 28px;
    stroke: var(--accent);
}

.permissions-modal .btn-close {
    filter: invert(1);
    opacity: 0.8;
    transition: opacity 0.2s, transform 0.2s;
}

.permissions-modal .btn-close:hover {
    opacity: 1;
    transform: rotate(90deg);
}

.permissions-modal .modal-body {
    padding: 2rem;
}

.permissions-modal .modal-footer {
    border-top: 1px solid rgba(34, 197, 94, 0.15);
    padding: 1.5rem 2rem;
    background: rgba(17, 24, 39, 0.4);
}

/* Loading State */
.permissions-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 2rem;
    gap: 1.5rem;
}

.permissions-loading-spinner {
    width: 48px;
    height: 48px;
    border: 4px solid rgba(34, 197, 94, 0.2);
    border-top-color: var(--accent);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.permissions-loading-text {
    color: var(--text-secondary);
    font-size: 0.95rem;
    font-weight: 500;
}

/* Permission Items */
.permission-toggle-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    background: rgba(17, 24, 39, 0.6);
    border: 1px solid rgba(55, 65, 81, 0.5);
    border-radius: 16px;
    margin-bottom: 1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.permission-toggle-item:hover {
    background: rgba(17, 24, 39, 0.8);
    border-color: rgba(34, 197, 94, 0.3);
    transform: translateX(4px);
}

.permission-toggle-item.active {
    background: rgba(34, 197, 94, 0.08);
    border-color: rgba(34, 197, 94, 0.4);
}

.permission-toggle-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.permission-toggle-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(55, 65, 81, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.3s;
}

.permission-toggle-item.active .permission-toggle-icon {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.1));
}

.permission-toggle-icon svg {
    width: 22px;
    height: 22px;
    stroke: var(--text-muted);
    transition: all 0.3s;
}

.permission-toggle-item.active .permission-toggle-icon svg {
    stroke: var(--accent);
}

.permission-toggle-content {
    flex: 1;
}

.permission-toggle-label {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
    display: block;
}

.permission-toggle-description {
    color: var(--text-muted);
    font-size: 0.8rem;
    line-height: 1.4;
}

/* Modern Toggle Switch */
.toggle-switch {
    position: relative;
    width: 52px;
    height: 28px;
    flex-shrink: 0;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(55, 65, 81, 0.8);
    border: 2px solid rgba(55, 65, 81, 0.6);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 34px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 3px;
    bottom: 2px;
    background: white;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.toggle-switch input:checked + .toggle-slider {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    border-color: var(--accent);
    box-shadow: 0 0 12px rgba(34, 197, 94, 0.3);
}

.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(24px);
}

.toggle-slider:hover {
    border-color: rgba(34, 197, 94, 0.5);
}

/* Alert Messages */
.permissions-modal .alert {
    border-radius: 12px;
    border: none;
    padding: 1rem 1.25rem;
    font-size: 0.9rem;
    font-weight: 500;
    margin-top: 1.5rem;
    animation: slideDown 0.3s ease;
}

.permissions-modal .alert-success {
    background: rgba(34, 197, 94, 0.15);
    color: var(--accent);
    border-left: 4px solid var(--accent);
}

.permissions-modal .alert-danger {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border-left: 4px solid #ef4444;
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

/* User Info Header */
.permissions-user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: rgba(17, 24, 39, 0.5);
    border: 1px solid rgba(55, 65, 81, 0.5);
    border-radius: 14px;
    margin-bottom: 1.5rem;
}

.permissions-user-avatar-img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    border-radius: 10px !important;
    display: block !important;
}

/* Container do avatar */
.permissions-user-avatar {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    background: linear-gradient(135deg, #064e3b, #052e16);
    border: 2px solid rgba(34, 197, 94, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--accent);
    flex-shrink: 0;
    overflow: hidden; /* CRÍTICO */
    position: relative;
}

.permissions-user-details h6 {
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
    margin: 0 0 0.25rem 0;
}

.permissions-user-details p {
    color: var(--text-muted);
    font-size: 0.85rem;
    margin: 0;
}

/* Responsive */
@media (max-width: 576px) {
    .permissions-modal .modal-dialog {
        margin: 1rem;
    }
    
    .permission-toggle-item {
        padding: 1rem;
    }
    
    .permission-toggle-icon {
        width: 38px;
        height: 38px;
    }
    
    .permission-toggle-icon svg {
        width: 20px;
        height: 20px;
    }
}
    </style>
</head>
<body>

<!-- ========== HEADER ========== -->
<header class="header">
    <div class="container">
        <nav class="nav">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Ambience RPG" class="logo-img">
            </a>
            <div class="nav-links">
                <a href="{{ route('salas.index') }}">Salas</a>
                <a href="{{ route('comunidade.feed') }}">Comunidade</a>
                <a href="{{ route('suporte.index') }}">Suporte</a>
            </div>
            
            <div class="user-menu">
                <button class="notification-btn" id="notificationBtn" aria-label="Notificações">
                    <svg viewBox="0 0 24 24">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                </button>
                
                <div class="user-avatar-wrapper" id="userAvatarBtn">
                    @if(auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->username }}" class="user-avatar">
                    @else
                        <div class="user-avatar" style="background: linear-gradient(135deg, #064e3b, #052e16); display: flex; align-items: center; justify-content: center; color: var(--accent); font-weight: 700; font-size: 18px;">
                            {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                        </div>
                    @endif
                    
                    <!-- Dropdown Menu -->
                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-dropdown-header">
                            <div class="user-dropdown-info">
                                @if(auth()->user()->avatar_url)
                                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->username }}" class="user-dropdown-avatar">
                                @else
                                    <div class="user-dropdown-avatar-default">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</div>
                                @endif
                                <div class="user-dropdown-details">
                                    <h4>{{ auth()->user()->username }}</h4>
                                    <p>{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="user-dropdown-menu">
                            <a href="{{ route('perfil.show', auth()->user()->username) }}" class="user-dropdown-item">
                                <svg viewBox="0 0 24 24">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                Meu Perfil
                            </a>
                            
                            <div class="user-dropdown-divider"></div>
                            
                            <form method="POST" action="{{ route('usuarios.logout') }}" style="margin:0;">
                                @csrf
                                <button type="submit" class="user-dropdown-item logout">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                        <polyline points="16 17 21 12 16 7"/>
                                        <line x1="21" y1="12" x2="9" y2="12"/>
                                    </svg>
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

<!-- Substitua o CSS da loading screen no <style> do seu arquivo por este: -->

<style>
/* ========== LOADING SCREEN ========== */
.loading-screen {
    position: fixed;
    inset: 0;
    background: linear-gradient(135deg, #0a0f14 0%, #0d1419 100%);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 1;
    transition: opacity 0.8s ease;
}

.loading-screen.fade-out {
    opacity: 0;
    pointer-events: none;
}

.loading-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2.5rem;
    max-width: 500px;
    padding: 2rem;
}

/* Ícones inline em badges e labels */
.tipo-badge svg,
.participant-role svg {
    width: 14px;
    height: 14px;
    margin-right: 4px;
    vertical-align: middle;
    stroke: currentColor;
    fill: none;
    stroke-width: 2.5;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 600;
    line-height: 1;
    box-shadow: 0 3px 10px rgba(0,0,0,0.35);
    border: 1px solid rgba(255,255,255,0.06);
    margin-left: 0 !important;
  transform: none !important;
}

.role-badge i {
    font-size: 0.85rem;
    width: 16px;
    height: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Mestre (dourado leve, não grita) */
.role-mestre {
    background: linear-gradient(90deg, #f7d87c, #eabc4e);
    color: #2b1e05;
}

/* Admin (azul elegante) */
.role-admin {
    background: linear-gradient(90deg, #79c4ff, #44aaff);
    color: #062038;
}

/* Jogador (neutro, discreto) */
.role-jogador {
    background: rgba(255,255,255,0.05);
    color: var(--text-muted);
}

/* Ícones em títulos de seção */
.session-info h3 svg {
    width: 20px;
    height: 20px;
    margin-right: 8px;
    vertical-align: middle;
    stroke: currentColor;
    fill: none;
    stroke-width: 2;
}

/* Garantir que os ícones não quebrem em mobile */
@media (max-width: 768px) {
    .tipo-badge svg,
    .participant-role svg {
        width: 12px;
        height: 12px;
    }
}

/* ========== LOGO ANIMADO ========== */
.loading-logo {
    position: relative;
    width: 120px;
    height: 120px;
    animation: logoFloat 3s ease-in-out infinite;
}

@keyframes logoFloat {
    0%, 100% {
        transform: translateY(0px) scale(1);
    }
    50% {
        transform: translateY(-10px) scale(1.05);
    }
}

.loading-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    position: relative;
    z-index: 2;
    filter: drop-shadow(0 0 20px rgba(34, 197, 94, 0.5));
}

.loading-logo-glow {
    position: absolute;
    inset: -20px;
    background: radial-gradient(circle, rgba(34, 197, 94, 0.3), transparent 70%);
    animation: glowPulse 2s ease-in-out infinite;
    z-index: 1;
}

@keyframes glowPulse {
    0%, 100% {
        opacity: 0.5;
        transform: scale(1);
    }
    50% {
        opacity: 1;
        transform: scale(1.2);
    }
}

/* ========== BARRA DE PROGRESSO ========== */
.loading-progress-container {
    width: 100%;
    height: 4px;
    background: rgba(55, 65, 81, 0.4);
    border-radius: 2px;
    overflow: hidden;
    position: relative;
}

.loading-progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #22c55e, #16a34a);
    border-radius: 2px;
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
    position: relative;
    z-index: 2;
}

.loading-progress-shine {
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: progressShine 2s ease-in-out infinite;
    z-index: 3;
}

@keyframes progressShine {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(200%);
    }
}

/* ========== STATUS DE CARREGAMENTO - PADRONIZADO ========== */
.loading-status {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;
}

.loading-status-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    background: rgba(31, 41, 55, 0.4);
    border: 1px solid rgba(55, 65, 81, 0.5);
    border-radius: 12px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    opacity: 0.6;
}

.loading-status-item.active {
    background: rgba(34, 197, 94, 0.1);
    border-color: rgba(34, 197, 94, 0.3);
    opacity: 1;
    transform: translateX(4px);
}

.loading-status-item.completed {
    background: rgba(34, 197, 94, 0.15);
    border-color: rgba(34, 197, 94, 0.4);
    opacity: 1;
}

/* ========== ÍCONES PADRONIZADOS ========== */
.loading-status-icon {
    width: 24px;
    height: 24px;
    position: relative;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.loading-status-icon svg {
    width: 100%;
    height: 100%;
    stroke: var(--text-muted);
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
    fill: none;
    transition: all 0.3s;
}

/* Estado ATIVO - Spinner girando */
.loading-status-item.active .loading-status-icon svg {
    stroke: var(--accent);
    animation: iconSpin 1s linear infinite;
}

/* Estado COMPLETO - Checkmark */
.loading-status-item.completed .loading-status-icon svg {
    stroke: var(--accent);
    animation: checkmarkPop 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

@keyframes iconSpin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes checkmarkPop {
    0% {
        transform: scale(0.5);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* ========== TEXTOS DOS STATUS ========== */
.loading-status-item span {
    color: var(--text-muted);
    font-size: 0.95rem;
    font-weight: 500;
    transition: color 0.3s;
}

.loading-status-item.active span {
    color: var(--text-primary);
    font-weight: 600;
}

.loading-status-item.completed span {
    color: var(--accent);
    font-weight: 600;
}

/* ========== DICA DE CARREGAMENTO ========== */
.loading-tip {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 12px;
    animation: tipFadeIn 0.5s ease;
}

.loading-tip span {
    transition: opacity 0.3s ease;
}

@keyframes tipFadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.loading-tip svg {
    width: 20px;
    height: 20px;
    stroke: #3b82f6;
    flex-shrink: 0;
}

.loading-tip span {
    color: #93c5fd;
    font-size: 0.875rem;
    font-weight: 500;
}
</style>

<!-- Substitua o HTML da loading screen por este: -->

<div class="loading-screen" id="loadingScreen">
    <div class="loading-content">
        <!-- Logo animado -->
        <div class="loading-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Ambience RPG">
            <div class="loading-logo-glow"></div>
        </div>
        
        <!-- Barra de progresso -->
        <div class="loading-progress-container">
            <div class="loading-progress-bar" id="loadingProgressBar"></div>
            <div class="loading-progress-shine"></div>
        </div>
        
        <!-- Status de carregamento -->
        <div class="loading-status">
            <div class="loading-status-item" id="statusWebSocket">
                <div class="loading-status-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                </div>
                <span>Conectando ao servidor</span>
            </div>
            
            <div class="loading-status-item" id="statusChat">
                <div class="loading-status-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                </div>
                <span>Carregando chat</span>
            </div>
            
            <div class="loading-status-item" id="statusReady">
                <div class="loading-status-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                    </svg>
                </div>
                <span>Preparando interface</span>
            </div>
        </div>
        
        <!-- Texto de dica -->
        <div class="loading-tip">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4"/>
                <circle cx="12" cy="8" r="0.5" fill="currentColor"/>
            </svg>
            <span id="loadingTipText">Preparando sua aventura...</span>
        </div>
    </div>
</div>

<!-- Modal de Notificações -->
<div class="notification-modal" id="notificationModal">
    <div class="notification-header">
        <h3>Notificações</h3>
        <button class="notification-close" id="closeNotificationModal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>
    <div class="notification-empty">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <p>Você não tem notificações no momento.<br>Quando algo acontecer, você verá aqui!</p>
    </div>
</div>

<!-- ========== MAIN CONTENT ========== -->
<div class="main-container fade-in">

    <!-- Banner da Sala -->
    <div class="sala-banner-section">
        <div class="sala-banner" 
             @if($sala->banner_url)
                style="background-image: url('{{ $sala->banner_url }}');"
             @else
                style="background-color: {{ $sala->banner_color ?? '#6c757d' }};"
             @endif>
            
            @if((int)auth()->id() === (int)$sala->criador_id)
                <div class="banner-edit-btn">
                    <button class="btn" id="openBannerEditorBtn" data-sala-id="{{ $sala->id }}">
                        <i class="fa-solid fa-image me-2"></i> Editar Banner
                    </button>
                </div>
            @endif

            <div class="banner-overlay"></div>

            @if(!$sala->banner_url)
                <div class="banner-fallback">
                    {{ $sala->nome }}
                </div>
            @endif
        </div>
    </div>

    <!-- Profile Section -->
    <div class="sala-profile-section">
        <div class="sala-profile-photo-wrapper">
            <div class="sala-profile-photo" 
                 @if($sala->profile_photo_url)
                    style="background-image: url('{{ $sala->profile_photo_url }}');"
                 @else
                    style="background-color: {{ $sala->profile_photo_color ?? '#6c757d' }};"
                 @endif>
                
                @if(!$sala->profile_photo_url)
                    <div class="photo-fallback">
                        {{ strtoupper(mb_substr($sala->nome, 0, 1)) }}
                    </div>
                @endif

                @if((int)auth()->id() === (int)$sala->criador_id)
                    <button class="photo-edit-btn" id="openProfilePhotoEditorBtn" 
                            data-sala-id="{{ $sala->id }}" 
                            data-foto-url="{{ $sala->profile_photo_url ?? '' }}">
                        <i class="fa-solid fa-camera"></i>
                    </button>
                @endif
            </div>
        </div>

        <div class="sala-info">
            <h1 class="sala-name">{{ $sala->nome }}</h1>
            <div class="sala-meta">
                <div class="sala-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    ID: #{{ $sala->id }}
                </div>

                <span class="tipo-badge {{ $sala->tipo === 'publica' ? 'tipo-publica' : 'tipo-privada' }}">
    @if($sala->tipo === 'publica')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="2" y1="12" x2="22" y2="12"/>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
        </svg>
        Pública
    @else
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
        Privada
    @endif
</span>

                <div class="sala-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    {{ $sala->participantes->count() }}/{{ $sala->max_participantes }} Participantes
                </div>

                <div class="sala-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    {{ ucfirst($meu_papel) }}
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <a href="{{ route('salas.index') }}" class="btn-action btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Voltar
            </a>

            @if((int)auth()->id() === (int)$sala->criador_id)
        <button type="button" class="btn-action btn-secondary" id="btnEditarSala" data-sala-id="{{ $sala->id }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Editar Sala
        </button>
    @endif

            @if((int)auth()->id() === (int)$sala->criador_id || ($minhas_permissoes && $minhas_permissoes->pode_convidar_usuarios))
    <button type="button" 
            class="btn-action btn-primary" 
            data-bs-toggle="modal" 
            data-bs-target="#inviteLinksModal"
            onclick="console.log('🔘 Botão clicado, abrindo modal...'); debugModal();">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
        </svg>
        Links de Convite
    </button>
@endif

            @if($meu_papel !== 'mestre' && $sala->criador_id !== auth()->id())
                <button id="btnSairSala" type="button" class="btn-action btn-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Sair da Sala
                </button>
            @endif
        </div>
    </div>

    <!-- Descrição da Sala -->
    @if($sala->descricao)
        <div class="sala-description fade-in">
            {{ $sala->descricao }}
        </div>
    @endif

    <!-- Sessão Ativa -->
    @if($sessao_ativa)
        <div class="session-alert fade-in">
            <div class="session-alert-content">
                <div class="session-info">
                    <h3>
    <svg style="width:20px;height:20px;margin-right:8px;vertical-align:middle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="2" y="7" width="20" height="15" rx="2" ry="2"/>
        <polyline points="17 2 12 7 7 2"/>
    </svg>
    Sessão em Andamento
</h3>
                    <p>{{ $sessao_ativa->nome_sessao }}</p>
                    <p style="font-size: 0.75rem; color: var(--text-muted);">Status: {{ ucfirst($sessao_ativa->status) }}</p>
                </div>
                <div>
                    @if($participa_na_sessao)
                        <a href="{{ route('sessoes.show', ['id' => $sessao_ativa->id]) }}" class="btn-action btn-primary">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="1" y="3" width="15" height="13"/>
        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
        <circle cx="5.5" cy="18.5" r="2.5"/>
        <circle cx="18.5" cy="18.5" r="2.5"/>
    </svg>
    Ir para Sessão
</a>
                    @else
                        <form action="{{ route('sessoes.entrar', ['id' => $sessao_ativa->id]) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-action btn-primary">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
        <polyline points="10 17 15 12 10 7"/>
        <line x1="15" y1="12" x2="3" y2="12"/>
    </svg>
    Entrar na Sessão
</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @else
        @if($minhas_permissoes && $minhas_permissoes->pode_iniciar_sessao)
    <div style="text-align: right; margin-bottom: 2rem; margin-top: 1.5rem;">
        <button id="btnIniciarSessao" class="btn-action btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polygon points="5 3 19 12 5 21 5 3"/>
            </svg>
            Iniciar Sessão
        </button>
    </div>
@endif
    @endif

    <!-- Grid Principal -->
    <div class="sala-grid">
        <!-- Coluna Principal -->
        <div class="sala-main">
            
            <!-- Participantes -->
            <div class="card-modern fade-in">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                    <h2 class="card-title" style="margin-bottom: 0;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        Participantes Ativos
                    </h2>
                </div>

                <div class="participants-grid">
                    @foreach($sala->participantes as $participante)
                        @php
                            $uid = $participante->usuario->id;
                            $isCriador = (int)$participante->usuario_id === (int)$sala->criador_id;
                            $papel = $participante->papel;
                        @endphp

                        <div class="participant-card" id="user-{{ $uid }}">
            <div class="participant-left">
                @if(!empty($participante->usuario->avatar_url))
                    <img src="{{ $participante->usuario->avatar_url }}" alt="{{ $participante->usuario->username }}" class="participant-avatar">
                @else
                    <div class="participant-avatar-fallback">
                        {{ strtoupper(substr($participante->usuario->username, 0, 1)) }}
                    </div>
                @endif
            </div>

                            <div class="participant-info">
                                <div class="participant-name">
                                    {{ $participante->usuario->username }}
                                    @if($isCriador)
                                        <span style="color: var(--accent); font-size: 0.75rem; margin-left: 0.5rem;">• Criador</span>
                                    @endif
                                </div>
                                <div class="participant-role">
    @if($papel === 'mestre')
        <span class="role-badge role-mestre">
            <i class="fa-solid fa-crown"></i>
            Mestre
        </span>
    @elseif($papel === 'admin_sala')
        <span class="role-badge role-admin">
            <i class="fa-solid fa-user-shield"></i>
            Admin
        </span>
    @else
        <span class="role-badge role-jogador">
            <i class="fa-solid fa-user"></i>
            Jogador
        </span>
    @endif
</div>
                            </div>

                            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                                <div class="participant-status">
                                    <span class="status-dot" data-user-dot data-user-id="{{ $uid }}"></span>
                                    <span class="status-text" data-user-status data-user-id="{{ $uid }}">Offline</span>
                                </div>

                                @if((int)auth()->id() === (int)$sala->criador_id && (int)$uid !== (int)$sala->criador_id)
                                    <button type="button" class="manage-permissions-btn" data-user-id="{{ $uid }}" 
                                            style="background: transparent; border: 1px solid rgba(55, 65, 81, 0.8); color: var(--text-secondary); padding: 0.375rem 0.75rem; border-radius: 8px; font-size: 0.75rem; cursor: pointer; transition: var(--transition);"
                                            onmouseover="this.style.borderColor='var(--accent)'; this.style.color='var(--accent)'"
                                            onmouseout="this.style.borderColor='rgba(55, 65, 81, 0.8)'; this.style.color='var(--text-secondary)'">
                                        <i class="fa-solid fa-user-gear me-1"></i> Gerenciar
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if($sala->participantes->isEmpty())
                        <div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: var(--text-muted);">
                            Nenhum participante.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Minhas Permissões -->
            <div class="card-modern fade-in">
                <h2 class="card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Minhas Permissões
                </h2>

                @if($minhas_permissoes)
                    <div class="permissions-grid">
                        <div class="permission-item {{ $minhas_permissoes->pode_criar_conteudo ? 'enabled' : '' }}">
                            <svg class="permission-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v14m7-7H5"/>
                            </svg>
                            <span class="permission-label">Criar Conteúdo</span>
                        </div>

                        <div class="permission-item {{ $minhas_permissoes->pode_editar_grid ? 'enabled' : '' }}">
                            <svg class="permission-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            <span class="permission-label">Editar Grid</span>
                        </div>

                        <div class="permission-item {{ $minhas_permissoes->pode_iniciar_sessao ? 'enabled' : '' }}">
                            <svg class="permission-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="5 3 19 12 5 21 5 3"/>
                            </svg>
                            <span class="permission-label">Iniciar Sessão</span>
                        </div>

                        <div class="permission-item {{ $minhas_permissoes->pode_moderar_chat ? 'enabled' : '' }}">
                            <svg class="permission-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            <span class="permission-label">Moderar Chat</span>
                        </div>

                        <div class="permission-item {{ $minhas_permissoes->pode_convidar_usuarios ? 'enabled' : '' }}">
                            <svg class="permission-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="8.5" cy="7" r="4"/>
                                <line x1="20" y1="8" x2="20" y2="14"/>
                                <line x1="23" y1="11" x2="17" y2="11"/>
                            </svg>
                            <span class="permission-label">Convidar Usuários</span>
                        </div>
                    </div>
                @else
                    <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                        Permissões não atribuídas.
                    </div>
                @endif
            </div>

        </div>

        <!-- Sidebar: Chat -->
        <div class="sala-sidebar">
            <div class="chat-sidebar">
                @include('components.chat-container', ['sala' => $sala])
            </div>
        </div>
    </div>

</div>

<!-- ========== FOOTER ========== -->
<footer class="footer">
    <div class="footer-columns">
        <div>
            <div class="footer-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Ambience RPG" class="logo-img">
            </div>
            <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6; max-width: 320px;">
                Capacitando mestres e aventureiros em todo o mundo com a plataforma definitiva para RPG.
            </p>
        </div>
        <div>
            <h4>Recursos</h4>
            <ul>
                <li onclick="window.location.href='{{ route('salas.index') }}'">Salas</li>
                <li onclick="window.location.href='{{ route('comunidade.feed') }}'">Comunidade</li>
                <li onclick="window.location.href='{{ route('suporte.index') }}'">Suporte</li>
            </ul>
        </div>
        <div>
            <h4>Ajuda</h4>
            <ul>
                <li onclick="window.location.href='{{ route('suporte.create') }}'">Criar Ticket</li>
                <li onclick="window.location.href='{{ route('suporte.index') }}'">Meus Tickets</li>
                <li>Tutoriais</li>
                <li>FAQ</li>
            </ul>
        </div>
        <div>
            <h4>Conecte-se</h4>
            <div style="display: flex; gap: 16px; margin-top: 12px;">
                <svg viewBox="0 0 24 24" fill="var(--text-muted)" style="width: 20px; height: 20px; cursor: pointer; transition: fill 0.2s;" onmouseover="this.style.fill='var(--accent)'" onmouseout="this.style.fill='var(--text-muted)'">
                    <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515a.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0a12.64 12.64 0 0 0-.617-1.25a.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057a19.9 19.9 0 0 0 5.993 3.03a.078.078 0 0 0 .084-.028a14.09 14.09 0 0 0 1.226-1.994a.076.076 0 0 0-.041-.106a13.107 13.107 0 0 1-1.872-.892a.077.077 0 0 1-.008-.128a10.2 10.2 0 0 0 .372-.292a.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127a12.299 12.299 0 0 1-1.873.892a.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028a19.839 19.839 0 0 0 6.002-3.03a.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="footer-bottom">© 2025 Ambience RPG. Todos os direitos reservados.</div>
</footer>

<!-- ========== MODALS ========== -->


{{-- ========== MODAL: EDITAR SALA (VERSÃO MELHORADA) ========== --}}
<div class="modal fade" id="modalEditarSala" tabindex="-1" aria-labelledby="modalEditarSalaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.95)); border: 1px solid var(--border-subtle); border-radius: 20px;">
            
            {{-- HEADER --}}
            <div class="modal-header" style="border-bottom: 1px solid var(--border-subtle); padding: 1.75rem 2rem;">
                <h5 class="modal-title" id="modalEditarSalaLabel" style="color: #fff; font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 24px; height: 24px;">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Configurações da Sala
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar" style="filter: invert(1);"></button>
            </div>
            
            {{-- BODY --}}
            <div class="modal-body" style="padding: 2rem;">
                
                {{-- FORMULÁRIO DE EDIÇÃO --}}
                <form id="formEditarSala">
                    <input type="hidden" id="edit_sala_id">
                    
                    {{-- Nome da Sala --}}
                    <div class="mb-4">
                        <label class="form-label" style="color: var(--text-secondary); font-weight: 600; font-size: 0.95rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                <path d="M2 17l10 5 10-5"/>
                                <path d="M2 12l10 5 10-5"/>
                            </svg>
                            Nome da Sala *
                        </label>
                        <input type="text" id="edit_nome" class="form-control" placeholder="Ex: Mesa de D&D" required
                               style="background: rgba(17, 24, 39, 0.8); border: 2px solid rgba(55, 65, 81, 0.8); border-radius: 12px; color: #f9fafb; padding: 0.875rem 1rem; font-size: 0.95rem; transition: all 0.3s;">
                    </div>
                    
                    {{-- Descrição --}}
                    <div class="mb-4">
                        <label class="form-label" style="color: var(--text-secondary); font-weight: 600; font-size: 0.95rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10 9 9 9 8 9"/>
                            </svg>
                            Descrição
                        </label>
                        <textarea id="edit_descricao" class="form-control" rows="3" placeholder="Descreva sua sala..."
                                  style="background: rgba(17, 24, 39, 0.8); border: 2px solid rgba(55, 65, 81, 0.8); border-radius: 12px; color: #f9fafb; padding: 0.875rem 1rem; font-size: 0.95rem; resize: vertical; transition: all 0.3s;"></textarea>
                    </div>
                    
                    {{-- Grid: Tipo e Max Participantes --}}
                    <div class="row g-3 mb-4">
                        {{-- Tipo de Sala --}}
                        <div class="col-md-6">
    <label class="form-label" style="color: var(--text-secondary); font-weight: 600; font-size: 0.95rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
        Tipo de Sala *
    </label>
    <div class="custom-select-wrapper" style="position: relative;">
        <select id="edit_tipo" class="form-select" required
                style="background: rgba(17, 24, 39, 0.8); border: 2px solid rgba(55, 65, 81, 0.8); border-radius: 12px; color: #f9fafb; padding: 0.875rem 1rem 0.875rem 2.75rem; font-size: 0.95rem; transition: all 0.3s; appearance: none;">
            <option value="publica">Pública (qualquer um pode entrar)</option>
            <option value="privada">Privada (requer senha)</option>
        </select>
        
        <!-- Ícone dinâmico baseado na seleção -->
        <svg id="tipo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
             style="position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; pointer-events: none; color: var(--accent);">
            <circle cx="12" cy="12" r="10"/>
            <line x1="2" y1="12" x2="22" y2="12"/>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
        </svg>
        
        <!-- Seta dropdown customizada -->
        <svg viewBox="0 0 16 16" fill="none" stroke="var(--accent)" stroke-width="2"
             style="position: absolute; right: 0.875rem; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; pointer-events: none;">
            <path d="M2 5l6 6 6-6"/>
        </svg>
    </div>
</div>
                        
                        {{-- Máximo de Participantes --}}
                        <div class="col-md-6">
                            <label class="form-label" style="color: var(--text-secondary); font-weight: 600; font-size: 0.95rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                                Máximo de Participantes *
                            </label>
                            <input type="number" id="edit_max_participantes" class="form-control" min="2" max="100" required
                                   style="background: rgba(17, 24, 39, 0.8); border: 2px solid rgba(55, 65, 81, 0.8); border-radius: 12px; color: #f9fafb; padding: 0.875rem 1rem; font-size: 0.95rem; transition: all 0.3s;">
                        </div>
                    </div>
                    
                    {{-- Senha (condicional) --}}
                    <div class="mb-4" id="edit_senha_container" style="display: none;">
                        <label class="form-label" style="color: var(--text-secondary); font-weight: 600; font-size: 0.95rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                                <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                            </svg>
                            Nova Senha (deixe vazio para manter a atual)
                        </label>
                        <input type="password" id="edit_senha" class="form-control" placeholder="Mínimo 4 caracteres"
                               style="background: rgba(17, 24, 39, 0.8); border: 2px solid rgba(55, 65, 81, 0.8); border-radius: 12px; color: #f9fafb; padding: 0.875rem 1rem; font-size: 0.95rem; transition: all 0.3s;">
                    </div>
                    
                    {{-- Alert de feedback --}}
                    <div id="edit_alert" class="alert d-none" role="alert" style="border-radius: 12px; padding: 1rem 1.25rem; font-size: 0.95rem;"></div>
                </form>
                
                {{-- DIVISÓRIA --}}
                <hr style="border-color: rgba(239, 68, 68, 0.2); margin: 2.5rem 0;">
                
                {{-- SEÇÃO DE EXCLUSÃO (ZONA DE PERIGO) --}}
                <div id="deleteZone" style="padding: 1.75rem; background: rgba(239, 68, 68, 0.05); border: 2px solid rgba(239, 68, 68, 0.2); border-radius: 16px;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" style="width: 24px; height: 24px; flex-shrink: 0;">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        <h6 style="color: #ef4444; font-weight: 700; margin: 0; font-size: 1.1rem;">
                            Zona de Perigo
                        </h6>
                    </div>
                    
                    <p style="color: #fca5a5; font-size: 0.9rem; margin-bottom: 1.25rem; line-height: 1.6;">
                        Excluir esta sala é uma ação permanente e irreversível. Todos os dados serão perdidos, incluindo mensagens, histórico de sessões, permissões e arquivos.
                    </p>
                    
                    {{-- Botão para expandir exclusão --}}
                    <button type="button" id="btnMostrarExclusao" class="btn-action" 
        style="background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); width: 100%; padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem; font-weight: 600; font-size: 0.95rem; transition: all 0.3s;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; flex-shrink: 0;">
        <polyline points="3 6 5 6 21 6"/>
        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
        <line x1="10" y1="11" x2="10" y2="17"/>
        <line x1="14" y1="11" x2="14" y2="17"/>
    </svg>
    <span>Excluir Esta Sala Permanentemente</span>
</button>
                    
                    {{-- Formulário de confirmação (oculto inicialmente) --}}
                    <div id="deleteConfirmSection" style="display: none; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(239, 68, 68, 0.2);">
                        <p style="color: #fca5a5; font-size: 0.9rem; margin-bottom: 1rem; font-weight: 600;">
                            Para confirmar a exclusão, digite exatamente o nome da sala abaixo:
                        </p>
                        
                        <div style="background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 1rem;">
                            <code style="color: var(--accent); font-size: 0.95rem; font-weight: 700;">{{ $sala->nome }}</code>
                        </div>
                        
                        <input type="text" id="delete_confirm_nome" class="form-control mb-3" 
                               placeholder="Digite o nome da sala aqui"
                               style="background: rgba(17, 24, 39, 0.8); border: 2px solid rgba(239, 68, 68, 0.3); border-radius: 12px; color: #f9fafb; padding: 0.875rem 1rem; font-size: 0.95rem;">
                        
                        <div style="display: flex; gap: 1rem;">
                            <button type="button" id="btnCancelarExclusao" class="btn-action btn-secondary" style="flex: 1; padding: 0.875rem 1.5rem;">
                                Cancelar
                            </button>
                            <button type="button" id="btnConfirmarExclusao" class="btn-action" disabled
                                    style="flex: 1; padding: 0.875rem 1.5rem; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.4); display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                                Confirmar Exclusão
                            </button>
                        </div>
                        
                        <div id="delete_alert" class="alert d-none mt-3" role="alert" style="border-radius: 12px; padding: 1rem 1.25rem; font-size: 0.95rem;"></div>
                    </div>
                </div>
                
            </div>
            
            {{-- FOOTER --}}
            <div class="modal-footer" style="border-top: 1px solid var(--border-subtle); padding: 1.5rem 2rem;">
                <button type="button" class="btn-action btn-secondary" data-bs-dismiss="modal" style="padding: 0.875rem 1.5rem;">
                    Fechar
                </button>
                <button type="submit" form="formEditarSala" class="btn-action" id="btnSalvarEdicao"
                        style="background: linear-gradient(135deg, #22c55e, #16a34a); color: #052e16; border: none; padding: 0.875rem 1.5rem; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 14px rgba(34, 197, 94, 0.4); font-weight: 700;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Salvar Alterações
                </button>
            </div>
            
        </div>
    </div>
</div>

<!-- Modal: Convidar Usuário -->
<div class="modal fade" id="modalConvite" tabindex="-1" aria-labelledby="modalConviteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('salas.convidar', ['id' => $sala->id]) }}" style="background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.95)); border: 1px solid var(--border-subtle); border-radius: 20px;">
            @csrf
            <div class="modal-header" style="border-bottom: 1px solid var(--border-subtle);">
                <h5 class="modal-title" id="modalConviteLabel" style="color: #fff; font-weight: 700;">Convidar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label" style="color: var(--text-secondary); font-weight: 600;">Email do Usuário</label>
                    <input type="email" name="email" class="form-control" placeholder="usuario@exemplo.com" required
                           style="background: rgba(17, 24, 39, 0.8); border: 2px solid rgba(55, 65, 81, 0.8); border-radius: 12px; color: #f9fafb; padding: 0.875rem 1rem;">
                </div>
                <div class="mb-0">
                    <label class="form-label" style="color: var(--text-secondary); font-weight: 600;">Expira em (horas)</label>
                    <select class="form-select" name="expira_em_horas" required
                            style="background: rgba(17, 24, 39, 0.8); border: 2px solid rgba(55, 65, 81, 0.8); border-radius: 12px; color: #f9fafb; padding: 0.875rem 1rem;">
                        <option value="24">24 horas</option>
                        <option value="72">3 dias</option>
                        <option value="168">1 semana</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border-subtle);">
                <button type="button" class="btn-action btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn-action btn-primary">Enviar Convite</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Confirmar Sair -->
<div class="modal fade" id="confirmSairModal" tabindex="-1" aria-labelledby="confirmSairModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.95)); border: 1px solid var(--border-subtle); border-radius: 20px;">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-subtle);">
                <h5 class="modal-title" id="confirmSairModalLabel" style="color: #fff; font-weight: 700;">Sair da sala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" style="color: var(--text-secondary);">
                Você deseja mesmo sair dessa sala?
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border-subtle);">
                <button type="button" class="btn-action btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="confirmSairBtn" type="button" class="btn-action btn-danger">Sair</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Gerenciar Permissões -->
<div class="modal fade permissions-modal" id="managePermissionsModal" tabindex="-1" aria-labelledby="managePermissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="managePermissionsForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="managePermissionsModalLabel">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        Gerenciar Permissões
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                
                <div class="modal-body">
                    <input type="hidden" id="mp_user_id" value="">
                    
                    <!-- Loading State -->
                    <div class="permissions-loading" id="mp_loading">
                        <div class="permissions-loading-spinner"></div>
                        <span class="permissions-loading-text">Carregando permissões...</span>
                    </div>
                    
                    <!-- User Info (Hidden inicialmente) -->
                    <div class="permissions-user-info d-none" id="mp_user_info">
                        <div class="permissions-user-avatar" id="mp_user_avatar">U</div>
                        <div class="permissions-user-details">
                            <h6 id="mp_username">Usuário</h6>
                            <p id="mp_user_role">Carregando...</p>
                        </div>
                    </div>
                    
                    <!-- Permissions List (Hidden inicialmente) -->
                    <div class="d-none" id="mp_permissions_list">
                        <!-- Criar Conteúdo -->
                        <div class="permission-toggle-item" data-permission="criar_conteudo">
                            <div class="permission-toggle-info">
                                <div class="permission-toggle-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 5v14m7-7H5"/>
                                    </svg>
                                </div>
                                <div class="permission-toggle-content">
                                    <label class="permission-toggle-label" for="mp_pode_criar_conteudo">Criar Conteúdo</label>
                                    <span class="permission-toggle-description">Permite criar fichas, NPCs e itens</span>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="mp_pode_criar_conteudo">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        
                        <!-- Editar Grid -->
                        <div class="permission-toggle-item" data-permission="editar_grid">
                            <div class="permission-toggle-info">
                                <div class="permission-toggle-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </div>
                                <div class="permission-toggle-content">
                                    <label class="permission-toggle-label" for="mp_pode_editar_grid">Editar Grid</label>
                                    <span class="permission-toggle-description">Permite modificar o mapa e cenários</span>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="mp_pode_editar_grid">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        
                        <!-- Iniciar Sessão -->
                        <div class="permission-toggle-item" data-permission="iniciar_sessao">
                            <div class="permission-toggle-info">
                                <div class="permission-toggle-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="5 3 19 12 5 21 5 3"/>
                                    </svg>
                                </div>
                                <div class="permission-toggle-content">
                                    <label class="permission-toggle-label" for="mp_pode_iniciar_sessao">Iniciar Sessão</label>
                                    <span class="permission-toggle-description">Permite criar e começar novas sessões</span>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="mp_pode_iniciar_sessao">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        
                        <!-- Moderar Chat -->
                        <div class="permission-toggle-item" data-permission="moderar_chat">
                            <div class="permission-toggle-info">
                                <div class="permission-toggle-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                    </svg>
                                </div>
                                <div class="permission-toggle-content">
                                    <label class="permission-toggle-label" for="mp_pode_moderar_chat">Moderar Chat</label>
                                    <span class="permission-toggle-description">Permite deletar mensagens e silenciar usuários</span>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="mp_pode_moderar_chat">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        
                        <!-- Convidar Usuários -->
                        <div class="permission-toggle-item" data-permission="convidar_usuarios">
                            <div class="permission-toggle-info">
                                <div class="permission-toggle-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="8.5" cy="7" r="4"/>
                                        <line x1="20" y1="8" x2="20" y2="14"/>
                                        <line x1="23" y1="11" x2="17" y2="11"/>
                                    </svg>
                                </div>
                                <div class="permission-toggle-content">
                                    <label class="permission-toggle-label" for="mp_pode_convidar_usuarios">Convidar Usuários</label>
                                    <span class="permission-toggle-description">Permite gerar links e convidar novos membros</span>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="mp_pode_convidar_usuarios">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    
                    <div id="mp_alert" class="d-none alert" role="alert"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-action btn-secondary" data-bs-dismiss="modal">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        Cancelar
                    </button>
                    <button type="submit" class="btn-action btn-primary" id="mp_save_btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Salvar Permissões
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========== SCRIPTS ========== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.11/dist/clipboard.min.js"></script>

<!-- Presence Channel para status Online/Offline -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const salaId = {{ $sala->id }};
    const channelName = `sala.${salaId}`;

    function setOnline(userId, online) {
        const card = document.getElementById(`user-${userId}`);
        if (!card) return;

        const statusEl = card.querySelector(`[data-user-status][data-user-id="${userId}"]`);
        const dotEl = card.querySelector(`[data-user-dot][data-user-id="${userId}"]`);

        if (statusEl) {
            statusEl.textContent = online ? 'Online' : 'Offline';
            statusEl.style.color = online ? 'var(--accent)' : 'var(--text-muted)';
        }
        if (dotEl) {
            if (online) {
                dotEl.classList.add('online');
            } else {
                dotEl.classList.remove('online');
            }
        }
    }

    // Marca todos como offline inicialmente
    document.querySelectorAll('[data-user-status]').forEach(function(el) {
        el.textContent = 'Offline';
        el.style.color = 'var(--text-muted)';
    });
    document.querySelectorAll('[data-user-dot]').forEach(function(el) {
        el.classList.remove('online');
    });

    // Confere se Echo foi injetado
    if (!window.Echo) {
        console.warn('Echo não encontrado. Verifique Vite/Bootstrap.ts.');
        return;
    }

    // Entra no presence channel da sala
    const presence = window.Echo.join(channelName)
        .here((users) => {
            users.forEach((u) => setOnline(u.id, true));
        })
        .joining((u) => {
            setOnline(u.id, true);
        })
        .leaving((u) => {
            setOnline(u.id, false);
        })
        .error((e) => {
            console.error('Erro no presence channel:', e);
        });

    // Ao fechar/navegar, sair do canal
    window.addEventListener('beforeunload', () => {
        try { window.Echo.leave(channelName); } catch (_) {}
    });
});
</script>

<!-- Script: Sair da Sala -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btnSairSala');
    const confirmBtn = document.getElementById('confirmSairBtn');

    if (!btn || !confirmBtn) return;

    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) return meta.getAttribute('content');
        return '';
    }

    btn.addEventListener('click', function () {
        const modalEl = document.getElementById('confirmSairModal');
        if (window.bootstrap && modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        } else {
            if (confirm('Você deseja mesmo sair dessa sala?')) {
                submitSair();
            }
        }
    });

    confirmBtn.addEventListener('click', function () {
        confirmBtn.disabled = true;
        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = 'Saindo...';
        submitSair().finally(() => {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = originalText;
        });
    });

    async function submitSair() {
        try {
            const csrfToken = getCsrfToken();
            const url = "{{ route('salas.sair', ['id' => $sala->id]) }}";

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            });

            const json = await res.json();

            if (res.ok && json.success) {
                const modalEl = document.getElementById('confirmSairModal');
                if (window.bootstrap && modalEl) {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }
                try {
                    if (window.Echo) {
                        Echo.leave('presence-sala.{{ $sala->id }}');
                    }
                } catch(e) { console.warn('Erro ao deixar channel:', e); }
                
                window.location.replace(json.redirect_to || "{{ route('salas.index') }}");
            } else {
                alert(json.message || 'Erro ao sair da sala.');
            }
        } catch (err) {
            console.error('Erro ao tentar sair da sala:', err);
            alert('Erro de rede ao tentar sair. Tente novamente.');
        }
    }
});
</script>

<!-- Script: Gerenciar Permissões (Atualizado) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('managePermissionsModal');
    const mpForm = document.getElementById('managePermissionsForm');
    const mpUserId = document.getElementById('mp_user_id');
    const mpSaveBtn = document.getElementById('mp_save_btn');
    const mpAlert = document.getElementById('mp_alert');
    const mpLoading = document.getElementById('mp_loading');
    const mpUserInfo = document.getElementById('mp_user_info');
    const mpPermissionsList = document.getElementById('mp_permissions_list');
    const mpUsername = document.getElementById('mp_username');
    const mpUserRole = document.getElementById('mp_user_role');
    const mpUserAvatar = document.getElementById('mp_user_avatar');

    const fields = {
        pode_criar_conteudo: document.getElementById('mp_pode_criar_conteudo'),
        pode_editar_grid: document.getElementById('mp_pode_editar_grid'),
        pode_iniciar_sessao: document.getElementById('mp_pode_iniciar_sessao'),
        pode_moderar_chat: document.getElementById('mp_pode_moderar_chat'),
        pode_convidar_usuarios: document.getElementById('mp_pode_convidar_usuarios'),
    };

    function getCsrf() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    function getShowUrl(salaId, userId) {
        return `/salas/${salaId}/participantes/${userId}/permissoes`;
    }
    
    function getUpdateUrl(salaId, userId) {
        return `/salas/${salaId}/participantes/${userId}/permissoes`;
    }

    const SALA_ID = "{{ $sala->id }}";

    // Atualizar classes ativas nos items
    function updateActiveStates() {
        document.querySelectorAll('.permission-toggle-item').forEach(item => {
            const permission = item.dataset.permission;
            const checkbox = item.querySelector('input[type="checkbox"]');
            
            if (checkbox && checkbox.checked) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    }

    // Event listeners para os toggles
    Object.values(fields).forEach(field => {
        if (field) {
            field.addEventListener('change', updateActiveStates);
        }
    });

    // Clicar no item inteiro para alternar
    document.querySelectorAll('.permission-toggle-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (e.target.tagName !== 'INPUT' && !e.target.closest('.toggle-switch')) {
                const checkbox = this.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    updateActiveStates();
                }
            }
        });
    });

    // Abrir modal
    document.body.addEventListener('click', function (e) {
        const el = e.target.closest('.manage-permissions-btn');
        if (!el) return;

        e.preventDefault();
        const userId = el.getAttribute('data-user-id');
        if (!userId) return;

        mpUserId.value = userId;
        
        // Resetar estado do modal
        mpAlert.classList.add('d-none');
        mpAlert.innerText = '';
        mpLoading.classList.remove('d-none');
        mpUserInfo.classList.add('d-none');
        mpPermissionsList.classList.add('d-none');
        
        // Limpar campos
        Object.values(fields).forEach(f => { if (f) f.checked = false; });
        updateActiveStates();

        // 🎯 ABRIR MODAL IMEDIATAMENTE
        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        // 📡 Carregar dados em paralelo
        const url = getShowUrl(SALA_ID, userId);
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrf()
            },
            credentials: 'same-origin'
        })
        .then(async res => {
            if (!res.ok) {
                let json = {};
                try { json = await res.json(); } catch (e) {}
                throw (json && json.message) ? json : { message: 'Erro ao buscar permissões.', status: res.status };
            }
            return res.json();
        })
        .then(data => {
            const permissoes = (data && data.permissoes) ? data.permissoes : (data || {});
            const participante = data.participante || {};
            const usuario = participante.usuario || {};

            // Preencher dados do usuário
            if (usuario.username) {
    mpUsername.textContent = usuario.username;
    
    // 🔍 Debug - ver o que está vindo
    console.log('🖼️ Avatar Debug:', {
        username: usuario.username,
        avatar_url: usuario.avatar_url,
        avatar_exists: !!usuario.avatar_url,
        avatar_length: usuario.avatar_url ? usuario.avatar_url.length : 0
    });
    
    // 🖼️ Avatar - Versão DEFINITIVA
    mpUserAvatar.innerHTML = ''; // Limpar TUDO
    
    if (usuario.avatar_url) {
        // Criar e configurar imagem
        const avatarImg = document.createElement('img');
        avatarImg.src = usuario.avatar_url;
        avatarImg.alt = usuario.username;
        avatarImg.className = 'permissions-user-avatar-img';
        
        // Fallback se falhar
        avatarImg.onerror = function() {
            console.warn('⚠️ Falha ao carregar avatar, usando fallback');
            mpUserAvatar.innerHTML = '';
            mpUserAvatar.textContent = usuario.username.charAt(0).toUpperCase();
            mpUserAvatar.style.fontSize = '1.25rem';
            mpUserAvatar.style.fontWeight = '700';
            mpUserAvatar.style.color = 'var(--accent)';
        };
        
        // Quando carregar com sucesso
        avatarImg.onload = function() {
            console.log('✅ Avatar carregado com sucesso!');
        };
        
        mpUserAvatar.appendChild(avatarImg);
        
        // Resetar estilos de texto
        mpUserAvatar.style.fontSize = '';
        mpUserAvatar.style.fontWeight = '';
        mpUserAvatar.style.color = '';
        
    } else {
        // Sem avatar - mostrar inicial
        console.log('ℹ️ Sem avatar, mostrando inicial');
        mpUserAvatar.textContent = usuario.username.charAt(0).toUpperCase();
        mpUserAvatar.style.fontSize = '1.25rem';
        mpUserAvatar.style.fontWeight = '700';
        mpUserAvatar.style.color = 'var(--accent)';
    }
}

            // Papel do usuário
            const papelMap = {
                'mestre': '👑 Mestre',
                'admin_sala': '🛡️ Admin',
                'jogador': '👤 Jogador'
            };
            mpUserRole.textContent = papelMap[participante.papel] || 'Jogador';

            // Preencher permissões
            fields.pode_criar_conteudo.checked = !!permissoes.pode_criar_conteudo;
            fields.pode_editar_grid.checked = !!permissoes.pode_editar_grid;
            fields.pode_iniciar_sessao.checked = !!permissoes.pode_iniciar_sessao;
            fields.pode_moderar_chat.checked = !!permissoes.pode_moderar_chat;
            fields.pode_convidar_usuarios.checked = !!permissoes.pode_convidar_usuarios;

            updateActiveStates();

            // Ocultar loading e mostrar conteúdo
            mpLoading.classList.add('d-none');
            mpUserInfo.classList.remove('d-none');
            mpPermissionsList.classList.remove('d-none');
        })
        .catch(err => {
            mpLoading.classList.add('d-none');
            mpAlert.className = 'alert alert-danger';
            mpAlert.innerText = err.message || 'Erro ao buscar permissões.';
            mpAlert.classList.remove('d-none');
        });
    });

    // Salvar permissões
    mpForm.addEventListener('submit', function (e) {
        e.preventDefault();
        
        const originalBtnText = mpSaveBtn.innerHTML;
        mpSaveBtn.disabled = true;
        mpSaveBtn.innerHTML = `
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;animation:spin 0.6s linear infinite;">
                <circle cx="12" cy="12" r="10"/>
            </svg>
            Salvando...
        `;

        const userId = mpUserId.value;
        const payload = {
            pode_criar_conteudo: !!fields.pode_criar_conteudo.checked,
            pode_editar_grid: !!fields.pode_editar_grid.checked,
            pode_iniciar_sessao: !!fields.pode_iniciar_sessao.checked,
            pode_moderar_chat: !!fields.pode_moderar_chat.checked,
            pode_convidar_usuarios: !!fields.pode_convidar_usuarios.checked
        };

        const url = getUpdateUrl(SALA_ID, userId);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrf()
            },
            credentials: 'same-origin',
            body: JSON.stringify(payload)
        })
        .then(async res => {
            if (!res.ok) {
                const json = await res.json().catch(() => ({}));
                throw json;
            }
            return res.json();
        })
        .then(json => {
            mpAlert.className = 'alert alert-success';
            mpAlert.innerText = json.message || 'Permissões atualizadas com sucesso! ✨';
            mpAlert.classList.remove('d-none');

            mpSaveBtn.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Salvo!
            `;

            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
                
                mpSaveBtn.disabled = false;
                mpSaveBtn.innerHTML = originalBtnText;
            }, 1200);
        })
        .catch(err => {
            mpAlert.className = 'alert alert-danger';
            mpAlert.innerText = err.message || 'Erro ao salvar permissões.';
            mpAlert.classList.remove('d-none');
            
            mpSaveBtn.disabled = false;
            mpSaveBtn.innerHTML = originalBtnText;
        });
    });

    // Resetar ao fechar
    modalEl.addEventListener('hidden.bs.modal', function () {
        mpAlert.classList.add('d-none');
        mpLoading.classList.remove('d-none');
        mpUserInfo.classList.add('d-none');
        mpPermissionsList.classList.add('d-none');
    });
});
</script>

<!-- Script: Iniciar Sessão -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btnIniciarSessao');
    if (!btn) return;

    btn.addEventListener('click', async () => {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Iniciando...';

        try {
            const res = await fetch(`/salas/{{ $sala->id }}/iniciar-sessao`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ nome_sessao: null, configuracoes: {} })
            });
            const data = await res.json();
            if (data.success) {
                window.location.href = data.redirect_to;
            } else {
                alert(data.message || 'Erro ao iniciar sessão.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-play me-2"></i> Iniciar Sessão';
            }
        } catch {
            alert('Erro ao iniciar sessão. Tente novamente.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-play me-2"></i> Iniciar Sessão';
        }
    });
});
</script>

<!-- Script: Escutar Sessão Iniciada -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('[Sala] Página carregada, configurando WebSocket...');
    
    if (typeof window.Echo === 'undefined') {
        console.error('[Sala] Echo não está disponível!');
        return;
    }

    const salaId = {{ $sala->id }};
    const userId = {{ auth()->id() }};
    
    console.log(`[Sala] Conectando ao canal: sala.${salaId}`);
    
    const salaChannel = window.Echo.join(`sala.${salaId}`);
    
    salaChannel.listen('.session.started', function(data) {
        console.log('[Sala] 🎮 SESSÃO INICIADA! Evento recebido:', data);
        
        if (!data || !data.redirect_to) {
            console.error('[Sala] Dados do evento inválidos:', data);
            return;
        }
        
        console.log('[Sala] Redirecionando para:', data.redirect_to);
        window.location.href = data.redirect_to;
    });
    
    salaChannel.here((users) => {
        console.log('[Sala] 👥 Usuários online:', users);
    });
    
    salaChannel.joining((user) => {
        console.log('[Sala] ✅ Entrou:', user.name || user.username);
    });
    
    salaChannel.leaving((user) => {
        console.log('[Sala] ❌ Saiu:', user.name || user.username);
    });
    
    salaChannel.error((error) => {
        console.error('[Sala] ❌ Erro no canal:', error);
    });
    
    console.log('[Sala] ✅ Listener de sessão configurado!');
});
</script>

<!-- Scripts: Banner e Profile Photo Editors -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const bannerBtn = document.getElementById('openBannerEditorBtn');
    if (bannerBtn) {
        bannerBtn.addEventListener('click', () => {
            const salaId = bannerBtn.getAttribute('data-sala-id');
            const bannerUrl = "{{ $sala->banner_url }}";
            const bannerColor = "{{ $sala->banner_color }}";

            if (window.openBannerEditor) {
                window.openBannerEditor(salaId, bannerUrl || null, bannerColor || null);
            } else {
                console.error('openBannerEditor indisponível');
            }
        });
    }

    const photoBtn = document.getElementById('openProfilePhotoEditorBtn');
    if (photoBtn) {
        photoBtn.addEventListener('click', () => {
            const salaId = photoBtn.getAttribute('data-sala-id');
            const fotoUrl = photoBtn.getAttribute('data-foto-url') || "{{ $sala->profile_photo_url ?? '' }}";

            if (window.openProfilePhotoEditor) {
                window.openProfilePhotoEditor(salaId, fotoUrl || null);
            } else {
                console.error('openProfilePhotoEditor indisponível');
            }
        });
    }
});
</script>

@include('partials.banner-editor')
@include('partials.profile-photo-editor')
@include('partials.invite-links-manager')
@include('partials.chat-denuncia-modal')

<script>
// ========== SISTEMA DE CORREÇÃO E DEBUG DE MODAIS ==========
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 [Modal Fix] Iniciando correção de posicionamento...');
    
    const modals = [
        'inviteLinksModal',
        'modalBannerEditor',
        'modalProfileEditor',
        'managePermissionsModal',
        'confirmSairModal',
        'modalConvite'
    ];
    
    let movedCount = 0;
    let alreadyCorrect = 0;
    
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        
        if (modal) {
            // Verificar se está dentro de outro modal
            let parent = modal.parentElement;
            let isInsideModal = false;
            let depth = 0;
            
            while (parent && parent !== document.body && depth < 10) {
                if (parent.classList && parent.classList.contains('modal')) {
                    isInsideModal = true;
                    console.warn(`⚠️ [Modal Fix] "${modalId}" está dentro de outro modal!`);
                    break;
                }
                parent = parent.parentElement;
                depth++;
            }
            
            // Se não está diretamente no body, mover
            if (modal.parentElement !== document.body) {
                console.log(`🔄 [Modal Fix] Movendo "${modalId}" para o body...`);
                document.body.appendChild(modal);
                movedCount++;
                console.log(`✅ [Modal Fix] "${modalId}" movido com sucesso!`);
            } else {
                alreadyCorrect++;
            }
        }
    });
    
    console.log(`📊 [Modal Fix] Resumo: ${movedCount} movidos, ${alreadyCorrect} já corretos`);
    console.log('✅ [Modal Fix] Correção concluída!');
});

// Função de debug para testar modal
window.debugModal = function() {
    console.log('🔍 ===== DEBUG DO MODAL DE LINKS =====');
    
    const modal = document.getElementById('inviteLinksModal');
    
    if (!modal) {
        console.error('❌ Modal "inviteLinksModal" não encontrado!');
        return;
    }
    
    console.log('✅ Modal encontrado');
    console.log('📍 Parent:', modal.parentElement ? modal.parentElement.tagName : 'null');
    console.log('👁️ Display:', window.getComputedStyle(modal).display);
    console.log('📊 Classes:', modal.className);
    console.log('🎯 Z-index:', window.getComputedStyle(modal).zIndex);
    
    // Verificar hierarquia
    let parent = modal.parentElement;
    let depth = 0;
    console.log('🌳 Hierarquia:');
    while (parent && depth < 5) {
        console.log(`  ${'  '.repeat(depth)}↳ ${parent.tagName}${parent.id ? '#' + parent.id : ''}${parent.className ? '.' + parent.className.split(' ')[0] : ''}`);
        
        if (parent.classList.contains('modal')) {
            console.error(`  ❌ ENCONTRADO MODAL PAI: ${parent.id || 'sem ID'}`);
        }
        
        parent = parent.parentElement;
        depth++;
    }
    
    // Verificar Bootstrap
    if (typeof bootstrap === 'undefined') {
        console.error('❌ Bootstrap não está carregado!');
        return;
    }
    
    console.log('✅ Bootstrap está carregado');
    
    // Tentar abrir manualmente
    console.log('🚀 Abrindo modal...');
    try {
        const bsModal = new bootstrap.Modal(modal, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
        bsModal.show();
        console.log('✅ Modal aberto com sucesso!');
    } catch (error) {
        console.error('❌ Erro ao abrir modal:', error);
    }
    
    console.log('===================================');
};

// Listener adicional para debug
document.addEventListener('click', function(e) {
    if (e.target.closest('[data-bs-target="#inviteLinksModal"]')) {
        console.log('🔘 [Click] Botão de links clicado!');
        
        setTimeout(() => {
            const modal = document.getElementById('inviteLinksModal');
            if (modal) {
                const isVisible = window.getComputedStyle(modal).display !== 'none';
                console.log(isVisible ? '✅ Modal está visível' : '❌ Modal não está visível');
                
                if (!isVisible) {
                    console.log('🔧 Tentando forçar abertura...');
                    window.debugModal();
                }
            }
        }, 500);
    }
});
</script>

<!-- NSFW Detection -->
<script src="https://unpkg.com/nsfwjs@2.4.2/dist/nsfwjs.min.js"></script>
<script src="{{ asset('js/moderation.js') }}"></script>
<script src="{{ asset('js/chat.js') }}"></script>
<script src="{{ asset('js/nsfw-detector.js') }}"></script>
<script src="{{ asset('js/nsfw-alert.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    try {
        const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
        if (conn && (conn.saveData || /2g/.test(conn.effectiveType || ''))) {
            console.log('Pulando pre-load do modelo (conexão lenta / save-data).');
            return;
        }
        window.NSFWDetector.loadModel()
            .then(() => {
                console.log('Modelo NSFW pré-carregado.');
            })
            .catch(err => {
                console.warn('Falha ao pré-carregar modelo NSFW:', err);
            });
    } catch (e) { 
        console.warn('Erro no preloader NSFW:', e); 
    }
});
</script>

<!-- Scripts: User Menu e Notificações -->
<script>
// ========== DROPDOWN DE USUÁRIO ==========
(function(){
    const userAvatarBtn = document.getElementById('userAvatarBtn');
    const userDropdown = document.getElementById('userDropdown');

    if(!userAvatarBtn || !userDropdown) return;

    userAvatarBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        userDropdown.classList.toggle('active');
        const notificationModal = document.getElementById('notificationModal');
        if(notificationModal) notificationModal.classList.remove('active');
    });

    document.addEventListener('click', (e) => {
        if(!userDropdown.contains(e.target) && !userAvatarBtn.contains(e.target)) {
            userDropdown.classList.remove('active');
        }
    });

    document.addEventListener('keydown', (e) => {
        if(e.key === 'Escape' && userDropdown.classList.contains('active')) {
            userDropdown.classList.remove('active');
        }
    });
})();
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Iniciando diagnóstico de variáveis CSS...');
    
    const root = document.documentElement;
    const style = getComputedStyle(root);
    
    // Lista de TODAS as variáveis que deveriam existir
    const requiredVars = [
        '--bg-dark',
        '--bg-card', 
        '--bg-elevated',
        '--border-subtle',
        '--border-hover',
        '--text-primary',
        '--text-secondary',
        '--text-muted',
        '--muted',  // 👈 ESTA É A QUE FALTA!
        '--accent',
        '--accent-light',
        '--accent-dark',
        '--accent-glow',
        '--gradient-start',
        '--gradient-end',
        '--transition'
    ];
    
    console.log('📋 Verificando variáveis CSS:');
    console.log('═'.repeat(50));
    
    const missing = [];
    const defined = [];
    
    requiredVars.forEach(varName => {
        const value = style.getPropertyValue(varName).trim();
        
        if (!value) {
            missing.push(varName);
            console.error(`❌ ${varName} - NÃO DEFINIDA!`);
        } else {
            defined.push(varName);
            console.log(`✅ ${varName}: ${value}`);
        }
    });
    
    console.log('═'.repeat(50));
    console.log(`📊 Resultado: ${defined.length}/${requiredVars.length} variáveis definidas`);
    
    if (missing.length > 0) {
        console.error('🚨 VARIÁVEIS FALTANDO:', missing);
        console.error('🔧 SOLUÇÃO: Adicione estas linhas no :root do CSS:');
        
        // Definir valores padrão para variáveis faltando
        const defaults = {
            '--muted': '#9ca3af',
            '--text-primary': '#f9fafb',
            '--text-secondary': '#d1d5db',
            '--text-muted': '#9ca3af',
            '--accent': '#22c55e'
        };
        
        missing.forEach(varName => {
            const defaultValue = defaults[varName] || '#ffffff';
            console.log(`${varName}: ${defaultValue};`);
            
            // Aplicar correção temporária
            root.style.setProperty(varName, defaultValue);
        });
        
        console.log('✅ Correções temporárias aplicadas via JavaScript');
        console.log('⚠️  Para correção permanente, adicione essas linhas no :root do CSS');
    } else {
        console.log('✅ Todas as variáveis CSS estão definidas corretamente!');
    }
    
    // Verificar elementos que usam var(--accent)
    console.log('\n🎨 Verificando elementos que usam --accent:');
    
    const accentElements = document.querySelectorAll(`
        .sala-meta-item svg,
        .card-title svg,
        .logo svg,
        .notification-btn svg,
        .tipo-publica,
        .permission-item.enabled,
        .nav-links a.active
    `);
    
    console.log(`Encontrados ${accentElements.length} elementos usando --accent`);
    
    accentElements.forEach((el, i) => {
        const computed = getComputedStyle(el);
        const color = computed.color || computed.stroke || computed.fill;
        
        if (color && !color.includes('22, 197, 94') && !color.includes('#22c55e')) {
            console.warn(`⚠️  Elemento ${i + 1} (${el.tagName}) não está usando accent corretamente:`, color);
        }
    });
});

// Função para aplicar fix instantâneo
function applyInstantFix() {
    console.log('🔧 Aplicando correção instantânea...');
    
    const root = document.documentElement;
    
    // Forçar TODAS as variáveis
    root.style.setProperty('--text-primary', '#f9fafb');
    root.style.setProperty('--text-secondary', '#d1d5db');
    root.style.setProperty('--text-muted', '#9ca3af');
    root.style.setProperty('--muted', '#9ca3af');  // 👈 ESSENCIAL
    root.style.setProperty('--accent', '#22c55e');
    root.style.setProperty('--accent-light', '#16a34a');
    root.style.setProperty('--accent-dark', '#15803d');
    
    // Forçar cores diretamente nos elementos problemáticos
    document.querySelectorAll('.sala-meta-item').forEach(el => {
        el.style.color = '#d1d5db';
    });
    
    document.querySelectorAll('.sala-meta-item svg').forEach(svg => {
        svg.style.stroke = '#22c55e';
    });
    
    document.querySelectorAll('.tipo-publica').forEach(el => {
        el.style.color = '#22c55e';
        el.style.borderColor = '#22c55e';
    });
    
    document.querySelectorAll('.card-title').forEach(el => {
        el.style.color = '#ffffff';
    });
    
    document.querySelectorAll('.card-title svg').forEach(svg => {
        svg.style.stroke = '#22c55e';
    });
    
    console.log('✅ Fix instantâneo aplicado!');
}

// Executar fix automaticamente
setTimeout(applyInstantFix, 100);

// Expor função global para teste manual
window.fixColors = applyInstantFix;
window.debugCSS = function() {
    const root = document.documentElement;
    const style = getComputedStyle(root);
    
    console.table({
        'text-primary': style.getPropertyValue('--text-primary'),
        'text-secondary': style.getPropertyValue('--text-secondary'),
        'text-muted': style.getPropertyValue('--text-muted'),
        'muted': style.getPropertyValue('--muted'),
        'accent': style.getPropertyValue('--accent')
    });
};

console.log('💡 Use window.fixColors() para reaplicar correções');
console.log('💡 Use window.debugCSS() para ver todas as variáveis');
</script>

<script>
// ========== SISTEMA DE LOADING CORRIGIDO ========== 
(function() {
    console.log('🚀 Iniciando sistema de loading melhorado...');
    
    const loadingScreen = document.getElementById('loadingScreen');
    const progressBar = document.getElementById('loadingProgressBar');
    const tipText = document.getElementById('loadingTipText');
    
    // Verificar se elementos existem
    if (!loadingScreen || !progressBar || !tipText) {
        console.warn('⚠️ Elementos de loading não encontrados');
        return;
    }
    
    // Configurações de timing
    const config = {
        minLoadingTime: 2500,
        stageDelay: 600,
        progressSmoothness: 0.6,
        fadeOutDuration: 800
    };
    
    // Estados de carregamento
    const loadingState = {
        websocket: false,
        chat: false,
        ready: false
    };
    
    const startTime = Date.now();
    
    // Dicas rotativas
    const tips = [
        'Preparando sua aventura...',
        'Conectando com outros jogadores...',
        'Carregando histórico de mensagens...',
        'Sincronizando dados da sala...',
        'Quase lá! Finalizando configurações...'
    ];
    
    let currentTipIndex = 0;
    
    // Rotacionar dicas
    const tipInterval = setInterval(() => {
        currentTipIndex = (currentTipIndex + 1) % tips.length;
        tipText.style.transition = 'opacity 0.3s ease';
        tipText.style.opacity = '0';
        
        setTimeout(() => {
            tipText.textContent = tips[currentTipIndex];
            tipText.style.opacity = '1';
        }, 300);
    }, 3500);
    
    // Atualizar progresso
    function updateProgress() {
        const completed = Object.values(loadingState).filter(Boolean).length;
        const total = Object.keys(loadingState).length;
        const percentage = (completed / total) * 100;
        
        progressBar.style.transition = `width ${config.progressSmoothness}s cubic-bezier(0.4, 0, 0.2, 1)`;
        progressBar.style.width = `${percentage}%`;
        
        console.log('📊 Progresso:', {
            websocket: loadingState.websocket ? '✅' : '⏳',
            chat: loadingState.chat ? '✅' : '⏳',
            ready: loadingState.ready ? '✅' : '⏳',
            percentage: `${percentage.toFixed(0)}%`
        });
        
        if (percentage === 100) {
            checkMinimumTimeAndComplete();
        }
    }
    
    // Verificar tempo mínimo
    function checkMinimumTimeAndComplete() {
        const elapsed = Date.now() - startTime;
        const remaining = config.minLoadingTime - elapsed;
        
        if (remaining > 0) {
            console.log(`⏰ Aguardando ${remaining}ms...`);
            setTimeout(completeLoading, remaining);
        } else {
            completeLoading();
        }
    }
    
    // Marcar como carregado
    function markAsLoaded(item, skipDelay = false) {
        // ⚠️ CORREÇÃO: Validar se item existe
        if (!item) {
            console.warn('⚠️ Item inválido para markAsLoaded');
            return;
        }
        
        const itemKey = item.toLowerCase();
        const statusItem = document.getElementById(`status${item}`);
        
        if (!statusItem) {
            console.warn(`⚠️ Elemento status${item} não encontrado`);
            return;
        }
        
        if (loadingState[itemKey]) {
            console.log(`ℹ️ ${item} já está carregado`);
            return;
        }
        
        const delay = skipDelay ? 0 : config.stageDelay;
        
        setTimeout(() => {
            statusItem.classList.remove('active');
            statusItem.classList.add('completed');
            
            const svg = statusItem.querySelector('svg');
            if (svg) {
                svg.innerHTML = `
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9 12l2 2 4-4"/>
                `;
            }
            
            loadingState[itemKey] = true;
            updateProgress();
            
            console.log(`✅ ${item} carregado`);
        }, delay);
    }
    
    // Marcar como ativo
    function markAsActive(item) {
        if (!item) return;
        
        const statusItem = document.getElementById(`status${item}`);
        
        if (statusItem && !statusItem.classList.contains('completed')) {
            statusItem.classList.add('active');
            console.log(`⏳ ${item} ativado`);
        }
    }
    
    // Completar loading
    function completeLoading() {
        console.log('✅ Loading completo!');
        
        clearInterval(tipInterval);
        
        tipText.style.opacity = '0';
        setTimeout(() => {
            tipText.textContent = '🎮 Tudo pronto! Entrando...';
            tipText.style.opacity = '1';
        }, 300);
        
        setTimeout(() => {
            loadingScreen.style.transition = `opacity ${config.fadeOutDuration}ms ease`;
            loadingScreen.classList.add('fade-out');
            
            setTimeout(() => {
                if (loadingScreen && loadingScreen.parentNode) {
                    loadingScreen.parentNode.removeChild(loadingScreen);
                    console.log('🗑️ Loading screen removido');
                }
            }, config.fadeOutDuration);
        }, 800);
    }
    
    // ========== CARREGAMENTO SEQUENCIAL ==========
    
    let currentStage = 0;
    const stages = ['WebSocket', 'Chat', 'Ready'];
    
    markAsActive(stages[0]);
    
    // ⚠️ CORREÇÃO: Validação completa
    function checkAndAdvanceStage() {
        // Verificar se currentStage é válido
        if (currentStage >= stages.length) {
            console.log('ℹ️ Todas as etapas concluídas');
            return;
        }
        
        const currentStageName = stages[currentStage];
        
        // Verificar se o nome da etapa existe
        if (!currentStageName) {
            console.warn('⚠️ Nome da etapa inválido');
            return;
        }
        
        const currentKey = currentStageName.toLowerCase();
        
        // Verificações específicas
        let isComplete = false;
        
        switch(currentStage) {
            case 0: // WebSocket
                isComplete = typeof window.Echo !== 'undefined' && window.Echo;
                break;
            case 1: // Chat
                isComplete = !!document.querySelector('.chat-container, #chatContainer, [data-chat-container]');
                break;
            case 2: // Ready
                isComplete = document.readyState === 'complete';
                break;
        }
        
        if (isComplete && !loadingState[currentKey]) {
            markAsLoaded(currentStageName);
            
            currentStage++;
            if (currentStage < stages.length) {
                setTimeout(() => {
                    markAsActive(stages[currentStage]);
                }, config.stageDelay / 2);
            }
        }
    }
    
    // Verificação periódica
    const checkInterval = setInterval(() => {
        checkAndAdvanceStage();
        
        if (Object.values(loadingState).every(v => v)) {
            clearInterval(checkInterval);
        }
    }, 200);
    
    // Timeout de segurança
    setTimeout(() => {
        if (!Object.values(loadingState).every(v => v)) {
            console.warn('⚠️ Timeout de segurança ativado');
            clearInterval(checkInterval);
            
            stages.forEach((stage) => {
                if (stage) {
                    const key = stage.toLowerCase();
                    if (!loadingState[key]) {
                        markAsLoaded(stage, true);
                    }
                }
            });
        }
    }, 10000);
    
    // ========== EVENT LISTENERS ==========
    
    document.addEventListener('echo:ready', () => {
        console.log('📡 Echo ready event');
        if (!loadingState.websocket) {
            markAsLoaded('WebSocket');
        }
    });
    
    document.addEventListener('chat:ready', () => {
        console.log('💬 Chat ready event');
        if (!loadingState.chat) {
            markAsLoaded('Chat');
        }
    });
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            console.log('📄 DOMContentLoaded');
            if (!loadingState.ready) {
                markAsLoaded('Ready');
            }
        });
    } else if (document.readyState === 'complete') {
        if (!loadingState.ready) {
            markAsLoaded('Ready');
        }
    }
    
    window.addEventListener('load', () => {
        console.log('🌐 Window load');
        if (!loadingState.ready) {
            markAsLoaded('Ready');
        }
    });
    
    console.log('✅ Sistema de loading inicializado');
    console.log(`⏱️ Tempo mínimo: ${config.minLoadingTime}ms`);
})();
</script>

<script>
// ========== SISTEMA DE LOADING CORRIGIDO ========== 
(function() {
    console.log('🚀 Iniciando sistema de loading melhorado...');
    
    const loadingScreen = document.getElementById('loadingScreen');
    const progressBar = document.getElementById('loadingProgressBar');
    const tipText = document.getElementById('loadingTipText');
    
    // Verificar se elementos existem
    if (!loadingScreen || !progressBar || !tipText) {
        console.warn('⚠️ Elementos de loading não encontrados');
        return;
    }
    
    // Configurações de timing
    const config = {
        minLoadingTime: 2500,
        stageDelay: 600,
        progressSmoothness: 0.6,
        fadeOutDuration: 800
    };
    
    // Estados de carregamento
    const loadingState = {
        websocket: false,
        chat: false,
        ready: false
    };
    
    const startTime = Date.now();
    
    // Dicas rotativas
    const tips = [
        'Preparando sua aventura...',
        'Conectando com outros jogadores...',
        'Carregando histórico de mensagens...',
        'Sincronizando dados da sala...',
        'Quase lá! Finalizando configurações...'
    ];
    
    let currentTipIndex = 0;
    
    // Rotacionar dicas
    const tipInterval = setInterval(() => {
        currentTipIndex = (currentTipIndex + 1) % tips.length;
        tipText.style.transition = 'opacity 0.3s ease';
        tipText.style.opacity = '0';
        
        setTimeout(() => {
            tipText.textContent = tips[currentTipIndex];
            tipText.style.opacity = '1';
        }, 300);
    }, 3500);
    
    // Atualizar progresso
    function updateProgress() {
        const completed = Object.values(loadingState).filter(Boolean).length;
        const total = Object.keys(loadingState).length;
        const percentage = (completed / total) * 100;
        
        progressBar.style.transition = `width ${config.progressSmoothness}s cubic-bezier(0.4, 0, 0.2, 1)`;
        progressBar.style.width = `${percentage}%`;
        
        console.log('📊 Progresso:', {
            websocket: loadingState.websocket ? '✅' : '⏳',
            chat: loadingState.chat ? '✅' : '⏳',
            ready: loadingState.ready ? '✅' : '⏳',
            percentage: `${percentage.toFixed(0)}%`
        });
        
        if (percentage === 100) {
            checkMinimumTimeAndComplete();
        }
    }
    
    // Verificar tempo mínimo
    function checkMinimumTimeAndComplete() {
        const elapsed = Date.now() - startTime;
        const remaining = config.minLoadingTime - elapsed;
        
        if (remaining > 0) {
            console.log(`⏰ Aguardando ${remaining}ms...`);
            setTimeout(completeLoading, remaining);
        } else {
            completeLoading();
        }
    }
    
    // Marcar como carregado
    function markAsLoaded(item, skipDelay = false) {
        // ⚠️ CORREÇÃO: Validar se item existe
        if (!item) {
            console.warn('⚠️ Item inválido para markAsLoaded');
            return;
        }
        
        const itemKey = item.toLowerCase();
        const statusItem = document.getElementById(`status${item}`);
        
        if (!statusItem) {
            console.warn(`⚠️ Elemento status${item} não encontrado`);
            return;
        }
        
        if (loadingState[itemKey]) {
            console.log(`ℹ️ ${item} já está carregado`);
            return;
        }
        
        const delay = skipDelay ? 0 : config.stageDelay;
        
        setTimeout(() => {
            statusItem.classList.remove('active');
            statusItem.classList.add('completed');
            
            const svg = statusItem.querySelector('svg');
            if (svg) {
                svg.innerHTML = `
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9 12l2 2 4-4"/>
                `;
            }
            
            loadingState[itemKey] = true;
            updateProgress();
            
            console.log(`✅ ${item} carregado`);
        }, delay);
    }
    
    // Marcar como ativo
    function markAsActive(item) {
        if (!item) return;
        
        const statusItem = document.getElementById(`status${item}`);
        
        if (statusItem && !statusItem.classList.contains('completed')) {
            statusItem.classList.add('active');
            console.log(`⏳ ${item} ativado`);
        }
    }
    
    // Completar loading
    function completeLoading() {
        console.log('✅ Loading completo!');
        
        clearInterval(tipInterval);
        
        tipText.style.opacity = '0';
        setTimeout(() => {
            tipText.textContent = '🎮 Tudo pronto! Entrando...';
            tipText.style.opacity = '1';
        }, 300);
        
        setTimeout(() => {
            loadingScreen.style.transition = `opacity ${config.fadeOutDuration}ms ease`;
            loadingScreen.classList.add('fade-out');
            
            setTimeout(() => {
                if (loadingScreen && loadingScreen.parentNode) {
                    loadingScreen.parentNode.removeChild(loadingScreen);
                    console.log('🗑️ Loading screen removido');
                }
            }, config.fadeOutDuration);
        }, 800);
    }
    
    // ========== CARREGAMENTO SEQUENCIAL ==========
    
    let currentStage = 0;
    const stages = ['WebSocket', 'Chat', 'Ready'];
    
    markAsActive(stages[0]);
    
    // ⚠️ CORREÇÃO: Validação completa
    function checkAndAdvanceStage() {
        // Verificar se currentStage é válido
        if (currentStage >= stages.length) {
            console.log('ℹ️ Todas as etapas concluídas');
            return;
        }
        
        const currentStageName = stages[currentStage];
        
        // Verificar se o nome da etapa existe
        if (!currentStageName) {
            console.warn('⚠️ Nome da etapa inválido');
            return;
        }
        
        const currentKey = currentStageName.toLowerCase();
        
        // Verificações específicas
        let isComplete = false;
        
        switch(currentStage) {
            case 0: // WebSocket
                isComplete = typeof window.Echo !== 'undefined' && window.Echo;
                break;
            case 1: // Chat
                isComplete = !!document.querySelector('.chat-container, #chatContainer, [data-chat-container]');
                break;
            case 2: // Ready
                isComplete = document.readyState === 'complete';
                break;
        }
        
        if (isComplete && !loadingState[currentKey]) {
            markAsLoaded(currentStageName);
            
            currentStage++;
            if (currentStage < stages.length) {
                setTimeout(() => {
                    markAsActive(stages[currentStage]);
                }, config.stageDelay / 2);
            }
        }
    }
    
    // Verificação periódica
    const checkInterval = setInterval(() => {
        checkAndAdvanceStage();
        
        if (Object.values(loadingState).every(v => v)) {
            clearInterval(checkInterval);
        }
    }, 200);
    
    // Timeout de segurança
    setTimeout(() => {
        if (!Object.values(loadingState).every(v => v)) {
            console.warn('⚠️ Timeout de segurança ativado');
            clearInterval(checkInterval);
            
            stages.forEach((stage) => {
                if (stage) {
                    const key = stage.toLowerCase();
                    if (!loadingState[key]) {
                        markAsLoaded(stage, true);
                    }
                }
            });
        }
    }, 10000);
    
    // ========== EVENT LISTENERS ==========
    
    document.addEventListener('echo:ready', () => {
        console.log('📡 Echo ready event');
        if (!loadingState.websocket) {
            markAsLoaded('WebSocket');
        }
    });
    
    document.addEventListener('chat:ready', () => {
        console.log('💬 Chat ready event');
        if (!loadingState.chat) {
            markAsLoaded('Chat');
        }
    });
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            console.log('📄 DOMContentLoaded');
            if (!loadingState.ready) {
                markAsLoaded('Ready');
            }
        });
    } else if (document.readyState === 'complete') {
        if (!loadingState.ready) {
            markAsLoaded('Ready');
        }
    }
    
    window.addEventListener('load', () => {
        console.log('🌐 Window load');
        if (!loadingState.ready) {
            markAsLoaded('Ready');
        }
    });
    
    console.log('✅ Sistema de loading inicializado');
    console.log(`⏱️ Tempo mínimo: ${config.minLoadingTime}ms`);
})();
</script>

<script>
(function(){
  const notificationBtn = document.getElementById('notificationBtn');
  const notificationModal = document.getElementById('notificationModal');
  const closeNotificationModal = document.getElementById('closeNotificationModal');
  const notificationBadge = document.querySelector('.notification-badge');

  if(!notificationBtn || !notificationModal) return;

  let notificacoes = [];
  let offset = 0;
  const limit = 10;

  // Carregar notificações
  async function carregarNotificacoes(append = false) {
    try {
      const response = await fetch(`/api/notificacoes?limit=${limit}&offset=${offset}`);
      const data = await response.json();

      if (data.success) {
        if (append) {
          notificacoes = [...notificacoes, ...data.notificacoes];
        } else {
          notificacoes = data.notificacoes;
        }

        renderizarNotificacoes();
        atualizarBadge(data.total_nao_lidas);
      }
    } catch (error) {
      console.error('Erro ao carregar notificações:', error);
    }
  }

  // Renderizar notificações no DOM
  function renderizarNotificacoes() {
    const container = document.querySelector('.notification-list');
    if (!container) {
      // Criar container se não existir
      const emptyDiv = notificationModal.querySelector('.notification-empty');
      if (emptyDiv) {
        emptyDiv.remove();
      }

      const list = document.createElement('div');
      list.className = 'notification-list';
      notificationModal.appendChild(list);
      renderizarNotificacoes();
      return;
    }

    if (notificacoes.length === 0) {
      container.innerHTML = `
        <div class="notification-empty">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
          </svg>
          <p>Você não tem notificações no momento.<br>Quando algo acontecer, você verá aqui!</p>
        </div>
      `;
      return;
    }

    container.innerHTML = notificacoes.map(notif => `
      <div class="notification-item ${notif.lida ? 'lida' : 'nao-lida'}" data-id="${notif.id}">
        <div class="notification-icon ${notif.cor}">
          ${getIconSvg(notif.icone)}
        </div>
        <div class="notification-content">
          <p class="notification-message">${notif.mensagem}</p>
          <span class="notification-time">${notif.tempo}</span>
        </div>
        <div class="notification-actions">
          ${!notif.lida ? `
            <button class="notification-action-btn marcar-lida" data-id="${notif.id}" title="Marcar como lida">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
            </button>
          ` : ''}
          <button class="notification-action-btn remover" data-id="${notif.id}" title="Remover">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18"/>
              <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
          </button>
        </div>
      </div>
    `).join('');

    // Adicionar event listeners
    adicionarEventListeners();
  }

  // Atualizar badge de contador
  function atualizarBadge(count) {
    if (count > 0) {
      if (!notificationBadge) {
        const badge = document.createElement('span');
        badge.className = 'notification-badge';
        badge.textContent = count > 99 ? '99+' : count;
        notificationBtn.appendChild(badge);
      } else {
        notificationBadge.textContent = count > 99 ? '99+' : count;
      }
    } else {
      if (notificationBadge) {
        notificationBadge.remove();
      }
    }
  }

  // Marcar notificação como lida
  async function marcarComoLida(id) {
    try {
      const response = await fetch(`/api/notificacoes/${id}/marcar-lida`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
      });

      const data = await response.json();

      if (data.success) {
        // Atualizar notificação localmente
        const notif = notificacoes.find(n => n.id === id);
        if (notif) {
          notif.lida = true;
        }
        renderizarNotificacoes();
        atualizarBadge(data.total_nao_lidas);
      }
    } catch (error) {
      console.error('Erro ao marcar como lida:', error);
    }
  }

  // Remover notificação
  async function removerNotificacao(id) {
    try {
      const response = await fetch(`/api/notificacoes/${id}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
      });

      const data = await response.json();

      if (data.success) {
        // Remover notificação localmente
        notificacoes = notificacoes.filter(n => n.id !== id);
        renderizarNotificacoes();
        atualizarBadge(data.total_nao_lidas);
      }
    } catch (error) {
      console.error('Erro ao remover notificação:', error);
    }
  }

  // Adicionar event listeners
  function adicionarEventListeners() {
    // Marcar como lida
    document.querySelectorAll('.marcar-lida').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const id = parseInt(btn.dataset.id);
        marcarComoLida(id);
      });
    });

    // Remover
    document.querySelectorAll('.remover').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const id = parseInt(btn.dataset.id);
        removerNotificacao(id);
      });
    });

    // Clicar na notificação (ir para o link)
    document.querySelectorAll('.notification-item').forEach(item => {
      item.addEventListener('click', () => {
        const notif = notificacoes.find(n => n.id === parseInt(item.dataset.id));
        if (notif && notif.link) {
          if (!notif.lida) {
            marcarComoLida(notif.id);
          }
          window.location.href = notif.link;
        }
      });
    });
  }

  // Obter ícone SVG
  function getIconSvg(icone) {
    const icones = {
      'UserPlus': '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>',
      'Heart': '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
      'MessageCircle': '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>',
      'AtSign': '<circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/>',
      'Mail': '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
      'Bell': '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>'
    };

    return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${icones[icone] || icones['Bell']}</svg>`;
  }

  // Abrir/fechar modal
  notificationBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    notificationModal.classList.toggle('active');
    const userDropdown = document.getElementById('userDropdown');
    if(userDropdown) userDropdown.classList.remove('active');

    if (notificationModal.classList.contains('active')) {
      carregarNotificacoes();
    }
  });

  if(closeNotificationModal) {
    closeNotificationModal.addEventListener('click', () => {
      notificationModal.classList.remove('active');
    });
  }

  document.addEventListener('click', (e) => {
    if(!notificationModal.contains(e.target) && e.target !== notificationBtn) {
      notificationModal.classList.remove('active');
    }
  });

  document.addEventListener('keydown', (e) => {
    if(e.key === 'Escape' && notificationModal.classList.contains('active')) {
      notificationModal.classList.remove('active');
    }
  });

  // Polling para verificar novas notificações a cada 30 segundos
  setInterval(async () => {
    try {
      const response = await fetch('/api/notificacoes/count');
      const data = await response.json();
      
      if (data.success) {
        atualizarBadge(data.count);
      }
    } catch (error) {
      console.error('Erro ao verificar notificações:', error);
    }
  }, 30000);

  // Carregar contador inicial
  (async () => {
    try {
      const response = await fetch('/api/notificacoes/count');
      const data = await response.json();
      
      if (data.success) {
        atualizarBadge(data.count);
      }
    } catch (error) {
      console.error('Erro ao carregar contador inicial:', error);
    }
  })();
})();
</script>

<script>
// ========================================
// 🎨 CORREÇÃO FORÇADA DE CORES - ESTADOS VAZIOS
// ========================================
(function() {
    console.log('🎨 [Color Fix] Iniciando correção de estados vazios...');
    
    // Função para aplicar cores forçadas
    function forceEmptyStateColors() {
        let fixedCount = 0;
        
        // ✅ 1. CHAT - "Nenhuma mensagem ainda"
        const chatEmpty = document.querySelector('#chatMessages .text-center.text-muted');
        if (chatEmpty) {
            // Container
            chatEmpty.style.color = '#9ca3af';
            chatEmpty.style.setProperty('color', '#9ca3af', 'important');
            
            // Ícone
            const icon = chatEmpty.querySelector('i');
            if (icon) {
                icon.style.color = '#6b7280';
                icon.style.setProperty('color', '#6b7280', 'important');
            }
            
            // Texto
            const p = chatEmpty.querySelector('p');
            if (p) {
                p.style.color = '#9ca3af';
                p.style.setProperty('color', '#9ca3af', 'important');
            }
            
            fixedCount++;
            console.log('✅ Chat empty state corrigido');
        }
        
        // ✅ 2. MODAL DE DENÚNCIA - "Nenhuma mensagem selecionada"
        const reportEmpty = document.querySelector('.ambience-empty-state-small');
        if (reportEmpty) {
            // Container
            reportEmpty.style.color = '#9ca3af';
            reportEmpty.style.setProperty('color', '#9ca3af', 'important');
            
            // SVG
            const svg = reportEmpty.querySelector('svg');
            if (svg) {
                svg.style.stroke = '#6b7280';
                svg.style.setProperty('stroke', '#6b7280', 'important');
                svg.style.opacity = '0.7';
            }
            
            // Texto
            const p = reportEmpty.querySelector('p');
            if (p) {
                p.style.color = '#9ca3af';
                p.style.setProperty('color', '#9ca3af', 'important');
                p.style.fontWeight = '500';
            }
            
            fixedCount++;
            console.log('✅ Report modal empty state corrigido');
        }
        
        // ✅ 3. NOTIFICAÇÕES - "Você não tem notificações"
        const notifEmpty = document.querySelector('.notification-empty');
        if (notifEmpty) {
            notifEmpty.style.color = '#9ca3af';
            notifEmpty.style.setProperty('color', '#9ca3af', 'important');
            
            const svg = notifEmpty.querySelector('svg');
            if (svg) {
                svg.style.stroke = '#6b7280';
                svg.style.setProperty('stroke', '#6b7280', 'important');
            }
            
            const p = notifEmpty.querySelector('p');
            if (p) {
                p.style.color = '#9ca3af';
                p.style.setProperty('color', '#9ca3af', 'important');
            }
            
            fixedCount++;
            console.log('✅ Notification empty state corrigido');
        }
        
        // ✅ 4. QUALQUER OUTRO .text-muted QUE SEJA FILHO DE CONTAINERS DE MENSAGENS
        document.querySelectorAll('.text-muted').forEach(el => {
            // Verificar se está em contexto de empty state
            const isInChat = el.closest('#chatMessages');
            const isInModal = el.closest('.modal-body');
            const isInCard = el.closest('.card-modern');
            
            if (isInChat || isInModal || isInCard) {
                el.style.color = '#9ca3af';
                el.style.setProperty('color', '#9ca3af', 'important');
            }
        });
        
        console.log(`📊 Total de elementos corrigidos: ${fixedCount}`);
        
        if (fixedCount === 0) {
            console.log('ℹ️ Nenhum elemento de empty state encontrado no momento');
        } else {
            console.log('✅ Todas as correções aplicadas com sucesso!');
        }
    }
    
    // ✅ Aplicar correções imediatamente
    forceEmptyStateColors();
    
    // ✅ Aplicar novamente após 100ms (garantir que DOM está completo)
    setTimeout(forceEmptyStateColors, 100);
    
    // ✅ Aplicar novamente após 500ms (para elementos carregados dinamicamente)
    setTimeout(forceEmptyStateColors, 500);
    
    // ✅ Observar mudanças no DOM para corrigir elementos dinâmicos
    const observer = new MutationObserver((mutations) => {
        let needsFix = false;
        
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === 1) { // Element node
                    // Verificar se é um empty state ou contém um
                    if (
                        node.classList?.contains('text-muted') ||
                        node.classList?.contains('ambience-empty-state-small') ||
                        node.classList?.contains('notification-empty') ||
                        node.querySelector?.('.text-muted') ||
                        node.querySelector?.('.ambience-empty-state-small') ||
                        node.querySelector?.('.notification-empty')
                    ) {
                        needsFix = true;
                    }
                }
            });
        });
        
        if (needsFix) {
            console.log('🔄 Elemento dinâmico detectado, reaplicando correções...');
            forceEmptyStateColors();
        }
    });
    
    // Observar mudanças no body
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // ✅ Expor função global para correção manual
    window.fixEmptyStateColors = forceEmptyStateColors;
    
    console.log('💡 Use window.fixEmptyStateColors() para reaplicar correções manualmente');
    console.log('✅ Sistema de correção de cores ativado');
})();
</script>

<script>
// ========================================
// 🎨 SISTEMA COMPLETO DE EDIÇÃO E EXCLUSÃO DE SALA
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎨 Iniciando sistema de gerenciamento de sala...');
    
    const btnEditarSala = document.getElementById('btnEditarSala');
    const modalEditarSala = document.getElementById('modalEditarSala');
    const formEditarSala = document.getElementById('formEditarSala');
    const tipoSelect = document.getElementById('edit_tipo');
    const senhaContainer = document.getElementById('edit_senha_container');
    
    // Elementos de exclusão
    const btnMostrarExclusao = document.getElementById('btnMostrarExclusao');
    const deleteConfirmSection = document.getElementById('deleteConfirmSection');
    const deleteConfirmNome = document.getElementById('delete_confirm_nome');
    const btnCancelarExclusao = document.getElementById('btnCancelarExclusao');
    const btnConfirmarExclusao = document.getElementById('btnConfirmarExclusao');
    
    const nomeSalaOriginal = "{{ $sala->nome }}";
    
    // ========================================
    // 📝 ABERTURA DO MODAL E CARREGAMENTO
    // ========================================
    if (btnEditarSala) {
        btnEditarSala.addEventListener('click', async function() {
            const salaId = this.dataset.salaId;
            
            console.log('📝 Abrindo modal de edição para sala:', salaId);
            
            // Resetar estado do modal
            resetModal();
            
            // Abrir modal imediatamente
            const modal = new bootstrap.Modal(modalEditarSala);
            modal.show();
            
            // Carregar dados
            try {
                const response = await fetch(`/salas/${salaId}/editar`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Preencher formulário
                    document.getElementById('edit_sala_id').value = data.sala.id;
                    document.getElementById('edit_nome').value = data.sala.nome;
                    document.getElementById('edit_descricao').value = data.sala.descricao || '';
                    document.getElementById('edit_tipo').value = data.sala.tipo;
                    document.getElementById('edit_max_participantes').value = data.sala.max_participantes;
                    
                    // Mostrar/ocultar campo de senha
                    if (data.sala.tipo === 'privada') {
                        senhaContainer.style.display = 'block';
                    } else {
                        senhaContainer.style.display = 'none';
                    }
                    
                    console.log('✅ Dados carregados com sucesso');
                } else {
                    throw new Error(data.message || 'Erro ao carregar dados');
                }
            } catch (error) {
                console.error('❌ Erro:', error);
                showEditAlert('danger', error.message || 'Erro ao carregar dados da sala');
            }
        });
    }
    
    // ========================================
    // 🔄 ALTERNAR CAMPO DE SENHA
    // ========================================
    if (tipoSelect) {
        tipoSelect.addEventListener('change', function() {
            if (this.value === 'privada') {
                senhaContainer.style.display = 'block';
            } else {
                senhaContainer.style.display = 'none';
                document.getElementById('edit_senha').value = '';
            }
        });
    }
    
    // ========================================
    // 💾 SALVAR EDIÇÕES
    // ========================================
    if (formEditarSala) {
        formEditarSala.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const salaId = document.getElementById('edit_sala_id').value;
            const btnSalvar = document.getElementById('btnSalvarEdicao');
            const originalText = btnSalvar.innerHTML;
            
            // Desabilitar botão
            btnSalvar.disabled = true;
            btnSalvar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Salvando...';
            
            // Coletar dados
            const formData = {
                nome: document.getElementById('edit_nome').value,
                descricao: document.getElementById('edit_descricao').value,
                tipo: document.getElementById('edit_tipo').value,
                max_participantes: parseInt(document.getElementById('edit_max_participantes').value),
                senha: document.getElementById('edit_senha').value
            };
            
            try {
                const response = await fetch(`/salas/${salaId}/atualizar`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showEditAlert('success', data.message);
                    
                    btnSalvar.innerHTML = `
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Salvo!
                    `;
                    
                    // Aguardar 1.5s e recarregar página
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Erro ao salvar');
                }
            } catch (error) {
                console.error('❌ Erro:', error);
                showEditAlert('danger', error.message || 'Erro ao salvar alterações');
                
                // Reabilitar botão
                btnSalvar.disabled = false;
                btnSalvar.innerHTML = originalText;
            }
        });
    }
    
    // ========================================
    // 🗑️ SISTEMA DE EXCLUSÃO SEGURA
    // ========================================
    
    // Mostrar seção de confirmação
    if (btnMostrarExclusao) {
        btnMostrarExclusao.addEventListener('click', function() {
            deleteConfirmSection.style.display = 'block';
            btnMostrarExclusao.style.display = 'none';
            deleteConfirmNome.focus();
        });
    }
    
    // Cancelar exclusão
    if (btnCancelarExclusao) {
        btnCancelarExclusao.addEventListener('click', function() {
            deleteConfirmSection.style.display = 'none';
            btnMostrarExclusao.style.display = 'block';
            deleteConfirmNome.value = '';
            btnConfirmarExclusao.disabled = true;
            hideDeleteAlert();
        });
    }
    
    // Validar nome digitado
    if (deleteConfirmNome) {
        deleteConfirmNome.addEventListener('input', function() {
            const nomeDigitado = this.value.trim();
            const nomeCorreto = nomeSalaOriginal.trim();
            
            if (nomeDigitado === nomeCorreto) {
                btnConfirmarExclusao.disabled = false;
                btnConfirmarExclusao.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
                btnConfirmarExclusao.style.color = '#fff';
                btnConfirmarExclusao.style.borderColor = '#ef4444';
            } else {
                btnConfirmarExclusao.disabled = true;
                btnConfirmarExclusao.style.background = 'rgba(239, 68, 68, 0.2)';
                btnConfirmarExclusao.style.color = '#ef4444';
                btnConfirmarExclusao.style.borderColor = 'rgba(239, 68, 68, 0.4)';
            }
        });
    }
    
    // Confirmar exclusão
    if (btnConfirmarExclusao) {
        btnConfirmarExclusao.addEventListener('click', async function() {
            const salaId = document.getElementById('edit_sala_id').value;
            const originalText = this.innerHTML;
            
            // Desabilitar botão
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Excluindo...';
            
            try {
                const response = await fetch(`/salas/${salaId}/excluir`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showDeleteAlert('success', data.message);
                    
                    this.innerHTML = `
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Excluído!
                    `;
                    
                    // Aguardar 1.5s e redirecionar
                    setTimeout(() => {
                        window.location.href = data.redirect_to || '/salas';
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Erro ao excluir');
                }
            } catch (error) {
                console.error('❌ Erro:', error);
                showDeleteAlert('danger', error.message || 'Erro ao excluir sala');
                
                // Reabilitar botão
                this.disabled = false;
                this.innerHTML = originalText;
            }
        });
    }
    
    // ========================================
    // 🛠️ FUNÇÕES AUXILIARES
    // ========================================
    
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }
    
    function resetModal() {
        // Resetar formulário
        if (formEditarSala) formEditarSala.reset();
        
        // Ocultar alertas
        hideEditAlert();
        hideDeleteAlert();
        
        // Ocultar seção de exclusão
        if (deleteConfirmSection) deleteConfirmSection.style.display = 'none';
        if (btnMostrarExclusao) btnMostrarExclusao.style.display = 'block';
        
        // Resetar campo de confirmação
        if (deleteConfirmNome) deleteConfirmNome.value = '';
        if (btnConfirmarExclusao) btnConfirmarExclusao.disabled = true;
    }
    
    function showEditAlert(type, message) {
        const alert = document.getElementById('edit_alert');
        if (!alert) return;
        
        alert.className = `alert alert-${type}`;
        alert.textContent = message;
        alert.classList.remove('d-none');
        
        if (type === 'success') {
            setTimeout(() => {
                alert.classList.add('d-none');
            }, 3000);
        }
    }
    
    function hideEditAlert() {
        const alert = document.getElementById('edit_alert');
        if (alert) alert.classList.add('d-none');
    }
    
    function showDeleteAlert(type, message) {
        const alert = document.getElementById('delete_alert');
        if (!alert) return;
        
        alert.className = `alert alert-${type}`;
        alert.textContent = message;
        alert.classList.remove('d-none');
        
        if (type === 'success') {
            setTimeout(() => {
                alert.classList.add('d-none');
            }, 3000);
        }
    }
    
    function hideDeleteAlert() {
        const alert = document.getElementById('delete_alert');
        if (alert) alert.classList.add('d-none');
    }
    
    // Resetar modal ao fechar
    if (modalEditarSala) {
        modalEditarSala.addEventListener('hidden.bs.modal', resetModal);
    }
    
    console.log('✅ Sistema de gerenciamento de sala inicializado');
});
</script>

<script>
// ========================================
// 🎨 ALTERNAR ÍCONE DO SELECT TIPO SALA
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('edit_tipo');
    const tipoIcon = document.getElementById('tipo-icon');
    
    if (!tipoSelect || !tipoIcon) return;
    
    // Ícones SVG
    const icons = {
        publica: `
            <circle cx="12" cy="12" r="10"/>
            <line x1="2" y1="12" x2="22" y2="12"/>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
        `,
        privada: `
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        `
    };
    
    // Função para atualizar ícone
    function updateIcon() {
        const value = tipoSelect.value;
        tipoIcon.innerHTML = icons[value] || icons.publica;
        
        // Mudar cor do ícone
        if (value === 'publica') {
            tipoIcon.style.color = '#22c55e';
        } else {
            tipoIcon.style.color = '#22c55e';
        }
    }
    
    // Atualizar ao carregar
    updateIcon();
    
    // Atualizar ao mudar
    tipoSelect.addEventListener('change', updateIcon);
});
</script>
</body>
</html>