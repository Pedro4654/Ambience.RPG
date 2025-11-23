<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Salas - Ambience RPG</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-title h1 {
            font-family: Montserrat, sans-serif;
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: 1px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .staff-badge {
            background: linear-gradient(45deg, #ff6b6b, #ee5a6f);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            animation: badgePulse 2s infinite;
        }

        @keyframes badgePulse {
            0%, 100% { box-shadow: 0 0 10px rgba(255, 107, 107, 0.4); }
            50% { box-shadow: 0 0 20px rgba(255, 107, 107, 0.6); }
        }

        .header-subtitle {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .header-actions {
    display: flex;
    gap: 0.75rem;
    margin: 0 1rem;
}

@media (max-width: 1200px) {
    .header-actions .btn-primary,
    .header-actions .btn-success {
        padding: 0.75rem 1rem;
        font-size: 0.8rem;
    }
}

@media (max-width: 768px) {
    .header-actions {
        display: none; /* Esconder em mobile */
    }
}

/* ========== SKELETON LOADING SYSTEM ========== */
.skeleton-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 1.5rem;
    opacity: 1;
    animation: fadeIn 0.4s ease;
}

.skeleton-card {
    background: linear-gradient(145deg, rgba(31, 42, 51, 0.4), rgba(20, 28, 35, 0.3));
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    overflow: hidden;
    animation: cardAppear 0.4s ease backwards;
}

.skeleton-banner {
    width: 100%;
    height: 140px;
    background: linear-gradient(
        90deg,
        rgba(31, 42, 51, 0.4) 0%,
        rgba(52, 73, 94, 0.6) 50%,
        rgba(31, 42, 51, 0.4) 100%
    );
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite linear;
}

.skeleton-profile {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    background: linear-gradient(
        90deg,
        rgba(31, 42, 51, 0.6) 0%,
        rgba(52, 73, 94, 0.8) 50%,
        rgba(31, 42, 51, 0.6) 100%
    );
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite linear;
    margin: -2.5rem 1.5rem 1rem;
    border: 4px solid rgba(20, 28, 35, 0.9);
}

.skeleton-content {
    padding: 1.5rem;
}

.skeleton-line {
    height: 16px;
    background: linear-gradient(
        90deg,
        rgba(31, 42, 51, 0.4) 0%,
        rgba(52, 73, 94, 0.6) 50%,
        rgba(31, 42, 51, 0.4) 100%
    );
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite linear;
    border-radius: 8px;
    margin-bottom: 12px;
}

.skeleton-line.title {
    width: 70%;
    height: 24px;
}

.skeleton-line.subtitle {
    width: 90%;
    height: 14px;
}

.skeleton-line.short {
    width: 50%;
}

.skeleton-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin: 1rem 0;
    padding: 1rem;
    background: rgba(17, 24, 39, 0.5);
    border-radius: 12px;
}

.skeleton-stat {
    height: 40px;
    background: linear-gradient(
        90deg,
        rgba(31, 42, 51, 0.4) 0%,
        rgba(52, 73, 94, 0.6) 50%,
        rgba(31, 42, 51, 0.4) 100%
    );
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite linear;
    border-radius: 8px;
}

@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

/* ========== LOADING OVERLAY (Tela Cheia) ========== */
.loading-overlay {
    position: fixed;
    inset: 0;
    background: linear-gradient(135deg, #0a0f14 0%, #0d1419 100%);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 1; /* Alterado de 0 para 1 */
    pointer-events: all; /* Alterado de none para all */
    transition: opacity 0.4s ease;
}

.loading-overlay.active {
    opacity: 1;
    pointer-events: all;
}

.loading-overlay.hidden {
    opacity: 0;
    pointer-events: none;
}

.loading-logo {
    width: 140px;
    height: 120px;
    margin-bottom: 2rem;
    animation: logoFloat 2s ease-in-out infinite;
}

@keyframes logoFloat {
    0%, 100% {
        transform: translateY(0px) scale(1);
    }
    50% {
        transform: translateY(-20px) scale(1.05);
    }
}

.loading-spinner-modern {
    position: relative;
    width: 80px;
    height: 80px;
    margin-bottom: 2rem;
}

.loading-spinner-modern::before,
.loading-spinner-modern::after {
    content: '';
    position: absolute;
    border-radius: 50%;
}

.loading-spinner-modern::before {
    width: 80px;
    height: 80px;
    border: 4px solid rgba(34, 197, 94, 0.1);
}

.loading-spinner-modern::after {
    width: 80px;
    height: 80px;
    border: 4px solid transparent;
    border-top-color: var(--accent);
    border-right-color: var(--accent);
    animation: spinModern 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
}

@keyframes spinModern {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.loading-text {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    animation: pulse 2s ease-in-out infinite;
}

.loading-subtext {
    font-size: 0.875rem;
    color: var(--text-muted);
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.6;
    }
}

/* ========== LOADING INLINE (Ver Mais) ========== */
.loading-inline {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    padding: 2rem;
    opacity: 0;
    height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
}

.loading-inline.active {
    opacity: 1;
    height: auto;
}

.loading-dots {
    display: flex;
    gap: 8px;
}

.loading-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--accent);
    animation: dotBounce 1.4s infinite ease-in-out;
}

.loading-dot:nth-child(1) {
    animation-delay: 0s;
}

.loading-dot:nth-child(2) {
    animation-delay: 0.2s;
}

.loading-dot:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes dotBounce {
    0%, 80%, 100% {
        transform: scale(0);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}

.loading-inline-text {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--accent);
}

/* ========== FADE TRANSITION ========== */
.section-content {
    opacity: 1;
    transition: opacity 0.3s ease;
}

.section-content.loading {
    opacity: 0.5;
    pointer-events: none;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
    .skeleton-container {
        grid-template-columns: 1fr;
    }
    
    .loading-logo {
        width: 80px;
        height: 80px;
    }
    
    .loading-spinner-modern {
        width: 60px;
        height: 60px;
    }
    
    .loading-spinner-modern::before,
    .loading-spinner-modern::after {
        width: 60px;
        height: 60px;
    }
}

        .btn-primary, .btn-success {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: #052e16;
            box-shadow: 0 4px 14px var(--accent-glow);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }

        .btn-success {
            background: rgba(34, 197, 94, 0.1);
            color: var(--accent);
            border: 2px solid var(--accent);
        }

        .btn-success:hover {
            background: rgba(34, 197, 94, 0.2);
            transform: translateY(-2px);
        }

        /* ========== MAIN CONTAINER ========== */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* ========== ALERTAS ========== */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideDown 0.4s ease;
            border: 1px solid;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 193, 7, 0.05));
            border-color: rgba(255, 193, 7, 0.3);
            color: #fbbf24;
        }

        .alert-dismissible {
            position: relative;
            padding-right: 3rem;
        }

        .btn-close {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: inherit;
            opacity: 0.6;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-close:hover {
            opacity: 1;
        }

        /* ========== TABS INTERATIVAS ========== */
        .section-tabs {
            display: flex;
            gap: 0.5rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 2rem;
            position: relative;
        }

        .tab-button {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.95rem;
            padding: 1rem 1.5rem;
            cursor: pointer;
            transition: color 0.3s;
            position: relative;
        }

        .tab-button:hover {
            color: var(--text-primary);
        }

        .tab-button.active {
            color: var(--accent);
        }

        .tab-underline {
            position: absolute;
            bottom: -2px;
            height: 2px;
            background: linear-gradient(90deg, var(--accent), var(--accent-light));
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 10px var(--accent-glow);
        }

        /* ========== SECTION HEADER ========== */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-title h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
        }

        .section-count {
            background: var(--bg-elevated);
            color: var(--accent);
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 700;
            border: 1px solid var(--border-subtle);
        }

        /* ========== SEARCH BOX MODERNA ========== */
        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            background: var(--bg-elevated);
            border: 2px solid var(--border-subtle);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-glow);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
        }

        /* ========== CARDS GRID ========== */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 1.5rem;
            opacity: 1;
            transition: opacity 0.15s ease;
        }

        .cards-grid.transitioning {
            opacity: 0;
        }

        /* ========== SALA CARD REDESENHADO ========== */
        .sala-card {
            background: linear-gradient(145deg, rgba(31, 42, 51, 0.6), rgba(20, 28, 35, 0.4));
            border: 1px solid var(--border-subtle);
            border-radius: 20px;
            overflow: hidden;
            transition: var(--transition);
            position: relative;
            animation: cardAppear 0.4s ease backwards;
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .sala-card:hover {
            transform: translateY(-8px);
            border-color: var(--border-hover);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 20px var(--accent-glow);
        }

        /* Banner da Sala */
        .sala-banner {
            width: 100%;
            height: 140px;
            background-position: center;
            background-size: cover;
            position: relative;
            overflow: hidden;
        }

        .sala-banner::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(to top, rgba(20, 28, 35, 0.9), transparent);
        }

        .banner-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 700;
            font-size: 1.125rem;
            text-align: center;
            padding: 0 1rem;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        }

        .banner-edit-btn {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            z-index: 10;
        }

        .banner-edit-btn button {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .banner-edit-btn button:hover {
            background: rgba(0, 0, 0, 0.8);
            border-color: var(--accent);
        }

        /* Foto de Perfil da Sala */
        .sala-profile-section {
            padding: 0 1.5rem;
            margin-top: -2.5rem;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .sala-profile-photo {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            background-position: center;
            background-size: cover;
            border: 4px solid rgba(20, 28, 35, 0.9);
            position: relative;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
        }

        .photo-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 700;
            font-size: 2rem;
            text-transform: uppercase;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        }

        .photo-edit-btn {
            position: absolute;
            bottom: -4px;
            right: -4px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--accent);
            border: 3px solid rgba(20, 28, 35, 0.9);
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

        .tipo-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        .tipo-apenas_convite {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(251, 191, 36, 0.1));
            color: #fbbf24;
            border: 1px solid #fbbf24;
        }

        /* Card Body */
        .sala-card-body {
            padding: 1.5rem;
        }

        .sala-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sala-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.6;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Stats da Sala */
        .sala-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin: 1rem 0;
            padding: 1rem;
            background: var(--bg-elevated);
            border-radius: 12px;
            border: 1px solid var(--border-subtle);
        }

        .stat-item {
            text-align: center;
        }

        .stat-label {
            display: block;
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
        }

        .stat-value {
            display: block;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Participantes com Conexões */
        .sala-participants {
            margin: 1rem 0;
        }

        .participants-header {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .participants-grid {
            display: flex;
            gap: 0.5rem;
            position: relative;
            padding: 1rem;
            background: var(--bg-elevated);
            border-radius: 12px;
            border: 1px solid var(--border-subtle);
        }

        .participant-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--border-subtle);
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .participant-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .participant-avatar:hover {
            transform: scale(1.15);
            border-color: var(--accent);
            z-index: 10;
        }

        /* Efeito de Conexão entre Participantes */
        .participants-grid:hover .connection-line {
            opacity: 1;
        }

        .connection-line {
            position: absolute;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            opacity: 0;
            transition: opacity 0.4s;
            pointer-events: none;
            box-shadow: 0 0 4px var(--accent);
        }

        /* Card Footer */
        .sala-card-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(0, 0, 0, 0.2);
        }

        .sala-meta {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-enter {
            padding: 0.625rem 1.25rem;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: #052e16;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.875rem;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-enter:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px var(--accent-glow);
        }

        .badge-staff-access {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: linear-gradient(45deg, #ff6b6b, #ee5a6f);
            color: white;
            padding: 0.375rem 0.875rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 700;
            z-index: 5;
        }

        /* Staff Actions Dropdown */
        .staff-actions {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem; /* MUDOU DE right PARA left */
    z-index: 10;
}

        .dropdown {
            position: relative;
        }

        .dropdown-toggle {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(220, 38, 38, 0.1));
    backdrop-filter: blur(10px);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.dropdown-toggle::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at center, rgba(239, 68, 68, 0.2), transparent);
    opacity: 0;
    transition: opacity 0.3s;
}

.dropdown-toggle:hover {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.25), rgba(220, 38, 38, 0.15));
    border-color: rgba(239, 68, 68, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.dropdown-toggle:hover::before {
    opacity: 1;
}

.dropdown-toggle i {
    font-size: 16px;
    transition: transform 0.3s;
}

.dropdown-toggle:hover i {
    transform: scale(1.1);
}

.dropdown-menu {
    position: absolute;
    top: calc(100% + 0.75rem);
    left: 0;
    background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.95));
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 16px;
    padding: 0.5rem;
    min-width: 220px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6), 0 0 1px rgba(239, 68, 68, 0.3);
    backdrop-filter: blur(12px);
    display: none;
    opacity: 0;
    transform: translateY(0) scale(0.95); /* FIXADO: era -10px */
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
    z-index: 100;
    list-style: none; /* REMOVE BOLINHAS */
}

.dropdown-menu::before {
    content: '';
    position: absolute;
    top: -6px;
    left: 12px;
    width: 12px;
    height: 12px;
    background: linear-gradient(135deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.95));
    border-left: 1px solid rgba(239, 68, 68, 0.2);
    border-top: 1px solid rgba(239, 68, 68, 0.2);
    transform: rotate(45deg);
}

        .dropdown-menu.show {
    display: block;
    opacity: 1;
    transform: scale(1);
    pointer-events: all;
}

        .dropdown-item {
    padding: 0.75rem 1rem;
    color: var(--text-primary);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    border-radius: 10px;
    margin: 0.25rem 0;
    position: relative;
    overflow: hidden;
}

.dropdown-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: var(--accent);
    transform: scaleY(0);
    transition: transform 0.2s;
}

.dropdown-item:hover {
    background: rgba(34, 197, 94, 0.1);
    color: var(--accent);
    transform: translateX(4px);
}

.dropdown-item:hover::before {
    transform: scaleY(1);
}

.dropdown-item i {
    width: 18px;
    font-size: 14px;
    transition: transform 0.2s;
}

.dropdown-item:hover i {
    transform: scale(1.15);
}

.dropdown-item.text-danger {
    color: #ef4444;
}

.dropdown-item.text-danger::before {
    background: #ef4444;
}

.dropdown-item.text-danger:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.dropdown-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(239, 68, 68, 0.2), transparent);
    margin: 0.5rem 0; /* REDUZIDO: era 0.5rem 1rem */
    position: relative;
    list-style: none; /* GARANTE QUE NÃO APAREÇA BOLINHA */
}

.dropdown-divider::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 4px;
    height: 4px;
    background: rgba(239, 68, 68, 0.4);
    border-radius: 50%;
}

.dropdown.show .dropdown-toggle {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.3), rgba(220, 38, 38, 0.2));
    border-color: rgba(239, 68, 68, 0.6);
    box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
}

.dropdown-toggle::after {
    content: 'STAFF';
    position: absolute;
    bottom: -20px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 0.65rem;
    font-weight: 700;
    color: #ef4444;
    background: rgba(239, 68, 68, 0.15);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    border: 1px solid rgba(239, 68, 68, 0.3);
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s;
    white-space: nowrap;
}

.dropdown-toggle:hover::after {
    opacity: 1;
    bottom: -24px;
}

        /* Informação de Desativação */
        .desativacao-info {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem 0;
            font-size: 0.875rem;
        }

        .desativacao-info strong {
            color: #fbbf24;
            display: block;
            margin-bottom: 0.5rem;
        }

        .desativacao-info p {
            color: var(--text-secondary);
            margin: 0.5rem 0;
        }

        .desativacao-info small {
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        /* Empty State */
        .empty-state {
    text-align: center;
    padding: 5rem 2rem;
    color: var(--text-muted);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.02), transparent);
    border-radius: 20px;
    border: 1px dashed rgba(34, 197, 94, 0.1);
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(34, 197, 94, 0.05));
    border-radius: 50%;
    border: 2px solid rgba(34, 197, 94, 0.2);
    position: relative;
}

.empty-state-icon::before {
    content: '';
    position: absolute;
    inset: -10px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(34, 197, 94, 0.1), transparent 70%);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 0.5;
        transform: scale(1);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
}

.empty-state-icon svg {
    width: 40px;
    height: 40px;
    stroke: var(--accent);
    stroke-width: 2.5;
    position: relative;
    z-index: 1;
    animation: checkBounce 2s ease-in-out infinite;
}

@keyframes checkBounce {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.empty-state h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
    letter-spacing: 0.5px;
}

.empty-state p {
    font-size: 1rem;
    color: var(--text-secondary);
    max-width: 400px;
    line-height: 1.6;
}

