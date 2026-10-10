<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_index'])) {
    session_start();
    $index = $_POST['hapus_index'];

    if (isset($_SESSION['buku'][$index])) {
        unset($_SESSION['buku'][$index]);
        $_SESSION['buku'] = array_values($_SESSION['buku']);
        $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil dihapus.'];
    } else {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Buku tidak ditemukan.'];
    }

    header('Location: list.php');
    exit;
}

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    session_destroy();
    header('Location: list.php');
    exit;
}

$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$daftarBuku = $_SESSION['buku'] ?? [];
?>
        <section>
            <h2>Daftar Buku</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo e($flash['type']); ?>"><?php echo e($flash['pesan']); ?></p>
            <?php endif; ?>
            
            <div class="search-box">
                <label for="search-input">Cari Judul Buku</label>
                <input type="text" id="search-input" placeholder="Ketik judul buku...">
            </div>

            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Tahun</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <td colspan="5">Belum ada data buku. Silahkan tambah lewat menu "Tambah Buku"</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarBuku as $i => $buku): ?>
                        <tr>
                            <td><?php echo $buku['judul']; ?></td>
                            <td><?php echo $buku['pengarang']; ?></td>
                            <td><?php echo $buku['tahun']; ?></td>
                            <td><?php echo $buku['stok']; ?></td>
                            <td>
                                <button type="button" class="btn-edit">Edit</button>
                                <button type="button" class="btn-detail">Detail</button>
                                <form method="post" action="list.php" style="display:inline">
                                    <input type="hidden" name="hapus_index" value="<?php echo $i; ?>">
                                    <button class="btn-hapus">Hapus</button>
                                </form>
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