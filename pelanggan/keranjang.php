<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: " . BASE_URL . "/index.php"); exit; }
$judul = "Keranjang - Taking Order Cafe";
include '../includes/header.php'; include '../includes/navbar.php';

$keranjang = $_SESSION['keranjang'] ?? [];
$total = 0;
?>
<main class="max-w-5xl mx-auto px-5 sm:px-6 py-10">
  <header class="mb-8">
    <p class="text-sand-500 text-xs font-semibold tracking-[0.18em] uppercase mb-2">Keranjang</p>
    <h1 class="font-brand text-3xl text-navy-900">Pesanan Anda</h1>
  </header>

  <?php if (!empty($_SESSION['error_checkout'])): ?>
    <div class="mb-6 flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
      <iconify-icon icon="mdi:alert-circle-outline" class="mt-0.5" width="18"></iconify-icon>
      <?= $_SESSION['error_checkout']; unset($_SESSION['error_checkout']); ?>
    </div>
  <?php endif; ?>

  <?php if (empty($keranjang)): ?>
    <div class="card-soft p-12 text-center">
      <iconify-icon icon="mdi:cart-outline" class="text-ink/20" width="48"></iconify-icon>
      <p class="text-ink/60 mt-3">Keranjang masih kosong.</p>
      <a href="<?= BASE_URL ?>/pelanggan/menu.php" class="btn-primary mt-5 inline-flex">Lihat Menu</a>
    </div>
  <?php else: ?>
    <div class="card-soft overflow-x-auto">
      <table class="w-full text-sm min-w-[640px]">
        <thead class="border-b border-[rgba(14,77,164,0.10)]">
          <tr class="text-left text-ink/50 text-xs uppercase tracking-wider">
            <th class="px-5 py-3.5 font-semibold">Menu</th>
            <th class="px-5 py-3.5 font-semibold text-right">Harga</th>
            <th class="px-5 py-3.5 font-semibold text-center">Jumlah</th>
            <th class="px-5 py-3.5 font-semibold text-right">Subtotal</th>
            <th class="px-5 py-3.5"></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($keranjang as $mid => $jml):
            $q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id=" . (int)$mid);
            $m = mysqli_fetch_assoc($q); if (!$m) continue;
            $sub = $m['harga'] * $jml; $total += $sub;
        ?>
          <tr class="border-b border-[rgba(14,77,164,0.06)] last:border-0">
            <td class="px-5 py-4 font-medium text-navy-900"><?= htmlspecialchars($m['nama_menu']); ?></td>
            <td class="px-5 py-4 text-right text-ink/70"><?= rupiah($m['harga']); ?></td>
            <td class="px-5 py-4">
              <form action="<?= BASE_URL ?>/proses/update_keranjang.php" method="POST" class="flex gap-1.5 justify-center">
                <input type="hidden" name="menu_id" value="<?= (int)$mid; ?>">
                <input type="number" name="jumlah" value="<?= (int)$jml; ?>" min="1" max="<?= (int)$m['stok']; ?>"
                       class="input-soft !w-16 !py-1 text-center text-sm">
                <button type="submit" class="icon-btn bg-ice-100 hover:bg-ice-50 text-navy-800 border border-[rgba(14,77,164,0.15)]" title="Update">
                  <iconify-icon icon="mdi:refresh" width="14"></iconify-icon>
                </button>
              </form>
            </td>
            <td class="px-5 py-4 text-right font-semibold"><?= rupiah($sub); ?></td>
            <td class="px-5 py-4 text-center">
              <a href="<?= BASE_URL ?>/proses/hapus_keranjang.php?id=<?= (int)$mid; ?>"
                 class="icon-btn icon-btn-danger" title="Hapus">
                <iconify-icon icon="mdi:trash-can-outline" width="14"></iconify-icon>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="flex items-center justify-between mt-6 flex-wrap gap-3">
      <a href="<?= BASE_URL ?>/pelanggan/menu.php"
         class="btn-ghost">
        <iconify-icon icon="mdi:arrow-left" width="16"></iconify-icon> Lanjut Pesan
      </a>
      <div class="flex items-center gap-4">
        <div class="text-right">
          <div class="text-xs text-ink/50 uppercase tracking-wider">Total</div>
          <div class="font-brand text-2xl text-navy-900"><?= rupiah($total); ?></div>
        </div>
        <a href="<?= BASE_URL ?>/pelanggan/checkout.php" class="btn-primary !py-3">
          Checkout <iconify-icon icon="mdi:arrow-right" width="16"></iconify-icon>
        </a>
      </div>
    </div>
  <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>