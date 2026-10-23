<?php
require_once __DIR__ . '/../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) redirect('/admin/login.php');

$judul = "Laporan Order";
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/sidebar.php';

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$ta = mysqli_real_escape_string($koneksi, $tgl_awal);
$tk = mysqli_real_escape_string($koneksi, $tgl_akhir);

$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o JOIN pelanggan p ON o.pelanggan_id=p.id
                             WHERE DATE(o.waktu_order) BETWEEN '$ta' AND '$tk'
                             ORDER BY o.waktu_order DESC");
$data = []; $total_pendapatan = 0;
while ($row = mysqli_fetch_assoc($q)) {
    $data[] = $row;
    if ($row['status'] === 'selesai') $total_pendapatan += $row['total_harga'];
}
?>
<div class="flex min-h-screen">
  <?php renderSidebar('laporan'); ?>
  <div class="flex-1 min-w-0">
    <header class="bg-white border-b border-[rgba(14,77,164,0.08)] h-16 flex items-center px-6 md:px-8 sticky top-0 z-30">
      <button class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg hover:bg-ice-100 text-navy-800 mr-3"
              onclick="document.getElementById('mobileSidebar').classList.remove('hidden')">
        <iconify-icon icon="mdi:menu" width="20"></iconify-icon>
      </button>
      <h1 class="font-semibold text-navy-900">Laporan Order</h1>
    </header>

    <main class="p-6 md:p-8 space-y-5">
      <form method="GET" class="card-soft p-5 flex flex-wrap items-end gap-3">
        <div>
          <label class="block text-xs font-semibold mb-1.5 text-ink/60 uppercase tracking-wider">Tanggal Awal</label>
          <input type="date" name="tgl_awal" value="<?= $tgl_awal; ?>" class="input-soft">
        </div>
        <div>
          <label class="block text-xs font-semibold mb-1.5 text-ink/60 uppercase tracking-wider">Tanggal Akhir</label>
          <input type="date" name="tgl_akhir" value="<?= $tgl_akhir; ?>" class="input-soft">
        </div>
        <button type="submit" class="btn-primary">
          <iconify-icon icon="mdi:magnify" width="16"></iconify-icon> Filter
        </button>
        <a href="<?= BASE_URL ?>/admin/cetak_laporan.php?tgl_awal=<?= $tgl_awal; ?>&tgl_akhir=<?= $tgl_akhir; ?>"
           target="_blank" class="btn-ghost">
          <iconify-icon icon="mdi:printer-outline" width="16"></iconify-icon> Cetak
        </a>
      </form>

      <div class="card-soft p-5 flex items-center justify-between flex-wrap gap-3">
        <div>
          <p class="text-xs uppercase tracking-wider text-ink/50 font-semibold">Total Pendapatan (Selesai)</p>
          <p class="font-brand text-2xl text-navy-900 mt-1"><?= rupiah($total_pendapatan); ?></p>
        </div>
        <div class="text-right">
          <p class="text-xs uppercase tracking-wider text-ink/50 font-semibold">Jumlah Order</p>
          <p class="font-brand text-2xl text-navy-900 mt-1"><?= count($data); ?></p>
        </div>
      </div>

      <div class="card-soft overflow-x-auto">
        <table class="w-full text-sm min-w-[720px]">
          <thead class="border-b border-[rgba(14,77,164,0.10)]">
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wider">
              <th class="px-4 py-3 font-semibold">No</th>
              <th class="px-4 py-3 font-semibold">Kode</th>
              <th class="px-4 py-3 font-semibold">Nama</th>
              <th class="px-4 py-3 font-semibold text-center">Meja</th>
              <th class="px-4 py-3 font-semibold text-right">Total</th>
              <th class="px-4 py-3 font-semibold text-center">Status</th>
              <th class="px-4 py-3 font-semibold text-center">Waktu</th>
            </tr>
          </thead>
          <tbody>
          <?php $no=1; foreach ($data as $o):
            $badge = ['pending'=>'badge-warn','diproses'=>'badge-info','selesai'=>'badge-success','dibatalkan'=>'badge-danger'][$o['status']] ?? 'badge-neutral';
          ?>
            <tr class="border-b border-[rgba(14,77,164,0.06)] last:border-0">
              <td class="px-4 py-3 text-ink/50"><?= $no++; ?></td>
              <td class="px-4 py-3 font-mono text-xs text-navy-900"><?= htmlspecialchars($o['kode_order']); ?></td>
              <td class="px-4 py-3"><?= htmlspecialchars($o['nama']); ?></td>
              <td class="px-4 py-3 text-center"><?= htmlspecialchars($o['no_meja']); ?></td>
              <td class="px-4 py-3 text-right font-medium"><?= rupiah($o['total_harga']); ?></td>
              <td class="px-4 py-3 text-center"><span class="badge <?= $badge ?>"><?= ucfirst($o['status']); ?></span></td>
              <td class="px-4 py-3 text-center text-xs text-ink/60"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>