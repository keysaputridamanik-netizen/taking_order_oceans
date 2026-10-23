<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: " . BASE_URL . "/index.php"); exit; }
$judul = "Menu - Taking Order Cafe";
include '../includes/header.php';
include '../includes/navbar.php';

$kf = $_GET['kategori'] ?? '';
$where = "WHERE status='tersedia'";
if ($kf !== '') {
    $kf_safe = mysqli_real_escape_string($koneksi, $kf);
    $where .= " AND kategori='$kf_safe'";
}
$query = mysqli_query($koneksi, "SELECT * FROM menu $where ORDER BY kategori, nama_menu");
$kategori_list = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM menu ORDER BY kategori");
?>
<main class="max-w-7xl mx-auto px-5 sm:px-6 py-10">

  <header class="mb-10">
    <p class="text-sand-500 text-xs font-semibold tracking-[0.18em] uppercase mb-2">Menu Pilihan</p>
    <h1 class="font-brand text-3xl text-navy-900">Apa yang Anda ingin nikmati hari ini?</h1>
    <p class="text-ink/60 mt-2">Pilih menu, tambahkan ke keranjang, dan pesanan kami antar ke meja Anda.</p>
  </header>

  <?php if (!empty($_SESSION['pesan'])): ?>
    <div class="mb-8 flex items-center gap-2.5 bg-[rgba(0,167,225,0.08)] border border-ocean-400/30
                text-navy-900 px-4 py-3 rounded-lg">
      <iconify-icon icon="mdi:check-circle" class="text-ocean-500" width="20"></iconify-icon>
      <span class="text-sm"><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></span>
    </div>
  <?php endif; ?>

  <!-- Filter kategori -->
  <div class="flex flex-wrap gap-2 mb-8 pb-6 border-b border-[rgba(14,77,164,0.10)]">
    <a href="<?= BASE_URL ?>/pelanggan/menu.php"
       class="px-4 py-1.5 rounded-full text-sm font-semibold transition-colors
              <?= $kf===''?'bg-navy-800 text-white':'bg-white text-navy-800 border border-[rgba(14,77,164,0.15)] hover:bg-ice-100'; ?>">
      Semua
    </a>
    <?php while ($k = mysqli_fetch_assoc($kategori_list)): ?>
      <a href="<?= BASE_URL ?>/pelanggan/menu.php?kategori=<?= urlencode($k['kategori']); ?>"
         class="px-4 py-1.5 rounded-full text-sm font-semibold transition-colors
                <?= $kf===$k['kategori']?'bg-navy-800 text-white':'bg-white text-navy-800 border border-[rgba(14,77,164,0.15)] hover:bg-ice-100'; ?>">
        <?= htmlspecialchars($k['kategori']); ?>
      </a>
    <?php endwhile; ?>
  </div>

  <!-- Grid produk -->
  <?php if (mysqli_num_rows($query) === 0): ?>
    <div class="card-soft p-12 text-center text-ink/60">
      <iconify-icon icon="mdi:coffee-off-outline" width="48" class="text-ink/20 mb-3"></iconify-icon>
      <p>Belum ada menu pada kategori ini.</p>
    </div>
  <?php else: ?>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php while ($m = mysqli_fetch_assoc($query)): ?>
      <article class="card-soft overflow-hidden flex flex-col group hover:shadow-lift transition-shadow duration-300">
        <a href="<?= BASE_URL ?>/pelanggan/detail.php?id=<?= (int)$m['id']; ?>"
           class="block aspect-[5/4] bg-ice-100 overflow-hidden relative">
          <?php if (!empty($m['gambar']) && file_exists(__DIR__.'/../assets/uploads/menu/'.$m['gambar'])): ?>
            <img src="<?= BASE_URL ?>/assets/uploads/menu/<?= htmlspecialchars($m['gambar']); ?>"
                 alt="<?= htmlspecialchars($m['nama_menu']); ?>"
                 class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
          <?php else: ?>
            <div class="w-full h-full flex items-center justify-center text-navy-800/20">
              <iconify-icon icon="mdi:image-outline" width="48"></iconify-icon>
            </div>
          <?php endif; ?>
          <span class="absolute top-3 left-3 badge badge-neutral bg-white/95 shadow-sm"><?= htmlspecialchars($m['kategori']); ?></span>
        </a>

        <div class="p-5 flex-1 flex flex-col">
          <h3 class="font-semibold text-navy-900 text-lg leading-snug">
            <a href="<?= BASE_URL ?>/pelanggan/detail.php?id=<?= (int)$m['id']; ?>"
               class="hover:text-ocean-500 transition-colors"><?= htmlspecialchars($m['nama_menu']); ?></a>
          </h3>
          <p class="text-sm text-ink/60 mt-1.5 line-clamp-2 flex-1"><?= htmlspecialchars($m['deskripsi']); ?></p>

          <div class="flex items-end justify-between mt-4 mb-4">
            <span class="font-brand text-xl text-navy-900"><?= rupiah($m['harga']); ?></span>
            <span class="text-xs text-ink/50">Stok <?= (int)$m['stok']; ?></span>
          </div>

          <form action="<?= BASE_URL ?>/proses/tambah_keranjang.php" method="POST" class="flex gap-2">
            <input type="hidden" name="menu_id" value="<?= (int)$m['id']; ?>">
            <input type="number" name="jumlah" value="1" min="1" max="<?= (int)$m['stok']; ?>"
                   class="input-soft !w-16 !py-2 text-center">
            <button type="submit" class="btn-accent flex-1 !py-2">
              <iconify-icon icon="mdi:plus" width="16"></iconify-icon> Tambah
            </button>
          </form>
        </div>
      </article>
    <?php endwhile; ?>
  </div>
  <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>