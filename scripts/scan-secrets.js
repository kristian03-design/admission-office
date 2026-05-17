import fs from 'node:fs';
import path from 'node:path';

const root = process.cwd();
const scanRoots = ['public', 'resources'];
const ignoredDirs = new Set(['node_modules', 'vendor', '.git']);
const textExtensions = new Set(['.js', '.css', '.html', '.php', '.blade.php', '.json', '.map']);

const forbiddenEnvNames = [
  'SUPABASE_S3_KEY',
  'SUPABASE_S3_SECRET',
  'SUPABASE_SERVICE_ROLE',
  'SERVICE_ROLE',
  'DB_PASSWORD',
  'MAIL_PASSWORD',
  'POSTMARK_API_KEY',
  'RESEND_API_KEY',
  'AWS_SECRET_ACCESS_KEY',
  'SLACK_BOT_USER_OAUTH_TOKEN',
];

const suspiciousValuePatterns = [
  /eyJ[a-zA-Z0-9_-]{20,}\.[a-zA-Z0-9_-]{20,}\.[a-zA-Z0-9_-]{20,}/g,
  /(?:sk|pk|sb|btech_pat)_[a-zA-Z0-9]{24,}/g,
  /(?:access_token|service_role|private_key|api_key|secret)\s*[:=]\s*["'][^"']{12,}["']/gi,
];

const findings = [];

function walk(dir) {
  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
    if (ignoredDirs.has(entry.name)) continue;

    const fullPath = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      walk(fullPath);
      continue;
    }

    const lower = entry.name.toLowerCase();
    const ext = lower.endsWith('.blade.php') ? '.blade.php' : path.extname(lower);
    if (!textExtensions.has(ext)) continue;

    scanFile(fullPath);
  }
}

function scanFile(filePath) {
  const rel = path.relative(root, filePath).replaceAll(path.sep, '/');
  const content = fs.readFileSync(filePath, 'utf8');

  for (const envName of forbiddenEnvNames) {
    if (content.includes(envName)) {
      findings.push(`${rel}: references server-only env name ${envName}`);
    }
  }

  for (const pattern of suspiciousValuePatterns) {
    const matches = content.match(pattern) || [];
    for (const match of matches) {
      findings.push(`${rel}: suspicious secret-like value "${match.slice(0, 48)}..."`);
    }
  }
}

for (const scanRoot of scanRoots) {
  const dir = path.join(root, scanRoot);
  if (fs.existsSync(dir)) walk(dir);
}

if (findings.length) {
  console.error('Secret scan failed. Review these browser-exposed files:');
  for (const finding of findings) console.error(`- ${finding}`);
  process.exit(1);
}

console.log('Secret scan passed: no server-only key references or obvious secrets in public/resources.');
