<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/remember.php';
remember_login();

function wajib_role(string $role, string $kembali = 'list.php'): void
{
    if (($_SESSION['role'] ?? '') !== $role) {
        $_SESSION['flash'] = [
            'type'  => 'error',
            'pesan' => 'Anda tidak memiliki akses untuk menghapus anggota.',
        ];
        header('Location: ' . $kembali);
        exit;
    }
}