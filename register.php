<?php
session_start();
require 'database.php';

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$redirect = $_GET['redirect'] ?? $_POST['redirect'] ?? 'index.php';
if (!in_array($redirect, ['index.php', 'reservasi.php'], true)) {
    $redirect = 'index.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || strlen($name) < 2) {
        $error = 'Nama minimal 2 karakter.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $confirm) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        $check = $pdo->prepare('SELECT id FROM users WHERE name = ? LIMIT 1');
        $check->execute([$name]);

        if ($check->fetch()) {
            $error = 'Nama tersebut sudah digunakan. Silakan pilih nama lain.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                'INSERT INTO users (name, password) VALUES (?, ?)'
            );
            $stmt->execute([$name, $hash]);

            $id = $pdo->lastInsertId();

            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => $id,
                'name' => $name,
                'email' => null,
                'picture' => null
            ];

            header('Location: ' . $redirect);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buat Akun — Tambak Fishing</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#cda15a;--gold2:#e8c580;--cream:#f4ead9;--ink:#0d1218;--muted:#bfb5a7}
*{box-sizing:border-box}body{margin:0;min-height:100vh;font-family:Jost,sans-serif;color:var(--cream);background:linear-gradient(180deg,rgba(6,10,16,.72),rgba(6,10,16,.94)),url('gambartambak.png') center/cover fixed;display:grid;place-items:center;padding:24px}
.card{width:min(440px,100%);background:rgba(13,18,24,.84);border:1px solid rgba(232,197,128,.3);border-radius:20px;padding:36px;box-shadow:0 25px 80px rgba(0,0,0,.45);backdrop-filter:blur(12px)}
.logo{text-align:center;color:var(--gold2);font-family:"Cormorant Garamond",serif;font-size:2.2rem;letter-spacing:.12em}.subtitle{text-align:center;color:var(--muted);margin:4px 0 28px}
label{display:block;margin:14px 0 7px;font-size:.8rem;letter-spacing:.08em;text-transform:uppercase}input{width:100%;padding:13px 14px;border-radius:9px;border:1px solid rgba(244,234,217,.22);background:rgba(255,255,255,.06);color:var(--cream);outline:none}input:focus{border-color:var(--gold2)}
button{width:100%;padding:13px;margin-top:20px;border:0;border-radius:9px;cursor:pointer;font-family:Jost;background:var(--cream);color:var(--ink);letter-spacing:.12em;text-transform:uppercase}
.error{padding:10px 12px;border-radius:8px;background:rgba(170,50,50,.2);border:1px solid rgba(220,100,100,.35);color:#ffd6d6;margin-bottom:14px;font-size:.9rem}
.links{text-align:center;margin-top:22px;color:var(--muted);font-size:.9rem}.links a,.back{color:var(--gold2);text-decoration:none}.back{display:block;text-align:center;margin-top:14px;font-size:.85rem}
</style>
</head>
<body>
<main class="card">
  <div class="logo">TAMBAK FISHING</div>
  <div class="subtitle">Buat akun untuk melanjutkan reservasi</div>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post">
    <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') ?>">

    <label for="name">Nama</label>
    <input id="name" name="name" type="text" autocomplete="name" required>

    <label for="password">Password</label>
    <input id="password" name="password" type="password" minlength="6" autocomplete="new-password" required>

    <label for="confirm_password">Konfirmasi Password</label>
    <input id="confirm_password" name="confirm_password" type="password" minlength="6" autocomplete="new-password" required>

    <button type="submit">Buat Akun</button>
  </form>

  <div class="links">
    Sudah punya akun?
    <a href="login.php?redirect=<?= urlencode($redirect) ?>">Login</a>
  </div>
  <a class="back" href="index.php">← Kembali ke website</a>
</main>
</body>
</html>
