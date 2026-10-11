<?php
require __DIR__ . '/../includes/auth.php';
session_start();
require_once __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id        = $_POST['id'] ?? null;
$judul     = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun     = trim($_POST['tahun'] ?? '');
$isbn      = trim($_POST['isbn'] ?? '');
$stok      = trim($_POST['stok'] ?? '');
$kategori  = trim($_POST['kategori'] ?? '');

if (!$id) {
    header('Location: list.php');
    exit;
}

$errors = [];
if ($judul === '')     $errors[] = "Judul wajib diisi.";
if ($pengarang === '') $errors[] = "Pengarang wajib diisi.";
if ($tahun === '' || !ctype_digit($tahun)) $errors[] = "Tahun harus berupa angka.";
if ($stok === '' || !ctype_digit($stok))   $errors[] = "Stok harus berupa angka (0 atau lebih).";
if ($kategori === '')  $errors[] = "Kategori wajib dipilih.";

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'danger', 'pesan' => implode(' ', $errors)];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE buku
        SET judul = :judul, pengarang = :pengarang, tahun = :tahun,
            isbn = :isbn, stok = :stok, kategori = :kategori
        WHERE id = :id
    ");
    $stmt->execute([
        'judul'     => $judul,
        'pengarang' => $pengarang,
        'tahun'     => (int) $tahun,
        'isbn'      => $isbn,
        'stok'      => (int) $stok,
        'kategori'  => $kategori,
        'id'        => $id,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil diperbarui.'];
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['flash'] = ['type' => 'danger', 'pesan' => 'Gagal menyimpan perubahan: ' . $e->getMessage()];
    header('Location: edit.php?id=' . urlencode($id));
    exit;
}