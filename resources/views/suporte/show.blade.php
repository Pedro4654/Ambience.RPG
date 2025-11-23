<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ticket #{{ $ticket->numero_ticket }} - Ambience RPG</title>
    
    <!-- Three.js e GLTFLoader -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
    
    <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Back Button */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 24px;
            transition: opacity 0.2s;
        }

        .back-link:hover {
            opacity: 0.8;
        }

        .back-link svg {
            width: 20px;
            height: 20px;
        }

        /* Alerts */
        .alert {
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        .alert-warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #856404;
        }

        /* Grid Layout */
        .grid-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
        }

        /* Main Content */
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 40px;
        }

        /* Ticket Header */
        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            margin-bottom: 24px;
        }

        .ticket-title-section {
            flex: 1;
        }

        .ticket-number-status {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .ticket-number {
            font-size: 24px;
            font-weight: 700;
            color: #1a202c;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge.status-novo { background: #dbeafe; color: #1e40af; }
        .badge.status-analise { background: #fef3c7; color: #92400e; }
        .badge.status-resolvido, .badge.status-fechado { background: #d1fae5; color: #065f46; }
        .badge.status-spam { background: #fee2e2; color: #991b1b; }
        .badge.status-default { background: #e5e7eb; color: #374151; }

        .badge.priority-urgente { background: #fee2e2; color: #991b1b; }
        .badge.priority-alta { background: #ffedd5; color: #9a3412; }
        .badge.priority-normal { background: #dbeafe; color: #1e40af; }
        .badge.priority-baixa { background: #e5e7eb; color: #374151; }

        .ticket-title {
            font-size: 22px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 16px;
            line-height: 1.4;
        }

        .ticket-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
            color: #6b7280;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .meta-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Report Alert Card */
        .report-alert-card {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left: 4px solid #dc2626;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .report-alert-card svg {
            width: 28px;
            height: 28px;
            color: #dc2626;
            flex-shrink: 0;
        }

        .report-content {
            flex: 1;
        }

        .report-content h4 {
            font-size: 17px;
            font-weight: 700;
            color: #991b1b;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .reported-user-card {
            background: white;
            border-radius: 10px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .reported-user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #dc2626;
        }

        .reported-user-info {
            flex: 1;
        }

        .reported-user-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .reported-user-username {
            font-size: 14px;
            color: #6b7280;
        }

        /* Description */
        .description {
            font-size: 15px;
            line-height: 1.8;
            color: #374151;
            white-space: pre-wrap;
        }

        /* Attachments */
        .attachments-section {
            margin-top: 32px;
            padding-top: 32px;
            border-top: 2px solid #f3f4f6;
        }

        .attachments-title {
            font-size: 14px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 16px;
        }

        .attachments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        /* Anexo de Imagem */
        .attachment-item.image-attachment {
            display: flex;
            flex-direction: column;
            padding: 0;
            background: #f9fafb;
            border-radius: 10px;
            overflow: hidden;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
            cursor: pointer;
        }

        .attachment-item.image-attachment:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .attachment-image-preview {
            width: 100%;
            height: 160px;
            object-fit: cover;
            transition: opacity 0.2s;
        }

        .attachment-image-preview:hover {
            opacity: 0.9;
        }

        .attachment-image-info {
            padding: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .attachment-image-name {
            font-size: 12px;
            font-weight: 600;
            color: #1a202c;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
        }

        /* Anexo de Vídeo */
        .attachment-item.video-attachment {
            display: flex;
            flex-direction: column;
            padding: 0;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid #f59e0b;
        }

        .attachment-item.video-attachment:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.3);
        }

        .video-preview-container {
            position: relative;
            width: 100%;
            height: 160px;
            background: #000;
        }

        .video-preview {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .video-play-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background: rgba(245, 158, 11, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        .video-play-overlay svg {
            width: 30px;
            height: 30px;
            color: white;
            margin-left: 4px;
        }

        .video-info {
            padding: 12px;
        }

        .video-name {
            font-size: 13px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 6px;
        }

        .video-meta {
            font-size: 11px;
            color: #d97706;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .video-badge {
            background: #f59e0b;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Anexo PDF */
        .attachment-item.pdf-attachment {
            display: flex;
            flex-direction: column;
            padding: 0;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid #ef4444;
        }

        .attachment-item.pdf-attachment:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
        }

        .pdf-preview-icon {
            width: 100%;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            font-size: 56px;
        }

        /* Anexo de Texto */
        .attachment-item.text-attachment {
            display: flex;
            flex-direction: column;
            padding: 0;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid #22c55e;
        }

        .attachment-item.text-attachment:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.3);
        }

        .text-preview-icon {
            width: 100%;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            font-size: 56px;
        }

        /* Anexo de Documento Office */
        .attachment-item.doc-attachment {
            display: flex;
            flex-direction: column;
            padding: 0;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid #3b82f6;
        }

        .attachment-item.doc-attachment:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
        }

        .doc-preview-icon {
            width: 100%;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            font-size: 56px;
        }

        /* Anexo GLB */
        .attachment-item.glb-attachment {
            display: flex;
            flex-direction: column;
            padding: 0;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid #06b6d4;
        }

        .attachment-item.glb-attachment:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.3);
        }

        .glb-preview-icon {
            width: 100%;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
            font-size: 56px;
        }

        /* Info genérica de anexos */
        .attachment-info-generic {
            padding: 12px;
        }

        .attachment-name-generic {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .attachment-meta-generic {
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .attachment-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: white;
        }

        /* Anexo Normal */
        .attachment-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .attachment-item:not(.image-attachment):not(.glb-attachment):not(.video-attachment):not(.pdf-attachment):not(.text-attachment):not(.doc-attachment):hover {
            background: #f3f4f6;
            transform: translateY(-2px);
        }

        .attachment-item svg {
            width: 24px;
            height: 24px;
            color: #6b7280;
            flex-shrink: 0;
        }

        .attachment-info {
            flex: 1;
            min-width: 0;
        }

        .attachment-name {
            font-size: 13px;
            font-weight: 600;
            color: #1a202c;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .attachment-size {
            font-size: 11px;
            color: #6b7280;
        }

        .attachment-download-btn {
            padding: 6px 10px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: background 0.2s;
        }

        .attachment-download-btn:hover {
            background: #764ba2;
        }

        .attachment-download-btn svg {
            width: 12px;
            height: 12px;
        }

        /* Modal Universal */
        .universal-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            z-index: 10000;
            animation: fadeIn 0.3s ease;
        }

        .universal-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content-wrapper {
            position: relative;
            max-width: 95%;
            max-height: 95%;
            animation: zoomIn 0.3s ease;
        }

        @keyframes zoomIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .modal-close-btn {
            position: absolute;
            top: -50px;
            right: 0;
            background: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #1a202c;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            z-index: 10001;
        }

        .modal-close-btn:hover {
            background: #f3f4f6;
            transform: rotate(90deg);
        }

        .modal-image {
            max-width: 90vw;
            max-height: 85vh;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        }

        .modal-video {
            max-width: 90vw;
            max-height: 85vh;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        }

        .modal-pdf-viewer {
            width: 90vw;
            height: 85vh;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            overflow: auto;
        }

        .modal-text-viewer {
            width: 800px;
            max-width: 90vw;
            max-height: 85vh;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            overflow: auto;
            padding: 30px;
        }

        .modal-text-content {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
            white-space: pre-wrap;
            color: #1a202c;
        }

        .modal-info-bar {
            position: absolute;
            bottom: -60px;
            left: 0;
            right: 0;
            text-align: center;
            color: white;
            font-size: 14px;
        }

        .modal-download-btn {
            position: absolute;
            bottom: -55px;
            right: 0;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .modal-download-btn:hover {
            background: #764ba2;
        }

        /* Responses */
        .responses-section h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 20px;
        }

        .response-item {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .response-item.internal {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-left: 4px solid #f59e0b;
        }

        /* ========== MENSAGENS DENUNCIADAS ========== */
.report-messages-section {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border-left: 4px solid #3b82f6;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
}

.messages-title {
    font-size: 17px;
    font-weight: 700;
    color: #1e40af;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
}

.reported-messages-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.reported-message-card {
    background: white;
    border-radius: 10px;
    padding: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 2px solid #e5e7eb;
}

.reported-message-card.censored {
    border-color: #fbbf24;
    background: #fffbeb;
}

.reported-message-card.deleted {
    border-color: #ef4444;
    background: #fef2f2;
    opacity: 0.7;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.message-author-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #3b82f6;
}

.message-author-name {
    font-size: 15px;
    font-weight: 700;
    color: #1a202c;
    display: block;
}

.message-timestamp {
    font-size: 12px;
    color: #6b7280;
    display: block;
    margin-top: 2px;
}

.message-content {
    margin-bottom: 12px;
}

.message-text {
    font-size: 15px;
    line-height: 1.6;
    color: #374151;
    word-wrap: break-word;
    white-space: pre-wrap;
}

.message-text.original {
    background: #fef3c7;
    padding: 8px 12px;
    border-radius: 6px;
    margin-bottom: 8px;
}

.message-text.censored {
    background: #fee2e2;
    padding: 8px 12px;
    border-radius: 6px;
}

.message-flags {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    font-size: 13px;
}

.flag-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.flag-profanity { background: #fef3c7; color: #92400e; }
.flag-sexual { background: #fee2e2; color: #991b1b; }
.flag-porn { background: #fecaca; color: #7f1d1d; }
.flag-harassment { background: #fed7aa; color: #9a3412; }
.flag-swear { background: #fef3c7; color: #854d0e; }

.message-attachments {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #e5e7eb;
}

.message-attachment-image {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e5e7eb;
    transition: transform 0.2s;
}

.message-attachment-image:hover {
    transform: scale(1.05);
    border-color: #3b82f6;
}

.message-attachment-file {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    background: #f3f4f6;
    border-radius: 6px;
    text-decoration: none;
    color: #374151;
    font-size: 13px;
    font-weight: 500;
    transition: background 0.2s;
}

.message-attachment-file:hover {
    background: #e5e7eb;
    color: #1a202c;
}

        .response-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .response-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .response-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .response-author-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .response-author-name {
            font-size: 15px;
            font-weight: 700;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .staff-badge {
            padding: 4px 8px;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .response-date {
            font-size: 13px;
            color: #6b7280;
        }

        .internal-badge {
            padding: 6px 12px;
            background: #fef3c7;
            color: #92400e;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .response-content {
            font-size: 15px;
            line-height: 1.7;
            color: #374151;
            white-space: pre-wrap;
        }

        .empty-responses {
            background: #f9fafb;
            padding: 60px;
            border-radius: 12px;
            text-align: center;
            color: #9ca3af;
        }

        /* Reply Form */
        .reply-form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 32px;
        }

        .reply-form-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-textarea {
            width: 100%;
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            resize: vertical;
            min-height: 150px;
            transition: all 0.3s ease;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-warn {
            border: 2px solid #e0556b !important;
            background: #fff6f7 !important;
        }

        .moderation-warning {
            display: none;
            color: #e0556b;
            font-size: 0.85rem;
            margin-top: 4px;
            font-weight: 600;
        }

        .moderation-warning.show {
            display: block;
        }

        .form-textarea.input-warn {
            animation: shake 0.3s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .checkbox-label {
            font-size: 14px;
            color: #374151;
            cursor: pointer;
        }

        .file-input-wrapper {
            position: relative;
        }

        .file-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-submit {
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .closed-message {
            background: #f9fafb;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            color: #6b7280;
        }

        /* Sidebar */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .sidebar-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 28px;
        }

        .sidebar-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 20px;
        }

        .info-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .info-item dt {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .info-item dd {
            font-size: 14px;
            color: #1a202c;
        }

        /* Moderation Actions */
        .mod-form {
            margin-bottom: 16px;
        }

        .mod-form:last-child {
            margin-bottom: 0;
        }

        .mod-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .mod-select {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mod-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .assign-wrapper {
            display: flex;
            gap: 8px;
        }

        .assign-wrapper .mod-select {
            flex: 1;
        }

        .btn-assign {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-assign:hover {
            background: #764ba2;
        }

        .btn-action {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-close {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-reopen {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-close:hover, .btn-reopen:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        @media (max-width: 1200px) {
            .grid-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }

            .card, .sidebar-card, .reply-form-card {
                padding: 24px 20px;
            }

            .ticket-header {
                flex-direction: column;
            }

            .attachments-grid {
                grid-template-columns: 1fr;
            }

            .modal-close-btn {
                top: 10px;
                right: 10px;
            }

            .modal-info-bar, .modal-download-btn {
                bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <a href="{{ auth()->user()->isStaff() ? route('suporte.moderacao.index') : route('suporte.index') }}" class="back-link">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Voltar
        </a>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                </svg>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Grid Layout -->
        <div class="grid-layout">
            <!-- Main Content -->
            <div class="main-content">
                <!-- Ticket Card -->
                <div class="card">
                    <div class="ticket-header">
                        <div class="ticket-title-section">
                            <div class="ticket-number-status">
                                <h1 class="ticket-number">#{{ $ticket->numero_ticket }}</h1>
                                <span class="badge 
                                    @if($ticket->status === 'novo') status-novo
                                    @elseif($ticket->status === 'em_analise') status-analise
                                    @elseif($ticket->status === 'resolvido' || $ticket->status === 'fechado') status-resolvido
                                    @elseif($ticket->status === 'spam') status-spam
                                    @else status-default
                                    @endif">
                                    {{ $ticket->getStatusLabel() }}
                                </span>
                            </div>

                            <h2 class="ticket-title">{{ $ticket->assunto }}</h2>

                            <div class="ticket-meta">
                                <div class="meta-item">
    @if($ticket->usuario->avatar_url)
        <img src="{{ str_starts_with($ticket->usuario->avatar_url, 'http') ? $ticket->usuario->avatar_url : Storage::url($ticket->usuario->avatar_url) }}" 
            alt="{{ $ticket->usuario->username }}" 
            class="meta-avatar"
            onerror="this.src='{{ asset('images/default-avatar.png') }}'">
    @else
        <img src="{{ asset('images/default-avatar.png') }}" 
            alt="{{ $ticket->usuario->username }}" 
            class="meta-avatar">
    @endif
    <span>{{ $ticket->usuario->username }}</span>
</div>
                                <div class="meta-item">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $ticket->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }} (GMT-3)
                                </div>
                                @if(auth()->user()->isStaff())
                                    <div class="meta-item">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        {{ $ticket->visualizacoes }} visualizações
                                    </div>
                                @endif
                            </div>
                        </div>

                        <span class="badge 
                            @if($ticket->prioridade === 'urgente') priority-urgente
                            @elseif($ticket->prioridade === 'alta') priority-alta
                            @elseif($ticket->prioridade === 'normal') priority-normal
                            @else priority-baixa
                            @endif">
                            {{ $ticket->getPrioridadeLabel() }}
                        </span>
                    </div>

                    @if($ticket->ehDenuncia() && $ticket->usuarioDenunciado)
    <div class="report-alert-card">
        <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <div class="report-content">
            <h4>⚠️ Denúncia de Usuário</h4>
            <div class="reported-user-card">
                @if($ticket->usuarioDenunciado->avatar_url)
                    <img src="{{ str_starts_with($ticket->usuarioDenunciado->avatar_url, 'http') ? $ticket->usuarioDenunciado->avatar_url : Storage::url($ticket->usuarioDenunciado->avatar_url) }}" 
                         alt="{{ $ticket->usuarioDenunciado->username }}" 
                         class="reported-user-avatar"
                         onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                @else
                    <img src="{{ asset('images/default-avatar.png') }}" 
                         alt="{{ $ticket->usuarioDenunciado->username }}" 
                         class="reported-user-avatar">
                @endif
                <div class="reported-user-info">
                    <div class="reported-user-name">
                        @if($ticket->usuarioDenunciado->nickname)
                            {{ $ticket->usuarioDenunciado->nickname }}
                        @else
                            {{ $ticket->usuarioDenunciado->username }}
                        @endif
                    </div>
                    <div class="reported-user-username">&#64;{{ $ticket->usuarioDenunciado->username }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ SEÇÃO DE MENSAGENS DENUNCIADAS (DENTRO DO MESMO IF) --}}
    @if($ticket->mensagensAnexadas->unique('mensagem_id')->count() > 0)
        <div class="report-messages-section">
            <h4 class="messages-title">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="vertical-align: middle; margin-right: 8px;">
                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                </svg>
💬 Mensagens Denunciadas ({{ $ticket->mensagensAnexadas->unique('mensagem_id')->count() }})
            </h4>
            
            <div class="reported-messages-container">
                @foreach($ticket->mensagensAnexadas->unique('mensagem_id') as $anexada)
                    @php
                        $mensagem = $anexada->mensagem;
                    @endphp
                    
                    <div class="reported-message-card {{ $mensagem->censurada ? 'censored' : '' }} {{ $mensagem->deletada ? 'deleted' : '' }}">
                        {{-- Header da mensagem --}}
                        <div class="message-header">
    <div class="message-author-info">
        @if($mensagem->usuario->avatar_url)
            <img src="{{ str_starts_with($mensagem->usuario->avatar_url, 'http') ? $mensagem->usuario->avatar_url : Storage::url($mensagem->usuario->avatar_url) }}" alt="{{ $mensagem->usuario->username }}" class="message-avatar" onerror="this.src='{{ asset('images/default-avatar.png') }}'">
        @else
            <img src="{{ asset('images/default-avatar.png') }}" alt="{{ $mensagem->usuario->username }}" class="message-avatar">
        @endif
        <div>
            <span class="message-author-name">{{ $mensagem->usuario->username }}</span>
            <span class="message-timestamp">{{ $mensagem->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</span>
        </div>
    </div>
                            
                            @if($mensagem->deletada)
                                <span class="badge" style="background: #fee2e2; color: #991b1b;">Deletada</span>
                            @elseif($mensagem->censurada)
                                <span class="badge" style="background: #fef3c7; color: #92400e;">Censurada</span>
                            @endif
                        </div>
                        
                        {{-- Conteúdo da mensagem --}}
                        <div class="message-content">
    @if($mensagem->deletada)
        <em style="color: #991b1b;">[Mensagem deletada]</em>
    @else
        @if($mensagem->censurada && auth()->user()->isStaff())
            <div class="message-text original"><strong>Original:</strong> {{ $mensagem->mensagem_original }}</div>
            <div class="message-text censored"><strong>Censurada:</strong> {{ $mensagem->mensagem }}</div>
        @else
            <div class="message-text">{{ $mensagem->mensagem_original ?? $mensagem->mensagem }}</div>
        @endif
                                
                                {{-- Flags detectadas --}}
                                @if($mensagem->censurada && !empty($mensagem->flags_detectadas))
                                    <div class="message-flags">
                                        <strong>Flags:</strong>
                                        @foreach($mensagem->flags_detectadas as $flag)
                                            <span class="flag-badge flag-{{ $flag }}">{{ $flag }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                        
                        {{-- Anexos da mensagem --}}
                        @if($mensagem->anexos->count() > 0)
                            <div class="message-attachments">
                                @foreach($mensagem->anexos as $anexo)
                                    @if($anexo->ehImagem())
                                        <img src="{{ $anexo->getUrl() }}" 
                                             alt="{{ $anexo->nome_original }}"
                                             class="message-attachment-image"
                                             onclick="openImageModal('{{ $anexo->getUrl() }}', '{{ addslashes($anexo->nome_original) }}', '{{ $anexo->getTamanhoFormatado() }}')"
                                             style="cursor: pointer;">
                                    @else
                                        <a href="{{ route('suporte.anexos.download', $anexo->id) }}" 
                                           class="message-attachment-file"
                                           download>
                                            📎 {{ $anexo->nome_original }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endif

{{-- ✅ DESCRIÇÃO (FORA DO BLOCO DE DENÚNCIA) --}}
<div class="description">{{ $ticket->descricao }}</div>

                    @if($ticket->anexos->where('resposta_id', null)->count() > 0)
                        <div class="attachments-section">
                            <h3 class="attachments-title">📎 Anexos</h3>
                            <div class="attachments-grid">
                                @foreach($ticket->anexos->where('resposta_id', null) as $anexo)
                                    @php
                                        $extensao = strtolower(pathinfo($anexo->nome_original, PATHINFO_EXTENSION));
                                        $urlAnexo = route('suporte.anexos.download', $anexo->id);
                                        
                                        $isImagem = in_array($extensao, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);
                                        $isVideo = in_array($extensao, ['mp4', 'webm', 'mov', 'avi', 'mkv']);
                                        $isPDF = $extensao === 'pdf';
                                        $isTexto = in_array($extensao, ['txt', 'log', 'md']);
                                        $isGLB = $extensao === 'glb';
                                        $isWord = in_array($extensao, ['doc', 'docx']);
                                        $isExcel = in_array($extensao, ['xls', 'xlsx', 'csv']);
                                        $isPowerPoint = in_array($extensao, ['ppt', 'pptx']);
                                    @endphp

                                    @if($isImagem)
                                        <div class="attachment-item image-attachment" 
                                             onclick="openImageModal('{{ $urlAnexo }}', '{{ addslashes($anexo->nome_original) }}', '{{ $anexo->getTamanhoFormatado() }}')">
                                            <img src="{{ $urlAnexo }}" 
                                                 alt="{{ $anexo->nome_original }}" 
                                                 class="attachment-image-preview">
                                            <div class="attachment-image-info">
                                                <span class="attachment-image-name" title="{{ $anexo->nome_original }}">{{ $anexo->nome_original }}</span>
                                                <a href="{{ $urlAnexo }}" class="attachment-download-btn" download onclick="event.stopPropagation()">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Baixar
                                                </a>
                                            </div>
                                        </div>
                                    @elseif($isVideo)
                                        <div class="attachment-item video-attachment" 
                                             onclick="openVideoModal('{{ $urlAnexo }}', '{{ addslashes($anexo->nome_original) }}')">
                                            <div class="video-preview-container">
                                                <video class="video-preview" preload="metadata">
                                                    <source src="{{ $urlAnexo }}#t=0.1" type="{{ $anexo->tipo_mime }}">
                                                </video>
                                                <div class="video-play-overlay">
                                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="video-info">
                                                <div class="video-name">{{ Str::limit($anexo->nome_original, 30) }}</div>
                                                <div class="video-meta">
                                                    <span>{{ $anexo->getTamanhoFormatado() }}</span>
                                                    <span class="video-badge">Vídeo</span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($isPDF)
                                        <div class="attachment-item pdf-attachment" 
                                             onclick="openPDFModal('{{ $urlAnexo }}', '{{ addslashes($anexo->nome_original) }}')">
                                            <div class="pdf-preview-icon">📄</div>
                                            <div class="attachment-info-generic">
                                                <div class="attachment-name-generic" style="color: #991b1b;">{{ Str::limit($anexo->nome_original, 30) }}</div>
                                                <div class="attachment-meta-generic" style="color: #dc2626;">
                                                    <span>{{ $anexo->getTamanhoFormatado() }}</span>
                                                    <span class="attachment-badge" style="background: #ef4444;">PDF</span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($isTexto)
                                        <div class="attachment-item text-attachment" 
                                             onclick="openTextModal('{{ $urlAnexo }}', '{{ addslashes($anexo->nome_original) }}')">
                                            <div class="text-preview-icon">📝</div>
                                            <div class="attachment-info-generic">
                                                <div class="attachment-name-generic" style="color: #15803d;">{{ Str::limit($anexo->nome_original, 30) }}</div>
                                                <div class="attachment-meta-generic" style="color: #16a34a;">
                                                    <span>{{ $anexo->getTamanhoFormatado() }}</span>
                                                    <span class="attachment-badge" style="background: #22c55e;">Texto</span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($isWord)
                                        <div class="attachment-item doc-attachment" 
                                             onclick="window.open('{{ $urlAnexo }}', '_blank')">
                                            <div class="doc-preview-icon">📘</div>
                                            <div class="attachment-info-generic">
                                                <div class="attachment-name-generic" style="color: #1e40af;">{{ Str::limit($anexo->nome_original, 30) }}</div>
                                                <div class="attachment-meta-generic" style="color: #2563eb;">
                                                    <span>{{ $anexo->getTamanhoFormatado() }}</span>
                                                    <span class="attachment-badge" style="background: #3b82f6;">Word</span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($isExcel)
                                        <div class="attachment-item doc-attachment" 
                                             onclick="window.open('{{ $urlAnexo }}', '_blank')">
                                            <div class="doc-preview-icon">📊</div>
                                            <div class="attachment-info-generic">
                                                <div class="attachment-name-generic" style="color: #15803d;">{{ Str::limit($anexo->nome_original, 30) }}</div>
                                                <div class="attachment-meta-generic" style="color: #16a34a;">
                                                    <span>{{ $anexo->getTamanhoFormatado() }}</span>
                                                    <span class="attachment-badge" style="background: #22c55e;">Excel</span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($isPowerPoint)
                                        <div class="attachment-item doc-attachment" 
                                             onclick="window.open('{{ $urlAnexo }}', '_blank')">
                                            <div class="doc-preview-icon">📽️</div>
                                            <div class="attachment-info-generic">
                                                <div class="attachment-name-generic" style="color: #c2410c;">{{ Str::limit($anexo->nome_original, 30) }}</div>
                                                <div class="attachment-meta-generic" style="color: #ea580c;">
                                                    <span>{{ $anexo->getTamanhoFormatado() }}</span>
                                                    <span class="attachment-badge" style="background: #f97316;">PowerPoint</span>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($isGLB)
                                        <div class="attachment-item glb-attachment" 
                                             onclick="openGLBModal('{{ $urlAnexo }}', '{{ addslashes($anexo->nome_original) }}', {{ $anexo->id }})">
                                            <div class="glb-preview-icon">🎲</div>
                                            <div class="attachment-info-generic">
                                                <div class="attachment-name-generic" style="color: #0e7490;">{{ Str::limit($anexo->nome_original, 30) }}</div>
                                                <div class="attachment-meta-generic" style="color: #0891b2;">
                                                    <span>{{ $anexo->getTamanhoFormatado() }}</span>
                                                    <span class="attachment-badge" style="background: #06b6d4;">Modelo 3D</span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ $urlAnexo }}" class="attachment-item" download>
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <div class="attachment-info">
                                                <div class="attachment-name">{{ $anexo->nome_original }}</div>
                                                <div class="attachment-size">{{ $anexo->getTamanhoFormatado() }}</div>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Responses -->
                <div class="responses-section">
                    <h3>💬 Respostas/Comentários</h3>
                    
                    @forelse($ticket->respostas as $resposta)
    <div class="response-item {{ $resposta->ehInterna() ? 'internal' : '' }}">
        <div class="response-header">
            <div class="response-author">
                @if($resposta->usuario->avatar_url)
                    <img src="{{ str_starts_with($resposta->usuario->avatar_url, 'http') ? $resposta->usuario->avatar_url : Storage::url($resposta->usuario->avatar_url) }}" 
                        alt="{{ $resposta->usuario->username }}" 
                        class="response-avatar"
                        onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                @else
                    <img src="{{ asset('images/default-avatar.png') }}" 
                        alt="{{ $resposta->usuario->username }}" 
                        class="response-avatar">
                @endif
                <div class="response-author-info">
                    <div class="response-author-name">
                        {{ $resposta->usuario->username }}
                        @if($resposta->usuario->isStaff())
                            <span class="staff-badge">
                                {{ $resposta->usuario->nivel_usuario === 'admin' ? 'Admin' : 'Mod' }}
                            </span>
                        @endif
                    </div>
                    <div class="response-date">
                        {{ $resposta->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }} (GMT-3)
                        @if($resposta->foiEditada())
                            <span style="font-style: italic;">(editado)</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($resposta->ehInterna())
                <span class="internal-badge">Nota Interna</span>
            @endif
        </div>

        <div class="response-content">{{ $resposta->mensagem }}</div>

                            @if($resposta->anexos->count() > 0)
                                <div class="attachments-section" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                                    <div class="attachments-grid">
                                        @foreach($resposta->anexos as $anexo)
                                            @php
                                                $extensao = strtolower(pathinfo($anexo->nome_original, PATHINFO_EXTENSION));
                                                $urlAnexo = route('suporte.anexos.download', $anexo->id);
                                                
                                                $isImagem = in_array($extensao, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);
                                                $isVideo = in_array($extensao, ['mp4', 'webm', 'mov', 'avi', 'mkv']);
                                                $isPDF = $extensao === 'pdf';
                                                $isTexto = in_array($extensao, ['txt', 'log', 'md']);
                                                $isGLB = $extensao === 'glb';
                                            @endphp

                                            @if($isImagem)
                                                <div class="attachment-item image-attachment" 
                                                     onclick="openImageModal('{{ $urlAnexo }}', '{{ addslashes($anexo->nome_original) }}', '{{ $anexo->getTamanhoFormatado() }}')">
                                                    <img src="{{ $urlAnexo }}" 
                                                         alt="{{ $anexo->nome_original }}" 
                                                         class="attachment-image-preview">
                                                    <div class="attachment-image-info">
                                                        <span class="attachment-image-name">{{ $anexo->nome_original }}</span>
                                                        <a href="{{ $urlAnexo }}" class="attachment-download-btn" download onclick="event.stopPropagation()">
                                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                            </svg>
                                                            Baixar
                                                        </a>
                                                    </div>
                                                </div>
                                            @elseif($isVideo)
                                                <div class="attachment-item video-attachment" 
                                                     onclick="openVideoModal('{{ $urlAnexo }}', '{{ addslashes($anexo->nome_original) }}')">
                                                    <div class="video-preview-container">
                                                        <video class="video-preview" preload="metadata">
                                                            <source src="{{ $urlAnexo }}#t=0.1">
                                                        </video>
                                                        <div class="video-play-overlay">
                                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="video-info">
                                                        <div class="video-name">{{ Str::limit($anexo->nome_original, 30) }}</div>
                                                        <div class="video-meta">
                                                            <span>{{ $anexo->getTamanhoFormatado() }}</span>
                                                            <span class="video-badge">Vídeo</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($isPDF)
                                                <div class="attachment-item pdf-attachment" 
                                                     onclick="openPDFModal('{{ $urlAnexo }}', '{{ addslashes($anexo->nome_original) }}')">
                                                    <div class="pdf-preview-icon">📄</div>
                                                    <div class="attachment-info-generic">
                                                        <div class="attachment-name-generic" style="color: #991b1b;">{{ Str::limit($anexo->nome_original, 30) }}</div>
                                                        <div class="attachment-meta-generic" style="color: #dc2626;">
                                                            <span>{{ $anexo->getTamanhoFormatado() }}</span>
                                                            <span class="attachment-badge" style="background: #ef4444;">PDF</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($isTexto)
                                                <div class="attachment-item text-attachment" 
                                                     onclick="openTextModal('{{ $urlAnexo }}', '{{ addslashes($anexo->nome_original) }}')">
                                                    <div class="text-preview-icon">📝</div>
                                                    <div class="attachment-info-generic">
                                                        <div class="attachment-name-generic" style="color: #15803d;">{{ Str::limit($anexo->nome_original, 30) }}</div>
                                                        <div class="attachment-meta-generic" style="color: #16a34a;">
                                                            <span>{{ $anexo->getTamanhoFormatado() }}</span>
                                                            <span class="attachment-badge" style="background: #22c55e;">Texto</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($isGLB)
                                                <div class="attachment-item glb-attachment" 
                                                     onclick="openGLBModal('{{ $urlAnexo }}', '{{ addslashes($anexo->nome_original) }}', {{ $anexo->id }})">
                                                    <div class="glb-preview-icon">🎲</div>
                                                    <div class="attachment-info-generic">
                                                        <div class="attachment-name-generic" style="color: #0e7490;">{{ Str::limit($anexo->nome_original, 30) }}</div>
                                                        <div class="attachment-meta-generic" style="color: #0891b2;">
                                                            <span>{{ $anexo->getTamanhoFormatado() }}</span>
                                                            <span class="attachment-badge" style="background: #06b6d4;">3D</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <a href="{{ $urlAnexo }}" class="attachment-item" download>
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                    </svg>
                                                    <div class="attachment-info">
                                                        <div class="attachment-name">{{ $anexo->nome_original }}</div>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-responses">
                            <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 16px; color: #cbd5e0;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p>Nenhuma resposta/comentário ainda</p>
                        </div>
                    @endforelse
                </div>

                <!-- Reply Form -->
                @if($ticket->estaAberto())
                    <div class="reply-form-card">
                        <h3 class="reply-form-title">✏️ Adicionar Comentário/Resposta</h3>
                        
                        <form id="replyForm" action="{{ route('suporte.responder', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="mensagem" class="form-label">Mensagem *</label>
                                <textarea name="mensagem" 
                                    id="mensagem"
                                    required
                                    minlength="10"
                                    maxlength="5000"
                                    placeholder="Digite sua mensagem aqui..."
                                    class="form-textarea">{{ old('mensagem') }}</textarea>
                                <small class="moderation-warning" id="mensagem-warning">Conteúdo inapropriado detectado</small>
                            </div>

                            @if(auth()->user()->isStaff())
                                <div class="form-group">
                                    <div class="checkbox-wrapper">
                                        <input type="checkbox" name="interno" value="1" id="interno">
                                        <label for="interno" class="checkbox-label">Nota interna (apenas staff)</label>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="anexos" class="form-label">Anexos (opcional)</label>
                                <input type="file" name="anexos[]" id="anexos" multiple class="file-input" 
                                       accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.zip,.glb,.mp4,.webm,.mov,.avi,.xls,.xlsx,.ppt,.pptx,.csv">
                                <small style="display: block; margin-top: 6px; color: #6b7280; font-size: 12px;">
                                    Formatos aceitos: Imagens, Vídeos, PDFs, Documentos Office, Texto, ZIP, Modelos 3D (GLB). Máximo 100MB por arquivo.
                                </small>
                            </div>

                            <button type="submit" class="btn-submit" id="submitBtn">
                                🚀 Enviar Resposta
                            </button>
                        </form>
                    </div>
                @else
                    <div class="closed-message">
                        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 16px; color: #cbd5e0;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <p>Este ticket está fechado e não aceita mais respostas.</p>
                        @if(auth()->user()->isStaff())
                            <p style="margin-top: 12px; font-size: 14px;">Você pode reabrir o ticket usando o painel lateral.</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Ticket Info -->
                <div class="sidebar-card">
                    <h3>ℹ️ Informações</h3>
                    
                    <dl class="info-list">
                        <div class="info-item">
                            <dt>Categoria</dt>
                            <dd>{{ $ticket->getCategoriaLabel() }}</dd>
                        </div>

                        <div class="info-item">
                            <dt>Status</dt>
                            <dd>{{ $ticket->getStatusLabel() }}</dd>
                        </div>

                        <div class="info-item">
                            <dt>Prioridade</dt>
                            <dd>{{ $ticket->getPrioridadeLabel() }}</dd>
                        </div>

                        @if($ticket->atribuidoA)
                            <div class="info-item">
                                <dt>Atribuído a</dt>
                                <dd>{{ $ticket->atribuidoA->username }}</dd>
                            </div>
                        @endif

                        <div class="info-item">
                            <dt>Criado em</dt>
                            <dd>{{ $ticket->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }} (GMT-3)</dd>
                        </div>

                        @if($ticket->data_fechamento)
                            <div class="info-item">
                                <dt>Fechado em</dt>
                                <dd>{{ $ticket->data_fechamento->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }} (GMT-3)</dd>
                            </div>
                        @endif

                        @if($ticket->respostas->count() > 0)
                            <div class="info-item">
                                <dt>Total de Respostas</dt>
                                <dd>{{ $ticket->respostas->count() }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                   <!-- Ações Rápidas de Usuário (se for denúncia) -->
                @if(auth()->user()->isStaff() && $ticket->ehDenuncia() && $ticket->usuarioDenunciado)
                    <div class="sidebar-card" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-left: 4px solid #ef4444;">
                        <h3 style="color: #991b1b;">👤 Ações do Usuário Denunciado</h3>
                        
                        <a href="{{ route('moderacao.usuarios.show', $ticket->usuarioDenunciado->id) }}" class="btn-action" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; width: 100%; padding: 12px; margin-bottom: 12px; text-align: center; text-decoration: none; border-radius: 10px; display: inline-block; font-weight: 600;">
                            👁️ Ver Perfil Completo
                        </a>

                        @if(!$ticket->usuarioDenunciado->warning_ativo && !$ticket->usuarioDenunciado->ban_tipo)
                            <button onclick="openPunishmentModal('warning', {{ $ticket->usuarioDenunciado->id }}, {{ $ticket->id }})" class="btn-action" style="width: 100%; padding: 12px; background: #f59e0b; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; margin-bottom: 8px;">
                                ⚠️ Aplicar Warning
                            </button>

                            <button onclick="openPunishmentModal('ban-temp', {{ $ticket->usuarioDenunciado->id }}, {{ $ticket->id }})" class="btn-action" style="width: 100%; padding: 12px; background: #ef4444; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; margin-bottom: 8px;">
                                🚫 Ban Temporário
                            </button>

                            <button onclick="openPunishmentModal('perma-ban', {{ $ticket->usuarioDenunciado->id }}, {{ $ticket->id }})" class="btn-action" style="width: 100%; padding: 12px; background: #991b1b; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">
                                ⛔ Ban Permanente
                            </button>
                        @else
                            <div style="background: #fef3c7; padding: 16px; border-radius: 10px; font-size: 14px; color: #92400e; text-align: center;">
                                ⚠️ Usuário já possui punição ativa
                            </div>
                        @endif

                        <div style="margin-top: 16px; padding-top: 16px; border-top: 2px solid #fee2e2;">
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">
                                <strong>Tickets criados:</strong> {{ $ticket->usuarioDenunciado->tickets_count ?? 0 }}<br>
                                <strong>Denúncias recebidas:</strong> {{ $ticket->usuarioDenunciado->denuncias_recebidas_count ?? 0 }}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Staff Actions -->
                @if(auth()->user()->isStaff())
                    <div class="sidebar-card">
                        <h3>⚙️ Ações de Moderação</h3>
                        
                        <!-- Atribuir -->
                        <form action="{{ route('suporte.moderacao.atribuir', $ticket->id) }}" method="POST" class="mod-form">
                            @csrf
                            <label for="staff_id" class="mod-label">Atribuir a</label>
                            <div class="assign-wrapper">
                                <select name="staff_id" id="staff_id" required class="mod-select">
                                    <option value="">Selecione...</option>
                                    @foreach(\App\Models\Usuario::whereIn('nivel_usuario', ['moderador', 'admin'])->where('status', 'ativo')->orderBy('username')->get() as $staff)
                                        <option value="{{ $staff->id }}" {{ $ticket->atribuido_a == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->username }} ({{ $staff->nivel_usuario === 'admin' ? 'Admin' : 'Mod' }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn-assign">OK</button>
                            </div>
                        </form>

                        <!-- Alterar Status -->
                        <form action="{{ route('suporte.moderacao.status', $ticket->id) }}" method="POST" class="mod-form">
                            @csrf
                            <label for="status" class="mod-label">Alterar Status</label>
                            <select name="status" id="status" onchange="this.form.submit()" class="mod-select">
                                <option value="novo" {{ $ticket->status == 'novo' ? 'selected' : '' }}>Novo</option>
                                <option value="em_analise" {{ $ticket->status == 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                                <option value="aguardando_resposta" {{ $ticket->status == 'aguardando_resposta' ? 'selected' : '' }}>Aguardando Resposta</option>
                                <option value="resolvido" {{ $ticket->status == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                                <option value="fechado" {{ $ticket->status == 'fechado' ? 'selected' : '' }}>Fechado</option>
                                <option value="spam" {{ $ticket->status == 'spam' ? 'selected' : '' }}>Spam</option>
                            </select>
                        </form>

                        <!-- Alterar Prioridade -->
                        <form action="{{ route('suporte.moderacao.prioridade', $ticket->id) }}" method="POST" class="mod-form">
                            @csrf
                            <label for="prioridade" class="mod-label">Alterar Prioridade</label>
                            <select name="prioridade" id="prioridade" onchange="this.form.submit()" class="mod-select">
                                <option value="baixa" {{ $ticket->prioridade == 'baixa' ? 'selected' : '' }}>Baixa</option>
                                <option value="normal" {{ $ticket->prioridade == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="alta" {{ $ticket->prioridade == 'alta' ? 'selected' : '' }}>Alta</option>
                                <option value="urgente" {{ $ticket->prioridade == 'urgente' ? 'selected' : '' }}>Urgente</option>
                            </select>
                        </form>

                        <!-- Fechar/Reabrir -->
                        @if($ticket->estaAberto())
                            <form action="{{ route('suporte.moderacao.fechar', $ticket->id) }}" method="POST" class="mod-form">
                                @csrf
                                <button type="submit" class="btn-action btn-close" onclick="return confirm('Tem certeza que deseja fechar este ticket?')">
                                    ✅ Fechar Ticket
                                </button>
                            </form>
                        @else
                            <form action="{{ route('suporte.moderacao.reabrir', $ticket->id) }}" method="POST" class="mod-form">
                                @csrf
                                <button type="submit" class="btn-action btn-reopen" onclick="return confirm('Tem certeza que deseja reabrir este ticket?')">
                                    🔓 Reabrir Ticket
                                </button>
                            </form>
                        @endif

                        <!-- Marcar como Spam -->
                        @if($ticket->status !== 'spam')
                            <form action="{{ route('suporte.moderacao.marcar-spam', $ticket->id) }}" method="POST" class="mod-form">
                                @csrf
                                <button type="submit" class="btn-action" style="background: #dc2626; color: white;" onclick="return confirm('Tem certeza que deseja marcar este ticket como SPAM?')">
                                    🚫 Marcar como Spam
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Universal -->
    <div id="universalModal" class="universal-modal" onclick="closeModal(event)">
        <div class="modal-content-wrapper">
            <button class="modal-close-btn" onclick="closeModal(event)">×</button>
            <div id="modalContentContainer"></div>
            <div class="modal-info-bar" id="modalInfoBar"></div>
            <a id="modalDownloadBtn" class="modal-download-btn" href="" download style="display: none;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Baixar
            </a>
        </div>
    </div>

    <!-- GLB Viewer Modal -->
    @include('partials.glb-viewer-modal')

    <!-- Scripts -->
    <script src="https://unpkg.com/nsfwjs@2.4.2/dist/nsfwjs.min.js"></script>
    <script src="{{ asset('js/nsfw-detector.js') }}"></script>
    <script src="{{ asset('js/moderation.js') }}" defer></script>
    <script src="{{ asset('js/glb-viewer.js') }}"></script>
    
    <script>
    // ==================== MODAL SYSTEM ====================
    const modal = document.getElementById('universalModal');
    const modalContainer = document.getElementById('modalContentContainer');
    const modalInfoBar = document.getElementById('modalInfoBar');
    const modalDownloadBtn = document.getElementById('modalDownloadBtn');

    function openImageModal(imageUrl, imageName, imageSize) {
        modalContainer.innerHTML = `<img src="${imageUrl}" alt="${imageName}" class="modal-image">`;
        modalInfoBar.textContent = `${imageName} - ${imageSize}`;
        modalDownloadBtn.href = imageUrl;
        modalDownloadBtn.download = imageName;
        modalDownloadBtn.style.display = 'inline-flex';
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function openVideoModal(videoUrl, videoName) {
        modalContainer.innerHTML = `
            <video class="modal-video" controls autoplay>
                <source src="${videoUrl}" type="video/mp4">
                Seu navegador não suporta vídeos.
            </video>
        `;
        modalInfoBar.textContent = videoName;
        modalDownloadBtn.href = videoUrl;
        modalDownloadBtn.download = videoName;
        modalDownloadBtn.style.display = 'inline-flex';
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function openPDFModal(pdfUrl, pdfName) {
        modalContainer.innerHTML = `
            <div class="modal-pdf-viewer">
                <canvas id="pdfCanvas"></canvas>
                <div style="text-align: center; padding: 20px;">
                    <button onclick="changePDFPage(-1)" style="padding: 10px 20px; margin: 0 10px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer;">◀ Anterior</button>
                    <span id="pdfPageInfo" style="margin: 0 20px; font-weight: 600;">Página 1</span>
                    <button onclick="changePDFPage(1)" style="padding: 10px 20px; margin: 0 10px; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer;">Próxima ▶</button>
                </div>
            </div>
        `;
        modalInfoBar.textContent = pdfName;
        modalDownloadBtn.href = pdfUrl;
        modalDownloadBtn.download = pdfName;
        modalDownloadBtn.style.display = 'inline-flex';
        
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        loadPDF(pdfUrl);
    }

    // PDF.js variables
    let pdfDoc = null;
    let currentPage = 1;
    let totalPages = 0;

    async function loadPDF(url) {
        try {
            const loadingTask = pdfjsLib.getDocument(url);
            pdfDoc = await loadingTask.promise;
            totalPages = pdfDoc.numPages;
            renderPage(currentPage);
        } catch (error) {
            console.error('Erro ao carregar PDF:', error);
            modalContainer.innerHTML = '<div style="color: white; padding: 40px; text-align: center;">Erro ao carregar PDF</div>';
        }
    }

    async function renderPage(pageNum) {
        const page = await pdfDoc.getPage(pageNum);
        const canvas = document.getElementById('pdfCanvas');
        const context = canvas.getContext('2d');
        
        const viewport = page.getViewport({ scale: 1.5 });
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        
        await page.render({ canvasContext: context, viewport: viewport }).promise;
        
        document.getElementById('pdfPageInfo').textContent = `Página ${pageNum} de ${totalPages}`;
    }

    function changePDFPage(delta) {
        currentPage += delta;
        if (currentPage < 1) currentPage = 1;
        if (currentPage > totalPages) currentPage = totalPages;
        renderPage(currentPage);
    }

    function openTextModal(textUrl, textName) {
        fetch(textUrl)
            .then(response => response.text())
            .then(text => {
                modalContainer.innerHTML = `
                    <div class="modal-text-viewer">
                        <h2 style="margin-bottom: 20px; color: #1a202c; border-bottom: 2px solid #e5e7eb; padding-bottom: 12px;">${textName}</h2>
                        <pre class="modal-text-content">${escapeHtml(text)}</pre>
                    </div>
                `;
                modalInfoBar.textContent = textName;
                modalDownloadBtn.href = textUrl;
                modalDownloadBtn.download = textName;
                modalDownloadBtn.style.display = 'inline-flex';
                
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            })
            .catch(error => {
                console.error('Erro ao carregar arquivo de texto:', error);
                modalContainer.innerHTML = '<div style="color: white; padding: 40px; text-align: center;">Erro ao carregar arquivo</div>';
            });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function closeModal(event) {
        if (event.target.id === 'universalModal' || event.target.classList.contains('modal-close-btn')) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            modalContainer.innerHTML = '';
            modalInfoBar.textContent = '';
            modalDownloadBtn.style.display = 'none';
            
            // Parar vídeos
            const videos = modalContainer.querySelectorAll('video');
            videos.forEach(video => {
                video.pause();
                video.src = '';
            });
            
            // Reset PDF
            pdfDoc = null;
            currentPage = 1;
            totalPages = 0;
        }
    }

    // Fechar com ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            if (modal.classList.contains('active')) {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
                modalContainer.innerHTML = '';
            }
        }
    });

    // ==================== MODERATION SYSTEM ====================
    document.addEventListener('DOMContentLoaded', async function() {
        const form = document.getElementById('replyForm');
        const mensagemInput = document.getElementById('mensagem');
        const submitBtn = document.getElementById('submitBtn');

        if (!form || !mensagemInput || !submitBtn) {
            console.warn('Formulário de resposta não encontrado');
            return;
        }

        // Verificar se sistema de moderação está disponível
        if (!window.Moderation) {
            console.warn('⚠️ Sistema de moderação não disponível');
            setupBasicValidation();
            return;
        }

        try {
            // Inicializar sistema de moderação
            const state = await window.Moderation.init({
                csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                endpoint: '/moderate',
                debounceMs: 120
            });

            console.log('🛡️ Sistema de moderação inicializado:', state);

            // Função para aplicar avisos visuais
            function applyWarning(selector, res) {
                const el = document.querySelector(selector);
                if (!el) return;

                const warnId = selector.replace('#', '') + '-warning';
                let warn = document.getElementById(warnId);

                if (res && res.inappropriate) {
                    el.classList.add('input-warn');
                    if (warn) warn.classList.add('show');
                } else {
                    el.classList.remove('input-warn');
                    if (warn) warn.classList.remove('show');
                }
            }

            // Conectar campo mensagem
            window.Moderation.attachInput('#mensagem', 'mensagem', {
                onLocal: (res) => {
                    applyWarning('#mensagem', res);
                    if (res.inappropriate) {
                        console.warn('⚠️ Conteúdo inapropriado detectado:', res.matches);
                    }
                },
                onServer: (srv) => {
                    if (srv && srv.data && srv.data.inappropriate) {
                        applyWarning('#mensagem', { inappropriate: true });
                        console.warn('⚠️ Servidor detectou conteúdo inapropriado');
                    }
                }
            });

            // Interceptar submit
            window.Moderation.attachFormSubmit('#replyForm', [
                { selector: '#mensagem', fieldName: 'mensagem' }
            ]);

            // Listener para bloqueio
            form.addEventListener('moderation:blocked', (e) => {
                console.error('🚫 Formulário bloqueado:', e.detail);
                submitBtn.disabled = false;
                submitBtn.textContent = '🚀 Enviar Resposta';
            });

            // Listener para aprovação
            form.addEventListener('moderation:approved', (e) => {
                console.log('✅ Formulário aprovado:', e.detail);
            });

            // Backup validation
            form.addEventListener('submit', function(e) {
                const hasWarning = mensagemInput.classList.contains('input-warn');
                if (hasWarning) {
                    e.preventDefault();
                    e.stopPropagation();
                    submitBtn.disabled = false;
                    submitBtn.textContent = '🚀 Enviar Resposta';
                    return false;
                }
                
                // Validação básica
                const mensagem = mensagemInput.value.trim();
                if (mensagem.length < 10) {
                    e.preventDefault();
                    alert('A mensagem deve ter no mínimo 10 caracteres');
                    return false;
                }
                
                submitBtn.disabled = true;
                submitBtn.textContent = 'Enviando...';
            });

            console.log('✅ Sistema de moderação configurado');

        } catch (error) {
            console.error('❌ Erro ao inicializar moderação:', error);
            setupBasicValidation();
        }

        function setupBasicValidation() {
            form.addEventListener('submit', function(e) {
                const mensagem = mensagemInput.value.trim();
                
                if (mensagem.length < 10) {
                    e.preventDefault();
                    alert('A mensagem deve ter no mínimo 10 caracteres');
                    return false;
                }
                
                if (mensagem.length > 5000) {
                    e.preventDefault();
                    alert('A mensagem não pode ter mais de 5000 caracteres');
                    return false;
                }
                
                submitBtn.disabled = true;
                submitBtn.textContent = 'Enviando...';
            });
        }
    });

    // Pre-carregar modelo NSFW (opcional)
    document.addEventListener('DOMContentLoaded', () => {
        try {
            const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
            if (conn && (conn.saveData || /2g/.test(conn.effectiveType || ''))) {
                return;
            }
            
            if (window.NSFWDetector) {
                window.NSFWDetector.loadModel()
                    .then(() => console.log('✅ Modelo NSFW pré-carregado'))
                    .catch(err => console.warn('⚠️ Falha ao pré-carregar NSFW:', err));
            }
        } catch (e) { 
            console.warn('⚠️ Erro no preloader NSFW:', e); 
        }
    });

    // Configurar PDF.js worker
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }
    </script>


<script>
/**
 * ========================================
 * SISTEMA DE PUNIÇÃO INTEGRADO COM TICKETS
 * ========================================
 */

// Função para abrir modal de punição a partir do ticket
function openPunishmentModal(type, userId, ticketId) {
    // Criar modal dinamicamente se não existir
    let modal = document.getElementById('punishment-modal-ticket');
    
    if (!modal) {
        modal = createPunishmentModal();
        document.body.appendChild(modal);
    }

    // Configurar modal baseado no tipo de punição
    const form = modal.querySelector('#punishment-form-ticket');
    const title = modal.querySelector('#punishment-modal-title');
    const diasGroup = modal.querySelector('#punishment-dias-group');
    const diasInput = modal.querySelector('#punishment-dias-input');
    const submitBtn = modal.querySelector('#punishment-submit-btn');

    // Resetar formulário
    form.reset();
    diasGroup.style.display = 'none';
    diasInput.required = false;

    // Adicionar ticket_id como campo hidden
    let ticketInput = form.querySelector('input[name="ticket_id"]');
    if (!ticketInput) {
        ticketInput = document.createElement('input');
        ticketInput.type = 'hidden';
        ticketInput.name = 'ticket_id';
        form.appendChild(ticketInput);
    }
    ticketInput.value = ticketId;

    // Configurar ação e título
    switch(type) {
        case 'warning':
            form.action = `/moderacao/usuarios/${userId}/warning`;
            title.textContent = '⚠️ Aplicar Warning';
            submitBtn.textContent = 'Aplicar Warning';
            submitBtn.style.background = '#f59e0b';
            break;

        case 'ban-temp':
            form.action = `/moderacao/usuarios/${userId}/ban-temporario`;
            title.textContent = '🚫 Aplicar Ban Temporário';
            submitBtn.textContent = 'Aplicar Ban';
            submitBtn.style.background = '#ef4444';
            diasGroup.style.display = 'block';
            diasInput.required = true;
            break;

        case 'perma-ban':
            form.action = `/moderacao/usuarios/${userId}/perma-ban`;
            title.textContent = '⛔ Ban Permanente';
            submitBtn.textContent = 'Aplicar Ban Permanente';
            submitBtn.style.background = '#991b1b';
            break;

        case 'ip-ban':
            form.action = `/moderacao/usuarios/${userId}/ip-ban`;
            title.textContent = '🛡️ IP Ban';
            submitBtn.textContent = 'Aplicar IP Ban';
            submitBtn.style.background = '#7f1d1d';
            break;
    }

    // Mostrar modal
    modal.style.display = 'flex';
}

// Criar estrutura do modal
function createPunishmentModal() {
    const modal = document.createElement('div');
    modal.id = 'punishment-modal-ticket';
    modal.style.cssText = `
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    `;

    modal.innerHTML = `
        <div style="background: white; border-radius: 16px; padding: 32px; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.3);" onclick="event.stopPropagation()">
            <h2 id="punishment-modal-title" style="font-size: 24px; font-weight: 700; color: #1a202c; margin-bottom: 20px;">Aplicar Punição</h2>
            
            <form id="punishment-form-ticket" method="POST">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        Motivo *
                    </label>
                    <textarea name="motivo" 
                              id="punishment-motivo-input"
                              required 
                              minlength="10" 
                              maxlength="1000" 
                              placeholder="Descreva o motivo da punição..."
                              style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 15px; font-family: inherit; resize: vertical; min-height: 100px;"></textarea>
                    <small style="display: block; margin-top: 4px; color: #6b7280; font-size: 12px;">
                        Mínimo 10 caracteres
                    </small>
                </div>

                <div id="punishment-dias-group" style="margin-bottom: 20px; display: none;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        Duração (dias) *
                    </label>
                    <input type="number" 
                           name="dias" 
                           id="punishment-dias-input"
                           min="1" 
                           max="365" 
                           placeholder="Ex: 7"
                           style="width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 15px;">
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="button" 
                            onclick="closePunishmentModal()"
                            style="flex: 1; padding: 14px; background: #e5e7eb; color: #374151; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" 
                            id="punishment-submit-btn"
                            style="flex: 1; padding: 14px; background: #667eea; color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    `;

    // Event listeners
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closePunishmentModal();
        }
    });

    // Validação do form
    const form = modal.querySelector('#punishment-form-ticket');
    form.addEventListener('submit', function(e) {
        const motivo = modal.querySelector('#punishment-motivo-input').value.trim();
        const diasGroup = modal.querySelector('#punishment-dias-group');
        const diasInput = modal.querySelector('#punishment-dias-input');

        if (motivo.length < 10) {
            e.preventDefault();
            alert('O motivo deve ter no mínimo 10 caracteres.');
            return false;
        }

        if (diasGroup.style.display !== 'none') {
            const dias = parseInt(diasInput.value);
            if (!dias || dias < 1 || dias > 365) {
                e.preventDefault();
                alert('A duração deve ser entre 1 e 365 dias.');
                return false;
            }
        }

        if (!confirm('Tem certeza que deseja aplicar esta punição?')) {
            e.preventDefault();
            return false;
        }

        // Desabilitar botão
        const submitBtn = modal.querySelector('#punishment-submit-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando...';
    });

    return modal;
}

function closePunishmentModal() {
    const modal = document.getElementById('punishment-modal-ticket');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Fechar com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePunishmentModal();
    }
});

console.log('✅ Sistema de punição via ticket carregado');
</script>
</body>
</html>