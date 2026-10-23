<?php
require_once __DIR__ . '/../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) redirect('/admin/login.php');

$id = (int)($_GET['id'] ?? 0);
$m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT gambar FROM menu WHERE id=$id"));
if ($m && !empty($m['gambar'])) {
    $path = __DIR__ . '/../assets/uploads/menu/' . $m['gambar'];
    if (file_exists($path)) @unlink($path);
}
mysqli_query($koneksi, "DELETE FROM menu WHERE id=$id");
$_SESSION['pesan'] = "Menu berhasil dihapus.";
redirect('/admin/menu.php');