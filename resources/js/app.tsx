// resources/js/app.tsx
import '../css/app.css';
import './bootstrap.ts';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { initializeTheme } from './hooks/use-appearance';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Só inicializa o Inertia se existir o #app na página
const el = document.getElementById('app');
if (el) {
  createInertiaApp({
    title: (title) => (title ? `${title} – ${appName}` : appName),
    resolve: (name) =>
      resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),
    setup({ el, App, props }) {
      const root = createRoot(el);
      root.render(<App {...props} />);
    },
    progress: { color: '#4B5563' },
  });
}

initializeTheme();
