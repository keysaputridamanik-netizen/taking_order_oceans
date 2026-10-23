<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_cafe";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) die("Koneksi database gagal: " . mysqli_connect_error());

date_default_timezone_set('Asia/Jakarta');

/* =========================================================
 * BASE_URL — TANPA trailing slash, TANPA /admin, TANPA /pelanggan
 * Semua redirect & link WAJIB pakai BASE_URL . '/...'
 * ========================================================= */
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/taking_order_oceans');
}

function rupiah($angka) {
    return "Rp " . number_format((float)$angka, 0, ',', '.');
}

function generateKodeOrder() {
    return "ORD-" . date("YmdHis") . "-" . rand(100, 999);
}

/**
 * Upload gambar menu. Return nama file atau null.
 */
function uploadGambarMenu(array $file, ?string $dir = null): ?string {
    $dir = $dir ?? __DIR__ . '/../assets/uploads/menu/';
    if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) return null;

    $allowed = ['image/jpeg'=>'jpg', 'image/png'=>'png', 'image/webp'=>'webp'];
    $mime = mime_content_type($file['tmp_name']);
    if (!isset($allowed[$mime])) return null;
    if ($file['size'] > 3 * 1024 * 1024) return null;

    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $ext  = $allowed[$mime];
    $name = 'menu_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

    return move_uploaded_file($file['tmp_name'], $dir . $name) ? $name : null;
}

/**
 * Helper redirect — SELALU pakai path absolut.
 * Contoh: redirect('/admin/dashboard.php');
 */
function redirect(string $path = '/') {
    $path = '/' . ltrim($path, '/');
    header("Location: " . BASE_URL . $path);
    exit;
}