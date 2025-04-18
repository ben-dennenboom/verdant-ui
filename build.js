import fs from 'fs';
import path from 'path';
import postcss from 'postcss';
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';
import {fileURLToPath} from 'url';
import https from 'https';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

const buildDir = path.join(__dirname, 'public', 'build', 'css');
fs.mkdirSync(buildDir, {recursive: true});

const jsDir = path.join(__dirname, 'public', 'build', 'js');
fs.mkdirSync(jsDir, {recursive: true});

const vendorDir = path.join(__dirname, 'public', 'build', 'vendor');
fs.mkdirSync(vendorDir, {recursive: true});

const fontAwesomeDir = path.join(vendorDir, 'fontawesome');
fs.mkdirSync(path.join(fontAwesomeDir, 'css'), {recursive: true});
fs.mkdirSync(path.join(fontAwesomeDir, 'webfonts'), {recursive: true});

const alpineDir = path.join(vendorDir, 'alpine');
fs.mkdirSync(alpineDir, {recursive: true});

const cssInput = fs.readFileSync(
    path.join(__dirname, 'resources', 'css', 'verdant-ui.css'),
    'utf8'
);

const cssWithoutFontAwesome = cssInput.replace('@import \'@fortawesome/fontawesome-free/css/all.css\';', '');

const cssOutput = await postcss([
  tailwindcss('./tailwind.config.js'),
  autoprefixer()
])
    .process(cssWithoutFontAwesome, {
      from: path.join(__dirname, 'resources', 'css', 'verdant-ui.css'),
      to: path.join(buildDir, 'verdant-ui.css')
    });

fs.writeFileSync(path.join(buildDir, 'verdant-ui.css'), cssOutput.css);

fs.copyFileSync(
    path.join(__dirname, 'resources', 'js', 'verdant-ui.js'),
    path.join(jsDir, 'verdant-ui.js')
);

const downloads = [
  {
    url: 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css',
    dest: path.join(fontAwesomeDir, 'css', 'all.min.css')
  },
  {
    url: 'https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js',
    dest: path.join(alpineDir, 'alpine.min.js')
  }
];

const webfonts = [
  'fa-brands-400.ttf',
  'fa-brands-400.woff2',
  'fa-regular-400.ttf',
  'fa-regular-400.woff2',
  'fa-solid-900.ttf',
  'fa-solid-900.woff2'
];

webfonts.forEach(font => {
  downloads.push({
    url: `https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/webfonts/${font}`,
    dest: path.join(fontAwesomeDir, 'webfonts', font)
  });
});

function downloadFile(url, dest) {
  return new Promise((resolve, reject) => {
    const file = fs.createWriteStream(dest);
    https.get(url, response => {
      response.pipe(file);
      file.on('finish', () => {
        file.close();
        resolve();
      });
    }).on('error', err => {
      fs.unlink(dest);
      reject(err);
    });
  });
}

console.log('Downloading vendor files...');
await Promise.all(downloads.map(file => downloadFile(file.url, file.dest)));

console.log('Build completed successfully!');
