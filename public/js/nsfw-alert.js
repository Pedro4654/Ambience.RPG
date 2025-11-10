/**
 * NSFW Alert Component - Componente Visual Reutilizável
 * Arquivo: public/js/nsfw-alert.js
 * 
 * Mostra alertas visuais para resultados NSFW
 */

const NSFWAlert = (function() {
    
    /**
     * Criar HTML do alerta
     */
    function createAlertHTML(result, options = {}) {
        const { isBlocked, riskLevel, reasons, scores, predictions } = result;
        
        let icon, title, colorClass, subtitle;
        
        if (riskLevel === 'safe') {
            icon = '✅';
            title = options.safeTitle || 'Imagem Aprovada';
            colorClass = 'alert-success';
            subtitle = options.safeSubtitle || 'Nenhum conteúdo inapropriado detectado';
        } else if (riskLevel === 'warning') {
            icon = '⚠️';
            title = options.warningTitle || 'Conteúdo Sugestivo';
            colorClass = 'alert-warning';
            subtitle = reasons.join(' • ');
        } else {
            icon = '🚫';
            title = options.blockedTitle || 'Imagem Bloqueada';
            colorClass = 'alert-danger';
            subtitle = reasons.join(' • ');
        }

        const detailsHTML = options.showDetails ? `
            <div class="mt-2 small">
                <strong>Análise Detalhada:</strong>
                <ul class="mb-0 mt-1">
                    ${predictions.map(pred => `
                        <li>${pred.className}: ${(pred.probability * 100).toFixed(1)}%</li>
                    `).join('')}
                </ul>
            </div>
        ` : '';

        return `
            <div class="alert ${colorClass} d-flex align-items-start fade show" role="alert">
                <div class="me-2" style="font-size: 1.5rem;">${icon}</div>
                <div class="flex-grow-1">
                    <strong>${title}</strong>
                    <div class="mt-1">${subtitle}</div>
                    ${detailsHTML}
                </div>
                ${options.showClose ? `
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                ` : ''}
            </div>
        `;
    }

    /**
     * Mostrar alerta em um container
     */
    function show(containerId, result, options = {}) {
        const container = document.getElementById(containerId);
        if (!container) {
            console.error(`Container #${containerId} não encontrado`);
            return;
        }

        const alertHTML = createAlertHTML(result, {
            showDetails: false,
            showClose: true,
            ...options
        });

        container.innerHTML = alertHTML;
        container.style.display = 'block';
    }

    /**
     * Limpar alerta
     */
    function clear(containerId) {
        const container = document.getElementById(containerId);
        if (container) {
            container.innerHTML = '';
            container.style.display = 'none';
        }
    }

    /**
     * Mostrar loading
     */
    function showLoading(containerId, message = 'Analisando imagem...') {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.innerHTML = `
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <div class="spinner-border spinner-border-sm me-2" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <div>${message}</div>
            </div>
        `;
        container.style.display = 'block';
    }

    /**
     * Mostrar erro
     */
    function showError(containerId, errorMessage) {
        const container = document.getElementById(containerId);
        if (!container) return;

        container.innerHTML = `
            <div class="alert alert-danger" role="alert">
                <strong>❌ Erro:</strong> ${errorMessage}
            </div>
        `;
        container.style.display = 'block';
    }

    // === API PÚBLICA ===
    return {
        show,
        clear,
        showLoading,
        showError,
        createAlertHTML
    };
})();

// Expor globalmente
window.NSFWAlert = NSFWAlert;