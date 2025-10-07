// ====================================
// DETECTOR NSFW - CLIENT SIDE
// Arquivo: script.js
// ====================================

// === VARIÁVEIS GLOBAIS ===
let nsfwModel = null;
let isModelLoaded = false;
let isAnalyzing = false;
let modelLoadStartTime = null;
let analysisStats = {
    totalImages: 0,
    blockedImages: 0
};

// === ELEMENTOS DO DOM ===
const elements = {
    uploadArea: document.getElementById('uploadArea'),
    fileInput: document.getElementById('fileInput'),
    loading: document.getElementById('loading'),
    loadingText: document.getElementById('loadingText'),
    previewSection: document.getElementById('previewSection'),
    imagePreview: document.getElementById('imagePreview'),
    resultSection: document.getElementById('resultSection'),
    clearBtn: document.getElementById('clearBtn'),
    statusDot: document.getElementById('statusDot'),
    statusText: document.getElementById('statusText'),
    modelStatus: document.getElementById('modelStatus'),
    loadTime: document.getElementById('loadTime'),
    imageCount: document.getElementById('imageCount'),
    blockedCount: document.getElementById('blockedCount'),
    imageSize: document.getElementById('imageSize'),
    imageDimensions: document.getElementById('imageDimensions'),
    helpBtn: document.getElementById('helpBtn'),
    helpModal: document.getElementById('helpModal'),
    closeModal: document.getElementById('closeModal')
};

// === CONFIGURAÇÕES ===
const CONFIG = {
    maxFileSize: 10 * 1024 * 1024, // 10MB
    allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
    thresholds: {
        porn: 0.60,      // 60%
        hentai: 0.60,    // 60%
        sexy: 0.80       // 80%
    },
    categoryEmojis: {
        Drawing: '🎨',
        Neutral: '😐',
        Porn: '🔞',
        Sexy: '👙',
        Hentai: '🌸'
    }
};

// === INICIALIZAÇÃO ===
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Iniciando Detector NSFW...');
    initializeEventListeners();
    loadNSFWModel();
});

// === EVENT LISTENERS ===
function initializeEventListeners() {
    // Upload events
    elements.uploadArea.addEventListener('click', () => elements.fileInput.click());
    elements.fileInput.addEventListener('change', handleFileSelect);

    // Drag and drop events
    elements.uploadArea.addEventListener('dragover', handleDragOver);
    elements.uploadArea.addEventListener('dragleave', handleDragLeave);
    elements.uploadArea.addEventListener('drop', handleDrop);

    // Clear button
    elements.clearBtn.addEventListener('click', clearImage);

    // Help modal
    elements.helpBtn.addEventListener('click', () => showModal());
    elements.closeModal.addEventListener('click', () => hideModal());
    elements.helpModal.addEventListener('click', (e) => {
        if (e.target === elements.helpModal) hideModal();
    });

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        document.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    console.log('✅ Event listeners configurados');
}

// === CARREGAR MODELO NSFW.JS ===
async function loadNSFWModel() {
    console.log('📦 Carregando modelo NSFW.js...');
    modelLoadStartTime = Date.now();
    
    updateStatus('loading', 'Carregando modelo IA...');
    elements.modelStatus.textContent = 'Carregando...';

    try {
        // Verificar se NSFW.js está disponível
        if (typeof nsfwjs === 'undefined') {
            throw new Error('NSFW.js não foi carregado. Verifique a conexão com a internet.');
        }

        // Carregar modelo
        nsfwModel = await nsfwjs.load('./model/nsfwjs-master/models/mobilenet_v2/');
        
        const loadTime = Date.now() - modelLoadStartTime;
        isModelLoaded = true;
        
        console.log(`✅ Modelo carregado em ${loadTime}ms`);
        updateStatus('loaded', 'Modelo carregado - Pronto para uso!');
        elements.modelStatus.textContent = 'Carregado';
        elements.modelStatus.classList.add('loaded');
        elements.loadTime.textContent = `${(loadTime / 1000).toFixed(1)}s`;

    } catch (error) {
        console.error('❌ Erro ao carregar modelo:', error);
        updateStatus('error', 'Erro ao carregar modelo');
        elements.modelStatus.textContent = 'Erro';
        elements.modelStatus.classList.add('error');
        showError('Falha ao Carregar Modelo', error.message);
    }
}

// === ATUALIZAR STATUS ===
function updateStatus(status, text) {
    elements.statusDot.className = `status-dot ${status}`;
    elements.statusText.textContent = text;
}

// === HANDLE FILE SELECT ===
function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        processFile(file);
    }
}

// === DRAG AND DROP HANDLERS ===
function handleDragOver(e) {
    e.preventDefault();
    elements.uploadArea.classList.add('dragover');
}

