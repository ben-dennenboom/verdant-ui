import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import fs from 'fs';
import path from 'path';

const jsFiles = Object.fromEntries(
    fs.readdirSync('resources/js')
        .filter(files => files.endsWith('.js'))
        .map(files => [files.replace(/\.js$/, ''), `resources/js/${files}`])
);

function copyVendor() {
  return {
    name: 'copy-vendor',
    closeBundle() {
      const out = path.resolve('public/build');
      const copies = [
        ['node_modules/@fortawesome/fontawesome-free/css/all.min.css', 'vendor/fontawesome/css/all.min.css'],
        ['node_modules/alpinejs/dist/cdn.min.js', 'vendor/alpine/alpine.min.js'],
        ['node_modules/cropperjs/dist/cropper.min.css', 'css/cropper.min.css'],
        ['node_modules/cropperjs/dist/cropper.min.js', 'js/cropper.min.js'],
      ];

      for (const [src, dest] of copies) {
        const from = path.resolve(src);
        const to = path.join(out, dest);
        fs.mkdirSync(path.dirname(to), { recursive: true });
        fs.copyFileSync(from, to);
      }

      const fontsDir = path.resolve('node_modules/@fortawesome/fontawesome-free/webfonts');
      const fontsOut = path.join(out, 'vendor/fontawesome/webfonts');
      fs.mkdirSync(fontsOut, { recursive: true });
      for (const file of fs.readdirSync(fontsDir)) {
        fs.copyFileSync(path.join(fontsDir, file), path.join(fontsOut, file));
      }
    },
  };
}

export default defineConfig({
  plugins: [tailwindcss(), copyVendor()],
  build: {
    outDir: 'public/build',
    rolldownOptions: {
      input: {
        ...jsFiles,
        'verdant-ui': 'resources/css/verdant-ui.css',
      },
      output: {
        entryFileNames: ({ name = '' }) => {
          if (name.startsWith('vendor/')) {
            return '[name].js';
          }
          return 'js/[name].js';
        },
        assetFileNames: ({ name = '' }) => {
          if (name.startsWith('fa-')) {
            return `vendor/fontawesome/webfonts/[name][extname]`;
          }
          if (name.startsWith('vendor/')) {
            return '[name][extname]';
          }
          if (name.endsWith('.css')) {
            return 'css/[name][extname]';
          }
          return 'assets/[name][extname]';
        },
      }
    },
  }
});