/* Variações para diferentes tipos de empty state */
.empty-state i {
    font-size: 4rem;
    opacity: 0.2;
    margin-bottom: 1.5rem;
    display: block;
    color: var(--accent);
}

        /* Ver Mais Button */
        .btn-ver-mais {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
            border: 2px solid var(--accent);
            color: var(--accent);
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.875rem;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-ver-mais:hover {
            background: rgba(34, 197, 94, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px var(--accent-glow);
        }

        /* ========== MODAIS ========== */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(4px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-dialog {
            width: 90%;
            max-width: 600px;
            animation: modalAppear 0.3s ease;
        }

        @keyframes modalAppear {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-content {
            background: var(--bg-card);
            border-radius: 20px;
            border: 1px solid var(--border-subtle);
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-close-white {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .btn-close-white:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        /* Form Styles */
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.875rem 1rem;
            background: var(--bg-elevated);
            border: 2px solid var(--border-subtle);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-glow);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        /* Highlight de Busca */
        .search-highlight {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.3), rgba(34, 197, 94, 0.2));
            color: var(--accent);
            padding: 0 0.25rem;
            border-radius: 4px;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                width: 100%;
            }

            .btn-primary, .btn-success {
                flex: 1;
                justify-content: center;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-box {
                max-width: 100%;
            }

            .cards-grid {
                grid-template-columns: 1fr;
            }

            .section-tabs {
                overflow-x: auto;
            }

            .tab-button {
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>

<!-- Loading Overlay (Tela Cheia) -->
    <div class="loading-overlay active" id="loadingOverlay">
    <img src="{{ asset('images/logo.png') }}" alt="Ambience RPG" class="loading-logo">
    <div class="loading-spinner-modern"></div>
    <div class="loading-text">Carregando suas aventuras...</div>
    <div class="loading-subtext">Preparando o mundo RPG para você</div>
</div>

    <!-- Header -->
    <header class="header">
  <div class="container">
    <nav class="nav">
      <a href="{{ route('home') }}" class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Ambience RPG" class="logo-img">
      </a>
      <div class="nav-links">
        <a href="{{ route('salas.index') }}" class="active">Salas</a>
        <a href="{{ route('comunidade.feed') }}">Comunidade</a>
        <a href="{{ route('suporte.index') }}">Suporte</a>
      </div>

      <div class="header-actions">
    <button class="btn-success" onclick="openModal('modalEntrarSala')">
      <i class="fas fa-sign-in-alt"></i>Entrar em Sala
    </button>
    <button class="btn-primary" onclick="openModal('modalCriarSala')">
      <i class="fas fa-plus"></i>Criar Sala
    </button>
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
            <div class="user-avatar-default">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</div>
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

    <div class="main-container">
        <!-- Alerta de Salas Desativadas -->
        @if(isset($minhasSalasDesativadasCount) && $minhasSalasDesativadasCount > 0)
        <div class="alert alert-warning alert-dismissible fade show" id="alertSalasDesativadas">
            <div style="flex:1">
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.5rem">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                    <div>
                        <h5 style="margin:0;font-size:1rem;font-weight:700">Atenção: Salas Desativadas</h5>
                        <p style="margin:0.25rem 0 0;font-size:0.875rem">
                            Você possui <strong>{{ $minhasSalasDesativadasCount }}</strong> 
                            {{ $minhasSalasDesativadasCount == 1 ? 'sala desativada' : 'salas desativadas' }} pela Moderação.
                        </p>
                    </div>
                </div>
                <button class="btn-success" style="margin-top:0.5rem" onclick="sistema.mostrarMinhasSalasDesativadas()">
                    <i class="fas fa-eye"></i>Ver Salas Desativadas
                </button>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Container de Alertas -->
        <div id="alertContainer"></div>

        <!-- Tabs Interativas -->
        <div class="section-tabs" id="sectionTabs">
            <button class="tab-button active" data-section="minhas">
                <i class="fas fa-home"></i> Minhas Salas
            </button>
            <button class="tab-button" data-section="publicas">
                <i class="fas fa-globe"></i> Salas Públicas
            </button>
            @if($isStaff)
            <button class="tab-button" data-section="desativadas">
                <i class="fas fa-power-off"></i> Salas Desativadas
            </button>
            @endif
            <div class="tab-underline" id="tabUnderline"></div>
        </div>

        <!-- Seção: Minhas Salas -->
        <div class="section-content active" id="section-minhas">
            <div class="section-header">
                <div class="section-title">
                    <h2>Minhas Salas</h2>
                    <span class="section-count" id="minhasSalasCount">0</span>
                </div>
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="searchMinhasSalas" placeholder="Buscar minhas salas...">
                </div>
            </div>
            <div id="minhasSalasContainer" class="cards-grid"></div>
            <div id="minhasSalasVerMais" style="display:none">
                <button class="btn-ver-mais" onclick="sistema.carregarMaisMinhasSalas()">
                    <i class="fas fa-chevron-down"></i>Ver Mais
                </button>
            </div>
        </div>

        <!-- Seção: Salas Públicas -->
        <div class="section-content" id="section-publicas" style="display:none">
            <div class="section-header">
                <div class="section-title">
                    <h2>Salas Públicas</h2>
                    <span class="section-count" id="salasPublicasCount">0</span>
                </div>
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="searchSalasPublicas" placeholder="Buscar salas públicas...">
                </div>
            </div>
            <div id="salasPublicasContainer" class="cards-grid"></div>
            <div id="salasPublicasVerMais" style="display:none">
                <button class="btn-ver-mais" onclick="sistema.carregarMaisSalasPublicas()">
                    <i class="fas fa-chevron-down"></i>Ver Mais
                </button>
            </div>
        </div>

        <!-- Seção: Minhas Salas Desativadas -->
        <div class="section-content" id="secaoMinhasSalasDesativadas" style="display:none">
            <div class="alert alert-warning">
                <i class="fas fa-power-off fa-2x"></i>
                <div>
                    <h4 style="margin:0 0 0.5rem;font-size:1rem;font-weight:700">Suas Salas Desativadas</h4>
                    <p style="margin:0;font-size:0.875rem">
                        Estas salas foram desativadas pela Moderação e não podem mais receber novos participantes.
                    </p>
                </div>
            </div>
            <div id="minhasSalasDesativadasContainer" class="cards-grid"></div>
            <button class="btn-success" style="margin-top:1.5rem" onclick="sistema.ocultarMinhasSalasDesativadas()">
                <i class="fas fa-times"></i>Ocultar
            </button>
        </div>

        <!-- Seção: Salas Desativadas (Staff) -->
        @if($isStaff)
        <div class="section-content" id="section-desativadas" style="display:none">
            <div class="section-header">
                <div class="section-title">
                    <h2 style="color:#ef4444">Salas Desativadas</h2>
                    <span class="section-count" style="color:#ef4444;border-color:rgba(239,68,68,0.3)" id="salasDesativadasCount">0</span>
                </div>
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="searchSalasDesativadas" placeholder="Buscar salas desativadas...">
                </div>
            </div>
            <div id="salasDesativadasContainer" class="cards-grid"></div>
            <div id="salasDesativadasVerMais" style="display:none">
                <button class="btn-ver-mais" style="border-color:#ef4444;color:#ef4444" onclick="sistema.carregarMaisSalasDesativadas()">
                    <i class="fas fa-chevron-down"></i>Ver Mais
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- ========== MODAL CRIAR SALA (NOVO DESIGN) ========== -->
<div class="rpg-modal" id="modalCriarSala">
  <div class="rpg-modal-overlay" data-close-modal="modalCriarSala"></div>
  <div class="rpg-modal-container">
    <div class="rpg-modal-header">
      <div class="rpg-modal-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 4v16m8-8H4"/>
        </svg>
      </div>
      <div class="rpg-modal-title-wrapper">
        <h3 class="rpg-modal-title">Criar Nova Sala</h3>
        <p class="rpg-modal-subtitle">Configure sua aventura épica</p>
      </div>
      <button class="rpg-modal-close" data-close-modal="modalCriarSala" type="button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"/>
          <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>

    <form id="formCriarSala" class="rpg-modal-form">
      <div class="rpg-modal-body">
        <!-- Nome da Sala -->
        <div class="rpg-form-group">
          <label class="rpg-label">
            <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 6l6-3 6 3 6-3v15l-6 3-6-3-6 3V6z"/>
            </svg>
            Nome da Sala
            <span class="rpg-required">*</span>
          </label>
          <div class="rpg-input-wrapper">
            <input 
              type="text" 
              class="rpg-input" 
              id="nomeSala" 
              name="nome" 
              required 
              placeholder="Ex: Aventura em Pedra Branca"
              maxlength="100"
            >
            <div class="rpg-input-focus-border"></div>
          </div>
          <small id="nomeSala-warning" class="rpg-warning">
  <svg class="warning-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
    <line x1="12" y1="9" x2="12" y2="13"/>
    <line x1="12" y1="17" x2="12.01" y2="17"/>
  </svg>
  Conteúdo inapropriado detectado
</small>
        </div>

        <!-- Tipo e Participantes -->
        <div class="rpg-form-row">
          <div class="rpg-form-group">
            <label class="rpg-label">
              <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
              </svg>
              Tipo da Sala
              <span class="rpg-required">*</span>
            </label>
            <div class="rpg-select-wrapper">
              <select class="rpg-select" id="tipoSala" name="tipo" required>
                <option value="publica">Pública</option>
                <option value="privada">Privada</option>
              </select>
              <div class="rpg-select-arrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M6 9l6 6 6-6"/>
                </svg>
              </div>
            </div>
          </div>

          <div class="rpg-form-group">
            <label class="rpg-label">
              <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
              Máx. Participantes
            </label>
            <div class="rpg-input-wrapper">
              <input 
                type="number" 
                class="rpg-input" 
                id="maxParticipantes" 
                name="max_participantes" 
                value="10" 
                min="2" 
                max="20"
              >
              <div class="rpg-input-focus-border"></div>
            </div>
          </div>
        </div>

        <!-- Senha (condicional) -->
        <div class="rpg-form-group" id="senhaContainer" style="display:none;">
  <label class="rpg-label">
    <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
      <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
    </svg>
    Senha da Sala
  </label>
  <div class="rpg-input-wrapper">
    <input 
      type="password" 
      class="rpg-input" 
      id="senhaSala" 
      name="senha" 
      placeholder="Mínimo 4 caracteres"
      minlength="4"
    >
    <div class="rpg-input-focus-border"></div>
  </div>
  <small class="rpg-hint">
    <svg class="hint-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
      <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
    </svg>
    Apenas usuários com senha poderão entrar
  </small>
</div>

        <!-- Descrição -->
        <div class="rpg-form-group">
          <label class="rpg-label">
            <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
              <polyline points="10 9 9 9 8 9"/>
            </svg>
            Descrição
            <span class="rpg-optional">(opcional)</span>
          </label>
          <div class="rpg-textarea-wrapper">
            <textarea 
              class="rpg-textarea" 
              id="descricaoSala" 
              name="descricao" 
              rows="4" 
              placeholder="Descreva sua aventura... O que os jogadores podem esperar?"
              maxlength="1000"
            ></textarea>
            <div class="rpg-textarea-focus-border"></div>
          </div>
          <small id="descricaoSala-warning" class="rpg-warning">
  <svg class="warning-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
    <line x1="12" y1="9" x2="12" y2="13"/>
    <line x1="12" y1="17" x2="12.01" y2="17"/>
  </svg>
  Conteúdo inapropriado detectado
</small>
        </div>

        <!-- Info Box -->
        <div class="rpg-info-box">
          <svg class="rpg-info-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 16v-4"/>
            <circle cx="12" cy="8" r="0.5" fill="currentColor"/>
          </svg>
          <div class="rpg-info-content">
            <strong>Dica:</strong> Escolha um nome descritivo e adicione uma descrição envolvente para atrair mais jogadores!
          </div>
        </div>
      </div>

      <div class="rpg-modal-footer">
        <button type="button" class="rpg-btn rpg-btn-secondary" data-close-modal="modalCriarSala">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
          Cancelar
        </button>
        <button type="submit" class="rpg-btn rpg-btn-primary" id="btnCriarSala">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 4v16m8-8H4"/>
          </svg>
          <span>Criar Sala</span>
          <div class="rpg-btn-shine"></div>
        </button>
      </div>
    </form>

    <!-- Feedback de Sucesso/Erro -->
    <div class="rpg-modal-feedback" id="criarSalaFeedback">
      <div class="rpg-feedback-icon" id="feedbackIcon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <path d="M9 12l2 2 4-4"/>
        </svg>
      </div>
      <h4 id="feedbackTitle">Sala Criada!</h4>
      <p id="feedbackMessage">Sua sala foi criada com sucesso. Redirecionando...</p>
    </div>
  </div>
</div>

<!-- ========== MODAL ENTRAR EM SALA (NOVO DESIGN) ========== -->
<div class="rpg-modal" id="modalEntrarSala">
  <div class="rpg-modal-overlay" data-close-modal="modalEntrarSala"></div>
  <div class="rpg-modal-container rpg-modal-compact">
    <div class="rpg-modal-header">
      <div class="rpg-modal-icon rpg-modal-icon-blue">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
          <polyline points="10 17 15 12 10 7"/>
          <line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
      </div>
      <div class="rpg-modal-title-wrapper">
        <h3 class="rpg-modal-title">Entrar em Sala</h3>
        <p class="rpg-modal-subtitle">Digite o ID para juntar-se à aventura</p>
      </div>
      <button class="rpg-modal-close" data-close-modal="modalEntrarSala" type="button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"/>
          <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>

    <form id="formEntrarSala" class="rpg-modal-form">
      <div class="rpg-modal-body">
        <!-- Campo ID da Sala -->
        <div class="rpg-form-group">
          <label class="rpg-label">
            <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="3" width="7" height="7"/>
              <rect x="14" y="3" width="7" height="7"/>
              <rect x="14" y="14" width="7" height="7"/>
              <rect x="3" y="14" width="7" height="7"/>
            </svg>
            ID da Sala
            <span class="rpg-required">*</span>
          </label>
          <div class="rpg-input-wrapper">
            <input 
              type="number" 
              class="rpg-input rpg-input-large" 
              id="idSalaEntrar" 
              name="sala_id" 
              required 
              placeholder="Ex: 1234"
              min="1"
            >
            <div class="rpg-input-focus-border"></div>
          </div>
          <small class="rpg-hint">
  <svg class="hint-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <circle cx="12" cy="12" r="10"/>
    <path d="M12 16v-4"/>
    <circle cx="12" cy="8" r="0.5" fill="currentColor"/>
  </svg>
  Peça o ID ao mestre da sala
</small>
        </div>

        <!-- Preview da Sala (aparece após verificar) -->
        <div class="rpg-sala-preview" id="salaPreviewContainer" style="display:none;">
          <div class="rpg-preview-header">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <circle cx="12" cy="12" r="10"/>
    <path d="M12 16v-4"/>
    <circle cx="12" cy="8" r="0.5" fill="currentColor"/>
  </svg>
  Informações da Sala
</div>
          <div class="rpg-preview-body" id="salaPreviewContent">
            <!-- Conteúdo dinâmico será inserido aqui -->
          </div>
        </div>

        <!-- Campo Senha (condicional) -->
        <div class="rpg-form-group" id="senhaEntrarContainer" style="display:none;">
          <label class="rpg-label">
            <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            Senha da Sala
            <span class="rpg-required">*</span>
          </label>
          <div class="rpg-input-wrapper">
            <input 
              type="password" 
              class="rpg-input" 
              id="senhaEntrar" 
              name="senha" 
              placeholder="Digite a senha"
            >
            <div class="rpg-input-focus-border"></div>
          </div>
          <div class="rpg-password-hint">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
  </svg>
  Esta sala é privada e requer senha
</div>
        </div>
      </div>

      <div class="rpg-modal-footer">
        <button type="button" class="rpg-btn rpg-btn-secondary" data-close-modal="modalEntrarSala">
          Cancelar
        </button>
        <button type="button" id="btnVerificarSala" class="rpg-btn rpg-btn-info">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/>
            <path d="M21 21l-4.35-4.35"/>
          </svg>
          <span>Verificar Sala</span>
        </button>
        <button type="submit" id="btnEntrarSala" class="rpg-btn rpg-btn-primary" style="display:none;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/>
            <line x1="15" y1="12" x2="3" y2="12"/>
          </svg>
          <span>Entrar na Sala</span>
          <div class="rpg-btn-shine"></div>
        </button>
      </div>
    </form>

    <!-- Feedback de Sucesso/Erro -->
    <div class="rpg-modal-feedback" id="entrarSalaFeedback">
      <div class="rpg-feedback-icon" id="entrarFeedbackIcon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <path d="M9 12l2 2 4-4"/>
        </svg>
      </div>
      <h4 id="entrarFeedbackTitle">Entrando...</h4>
      <p id="entrarFeedbackMessage">Preparando sua entrada na sala...</p>
    </div>
  </div>
</div>

    <!-- Modal Editar Sala (Staff) -->
    <div class="rpg-modal" id="modalEditarSala">
  <div class="rpg-modal-overlay" data-close-modal="modalEditarSala"></div>
  <div class="rpg-modal-container">
    <div class="rpg-modal-header">
      <div class="rpg-modal-icon" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.1)); border-color: rgba(239, 68, 68, 0.3);">
        <svg viewBox="0 0 24 24" fill="none" stroke="#7aef44ff" stroke-width="2">
          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
      </div>
      <div class="rpg-modal-title-wrapper">
        <h3 class="rpg-modal-title">Editar Sala (Staff)</h3>
        <p class="rpg-modal-subtitle">Modificar configurações da sala</p>
      </div>
      <button class="rpg-modal-close" data-close-modal="modalEditarSala" type="button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"/>
          <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>

    <form id="formEditarSala" class="rpg-modal-form">
      <input type="hidden" id="editSalaId">
      
      <div class="rpg-modal-body">
        <!-- Nome e Tipo -->
        <div class="rpg-form-row">
          <div class="rpg-form-group">
            <label class="rpg-label">
              <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 6l6-3 6 3 6-3v15l-6 3-6-3-6 3V6z"/>
              </svg>
              Nome da Sala
              <span class="rpg-required">*</span>
            </label>
            <div class="rpg-input-wrapper">
              <input type="text" class="rpg-input" id="editNomeSala" name="nome" required placeholder="Nome da sala">
              <div class="rpg-input-focus-border"></div>
            </div>
            <small id="editNomeSala-warning" class="rpg-warning">
              <svg class="warning-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
              </svg>
              Conteúdo inapropriado detectado
            </small>
          </div>

          <div class="rpg-form-group">
            <label class="rpg-label">
              <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
              </svg>
              Tipo da Sala
              <span class="rpg-required">*</span>
            </label>
            <div class="rpg-select-wrapper">
              <select class="rpg-select" id="editTipoSala" name="tipo" required>
                <option value="publica">Pública</option>
                <option value="privada">Privada</option>
              </select>
              <div class="rpg-select-arrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M6 9l6 6 6-6"/>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Descrição -->
        <div class="rpg-form-group">
          <label class="rpg-label">
            <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
            Descrição
            <span class="rpg-optional">(opcional)</span>
          </label>
          <div class="rpg-textarea-wrapper">
            <textarea class="rpg-textarea" id="editDescricaoSala" name="descricao" rows="3" placeholder="Descrição da sala..."></textarea>
            <div class="rpg-textarea-focus-border"></div>
          </div>
          <small id="editDescricaoSala-warning" class="rpg-warning">
            <svg class="warning-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
              <line x1="12" y1="9" x2="12" y2="13"/>
              <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            Conteúdo inapropriado detectado
          </small>
        </div>

        <!-- Max Participantes e Status -->
        <div class="rpg-form-row">
          <div class="rpg-form-group">
            <label class="rpg-label">
              <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
              </svg>
              Máx. Participantes
            </label>
            <div class="rpg-input-wrapper">
              <input type="number" class="rpg-input" id="editMaxParticipantes" name="max_participantes" min="2" max="100" value="50">
              <div class="rpg-input-focus-border"></div>
            </div>
          </div>

          <div class="rpg-form-group">
            <label class="rpg-label">
              <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M8 12l2 2 4-4"/>
              </svg>
              Status da Sala
            </label>
            <div class="rpg-toggle-wrapper">
              <label class="rpg-toggle">
                <input type="checkbox" id="editAtiva" name="ativa" checked>
                <span class="rpg-toggle-slider"></span>
                <span class="rpg-toggle-label">Sala Ativa</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Info Box -->
        <div class="rpg-info-box" style="background: rgba(239, 68, 68, 0.08); border-color: rgba(239, 68, 68, 0.2);">
          <svg class="rpg-info-icon" style="stroke: #ef4444;" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M12 9v4"/>
  <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
  <circle cx="12" cy="17" r="0.5" fill="#ef4444"/>
</svg>
          <div class="rpg-info-content">
            <strong style="color: #ef4444;">Atenção:</strong> Estas alterações afetarão todos os participantes da sala. Use com responsabilidade.
          </div>
        </div>
      </div>

      <div class="rpg-modal-footer">
        <button type="button" class="rpg-btn rpg-btn-secondary" data-close-modal="modalEditarSala">
          Cancelar
        </button>
        <button type="submit" class="rpg-btn rpg-btn-primary" id="btnSalvarEdicao">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
            <polyline points="17 21 17 13 7 13 7 21"/>
            <polyline points="7 3 7 8 15 8"/>
          </svg>
          <span>Salvar Alterações</span>
          <div class="rpg-btn-shine"></div>
        </button>
      </div>
    </form>

    <!-- Feedback -->
    <div class="rpg-modal-feedback" id="editarSalaFeedback">
      <div class="rpg-feedback-icon" id="editarFeedbackIcon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <path d="M9 12l2 2 4-4"/>
        </svg>
      </div>
      <h4 id="editarFeedbackTitle">Salvando...</h4>
      <p id="editarFeedbackMessage">Atualizando informações da sala...</p>
    </div>
  </div>
</div>

    <!-- Modal Motivo de Desativação -->
    <div class="rpg-modal" id="modalMotivoDesativacao">
  <div class="rpg-modal-overlay" data-close-modal="modalMotivoDesativacao"></div>
  <div class="rpg-modal-container rpg-modal-compact">
    <div class="rpg-modal-header">
      <div class="rpg-modal-icon" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.1)); border-color: rgba(239, 68, 68, 0.3);">
        <svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <line x1="15" y1="9" x2="9" y2="15"/>
          <line x1="9" y1="9" x2="15" y2="15"/>
        </svg>
      </div>
      <div class="rpg-modal-title-wrapper">
        <h3 class="rpg-modal-title" style="color: #ef4444;">Desativar Sala</h3>
        <p class="rpg-modal-subtitle">Esta ação impedirá novos acessos</p>
      </div>
      <button class="rpg-modal-close" data-close-modal="modalMotivoDesativacao" type="button">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="18" y1="6" x2="6" y2="18"/>
          <line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>

    <form id="formMotivoDesativacao" class="rpg-modal-form">
      <input type="hidden" id="motivoSalaId">
      
      <div class="rpg-modal-body">
        <!-- Alert de Atenção -->
        <div class="rpg-info-box" style="background: rgba(239, 68, 68, 0.08); border-color: rgba(239, 68, 68, 0.2);">
          <svg class="rpg-info-icon" style="stroke: #ef4444;" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M12 9v4"/>
  <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
  <circle cx="12" cy="17" r="0.5" fill="#ef4444"/>
</svg>
          <div class="rpg-info-content">
            <strong style="color: #ef4444;">Atenção:</strong> Esta ação desativará a sala e impedirá novos acessos. Apenas membros da staff poderão visualizá-la.
          </div>
        </div>

        <!-- Sala a ser desativada -->
        <div class="rpg-form-group">
          <label class="rpg-label">
            <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 6l6-3 6 3 6-3v15l-6 3-6-3-6 3V6z"/>
            </svg>
            Sala a ser desativada
          </label>
          <div class="rpg-sala-info-box">
            <strong id="motivoSalaNome">Carregando...</strong>
          </div>
        </div>

        <!-- Motivo -->
        <div class="rpg-form-group">
          <label class="rpg-label">
            <svg class="rpg-label-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
            Motivo da Desativação
            <span class="rpg-optional">(opcional)</span>
          </label>
          <div class="rpg-textarea-wrapper">
            <textarea 
    class="rpg-textarea" 
    id="motivoDesativacao" 
    name="motivo" 
    rows="4" 
    placeholder="Ex: Violação das regras da comunidade, conteúdo inapropriado, spam, etc."
    required
    minlength="20"
></textarea>
            <div class="rpg-textarea-focus-border"></div>
          </div>
          <small class="rpg-hint" style="display: flex; justify-content: space-between; align-items: center;">
    <span>
        <svg class="hint-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 18h6"/>
            <path d="M10 22h4"/>
            <path d="M12 2a7 7 0 0 0-7 7c0 2.38 1.19 4.47 3 5.74V17a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-2.26c1.81-1.27 3-3.36 3-5.74a7 7 0 0 0-7-7z"/>
        </svg>
        O criador da sala poderá ver este motivo
    </span>
    <span id="motivoCounter" style="font-weight: 600; color: var(--muted);">0/20</span>
</small>
        </div>
      </div>

      <div class="rpg-modal-footer">
        <button type="button" class="rpg-btn rpg-btn-secondary" data-close-modal="modalMotivoDesativacao">
          Cancelar
        </button>
        <button type="submit" class="rpg-btn rpg-btn-danger" id="btnConfirmarDesativacao">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="15" y1="9" x2="9" y2="15"/>
            <line x1="9" y1="9" x2="15" y2="15"/>
          </svg>
          <span>Desativar Sala</span>
          <div class="rpg-btn-shine"></div>
        </button>
      </div>
    </form>

    <!-- Feedback -->
    <div class="rpg-modal-feedback" id="desativarSalaFeedback">
      <div class="rpg-feedback-icon" id="desativarFeedbackIcon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <path d="M9 12l2 2 4-4"/>
        </svg>
      </div>
      <h4 id="desativarFeedbackTitle">Processando...</h4>
      <p id="desativarFeedbackMessage">Desativando sala...</p>
    </div>
  </div>
</div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        const AUTH_ID = {{ (int) $userId }};
        const IS_STAFF = {{ $isStaff ? 'true' : 'false' }};

        // ========== HELPER PARA ÍCONES DE TIPO ==========
function getSalaIcon(tipo) {
    const icons = {
        'publica': `
            <svg style="width:14px;height:14px;margin-right:6px;vertical-align:middle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10"/>
                <line x1="2" y1="12" x2="22" y2="12"/>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
            </svg>
        `,
        'privada': `
            <svg style="width:14px;height:14px;margin-right:6px;vertical-align:middle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        `
    };
    return icons[tipo] || icons['publica'];
}

function getTipoTextWithIcon(tipo) {
    const icons = {
        'publica': `
            <span class="tipo-icon">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="2" y1="12" x2="22" y2="12"/>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                </svg>
            </span>
        `,
        'privada': `
            <span class="tipo-icon">
                <svg viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </span>
        `
    };
    
    const icon = icons[tipo] || icons['publica'];
    const text = tipo === 'publica' ? 'Pública' : 'Privada';
    
    return `${icon}${text}`;
}

        // ========== TABS INTERATIVAS ==========
        const tabs = document.querySelectorAll('.tab-button');
        const tabUnderline = document.getElementById('tabUnderline');
        const sections = document.querySelectorAll('.section-content');

        function updateTabUnderline(activeTab) {
            const rect = activeTab.getBoundingClientRect();
            const parent = activeTab.parentElement.getBoundingClientRect();
            tabUnderline.style.width = rect.width + 'px';
            tabUnderline.style.left = (rect.left - parent.left) + 'px';
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const section = tab.dataset.section;
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                updateTabUnderline(tab);
                
                sections.forEach(s => {
                    const container = s.querySelector('.cards-grid');
                    if (container) container.classList.add('transitioning');
                });
                
                setTimeout(() => {
                    sections.forEach(s => s.style.display = 'none');
                    const targetSection = document.getElementById(`section-${section}`);
                    if (targetSection) {
                        targetSection.style.display = 'block';
                        const container = targetSection.querySelector('.cards-grid');
                        if (container) {
                            setTimeout(() => container.classList.remove('transitioning'), 50);
                        }
                    }
                }, 150);
            });
        });

        window.addEventListener('load', () => {
            const activeTab = document.querySelector('.tab-button.active');
            if (activeTab) updateTabUnderline(activeTab);
        });

        // ========== SISTEMA DE SALAS ==========
        class SistemaSalas {
            constructor() {
                this.pageMinhas = 1;
                this.pagePublicas = 1;
                this.pageDesativadas = 1;
                this.searchMinhas = '';
                this.searchPublicas = '';
                this.searchDesativadas = '';
                this.hasMoreMinhas = false;
                this.hasMorePublicas = false;
                this.hasMoreDesativadas = false;
                
                this.init();
                this.loadSalas();
                this.bindEvents();
                this.setupSearch();
                if (IS_STAFF) this.loadSalasDesativadas();
            }

            init() {
                console.log('🎮 Sistema de Salas inicializado');
            }

            setupSearch() {
                let timeoutMinhas, timeoutPublicas, timeoutDesativadas;

                $('#searchMinhasSalas').on('input', (e) => {
                    clearTimeout(timeoutMinhas);
                    timeoutMinhas = setTimeout(() => {
                        this.searchMinhas = e.target.value.trim();
                        this.pageMinhas = 1;
                        this.loadSalas();
                    }, 500);
                });

                $('#searchSalasPublicas').on('input', (e) => {
                    clearTimeout(timeoutPublicas);
                    timeoutPublicas = setTimeout(() => {
                        this.searchPublicas = e.target.value.trim();
                        this.pagePublicas = 1;
                        this.loadSalas();
                    }, 500);
                });

                $('#searchSalasDesativadas').on('input', (e) => {
                    clearTimeout(timeoutDesativadas);
                    timeoutDesativadas = setTimeout(() => {
                        this.searchDesativadas = e.target.value.trim();
                        this.pageDesativadas = 1;
                        this.loadSalasDesativadas();
                    }, 500);
                });
            }

            highlightSearchTerms(text, searchTerm) {
                if (!searchTerm) return text;
                const regex = new RegExp(`(${searchTerm})`, 'gi');
                return text.replace(regex, '<span class="search-highlight">$1</span>');
            }

            loadSalas() {
    // Se é primeira carga, mostra skeleton
    if (this.pageMinhas === 1) {
        this.showSkeleton('minhasSalasContainer', 3);
    }
    
    if (this.pagePublicas === 1) {
        this.showSkeleton('salasPublicasContainer', 3);
    }
    
    $.ajax({
        url: '/api/salas/data',
        type: 'GET',
        data: {
            page_minhas: this.pageMinhas,
            page_publicas: this.pagePublicas,
            search_minhas: this.searchMinhas,
            search_publicas: this.searchPublicas
        },
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json'}
    })
    .done(data => {
        if (data.success) {
            // Pequeno delay para mostrar o skeleton (UX)
            setTimeout(() => {
                if (this.pageMinhas === 1) this.renderMinhasSalas(data.minhas_salas);
                else this.appendMinhasSalas(data.minhas_salas);

                if (this.pagePublicas === 1) this.renderSalasPublicas(data.salas_publicas);
                else this.appendSalasPublicas(data.salas_publicas);

                this.hasMoreMinhas = data.pagination.minhas.has_more;
                this.hasMorePublicas = data.pagination.publicas.has_more;

                $('#minhasSalasVerMais').toggle(this.hasMoreMinhas);
                $('#salasPublicasVerMais').toggle(this.hasMorePublicas);
                $('#minhasSalasCount').text(data.pagination.minhas.total);
                $('#salasPublicasCount').text(data.pagination.publicas.total);
            }, 800); // 800ms para mostrar skeleton
        }
    })
    .fail(xhr => {
        console.error('❌ Erro ao carregar salas:', xhr);
        this.showAlert('Erro ao carregar salas. Tente novamente.', 'danger');
        
        // Remover skeleton em caso de erro
        $('#minhasSalasContainer').html('<div class="empty-state"><i class="fas fa-exclamation-triangle"></i><h4>Erro ao carregar</h4><p>Tente recarregar a página</p></div>');
    });
}

            loadSalasDesativadas() {
                if (!IS_STAFF) return;
                $.ajax({
                    url: '/api/salas/desativadas',
                    type: 'GET',
                    data: {page: this.pageDesativadas, search: this.searchDesativadas},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json'}
                })
                .done(data => {
                    if (data.success) {
                        if (this.pageDesativadas === 1) this.renderSalasDesativadas(data.salas);
                        else this.appendSalasDesativadas(data.salas);
                        this.hasMoreDesativadas = data.pagination.has_more;
                        $('#salasDesativadasVerMais').toggle(this.hasMoreDesativadas);
                        $('#salasDesativadasCount').text(data.salas.length);
                    }
                })
                .fail(xhr => console.error('❌ Erro ao carregar salas desativadas:', xhr));
            }

            carregarMaisMinhasSalas() {
    this.showLoading(true);
    this.pageMinhas++;
    
    $.ajax({
        url: '/api/salas/data',
        type: 'GET',
        data: {
            page_minhas: this.pageMinhas,
            page_publicas: 1,
            search_minhas: this.searchMinhas,
            search_publicas: ''
        },
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json'}
    })
    .done(data => {
        if (data.success) {
            setTimeout(() => {
                this.appendMinhasSalas(data.minhas_salas);
                this.hasMoreMinhas = data.pagination.minhas.has_more;
                $('#minhasSalasVerMais').toggle(this.hasMoreMinhas);
                this.hideLoading(true);
            }, 600);
        }
    })
    .fail(() => {
        this.hideLoading(true);
        this.showAlert('Erro ao carregar mais salas.', 'danger');
    });
}

            carregarMaisSalasPublicas() {
    this.showLoading(true);
    this.pagePublicas++;
    
    $.ajax({
        url: '/api/salas/data',
        type: 'GET',
        data: {
            page_minhas: 1,
            page_publicas: this.pagePublicas,
            search_minhas: '',
            search_publicas: this.searchPublicas
        },
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json'}
    })
    .done(data => {
        if (data.success) {
            setTimeout(() => {
                this.appendSalasPublicas(data.salas_publicas);
                this.hasMorePublicas = data.pagination.publicas.has_more;
                $('#salasPublicasVerMais').toggle(this.hasMorePublicas);
                this.hideLoading(true);
            }, 600);
        }
    })
    .fail(() => {
        this.hideLoading(true);
        this.showAlert('Erro ao carregar mais salas.', 'danger');
    });
}

            carregarMaisSalasDesativadas() {
                this.pageDesativadas++;
                this.loadSalasDesativadas();
            }

            renderMinhasSalas(salas) {
                const container = $('#minhasSalasContainer');
                if (salas.length === 0) {
                    container.html(`<div class="empty-state"><i class="fas fa-home"></i><h4>Você ainda não participa de nenhuma sala</h4><p>Crie sua primeira sala ou entre em uma sala pública!</p></div>`);
                    return;
                }
                let html = '';
                salas.forEach(sala => { html += this.generateSalaCard(sala, true); });
                container.html(html);
            }

            appendMinhasSalas(salas) {
                const container = $('#minhasSalasContainer');
                let html = '';
                salas.forEach(sala => { html += this.generateSalaCard(sala, true); });
                container.append(html);
            }

            renderSalasPublicas(salas) {
                const container = $('#salasPublicasContainer');
                if (salas.length === 0) {
                    container.html(`<div class="empty-state"><i class="fas fa-globe"></i><h4>Nenhuma sala pública disponível</h4><p>Seja o primeiro a criar uma sala pública!</p></div>`);
                    return;
                }
                let html = '';
                salas.forEach(sala => { html += this.generateSalaCard(sala, false); });
                container.html(html);
            }

            appendSalasPublicas(salas) {
                const container = $('#salasPublicasContainer');
                let html = '';
                salas.forEach(sala => { html += this.generateSalaCard(sala, false); });
                container.append(html);
            }

            renderSalasDesativadas(salas) {
    const container = $('#salasDesativadasContainer');
    if (salas.length === 0) {
        container.html(`
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4"/>
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                </div>
                <h4>Nenhuma sala desativada</h4>
                <p>Todas as salas estão ativas e funcionando!</p>
            </div>
        `);
        return;
    }
    let html = '';
    salas.forEach(sala => { html += this.generateSalaCard(sala, false, true); });
    container.html(html);
}

            appendSalasDesativadas(salas) {
                const container = $('#salasDesativadasContainer');
                let html = '';
                salas.forEach(sala => { html += this.generateSalaCard(sala, false, true); });
                container.append(html);
            }

            mostrarMinhasSalasDesativadas() {
                $('#secaoMinhasSalasDesativadas').slideDown(400);
                this.loadMinhasSalasDesativadas();
                $('html, body').animate({scrollTop: $('#secaoMinhasSalasDesativadas').offset().top - 100}, 600);
            }

            ocultarMinhasSalasDesativadas() {
                $('#secaoMinhasSalasDesativadas').slideUp(400);
            }

            loadMinhasSalasDesativadas() {
                $.ajax({
                    url: '/api/salas/minhas-desativadas',
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json'}
                })
                .done(data => {
                    if (data.success) this.renderMinhasSalasDesativadas(data.salas);
                })
                .fail(xhr => {
                    console.error('❌ Erro ao carregar suas salas desativadas:', xhr);
                    this.showAlert('Erro ao carregar suas salas desativadas.', 'danger');
                });
            }

            renderMinhasSalasDesativadas(salas) {
                const container = $('#minhasSalasDesativadasContainer');
                if (salas.length === 0) {
                    container.html(`<div class="empty-state"><i class="fas fa-check-circle text-success"></i><h4>Nenhuma sala desativada</h4><p>Suas salas estão todas ativas!</p></div>`);
                    return;
                }
                let html = '';
                salas.forEach(sala => { html += this.generateSalaCardDesativadaCreator(sala); });
                container.html(html);
            }

            generateSalaCard(sala, isMyRoom, isDesativada = false) {
                const participantes = sala.participantes || [];
                const criador = sala.criador;
                const tipoClass = `tipo-${sala.tipo}`;
                const tipoText = getTipoTextWithIcon(sala.tipo);
                const isCreator = criador && parseInt(criador.id) === parseInt(AUTH_ID);
                
                const bannerStyle = sala.banner_url ? `background-image:url('${sala.banner_url}');` : `background-color:${sala.banner_color || '#6c757d'};`;
                const bannerMini = `<div class="sala-banner" style="${bannerStyle}">${isCreator ? `<div class="banner-edit-btn"><button class="open-banner-editor-btn" data-sala-id="${sala.id}" data-banner-url="${sala.banner_url || ''}" data-banner-color="${sala.banner_color || ''}"><i class="fa-solid fa-image"></i> Editar</button></div>` : ''}${!sala.banner_url ? `<div class="banner-fallback">${this.highlightSearchTerms(sala.nome, this.searchMinhas || this.searchPublicas)}</div>` : ''}</div>`;
                
                const photoStyle = sala.profile_photo_url ? `background-image:url('${sala.profile_photo_url}');` : `background-color:${sala.profile_photo_color || '#6c757d'};`;
                const profilePhoto = `<div class="sala-profile-photo" style="${photoStyle}">${isCreator ? `<button class="photo-edit-btn open-profile-photo-editor-btn" data-sala-id="${sala.id}" data-foto-url="${sala.profile_photo_url || ''}" title="Editar foto"><i class="fa-solid fa-camera"></i></button>` : ''}${!sala.profile_photo_url ? `<div class="photo-fallback">${sala.nome.charAt(0).toUpperCase()}</div>` : ''}</div>`;
                
                const actionButton = isMyRoom ? `<a href="/salas/${sala.id}" class="btn-enter"><i class="fas fa-play"></i>Entrar</a>` : `<button class="btn-enter" onclick="sistema.entrarSalaRapida(${sala.id})"><i class="fas fa-sign-in-alt"></i>Juntar-se</button>`;
                
                const staffAccessBadge = (isDesativada && IS_STAFF) ? `<span class="badge-staff-access"><i class="fas fa-shield-alt"></i>ACESSO STAFF</span>` : '';
                
                const staffActions = IS_STAFF ? `<div class="staff-actions"><div class="dropdown"><button class="dropdown-toggle" type="button" data-bs-toggle="dropdown"><i class="fas fa-shield-alt"></i></button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="#" onclick="sistema.editarSalaStaff(${sala.id}); return false;"><i class="fas fa-edit"></i>Editar</a></li><li><a class="dropdown-item" href="#" onclick="toggleStatusSala(${sala.id}, ${sala.ativa}, '${sala.nome.replace(/'/g, "\\'")}'); return false;"
><i class="fas fa-power-off"></i>${sala.ativa ? 'Desativar' : 'Ativar'}</a></li><li><hr class="dropdown-divider"></li><li><a class="dropdown-item text-danger" href="#" onclick="sistema.deletarSalaStaff(${sala.id}); return false;"><i class="fas fa-trash"></i>Deletar</a></li></ul></div></div>` : '';
                
                const statusClass = sala.ativa ? '' : 'status-inactive';
                
                const participantesHtml = participantes.length > 0 ? `<div class="sala-participants"><div class="participants-header">Participantes (${participantes.length})</div><div class="participants-grid">${participantes.slice(0, 6).map((p, idx) => {
    const usuario = p.usuario || p;
    const avatarUrl = usuario.avatar_url || usuario.avatar || '/images/default-avatar.png';
    const username = usuario.username || 'Usuário';
    return `<div class="participant-avatar" style="animation-delay:${idx * 0.05}s" title="${username}"><img src="${avatarUrl}" alt="${username}"></div>`;
}).join('')}</div></div>` : '';
                
                const motivoHtml = sala.motivo_desativacao ? `<div class="desativacao-info"><strong>Motivo da desativação:</strong><p>${sala.motivo_desativacao}</p><small>${sala.desativada_por ? `Desativada por: <strong>${sala.desativada_por.username || 'Staff'}</strong>` : ''}${sala.data_desativacao ? ` em ${this.formatDateTime(sala.data_desativacao)}` : ''}</small></div>` : '';
                
                return `
                    <div class="sala-card ${statusClass}" style="animation-delay:${Math.random() * 0.3}s">
                        ${staffAccessBadge}
                        ${staffActions}
                        ${bannerMini}
                        <div class="sala-profile-section">
                            ${profilePhoto}
                            <span class="tipo-badge ${tipoClass}">${tipoText}</span>
                        </div>
                        <div class="sala-card-body">
                            <div class="sala-name">${this.highlightSearchTerms(sala.nome, this.searchMinhas || this.searchPublicas)}</div>
                            <p class="sala-description">${this.highlightSearchTerms(sala.descricao || 'Sem descrição', this.searchMinhas || this.searchPublicas)}</p>
                            <div class="sala-stats">
                                <div class="stat-item"><span class="stat-label">ID</span><span class="stat-value">${sala.id}</span></div>
                                <div class="stat-item"><span class="stat-label">Participantes</span><span class="stat-value">${participantes.length}/${sala.max_participantes}</span></div>
                                <div class="stat-item"><span class="stat-label">Criador</span><span class="stat-value">${(criador && criador.username) || 'N/A'}</span></div>
                            </div>
                            ${participantesHtml}
                            ${motivoHtml}
                        </div>
                        <div class="sala-card-footer">
                            <span class="sala-meta"><i class="fas fa-clock"></i>Criada ${this.formatDate(sala.data_criacao)}</span>
                            ${actionButton}
                        </div>
                    </div>
                `;
            }

            generateSalaCardDesativadaCreator(sala) {
                const participantes = sala.participantes || [];
                const tipoClass = `tipo-${sala.tipo}`;
                const tipoText = sala.tipo === 'publica' ? 'Pública' : (sala.tipo === 'privada' ? 'Privada' : 'Convite');
                
                const bannerStyle = sala.banner_url ? `background-image:url('${sala.banner_url}');` : `background-color:${sala.banner_color || '#6c757d'};`;
                const photoStyle = sala.profile_photo_url ? `background-image:url('${sala.profile_photo_url}');` : `background-color:${sala.profile_photo_color || '#6c757d'};`;
                
                return `
                    <div class="sala-card status-inactive" style="animation-delay:${Math.random() * 0.3}s">
                        <div class="sala-banner" style="${bannerStyle}">${!sala.banner_url ? `<div class="banner-fallback">${sala.nome}</div>` : ''}</div>
                        <div class="sala-profile-section">
                            <div class="sala-profile-photo" style="${photoStyle}">${!sala.profile_photo_url ? `<div class="photo-fallback">${sala.nome.charAt(0).toUpperCase()}</div>` : ''}</div>
                            <span class="tipo-badge ${tipoClass}">${tipoText}</span>
                        </div>
                        <div class="sala-card-body">
                            <div class="sala-name">${sala.nome}</div>
                            <div class="alert alert-warning" style="margin-bottom:1rem;font-size:0.875rem">
                                <i class="fas fa-info-circle"></i>
                                Esta sala foi desativada pela Moderação. Entre em contato com a equipe para mais informações.
                            </div>
                            ${sala.motivo_desativacao ? `<div class="desativacao-info"><strong>Motivo da desativação:</strong><p>${sala.motivo_desativacao}</p><small>${sala.desativada_por ? `Desativada por: <strong>${sala.desativada_por.username || 'Staff'}</strong>` : ''}${sala.data_desativacao ? ` em ${this.formatDateTime(sala.data_desativacao)}` : ''}</small></div>` : ''}
                            <p class="sala-description">${sala.descricao || 'Sem descrição'}</p>
                            <div class="sala-stats">
                                <div class="stat-item"><span class="stat-label">ID</span><span class="stat-value">${sala.id}</span></div>
                                <div class="stat-item"><span class="stat-label">Participantes</span><span class="stat-value">${participantes.length}/${sala.max_participantes}</span></div>
                                <div class="stat-item"><span class="stat-label">Status</span><span class="stat-value" style="color:#ef4444">Inativa</span></div>
                            </div>
                        </div>
                        <div class="sala-card-footer">
                            <span class="sala-meta"><i class="fas fa-clock"></i>Criada ${this.formatDate(sala.data_criacao)}</span>
                            <button class="btn-enter" disabled style="opacity:0.5"><i class="fas fa-ban"></i>Inacessível</button>
                        </div>
                    </div>
                `;
            }

            formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('pt-BR', {day: '2-digit', month: '2-digit', year: 'numeric'});
            }

            formatDateTime(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleString('pt-BR', {day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'});
            }

           editarSalaStaff(salaId) {
    // Usar a função global
    if (typeof window.editarSalaStaff === 'function') {
        window.editarSalaStaff(salaId);
    } else {
        console.error('Função editarSalaStaff não encontrada');
    }
}
        

            confirmarDesativacaoComMotivo() {
                const salaId = $('#motivoSalaId').val();
                const motivo = $('#motivoDesativacao').val().trim();
                const btnSubmit = $('#formMotivoDesativacao button[type="submit"]');
                const textoOriginal = btnSubmit.html();
                btnSubmit.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>Desativando...');
                
                $.ajax({
                    url: `/salas/${salaId}/staff/toggle-status`,
                    type: 'POST',
                    data: {motivo: motivo || null},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json'}
                })
                .done(response => {
                    if (response.success) {
                        this.showAlert(response.message, 'success');
                        $('#modalMotivoDesativacao').modal('hide');
                        this.recarregarTodasSalas();
                    }
                })
                .fail(xhr => {
                    const msg = xhr.responseJSON?.message || 'Erro ao desativar sala.';
                    this.showAlert(msg, 'danger');
                })
                .always(() => btnSubmit.prop('disabled', false).html(textoOriginal));
            }

            recarregarTodasSalas() {
                this.pageMinhas = 1;
                this.pagePublicas = 1;
                this.pageDesativadas = 1;
                this.loadSalas();
                if (IS_STAFF) this.loadSalasDesativadas();
            }

            deletarSalaStaff(salaId) {
    if (!confirm('⚠️ ATENÇÃO: Esta ação é IRREVERSÍVEL!\n\nDeseja realmente deletar esta sala permanentemente?')) return;
    
    // Desabilitar todos os botões de ação durante a operação
    const btnSubmit = event?.target;
    if (btnSubmit) {
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deletando...';
    }
    
    $.ajax({
        url: `/salas/${salaId}/staff/delete`, 
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    })
    .done(response => {
        if (response.success) {
            this.showAlert(response.message, 'success');
            // Recarregar TODAS as seções
            this.pageMinhas = 1;
            this.pagePublicas = 1;
            this.pageDesativadas = 1;
            this.loadSalas();
            if (IS_STAFF) this.loadSalasDesativadas();
        } else {
            this.showAlert(response.message || 'Erro ao deletar sala.', 'danger');
            if (btnSubmit) btnSubmit.disabled = false;
        }
    })
    .fail(xhr => {
        let msg = 'Erro ao deletar sala.';
        
        if (xhr.status === 403) {
            msg = 'Você não tem permissão para deletar esta sala.';
        } else if (xhr.status === 404) {
            msg = 'Sala não encontrada.';
        } else if (xhr.responseJSON?.message) {
            msg = xhr.responseJSON.message;
        }
        
        this.showAlert(msg, 'danger');
        if (btnSubmit) btnSubmit.disabled = false;
    });
}

            bindEvents() {
                $('#tipoSala').change(e => {
                    const senhaContainer = $('#senhaContainer');
                    if (e.target.value === 'privada') {
                        senhaContainer.show();
                        $('#senhaSala').attr('required', true);
                    } else {
                        senhaContainer.hide();
                        $('#senhaSala').attr('required', false);
                    }
                });

                $('#formEntrarSala').submit(e => {
                    e.preventDefault();
                    this.entrarSala();
                });

                $('#modalEntrarSala').on('hidden.bs.modal', () => this.resetFormEntrarSala());
            }

            salvarEdicaoSala() {
                const salaId = $('#editSalaId').val();
                if (!salaId) {
                    this.showAlert('ID da sala não encontrado.', 'danger');
                    return;
                }

                const data = {
                    nome: $('#editNomeSala').val().trim(),
                    descricao: $('#editDescricaoSala').val().trim(),
                    tipo: $('#editTipoSala').val(),
                    max_participantes: parseInt($('#editMaxParticipantes').val()) || 50,
                    ativa: $('#editAtiva').is(':checked')
                };

                if (!data.nome || data.nome.length < 3) {
                    this.showAlert('O nome da sala deve ter pelo menos 3 caracteres.', 'warning');
                    return;
                }

                if (data.max_participantes < 2 || data.max_participantes > 100) {
                    this.showAlert('O número de participantes deve estar entre 2 e 100.', 'warning');
                    return;
                }

                const btnSalvar = $('#formEditarSala button[type="submit"]');
                const textoOriginal = btnSalvar.html();
                btnSalvar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>Salvando...');

                $.ajax({
                    url: `/salas/${salaId}/staff/update`,
                    type: 'PUT',
                    data: data,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json'}
                })
                .done(response => {
                    if (response.success) {
                        this.showAlert(response.message || 'Sala atualizada com sucesso!', 'success');
                        $('#modalEditarSala').modal('hide');
                        setTimeout(() => {
                            this.pageMinhas = 1;
                            this.pagePublicas = 1;
                            this.loadSalas();
                        }, 1000);
                    } else {
                        this.showAlert(response.message || 'Erro ao salvar alterações.', 'danger');
                    }
                })
                .fail(xhr => {
                    let errorMsg = 'Erro ao salvar alterações.';
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            errorMsg = 'Erros de validação:<br>';
                            Object.values(errors).forEach(error => {
                                errorMsg += `• ${error[0]}<br>`;
                            });
                        }
                    } else if (xhr.status === 403) {
                        errorMsg = 'Você não tem permissão para editar esta sala.';
                    } else if (xhr.status === 404) {
                        errorMsg = 'Sala não encontrada.';
                    } else if (xhr.responseJSON?.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    this.showAlert(errorMsg, 'danger');
                })
                .always(() => btnSalvar.prop('disabled', false).html(textoOriginal));
            }

            verificarSala() {
                const salaId = $('#idSalaEntrar').val();
                if (!salaId) {
                    this.showAlert('Digite o ID da sala primeiro.', 'warning');
                    return;
                }

                const btnVerificar = $('#btnVerificarSala');
                const btnEntrar = $('#btnEntrarSala');
                const originalText = btnVerificar.html();
                btnVerificar.html('<i class="fas fa-spinner fa-spin"></i>Verificando...').prop('disabled', true);

                $.get(`/salas/${salaId}/info`)
                    .done(response => {
                        if (response.success) {
                            this.mostrarInfoSala(response.sala);
                            btnEntrar.show();
                            btnVerificar.html('<i class="fas fa-check"></i>Verificado');
                        }
                    })
                    .fail(xhr => {
                        const errorMsg = xhr.responseJSON?.message || 'Erro ao verificar sala.';
                        this.showAlert(errorMsg, 'danger');
                        btnVerificar.html(originalText).prop('disabled', false);
                    });
            }

            mostrarInfoSala(sala) {
                const infoContainer = $('#infoSalaContainer');
                const senhaContainer = $('#senhaEntrarContainer');

                let infoHtml = `
                    <div class="row">
                        <div class="col-6"><strong>Nome:</strong><br><span style="color:var(--text-secondary)">${sala.nome}</span></div>
                        <div class="col-6"><strong>Tipo:</strong><br><span style="color:var(--text-secondary)">${this.getTipoText(sala.tipo)}</span></div>
                    </div>
                    <hr style="border-color:var(--border-subtle)">
                    <div class="row">
                        <div class="col-6"><strong>Participantes:</strong><br><span style="color:var(--text-secondary)">${sala.participantes_atuais}/${sala.max_participantes}</span></div>
                        <div class="col-6"><strong>Status:</strong><br><span class="badge" style="background:var(--accent)">Ativa</span></div>
                    </div>
                `;

                $('#infoSalaContent').html(infoHtml);
                infoContainer.show();

                if (sala.precisa_senha) {
                    senhaContainer.show();
                    $('#senhaEntrar').attr('required', true);
                } else {
                    senhaContainer.hide();
                    $('#senhaEntrar').attr('required', false);
                }

                if (sala.apenas_convite) {
                    this.showAlert('Esta sala é apenas por convite. Você precisa ser convidado.', 'warning');
                }
            }

            getTipoText(tipo) {
    return getTipoTextWithIcon(tipo);
}

            resetFormEntrarSala() {
                $('#formEntrarSala')[0].reset();
                $('#infoSalaContainer').hide();
                $('#senhaEntrarContainer').hide();
                $('#btnEntrarSala').hide();
                $('#btnVerificarSala').html('<i class="fas fa-search"></i>Verificar Sala').prop('disabled', false);
                $('#senhaEntrar').attr('required', false);
            }

            criarSala() {
                const formData = new FormData($('#formCriarSala')[0]);
                const data = Object.fromEntries(formData);

                $.post('/salas', data)
                    .done(response => {
                        if (response.success) {
                            this.showAlert(response.message, 'success');
                            $('#modalCriarSala').modal('hide');
                            $('#formCriarSala')[0].reset();
                            this.pageMinhas = 1;
                            this.loadSalas();
                            setTimeout(() => window.location.href = `/salas/${response.sala.id}`, 2000);
                        } else {
                            this.showAlert(response.message, 'danger');
                        }
                    })
                    .fail(xhr => {
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            let errorMsg = 'Erros de validação:<br>';
                            Object.values(errors).forEach(error => {
                                errorMsg += `• ${error[0]}<br>`;
                            });
                            this.showAlert(errorMsg, 'danger');
                        } else {
                            this.showAlert('Erro interno. Tente novamente.', 'danger');
                        }
                    });
            }

            entrarSala() {
                const formData = new FormData($('#formEntrarSala')[0]);
                const data = Object.fromEntries(formData);

                $.post('/salas/entrar', data)
                    .done(response => {
                        if (response.success) {
                            this.showAlert(response.message, 'success');
                            $('#modalEntrarSala').modal('hide');
                            this.resetFormEntrarSala();
                            setTimeout(() => window.location.href = response.redirect_to, 1500);
                        } else {
                            this.showAlert(response.message, 'warning');
                        }
                    })
                    .fail(xhr => {
                        const errorMsg = xhr.responseJSON?.message || 'Erro interno. Tente novamente.';
                        this.showAlert(errorMsg, 'danger');
                    });
            }

            showSkeleton(containerId, count = 3) {
    const container = $(`#${containerId}`);
    let skeletonHtml = '';
    
    for (let i = 0; i < count; i++) {
        skeletonHtml += `
            <div class="skeleton-card" style="animation-delay: ${i * 0.1}s">
                <div class="skeleton-banner"></div>
                <div class="skeleton-profile"></div>
                <div class="skeleton-content">
                    <div class="skeleton-line title"></div>
                    <div class="skeleton-line subtitle"></div>
                    <div class="skeleton-line subtitle"></div>
                    <div class="skeleton-stats">
                        <div class="skeleton-stat"></div>
                        <div class="skeleton-stat"></div>
                        <div class="skeleton-stat"></div>
                    </div>
                    <div class="skeleton-line"></div>
                    <div class="skeleton-line short"></div>
                </div>
            </div>
        `;
    }
    
    container.html(`<div class="skeleton-container">${skeletonHtml}</div>`);
}

            entrarSalaRapida(salaId) {
                $.ajax({
                    url: '/salas/entrar',
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded'},
                    data: {sala_id: salaId},
                    dataType: 'json',
                    timeout: 10000
                })
                .done((response) => {
                    if (response && response.success) {
                        this.showAlert(response.message || 'Entrada realizada com sucesso!', 'success');
                        setTimeout(() => {
                            if (response.redirect_to) {
                                window.location.href = response.redirect_to;
                            } else {
                                location.reload();
                            }
                        }, 1500);
                    } else {
                        this.showAlert(response.message || 'Erro inesperado ao entrar na sala.', 'warning');
                    }
                })
                .fail((xhr, status, error) => {
                    let errorMsg = 'Erro ao entrar na sala. Tente novamente.';
                    try {
                        if (xhr.responseJSON) {
                            errorMsg = xhr.responseJSON.message || errorMsg;
                        } else if (xhr.responseText) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMsg = response.message;
                            }
                        }
                    } catch (e) {
                        console.warn('Não foi possível interpretar resposta de erro:', e);
                    }

                    if (xhr.status === 401) {
                        errorMsg = 'Sessão expirada. Faça login novamente.';
                        setTimeout(() => window.location.href = '/login', 2000);
                    } else if (xhr.status === 422) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMsg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                    } else if (xhr.status === 419) {
                        errorMsg = 'Token CSRF inválido. Recarregando página...';
                        setTimeout(() => location.reload(), 2000);
                    } else if (xhr.status === 0) {
                        errorMsg = 'Problema de conexão. Verifique sua internet.';
                    }
                    this.showAlert(errorMsg, 'danger');
                });
            }

            showAlert(message, type = 'info') {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" style="animation:slideDown 0.4s ease">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('#alertContainer').prepend(alertHtml);
                setTimeout(() => $('.alert').first().alert('close'), 5000);
            }

            showLoading(inline = false) {
    if (inline) {
        // Loading inline para "Ver Mais"
        const inlineLoading = document.createElement('div');
        inlineLoading.className = 'loading-inline active';
        inlineLoading.id = 'loadingInline';
        inlineLoading.innerHTML = `
            <div class="loading-dots">
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
            </div>
            <span class="loading-inline-text">Carregando mais salas...</span>
        `;
        
        const activeSection = document.querySelector('.section-content[style*="display: block"]');
        if (activeSection) {
            const verMaisBtn = activeSection.querySelector('.btn-ver-mais');
            if (verMaisBtn) {
                verMaisBtn.parentElement.appendChild(inlineLoading);
            }
        }
    } else {
        // Loading de tela cheia
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.add('active');
        }
    }
}

            hideLoading(inline = false) {
    if (inline) {
        // Remover loading inline
        const inlineLoading = document.getElementById('loadingInline');
        if (inlineLoading) {
            inlineLoading.classList.remove('active');
            setTimeout(() => inlineLoading.remove(), 300);
        }
    } else {
        // Remover overlay
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.remove('active');
        }
    }
}
        }

        // Event listeners para botões de edição
        document.body.addEventListener('click', function (e) {
            const btn = e.target.closest('.open-banner-editor-btn');
            if (!btn) return;
            const salaId = btn.getAttribute('data-sala-id');
            const bannerUrl = btn.getAttribute('data-banner-url') || null;
            const bannerColor = btn.getAttribute('data-banner-color') || null;
            if (window.openBannerEditor) {
                window.openBannerEditor(salaId, bannerUrl, bannerColor);
            }
        });

        document.body.addEventListener('click', function (e) {
            const btn = e.target.closest('.open-profile-photo-editor-btn');
            if (!btn) return;
            const salaId = btn.getAttribute('data-sala-id');
            const fotoUrl = btn.getAttribute('data-foto-url') || null;
            if (window.openProfilePhotoEditor) {
                window.openProfilePhotoEditor(salaId, fotoUrl);
            }
        });

        let sistema;
        $(document).ready(() => {
    const overlay = document.getElementById('loadingOverlay');
    
    sistema = new SistemaSalas();
    
    // Esconder overlay após tudo carregar
    setTimeout(() => {
        if (overlay) {
            overlay.classList.add('hidden');
        }
    }, 1500);
});
    </script>

    <script src="{{ asset('js/moderation.js') }}" defer></script>
    <script>
        async function initModeration() {
            try {
                const state = await window.Moderation.init({
                    csrfToken: $('meta[name="csrf-token"]').attr('content'),
                    endpoint: '/moderate',
                    debounceMs: 120
                });

                function applyWarning(selector, res) {
  const el = document.querySelector(selector);
  const warnId = selector.replace('#', '') + '-warning';
  let warn = document.getElementById(warnId);
  
  if (!el) return;

  if (!warn) {
    warn = document.createElement('small');
    warn.id = warnId;
    warn.className = 'rpg-warning';
    warn.style.cssText = 'color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem; display: none;';
    warn.innerHTML = `
  <svg class="warning-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
    <line x1="12" y1="9" x2="12" y2="13"/>
    <line x1="12" y1="17" x2="12.01" y2="17"/>
  </svg>
  Conteúdo inapropriado detectado. Contate o suporte se acredita que isso é um erro.
`;
    el.parentNode.appendChild(warn);
  }

  if (res && res.inappropriate) {
    el.classList.add('input-warn');
    warn.classList.add('show');
    warn.style.display = 'block';
    
    console.warn(`⚠️ Campo ${selector} marcado como inapropriado:`, res.matches);
  } else {
    el.classList.remove('input-warn');
    warn.classList.remove('show');
    warn.style.display = 'none';
  }
}

                window.Moderation.attachInput('#nomeSala', 'nome', {
                    onLocal: (res) => {
                        applyWarning('#nomeSala', res);
                        if (res.inappropriate) {
                            console.warn('⚠️ Nome da sala com conteúdo inapropriado:', res.matches);
                        }
                    },
                    onServer: (srv) => {
                        if (srv && srv.data && srv.data.inappropriate) {
                            applyWarning('#nomeSala', { inappropriate: true });
                        }
                    }
                });

                window.Moderation.attachInput('#descricaoSala', 'descricao', {
                    onLocal: (res) => {
                        applyWarning('#descricaoSala', res);
                    },
                    onServer: (srv) => {
                        if (srv && srv.data && srv.data.inappropriate) {
                            applyWarning('#descricaoSala', { inappropriate: true });
                        }
                    }
                });

                window.Moderation.attachInput('#editNomeSala', 'nome', {
                    onLocal: (res) => applyWarning('#editNomeSala', res)
                });

                window.Moderation.attachInput('#editDescricaoSala', 'descricao', {
                    onLocal: (res) => applyWarning('#editDescricaoSala', res)
                });

                window.Moderation.attachFormSubmit('#formCriarSala', [
                    { selector: '#nomeSala', fieldName: 'nome' },
                    { selector: '#descricaoSala', fieldName: 'descricao' }
                ]);

                window.Moderation.attachFormSubmit('#formEditarSala', [
                    { selector: '#editNomeSala', fieldName: 'nome' },
                    { selector: '#editDescricaoSala', fieldName: 'descricao' }
                ]);

                document.getElementById('formCriarSala').addEventListener('moderation:blocked', (e) => {
                    console.error('🚫 Formulário bloqueado por conteúdo inapropriado:', e.detail);
                    sistema.showAlert('Conteúdo inapropriado detectado. Por favor, revise os campos marcados antes de criar a sala.', 'danger');
                });

                document.getElementById('formEditarSala').addEventListener('moderation:blocked', (e) => {
                    console.error('🚫 Formulário de edição bloqueado:', e.detail);
                    sistema.showAlert('Conteúdo inapropriado detectado. Por favor, revise os campos marcados.', 'danger');
                });

            } catch (error) {
                console.error('❌ Erro ao inicializar moderação:', error);
            }
        }

        $(document).ready(() => {
            setTimeout(() => {
                initModeration();
            }, 100);
        });

        // Form Editar Sala
