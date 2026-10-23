<nav class="bg-navy-800 text-white">
  <div class="max-w-7xl mx-auto px-5 sm:px-6">
    <div class="flex items-center justify-between h-16">
      <a href="<?= BASE_URL ?>/pelanggan/menu.php" class="brand-lockup text-white">
        <img src="<?= BASE_URL ?>/assets/logo-brand.png" alt="Taking Order Cafe">
        <span class="brand-text hidden sm:inline">Taking Order <em>Cafe</em></span>
      </a>

      <?php if (isset($_SESSION['pelanggan_id'])): ?>
      <div class="flex items-center gap-2 text-sm">
        <div class="hidden md:flex items-center gap-2 text-ice-100/90 mr-2">
          <iconify-icon icon="mdi:account-circle" width="18"></iconify-icon>
          <span><?= htmlspecialchars($_SESSION['pelanggan_nama']); ?></span>
          <span class="text-white/30">|</span>
          <span>Meja <?= htmlspecialchars($_SESSION['pelanggan_meja']); ?></span>
        </div>

        <a href="<?= BASE_URL ?>/pelanggan/keranjang.php" class="btn-accent btn-sm">
          <iconify-icon icon="mdi:cart-outline" width="16"></iconify-icon>
          <span class="hidden sm:inline">Keranjang</span>
          <span class="bg-navy-900 text-white text-xs font-bold rounded-full w-5 h-5 inline-flex items-center justify-center">
            <?= isset($_SESSION['keranjang']) ? count($_SESSION['keranjang']) : 0; ?>
          </span>
        </a>

        <a href="<?= BASE_URL ?>/pelanggan/logout_pelanggan.php" class="btn-ghost btn-sm !text-white !border-white/25 hover:!bg-white/10">
          <iconify-icon icon="mdi:logout" width="16"></iconify-icon>
          <span class="hidden sm:inline">Selesai</span>
        </a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</nav>