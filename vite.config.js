import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import verdant from './verdant-build.js';

export default defineConfig({
  plugins: [
      laravel({
          input: verdant.files(),
          refresh: true,
      }),
      tailwindcss(),
      verdant()
  ],
  build: {
    outDir: 'public/build',
    rolldownOptions: {
      output: {
        entryFileNames: 'js/[name].js',
        assetFileNames: ({ name = '' }) => {
          if (name.endsWith('.css')) {
            return 'css/[name][extname]';
          }
          return 'assets/[name][extname]';
        },
      }
    },
  }
});
