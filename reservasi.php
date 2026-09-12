<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php?redirect=reservasi.php');
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reservasi — Tambak Fishing</title>
<style>
body{margin:0;background:#0d1218;color:#f4ead9;font-family:Arial,sans-serif;min-height:100vh;display:grid;place-items:center}
.card{width:min(600px,90%);padding:35px;border:1px solid #cda15a;border-radius:18px;background:#121a22}
h1{color:#e8c580}.user{opacity:.8;margin-bottom:25px}.row{display:grid;gap:8px;margin:16px 0}input,select{padding:12px;border-radius:8px;border:1px solid #555;background:#18212b;color:#fff}button{padding:13px;border:0;border-radius:8px;background:#f4ead9;color:#0d1218;cursor:pointer}.back{display:inline-block;margin-top:15px;color:#e8c580}
</style>
</head>
<body>
<div class="card">
  <h1>Reservasi Pemancingan</h1>
  <div class="user">Login sebagai <strong><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></strong></div>

  <!-- Form reservasi dapat kamu kembangkan setelah database reservasi dibuat. -->
  <form method="post" action="#">
    <div class="row">
      <label>Tanggal</label>
      <input type="date" required>
    </div>
    <div class="row">
      <label>Jam</label>
      <input type="time" required>
    </div>
    <div class="row">
      <label>Pilihan pemancingan</label>
      <select required>
        <option value="">Pilih lokasi</option>
        <option>Pemancingan Mas Arkea</option>
      </select>
    </div>
    <button type="submit">Lanjutkan Reservasi</button>
  </form>

  <a class="back" href="index.php">← Kembali ke website</a>
</div>
</body>
</html>
