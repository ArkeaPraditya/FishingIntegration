<?php
session_start();
require 'database.php';

$redirect = $_POST['redirect'] ?? 'index.php';
if (!in_array($redirect, ['index.php', 'reservasi.php'], true)) {
    $redirect = 'index.php';
}

/*
  Isi GOOGLE_CLIENT_ID dengan Client ID dari Google Cloud Console.
  Untuk produksi, sebaiknya verifikasi ID token dengan library Google API.
  Endpoint tokeninfo dipakai di contoh ini agar mudah dipelajari tanpa Composer.
*/
$googleClientId = 'GANTI_DENGAN_GOOGLE_CLIENT_ID';

$credential = $_POST['credential'] ?? '';
if ($credential === '' || $googleClientId === 'GANTI_DENGAN_GOOGLE_CLIENT_ID') {
    exit('Google Login belum dikonfigurasi. Isi GOOGLE_CLIENT_ID terlebih dahulu.');
}

$url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($credential);
$response = @file_get_contents($url);

if ($response === false) {
    exit('Token Google tidak dapat diverifikasi.');
}

$data = json_decode($response, true);

if (!$data || empty($data['sub']) || empty($data['name'])) {
    exit('Data Google tidak valid.');
}

if (($data['aud'] ?? '') !== $googleClientId) {
    exit('Google Client ID tidak cocok.');
}

$googleId = $data['sub'];
$name = $data['name'];
$email = $data['email'] ?? null;
$picture = $data['picture'] ?? null;

$stmt = $pdo->prepare('SELECT id, name, email, picture FROM users WHERE google_id = ? LIMIT 1');
$stmt->execute([$googleId]);
$user = $stmt->fetch();

if (!$user && $email) {
    $stmt = $pdo->prepare('SELECT id, name, email, picture FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $update = $pdo->prepare('UPDATE users SET google_id = ?, picture = ?, email = ? WHERE id = ?');
        $update->execute([$googleId, $picture, $email, $user['id']]);
    }
}

if (!$user) {
    $insert = $pdo->prepare(
        'INSERT INTO users (name, email, google_id, picture) VALUES (?, ?, ?, ?)'
    );
    $insert->execute([$name, $email, $googleId, $picture]);

    $id = $pdo->lastInsertId();
    $user = [
        'id' => $id,
        'name' => $name,
        'email' => $email,
        'picture' => $picture
    ];
}

session_regenerate_id(true);
$_SESSION['user'] = [
    'id' => $user['id'],
    'name' => $user['name'],
    'email' => $user['email'],
    'picture' => $user['picture']
];

header('Location: ' . $redirect);
exit;
