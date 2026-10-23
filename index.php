<?php
require_once 'config/koneksi.php';
$judul = "Selamat Datang - Taking Order Cafe";
include 'includes/header.php';
?>
<main class="min-h-screen grid lg:grid-cols-2">

  <!-- KIRI: brand panel -->
  <section class="hidden lg:flex relative bg-navy-900 text-white overflow-hidden">
    <div class="absolute inset-0 opacity-[0.06]"
         style="background-image:radial-gradient(circle at 25% 20%, #D8B485 0, transparent 45%), radial-gradient(circle at 80% 80%, #00A7E1 0, transparent 40%);"></div>
    <div class="relative z-10 flex flex-col justify-between p-14 w-full">
      <a href="<?= BASE_URL ?>/index.php" class="brand-lockup">
        <img src="<?= BASE_URL ?>/assets/logo-brand.png" alt="Taking Order Cafe" class="h-10 brightness-0 invert">
        <span class="brand-text text-white text-xl">Taking Order <em class="not-italic text-sand-400">Cafe</em></span>
      </a>

      <div class="max-w-sm">
        <p class="text-sand-400 text-sm font-semibold tracking-widest uppercase mb-3">Coastal Coffee</p>
        <h1 class="font-brand text-4xl leading-tight mb-5">
          Pesan kopi &amp; hidangan favorit, langsung dari meja Anda.
        </h1>
        <p class="text-ice-100/70 leading-relaxed">
          Isi nama dan nomor meja, pilih menu, dan kami antar ke meja Anda tanpa antre.
        </p>
      </div>

      <div class="flex items-center gap-6 text-ice-100/60 text-sm">
        <span class="flex items-center gap-1.5"><iconify-icon icon="mdi:check-decagram-outline" width="16"></iconify-icon> Menu segar</span>
        <span class="flex items-center gap-1.5"><iconify-icon icon="mdi:clock-outline" width="16"></iconify-icon> Siap 10 menit</span>
      </div>
    </div>
  </section>

  <!-- KANAN: form -->
  <section class="flex items-center justify-center px-5 py-12 bg-ice-50">
    <div class="w-full max-w-sm">

      <!-- Brand mobile -->
      <div class="lg:hidden text-center mb-8">
        <img src="<?= BASE_URL ?>/assets/logo-brand.png" alt="Taking Order Cafe" class="h-14 mx-auto mb-3">
        <h2 class="font-brand text-xl text-navy-900">Taking Order <em class="not-italic text-sand-500">Cafe</em></h2>
      </div>

      <h2 class="font-brand text-2xl text-navy-900 mb-1">Selamat datang</h2>
      <p class="text-sm text-ink/60 mb-7">Isi data diri Anda untuk mulai memesan.</p>

      <?php if (!empty($_SESSION['error'])): ?>
        <div class="mb-5 flex items-start gap-2 bg-red-50 border border-red-200 text-red-800
                    px-3.5 py-3 rounded-lg text-sm">
          <iconify-icon icon="mdi:alert-circle-outline" class="mt-0.5" width="18"></iconify-icon>
          <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
      <?php endif; ?>

      <form action="<?= BASE_URL ?>/proses/simpan_pelanggan.php" method="POST" class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-navy-900 mb-1.5">Nama Lengkap</label>
          <input type="text" name="nama" required placeholder="Nama Anda" class="input-soft">
        </div>
        <div>
          <label class="block text-sm font-semibold text-navy-900 mb-1.5">Nomor Meja</label>
          <input type="text" name="no_meja" required placeholder="Contoh: A01" class="input-soft">
        </div>
        <div>
          <label class="block text-sm font-semibold text-navy-900 mb-1.5">
            No. HP <span class="text-ink/40 font-normal">(opsional)</span>
          </label>
          <input type="text" name="no_hp" placeholder="08xxxxxxxxxx" class="input-soft">
        </div>
        <button type="submit" class="btn-primary w-full !py-3">
          Mulai Pesan
          <iconify-icon icon="mdi:arrow-right" width="18"></iconify-icon>
        </button>
      </form>

      <div class="mt-7 pt-5 border-t border-[rgba(14,77,164,0.10)] text-center">
        <a href="<?= BASE_URL ?>/admin/login.php"
           class="inline-flex items-center gap-1.5 text-sm text-ink/50 hover:text-navy-800 transition-colors">
          <iconify-icon icon="mdi:shield-account-outline" width="16"></iconify-icon>
          Login Admin
        </a>
      </div>
    </div>
  </section>
</main>
<?php include 'includes/footer.php'; ?>
<style>
    footer{
        margin-top: 0 !important;
    }
</style>