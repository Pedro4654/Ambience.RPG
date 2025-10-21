import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
  plugins: [
    react(),      // 1º
    laravel({     // 2º
      input: [
        'resources/js/app.tsx',
        'resources/js/pages/sala-show.tsx',
      ],
      refresh: true,
    }),
  ],
});