function handleDragLeave(e) {
    e.preventDefault();
    elements.uploadArea.classList.remove('dragover');
}

function handleDrop(e) {
    e.preventDefault();
    elements.uploadArea.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        processFile(files[0]);
    }
}

// === PROCESSAR ARQUIVO ===
async function processFile(file) {
    console.log('📁 Processando arquivo:', file.name);
    
    // Validações
    if (!validateFile(file)) {
        return;
    }

    // Verificar se modelo está carregado
    if (!isModelLoaded) {
        showError('Modelo Não Carregado', 'Aguarde o modelo terminar de carregar antes de analisar imagens.');
        return;
    }

    if (isAnalyzing) {
        showError('Análise em Andamento', 'Aguarde a análise atual terminar antes de enviar outra imagem.');
        return;
    }

    try {
        // Mostrar preview
        await showImagePreview(file);
        
        // Analisar imagem
        await analyzeImage(file);
        
    } catch (error) {
        console.error('❌ Erro no processamento:', error);
        showError('Erro no Processamento', error.message);
        hideLoading();
    }
}

// === VALIDAR ARQUIVO ===
function validateFile(file) {
    // Verificar tipo
    if (!CONFIG.allowedTypes.includes(file.type)) {
        showError('Tipo de Arquivo Inválido', 
            `Apenas imagens são permitidas: JPG, PNG, GIF, WEBP`);
        return false;
    }

    // Verificar tamanho
    if (file.size > CONFIG.maxFileSize) {
        showError('Arquivo Muito Grande', 
            `O arquivo deve ter no máximo ${(CONFIG.maxFileSize / 1024 / 1024).toFixed(0)}MB`);
        return false;
    }

    return true;
}

// === MOSTRAR PREVIEW DA IMAGEM ===
function showImagePreview(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            elements.imagePreview.src = e.target.result;
            elements.imagePreview.onload = function() {
                // Mostrar seção de preview
                elements.previewSection.classList.add('show');
                
                // Atualizar informações da imagem
                const img = elements.imagePreview;
                elements.imageSize.textContent = `${(file.size / 1024).toFixed(0)} KB`;
                elements.imageDimensions.textContent = `${img.naturalWidth} × ${img.naturalHeight}px`;
                
                resolve();
            };
        };
        
        reader.onerror = function() {
            reject(new Error('Erro ao ler arquivo'));
        };
        
        reader.readAsDataURL(file);
    });
}

// === ANALISAR IMAGEM ===
async function analyzeImage(file) {
    console.log('🔍 Iniciando análise...');
    isAnalyzing = true;
    
    showLoading('Analisando imagem...');
    
    const startTime = Date.now();
    
    try {
        // Classificar imagem
        const predictions = await nsfwModel.classify(elements.imagePreview);
        const analysisTime = Date.now() - startTime;
        
        console.log('📊 Resultados:', predictions);
        
        // Processar resultados
        const result = processResults(predictions, analysisTime);
        
        // Mostrar resultados
        displayResults(result);
        
        // Atualizar estatísticas
        updateStats(result.isBlocked);
        
        console.log(`✅ Análise concluída em ${analysisTime}ms`);
        
    } catch (error) {
        console.error('❌ Erro na análise:', error);
        showError('Erro na Análise', 'Falha ao analisar a imagem. Tente novamente.');
    } finally {
        hideLoading();
        isAnalyzing = false;
    }
}

// === PROCESSAR RESULTADOS ===
function processResults(predictions, analysisTime) {
    // Converter array em objeto para facilitar acesso
    const scores = {};
    predictions.forEach(pred => {
        scores[pred.className] = pred.probability;
    });

    // Determinar se deve ser bloqueada
    const isBlocked = 
        (scores.Porn && scores.Porn >= CONFIG.thresholds.porn) ||
        (scores.Hentai && scores.Hentai >= CONFIG.thresholds.hentai) ||
        (scores.Sexy && scores.Sexy >= CONFIG.thresholds.sexy);

    // Determinar categoria de risco
    let riskLevel = 'safe';
    let reasons = [];

    if (scores.Porn >= CONFIG.thresholds.porn) {
        riskLevel = 'unsafe';
        reasons.push('Conteúdo pornográfico detectado');
    }
    if (scores.Hentai >= CONFIG.thresholds.hentai) {
        riskLevel = 'unsafe';
        reasons.push('Conteúdo hentai detectado');
    }
    if (scores.Sexy >= CONFIG.thresholds.sexy) {
        if (riskLevel === 'safe') riskLevel = 'warning';
        reasons.push('Conteúdo sensual detectado');
    }

    return {
        isBlocked,
        riskLevel,
        reasons,
        predictions,
        scores,
        analysisTime,
        maxScore: Math.max(...predictions.map(p => p.probability))
    };
}

