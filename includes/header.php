<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($judul) ? $judul : 'Taking Order Cafe'; ?></title>

<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        navy:  { 900:'#0C3B7C', 800:'#0E4DA4', 700:'#1B4998' },
        ocean: { 500:'#00A7E1', 400:'#29A3EE' },
        ice:   { 50:'#F0F8FF', 100:'#E6F3FA' },
        sand:  { 500:'#C69C6D', 400:'#D8B485' },
        ink:   '#2B3A4A',
        line:  'rgba(14,77,164,0.10)',
      },
      fontFamily: {
        sans: ['Inter','ui-sans-serif','system-ui','Segoe UI','sans-serif'],
        brand: ['"Fraunces"','Georgia','serif'],
      },
      boxShadow: {
        soft: '0 6px 20px rgba(12,59,124,0.06)',
        lift: '0 12px 32px rgba(12,59,124,0.10)',
      },
      borderRadius: { xl2: '1.125rem' }
    }
  }
}
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-ice-50 text-ink font-sans antialiased">