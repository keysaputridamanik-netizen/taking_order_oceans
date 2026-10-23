<?php
require_once __DIR__ . '/../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) redirect('/admin/login.php');

$judul = "Kelola Menu";
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/sidebar.php';

$cari = trim($_GET['cari'] ?? '');
$kat  = trim($_GET['kategori'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$per  = 10;
$offset = ($page - 1) * $per;

$where = [];
if ($cari !== '') $where[] = "(nama_menu LIKE '%" . mysqli_real_escape_string($koneksi, $cari) . "%')";
if ($kat !== '')  $where[] = "kategori='" . mysqli_real_escape_string($koneksi, $kat) . "'";
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$totalRows  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) j FROM menu $whereSql"))['j'];
$totalPages = max(1, (int)ceil($totalRows / $per));
$q = mysqli_query($koneksi, "SELECT * FROM menu $whereSql ORDER BY kategori, nama_menu LIMIT $per OFFSET $offset");
$kategori_list = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM menu ORDER BY kategori");
?>
<div class="flex min-h-screen">
  <?php renderSidebar('menu'); ?>
  <div class="flex-1 min-w-0">
    <header class="bg-white border-b border-[rgba(14,77,164,0.08)] h-16 flex items-center justify-between px-6 md:px-8 sticky top-0 z-30">
      <div class="flex items-center gap-3">
        <button class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg hover:bg-ice-100 text-navy-800"
                onclick="document.getElementById('mobileSidebar').classList.remove('hidden')">
          <iconify-icon icon="mdi:menu" width="20"></iconify-icon>
        </button>
        <h1 class="font-semibold text-navy-900">Kelola Menu</h1>
      </div>
      <a href="<?= BASE_URL ?>/admin/menu_tambah.php" class="btn-primary btn-sm">
        <iconify-icon icon="mdi:plus" width="16"></iconify-icon> Tambah Menu
      </a>
    </header>

    <main class="p-6 md:p-8">
      <?php if (!empty($_SESSION['pesan'])): ?>
        <div class="alert-info mb-5">
          <iconify-icon icon="mdi:check-circle" width="20"></iconify-icon>
          <span><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></span>
        </div>
      <?php endif; ?>

      <form method="GET" class="card-soft p-4 mb-5 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[180px]">
          <label class="block text-xs font-semibold mb-1.5 text-ink/60 uppercase tracking-wider">Cari</label>
          <input type="text" name="cari" value="<?= htmlspecialchars($cari); ?>" placeholder="Nama menu..." class="input-soft">
        </div>
        <div class="min-w-[140px]">
          <label class="block text-xs font-semibold mb-1.5 text-ink/60 uppercase tracking-wider">Kategori</label>
          <select name="kategori" class="input-soft">
            <option value="">Semua</option>
            <?php while ($k = mysqli_fetch_assoc($kategori_list)): ?>
              <option value="<?= htmlspecialchars($k['kategori']); ?>" <?= $kat===$k['kategori']?'selected':'' ?>>
                <?= htmlspecialchars($k['kategori']); ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <button type="submit" class="btn-primary">
          <iconify-icon icon="mdi:magnify" width="16"></iconify-icon> Cari
        </button>
      </form>

      <div class="card-soft overflow-x-auto">
        <table class="w-full text-sm min-w-[720px]">
          <thead class="border-b border-[rgba(14,77,164,0.10)]">
            <tr class="text-left text-ink/50 text-xs uppercase tracking-wider">
              <th class="px-4 py-3 font-semibold">No</th>
              <th class="px-4 py-3 font-semibold">Gambar</th>
              <th class="px-4 py-3 font-semibold">Nama</th>
              <th class="px-4 py-3 font-semibold">Kategori</th>
              <th class="px-4 py-3 font-semibold text-right">Harga</th>
              <th class="px-4 py-3 font-semibold text-center">Stok</th>
              <th class="px-4 py-3 font-semibold text-center">Status</th>
              <th class="px-4 py-3 font-semibold text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php $no = $offset + 1; while ($m = mysqli_fetch_assoc($q)): ?>
            <tr class="border-b border-[rgba(14,77,164,0.06)] last:border-0">
              <td class="px-4 py-3 text-ink/50"><?= $no++; ?></td>
              <td class="px-4 py-3">
                <?php if (!empty($m['gambar']) && file_exists(__DIR__.'/../assets/uploads/menu/'.$m['gambar'])): ?>
                  <img src="<?= BASE_URL ?>/assets/uploads/menu/<?= htmlspecialchars($m['gambar']); ?>"
                       class="w-12 h-12 object-cover rounded-md">
                <?php else: ?>
                  <div class="w-12 h-12 rounded-md bg-ice-100 flex items-center justify-center text-navy-800/30">
                    <iconify-icon icon="mdi:image-outline" width="20"></iconify-icon>
                  </div>
                <?php endif; ?>
              </td>
              <td class="px-4 py-3 font-medium text-navy-900"><?= htmlspecialchars($m['nama_menu']); ?></td>
              <td class="px-4 py-3"><span class="badge badge-neutral"><?= htmlspecialchars($m['kategori']); ?></span></td>
              <td class="px-4 py-3 text-right"><?= rupiah($m['harga']); ?></td>
              <td class="px-4 py-3 text-center"><?= (int)$m['stok']; ?></td>
              <td class="px-4 py-3 text-center">
                <?php if ($m['status']==='tersedia'): ?>
                  <span class="badge badge-success">Tersedia</span>
                <?php else: ?>
                  <span class="badge badge-danger">Habis</span>
                <?php endif; ?>
              </td>
              <td class="px-4 py-3">
                <div class="flex gap-1.5 justify-center">
                  <a href="<?= BASE_URL ?>/admin/menu_edit.php?id=<?= (int)$m['id']; ?>"
                     class="icon-btn icon-btn-info" title="Edit">
                    <iconify-icon icon="mdi:pencil" width="14"></iconify-icon>
                  </a>
                  <a href="<?= BASE_URL ?>/admin/menu_hapus.php?id=<?= (int)$m['id']; ?>"
                     onclick="return confirm('Hapus menu ini?')"
                     class="icon-btn icon-btn-danger" title="Hapus">
                    <iconify-icon icon="mdi:trash-can-outline" width="14"></iconify-icon>
                  </a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>

      <?php if ($totalPages > 1): ?>
      <div class="flex justify-center gap-1.5 mt-6">
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
          <a href="?page=<?= $p; ?>&cari=<?= urlencode($cari); ?>&kategori=<?= urlencode($kat); ?>"
             class="w-9 h-9 rounded-md inline-flex items-center justify-center text-sm font-semibold transition-colors
                    <?= $p === $page ? 'bg-navy-800 text-white' : 'bg-white text-navy-800 border border-[rgba(14,77,164,0.15)] hover:bg-ice-100'; ?>">
            <?= $p; ?>
          </a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>
    </main>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>