/// <reference types="vite/client" />
import React from 'react';
import { createRoot } from 'react-dom/client';
import IniciarSessaoButton from '../components/IniciarSessaoButton';


// Espera o DOM carregar
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('iniciar-sessao-container');
    
    if (container) {
        const salaId = parseInt(container.getAttribute('data-sala-id') || '0');
        const temPermissao = container.getAttribute('data-tem-permissao') === 'true';
        const userId = parseInt(container.getAttribute('data-user-id') || '0');
        
        const root = createRoot(container);
        root.render(
            <IniciarSessaoButton 
                salaId={salaId}
                temPermissao={temPermissao}
                userId={userId}
            />
        );
    }
});
