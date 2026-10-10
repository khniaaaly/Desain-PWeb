<?php

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    session_destroy();
    header('Location: list.php');
    exit;
}

$page_title = "Daftar Anggota";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarAnggota = $_SESSION['anggota'] ?? [];
?>
<section>
    <h2>Daftar Anggota</h2>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo htmlspecialchars($flash['type']); ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
    <?php endif; ?>

    <div class="search-box">
        <label for="search-input">Cari Nama Anggota</label>
        <input type="text" id="search-input" placeholder="Ketik nama anggota...">
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarAnggota)): ?>
                <tr>
                    <td colspan="4">Belum ada data anggota. Silakan tambah lewat menu "Tambah Anggota".</td>
                </tr>
                <?php else: ?>
                <?php foreach ($daftarAnggota as $anggota): ?>
                <tr data-cari="<?php echo htmlspecialchars(strtolower($anggota['nama'])); ?>">
                    <td><?php echo htmlspecialchars($anggota['nama']); ?></td>
                    <td><?php echo htmlspecialchars($anggota['email']); ?></td>
                    <td><?php echo htmlspecialchars($anggota['telepon'] !== '' ? $anggota['telepon'] : '-'); ?></td>
                    <td>
                        <button type="button" class="btn-edit">Edit</button>
                                <button type="button" class="btn-detail">Detail</button>
                                <button type="button" class="btn-hapus">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <button type="submit" name="reset" value="1" class="btn-hapus btn-kecil">Reset Data</button>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>