$('#formEditarSala').off('submit').on('submit', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const salaId = $('#editSalaId').val();
    if (!salaId) {
        showModalFeedback('modalEditarSala', 'error', 'Erro', 'ID da sala não encontrado.');
        return;
    }
    
    const data = {
        nome: $('#editNomeSala').val().trim(),
        descricao: $('#editDescricaoSala').val().trim(),
        tipo: $('#editTipoSala').val(),
        max_participantes: parseInt($('#editMaxParticipantes').val()) || 50,
        ativa: $('#editAtiva').is(':checked') ? 1 : 0
    };
    
    if (!data.nome || data.nome.length < 3) {
        showModalFeedback('modalEditarSala', 'warning', 'Atenção', 'O nome da sala deve ter pelo menos 3 caracteres.');
        return;
    }
    
    const btnSalvar = $('#btnSalvarEdicao');
    const textoOriginal = btnSalvar.html();
    
    btnSalvar.addClass('loading').prop('disabled', true);
    btnSalvar.find('span').text('Salvando...');
    
    $.ajax({
        url: `/salas/${salaId}/staff/update`,
        type: 'PUT',
        data: data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    })
    .done(response => {
        if (response.success) {
            showModalFeedback('modalEditarSala', 'success', 'Sucesso!', response.message || 'Sala atualizada com sucesso!', true);
            setTimeout(() => {
                if (typeof sistema !== 'undefined') {
                    sistema.pageMinhas = 1;
                    sistema.pagePublicas = 1;
                    sistema.loadSalas();
                }
            }, 1500);
        } else {
            showModalFeedback('modalEditarSala', 'error', 'Erro', response.message || 'Erro ao salvar alterações.');
            btnSalvar.removeClass('loading').prop('disabled', false).html(textoOriginal);
        }
    })
    .fail(xhr => {
        let errorMsg = 'Erro ao salvar alterações.';
        if (xhr.status === 422 && xhr.responseJSON?.errors) {
            errorMsg = Object.values(xhr.responseJSON.errors).flat().join(' ');
        } else if (xhr.responseJSON?.message) {
            errorMsg = xhr.responseJSON.message;
        }
        showModalFeedback('modalEditarSala', 'error', 'Erro', errorMsg);
        btnSalvar.removeClass('loading').prop('disabled', false).html(textoOriginal);
    });
});

