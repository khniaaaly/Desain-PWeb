<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Edit Anggota";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
$stmt->execute(['id' => $id]);
$anggota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anggota) {
    header('Location: list.php');
    exit;
}
?>
<section>
    <h2>Edit Anggota</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo e($flash['type']); ?>"><?php echo e($flash['pesan']); ?></p>
    <?php endif; ?>

    <form id="form-tambah" class="form-edit" method="post" action="proses_edit.php">
        <input type="hidden" name="id" value="<?php echo e($anggota['id']); ?>">

        <p>
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" value="<?php echo e($anggota['nama']); ?>" required>
        </p>
        <p>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo e($anggota['email']); ?>" required>
        </p>
        <p>
            <label for="no_anggota">No. Anggota</label>
            <input type="text" id="no_anggota" name="no_anggota" value="<?php echo e($anggota['no_anggota']); ?>" required>
        </p>
        <p>
            <label for="tanggal_lahir">Tanggal Lahir</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo e($anggota['tanggal_lahir']); ?>" required>
        </p>
        <p>
            <label for="alamat">Alamat</label>
            <textarea id="alamat" name="alamat" rows="3" required><?php echo e($anggota['alamat']); ?></textarea>
        </p>
        <p>
            <label for="no_hp">No. HP (opsional)</label>
            <input type="text" id="no_hp" name="no_hp" value="<?php echo e($anggota['no_hp']); ?>">
        </p>

        <button type="submit">Simpan Perubahan</button>
        <a href="list.php">Batal</a>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>