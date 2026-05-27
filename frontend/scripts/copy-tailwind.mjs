import { readFileSync, copyFileSync } from 'fs';
import { join } from 'path';

const manifest = JSON.parse(readFileSync('public/build/manifest.json', 'utf8'));
const entry = manifest['resources/css/app.css'];
if (!entry) { console.error('CSS entry not found in manifest'); process.exit(1); }

const src  = join('public', 'build', entry.file);
const dest = join('public', 'css', 'tailwind.css');
copyFileSync(src, dest);
console.log(`Copied ${src} → ${dest}`);