// Form Motivo Desativação
$('#formMotivoDesativacao').off('submit').on('submit', function(e) {
    e.preventDefault();
    
    const salaId = $('#motivoSalaId').val();
    const motivo = $('#motivoDesativacao').val().trim();
    const btnSubmit = $('#btnConfirmarDesativacao');
    const textoOriginal = btnSubmit.html();
    
    btnSubmit.addClass('loading').prop('disabled', true);
    btnSubmit.find('span').text('Desativando...');
    
    $.ajax({
        url: `/salas/${salaId}/staff/toggle-status`,
        type: 'POST',
        data: { motivo: motivo || null },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    })
    .done(response => {
        if (response.success) {
            showModalFeedback('modalMotivoDesativacao', 'success', 'Sala Desativada!', response.message, true);
            setTimeout(() => {
                if (typeof sistema !== 'undefined') {
                    sistema.recarregarTodasSalas();
                }
            }, 1500);
        }
    })
    .fail(xhr => {
        const msg = xhr.responseJSON?.message || 'Erro ao desativar sala.';
        showModalFeedback('modalMotivoDesativacao', 'error', 'Erro', msg);
        btnSubmit.removeClass('loading').prop('disabled', false).html(textoOriginal);
    });
});

console.log('✅ Handlers dos modais staff inicializados');
    </script>
    
    @include('partials.banner-editor')
