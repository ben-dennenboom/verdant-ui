import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { verdantFiles, verdantDependencies } from './verdant-build';

export default defineConfig({
  plugins: [tailwindcss(), verdantDependencies()],
  build: {
    outDir: 'public/build',
    rolldownOptions: {
      input: verdantFiles(),
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
