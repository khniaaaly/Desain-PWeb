<?php
session_start();

$nama    = trim($_POST['nama'] ?? '');
$email   = trim($_POST['email'] ?? '');
$telepon = trim($_POST['telepon'] ?? '');
$alamat  = trim($_POST['alamat'] ?? '');

$errors = [];
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($email === '') {
    $errors[] = "Email wajib diisi.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid.";
} else {
    foreach ($_SESSION['anggota'] ?? [] as $a) {
        if (strcasecmp($a['email'], $email) === 0) {
            $errors[] = "Email sudah terdaftar.";
            break;
        }
    }
}
if ($no_anggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
} else {
    foreach ($_SESSION['anggota'] ?? [] as $a) {
        if (strcasecmp($a['no_anggota'] ?? '', $no_anggota) === 0) {
            $errors[] = "No. Anggota sudah terdaftar.";
            break;
        }
    }
}

if ($tanggal_lahir === '') {
    $errors[] = "Tanggal lahir wajib diisi.";
} else {
    $tgl = DateTime::createFromFormat('Y-m-d', $tanggal_lahir);
    if (!$tgl || $tgl->format('Y-m-d') !== $tanggal_lahir) {
        $errors[] = "Format tanggal lahir tidak valid.";
    } elseif ($tgl > new DateTime('today')) {
        $errors[] = "Tanggal lahir tidak boleh di masa depan.";
    }
}
if ($alamat === '') {
    $errors[] = "Alamat wajib diisi.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

if (!isset($_SESSION['anggota'])) {
    $_SESSION['anggota'] = [];
}

$_SESSION['anggota'][] = [
    'nama'          => $nama,
    'email'         => $email,
    'no_anggota'    => $no_anggota,
    'tanggal_lahir' => $tanggal_lahir,
    'alamat'        => $alamat,
];

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
header('Location: list.php');
exit;