@include('partials.profile-photo-editor')

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Forçar modais para serem filhos diretos do body
    const modals = [
        document.getElementById('modalBannerEditor'),
        document.getElementById('modalProfileEditor')
    ];
    
    modals.forEach(modal => {
        if (modal && modal.parentElement !== document.body) {
            console.log(`🔧 Movendo ${modal.id} para body`);
            document.body.appendChild(modal);
        }
    });
    
    console.log('✅ Modais reposicionados no DOM');
});
</script>
    <script src="https://unpkg.com/nsfwjs@2.4.2/dist/nsfwjs.min.js"></script>
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
                if (window.NSFWAlert) window.NSFWAlert.showLoading('profileNsfwAlert', 'Pré-carregando modelo NSFW...');
                window.NSFWDetector.loadModel()
                    .then(() => {
                        console.log('Modelo NSFW pré-carregado.');
                        if (window.NSFWAlert) window.NSFWAlert.clear('profileNsfwAlert');
                    })
                    .catch(err => {
                        console.warn('Falha ao pré-carregar modelo NSFW:', err);
                        if (window.NSFWAlert) window.NSFWAlert.showError('profileNsfwAlert', 'Falha ao pré-carregar modelo.');
                    });
            } catch (e) { 
                console.warn('Erro no preloader NSFW:', e); 
            }
        });
    </script>

    <script>
        // 🔧 FIX DEFINITIVO PARA EDIÇÃO DE SALAS (STAFF)
        // ========== FIX COMPLETO PARA EDIÇÃO DE SALAS (STAFF) ==========
$(document).ready(function() {
    console.log('🔧 Inicializando sistema de edição de salas (FIX V2)...');
    
    // ========== FUNÇÃO PARA ABRIR MODAL DE EDIÇÃO ==========
    window.editarSalaStaff = function(salaId) {
        console.log('📝 Abrindo editor para sala:', salaId);
        
        // Buscar dados da sala
        $.ajax({
            url: `/salas/${salaId}/staff/edit`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .done(function(response) {
            console.log('📦 Resposta recebida:', response);
            
            if (response.success && response.sala) {
                const sala = response.sala;
                
                console.log('✅ Dados da sala:', sala);
                console.log('   - ID:', sala.id);
                console.log('   - Nome:', sala.nome);
                console.log('   - Descrição:', sala.descricao);
                console.log('   - Tipo:', sala.tipo);
                console.log('   - Max:', sala.max_participantes);
                console.log('   - Ativa:', sala.ativa);
                
                // ========== PREENCHER OS CAMPOS COM DELAY ==========
                // Primeiro abrir o modal
                openModal('modalEditarSala');
                
                // Depois preencher com um pequeno delay para garantir que o DOM está pronto
                setTimeout(function() {
                    // Verificar se os elementos existem
                    const $editSalaId = $('#editSalaId');
                    const $editNomeSala = $('#editNomeSala');
                    const $editDescricaoSala = $('#editDescricaoSala');
                    const $editTipoSala = $('#editTipoSala');
                    const $editMaxParticipantes = $('#editMaxParticipantes');
                    const $editAtiva = $('#editAtiva');
                    
                    console.log('🔍 Verificando elementos:');
                    console.log('   - #editSalaId existe:', $editSalaId.length > 0);
                    console.log('   - #editNomeSala existe:', $editNomeSala.length > 0);
                    console.log('   - #editDescricaoSala existe:', $editDescricaoSala.length > 0);
                    console.log('   - #editTipoSala existe:', $editTipoSala.length > 0);
                    console.log('   - #editMaxParticipantes existe:', $editMaxParticipantes.length > 0);
                    console.log('   - #editAtiva existe:', $editAtiva.length > 0);
                    
                    // Preencher os valores
                    $editSalaId.val(sala.id);
                    $editNomeSala.val(sala.nome || '');
                    $editDescricaoSala.val(sala.descricao || '');
                    $editTipoSala.val(sala.tipo || 'publica');
                    $editMaxParticipantes.val(sala.max_participantes || 50);
                    
                    // Checkbox - usar prop para boolean
                    $editAtiva.prop('checked', sala.ativa === true || sala.ativa === 1);
                    
                    // Verificar se os valores foram preenchidos
                    console.log('✅ Valores preenchidos:');
                    console.log('   - ID:', $editSalaId.val());
                    console.log('   - Nome:', $editNomeSala.val());
                    console.log('   - Descrição:', $editDescricaoSala.val());
                    console.log('   - Tipo:', $editTipoSala.val());
                    console.log('   - Max:', $editMaxParticipantes.val());
                    console.log('   - Ativa:', $editAtiva.prop('checked'));
                    
                    // Limpar warnings anteriores
                    $('#editNomeSala, #editDescricaoSala').removeClass('input-warn');
                    $('#editNomeSala-warning, #editDescricaoSala-warning')
                        .removeClass('show')
                        .css('display', 'none');
                    
                    // Forçar trigger de change nos selects para atualizar visual
                    $editTipoSala.trigger('change');
                    
                }, 100); // 100ms de delay
                
            } else {
                console.error('❌ Resposta sem sucesso ou sem dados:', response);
                alert(response.message || 'Erro ao carregar dados da sala.');
            }
        })
        .fail(function(xhr, status, error) {
            console.error('❌ Erro na requisição:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseJSON: xhr.responseJSON,
                error: error
            });
            
            let errorMsg = 'Erro ao carregar dados da sala.';
            
            if (xhr.status === 403) {
                errorMsg = 'Você não tem permissão para editar esta sala.';
            } else if (xhr.status === 404) {
                errorMsg = 'Sala não encontrada.';
            } else if (xhr.responseJSON?.message) {
                errorMsg = xhr.responseJSON.message;
            }
            
            alert(errorMsg);
        });
    };
    
    // ========== MÉTODO ALTERNATIVO USANDO VANILLA JS ==========
    // Caso o jQuery não esteja funcionando corretamente
    window.editarSalaStaffVanilla = function(salaId) {
        console.log('📝 [VANILLA] Abrindo editor para sala:', salaId);
        
        fetch(`/salas/${salaId}/staff/edit`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.sala) {
                const sala = data.sala;
                
                // Abrir modal
                openModal('modalEditarSala');
                
                // Preencher com delay
                setTimeout(() => {
                    document.getElementById('editSalaId').value = sala.id;
                    document.getElementById('editNomeSala').value = sala.nome || '';
                    document.getElementById('editDescricaoSala').value = sala.descricao || '';
                    document.getElementById('editTipoSala').value = sala.tipo || 'publica';
                    document.getElementById('editMaxParticipantes').value = sala.max_participantes || 50;
                    document.getElementById('editAtiva').checked = Boolean(sala.ativa);
                    
                    console.log('✅ [VANILLA] Campos preenchidos');
                }, 100);
            } else {
                alert(data.message || 'Erro ao carregar sala.');
            }
        })
        .catch(error => {
            console.error('❌ [VANILLA] Erro:', error);
            alert('Erro ao carregar dados da sala.');
        });
    };
    
    console.log('✅ Sistema de edição de salas inicializado (FIX V2)');
});
    
    // ========== TOGGLE STATUS SALA (DESATIVAR/ATIVAR) ==========
    window.toggleStatusSala = function(salaId, isAtiva, salaNome) {
        if (isAtiva) {
            // Se está ativa, abrir modal de motivo de desativação
            $('#motivoSalaId').val(salaId);
            $('#motivoSalaNome').text(salaNome);
            $('#motivoDesativacao').val('');
            openModal('modalMotivoDesativacao');
        } else {
            // Se está desativada, ativar diretamente
            if (!confirm(`Deseja reativar a sala "${salaNome}"?`)) return;
            
            $.ajax({
                url: `/salas/${salaId}/staff/toggle-status`,
                type: 'POST',
                data: { motivo: null },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            })
            .done(function(response) {
                if (response.success) {
                    if (typeof sistema !== 'undefined') {
                        sistema.showAlert(response.message, 'success');
                        sistema.recarregarTodasSalas();
                    } else {
                        alert(response.message);
                        location.reload();
                    }
                }
            })
            .fail(function(xhr) {
                const msg = xhr.responseJSON?.message || 'Erro ao ativar sala.';
                if (typeof sistema !== 'undefined') {
                    sistema.showAlert(msg, 'danger');
                } else {
                    alert(msg);
                }
            });
        }
    };
    
    // ========== FORM MOTIVO DE DESATIVAÇÃO ==========
    $('#formMotivoDesativacao').off('submit').on('submit', function(e) {
        e.preventDefault();
        
        const salaId = $('#motivoSalaId').val();
        const motivo = $('#motivoDesativacao').val().trim();
        
        const btnSubmit = $('#btnConfirmarDesativacao');
        const originalHtml = btnSubmit.html();
        
        btnSubmit.addClass('loading').prop('disabled', true);
        btnSubmit.find('span').text('Desativando...');
        
        $.ajax({
            url: `/salas/${salaId}/staff/toggle-status`,
            type: 'POST',
            data: { motivo: motivo || null },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .done(function(response) {
            if (response.success) {
                showModalFeedback('modalMotivoDesativacao', 'success', 'Sala Desativada!', response.message, false);
                
                setTimeout(function() {
                    if (typeof sistema !== 'undefined') {
                        sistema.recarregarTodasSalas();
                    }
                    
                    setTimeout(function() {
                        closeModal('modalMotivoDesativacao');
                    }, 1000);
                }, 1500);
            }
        })
        .fail(function(xhr) {
            const msg = xhr.responseJSON?.message || 'Erro ao desativar sala.';
            showModalFeedback('modalMotivoDesativacao', 'error', 'Erro', msg);
            btnSubmit.removeClass('loading').prop('disabled', false).html(originalHtml);
        });
    });
    
    // ========== DEBUG HELPER ==========
    window.debugFormEdit = function() {
        console.log('🔍 Debug do Formulário de Edição:');
        console.log('ID da Sala:', $('#editSalaId').val());
        console.log('Nome:', $('#editNomeSala').val());
        console.log('Descrição:', $('#editDescricaoSala').val());
        console.log('Tipo:', $('#editTipoSala').val());
        console.log('Max Participantes:', $('#editMaxParticipantes').val());
        console.log('Ativa:', $('#editAtiva').is(':checked'));
    };
    
    console.log('✅ Sistema de edição de salas inicializado');

    $('#motivoDesativacao').on('input', function() {
    const value = $(this).val();
    const length = value.length;
    const counter = $('#motivoCounter');
    const btnSubmit = $('#btnConfirmarDesativacao');
    
    // Atualizar contador
    counter.text(`${length}/20`);
    
    // Mudar cor baseado no status
    if (length === 0) {
        counter.css('color', 'var(--muted)');
    } else if (length < 20) {
        counter.css('color', '#ef4444');
    } else {
        counter.css('color', 'var(--accent)');
    }
    
    // Desabilitar botão se não atingir mínimo
    if (length < 20) {
        btnSubmit.prop('disabled', true).css('opacity', '0.5');
    } else {
        btnSubmit.prop('disabled', false).css('opacity', '1');
    }
});

// Validação adicional no submit
$('#formMotivoDesativacao').off('submit').on('submit', function(e) {
    e.preventDefault();
    
    const salaId = $('#motivoSalaId').val();
    const motivo = $('#motivoDesativacao').val().trim();
    
    // Validação: motivo é obrigatório e deve ter pelo menos 20 caracteres
    if (!motivo || motivo.length < 20) {
        showModalFeedback('modalMotivoDesativacao', 'warning', 
            'Motivo Obrigatório', 
            'Por favor, descreva o motivo da desativação com pelo menos 20 caracteres.',
            false
        );
        return;
    }
    
    const btnSubmit = $('#btnConfirmarDesativacao');
    const originalHtml = btnSubmit.html();
    
    btnSubmit.addClass('loading').prop('disabled', true);
    btnSubmit.find('span').text('Desativando...');
    
    $.ajax({
        url: `/salas/${salaId}/staff/toggle-status`,
        type: 'POST',
        data: { motivo: motivo },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    })
    .done(function(response) {
        if (response.success) {
            showModalFeedback('modalMotivoDesativacao', 'success', 'Sala Desativada!', response.message, false);
            
            setTimeout(function() {
                if (typeof sistema !== 'undefined') {
                    sistema.recarregarTodasSalas();
                }
                
                setTimeout(function() {
                    closeModal('modalMotivoDesativacao');
                    // Resetar o formulário
                    $('#motivoDesativacao').val('');
                    $('#motivoCounter').text('0/20').css('color', 'var(--muted)');
                }, 1000);
            }, 1500);
        }
    })
    .fail(function(xhr) {
        const msg = xhr.responseJSON?.message || 'Erro ao desativar sala.';
        showModalFeedback('modalMotivoDesativacao', 'error', 'Erro', msg);
        btnSubmit.removeClass('loading').prop('disabled', false).html(originalHtml);
    });
});

// Resetar contador ao abrir o modal
$(document).on('click', '[onclick*="toggleStatusSala"]', function() {
    setTimeout(function() {
        $('#motivoCounter').text('0/20').css('color', 'var(--muted)');
        $('#btnConfirmarDesativacao').prop('disabled', true).css('opacity', '0.5');
    }, 100);
});
    </script>

    <style>
        /* Estilos adicionais para inputs de moderação */
        .input-warn {
            border: 2px solid #e0556b !important;
            background: #fff6f7;
        }

        .moderation-warning {
            display: none;
            color: #e0556b;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .moderation-warning.show {
            display: block;
        }

        /* Bootstrap Modal Fixes */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: none;
        }

        /* Form Switches */
        .form-check-input {
            width: 3em;
            height: 1.5em;
            background-color: var(--bg-elevated);
            border: 2px solid var(--border-subtle);
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 4px var(--accent-glow);
            border-color: var(--accent);
        }

        .form-check-label {
            color: var(--text-primary);
            cursor: pointer;
            user-select: none;
        }

        /* Secondary Button Style */
        .btn-secondary {
            background: var(--bg-elevated);
            color: var(--text-secondary);
            border: 2px solid var(--border-subtle);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-secondary:hover {
            background: var(--bg-card);
            color: var(--text-primary);
            border-color: var(--border-hover);
        }

        /* Alert Close Button */
        .alert .btn-close {
            background: transparent;
            opacity: 0.6;
            filter: invert(1);
        }

        .alert .btn-close:hover {
            opacity: 1;
        }

        /* Dropdown Arrow Fix */
        .form-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%238b9ba8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 20px;
            padding-right: 3rem;
            cursor: pointer;
        }

        .form-select:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2322c55e'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        }

        /* Row Helper */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.75rem;
        }

        .row > * {
            padding: 0 0.75rem;
        }

        .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .col-md-8 {
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }

        .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .col-md-4, .col-md-6, .col-md-8 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Participant Avatar Animation */
        .participant-avatar {
            animation: avatarAppear 0.4s ease backwards;
        }

        @keyframes avatarAppear {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Smooth Scrollbar */
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
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(34, 197, 94, 0.5);
        }

        /* Section Content Base */
        .section-content {
            animation: sectionFadeIn 0.4s ease;
        }

        @keyframes sectionFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    width: 100%;
}

