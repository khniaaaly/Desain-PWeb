<?php
function remember_opts(int $expires): array
{
    return [
        'expires'  => $expires,
        'path'     => '/',
        'httponly' => true,      
        'samesite' => 'Lax',  
    ];
}
function remember_set(PDO $pdo, int $userId): void
{
    $selector  = bin2hex(random_bytes(9));
    $validator = bin2hex(random_bytes(32));
    $expires   = time() + 30 * 86400; // 30 hari

    $stmt = $pdo->prepare(
        "INSERT INTO remember_tokens (user_id, selector, token_hash, expires_at)
         VALUES (:u, :s, :h, to_timestamp(:e))"
    );
    $stmt->execute([
        'u' => $userId,
        's' => $selector,
        'h' => hash('sha256', $validator),
        'e' => $expires,
    ]);
    setcookie('remember', $selector . ':' . $validator, remember_opts($expires));
}
function remember_login(): void
{
    if (isset($_SESSION['user_id']) || empty($_COOKIE['remember'])) {
        return;
    }
    $parts = explode(':', $_COOKIE['remember'], 2);
    if (count($parts) !== 2) {
        return;
    }
    [$selector, $validator] = $parts;

    try {
        global $pdo;
        require_once __DIR__ . '/koneksi.php';

        $stmt = $pdo->prepare(
            "SELECT t.token_hash, u.id AS uid, u.nama, u.role
             FROM remember_tokens t
             JOIN users u ON u.id = t.user_id
             WHERE t.selector = :s AND t.expires_at > NOW()"
        );
        $stmt->execute(['s' => $selector]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && hash_equals($row['token_hash'], hash('sha256', $validator))) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $row['uid'];
            $_SESSION['nama']    = $row['nama'];
            $_SESSION['role']    = $row['role'];
        }
    } catch (Throwable $e) {
    }
}
function remember_clear(): void
{
    if (!empty($_COOKIE['remember'])) {
        $selector = explode(':', $_COOKIE['remember'], 2)[0];
        try {
            global $pdo;
            require_once __DIR__ . '/koneksi.php';
            $pdo->prepare("DELETE FROM remember_tokens WHERE selector = :s")
                ->execute(['s' => $selector]);
        } catch (Throwable $e) {
        }
    }
    setcookie('remember', '', remember_opts(time() - 3600));
}