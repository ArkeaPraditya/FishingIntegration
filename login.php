<?php
session_start();
require 'database.php';

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$redirect = $_GET['redirect'] ?? $_POST['redirect'] ?? 'index.php';

$allowedRedirects = ['index.php', 'reservasi.php'];
if (!in_array($redirect, $allowedRedirects, true)) {
    $redirect = 'index.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $password === '') {
        $error = 'Nama dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT id, name, password, picture, email FROM users WHERE name = ? LIMIT 1');
        $stmt->execute([$name]);
        $account = $stmt->fetch();

        if ($account && !empty($account['password']) && password_verify($password, $account['password'])) {
            session_regenerate_id(true);

            $_SESSION['user'] = [
                'id' => $account['id'],
                'name' => $account['name'],
                'email' => $account['email'],
                'picture' => $account['picture']
            ];

            header('Location: ' . $redirect);
            exit;
        }

        $error = 'Nama atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Tambak Fishing</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#cda15a;--gold2:#e8c580;--cream:#f4ead9;--ink:#0d1218;--muted:#bfb5a7}
*{box-sizing:border-box}
body{margin:0;min-height:100vh;font-family:Jost,sans-serif;color:var(--cream);background:
linear-gradient(180deg,rgba(6,10,16,.72),rgba(6,10,16,.94)),
url('gambartambak.png') center/cover fixed;display:grid;place-items:center;padding:24px}
.card{width:min(440px,100%);background:rgba(13,18,24,.82);border:1px solid rgba(232,197,128,.3);border-radius:20px;padding:36px;box-shadow:0 25px 80px rgba(0,0,0,.45);backdrop-filter:blur(12px)}
.logo{text-align:center;color:var(--gold2);font-family:"Cormorant Garamond",serif;font-size:2.2rem;letter-spacing:.12em}
.subtitle{text-align:center;color:var(--muted);margin:4px 0 28px}
label{display:block;margin:14px 0 7px;font-size:.8rem;letter-spacing:.08em;text-transform:uppercase}
input{width:100%;padding:13px 14px;border-radius:9px;border:1px solid rgba(244,234,217,.22);background:rgba(255,255,255,.06);color:var(--cream);outline:none}
input:focus{border-color:var(--gold2)}
button,.google{width:100%;padding:13px;border-radius:9px;cursor:pointer;font-family:Jost,sans-serif}
button{margin-top:20px;border:0;background:var(--cream);color:var(--ink);letter-spacing:.12em;text-transform:uppercase}
.google{margin-top:12px;border:1px solid rgba(244,234,217,.25);background:#fff;color:#222;text-align:center}
.error{padding:10px 12px;border-radius:8px;background:rgba(170,50,50,.2);border:1px solid rgba(220,100,100,.35);color:#ffd6d6;margin-bottom:14px;font-size:.9rem}
.divider{display:flex;align-items:center;gap:12px;margin:22px 0;color:#8f877c;font-size:.75rem}
.divider:before,.divider:after{content:"";height:1px;flex:1;background:rgba(244,234,217,.15)}
.links{text-align:center;margin-top:22px;color:var(--muted);font-size:.9rem}
.links a{color:var(--gold2);text-decoration:none}
.back{display:block;text-align:center;margin-top:14px;color:var(--muted);text-decoration:none;font-size:.85rem}
</style>
</head>
<body>
<main class="card">
  <div class="logo">TAMBAK FISHING</div>
  <div class="subtitle">Masuk untuk melakukan reservasi</div>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post">
    <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') ?>">

    <label for="name">Nama</label>
    <input id="name" name="name" type="text" autocomplete="username" required>

    <label for="password">Password</label>
    <input id="password" name="password" type="password" autocomplete="current-password" required>

    <button type="submit">Login</button>
  </form>

  <div class="divider">atau</div>

  <!-- Google login aktif setelah GOOGLE_CLIENT_ID di google-config.php diisi -->
  <div id="g_id_onload"
       data-client_id="GANTI_DENGAN_GOOGLE_CLIENT_ID"
       data-callback="handleGoogleCredential">
  </div>
  <div class="g_id_signin" data-type="standard" data-size="large" data-theme="outline"></div>

  <div class="links">
    Belum punya akun?
    <a href="register.php?redirect=<?= urlencode($redirect) ?>">Buat akun</a>
  </div>

  <a class="back" href="index.php">← Kembali ke website</a>
</main>

<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
function handleGoogleCredential(response) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'google-login.php';

    const credential = document.createElement('input');
    credential.type = 'hidden';
    credential.name = 'credential';
    credential.value = response.credential;

    const redirect = document.createElement('input');
    redirect.type = 'hidden';
    redirect.name = 'redirect';
    redirect.value = <?= json_encode($redirect) ?>;

    form.appendChild(credential);
    form.appendChild(redirect);
    document.body.appendChild(form);
    form.submit();
}
</script>
</body>
</html>