/* Logo na navbar */
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

/* Nav links */
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

/* ========== FIX NAVBAR LAYOUT ========== */
.nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 0;
    height: 70px;
    width: 100%;
}

.nav-links a:hover {
    color: var(--accent);
}

.nav-links a.active {
    color: var(--accent);
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

/* ========== ESTILOS ADICIONAIS PARA MODAIS STAFF ========== */

/* Toggle Switch Customizado */
.rpg-toggle-wrapper {
  padding: 1rem;
  background: rgba(17, 24, 39, 0.6);
  border-radius: 12px;
  border: 1px solid rgba(55, 65, 81, 0.8);
}

.rpg-toggle {
  display: flex;
  align-items: center;
  gap: 1rem;
  cursor: pointer;
}

.rpg-toggle input[type="checkbox"] {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

/* Ícones SVG para tipos de sala */
.tipo-icon {
    width: 14px;
    height: 14px;
    display: inline-block;
    vertical-align: middle;
    margin-right: 6px;
}

.tipo-icon svg {
    width: 100%;
    height: 100%;
    stroke: currentColor;
    fill: none;
    stroke-width: 2.5;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.rpg-toggle-slider {
  position: relative;
  width: 52px;
  height: 28px;
  background: rgba(55, 65, 81, 0.8);
  border-radius: 34px;
  transition: all 0.3s;
  border: 2px solid rgba(107, 114, 128, 0.5);
  flex-shrink: 0;
}

.rpg-toggle-slider::before {
  content: '';
  position: absolute;
  height: 20px;
  width: 20px;
  left: 4px;
  top: 2px;
  background: #d1d5db;
  border-radius: 50%;
  transition: all 0.3s;
}

.rpg-toggle input:checked ~ .rpg-toggle-slider {
  background: linear-gradient(135deg, #22c55e, #16a34a);
  border-color: var(--accent);
  box-shadow: 0 0 10px rgba(34, 197, 94, 0.3);
}

.rpg-toggle input:checked ~ .rpg-toggle-slider::before {
  transform: translateX(24px);
  background: #fff;
}

.rpg-toggle-label {
  font-size: 0.95rem;
  font-weight: 600;
  color: #d1d5db;
  transition: color 0.3s;
}

.rpg-toggle input:checked ~ .rpg-toggle-label {
  color: var(--accent);
}

/* Sala Info Box */
.rpg-sala-info-box {
  padding: 1rem 1.25rem;
  background: rgba(17, 24, 39, 0.8);
  border: 2px solid rgba(239, 68, 68, 0.3);
  border-radius: 12px;
  color: #f9fafb;
  font-size: 0.95rem;
}

.rpg-sala-info-box strong {
  color: #ef4444;
  font-size: 1.05rem;
}

/* Botão Danger (Vermelho) */
.rpg-btn-danger {
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: #fff;
  box-shadow: 0 4px 14px rgba(239, 68, 68, 0.4);
}

.rpg-btn-danger:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(239, 68, 68, 0.5);
}

.rpg-btn-danger svg {
  stroke: #fff;
}

/* Animações de Feedback com Cores Específicas */
.rpg-feedback-icon.error {
  background: rgba(239, 68, 68, 0.2);
  border-color: #ef4444;
  box-shadow: 0 0 30px rgba(239, 68, 68, 0.4);
}

.rpg-feedback-icon.error svg {
  stroke: #ef4444;
}

/* Ajuste para centralização perfeita */
.rpg-modal {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.rpg-modal-compact {
  max-width: 600px;
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
  color: var(--muted);
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
  color: var(--muted);
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

/* Scrollbar personalizada */
.notification-list::-webkit-scrollbar {
  width: 6px;
}

.notification-list::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 3px;
}

.notification-list::-webkit-scrollbar-thumb {
  background: rgba(34, 197, 94, 0.3);
  border-radius: 3px;
}

.notification-list::-webkit-scrollbar-thumb:hover {
  background: rgba(34, 197, 94, 0.5);
}

@media (max-width: 768px) {
    .nav-links {
        display: none;
    }
    
    .notification-modal {
        right: 16px;
        left: 16px;
        width: auto;
    }
    
    .footer-columns {
        grid-template-columns: 1fr;
    }
    
    .user-menu {
        gap: 8px;
    }
}

@media (max-width: 480px) {
    .logo-img {
        height: 40px;
    }
    
    .notification-btn,
    .user-avatar,
    .user-avatar-default {
        width: 36px;
        height: 36px;
    }
    
    .user-dropdown {
        width: 260px;
    }
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

/* User Menu */
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
    stroke-linecap: round;
    stroke-linejoin: round;
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
    background: linear-gradient(135deg, #064e3b, #052e16);
}

.user-avatar:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.rpg-info-box + .rpg-form-group {
  margin-top: 1.5rem; /* Espaçamento adicional após o info-box */
}

#modalMotivoDesativacao .rpg-info-box {
  margin-bottom: 2rem; /* Em vez de apenas margin-top: 1.5rem */
}

/* Garantir margem consistente no label */
#modalMotivoDesativacao .rpg-label {
  margin-top: 0.5rem; /* Pequena margem extra no topo do label */
}

.user-avatar-default {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: linear-gradient(135deg, #064e3b, #052e16);
    border: 2px solid rgba(34, 197, 94, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 700;
    color: var(--accent);
    transition: all 0.25s;
}

.user-avatar-default:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

/* User Dropdown */
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
    margin-bottom: 4px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.user-dropdown-details p {
    font-size: 13px;
    color: var(--muted);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
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

/* Modal de Notificações */
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

.badge-staff-access {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    z-index: 5;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.875rem;
    
    /* Mesmo estilo do dropdown */
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.12), rgba(220, 38, 38, 0.08));
    backdrop-filter: blur(10px);
    border: 1px solid rgba(239, 68, 68, 0.25);
    border-radius: 10px;
    
    /* Tipografia minimalista */
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #ef4444;
    
    /* Transição suave */
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Shadow sutil */
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15);
}

.badge-staff-access:hover {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.18), rgba(220, 38, 38, 0.12));
    border-color: rgba(239, 68, 68, 0.35);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
}

.badge-staff-access i {
    font-size: 0.75rem;
    opacity: 0.9;
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
    color: var(--muted);
}

.notification-empty svg {
    width: 48px;
    height: 48px;
    margin: 0 auto 12px;
    stroke: var(--muted);
    opacity: 0.4;
}

.notification-empty p {
    font-size: 14px;
    line-height: 1.6;
}

/* Footer */
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

.footer-brand .logo-img {
    height: 40px;
    width: auto;
}

.footer h4 {
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 14px;
    color: var(--accent);
}

/* ========== MODAIS RPG (NOVO SISTEMA) ========== */
.rpg-modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 10000;
  align-items: center;
  justify-content: center;
}

.rpg-modal.show {
  display: flex !important;
  align-items: center;
  justify-content: center;
}

.rpg-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.85);
  backdrop-filter: blur(8px);
  cursor: pointer;
  z-index: 1;
}

.rpg-modal-container {
  position: relative;
  width: 95%;
  max-width: 700px;
  max-height: 90vh; /* Isso já está OK */
  background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.95));
  border-radius: 24px;
  border: 1px solid rgba(34, 197, 94, 0.2);
  box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6), 0 0 1px rgba(34, 197, 94, 0.3);
  overflow: hidden;
  animation: modalSlideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
  display: flex;
  flex-direction: column;
  z-index: 2;
  margin: auto;
}

.btn-enter,
a.btn-enter {
    text-decoration: none !important;
}

.btn-enter *,
a.btn-enter * {
    text-decoration: none !important;
}

/* Fix específico para Font Awesome */
.btn-enter .fas,
.btn-enter .fa {
    text-decoration: none !important;
    border-bottom: 0 !important;
    text-underline-offset: 0 !important;
}

/* Remove qualquer underline herdado */
.sala-card-footer a {
    text-decoration: none !important;
}

.sala-card-footer a:hover {
    text-decoration: none !important;
}

.rpg-modal-compact {
  max-width: 550px;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes overlayFadeIn {
  from { 
    opacity: 0;
    backdrop-filter: blur(0);
  }
  to { 
    opacity: 1;
    backdrop-filter: blur(8px);
  }
}

@keyframes modalSlideUp {
  from {
    opacity: 0;
    transform: translateY(50px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Header do Modal */
.rpg-modal-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 2rem 2rem 1.5rem;
  border-bottom: 1px solid rgba(34, 197, 94, 0.15);
  background: linear-gradient(135deg, rgba(5, 46, 22, 0.3), transparent);
  position: relative;
  overflow: hidden;
  flex-shrink: 0;
}

.rpg-modal-header::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at top left, rgba(34, 197, 94, 0.1), transparent 60%);
  animation: glow 3s ease-in-out infinite;
}

@keyframes glow {
  0%, 100% { opacity: 0.5; }
  50% { opacity: 1; }
}

.rpg-modal-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.1));
  border: 1px solid rgba(34, 197, 94, 0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  position: relative;
  z-index: 2;
}

.rpg-modal-icon-blue {
  background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.1));
  border-color: rgba(59, 130, 246, 0.3);
}

.rpg-modal-icon svg {
  width: 24px;
  height: 24px;
  stroke: var(--accent);
}

.rpg-modal-icon-blue svg {
  stroke: #3b82f6;
}

.rpg-modal-title-wrapper {
  flex: 1;
  position: relative;
  z-index: 2;
}

.rpg-modal-title {
  font-size: 1.5rem;
  font-weight: 800;
  color: #fff;
  margin: 0 0 0.25rem;
  letter-spacing: 0.5px;
}

.rpg-modal-subtitle {
  font-size: 0.875rem;
  color: var(--muted);
  margin: 0;
}

.rpg-modal-close {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  flex-shrink: 0;
  position: relative;
  z-index: 2;
}

.rpg-modal-close:hover {
  background: rgba(239, 68, 68, 0.2);
  border-color: #ef4444;
  transform: rotate(90deg);
}

.rpg-modal-close svg {
  width: 18px;
  height: 18px;
  stroke: var(--muted);
  transition: stroke 0.2s;
}

.rpg-modal-close:hover svg {
  stroke: #ef4444;
}

/* Body do Modal */
.rpg-modal-body {
  padding: 2rem;
  overflow-y: auto;
  flex: 1 1 auto; /* Permite crescer e encolher */
  min-height: 0; /* ESSENCIAL para o overflow funcionar em flex containers */
}

.rpg-modal-body::-webkit-scrollbar {
  width: 8px;
}

.rpg-modal-body::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 4px;
}

.rpg-modal-body::-webkit-scrollbar-thumb {
  background: rgba(34, 197, 94, 0.3);
  border-radius: 4px;
}

.rpg-modal-body::-webkit-scrollbar-thumb:hover {
  background: rgba(34, 197, 94, 0.5);
}

/* Form Elements */
.rpg-form-group {
  margin-bottom: 1.5rem;
}

.rpg-form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

.rpg-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  font-weight: 700;
  color: #d1d5db;
  margin-bottom: 0.75rem;
  transition: color 0.3s;
}

.rpg-label-icon {
  width: 18px;
  height: 18px;
  stroke: var(--accent);
  flex-shrink: 0;
}

.rpg-required {
  color: #ef4444;
  font-size: 1rem;
  margin-left: auto;
}

.rpg-optional {
  color: var(--muted);
  font-size: 0.75rem;
  font-weight: 500;
  margin-left: 0.25rem;
}

.rpg-input-wrapper,
.rpg-textarea-wrapper {
  position: relative;
}

.rpg-input,
.rpg-textarea {
  width: 100%;
  padding: 0.875rem 1rem;
  background: rgba(17, 24, 39, 0.8);
  border: 2px solid rgba(55, 65, 81, 0.8);
  border-radius: 12px;
  color: #f9fafb;
  font-size: 0.95rem;
  font-family: Inter, sans-serif;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  z-index: 1;
}

.rpg-input-large {
  font-size: 1.25rem;
  font-weight: 700;
  text-align: center;
  padding: 1.125rem 1.25rem;
}

.rpg-input::placeholder,
.rpg-textarea::placeholder {
  color: #6b7280;
}

.rpg-input:focus,
.rpg-textarea:focus {
  outline: none;
  border-color: var(--accent);
  background: rgba(17, 24, 39, 0.95);
  box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
  transform: translateY(-2px);
}

.rpg-textarea {
  min-height: 120px;
  resize: vertical;
  line-height: 1.6;
}

.rpg-input-focus-border,
.rpg-textarea-focus-border {
  position: absolute;
  inset: 0;
  border-radius: 12px;
  border: 2px solid var(--accent);
  opacity: 0;
  transition: opacity 0.3s;
  pointer-events: none;
  z-index: 0;
}

.rpg-input:focus ~ .rpg-input-focus-border,
.rpg-textarea:focus ~ .rpg-textarea-focus-border {
  opacity: 0.3;
}

/* Select Customizado */
.rpg-select-wrapper {
  position: relative;
}

.rpg-select {
  width: 100%;
  padding: 0.875rem 3rem 0.875rem 1rem;
  background: rgba(17, 24, 39, 0.8);
  border: 2px solid rgba(55, 65, 81, 0.8);
  border-radius: 12px;
  color: #f9fafb;
  font-size: 0.95rem;
  font-family: Inter, sans-serif;
  cursor: pointer;
  appearance: none;
  transition: all 0.3s;
}

.rpg-select:focus {
  outline: none;
  border-color: var(--accent);
  background: rgba(17, 24, 39, 0.95);
  box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
  transform: translateY(-2px);
}

.rpg-select option {
  background: #1f2937;
  color: #f9fafb;
}

