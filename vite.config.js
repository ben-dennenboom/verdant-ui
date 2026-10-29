import { defineConfig } from 'vite-plus';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import verdant from './verdant-vite.js';

export default defineConfig({
    fmt: {
        ignorePatterns: ['public/**', 'vendor/**'],
        printWidth: 100,
        tabWidth: 4,
        useTabs: false,
        semi: true,
        singleQuote: true,
        trailingComma: 'all',
    },
    lint: {
        ignorePatterns: ['public/**', 'vendor/**'],
    },
    plugins: [
        laravel({
            input: verdant.files(),
            refresh: true,
        }),
        tailwindcss(),
        verdant(),
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
            },
        },
    },
});
