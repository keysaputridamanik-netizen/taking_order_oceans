<?php
require_once '../config/koneksi.php';
if (isset($_SESSION['admin_id'])) { header("Location: " . BASE_URL . "/admin/dashboard.php"); exit; }
$judul = "Login Admin";
include '../includes/header.php';
?>
<main class="min-h-screen grid lg:grid-cols-2">

  <section class="hidden lg:flex relative bg-navy-900 text-white overflow-hidden">
    <div class="absolute inset-0 opacity-[0.06]"
         style="background-image:radial-gradient(circle at 20% 30%, #D8B485 0, transparent 50%), radial-gradient(circle at 85% 75%, #00A7E1 0, transparent 45%);"></div>
    <div class="relative z-10 flex flex-col justify-between p-14 w-full">
      <a href="<?= BASE_URL ?>/index.php" class="brand-lockup">
        <img src="<?= BASE_URL ?>/assets/logo-brand.png" alt="Taking Order Cafe" class="h-10 brightness-0 invert">
        <span class="brand-text text-white text-xl">Taking Order <em class="not-italic text-sand-400">Cafe</em></span>
      </a>
      <div class="max-w-sm">
        <p class="text-sand-400 text-sm font-semibold tracking-widest uppercase mb-3">Admin Panel</p>
        <h1 class="font-brand text-4xl leading-tight mb-5">Kelola menu, pesanan, dan laporan dari satu tempat.</h1>
        <p class="text-ice-100/70 leading-relaxed">Akses khusus administrator kafe.</p>
      </div>
      <p class="text-ice-100/40 text-xs">&copy; <?= date('Y'); ?> Taking Order Cafe</p>
    </div>
  </section>

  <section class="flex items-center justify-center px-5 py-12 bg-ice-50">
    <div class="w-full max-w-sm">
      <div class="lg:hidden text-center mb-8">
        <img src="<?= BASE_URL ?>/assets/logo-brand.png" alt="Brand" class="h-14 mx-auto mb-3">
      </div>

      <h2 class="font-brand text-2xl text-navy-900 mb-1">Login Admin</h2>
      <p class="text-sm text-ink/60 mb-7">Masukkan kredensial administrator.</p>

      <?php if (!empty($_SESSION['error'])): ?>
        <div class="mb-5 flex items-start gap-2 bg-red-50 border border-red-200 text-red-800 px-3.5 py-3 rounded-lg text-sm">
          <iconify-icon icon="mdi:alert-circle-outline" class="mt-0.5" width="18"></iconify-icon>
          <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
      <?php endif; ?>

      <form action="<?= BASE_URL ?>/admin/proses_login.php" method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-navy-900 mb-1.5">Username</label>
          <input type="text" name="username" required class="input-soft">
        </div>
        <div>
          <label class="block text-sm font-semibold text-navy-900 mb-1.5">Password</label>
          <input type="password" name="password" required class="input-soft">
        </div>
        <button type="submit" class="btn-primary w-full !py-3">
          Login <iconify-icon icon="mdi:arrow-right" width="18"></iconify-icon>
        </button>
      </form>

      <div class="mt-7 pt-5 border-t border-[rgba(14,77,164,0.10)] text-center">
        <a href="<?= BASE_URL ?>/index.php" class="inline-flex items-center gap-1.5 text-sm text-ink/50 hover:text-navy-800 transition-colors">
          <iconify-icon icon="mdi:arrow-left" width="16"></iconify-icon> Halaman Pelanggan
        </a>
      </div>
    </div>
  </section>
</main>
<?php include '../includes/footer.php'; ?>