.rpg-select-arrow {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
  transition: transform 0.3s;
}

.rpg-select:focus ~ .rpg-select-arrow {
  transform: translateY(-50%) rotate(180deg);
}

.rpg-select-arrow svg {
  width: 20px;
  height: 20px;
  stroke: var(--muted);
}

.rpg-select:focus ~ .rpg-select-arrow svg {
  stroke: var(--accent);
}

/* Ícone de warning SVG */
.warning-icon {
  width: 16px;
  height: 16px;
  stroke: #ef4444;
  flex-shrink: 0;
  margin-right: 6px;
  display: inline-block;
  vertical-align: middle;
  animation: warningPulse 2s ease-in-out infinite;
}

@keyframes warningPulse {
  0%, 100% {
    opacity: 1;
    transform: scale(1);
  }
  50% {
    opacity: 0.8;
    transform: scale(1.05);
  }
}

.rpg-warning {
  display: none;
  margin-top: 0.5rem;
  font-size: 0.875rem;
  color: #ef4444;
  font-weight: 600;
  align-items: center;
  gap: 6px;
}

.rpg-warning.show {
  display: flex !important; /* Mudado de 'block' para 'flex' para alinhar o SVG */
  animation: warningShake 0.4s ease;
}

.rpg-hint {
  display: block;
  margin-top: 0.5rem;
  font-size: 0.75rem;
  color: var(--muted);
}

.hint-icon {
  width: 16px;
  height: 16px;
  stroke: var(--accent);
  flex-shrink: 0;
}

.rpg-password-hint {
  display: flex;
  align-items: center;
  gap:0.5rem;
  margin-top: 0.75rem;
  padding: 0.75rem 1rem;
  background: rgba(251, 191, 36, 0.1);
  border: 1px solid rgba(251, 191, 36, 0.3);
  border-radius: 10px;
  font-size: 0.875rem;
  color: #fbbf24;
}

.rpg-password-hint svg {
  width: 18px;
  height: 18px;
  stroke: #fbbf24;
  flex-shrink: 0;
}

/* Info Box */
.rpg-info-box {
  display: flex;
  gap: 1rem;
  padding: 1rem 1.25rem;
  background: rgba(59, 130, 246, 0.08);
  border: 1px solid rgba(59, 130, 246, 0.2);
  border-radius: 12px;
  margin-top: 1.5rem;
}

.rpg-info-icon {
  width: 22px;
  height: 22px;
  stroke: #3b82f6;
  flex-shrink: 0;
  margin-top: 2px;
}

.rpg-info-content {
  flex: 1;
  font-size: 0.875rem;
  color: #d1d5db;
  line-height: 1.6;
}

.rpg-info-content strong {
  color: #3b82f6;
  font-weight: 700;
}

/* Preview da Sala */
.rpg-sala-preview {
  margin-top: 1.5rem;
  border-radius: 16px;
  border: 1px solid rgba(34, 197, 94, 0.2);
  overflow: hidden;
  animation: slideDown 0.4s ease;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.rpg-preview-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 1.25rem;
  background: rgba(34, 197, 94, 0.1);
  border-bottom: 1px solid rgba(34, 197, 94, 0.2);
  font-size: 0.875rem;
  font-weight: 700;
  color: var(--accent);
}

.rpg-preview-header svg {
  width: 18px;
  height: 18px;
  stroke: var(--accent);
}

.rpg-preview-body {
  padding: 1.25rem;
  background: rgba(17, 24, 39, 0.6);
}

.rpg-preview-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 0;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.rpg-preview-item:last-child {
  border-bottom: none;
}

.rpg-preview-label {
  font-size: 0.875rem;
  color: var(--muted);
  font-weight: 500;
}

.rpg-preview-value {
  font-size: 0.95rem;
  color: #f9fafb;
  font-weight: 700;
}

#motivoCounter {
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
    background: rgba(17, 24, 39, 0.6);
    border-radius: 6px;
    border: 1px solid rgba(55, 65, 81, 0.5);
    transition: all 0.3s;
}

#motivoCounter.valid {
    color: var(--accent) !important;
    border-color: var(--accent);
    background: rgba(34, 197, 94, 0.1);
}

#motivoCounter.invalid {
    color: #ef4444 !important;
    border-color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
}

.rpg-preview-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.375rem 0.875rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.rpg-preview-badge.publica {
  background: rgba(34, 197, 94, 0.2);
  color: var(--accent);
  border: 1px solid var(--accent);
}

.rpg-preview-badge.privada {
  background: rgba(239, 68, 68, 0.2);
  color: #ef4444;
  border: 1px solid #ef4444;
}

.rpg-preview-badge.apenas_convite {
  background: rgba(251, 191, 36, 0.2);
  color: #fbbf24;
  border: 1px solid #fbbf24;
}

/* Footer do Modal */
.rpg-modal-footer {
  display: flex;
  gap: 1rem;
  padding: 1.5rem 2rem;
  border-top: 1px solid rgba(34, 197, 94, 0.15);
  background: rgba(17, 24, 39, 0.6);
  flex-shrink: 0;
}

.rpg-modal-form {
  display: flex;
  flex-direction: column;
  flex: 1 1 auto;
  min-height: 0; /* ESSENCIAL */
  overflow: hidden;
}

/* Botões */
.rpg-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.875rem 1.5rem;
  border-radius: 12px;
  font-size: 0.95rem;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  font-family: Inter, sans-serif;
}

.rpg-btn svg {
  width: 18px;
  height: 18px;
  flex-shrink: 0;
  transition: transform 0.3s;
}

.rpg-btn:hover svg {
  transform: scale(1.1);
}

.rpg-btn-secondary {
  background: rgba(55, 65, 81, 0.6);
  color: #d1d5db;
  border: 1px solid rgba(55, 65, 81, 0.8);
}

.rpg-btn-secondary:hover {
  background: rgba(55, 65, 81, 0.8);
  border-color: rgba(107, 114, 128, 0.8);
  transform: translateY(-2px);
}

.rpg-btn-primary {
  background: linear-gradient(135deg, #22c55e, #16a34a);
  color: #052e16;
  box-shadow: 0 4px 14px rgba(34, 197, 94, 0.4);
  flex: 1;
}

.rpg-btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(34, 197, 94, 0.5);
}

.rpg-btn-info {
  background: rgba(59, 130, 246, 0.15);
  color: #3b82f6;
  border: 1px solid rgba(59, 130, 246, 0.3);
  flex: 1;
}

.rpg-btn-info:hover {
  background: rgba(59, 130, 246, 0.25);
  border-color: rgba(59, 130, 246, 0.5);
  transform: translateY(-2px);
}

.rpg-btn-shine {
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
  transition: left 0.5s;
}

.rpg-btn-primary:hover .rpg-btn-shine {
  left: 100%;
}

.rpg-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none !important;
}

#secaoMinhasSalasDesativadas {
    margin-top: 3rem; /* Espaço superior */
    padding-top: 2rem; /* Padding interno */
    border-top: 1px solid rgba(34, 197, 94, 0.1); /* Linha sutil separadora */
}

/* Feedback de Sucesso/Erro */
.rpg-modal-feedback {
  position: absolute;
  inset: 0;
  display: none;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.98));
  z-index: 100;
  padding: 2rem;
  text-align: center;
  animation: feedbackFadeIn 0.4s ease;
}

.rpg-modal-feedback.show {
  display: flex;
}

@keyframes feedbackFadeIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.rpg-feedback-icon {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.5rem;
  animation: iconBounce 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes iconBounce {
  0% {
    transform: scale(0);
    opacity: 0;
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.rpg-feedback-icon.success {
  background: rgba(34, 197, 94, 0.2);
  border: 3px solid var(--accent);
  box-shadow: 0 0 30px rgba(34, 197, 94, 0.4);
}

.rpg-feedback-icon.error {
  background: rgba(239, 68, 68, 0.2);
  border: 3px solid #ef4444;
  box-shadow: 0 0 30px rgba(239, 68, 68, 0.4);
}

.rpg-feedback-icon.warning {
  background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.15));
  border: 3px solid #f59e0b;
  box-shadow: 0 0 30px rgba(245, 158, 11, 0.4), 0 0 60px rgba(245, 158, 11, 0.2);
}

.rpg-feedback-icon.warning svg {
  stroke: #f59e0b;
  fill: none;
}

.rpg-feedback-icon.warning svg circle {
  fill: #f59e0b;
}

.rpg-feedback-icon svg {
  width: 40px;
  height: 40px;
  stroke-width: 3;
}

.rpg-feedback-icon.success svg {
  stroke: var(--accent);
}

.rpg-feedback-icon.error svg {
  stroke: #ef4444;
}

.rpg-modal-feedback h4 {
  font-size: 1.75rem;
  font-weight: 800;
  color: #fff;
  margin-bottom: 0.75rem;
  animation: textSlideUp 0.5s ease 0.2s backwards;
}

.rpg-modal-feedback p {
  font-size: 1rem;
  color: var(--muted);
  max-width: 400px;
  line-height: 1.6;
  animation: textSlideUp 0.5s ease 0.3s backwards;
}

@keyframes textSlideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Loading State - Sem spinner ::after */
.rpg-btn.loading {
  pointer-events: none;
  opacity: 0.7;
  position: relative;
}

.rpg-btn.loading span {
  opacity: 0.6;
}

/* Animação sutil do botão durante loading */
.rpg-btn.loading {
  animation: pulseButton 1.5s ease-in-out infinite;
}

@keyframes pulseButton {
  0%, 100% {
    opacity: 0.7;
  }
  50% {
    opacity: 0.85;
  }
}

/* Responsive */
@media (max-width: 768px) {
  .rpg-modal-container {
    width: 100%;
    max-width: 100%;
    height: 100%;
    max-height: 100%;
    border-radius: 0;
    animation: modalSlideUpMobile 0.4s ease;
  }

  @keyframes modalSlideUpMobile {
    from {
      transform: translateY(100%);
    }
    to {
      transform: translateY(0);
    }
  }

  .rpg-modal-header {
    padding: 1.5rem 1.5rem 1rem;
  }

  .rpg-modal-body {
    padding: 1.5rem;
  }

  .rpg-modal-footer {
    padding: 1rem 1.5rem;
    flex-direction: column;
  }

  .rpg-btn {
    width: 100%;
    justify-content: center;
  }

  .rpg-form-row {
    grid-template-columns: 1fr;
  }

  .rpg-modal-title {
    font-size: 1.25rem;
  }

  .rpg-modal-icon {
    width: 40px;
    height: 40px;
  }

  .rpg-modal-icon svg {
    width: 20px;
    height: 20px;
  }
}

.footer ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer ul li {
    margin: 8px 0;
    color: var(--muted);
    font-size: 14px;
    cursor: pointer;
    transition: color 0.2s;
}

.footer ul li:hover {
    color: var(--accent);
}

.footer-text {
    color: var(--muted);
    font-size: 14px;
    line-height: 1.6;
    max-width: 320px;
}

.social-links {
    display: flex;
    gap: 16px;
    margin-top: 12px;
}

.social-links svg {
    width: 20px;
    height: 20px;
    fill: var(--muted);
    cursor: pointer;
    transition: fill 0.2s;
}

.social-links svg:hover {
    fill: var(--accent);
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
    </style>

    <footer class="footer">
  <div class="footer-columns">
    <div>
      <div class="footer-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Ambience RPG" class="logo-img">
      </div>
      <p class="footer-text">Capacitando mestres e aventureiros em todo o mundo com a plataforma definitiva para RPG.</p>
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
      <div class="social-links" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515a.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0a12.64 12.64 0 0 0-.617-1.25a.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057a19.9 19.9 0 0 0 5.993 3.03a.078.078 0 0 0 .084-.028a14.09 14.09 0 0 0 1.226-1.994a.076.076 0 0 0-.041-.106a13.107 13.107 0 0 1-1.872-.892a.077.077 0 0 1-.008-.128a10.2 10.2 0 0 0 .372-.292a.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127a12.299 12.299 0 0 1-1.873.892a.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028a19.839 19.839 0 0 0 6.002-3.03a.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/>
        </svg>
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53a4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
        </svg>
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <circle cx="12" cy="12" r="10"/>
          <circle cx="12" cy="12" r="3"/>
          <path d="M12 5c3.87 0 7 3.13 7 7"/>
        </svg>
      </div>
    </div>
  </div>
  <div class="footer-bottom">© 2025 Ambience RPG. Todos os direitos reservados.</div>
</footer>
<script>
// ========== SISTEMA DE NOTIFICAÇÕES ==========
(function(){
  const notificationBtn = document.getElementById('notificationBtn');
  const notificationModal = document.getElementById('notificationModal');
  const closeNotificationModal = document.getElementById('closeNotificationModal');

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

    adicionarEventListeners();
  }

  // Atualizar badge de contador
  function atualizarBadge(count) {
    let badge = notificationBtn.querySelector('.notification-badge');
    
    if (count > 0) {
      if (!badge) {
        badge = document.createElement('span');
        badge.className = 'notification-badge';
        notificationBtn.appendChild(badge);
      }
      badge.textContent = count > 99 ? '99+' : count;
    } else {
      if (badge) {
        badge.remove();
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
    document.querySelectorAll('.marcar-lida').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const id = parseInt(btn.dataset.id);
        marcarComoLida(id);
      });
    });

    document.querySelectorAll('.remover').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const id = parseInt(btn.dataset.id);
        removerNotificacao(id);
      });
    });

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
    if(!notificationModal.contains(e.target) && !notificationBtn.contains(e.target)) {
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
    // ========== SISTEMA DE MODAIS RPG ==========
// ========== SISTEMA DE MODAIS RPG ==========
(function() {
  // Função para abrir modal
  window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) {
      console.error('Modal não encontrado:', modalId);
      return;
    }
    
    // Fechar outros modais abertos
    document.querySelectorAll('.rpg-modal.show').forEach(m => {
      if (m.id !== modalId) {
        m.classList.remove('show');
      }
    });
    
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Reset form se existir
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }
    
    // Esconder feedback se existir
    const feedback = modal.querySelector('.rpg-modal-feedback');
    if (feedback) {
      feedback.classList.remove('show');
    }
    
    // Reset estados específicos
    resetModalStates(modal);
    
    console.log('Modal aberto:', modalId);
  };

  // Função para fechar modal
  window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) {
      console.error('Modal não encontrado para fechar:', modalId);
      return;
    }
    
    modal.classList.remove('show');
    document.body.style.overflow = '';
    
    console.log('Modal fechado:', modalId);
  };

  // Reset estados do modal
  function resetModalStates(modal) {
    // Reset preview da sala
    const preview = modal.querySelector('#salaPreviewContainer');
    if (preview) preview.style.display = 'none';
    
    // Reset senha container
    const senhaContainers = modal.querySelectorAll('#senhaEntrarContainer, #senhaContainer');
    senhaContainers.forEach(c => {
      if (c) c.style.display = 'none';
    });
    
    // Reset botões
    const btnVerificar = modal.querySelector('#btnVerificarSala');
    const btnEntrar = modal.querySelector('#btnEntrarSala');
    
    if (btnVerificar) {
      btnVerificar.style.display = '';
      btnVerificar.disabled = false;
      btnVerificar.classList.remove('loading');
      const span = btnVerificar.querySelector('span');
      if (span) span.textContent = 'Verificar Sala';
    }
    
    if (btnEntrar) {
      btnEntrar.style.display = 'none';
      btnEntrar.disabled = false;
      btnEntrar.classList.remove('loading');
    }
    
    // Limpar warnings
    modal.querySelectorAll('.rpg-warning').forEach(w => {
      w.style.display = 'none';
    });
  }

  // Event listeners para botões de fechar
  document.addEventListener('click', function(e) {
    // Botão de fechar com data-close-modal
    const closeBtn = e.target.closest('[data-close-modal]');
    if (closeBtn) {
      e.preventDefault();
      e.stopPropagation();
      const modalId = closeBtn.getAttribute('data-close-modal');
      closeModal(modalId);
      return;
    }
    
    // Clique no overlay
    if (e.target.classList.contains('rpg-modal-overlay')) {
      e.preventDefault();
      const modal = e.target.closest('.rpg-modal');
      if (modal) {
        closeModal(modal.id);
      }
      return;
    }
  });

  // Fechar ao pressionar ESC
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      const openModals = document.querySelectorAll('.rpg-modal.show');
      openModals.forEach(modal => {
        closeModal(modal.id);
      });
    }
  });

  // Função de feedback
window.showModalFeedback = function(modalId, type, title, message, autoClose) {
  if (typeof autoClose === 'undefined') autoClose = true;
  
  const modal = document.getElementById(modalId);
  if (!modal) return;
  
  const feedbackEl = modal.querySelector('.rpg-modal-feedback');
  if (!feedbackEl) return;
  
  const iconEl = feedbackEl.querySelector('.rpg-feedback-icon');
  const titleEl = feedbackEl.querySelector('h4');
  const messageEl = feedbackEl.querySelector('p');
  
  if (!iconEl || !titleEl || !messageEl) return;
  
  // Atualizar conteúdo
  titleEl.textContent = title;
  messageEl.textContent = message;
  
  // Remover classes antigas
  iconEl.classList.remove('success', 'error', 'warning');
  
  // Adicionar nova classe e ícone
  iconEl.classList.add(type);
  
  let iconSvg = '';
  if (type === 'success') {
    iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/></svg>';
  } else if (type === 'error') {
    iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
  } else if (type === 'warning') {
    iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 22h20L12 2z"/><line x1="12" y1="9" x2="12" y2="13"/><circle cx="12" cy="17" r="1" fill="currentColor"/></svg>';
  }
  
  iconEl.innerHTML = iconSvg;
  
  // Mostrar feedback
  feedbackEl.classList.add('show');
  
  // Lógica de fechamento baseada no tipo
  if (type === 'warning') {
    // Warning: volta ao formulário após 2 segundos
    setTimeout(function() {
      feedbackEl.classList.remove('show');
    }, 2000);
  } else if (type === 'error') {
    // Error: volta ao formulário após 2.5 segundos (para dar tempo de ler)
    setTimeout(function() {
      feedbackEl.classList.remove('show');
    }, 2500);
  } else if (type === 'success') {
    // Success com autoClose: fecha o modal
    if (autoClose) {
      setTimeout(function() {
        closeModal(modalId);
      }, 2500);
    }
    // Success sem autoClose: fica mostrando (geralmente tem redirect)
  }
};

  console.log('Sistema de Modais RPG inicializado');
})();

