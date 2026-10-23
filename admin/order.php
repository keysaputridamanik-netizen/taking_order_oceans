<?php
require_once __DIR__ . '/../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) redirect('/admin/login.php');

$judul = "Data Order";
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/sidebar.php';

$filter = $_GET['status'] ?? '';
$where = "";
if ($filter !== '') {
    $f = mysqli_real_escape_string($koneksi, $filter);
    $where = "WHERE o.status='$f'";
}
$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o JOIN pelanggan p ON o.pelanggan_id=p.id $where ORDER BY o.waktu_order DESC");

$tabs = [
  ''           => 'Semua',
  'pending'    => 'Pending',
  'diproses'   => 'Diproses',
  'selesai'    => 'Selesai',
  'dibatalkan' => 'Dibatalkan',
];
?>
<div class="flex min-h-screen">
  <?php renderSidebar('order'); ?>
  <div class="flex-1 min-w-0">
    <header class="bg-white border-b border-[rgba(14,77,164,0.08)] h-16 flex items-center px-6 md:px-8 sticky top-0 z-30">
      <button class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg hover:bg-ice-100 text-navy-800 mr-3"
              onclick="document.getElementById('mobileSidebar').classList.remove('hidden')">
        <iconify-icon icon="mdi:menu" width="20"></iconify-icon>
      </button>
      <h1 class="font-semibold text-navy-900">Data Order</h1>
    </header>

    <main class="p-6 md:p-8">
      <div class="flex flex-wrap gap-2 mb-5">
        <?php foreach ($tabs as $key => $label): ?>
          <a href="<?= BASE_URL ?>/admin/order.php<?= $key !== '' ? '?status='.$key : '' ?>"
             class="px-3.5 py-1.5 rounded-full text-sm font-semibold transition-colors
                    <?= $filter === $key
                        ? 'bg-navy-800 text-white'
                        : 'bg-white text-navy-800 border border-[rgba(14,77,164,0.15)] hover:bg-ice-100'; ?>">
            <?= $label ?>
          </a>
        <?php endforeach; ?>
      </div>

      <div class="card-soft overflow-x-auto">
        <table class="w-full text-sm min-w-[720px]">
          <thead class="border-b border-[rgba(14,77,164,0.10)]">
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wider">
              <th class="px-4 py-3 font-semibold">Kode</th>
              <th class="px-4 py-3 font-semibold">Nama</th>
              <th class="px-4 py-3 font-semibold text-center">Meja</th>
              <th class="px-4 py-3 font-semibold text-right">Total</th>
              <th class="px-4 py-3 font-semibold text-center">Status</th>
              <th class="px-4 py-3 font-semibold text-center">Waktu</th>
              <th class="px-4 py-3 font-semibold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php while ($o = mysqli_fetch_assoc($q)):
            $badge = ['pending'=>'badge-warn','diproses'=>'badge-info','selesai'=>'badge-success','dibatalkan'=>'badge-danger'][$o['status']] ?? 'badge-neutral';
          ?>
            <tr class="border-b border-[rgba(14,77,164,0.06)] last:border-0">
              <td class="px-4 py-3 font-mono text-xs text-navy-900"><?= htmlspecialchars($o['kode_order']); ?></td>
              <td class="px-4 py-3 text-navy-900"><?= htmlspecialchars($o['nama']); ?></td>
              <td class="px-4 py-3 text-center"><?= htmlspecialchars($o['no_meja']); ?></td>
              <td class="px-4 py-3 text-right font-medium"><?= rupiah($o['total_harga']); ?></td>
              <td class="px-4 py-3 text-center"><span class="badge <?= $badge ?>"><?= ucfirst($o['status']); ?></span></td>
              <td class="px-4 py-3 text-center text-xs text-ink/60"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
              <td class="px-4 py-3 text-center">
                <a href="<?= BASE_URL ?>/admin/order_detail.php?id=<?= (int)$o['id']; ?>"
                   class="icon-btn icon-btn-info" title="Lihat">
                  <iconify-icon icon="mdi:eye-outline" width="14"></iconify-icon>
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>