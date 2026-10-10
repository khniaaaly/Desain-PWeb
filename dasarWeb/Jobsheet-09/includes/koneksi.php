<?php

$host = '127.0.0.1';
$port = '5432';
$db = 'simpus_mini';
$user = 'postgres';
$password = 'khania20';

$dsn = "pgsql:host=$host;port=$port;dbname=$db";

try {
    $pdo = new PDO(
        $dsn,
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>