<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id']) || !isset($_SESSION['last_order_id'])) {
    header("Location: " . BASE_URL . "/index.php"); exit;
}
$oid = (int)$_SESSION['last_order_id'];
$order = mysqli_fetch_assoc(mysqli_query($koneksi,
  "SELECT o.*, p.nama, p.no_hp FROM orders o JOIN pelanggan p ON o.pelanggan_id=p.id WHERE o.id=$oid"));
if (!$order) { header("Location: " . BASE_URL . "/index.php"); exit; }
$qd = mysqli_query($koneksi, "SELECT * FROM order_detail WHERE order_id=$oid");
$judul = "Struk Pesanan - Taking Order Cafe";
include '../includes/header.php';
?>
<main class="bg-ice-50 py-10 px-4 min-h-screen">
  <div class="max-w-md mx-auto">

    <div class="text-center mb-6 no-print">
      <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 mb-3">
        <iconify-icon icon="mdi:check-circle" width="32"></iconify-icon>
      </div>
      <h1 class="font-brand text-2xl text-navy-900">Pesanan Berhasil</h1>
      <p class="text-sm text-ink/60 mt-1">Tunjukkan struk ini ke kasir atau pelayan.</p>
    </div>

    <div class="bg-white rounded-xl2 border border-[rgba(14,77,164,0.08)] p-6">
      <div class="text-center pb-4 mb-4 border-b border-dashed border-ink/20">
        <img src="<?= BASE_URL ?>/assets/logo-brand.png" alt="Taking Order Cafe" class="h-10 mx-auto mb-2">
        <p class="text-xs text-ink/50">Jl. Contoh Alamat No. 123, Kota Anda</p>
        <p class="text-xs text-ink/50">Telp: 021-1234567</p>
      </div>

      <dl class="text-sm space-y-1.5">
        <div class="flex justify-between"><dt class="text-ink/60">Kode Order</dt><dd class="font-mono font-semibold text-navy-900"><?= htmlspecialchars($order['kode_order']); ?></dd></div>
        <div class="flex justify-between"><dt class="text-ink/60">Tanggal</dt><dd><?= date('d/m/Y H:i', strtotime($order['waktu_order'])); ?></dd></div>
        <div class="flex justify-between"><dt class="text-ink/60">Nama</dt><dd><?= htmlspecialchars($order['nama']); ?></dd></div>
        <div class="flex justify-between"><dt class="text-ink/60">Meja</dt><dd><?= htmlspecialchars($order['no_meja']); ?></dd></div>
      </dl>

      <div class="border-t border-dashed border-ink/20 my-4"></div>

      <div class="space-y-1.5 text-sm">
        <?php while ($d = mysqli_fetch_assoc($qd)): ?>
          <div class="flex justify-between">
            <span class="text-ink/70"><?= htmlspecialchars($d['nama_menu']); ?> <span class="text-ink/40">×<?= (int)$d['jumlah']; ?></span></span>
            <span><?= number_format($d['subtotal'],0,',','.'); ?></span>
          </div>
        <?php endwhile; ?>
      </div>

      <div class="border-t border-dashed border-ink/20 my-4"></div>
      <div class="flex justify-between items-baseline">
        <span class="font-semibold text-navy-900">TOTAL</span>
        <span class="font-brand text-xl text-navy-900"><?= rupiah($order['total_harga']); ?></span>
      </div>

      <?php if (!empty($order['catatan'])): ?>
        <div class="mt-4 text-sm bg-ice-50 rounded-lg p-3 border border-[rgba(14,77,164,0.06)]">
          <span class="font-semibold text-navy-900 block mb-1">Catatan</span>
          <span class="text-ink/70"><?= nl2br(htmlspecialchars($order['catatan'])); ?></span>
        </div>
      <?php endif; ?>

      <p class="text-center text-xs text-ink/50 mt-5">Terima kasih atas kunjungan Anda.</p>
    </div>

    <div class="mt-6 flex flex-wrap justify-center gap-2 no-print">
      <button onclick="window.print()" class="btn-ghost">
        <iconify-icon icon="mdi:printer-outline" width="16"></iconify-icon> Cetak
      </button>
      <a href="<?= BASE_URL ?>/pelanggan/menu.php" class="btn-primary">
        <iconify-icon icon="mdi:plus" width="16"></iconify-icon> Order Lagi
      </a>
      <a href="<?= BASE_URL ?>/pelanggan/logout_pelanggan.php" class="btn-ghost">
        <iconify-icon icon="mdi:logout" width="16"></iconify-icon> Selesai
      </a>
    </div>
  </div>
</main>
<?php include '../includes/footer.php'; ?>