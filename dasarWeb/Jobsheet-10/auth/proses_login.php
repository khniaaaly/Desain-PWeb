<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

const MAKS_GAGAL = 5;   // percobaan sebelum dikunci
const WAKTU_KUNCI = 60; // detik

$sisaKunci = ($_SESSION['login_kunci_sampai'] ?? 0) - time();
if ($sisaKunci > 0) {
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama']    = $user['nama'];
    $_SESSION['role']    = $user['role'];

    if (!empty($_POST['remember'])) {
        require_once __DIR__ . '/../includes/remember.php';
        remember_set($pdo, (int) $user['id']);
    }
    header('Location: ../index.php');
    exit;
}

$_SESSION['login_gagal'] = ($_SESSION['login_gagal'] ?? 0) + 1;

if ($_SESSION['login_gagal'] >= MAKS_GAGAL) {
    $_SESSION['login_kunci_sampai'] = time() + WAKTU_KUNCI;
    $_SESSION['login_gagal'] = 0;
    $pesan = 'Terlalu banyak percobaan gagal. Login dikunci selama ' . WAKTU_KUNCI . ' detik.';
} elseif ($_SESSION['login_gagal'] >= 3) {
    $sisa = MAKS_GAGAL - $_SESSION['login_gagal'];
    $pesan = "Username atau password salah. Sisa percobaan: $sisa.";
} else {
    $pesan = 'Username atau password salah.';
}

$_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Username atau password salah.'];
header('Location: login.php');
exit;