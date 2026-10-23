<?php // pelanggan/logout_pelanggan.php
require_once __DIR__ . '/../config/koneksi.php';
unset($_SESSION['pelanggan_id'], $_SESSION['pelanggan_nama'], $_SESSION['pelanggan_meja'], $_SESSION['keranjang'], $_SESSION['last_order_id']);
header("Location: ../index.php");