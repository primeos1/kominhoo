<?php
/**
 * Kominhoo Beauty — Web Installer
 * Handles DB creation, Laravel migrations, and seeding via browser.
 *
 * DELETE this file after installation is complete.
 */

define('BACKEND_PATH', __DIR__ . '/backend');
define('LOCK_FILE',    __DIR__ . '/.installed');

// ─── AJAX step handler ───────────────────────────────────────────────────────
if (isset($_GET['step'])) {
    header('Content-Type: application/json');

    if (file_exists(LOCK_FILE) && $_GET['step'] !== 'check') {
        echo json_encode(['ok' => false, 'msg' => 'Already installed. Delete .installed to re-run.']);
        exit;
    }

    $step = $_GET['step'];

    switch ($step) {

        // ── 1. System requirements ──────────────────────────────────────────
        case 'check':
            $errors = [];

            if (version_compare(PHP_VERSION, '8.0.0', '<')) {
                $errors[] = 'PHP 8.0+ required (you have ' . PHP_VERSION . ')';
            }
            foreach (['pdo', 'pdo_mysql', 'mbstring', 'json', 'openssl'] as $ext) {
                if (!extension_loaded($ext)) $errors[] = "PHP extension missing: $ext";
            }
            if (!is_dir(BACKEND_PATH . '/vendor')) {
                $errors[] = 'Composer dependencies not installed — run <code>composer install --no-dev</code> in backend/';
            }

            $phpBin = _findPhp();
            if (!$phpBin) $errors[] = 'PHP CLI not found in PATH';

            if ($errors) {
                echo json_encode(['ok' => false, 'msg' => implode('<br>', $errors)]);
            } else {
                echo json_encode(['ok' => true, 'msg' => 'PHP ' . PHP_VERSION . ' · All extensions present · Composer ready']);
            }
            break;

        // ── 2. Create database ───────────────────────────────────────────────
        case 'db':
            $cfg = _readEnv(BACKEND_PATH . '/.env');
            $host = $cfg['DB_HOST'] ?? '127.0.0.1';
            $port = $cfg['DB_PORT'] ?? '3306';
            $user = $cfg['DB_USERNAME'] ?? 'root';
            $pass = $cfg['DB_PASSWORD'] ?? '';
            $db   = $cfg['DB_DATABASE'] ?? 'kominhoo_db';

            // Build DSN without the database name so we can CREATE it
            $dsn = "mysql:host=$host;port=$port;charset=utf8mb4";
            if (!empty($cfg['DB_SOCKET'])) {
                $sock = trim($cfg['DB_SOCKET'], '"\'');
                $dsn  = "mysql:unix_socket=$sock;charset=utf8mb4";
            }

            try {
                $pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 5,
                ]);
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                echo json_encode(['ok' => true, 'msg' => "Database <strong>$db</strong> ready"]);
            } catch (PDOException $e) {
                echo json_encode(['ok' => false, 'msg' => 'MySQL error: ' . htmlspecialchars($e->getMessage())]);
            }
            break;

        // ── 3. Run migrations ────────────────────────────────────────────────
        case 'migrate':
            $out = _artisan('migrate --force');
            $ok  = $out['code'] === 0;
            echo json_encode(['ok' => $ok, 'msg' => nl2br(htmlspecialchars(trim($out['output'])))]);
            break;

        // ── 4. Seed demo data ────────────────────────────────────────────────
        case 'seed':
            $out = _artisan('db:seed --force');
            $ok  = $out['code'] === 0;
            echo json_encode(['ok' => $ok, 'msg' => nl2br(htmlspecialchars(trim($out['output'])))]);
            break;

        // ── 5. Storage link + cache clear ───────────────────────────────────
        case 'finalise':
            $msgs  = [];
            $allOk = true;

            foreach (['storage:link', 'config:clear', 'cache:clear', 'route:clear', 'view:clear'] as $cmd) {
                $out = _artisan($cmd);
                if ($out['code'] === 0) {
                    $msgs[] = '✓ ' . $cmd;
                } else {
                    $allOk = false;
                    $msgs[] = '✗ ' . $cmd . ': ' . trim($out['output']);
                }
            }

            // Write lock file
            if ($allOk) file_put_contents(LOCK_FILE, date('Y-m-d H:i:s'));

            echo json_encode(['ok' => $allOk, 'msg' => implode('<br>', $msgs)]);
            break;

        default:
            echo json_encode(['ok' => false, 'msg' => 'Unknown step']);
    }
    exit;
}

// ─── Helpers ─────────────────────────────────────────────────────────────────
function _readEnv(string $path): array
{
    $result = [];
    if (!file_exists($path)) return $result;
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        [$k, $v] = explode('=', $line, 2);
        $result[trim($k)] = trim($v, " \t\"'");
    }
    return $result;
}

