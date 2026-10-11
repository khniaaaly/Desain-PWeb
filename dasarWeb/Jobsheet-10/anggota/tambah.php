<?php
require __DIR__ . '/../includes/auth.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Tambah Anggota";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section>
    <h2>Tambah Anggota</h2>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo htmlspecialchars($flash['type']); ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
    <?php endif; ?>

    <!-- [DIUBAH] urutan: Nama, Email, No. Anggota, Tanggal Lahir, Alamat, No. HP -->
    <form id="form-tambah" method="post" action="proses_tambah.php" novalidate>
        <label for="nama">Nama<span style="color: red" class="text-danger"> *</span></label>
        <input type="text" id="nama" name="nama" required>

        <label for="email">Email<span style="color: red" class="text-danger"> *</span></label>
        <input type="email" id="email" name="email" required>

        <label for="no_anggota">No. Anggota<span style="color: red" class="text-danger"> *</span></label>
        <input type="text" id="no_anggota" name="no_anggota" required>

        <label for="tanggal_lahir">Tanggal Lahir<span style="color: red" class="text-danger"> *</span></label>
        <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>

        <label for="alamat">Alamat<span style="color: red" class="text-danger"> *</span></label>
        <textarea id="alamat" name="alamat" rows="3" required></textarea>

        <label for="no_hp">No. HP (opsional)</label>
        <input type="text" id="no_hp" name="no_hp">
        <br>
        <button type="submit" style="margin-top: 1rem;">Simpan</button>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>