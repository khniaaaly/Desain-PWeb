<?php
$page_title = "Tambah Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<section>
    <h2>Tambah Buku</h2>

    <?php if ($flash): ?>
    <p class="flash flash-<?php echo e($flash['type']); ?>"><?php echo e($flash['pesan']); ?></p>
    <?php endif; ?>

    <form id="form-tambah" method="post" action="proses_tambah.php" novalidate>
        <label for="judul">Judul<span style="color: red"> * Wajib</span></label>
        <input type="text" id="judul" name="judul" required>
 
        <label for="pengarang">Pengarang<span style="color: red" class="text-danger"> *</span></label>
        <input type="text" id="pengarang" name="pengarang" required>

        <label for="tahun">Tahun Terbit<span style="color: red" class="text-danger"> *</span></label>
        <input type="number" id="tahun" name="tahun" min="1900" max="2026" required>

        <label for="isbn">ISBN<span class="text-secondary fw-normal"> (opsional)</span></label>
        <input type="text" id="isbn" name="isbn">

        <label for="stok">Stok<span style="color: red" class="text-danger"> *</span></label>
        <input type="number" id="stok" name="stok" min="0" required>

        <label for="kategori">Kategori<span style="color: red" class="text-danger"> *</span></label>
        <select id="kategori" name="kategori">
            <option value="fiksi">Fiksi</option>
            <option value="non-fiksi">Non-Fiksi</option>
            <option value="referensi">Referensi</option>
        </select>
        <br>
        <button type="submit" style="margin-top: 1rem;">Simpan</button>
    </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>