<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];

function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$initial = strtoupper(substr(trim($user['name']), 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil — Tambak Fishing</title>
<style>
body{margin:0;background:#0d1218;color:#f4ead9;font-family:Arial,sans-serif;min-height:100vh;display:grid;place-items:center}
.card{width:min(420px,90%);padding:35px;border:1px solid #cda15a;border-radius:18px;text-align:center;background:#121a22}
.avatar{width:100px;height:100px;border-radius:50%;object-fit:cover;border:2px solid #e8c580}.initial{display:grid;place-items:center;margin:auto;background:#cda15a;color:#0d1218;font-size:40px;font-weight:bold}
h1{margin-bottom:5px}.email{opacity:.7}.links{margin-top:25px;display:flex;gap:10px;justify-content:center;flex-wrap:wrap}.links a{padding:11px 18px;border-radius:8px;text-decoration:none;background:#f4ead9;color:#0d1218}.links a:last-child{background:transparent;color:#e8c580;border:1px solid #cda15a}
</style>
</head>
<body>
<div class="card">
  <?php if (!empty($user['picture'])): ?>
    <img class="avatar" src="<?= e($user['picture']) ?>" alt="Foto profil">
  <?php else: ?>
    <div class="avatar initial"><?= e($initial) ?></div>
  <?php endif; ?>

  <h1><?= e($user['name']) ?></h1>
  <?php if (!empty($user['email'])): ?>
    <div class="email"><?= e($user['email']) ?></div>
  <?php else: ?>
    <div class="email">Akun Tambak Fishing</div>
  <?php endif; ?>

  <div class="links">
    <a href="reservasi.php">Reservasi</a>
    <a href="logout.php">Keluar</a>
  </div>
</div>
</body>
</html>
