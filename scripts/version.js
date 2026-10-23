import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const ROOT      = path.resolve(__dirname, '..');

const bump = process.argv[2]; // patch | minor | major

if (!['patch', 'minor', 'major'].includes(bump)) {
  console.error('Usage: npm run version:patch | version:minor | version:major');
  process.exit(1);
}

// --- Read current version from style.css (theme header is the source of truth) ---
const cssPath    = path.join(ROOT, 'style.css');
const cssContent = fs.readFileSync(cssPath, 'utf8');

const headerMatch = cssContent.match(/^Version:\s+([\d.]+)/m);
if (!headerMatch) {
  console.error('Could not find Version header in style.css');
  process.exit(1);
}

const current = headerMatch[1].trim();
const [major, minor, patch] = current.split('.').map(Number);

// --- Calculate new version ---
let next;
if (bump === 'major') next = `${major + 1}.0.0`;
if (bump === 'minor') next = `${major}.${minor + 1}.0`;
if (bump === 'patch') next = `${major}.${minor}.${patch + 1}`;

console.log(`\n🔖 Bumping version: ${current} → ${next} (${bump})\n`);

// --- Update style.css theme header ---
fs.writeFileSync(cssPath, cssContent.replace(/^(Version:\s+)[\d.]+/m, `$1${next}`), 'utf8');
console.log(`  ✔ style.css`);

// --- Update functions.php constant ---
const phpPath    = path.join(ROOT, 'functions.php');
const phpContent = fs.readFileSync(phpPath, 'utf8');
fs.writeFileSync(
  phpPath,
  phpContent.replace(/(define\(\s*'VINCENT_VERSION',\s*')[^']+(')/, `$1${next}$2`),
  'utf8'
);
console.log(`  ✔ functions.php`);

// --- Update package.json ---
const pkgPath = path.join(ROOT, 'package.json');
const pkg     = JSON.parse(fs.readFileSync(pkgPath, 'utf8'));
pkg.version   = next;
fs.writeFileSync(pkgPath, JSON.stringify(pkg, null, 2) + '\n', 'utf8');
console.log(`  ✔ package.json`);

console.log(`\n✅ Version is now ${next}\n`);
console.log(`   Run "npm run bundle" to build and package the release.\n`);
