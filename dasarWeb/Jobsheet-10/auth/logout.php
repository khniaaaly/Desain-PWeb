<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/remember.php';
remember_clear();    
session_destroy();
header('Location: login.php');
exit;