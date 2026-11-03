import archiver from 'archiver';
import { execSync } from 'child_process';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const ROOT      = path.resolve(__dirname, '..');

// Read version from style.css (source of truth).
const styleFile    = fs.readFileSync(path.join(ROOT, 'style.css'), 'utf8');
const versionMatch = styleFile.match(/^Version:\s+(.+)/m);
const version      = versionMatch ? versionMatch[1].trim() : '1.0.0';

const zipName  = `dev-portfolio-v${version}.zip`;
const zipPath  = path.join(ROOT, zipName);
const themeDir = 'dev-portfolio'; // folder name inside the zip (must match theme slug)

console.log(`\n📦 Bundling Developer Portfolio v${version}...\n`);

// 1. Refuse to bundle while the dev server is running: `hot` makes PHP load assets from localhost.
if (fs.existsSync(path.join(ROOT, 'hot'))) {
  console.error('  ✖ Found "hot" file. Stop "npm run dev" (or delete hot) before bundling.');
  process.exit(1);
}

// 2. Build assets.
console.log('  → Building assets...');
execSync('vite build', { cwd: ROOT, stdio: 'inherit' });

// 3. Verify dist was produced.
if (!fs.existsSync(path.join(ROOT, 'dist/.vite/manifest.json'))) {
  console.error('  ✖ Build failed — dist/.vite/manifest.json not found.');
  process.exit(1);
}

// 4. Remove old zip if exists.
if (fs.existsSync(zipPath)) {
  fs.unlinkSync(zipPath);
}

// 5. Create zip.
console.log(`  → Creating ${zipName}...`);

const output  = fs.createWriteStream(zipPath);
const archive = archiver('zip', { zlib: { level: 9 } });

archive.on('error', (err) => { throw err; });

output.on('close', () => {
  const kb = (archive.pointer() / 1024).toFixed(1);
  console.log(`\n✅ Done! ${zipName} (${kb} KB)\n`);
  console.log(`   Install via WordPress Admin → Appearance → Themes → Add New → Upload Theme\n`);
});

archive.pipe(output);

// Folders to include. src/, node_modules/ and scripts/ are build-time only.
const includes = [
  { type: 'dir', src: 'inc',       dest: `${themeDir}/inc` },
  { type: 'dir', src: 'template-parts', dest: `${themeDir}/template-parts` },
  { type: 'dir', src: 'assets',    dest: `${themeDir}/assets` },
  { type: 'dir', src: 'dist',      dest: `${themeDir}/dist` },
  { type: 'dir', src: 'languages', dest: `${themeDir}/languages` },
];

// Optional files.
for (const file of ['screenshot.png', 'screenshot.jpg', 'readme.txt', 'LICENSE']) {
  if (fs.existsSync(path.join(ROOT, file))) {
    includes.push({ type: 'file', src: file, dest: `${themeDir}/${file}` });
  }
}

// style.css and every root-level PHP template.
for (const file of fs.readdirSync(ROOT)) {
  if (file === 'style.css' || file.endsWith('.php')) {
    includes.push({ type: 'file', src: file, dest: `${themeDir}/${file}` });
  }
}

for (const item of includes) {
  const fullSrc = path.join(ROOT, item.src);
  if (!fs.existsSync(fullSrc)) {
    if (item.src !== 'languages') console.warn(`  ⚠ Skipping missing: ${item.src}`);
    continue;
  }
  if (item.type === 'dir') {
    archive.directory(fullSrc, item.dest);
  } else {
    archive.file(fullSrc, { name: item.dest });
  }
}

archive.finalize();
