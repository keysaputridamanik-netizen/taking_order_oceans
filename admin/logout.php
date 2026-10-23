<?php
require_once __DIR__ . '/../config/koneksi.php';
unset($_SESSION['admin_id'], $_SESSION['admin_nama']);
session_destroy();
redirect('/admin/login.php');