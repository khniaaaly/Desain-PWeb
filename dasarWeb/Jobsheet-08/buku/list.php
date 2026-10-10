<?php
session_start();

require_once __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {
    $id = $_POST['hapus_id'];

    $stmt = $pdo->prepare("DELETE FROM buku WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Buku berhasil dihapus.'
    ];

    header('Location: list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    $pdo->exec("DELETE FROM buku");

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Semua data buku berhasil dihapus.'
    ];

    header('Location: list.php');
    exit;
}

$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$keyword = $_GET['q'] ?? '';

if ($keyword !== '') {
    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :keyword ORDER BY id DESC"
    );
    $stmt->execute([
        'keyword' => '%' . $keyword . '%'
    ]);
    $daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $daftarBuku = $pdo
        ->query("SELECT * FROM buku ORDER BY id DESC")
        ->fetchAll(PDO::FETCH_ASSOC);
}
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
                        <th>ISBN</th>
                        <th>Stok</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                        <th>Tanggal Ditambahkan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftarBuku)): ?>
                    <tr>
                        <td colspan="5">Belum ada data buku. Silahkan tambah lewat menu "Tambah Buku"</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($daftarBuku as $buku): ?>
                        <tr>
                            <td><?php echo $buku['judul']; ?></td>
                            <td><?php echo $buku['pengarang']; ?></td>
                            <td><?php echo $buku['tahun']; ?></td>
                            <td><?php echo $buku['isbn']; ?></td>
                            <td><?php echo $buku['stok']; ?></td>
                            <td><?php echo $buku['kategori']; ?></td>
                            <td>
                                <button type="button" class="btn-edit">Edit</button>
                                <button type="button" class="btn-detail">Detail</button>
                                <form method="post" action="list.php" style="display:inline">
                                    <input type="hidden" name="hapus_id" value="<?php echo $buku['id']; ?>">
                                    <button class="btn-hapus">Hapus</button>
                                </form>
                            </td>
                            <td><?php echo date('d-m-Y H:i', strtotime($buku['tanggal_ditambahkan'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
            <form method="post" action="list.php">
                <button type="submit"
                        name="reset"
                        value="1"
                        class="btn-hapus btn-kecil">
                    Reset Data
                </button>
            </form>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>