<?php
session_start();
require_once __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    $pdo->exec("DELETE FROM anggota");
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Semua data anggota dihapus.'];
    header('Location: list.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {
    $id = (int)$_POST['hapus_id'];
    $stmt = $pdo->prepare("DELETE FROM anggota WHERE id = :id");
    $stmt->execute(['id' => $id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil dihapus.'];
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'pesan' => "Tidak ada data yang terhapus (id yang dikirim: $id)."];
    }
    header('Location: list.php');
    exit;
}
$page_title = "Daftar Anggota";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarAnggota = $pdo->query("SELECT * FROM anggota ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);
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
                <!-- [DIUBAH] urutan: Nama, Email, No. Anggota, Tanggal Lahir, Alamat, No. HP, Aksi -->
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Anggota</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat</th>
                    <th>No. HP</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarAnggota)): ?>
                <tr>
                    <td colspan="7">Belum ada data anggota. Silakan tambah lewat menu "Tambah Anggota".</td>
                </tr>
                <?php else: ?>
                <?php foreach ($daftarAnggota as $anggota): ?>
                <tr data-cari="<?php echo htmlspecialchars(strtolower($anggota['nama'] ?? '')); ?>">
                    <td><?php echo htmlspecialchars($anggota['nama'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($anggota['email'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars($anggota['no_anggota'] ?? '-'); ?></td>
                    <td>
                        <?php
                        echo !empty($anggota['tanggal_lahir'])
                            ? htmlspecialchars(date('d-m-Y', strtotime($anggota['tanggal_lahir'])))
                            : '-';
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($anggota['alamat'] ?? '-'); ?></td>
                    <td><?php echo htmlspecialchars(($anggota['no_hp'] ?? '') !== '' ? $anggota['no_hp'] : '-'); ?></td>
                    <td>
                        <button type="button" class="btn-edit">Edit</button>
                        <button type="button" class="btn-detail">Detail</button>
                        <form method="post" action="list.php" style="display:inline">
                            <input type="hidden" name="hapus_id" value="<?php echo $anggota['id']; ?>">
                            <button class="btn-hapus">Hapus</button>
                        </form>
                    </td>
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