// === MOSTRAR RESULTADOS ===
function displayResults(result) {
    const { isBlocked, riskLevel, reasons, predictions, analysisTime } = result;
    
    // Determinar ícone e mensagem
    let icon, title, subtitle;
    
    if (riskLevel === 'safe') {
        icon = '✅';
        title = 'IMAGEM PERMITIDA';
        subtitle = 'Nenhum conteúdo inapropriado detectado';
    } else if (riskLevel === 'warning') {
        icon = '⚠️';
        title = 'CONTEÚDO SUGESTIVO';
        subtitle = 'Imagem contém elementos que podem ser considerados sensíveis';
    } else {
        icon = '🚫';
        title = 'IMAGEM BLOQUEADA';
        subtitle = reasons.join(' • ');
    }

    // Gerar HTML dos resultados
    const resultsHTML = `
        <div class="result-card ${riskLevel} fade-in">
            <div class="result-header">
                <div class="result-icon">${icon}</div>
                <div class="result-content">
                    <h2>${title}</h2>
                    <p class="result-subtitle">${subtitle}</p>
                </div>
            </div>
            
            <div class="predictions">
                <h4>📊 Análise Detalhada</h4>
                <div class="prediction-grid">
                    ${predictions.map(pred => `
                        <div class="prediction-item">
                            <span class="prediction-label">
                                ${CONFIG.categoryEmojis[pred.className] || '📊'} ${pred.className}
                            </span>
                            <div class="prediction-percentage">${(pred.probability * 100).toFixed(1)}%</div>
                            <div class="prediction-bar">
                                <div class="prediction-fill" style="width: ${pred.probability * 100}%"></div>
                            </div>
                        </div>
                    `).join('')}
                </div>
                <div class="analysis-time">
                    ⏱️ Análise concluída em ${analysisTime}ms
                </div>
            </div>
        </div>
    `;

    elements.resultSection.innerHTML = resultsHTML;
    elements.resultSection.classList.add('show');
}

// === ATUALIZAR ESTATÍSTICAS ===
function updateStats(wasBlocked) {
    analysisStats.totalImages++;
    if (wasBlocked) analysisStats.blockedImages++;
    
    elements.imageCount.textContent = analysisStats.totalImages;
    elements.blockedCount.textContent = analysisStats.blockedImages;
}

// === LIMPAR IMAGEM ===
function clearImage() {
    // Limpar preview
    elements.previewSection.classList.remove('show');
    elements.resultSection.classList.remove('show');
    elements.imagePreview.src = '';
    
    // Limpar input
    elements.fileInput.value = '';
    
    // Limpar informações
    elements.imageSize.textContent = '';
    elements.imageDimensions.textContent = '';
    
    console.log('🗑️ Imagem limpa');
}

// === MOSTRAR/ESCONDER LOADING ===
function showLoading(text = 'Carregando...') {
    elements.loadingText.textContent = text;
    elements.loading.classList.add('show');
}

function hideLoading() {
    elements.loading.classList.remove('show');
}

// === MOSTRAR ERRO ===
function showError(title, message) {
    const errorHTML = `
        <div class="error-message fade-in">
            <strong>${title}</strong>
            ${message}
        </div>
    `;
    
    elements.resultSection.innerHTML = errorHTML;
    elements.resultSection.classList.add('show');
    
    // Auto-hide após 5 segundos
    setTimeout(() => {
        if (elements.resultSection.querySelector('.error-message')) {
            elements.resultSection.classList.remove('show');
        }
    }, 5000);
}

// === MODAL DE AJUDA ===
function showModal() {
    elements.helpModal.classList.add('show');
}

function hideModal() {
    elements.helpModal.classList.remove('show');
}

// === UTILITÁRIOS ===
function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// === DEBUG INFO ===
console.log('🛡️ Detector NSFW carregado');
console.log('⚙️ Configurações:', CONFIG);

// === PERFORMANCE MONITORING ===
if ('performance' in window) {
    window.addEventListener('load', () => {
        const loadTime = performance.now();
        console.log(`⚡ Página carregada em ${loadTime.toFixed(2)}ms`);
    });
}

// === ERROR HANDLING GLOBAL ===
window.addEventListener('error', (e) => {
    console.error('❌ Erro global:', e.error);
});

window.addEventListener('unhandledrejection', (e) => {
    console.error('❌ Promise rejeitada:', e.reason);
});

// === EXPORTAR PARA DEBUG (opcional) ===
window.nsfwDetector = {
    model: () => nsfwModel,
    isLoaded: () => isModelLoaded,
    stats: () => analysisStats,
    config: CONFIG
};