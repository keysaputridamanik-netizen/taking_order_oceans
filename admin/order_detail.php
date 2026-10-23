<?php
require_once __DIR__ . '/../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) redirect('/admin/login.php');

$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $st = mysqli_real_escape_string($koneksi, $_POST['status']);
    mysqli_query($koneksi, "UPDATE orders SET status='$st' WHERE id=$id");
    $_SESSION['pesan'] = "Status order berhasil diperbarui.";
    redirect('/admin/order_detail.php?id=' . $id);
}

$judul = "Detail Order";
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/sidebar.php';

$o = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT o.*, p.nama, p.no_hp FROM orders o JOIN pelanggan p ON o.pelanggan_id=p.id WHERE o.id=$id"));
if (!$o) redirect('/admin/order.php');
$qd = mysqli_query($koneksi, "SELECT * FROM order_detail WHERE order_id=$id");
?>
<div class="flex min-h-screen">
  <?php renderSidebar('order'); ?>
  <div class="flex-1 min-w-0">
    <header class="bg-white border-b border-[rgba(14,77,164,0.08)] h-16 flex items-center px-6 md:px-8 sticky top-0 z-30">
      <a href="<?= BASE_URL ?>/admin/order.php" class="inline-flex items-center gap-1.5 text-sm text-ink/60 hover:text-navy-800 mr-4">
        <iconify-icon icon="mdi:arrow-left" width="16"></iconify-icon> Kembali
      </a>
      <h1 class="font-semibold text-navy-900">Detail Order</h1>
    </header>

    <main class="p-6 md:p-8">
      <?php if (!empty($_SESSION['pesan'])): ?>
        <div class="alert-info mb-5">
          <iconify-icon icon="mdi:check-circle" width="20"></iconify-icon>
          <span><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></span>
        </div>
      <?php endif; ?>

      <div class="grid md:grid-cols-5 gap-6">
        <div class="md:col-span-2 space-y-5">
          <div class="card-soft p-6">
            <h2 class="font-semibold text-navy-900 mb-3">Informasi</h2>
            <dl class="text-sm space-y-2">
              <div class="flex justify-between"><dt class="text-ink/60">Kode</dt><dd class="font-mono text-xs font-semibold text-navy-900"><?= htmlspecialchars($o['kode_order']); ?></dd></div>
              <div class="flex justify-between"><dt class="text-ink/60">Nama</dt><dd class="font-medium"><?= htmlspecialchars($o['nama']); ?></dd></div>
              <div class="flex justify-between"><dt class="text-ink/60">No. HP</dt><dd><?= htmlspecialchars($o['no_hp'] ?: '-'); ?></dd></div>
              <div class="flex justify-between"><dt class="text-ink/60">Meja</dt><dd><?= htmlspecialchars($o['no_meja']); ?></dd></div>
              <div class="flex justify-between"><dt class="text-ink/60">Waktu</dt><dd><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></dd></div>
              <div class="flex justify-between"><dt class="text-ink/60">Status</dt><dd><?= ucfirst($o['status']); ?></dd></div>
            </dl>
            <?php if (!empty($o['catatan'])): ?>
              <div class="mt-4 pt-4 border-t border-[rgba(14,77,164,0.08)]">
                <p class="text-xs uppercase tracking-wider text-ink/50 font-semibold mb-1.5">Catatan</p>
                <p class="text-sm text-ink/70"><?= nl2br(htmlspecialchars($o['catatan'])); ?></p>
              </div>
            <?php endif; ?>
          </div>

          <form method="POST" class="card-soft p-6 space-y-3">
            <h2 class="font-semibold text-navy-900">Update Status</h2>
            <select name="status" class="input-soft">
              <?php foreach (['pending','diproses','selesai','dibatalkan'] as $s): ?>
                <option value="<?= $s ?>" <?= $o['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn-primary w-full">
              <iconify-icon icon="mdi:content-save-outline" width="16"></iconify-icon> Simpan
            </button>
          </form>
        </div>

        <div class="md:col-span-3">
          <div class="card-soft overflow-x-auto">
            <div class="px-6 py-4 border-b border-[rgba(14,77,164,0.08)]">
              <h2 class="font-semibold text-navy-900">Rincian Item</h2>
            </div>
            <table class="w-full text-sm">
              <thead class="border-b border-[rgba(14,77,164,0.06)]">
                <tr class="text-left text-ink/50 text-xs uppercase tracking-wider">
                  <th class="px-6 py-3 font-semibold">Menu</th>
                  <th class="px-6 py-3 font-semibold text-right">Harga</th>
                  <th class="px-6 py-3 font-semibold text-center">Jumlah</th>
                  <th class="px-6 py-3 font-semibold text-right">Subtotal</th>
                </tr>
              </thead>
              <tbody>
              <?php while ($d = mysqli_fetch_assoc($qd)): ?>
                <tr class="border-b border-[rgba(14,77,164,0.06)] last:border-0">
                  <td class="px-6 py-3 text-navy-900"><?= htmlspecialchars($d['nama_menu']); ?></td>
                  <td class="px-6 py-3 text-right text-ink/70"><?= rupiah($d['harga']); ?></td>
                  <td class="px-6 py-3 text-center"><?= (int)$d['jumlah']; ?></td>
                  <td class="px-6 py-3 text-right font-medium"><?= rupiah($d['subtotal']); ?></td>
                </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
            <div class="px-6 py-4 border-t border-[rgba(14,77,164,0.08)] flex justify-between items-baseline">
              <span class="text-sm text-ink/60">Total</span>
              <span class="font-brand text-2xl text-navy-900"><?= rupiah($o['total_harga']); ?></span>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>