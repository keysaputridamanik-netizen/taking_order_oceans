<?php // update_keranjang.php
require_once __DIR__ . '/../config/koneksi.php';
$menu_id = (int)($_POST['menu_id'] ?? 0);
$jumlah  = max(1, (int)($_POST['jumlah'] ?? 1));

$q = mysqli_query($koneksi, "SELECT stok FROM menu WHERE id=$menu_id");
$m = mysqli_fetch_assoc($q);
if ($m) {
    if ($jumlah > (int)$m['stok']) $jumlah = (int)$m['stok'];
    if (isset($_SESSION['keranjang'][$menu_id])) $_SESSION['keranjang'][$menu_id] = $jumlah;
}
header("Location: " . $base_url . "pelanggan/keranjang.php");