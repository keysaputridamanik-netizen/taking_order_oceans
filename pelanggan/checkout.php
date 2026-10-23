<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: " . BASE_URL . "/index.php"); exit; }
$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) { header("Location: " . BASE_URL . "/pelanggan/keranjang.php"); exit; }

$total = 0;
foreach ($keranjang as $mid => $jml) {
    $q = mysqli_query($koneksi, "SELECT harga FROM menu WHERE id=" . (int)$mid);
    $m = mysqli_fetch_assoc($q);
    if ($m) $total += $m['harga'] * $jml;
}
$judul = "Checkout - Taking Order Cafe";
include '../includes/header.php'; include '../includes/navbar.php';
?>
<main class="max-w-5xl mx-auto px-5 sm:px-6 py-10">
  <header class="mb-8">
    <p class="text-sand-500 text-xs font-semibold tracking-[0.18em] uppercase mb-2">Checkout</p>
    <h1 class="font-brand text-3xl text-navy-900">Konfirmasi Pesanan</h1>
  </header>

  <div class="grid md:grid-cols-5 gap-6">
    <div class="md:col-span-3 space-y-5">
      <div class="card-soft p-6">
        <h2 class="font-semibold text-navy-900 mb-3 flex items-center gap-2">
          <iconify-icon icon="mdi:account-outline" class="text-ocean-500" width="20"></iconify-icon>
          Data Pelanggan
        </h2>
        <dl class="text-sm space-y-1.5">
          <div class="flex justify-between"><dt class="text-ink/60">Nama</dt><dd class="font-medium"><?= htmlspecialchars($_SESSION['pelanggan_nama']); ?></dd></div>
          <div class="flex justify-between"><dt class="text-ink/60">Meja</dt><dd class="font-medium"><?= htmlspecialchars($_SESSION['pelanggan_meja']); ?></dd></div>
        </dl>
      </div>

      <form action="<?= BASE_URL ?>/proses/simpan_order.php" method="POST" class="card-soft p-6 space-y-4">
        <h2 class="font-semibold text-navy-900 flex items-center gap-2">
          <iconify-icon icon="mdi:note-text-outline" class="text-ocean-500" width="20"></iconify-icon>
          Catatan Tambahan
        </h2>
        <textarea name="catatan" rows="3" class="input-soft"
                  placeholder="Contoh: tanpa gula, extra shot, tidak pedas..."></textarea>
        <button type="submit" class="btn-primary w-full !py-3">
          <iconify-icon icon="mdi:send-outline" width="18"></iconify-icon> Kirim Pesanan
        </button>
      </form>
    </div>

    <aside class="md:col-span-2 card-soft p-6 h-fit">
      <h2 class="font-semibold text-navy-900 mb-4 flex items-center gap-2">
        <iconify-icon icon="mdi:receipt-text-outline" class="text-ocean-500" width="20"></iconify-icon>
        Rincian
      </h2>
      <div class="space-y-2 text-sm">
        <?php foreach ($keranjang as $mid => $jml):
            $q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id=" . (int)$mid);
            $m = mysqli_fetch_assoc($q); if (!$m) continue;
        ?>
          <div class="flex justify-between">
            <span class="text-ink/70"><?= htmlspecialchars($m['nama_menu']); ?> <span class="text-ink/40">×<?= (int)$jml; ?></span></span>
            <span class="font-medium"><?= rupiah($m['harga'] * $jml); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="border-t border-[rgba(14,77,164,0.10)] my-4"></div>
      <div class="flex justify-between items-baseline">
        <span class="text-sm text-ink/60">Total</span>
        <span class="font-brand text-2xl text-navy-900"><?= rupiah($total); ?></span>
      </div>
    </aside>
  </div>
</main>
<?php include '../includes/footer.php'; ?>