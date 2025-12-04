<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard de Moderação - Ambience RPG</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
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
            --gradient-start: #022c22;
            --gradient-mid: #034935ff;
            --gradient-end: #22553dff;
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
            font-family: Inter, system-ui, -apple-system, sans-serif;
            background: linear-gradient(145deg, #0a0f14f4, #141c23f2);
            color: var(--text-on-primary);
            -webkit-font-smoothing: antialiased;
            line-height: 1.5;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Header */
        .page-header {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            margin-bottom: 30px;
            animation: slideDown 0.5s ease;
            border: 1px solid rgba(34, 197, 94, 0.2);
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

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            flex-wrap: wrap;
        }

        .header-info h1 {
            font-family: Montserrat, sans-serif;
            font-size: 32px;
            color: #fff;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 900;
        }

        .header-info p {
            color: var(--muted);
            font-size: 16px;
        }

        /* Search Bar */
        .search-section {
            flex: 1;
            min-width: 300px;
            max-width: 600px;
        }

        .search-wrapper {
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 14px 48px 14px 48px;
            border: 2px solid rgba(34, 197, 94, 0.2);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: rgba(10, 15, 20, 0.4);
            color: var(--text-on-primary);
            font-family: Inter, sans-serif;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: var(--muted);
        }

        .quick-filters {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .filter-chip {
            padding: 6px 14px;
            background: rgba(34, 197, 94, 0.1);
            border: 2px solid rgba(34, 197, 94, 0.2);
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            color: var(--muted);
        }

        .filter-chip:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-2px);
            background: rgba(34, 197, 94, 0.2);
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

        .btn-view-all {
            padding: 14px 28px;
            background: linear-gradient(to right, var(--btn-gradient-start), var(--btn-gradient-end));
            color: var(--hero-green);
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            white-space: nowrap;
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
        }

        .btn-view-all:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 22px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            animation: fadeInUp 0.5s ease;
            animation-fill-mode: both;
            border: 1px solid rgba(34, 197, 94, 0.1);
            position: relative;
            overflow: hidden;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(34, 197, 94, 0.05), transparent 70%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
            border-color: rgba(34, 197, 94, 0.3);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card.border-blue { border-left: 4px solid #3b82f6; }
        .stat-card.border-orange { border-left: 4px solid #f97316; }
        .stat-card.border-purple { border-left: 4px solid #a855f7; }
        .stat-card.border-red { border-left: 4px solid #ef4444; }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .stat-info h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #fff;
            line-height: 1;
        }

        .stat-subtitle {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon.blue { background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.1)); }
        .stat-icon.orange { background: linear-gradient(135deg, rgba(249, 115, 22, 0.2), rgba(234, 88, 12, 0.1)); }
        .stat-icon.purple { background: linear-gradient(135deg, rgba(168, 85, 247, 0.2), rgba(147, 51, 234, 0.1)); }
        .stat-icon.red { background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.1)); }

        .stat-icon svg {
            width: 28px;
            height: 28px;
        }

        .stat-icon.blue svg { color: #3b82f6; }
        .stat-icon.orange svg { color: #f97316; }
        .stat-icon.purple svg { color: #a855f7; }
        .stat-icon.red svg { color: #ef4444; }

        /* Secondary Stats */
        .secondary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .secondary-card {
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            color: white;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.5s ease;
            animation-fill-mode: both;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .secondary-card:nth-child(1) { 
            animation-delay: 0.5s;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(37, 99, 235, 0.1));
        }
        .secondary-card:nth-child(2) { 
            animation-delay: 0.6s;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(217, 119, 6, 0.1));
        }
        .secondary-card:nth-child(3) { 
            animation-delay: 0.7s;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(220, 38, 38, 0.1));
        }

        .secondary-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(34, 197, 94, 0.05);
            border-radius: 50%;
        }

        .secondary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }

        .secondary-header h3 {
            font-size: 18px;
            font-weight: 700;
        }

        .secondary-header svg {
            width: 24px;
            height: 24px;
            opacity: 0.9;
        }

        .secondary-value {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
        }

        .secondary-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 16px;
        }

        .secondary-link {
            color: white;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            opacity: 0.95;
            transition: all 0.2s;
        }

        .secondary-link:hover {
            opacity: 1;
            gap: 10px;
        }

        /* Charts Section */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.5s ease;
            animation-fill-mode: both;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .chart-card:nth-child(1) { animation-delay: 0.8s; }
        .chart-card:nth-child(2) { animation-delay: 0.9s; }

        .chart-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .chart-header svg {
            width: 24px;
            height: 24px;
        }

        .chart-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }

        .chart-item {
            margin-bottom: 24px;
        }

        .chart-item:last-child {
            margin-bottom: 0;
        }

        .chart-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .chart-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-on-primary);
            cursor: pointer;
            transition: all 0.2s;
        }

        .chart-label:hover {
            color: var(--accent);
        }

        .chart-value {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background: rgba(34, 197, 94, 0.1);
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            border-radius: 6px;
            transition: width 1s ease;
            position: relative;
            overflow: hidden;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, 
                rgba(255,255,255,0) 0%, 
                rgba(255,255,255,0.3) 50%, 
                rgba(255,255,255,0) 100%);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .progress-fill.blue { background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%); }
        .progress-fill.orange { background: linear-gradient(90deg, #f97316 0%, #ea580c 100%); }
        .progress-fill.red { background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%); }
        .progress-fill.green { background: linear-gradient(90deg, #10b981 0%, #059669 100%); }
        .progress-fill.gray { background: linear-gradient(90deg, #6b7280 0%, #4b5563 100%); }
        .progress-fill.yellow { background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%); }
        .progress-fill.purple { background: linear-gradient(90deg, #a855f7 0%, #9333ea 100%); }

        .chart-percentage {
            font-size: 12px;
            color: var(--muted);
            margin-top: 6px;
        }

        .empty-chart {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted);
        }

        /* Recent Activity */
        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .activity-card {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.5s ease;
            animation-fill-mode: both;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .activity-card:nth-child(1) { animation-delay: 1s; }
        .activity-card:nth-child(2) { animation-delay: 1.1s; }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .activity-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .activity-title svg {
            width: 24px;
            height: 24px;
        }

        .activity-title h2 {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }

        .activity-link {
            color: var(--accent);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .activity-link:hover {
            color: var(--accent-light);
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .activity-item {
            padding: 20px;
            background: rgba(34, 197, 94, 0.05);
            border-radius: 12px;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            display: block;
            border: 1px solid rgba(34, 197, 94, 0.1);
        }

        .activity-item:hover {
            background: rgba(34, 197, 94, 0.1);
            transform: translateX(5px);
            border-color: rgba(34, 197, 94, 0.2);
        }

        .activity-item-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .ticket-number {
            font-size: 15px;
            font-weight: 700;
            color: var(--accent);
        }

        .badge {
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge.status-novo { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
        .badge.status-analise { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
        .badge.status-resolvido { background: rgba(34, 197, 94, 0.2); color: var(--accent); }
        .badge.status-default { background: rgba(139, 155, 168, 0.2); color: var(--muted); }

        .activity-item-title {
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 10px;
        }

        .activity-item-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 13px;
            color: var(--muted);
        }

        .meta-user {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-avatar {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(34, 197, 94, 0.3);
        }

        .user-initial {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
            color: var(--hero-green);
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-activity {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-activity svg {
            width: 80px;
            height: 80px;
            color: var(--muted);
            opacity: 0.4;
            margin-bottom: 16px;
        }

        .empty-activity p {
            color: var(--muted);
            font-size: 15px;
        }

        /* Staff List */
        .staff-item {
            padding: 20px;
            background: rgba(34, 197, 94, 0.05);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            border: 1px solid rgba(34, 197, 94, 0.1);
        }

        .staff-info {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
        }

        .staff-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--accent);
            flex-shrink: 0;
        }

        .staff-details h4 {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
        }

        .staff-details p {
            font-size: 12px;
            color: var(--muted);
            text-transform: capitalize;
        }

        .staff-count {
            text-align: right;
        }

        .staff-count-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--accent);
        }

        .staff-count-label {
            font-size: 11px;
            color: var(--muted);
        }

        /* Quick Actions */
        .quick-actions {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            animation: fadeInUp 0.5s ease 1.2s both;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .quick-actions h2 {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 28px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .action-card {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(34, 197, 94, 0.2);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            text-decoration: none;
            color: white;
            transition: all 0.3s ease;
        }

        .action-card:hover {
            background: #0f5a2b33;
            transform: translateY(-5px);
            border-color: rgba(34, 197, 94, 0.4);
        }

        .action-card svg {
            width: 40px;
            height: 40px;
            margin-bottom: 12px;
        }

        .action-card-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .action-card-count {
            font-size: 13px;
            opacity: 0.9;
        }

        @media (max-width: 1400px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
            
            .activity-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }

            .page-header {
                padding: 20px;
            }

            .header-info h1 {
                font-size: 24px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .secondary-stats {
                grid-template-columns: 1fr;
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

            .activity-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-info">
                    <h1>
                         Dashboard de Moderação
                    </h1>
                    <p>Visão geral do sistema de suporte</p>
                </div>
                
                <!-- Search Section -->
                <div class="search-section">
                    <form action="{{ route('suporte.moderacao.index') }}" method="GET">
                        <div class="search-wrapper">
                            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" 
                                name="busca" 
                                value="{{ request('busca') }}"
                                placeholder="Buscar por #número, assunto, usuário ou categoria..."
                                class="search-input">
                        </div>
                        
                        <div class="quick-filters">
                            <button type="button" onclick="document.querySelector('input[name=busca]').value='categoria:duvida'; this.form.submit();" class="filter-chip blue">
                                 Dúvidas
                            </button>
                            <button type="button" onclick="document.querySelector('input[name=busca]').value='categoria:problema_tecnico'; this.form.submit();" class="filter-chip orange">
                                 Problemas
                            </button>
                            <button type="button" onclick="document.querySelector('input[name=busca]').value='categoria:denuncia'; this.form.submit();" class="filter-chip red">
                                 Denúncias
                            </button>
                            <button type="button" onclick="document.querySelector('input[name=busca]').value='categoria:sugestao'; this.form.submit();" class="filter-chip green">
                                 Sugestões
                            </button>
                        </div>
                    </form>
                </div>

                <a href="{{ route('suporte.moderacao.index') }}" class="btn-view-all">
                    Ver Todos os Tickets
                </a>
            </div>
        </div>

        <!-- Main Stats -->
        <div class="stats-grid">
            <div class="stat-card border-blue">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Total de Tickets</h3>
                        <div class="stat-value">{{ $stats['total_tickets'] }}</div>
                        <div class="stat-subtitle">Todos os tempos</div>
                    </div>
                    <div class="stat-icon blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card border-orange">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Tickets Abertos</h3>
                        <div class="stat-value">{{ $stats['tickets_abertos'] }}</div>
                        <div class="stat-subtitle">Requer atenção</div>
                    </div>
                    <div class="stat-icon orange">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card border-purple">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Novos (Sem Atribuição)</h3>
                        <div class="stat-value">{{ $stats['tickets_novos'] }}</div>
                        <div class="stat-subtitle">Aguardando atribuição</div>
                    </div>
                    <div class="stat-icon purple">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card border-red">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>Denúncias Pendentes</h3>
                        <div class="stat-value">{{ $stats['denuncias_pendentes'] }}</div>
                        <div class="stat-subtitle">Requer análise urgente</div>
                    </div>
                    <div class="stat-icon red">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="secondary-stats">
            <div class="secondary-card">
                <div class="secondary-header">
                    <h3>Meus Tickets Atribuídos</h3>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="secondary-value">{{ $stats['meus_tickets'] }}</div>
                <div class="secondary-subtitle">Tickets abertos atribuídos a você</div>
                <a href="{{ route('suporte.moderacao.index', ['filtro' => 'meus']) }}" class="secondary-link">
                    Ver meus tickets →
                </a>
            </div>

            <div class="secondary-card">
                <div class="secondary-header">
                    <h3>Aguardando Resposta</h3>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <div class="secondary-value">{{ $stats['aguardando_resposta'] }}</div>
                <div class="secondary-subtitle">Usuários aguardando resposta do staff</div>
                <a href="{{ route('suporte.moderacao.index', ['filtro' => 'pendentes']) }}" class="secondary-link">
                    Responder tickets →
                </a>
            </div>

            <div class="secondary-card">
                <div class="secondary-header">
                    <h3>Prioridade Urgente</h3>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="secondary-value">{{ $stats['urgentes'] }}</div>
                <div class="secondary-subtitle">Tickets marcados como urgentes</div>
                <a href="{{ route('suporte.moderacao.index', ['prioridade' => 'urgente']) }}" class="secondary-link">
                    Ver urgentes →
                </a>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-grid">
            <!-- Tickets por Categoria -->
            <div class="chart-card">
                <div class="chart-header">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #3b82f6;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h2>Tickets por Categoria</h2>
                </div>

                @php
                    $categorias = [
                        'duvida' => ['label' => 'Dúvidas', 'color' => 'blue'],
                        'problema_tecnico' => ['label' => 'Problemas Técnicos', 'color' => 'orange'],
                        'denuncia' => ['label' => 'Denúncias', 'color' => 'red'],
                        'sugestao' => ['label' => 'Sugestões', 'color' => 'green'],
                        'outro' => ['label' => 'Outros', 'color' => 'gray']
                    ];
                    
                    $totalCategorias = array_sum($porCategoria);
                @endphp

                @if($totalCategorias > 0)
                    @foreach($categorias as $key => $categoria)
                        @php
                            $total = $porCategoria[$key] ?? 0;
                            $percentual = ($total / $totalCategorias) * 100;
                        @endphp
                        <div class="chart-item">
                            <div class="chart-item-header">
                                <span class="chart-label" onclick="window.location.href='{{ route('suporte.moderacao.index', ['busca' => 'categoria:' . $key]) }}'">
                                    {{ $categoria['label'] }}
                                </span>
                                <span class="chart-value">{{ $total }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill {{ $categoria['color'] }}" style="width: {{ $percentual }}%"></div>
                            </div>
                            <div class="chart-percentage">{{ number_format($percentual, 1) }}% do total</div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-chart">
                        <p>Nenhum ticket registrado ainda</p>
                    </div>
                @endif
            </div>

            <!-- Tickets por Status -->
            <div class="chart-card">
                <div class="chart-header">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #10b981;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <h2>Tickets por Status</h2>
                </div>

                @php
                    $statusList = [
                        'novo' => ['label' => 'Novos', 'color' => 'blue'],
                        'em_analise' => ['label' => 'Em Análise', 'color' => 'yellow'],
                        'aguardando_resposta' => ['label' => 'Aguardando Resposta', 'color' => 'purple'],
                        'pendente' => ['label' => 'Pendentes', 'color' => 'orange'],
                        'resolvido' => ['label' => 'Resolvidos', 'color' => 'green'],
                        'fechado' => ['label' => 'Fechados', 'color' => 'gray'],
                        'spam' => ['label' => 'Spam', 'color' => 'red']
                    ];
                    
                    $totalStatus = array_sum($porStatus);
                @endphp

                @if($totalStatus > 0)
                    @foreach($statusList as $key => $status)
                        @php
                            $total = $porStatus[$key] ?? 0;
                            $percentual = ($total / $totalStatus) * 100;
                        @endphp
                        <div class="chart-item">
                            <div class="chart-item-header">
                                <span class="chart-label">{{ $status['label'] }}</span>
                                <span class="chart-value">{{ $total }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill {{ $status['color'] }}" style="width: {{ $percentual }}%"></div>
                            </div>
                            <div class="chart-percentage">{{ number_format($percentual, 1) }}% do total</div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-chart">
                        <p>Nenhum ticket registrado ainda</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Activity Section -->
        <div class="activity-grid">
            <!-- Últimos Tickets -->
            <div class="activity-card">
                <div class="activity-header">
                    <div class="activity-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #a855f7;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h2>Últimos Tickets</h2>
                    </div>
                    <a href="{{ route('suporte.moderacao.index') }}" class="activity-link">
                        Ver todos →
                    </a>
                </div>

                <div class="activity-list">
                    @forelse($ultimosTickets as $ticket)
                        <a href="{{ route('suporte.show', $ticket->id) }}" class="activity-item">
                            <div class="activity-item-header">
                                <span class="ticket-number">#{{ $ticket->numero_ticket }}</span>
                                <span class="badge 
                                    @if($ticket->status === 'novo') status-novo
                                    @elseif($ticket->status === 'em_analise') status-analise
                                    @elseif($ticket->status === 'resolvido') status-resolvido
                                    @else status-default
                                    @endif">
                                    {{ $ticket->getStatusLabel() }}
                                </span>
                            </div>
                            
                            <div class="activity-item-title">{{ Str::limit($ticket->assunto, 60) }}</div>
                            
                            <div class="activity-item-meta">
                                <div class="meta-user">
                                    @if($ticket->usuario->avatar_url)
    <img src="{{ str_starts_with($ticket->usuario->avatar_url, 'http') ? $ticket->usuario->avatar_url : Storage::url($ticket->usuario->avatar_url) }}" 
        alt="{{ $ticket->usuario->username }}" 
        class="user-avatar"
        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
    <div class="user-initial" style="display: none;">
        {{ strtoupper(substr($ticket->usuario->username, 0, 1)) }}
    </div>
@else
    <div class="user-initial">
        {{ strtoupper(substr($ticket->usuario->username, 0, 1)) }}
    </div>
@endif
                                    <span>{{ $ticket->usuario->username }}</span>
                                </div>
                                <span>•</span>
                                <span>{{ $ticket->created_at->diffForHumans() }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="empty-activity">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p>Nenhum ticket ainda</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Staff Ativo -->
            <div class="activity-card">
                <div class="activity-header">
                    <div class="activity-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h2>Staff com Mais Tickets Abertos</h2>
                    </div>
                </div>

                <div class="activity-list">
                    @forelse($staffAtivo as $staff)
                        <div class="staff-item">
                            <div class="staff-info">
                                @if($staff->avatar_url)
    <img src="{{ str_starts_with($staff->avatar_url, 'http') ? $staff->avatar_url : Storage::url($staff->avatar_url) }}" 
        alt="{{ $staff->username }}" 
        class="staff-avatar"
        onerror="this.src='{{ asset('images/default-avatar.png') }}'">
@else
    <img src="{{ asset('images/default-avatar.png') }}" 
        alt="{{ $staff->username }}" 
        class="staff-avatar">
@endif
                                <div class="staff-details">
                                    <h4>{{ $staff->username }}</h4>
                                    <p>{{ $staff->nivel_usuario === 'admin' ? 'Administrador' : 'Moderador' }}</p>
                                </div>
                            </div>
                            <div class="staff-count">
                                <div class="staff-count-value">{{ $staff->tickets_abertos_count }}</div>
                                <div class="staff-count-label">tickets abertos</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-activity">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p>Nenhum staff com tickets atribuídos</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2> Ações Rápidas</h2>
            
            <div class="actions-grid">
                <a href="{{ route('suporte.moderacao.index', ['filtro' => 'novos']) }}" class="action-card">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    <div class="action-card-title">Ver Novos</div>
                    <div class="action-card-count">{{ $stats['tickets_novos'] }} tickets</div>
                </a>

                <a href="{{ route('suporte.moderacao.index', ['filtro' => 'denuncias']) }}" class="action-card">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="action-card-title">Denúncias</div>
                    <div class="action-card-count">{{ $stats['denuncias_pendentes'] }} pendentes</div>
                </a>

                <a href="{{ route('suporte.moderacao.index', ['filtro' => 'meus']) }}" class="action-card">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <div class="action-card-title">Meus Tickets</div>
                    <div class="action-card-count">{{ $stats['meus_tickets'] }} atribuídos</div>
                </a>

                <a href="{{ route('suporte.moderacao.index', ['prioridade' => 'urgente']) }}" class="action-card">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <div class="action-card-title">Urgentes</div>
                    <div class="action-card-count">{{ $stats['urgentes'] }} tickets</div>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh a cada 2 minutos (opcional)
        // setTimeout(() => location.reload(), 120000);

        // Sistema de busca inteligente
        document.addEventListener('DOMContentLoaded', function() {
            const buscaInput = document.querySelector('input[name="busca"]');
            
            if (buscaInput) {
                // Adicionar dicas de busca
                buscaInput.addEventListener('focus', function() {
                    if (!this.value) {
                        this.placeholder = 'Ex: #1234, "problema de login", categoria:duvida, usuario:joao';
                    }
                });
                
                buscaInput.addEventListener('blur', function() {
                    if (!this.value) {
                        this.placeholder = 'Buscar por #número, assunto, usuário ou categoria...';
                    }
                });
                
                // Atalho de teclado: Ctrl+K para focar na busca
                document.addEventListener('keydown', function(e) {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                        e.preventDefault();
                        buscaInput.focus();
                        buscaInput.select();
                    }
                });
            }

            // Animação dos progress bars ao carregar
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        });
    </script>
</body>
</html>