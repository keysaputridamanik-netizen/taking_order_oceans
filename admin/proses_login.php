<?php
require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/admin/login.php');
}

$username = mysqli_real_escape_string($koneksi, $_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$q = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' LIMIT 1");
$admin = mysqli_fetch_assoc($q);

if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id']   = $admin['id'];
    $_SESSION['admin_nama'] = $admin['nama_lengkap'];
    redirect('/admin/dashboard.php');   // → BASE_URL + /admin/dashboard.php (tidak dobel)
}

$_SESSION['error'] = "Username atau password salah.";
redirect('/admin/login.php');