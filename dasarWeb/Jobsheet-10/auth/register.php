<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: --/index.php');
    exit;
}
$page_title = "Registrasi Petugas";
include __DIR__ . '/../includes/header.php';
?>
<main>
    <h2>Registrasi Petugas</h2>
    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-<?php echo e($_SESSION['flash']['type']); ?>">
            <?php echo e($_SESSION['flash']['pesan']); ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
    <form action="proses_register.php" method="post">
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" required>

        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" minlength="6" required>

        <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>