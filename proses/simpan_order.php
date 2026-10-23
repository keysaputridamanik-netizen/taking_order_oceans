<?php
require_once '../config/koneksi.php';

// Guard: pelanggan harus login
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

// Guard: keranjang tidak boleh kosong
$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) {
    header("Location: " . BASE_URL . "/pelanggan/keranjang.php");
    exit;
}

$pelanggan_id = (int)$_SESSION['pelanggan_id'];
$no_meja      = mysqli_real_escape_string($koneksi, $_SESSION['pelanggan_meja'] ?? '');
$catatan      = mysqli_real_escape_string($koneksi, $_POST['catatan'] ?? '');
$kode_order   = generateKodeOrder();

mysqli_begin_transaction($koneksi);
try {
    $total = 0;
    $items = [];

    // Lock baris menu & validasi stok
    foreach ($keranjang as $mid => $jml) {
        $mid = (int)$mid;
        $jml = (int)$jml;
        if ($mid <= 0 || $jml <= 0) {
            throw new Exception("Data keranjang tidak valid.");
        }

        $q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id=$mid FOR UPDATE");
        $m = mysqli_fetch_assoc($q);
        if (!$m) {
            throw new Exception("Menu tidak ditemukan.");
        }
        if ($m['status'] !== 'tersedia') {
            throw new Exception("Menu {$m['nama_menu']} sedang tidak tersedia.");
        }
        if ((int)$m['stok'] < $jml) {
            throw new Exception("Stok {$m['nama_menu']} hanya tersisa {$m['stok']}.");
        }

        $items[] = ['menu' => $m, 'jml' => $jml];
        $total  += $m['harga'] * $jml;
    }

    // Insert order
    $q_order = "INSERT INTO orders (kode_order, pelanggan_id, no_meja, total_harga, catatan)
                VALUES ('$kode_order', $pelanggan_id, '$no_meja', $total, '$catatan')";
    if (!mysqli_query($koneksi, $q_order)) {
        throw new Exception("Gagal menyimpan order: " . mysqli_error($koneksi));
    }
    $order_id = mysqli_insert_id($koneksi);

    // Insert detail + kurangi stok
    foreach ($items as $it) {
        $m   = $it['menu'];
        $jml = (int)$it['jml'];
        $sub = $m['harga'] * $jml;
        $mid = (int)$m['id'];
        $nama_menu = mysqli_real_escape_string($koneksi, $m['nama_menu']);

        $q_detail = "INSERT INTO order_detail (order_id, menu_id, nama_menu, harga, jumlah, subtotal)
                     VALUES ($order_id, $mid, '$nama_menu', {$m['harga']}, $jml, $sub)";
        if (!mysqli_query($koneksi, $q_detail)) {
            throw new Exception("Gagal menyimpan detail order: " . mysqli_error($koneksi));
        }

        $q_stok = "UPDATE menu SET stok = stok - $jml WHERE id = $mid";
        if (!mysqli_query($koneksi, $q_stok)) {
            throw new Exception("Gagal update stok: " . mysqli_error($koneksi));
        }
    }

    mysqli_commit($koneksi);

    // Bersihkan keranjang & simpan order terakhir
    $_SESSION['keranjang']     = [];
    $_SESSION['last_order_id'] = $order_id;

    header("Location: " . BASE_URL . "/pelanggan/sukses.php");
    exit;

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    $_SESSION['error_checkout'] = $e->getMessage();
    header("Location: " . BASE_URL . "/pelanggan/keranjang.php");
    exit;
}