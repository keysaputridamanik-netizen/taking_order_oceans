<?php
require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('/admin/login.php');
}

$judul = "Dashboard Admin";

$total_menu   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) j FROM menu"))['j'];
$total_order  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) j FROM orders"))['j'];
$total_pending= mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) j FROM orders WHERE status='pending'"))['j'];
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(total_harga),0) j FROM orders WHERE status='selesai'"))['j'];

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/sidebar.php';

$labels = []; $vals = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('d M', strtotime($d));
    $c = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) j FROM orders WHERE DATE(waktu_order)='$d'"))['j'];
    $vals[] = (int)$c;
}
?>
<div class="flex min-h-screen">
  <?php renderSidebar('dashboard'); ?>

  <div class="flex-1 min-w-0">
    <header class="bg-white border-b border-[rgba(14,77,164,0.08)] h-16 flex items-center justify-between px-6 md:px-8 sticky top-0 z-30">
      <div class="flex items-center gap-3">
        <button class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg hover:bg-ice-100 text-navy-800"
                onclick="document.getElementById('mobileSidebar').classList.remove('hidden')">
          <iconify-icon icon="mdi:menu" width="20"></iconify-icon>
        </button>
        <h1 class="font-semibold text-navy-900">Dashboard</h1>
      </div>
      <div class="flex items-center gap-3 text-sm">
        <span class="hidden sm:inline-flex items-center gap-1.5 text-ink/60">
          <iconify-icon icon="mdi:account-circle-outline" width="18"></iconify-icon>
          <?= htmlspecialchars($_SESSION['admin_nama']); ?>
        </span>
        <a href="<?= BASE_URL ?>/admin/logout.php" class="btn-ghost btn-sm">Logout</a>
      </div>
    </header>

    <main class="p-6 md:p-8 space-y-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php
        $cards = [
          ['Total Menu',    $total_menu,               'mdi:coffee-outline',       'text-navy-800', 'bg-ice-100'],
          ['Total Order',   $total_order,              'mdi:receipt-text-outline', 'text-ocean-500', 'bg-[rgba(0,167,225,0.08)]'],
          ['Order Pending', $total_pending,            'mdi:clock-outline',        'text-[#7A5A35]', 'bg-[rgba(198,156,109,0.12)]'],
          ['Pendapatan',    rupiah($total_pendapatan), 'mdi:cash-multiple',        'text-emerald-700', 'bg-emerald-50'],
        ];
        foreach ($cards as [$t, $v, $i, $color, $bg]):
        ?>
        <div class="card-soft p-5">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-xs uppercase tracking-wider text-ink/50 font-semibold"><?= $t ?></p>
              <p class="font-brand text-2xl text-navy-900 mt-2"><?= $v ?></p>
            </div>
            <div class="w-10 h-10 rounded-lg <?= $bg ?> <?= $color ?> flex items-center justify-center">
              <iconify-icon icon="<?= $i ?>" width="22"></iconify-icon>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="card-soft p-6">
        <h2 class="font-semibold text-navy-900 mb-4">Order 7 Hari Terakhir</h2>
        <canvas id="chartOrder" height="80"></canvas>
      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartOrder'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($labels); ?>,
    datasets: [{
      label: 'Order',
      data: <?= json_encode($vals); ?>,
      backgroundColor: '#0E4DA4',
      hoverBackgroundColor: '#00A7E1',
      borderRadius: 6,
      maxBarThickness: 36,
    }]
  },
  options: {
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(14,77,164,0.06)' } },
      x: { grid: { display: false } }
    }
  }
});
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>