function _findPhp(): string
{
    $candidates = ['php', 'php8', 'php8.2', 'php8.1', 'php8.0'];
    foreach ($candidates as $c) {
        $out = shell_exec(($c) . ' -r "echo 1;" 2>/dev/null');
        if (trim((string)$out) === '1') return $c;
    }
    // Windows XAMPP fallback
    $xamppPhp = 'C:/xampp/php/php.exe';
    if (file_exists($xamppPhp)) return '"' . $xamppPhp . '"';
    return '';
}

function _artisan(string $cmd): array
{
    $php  = _findPhp() ?: 'php';
    $path = realpath(BACKEND_PATH);
    $full = "$php \"$path/artisan\" $cmd 2>&1";
    $out  = [];
    $code = 0;
    exec($full, $out, $code);
    return ['output' => implode("\n", $out), 'code' => $code];
}

// ─── ENV values for display ──────────────────────────────────────────────────
$cfg        = _readEnv(BACKEND_PATH . '/.env');
$dbDatabase = $cfg['DB_DATABASE'] ?? 'kominhoo_db';
$dbHost     = $cfg['DB_HOST'] ?? 'localhost';
$appUrl     = $cfg['APP_URL'] ?? 'http://localhost/kominhoo/backend/public';
$frontendUrl = file_exists(__DIR__ . '/frontend/.env')
    ? (_readEnv(__DIR__ . '/frontend/.env')['APP_URL'] ?? '#')
    : '#';
