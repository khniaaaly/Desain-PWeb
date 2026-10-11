<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
$page_title = "Login";
include __DIR__ . '/../includes/header.php';
?>
<main>
    <h2>Login Petugas</h2>
    <?php
    $sisa = ($_SESSION['login_kunci_sampai'] ?? 0) - time();
    if ($sisa > 0):
    ?>
        <div class="alert alert-error" id="kunci-box">
            Terlalu banyak percobaan gagal. Coba lagi dalam
            <strong id="hitung-mundur"><?php echo (int) $sisa; ?></strong> detik.
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php elseif (isset($_SESSION['flash'])): ?>
        <div class="alert alert-<?php echo e($_SESSION['flash']['type']); ?>">
            <?php echo e($_SESSION['flash']['pesan']); ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
    <form action="proses_login.php" method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label class="remember-me">
            <input type="checkbox" name="remember" value="1"> Ingat saya
        </label>

        <button type="submit">Masuk</button>
    </form>
    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</main>
<script>
(function () {
    var el = document.getElementById('hitung-mundur');
    if (!el) return;
    var sisa = parseInt(el.textContent, 10);
    var box = document.getElementById('kunci-box');

    var timer = setInterval(function () {
        sisa--;
        if (sisa <= 0) {
            clearInterval(timer);
            box.className = 'alert alert-success';
            box.textContent = 'Anda sudah bisa mencoba login lagi.';
            return;
        }
        el.textContent = sisa;
    }, 1000);
})();
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>