import fs from 'node:fs';
import path from 'node:path';

function verdant() {
    return {
        name: 'verdant-dependencies',
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

verdant.files = function () {
    const baseDir = 'resources/js';
    const files = {};

    for (const item of fs.readdirSync(baseDir, { withFileTypes: true })) {
        if (item.isFile() && item.name.endsWith('.js')) {
            files[item.name.slice(0, -3)] = path.join(baseDir, item.name);
        } else if (item.isDirectory()) {
            const dirPath = path.join(baseDir, item.name);

            if (fs.existsSync(path.join(dirPath, 'index.js'))) {
                files[item.name] = path.join(dirPath, 'index.js');
            } else {
                for (const file of fs.readdirSync(dirPath).filter(f => f.endsWith('.js'))) {
                    files[`${item.name}/${file.slice(0, -3)}`] = path.join(dirPath, file);
                }
            }
        }
    }

    return files;
};

export default verdant;