$alreadyInstalled = file_exists(LOCK_FILE);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kominhoo — Installer</title>
<style>
  :root {
    --lime:    #C8E634;
    --black:   #0A0A0A;
    --dark:    #1A1A1A;
    --cream:   #FAF9F5;
    --gray-2:  #E2E1DC;
    --gray-5:  #737068;
    --success: #22C55E;
    --error:   #EF4444;
    --warn:    #F59E0B;
    --r:       12px;
  }
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: var(--cream);
    color: var(--black);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 48px 16px 80px;
  }
  .logo {
    font-size: 22px;
    font-weight: 800;
    letter-spacing: -0.5px;
    margin-bottom: 8px;
  }
  .logo span { color: var(--lime); background: var(--black); padding: 2px 8px; border-radius: 4px; }
  .subtitle { font-size: 13px; color: var(--gray-5); margin-bottom: 40px; }

  .card {
    width: 100%;
    max-width: 620px;
    background: #fff;
    border: 1px solid var(--gray-2);
    border-radius: var(--r);
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,.06);
  }
  .card-head {
    background: var(--black);
    color: #fff;
    padding: 20px 28px;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .card-head h1 { font-size: 17px; font-weight: 700; }
  .card-head p  { font-size: 13px; color: #888; margin-top: 2px; }
  .card-head svg { flex-shrink: 0; }

  .steps { padding: 24px 28px; display: flex; flex-direction: column; gap: 12px; }

  .step {
    display: grid;
    grid-template-columns: 36px 1fr auto;
    align-items: start;
    gap: 12px;
    padding: 14px 16px;
    border: 1px solid var(--gray-2);
    border-radius: 8px;
    transition: border-color .2s, background .2s;
  }
  .step.active  { border-color: var(--lime); background: #f9fce8; }
  .step.success { border-color: #86efac; background: #f0fdf4; }
  .step.error   { border-color: #fca5a5; background: #fef2f2; }

  .step-icon {
    width: 36px; height: 36px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
    background: var(--gray-2);
    flex-shrink: 0;
    font-weight: 700;
    color: var(--gray-5);
    transition: background .2s, color .2s;
  }
  .step.active  .step-icon { background: var(--lime); color: var(--black); }
  .step.success .step-icon { background: var(--success); color: #fff; }
  .step.error   .step-icon { background: var(--error);   color: #fff; }

  .step-body { min-width: 0; }
  .step-title { font-size: 14px; font-weight: 600; }
  .step-desc  { font-size: 12px; color: var(--gray-5); margin-top: 2px; }
  .step-out   {
    font-size: 11.5px;
    color: var(--gray-5);
    margin-top: 8px;
    padding: 8px 10px;
    background: var(--cream);
    border-radius: 6px;
    line-height: 1.6;
    display: none;
    word-break: break-word;
  }
  .step.success .step-out,
  .step.error   .step-out { display: block; }

  .step-badge {
    font-size: 11px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 20px;
    background: var(--gray-2);
    color: var(--gray-5);
    white-space: nowrap;
    align-self: center;
    flex-shrink: 0;
  }
  .step.active  .step-badge { background: var(--lime); color: var(--black); }
  .step.success .step-badge { background: #bbf7d0; color: #166534; }
  .step.error   .step-badge { background: #fee2e2; color: #991b1b; }

  .spinner {
    display: inline-block;
    width: 14px; height: 14px;
    border: 2px solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spin .6s linear infinite;
    vertical-align: middle;
    margin-right: 4px;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  .progress-wrap {
    padding: 0 28px 4px;
  }
  .progress-bar {
    height: 4px;
    background: var(--gray-2);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 4px;
  }
  .progress-fill {
    height: 100%;
    background: var(--lime);
    border-radius: 2px;
    transition: width .4s ease;
    width: 0%;
  }
  .progress-label { font-size: 11px; color: var(--gray-5); text-align: right; }

  .action-wrap { padding: 20px 28px 28px; }

  #install-btn, .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 28px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    border: none;
    transition: opacity .15s, transform .1s;
    text-decoration: none;
  }
  #install-btn:active, .btn:active { transform: scale(.97); }
  #install-btn { background: var(--lime); color: var(--black); width: 100%; justify-content: center; }
  #install-btn:disabled { opacity: .5; cursor: not-allowed; }
  .btn-outline { background: transparent; border: 2px solid var(--black); color: var(--black); }
  .btn-dark    { background: var(--black); color: #fff; }

  .info-box {
    margin: 0 28px 20px;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 13px;
    line-height: 1.6;
  }
  .info-box.warn    { background: #fffbeb; border: 1px solid #fde68a; color: #78350f; }
  .info-box.success { background: #f0fdf4; border: 1px solid #86efac; color: #14532d; }
  .info-box.info    { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e3a8a; }
  .info-box strong  { display: block; margin-bottom: 4px; }

  .creds-table { width: 100%; border-collapse: collapse; font-size: 13px; }
  .creds-table td { padding: 4px 0; vertical-align: top; }
  .creds-table td:first-child { color: var(--gray-5); width: 130px; }
  .creds-table code {
    background: var(--gray-2);
    padding: 1px 6px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 12px;
  }

  .launch-row {
    display: none;
    gap: 10px;
    flex-direction: column;
    margin-top: 16px;
  }
  .launch-row.visible { display: flex; }

  .delete-note {
    font-size: 11px;
    color: var(--gray-5);
    text-align: center;
    margin-top: 12px;
  }

  .already-installed {
    padding: 32px 28px;
    text-align: center;
  }
  .already-installed h2 { font-size: 20px; margin-bottom: 8px; }
  .already-installed p  { font-size: 14px; color: var(--gray-5); margin-bottom: 24px; }
</style>
</head>
<body>

<div class="logo"><span>kominhoo</span></div>
<p class="subtitle">Korean Beauty for Nigerian Skin — Setup Wizard</p>

<div class="card">
  <div class="card-head">
    <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
      <rect width="32" height="32" rx="8" fill="#C8E634"/>
      <path d="M8 17l5 5 11-11" stroke="#0A0A0A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <div>
      <h1>Database &amp; App Setup</h1>
      <p>Runs migrations, seeds demo data, and clears caches</p>
    </div>
  </div>

  <?php if ($alreadyInstalled): ?>
  <div class="already-installed">
    <h2>Already Installed ✓</h2>
    <p>Kominhoo was set up on <?= htmlspecialchars(file_get_contents(LOCK_FILE)) ?>.<br>
       Delete <code>.installed</code> from the project root to re-run.</p>
    <a href="<?= htmlspecialchars($frontendUrl) ?>" class="btn btn-dark" style="display:inline-flex;margin:0 auto;">Open Site →</a>
  </div>
  <?php else: ?>

  <div class="progress-wrap">
    <div class="progress-bar"><div class="progress-fill" id="progress"></div></div>
    <div class="progress-label" id="progress-label">Ready to install</div>
  </div>

  <div class="steps">
    <div class="step" id="step-check">
      <div class="step-icon">1</div>
      <div class="step-body">
        <div class="step-title">System Requirements</div>
        <div class="step-desc">PHP version, extensions, Composer vendor directory</div>
        <div class="step-out" id="out-check"></div>
      </div>
      <div class="step-badge" id="badge-check">Pending</div>
    </div>

    <div class="step" id="step-db">
      <div class="step-icon">2</div>
      <div class="step-body">
        <div class="step-title">Create Database</div>
        <div class="step-desc">
          Creates <strong><?= htmlspecialchars($dbDatabase) ?></strong> on
          <strong><?= htmlspecialchars($dbHost) ?></strong> if it doesn't exist
        </div>
        <div class="step-out" id="out-db"></div>
      </div>
      <div class="step-badge" id="badge-db">Pending</div>
    </div>

    <div class="step" id="step-migrate">
      <div class="step-icon">3</div>
      <div class="step-body">
        <div class="step-title">Run Migrations</div>
        <div class="step-desc">Creates all 12 application tables</div>
        <div class="step-out" id="out-migrate"></div>
      </div>
      <div class="step-badge" id="badge-migrate">Pending</div>
    </div>

    <div class="step" id="step-seed">
      <div class="step-icon">4</div>
      <div class="step-body">
        <div class="step-title">Seed Demo Data</div>
        <div class="step-desc">Admin user, 8 products, 2 bundles, guides, posts &amp; promos</div>
        <div class="step-out" id="out-seed"></div>
      </div>
      <div class="step-badge" id="badge-seed">Pending</div>
    </div>

    <div class="step" id="step-finalise">
      <div class="step-icon">5</div>
      <div class="step-body">
        <div class="step-title">Finalise</div>
        <div class="step-desc">Storage link, config &amp; cache clear</div>
        <div class="step-out" id="out-finalise"></div>
      </div>
      <div class="step-badge" id="badge-finalise">Pending</div>
    </div>
  </div>

  <div class="action-wrap">
    <button id="install-btn" onclick="runInstall()">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="16 16 12 20 8 16"/><line x1="12" y1="4" x2="12" y2="20"/>
        <path d="M20 21H4"/>
      </svg>
      Install Kominhoo
    </button>

    <div class="launch-row" id="launch-row">
      <div class="info-box success">
        <strong>Installation complete!</strong>
        <table class="creds-table">
          <tr><td>Admin email</td><td><code>admin@kominhoo.com</code></td></tr>
          <tr><td>Admin password</td><td><code>admin1234</code></td></tr>
          <tr><td>Promo codes</td><td><code>WELCOME10</code> &nbsp; <code>KBEAUTY2K</code></td></tr>
          <tr><td>Backend API</td><td><code><?= htmlspecialchars($appUrl) ?></code></td></tr>
          <tr><td>Frontend</td><td><code><?= htmlspecialchars($frontendUrl) ?></code></td></tr>
        </table>
      </div>
      <a href="<?= htmlspecialchars($frontendUrl) ?>" class="btn btn-dark">Open Frontend →</a>
      <a href="<?= htmlspecialchars($appUrl) ?>/health" class="btn btn-outline">Check API →</a>
      <p class="delete-note">
        Security: delete <code>install.php</code> and <code>.installed</code> from your project root before going live.
      </p>
    </div>
  </div>

  <?php endif; ?>
</div>

<script>
const STEPS = ['check', 'db', 'migrate', 'seed', 'finalise'];
const LABELS = {
  check:    'Checking requirements…',
  db:       'Creating database…',
  migrate:  'Running migrations…',
  seed:     'Seeding demo data…',
  finalise: 'Finalising…',
};

async function runStep(step) {
  const card  = document.getElementById('step-' + step);
  const badge = document.getElementById('badge-' + step);
  const out   = document.getElementById('out-'   + step);

  card.className  = 'step active';
  badge.innerHTML = '<span class="spinner"></span> Running';

  try {
    const r = await fetch('install.php?step=' + step);
    const j = await r.json();
    out.innerHTML   = j.msg || '';
    card.className  = 'step ' + (j.ok ? 'success' : 'error');
    badge.innerHTML = j.ok ? '✓ Done' : '✗ Failed';
    return j.ok;
  } catch (e) {
    out.innerHTML   = 'Network error: ' + e.message;
    card.className  = 'step error';
    badge.innerHTML = '✗ Failed';
    return false;
  }
}

async function runInstall() {
  const btn      = document.getElementById('install-btn');
  const progress = document.getElementById('progress');
  const label    = document.getElementById('progress-label');

  btn.disabled     = true;
  btn.innerHTML    = '<span class="spinner"></span> Installing…';

  for (let i = 0; i < STEPS.length; i++) {
    const step = STEPS[i];
    label.textContent = LABELS[step];
    progress.style.width = ((i / STEPS.length) * 100) + '%';

    const ok = await runStep(step);
    if (!ok) {
      btn.disabled     = false;
      btn.innerHTML    = 'Retry from failed step';
      label.textContent = 'Installation failed — check the step above';
      progress.style.width = (((i + 1) / STEPS.length) * 100) + '%';
      return;
    }
  }

  progress.style.width = '100%';
  label.textContent    = 'Installation complete!';
  btn.style.display    = 'none';
  document.getElementById('launch-row').classList.add('visible');
}
</script>

</body>
</html>
