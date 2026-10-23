<?php // hapus_keranjang.php
require_once __DIR__ . '/../config/koneksi.php';
$id = (int)($_GET['id'] ?? 0);
unset($_SESSION['keranjang'][$id]);
header("Location: " . $base_url . "pelanggan/keranjang.php");