// ========== FEEDBACK VISUAL DOS MODAIS ==========
function showModalFeedback(modalId, type, title, message, autoClose = true) {
  const modal = document.getElementById(modalId);
  if (!modal) return;
  
  const feedbackEl = modal.querySelector('.rpg-modal-feedback');
  const iconEl = feedbackEl.querySelector('.rpg-feedback-icon');
  const titleEl = feedbackEl.querySelector('h4');
  const messageEl = feedbackEl.querySelector('p');
  
  // Atualizar conteúdo
  titleEl.textContent = title;
  messageEl.textContent = message;
  
  // Remover classes antigas
  iconEl.classList.remove('success', 'error', 'warning');
  
  // Adicionar nova classe e ícone
  iconEl.classList.add(type);
  
  let iconSvg = '';
  if (type === 'success') {
    iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/></svg>';
  } else if (type === 'error') {
    iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
  } else if (type === 'warning') {
    iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>';
  }
  
  iconEl.innerHTML = iconSvg;
  
  // Mostrar feedback
  feedbackEl.classList.add('show');
  
  // Auto fechar se solicitado
  if (autoClose) {
    setTimeout(() => {
      closeModal(modalId);
    }, 2500);
  }
}

// ========== FORM CRIAR SALA ==========
$('#tipoSala').change(function() {
  const tipo = $(this).val();
  const senhaContainer = $('#senhaContainer');
  
  if (tipo === 'privada') {
    senhaContainer.slideDown(300);
    $('#senhaSala').attr('required', true);
  } else {
    senhaContainer.slideUp(300);
    $('#senhaSala').attr('required', false);
  }
});

// ========== SISTEMA DE BLOQUEIO DE SUBMIT COM MODERAÇÃO ==========

// Adicione este script ANTES do event listener do #formCriarSala

// Estado global de moderação
window.moderationState = {
  nome: { inappropriate: false, checking: false },
  descricao: { inappropriate: false, checking: false }
};

// Função para verificar se pode submeter
function canSubmitForm() {
  const hasVisibleWarnings = $('#formCriarSala .rpg-warning:visible').length > 0;
  const hasInappropriateInputs = $('#formCriarSala .input-warn').length > 0;
  const hasStateIssues = window.moderationState.nome.inappropriate || 
                         window.moderationState.descricao.inappropriate;
  const isChecking = window.moderationState.nome.checking || 
                     window.moderationState.descricao.checking;
  
  console.log('🔍 Verificação de submit:', {
    hasVisibleWarnings,
    hasInappropriateInputs,
    hasStateIssues,
    isChecking,
    state: window.moderationState
  });
  
  return !hasVisibleWarnings && !hasInappropriateInputs && !hasStateIssues && !isChecking;
}

// Função para bloquear submit e mostrar feedback
function blockSubmitWithFeedback() {
  console.error('🚫 Submit BLOQUEADO por conteúdo inapropriado');
  
  showModalFeedback('modalCriarSala', 'warning', 
    'Conteúdo Inapropriado Detectado', 
    'Por favor, revise os campos marcados. Não é permitido criar salas com conteúdo ofensivo, spam ou inapropriado. Se você acredita que isso é um erro, contate o suporte.',
    false, // Não fecha automaticamente
    5000  // Duração em milissegundos (5 segundos)
  );
  
  return false;
}

// Atualizar os event listeners de moderação
$(document).ready(function() {
  // Quando a moderação for inicializada
  if (window.Moderation) {
    // Override do attachInput para atualizar o estado
    const originalAttachInput = window.Moderation.attachInput;
    
    window.Moderation.attachInput = function(selector, fieldName, callbacks) {
      const originalOnLocal = callbacks?.onLocal;
      const originalOnServer = callbacks?.onServer;
      
      const enhancedCallbacks = {
        ...callbacks,
        onLocal: (res) => {
          // Atualizar estado
          window.moderationState[fieldName] = {
            inappropriate: res?.inappropriate || false,
            checking: false
          };
          
          console.log(`📝 Moderação local [${fieldName}]:`, window.moderationState[fieldName]);
          
          // Chamar callback original
          if (originalOnLocal) originalOnLocal(res);
        },
        onServer: (srv) => {
          // Atualizar estado
          if (srv?.data) {
            window.moderationState[fieldName] = {
              inappropriate: srv.data.inappropriate || false,
              checking: false
            };
            
            console.log(`🌐 Moderação servidor [${fieldName}]:`, window.moderationState[fieldName]);
          }
          
          // Chamar callback original
          if (originalOnServer) originalOnServer(srv);
        }
      };
      
      // Adicionar listener de input para marcar como "checking"
      $(selector).on('input', function() {
        window.moderationState[fieldName].checking = true;
      });
      
      // Chamar o método original
      return originalAttachInput.call(this, selector, fieldName, enhancedCallbacks);
    };
  }
});

// ========== SUBSTITUIR O EVENT LISTENER DO #formCriarSala ==========
// Remova o listener existente e adicione este:

$('#formCriarSala').off('submit').on('submit', function(e) {
  e.preventDefault();
  e.stopPropagation();
  e.stopImmediatePropagation();
  
  console.log('📋 Tentativa de submit do formulário');
  
  const btnSubmit = $('#btnCriarSala');
  const originalHtml = btnSubmit.html();
  
  // ========== VERIFICAÇÃO CRÍTICA 1: Warnings Visuais ==========
  const hasVisibleWarnings = $('#formCriarSala .rpg-warning:visible').length > 0;
  const hasInappropriateInputs = $('#formCriarSala .input-warn').length > 0;
  
  if (hasVisibleWarnings || hasInappropriateInputs) {
    console.warn('🚫 Bloqueio 1: Warnings visuais detectados');
    return blockSubmitWithFeedback();
  }
  
  // ========== VERIFICAÇÃO CRÍTICA 2: Estado de Moderação ==========
  if (window.moderationState.nome.inappropriate || window.moderationState.descricao.inappropriate) {
    console.warn('🚫 Bloqueio 2: Estado de moderação inapropriado');
    
    // Aplicar warnings visuais se ainda não existirem
    if (window.moderationState.nome.inappropriate) {
      $('#nomeSala').addClass('input-warn');
      $('#nomeSala-warning').addClass('show').css('display', 'block');
    }
    if (window.moderationState.descricao.inappropriate) {
      $('#descricaoSala').addClass('input-warn');
      $('#descricaoSala-warning').addClass('show').css('display', 'block');
    }
    
    return blockSubmitWithFeedback();
  }
  
  // ========== VERIFICAÇÃO CRÍTICA 3: Check Local Síncrono Final ==========
  const nomeSala = $('#nomeSala').val().trim();
  const descricaoSala = $('#descricaoSala').val().trim();
  
  let hasLocalIssues = false;
  
  if (nomeSala && window.Moderation) {
    const nomeCheck = window.Moderation.checkLocal(nomeSala);
    if (nomeCheck.inappropriate) {
      hasLocalIssues = true;
      console.error('🚫 Bloqueio 3A: Nome inapropriado detectado no check final:', nomeCheck.matches);
      
      $('#nomeSala').addClass('input-warn');
      $('#nomeSala-warning').addClass('show').css('display', 'block');
      window.moderationState.nome.inappropriate = true;
    }
  }
  
  if (descricaoSala && window.Moderation) {
    const descCheck = window.Moderation.checkLocal(descricaoSala);
    if (descCheck.inappropriate) {
      hasLocalIssues = true;
      console.error('🚫 Bloqueio 3B: Descrição inapropriada detectada no check final:', descCheck.matches);
      
      $('#descricaoSala').addClass('input-warn');
      $('#descricaoSala-warning').addClass('show').css('display', 'block');
      window.moderationState.descricao.inappropriate = true;
    }
  }
  
  if (hasLocalIssues) {
    return blockSubmitWithFeedback();
  }
  
  // ========== VERIFICAÇÃO CRÍTICA 4: Aguardar Checks Assíncronos ==========
  if (window.moderationState.nome.checking || window.moderationState.descricao.checking) {
    console.warn('⏳ Aguardando verificação de moderação...');
    
    btnSubmit.addClass('loading').prop('disabled', true);
    btnSubmit.find('span').text('Verificando conteúdo...');
    
    // Aguardar até 3 segundos pela verificação
    let attempts = 0;
    const maxAttempts = 30; // 30 x 100ms = 3 segundos
    
    const waitInterval = setInterval(function() {
      attempts++;
      
      if (!window.moderationState.nome.checking && !window.moderationState.descricao.checking) {
        clearInterval(waitInterval);
        
        // Verificar novamente se passou
        if (window.moderationState.nome.inappropriate || window.moderationState.descricao.inappropriate) {
          btnSubmit.removeClass('loading').prop('disabled', false).html(originalHtml);
          blockSubmitWithFeedback();
        } else {
          // Resubmeter o formulário
          $('#formCriarSala').trigger('submit');
        }
      } else if (attempts >= maxAttempts) {
        clearInterval(waitInterval);
        btnSubmit.removeClass('loading').prop('disabled', false).html(originalHtml);
        
        showModalFeedback('modalCriarSala', 'error', 
          'Tempo Esgotado', 
          'A verificação de conteúdo demorou muito. Tente novamente.'
        );
      }
    }, 100);
    
    return false;
  }
  
  // ========== VERIFICAÇÃO FINAL APROVADA - PROSSEGUIR COM SUBMIT ==========
  console.log('✅ Todas as verificações passaram. Prosseguindo com submit...');
  
  btnSubmit.addClass('loading').prop('disabled', true);
  btnSubmit.find('span').text('Criando...');
  
  const formData = $(this).serialize();
  
  $.ajax({
    url: '/salas',
    type: 'POST',
    data: formData,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      'Accept': 'application/json'
    }
  })
  .done(function(response) {
    if (response.success) {
      showModalFeedback('modalCriarSala', 'success', 'Sala Criada!', 'Redirecionando para sua nova sala...', false);
      
      // Resetar estado
      window.moderationState.nome.inappropriate = false;
      window.moderationState.descricao.inappropriate = false;
      
      setTimeout(function() {
        window.location.href = `/salas/${response.sala.id}`;
      }, 2000);
    } else {
      showModalFeedback('modalCriarSala', 'error', 'Erro', response.message || 'Não foi possível criar a sala.');
      btnSubmit.removeClass('loading').prop('disabled', false).html(originalHtml);
    }
  })
  .fail(function(xhr) {
    let errorMsg = 'Erro ao criar sala. Tente novamente.';
    
    if (xhr.status === 422 && xhr.responseJSON?.errors) {
      const errors = xhr.responseJSON.errors;
      errorMsg = Object.values(errors).flat().join(' ');
    } else if (xhr.responseJSON?.message) {
      errorMsg = xhr.responseJSON.message;
    }
    
    showModalFeedback('modalCriarSala', 'error', 'Erro', errorMsg);
    btnSubmit.removeClass('loading').prop('disabled', false).html(originalHtml);
  });
  
  return false;
});

// ========== LIMPAR WARNINGS QUANDO O USUÁRIO CORRIGIR ==========
$('#nomeSala').on('input', function() {
  const $input = $(this);
  const $warning = $('#nomeSala-warning');
  
  // Se o campo estiver vazio, limpar warnings
  if (!$input.val().trim()) {
    $input.removeClass('input-warn');
    $warning.removeClass('show').css('display', 'none');
    window.moderationState.nome.inappropriate = false;
  }
});

$('#descricaoSala').on('input', function() {
  const $input = $(this);
  const $warning = $('#descricaoSala-warning');
  
  // Se o campo estiver vazio, limpar warnings
  if (!$input.val().trim()) {
    $input.removeClass('input-warn');
    $warning.removeClass('show').css('display', 'none');
    window.moderationState.descricao.inappropriate = false;
  }
});

// ========== DEBUG HELPER ==========
window.debugModeration = function() {
  console.log('🔍 Estado atual de moderação:', window.moderationState);
  console.log('Warnings visíveis:', $('#formCriarSala .rpg-warning:visible').length);
  console.log('Inputs marcados:', $('#formCriarSala .input-warn').length);
  console.log('Pode submeter?', canSubmitForm());
};

console.log('✅ Sistema de bloqueio de submit com moderação inicializado');

// ========== DROPDOWN STAFF ACTIONS ==========
$(document).on('click', '.dropdown-toggle', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const $dropdown = $(this).closest('.dropdown');
    const $menu = $dropdown.find('.dropdown-menu');
    const isOpen = $dropdown.hasClass('show');
    
    // Fechar todos os outros dropdowns
    $('.dropdown.show').removeClass('show');
    $('.dropdown-menu').removeClass('show');
    
    // Toggle do dropdown atual
    if (!isOpen) {
        $dropdown.addClass('show');
        $menu.addClass('show');
    }
});

// Fechar dropdown ao clicar fora
$(document).on('click', function(e) {
    if (!$(e.target).closest('.dropdown').length) {
        $('.dropdown.show').removeClass('show');
        $('.dropdown-menu').removeClass('show');
    }
});

// Fechar dropdown ao clicar em um item
$(document).on('click', '.dropdown-item', function() {
    $(this).closest('.dropdown').removeClass('show');
    $(this).closest('.dropdown-menu').removeClass('show');
});

console.log('✅ Sistema de dropdown staff actions inicializado');

// ========== FORM ENTRAR EM SALA ==========
$('#btnVerificarSala').click(function() {
  const salaId = $('#idSalaEntrar').val();
  
  if (!salaId || salaId.trim() === '') {
    showModalFeedback('modalEntrarSala', 'warning', 'Campo Obrigatório', 'Digite o ID da sala para continuar.');
    setTimeout(function() {
      $('#idSalaEntrar').focus();
    }, 2100);
    return;
  }
  
  const btn = $(this);
  const originalHtml = btn.html();
  
  btn.addClass('loading').prop('disabled', true);
  btn.find('span').text('Verificando...');
  
  $.ajax({
    url: `/salas/${salaId}/info`,
    type: 'GET',
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      'Accept': 'application/json'
    }
  })
  .done(function(response) {
    if (response.success) {
      const sala = response.sala;
      
      let tipoText = getTipoTextWithIcon(sala.tipo);

if (sala.tipo === 'privada') {
    tipoClass = 'privada';
}
      
      const previewHtml = `
        <div class="rpg-preview-item">
          <span class="rpg-preview-label">Nome da Sala</span>
          <span class="rpg-preview-value">${sala.nome}</span>
        </div>
        <div class="rpg-preview-item">
          <span class="rpg-preview-label">Tipo</span>
          <span class="rpg-preview-badge ${tipoClass}">${tipoText}</span>
        </div>
        <div class="rpg-preview-item">
          <span class="rpg-preview-label">Participantes</span>
          <span class="rpg-preview-value">${sala.participantes_atuais}/${sala.max_participantes}</span>
        </div>
      `;
      
      $('#salaPreviewContent').html(previewHtml);
      $('#salaPreviewContainer').slideDown(400);
      
      if (sala.precisa_senha) {
        $('#senhaEntrarContainer').slideDown(400);
        $('#senhaEntrar').attr('required', true);
      } else {
        $('#senhaEntrarContainer').slideUp(400);
        $('#senhaEntrar').attr('required', false);
      }
      
      btn.hide();
      $('#btnEntrarSala').show();
      btn.removeClass('loading').prop('disabled', false).html(originalHtml);
    } else {
      // Erro retornado pelo servidor (success: false)
      showModalFeedback('modalEntrarSala', 'error', 'Erro', response.message || 'Não foi possível verificar a sala.');
      btn.removeClass('loading').prop('disabled', false).html(originalHtml);
    }
  })
  .fail(function(xhr) {
    let errorMsg = 'Sala não encontrada.';
    let errorTitle = 'Sala Não Encontrada';
    
    if (xhr.responseJSON) {
      // Verificar se já participa da sala (status 400)
      if (xhr.status === 400 && xhr.responseJSON.message) {
        errorMsg = xhr.responseJSON.message;
        
        // Se já participa, mostrar mensagem mais amigável e redirecionar
        if (errorMsg.includes('já participa') || errorMsg.includes('Você já')) {
          showModalFeedback('modalEntrarSala', 'success', 'Você já está nesta sala!', 'Redirecionando...', false);
          setTimeout(function() {
            window.location.href = `/salas/${salaId}`;
          }, 1500);
          return;
        }
        
        errorTitle = 'Aviso';
      } else if (xhr.responseJSON.message) {
        errorMsg = xhr.responseJSON.message;
      }
    }
    
    showModalFeedback('modalEntrarSala', 'error', errorTitle, errorMsg);
    btn.removeClass('loading').prop('disabled', false).html(originalHtml);
  });
});

$('#formEntrarSala').submit(function(e) {
  e.preventDefault();
  
  const btnSubmit = $('#btnEntrarSala');
  const originalHtml = btnSubmit.html();
  
  btnSubmit.addClass('loading').prop('disabled', true);
  btnSubmit.find('span').text('Entrando...');
  
  const formData = $(this).serialize();
  
  $.ajax({
    url: '/salas/entrar',
    type: 'POST',
    data: formData,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      'Accept': 'application/json'
    }
  })
  .done(function(response) {
    if (response.success) {
      // Sucesso - mostra feedback e redireciona
      showModalFeedback('modalEntrarSala', 'success', 'Bem-vindo!', 'Entrando na sala...', false);
      
      setTimeout(function() {
        window.location.href = response.redirect_to;
      }, 1500);
    } else {
      // Erro - mostra APENAS no modal
      showModalFeedback('modalEntrarSala', 'error', 'Erro', response.message || 'Não foi possível entrar na sala.');
      btnSubmit.removeClass('loading').prop('disabled', false).html(originalHtml);
    }
  })
  .fail(function(xhr) {
    let errorMsg = 'Erro ao entrar na sala. Tente novamente.';
    
    if (xhr.responseJSON?.message) {
      errorMsg = xhr.responseJSON.message;
    }
    
    // Erro - mostra APENAS no modal
    showModalFeedback('modalEntrarSala', 'error', 'Erro', errorMsg);
    btnSubmit.removeClass('loading').prop('disabled', false).html(originalHtml);
  });
});
</script>

<script>
// Prevenir Bootstrap de inicializar modais automaticamente nos elementos .rpg-modal
document.addEventListener('DOMContentLoaded', function() {
  // Remover data attributes do Bootstrap dos modais customizados
  document.querySelectorAll('.rpg-modal').forEach(function(modal) {
    modal.removeAttribute('data-bs-backdrop');
    modal.removeAttribute('data-bs-keyboard');
  });
  
  // Prevenir que cliques nos botões disparem eventos Bootstrap
  document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(btn) {
    btn.removeAttribute('data-bs-toggle');
    btn.removeAttribute('data-bs-target');
  });
});


document.getElementById('formCriarSala').addEventListener('moderation:blocked', (e) => {
  console.error('🚫 Formulário bloqueado por conteúdo inapropriado:', e.detail);
  
  // Garantir que os inputs fiquem marcados
  e.detail.forEach(item => {
    if (item.field === 'nome') {
      $('#nomeSala').addClass('input-warn');
      $('#nomeSala-warning').addClass('show').css('display', 'block');
    }
    if (item.field === 'descricao') {
      $('#descricaoSala').addClass('input-warn');
      $('#descricaoSala-warning').addClass('show').css('display', 'block');
    }
  });
  
  // Mostrar feedback no modal
  showModalFeedback('modalCriarSala', 'warning', 
    'Conteúdo Inapropriado Detectado', 
    'Por favor, revise os campos marcados antes de criar a sala. Se você acredita que isso é um erro, contate o suporte.',
    true // autoClose para voltar ao form
  );
});
</script>
</body>
</html>