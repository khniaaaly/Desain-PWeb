<?php
require_once __DIR__ . '/../includes/koneksi.php';

$file = __DIR__ . '/../../Jobsheet-06/data/buku.json';

if (!file_exists($file)) {
    die("File tidak ditemukan: " . htmlspecialchars($file));
}

$data = json_decode(file_get_contents($file), true);

if (!is_array($data) || empty($data)) {
    die("Isi JSON tidak valid: " . json_last_error_msg());
}

$kolom       = array_keys($data[0]);
$daftarKolom = implode(', ', $kolom);
$placeholder = ':' . implode(', :', $kolom);

$stmt = $pdo->prepare("INSERT INTO buku ($daftarKolom) VALUES ($placeholder)");

try {
    $pdo->beginTransaction();

    foreach ($data as $baris) {
        $stmt->execute($baris);
    }

    $pdo->commit();

    $namaDb = $pdo->query("SELECT current_database()")->fetchColumn();

    echo "<h3>Proses Migrasi Berhasil</h3>";
    echo "<p>Sebanyak <b>" . count($data) . "</b> buku telah disalin ke tabel <code>buku</code> "
        . "pada database <i>" . htmlspecialchars($namaDb) . "</i>.</p>";
    echo "<p>Sumber data: <i>" . htmlspecialchars(basename($file)) . "</i></p>";
    echo "<p><a href='list.php'>Lihat Daftar Buku</a></p>";
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "<h3>Proses Impor Gagal</h3>";
    echo "<p>Tidak ada data yang tersimpan. Penyebab: " . htmlspecialchars($e->getMessage()) . "</p>";
}