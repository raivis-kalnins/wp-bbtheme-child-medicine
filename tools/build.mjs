import fs from 'node:fs';
import path from 'node:path';
import crypto from 'node:crypto';

const root = process.cwd();
const src = path.join(root, 'src');
const dist = path.join(root, 'dist');
const assetsDir = path.join(dist, 'assets');
const styleEntry = 'src/scss/public.scss';
const scriptEntry = 'src/js/main.js';

function read(rel) {
  return fs.readFileSync(path.join(root, rel), 'utf8');
}

function hash(content) {
  return crypto.createHash('sha256').update(content).digest('hex').slice(0, 10);
}

function parseTokens() {
  const input = read('src/scss/_tokens.scss');
  const tokens = {};
  for (const line of input.split(/\r?\n/)) {
    const match = line.match(/^\s*\$([\w-]+)\s*:\s*(.+);\s*$/);
    if (match) tokens[match[1]] = match[2].trim();
  }

  const resolve = (name, stack = []) => {
    if (!(name in tokens)) return `$${name}`;
    if (stack.includes(name)) throw new Error(`Circular Sass token: ${[...stack, name].join(' -> ')}`);
    let value = tokens[name];
    value = value.replace(/\$([\w-]+)/g, (_, child) => resolve(child, [...stack, name]));
    return value;
  };

  return Object.fromEntries(Object.keys(tokens).map((name) => [name, resolve(name)]));
}

function rgbaHex(hex, alpha) {
  let value = hex.trim();
  if (!/^#[0-9a-f]{3,8}$/i.test(value)) return `rgba(${value},${alpha})`;
  value = value.slice(1);
  if (value.length === 3) value = value.split('').map((char) => char + char).join('');
  const rgb = [0, 2, 4].map((offset) => parseInt(value.slice(offset, offset + 2), 16));
  return `rgba(${rgb.join(',')},${alpha.trim()})`;
}

function compileScss() {
  const order = ['base', 'header', 'footer', 'components', 'swiper', 'motion', 'quality', 'forms', 'blog', 'sector', 'responsive', 'v371-fixes', 'v372-polish', 'v380-features'];
  const tokens = parseTokens();
  let css = order.map((name) => read(`src/scss/_${name}.scss`)).join('\n');
  css = css.replace(/^\s*@use\s+['"]tokens['"](?:\s+as\s+\*)?\s*;\s*$/gm, '');
  css = css.replace(/#\{\$([\w-]+)\}/g, (_, name) => tokens[name] ?? `$${name}`);
  css = css.replace(/rgba\(\s*\$([\w-]+)\s*,\s*([^)]+)\)/g, (_, name, alpha) => rgbaHex(tokens[name] ?? `$${name}`, alpha));
  css = css.replace(/\$([\w-]+)/g, (_, name) => tokens[name] ?? `$${name}`);
  if (/\$[\w-]+/.test(css)) throw new Error('Unresolved Sass variable remains in compiled CSS.');
  css = css.replace(/\/\*[\s\S]*?\*\//g, '');
  css = css.replace(/\s+/g, ' ')
    .replace(/\s*([{}:;,>])\s*/g, '$1')
    .replace(/;}/g, '}')
    .trim();
  return `${css}\n`;
}

function bundleJs() {
  const header = read('src/js/components/header.js').replace(/^export\s+/gm, '');
  const motion = read('src/js/components/motion.js').replace(/^export\s+/gm, '');
  const blog = read('src/js/components/blog.js').replace(/^export\s+/gm, '');
  let main = read('src/js/main.js').replace(/^import[^;]+;\s*$/gm, '');
  return `${header}\n${motion}\n${blog}\n${main}\n`;
}

fs.rmSync(dist, { recursive: true, force: true });
fs.mkdirSync(path.join(dist, '.vite'), { recursive: true });
fs.mkdirSync(assetsDir, { recursive: true });

const css = compileScss();
const js = bundleJs();
const cssFile = `assets/theme-${hash(css)}.css`;
const jsFile = `assets/theme-${hash(js)}.js`;
fs.writeFileSync(path.join(dist, cssFile), css);
fs.writeFileSync(path.join(dist, jsFile), js);
fs.writeFileSync(path.join(dist, '.vite', 'manifest.json'), JSON.stringify({
  [styleEntry]: { file: cssFile, src: styleEntry, isEntry: true },
  [scriptEntry]: { file: jsFile, src: scriptEntry, isEntry: true },
}, null, 2) + '\n');
console.log(`Built ${cssFile} and ${jsFile}`);
