<?php
session_start();

require_once __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tambah.php');
    exit;
}

$nama          = trim($_POST['nama'] ?? '');
$email         = trim($_POST['email'] ?? '');
$no_anggota    = trim($_POST['no_anggota'] ?? '');
$tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
$alamat        = trim($_POST['alamat'] ?? '');
$no_hp         = trim($_POST['no_hp'] ?? '');

$errors = [];
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($email === '') {
    $errors[] = "Email wajib diisi.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid.";
}
if ($no_anggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
}
if ($tanggal_lahir === '') {
    $errors[] = "Tanggal lahir wajib diisi.";
}
if ($alamat === '') {
    $errors[] = "Alamat wajib diisi.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'danger', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO anggota (nama, email, no_anggota, tanggal_lahir, alamat, no_hp)
        VALUES (:nama, :email, :no_anggota, :tanggal_lahir, :alamat, :no_hp)
    ");

    $stmt->execute([
        'nama'          => $nama,
        'email'         => $email,
        'no_anggota'    => $no_anggota,
        'tanggal_lahir' => $tanggal_lahir,
        'alamat'        => $alamat,
        'no_hp'         => $no_hp,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    if ($e->getCode() === '23505') {
        $pesan = 'No. Anggota sudah dipakai, gunakan nomor lain.';
    } else {
        $pesan = 'Gagal menyimpan data: ' . $e->getMessage();
    }
    $_SESSION['flash'] = ['type' => 'danger', 'pesan' => $pesan];
    header('Location: tambah.php');
    exit;
}