<?php
require_once __DIR__ . '/../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: " . BASE_URL . "/index.php"); exit; }

$menu_id = (int)($_POST['menu_id'] ?? 0);
$jumlah  = max(1, (int)($_POST['jumlah'] ?? 1));

$q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id=$menu_id AND status='tersedia'");
$menu = mysqli_fetch_assoc($q);
if (!$menu) {
    $_SESSION['pesan'] = "Menu tidak ditemukan.";
    header("Location: " . BASE_URL . "/pelanggan/menu.php"); exit;
}
if (!isset($_SESSION['keranjang'])) $_SESSION['keranjang'] = [];
$sudah = $_SESSION['keranjang'][$menu_id] ?? 0;
$sisa  = (int)$menu['stok'] - $sudah;

if ($sisa <= 0) {
    $_SESSION['pesan'] = "Stok {$menu['nama_menu']} sudah penuh di keranjang.";
    header("Location: " . BASE_URL . "/pelanggan/menu.php"); exit;
}

$tambah = min($jumlah, $sisa);
$_SESSION['keranjang'][$menu_id] = $sudah + $tambah;
$_SESSION['pesan'] = "{$menu['nama_menu']} × {$tambah} ditambahkan ke keranjang.";

$ref = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/pelanggan/menu.php');
header("Location: $ref");