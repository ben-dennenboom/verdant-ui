import fs from 'fs';
import path from 'path';

export function verdantFiles() {
    return Object.fromEntries(
        fs.readdirSync('resources/js')
            .filter(file => file.endsWith('.js'))
            .map(file => [file.replace(/\.js$/, ''), `resources/js/${file}`])
    );
}

export function verdantDependencies() {
    return {
        name: 'verdant-copy-vendor',
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