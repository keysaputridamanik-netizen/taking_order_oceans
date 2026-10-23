<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: " . BASE_URL . "/index.php"); exit; }
$id = (int)($_GET['id'] ?? 0);
$m  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM menu WHERE id=$id"));
if (!$m) { header("Location: " . BASE_URL . "/pelanggan/menu.php"); exit; }

$judul = $m['nama_menu'] . " - Taking Order Cafe";
include '../includes/header.php';
include '../includes/navbar.php';
?>
<main class="max-w-7xl mx-auto px-5 sm:px-6 py-10">
  <a href="<?= BASE_URL ?>/pelanggan/menu.php"
     class="inline-flex items-center gap-1.5 text-sm text-ink/60 hover:text-navy-800 mb-6 transition-colors">
    <iconify-icon icon="mdi:arrow-left" width="16"></iconify-icon> Kembali ke Menu
  </a>

  <div class="grid md:grid-cols-2 gap-10 items-start">
    <div class="card-soft overflow-hidden aspect-square">
      <?php if (!empty($m['gambar']) && file_exists(__DIR__.'/../assets/uploads/menu/'.$m['gambar'])): ?>
        <img src="<?= BASE_URL ?>/assets/uploads/menu/<?= htmlspecialchars($m['gambar']); ?>"
             class="w-full h-full object-cover" alt="<?= htmlspecialchars($m['nama_menu']); ?>">
      <?php else: ?>
        <div class="w-full h-full flex items-center justify-center text-navy-800/15 bg-ice-100">
          <iconify-icon icon="mdi:image-outline" width="72"></iconify-icon>
        </div>
      <?php endif; ?>
    </div>

    <div class="pt-2">
      <span class="badge badge-neutral"><?= htmlspecialchars($m['kategori']); ?></span>
      <h1 class="font-brand text-4xl text-navy-900 mt-3 mb-3"><?= htmlspecialchars($m['nama_menu']); ?></h1>
      <p class="text-ink/70 leading-relaxed mb-6">
        <?= nl2br(htmlspecialchars($m['deskripsi'] ?: 'Tidak ada deskripsi.')); ?>
      </p>

      <div class="flex items-center gap-4 py-5 border-y border-[rgba(14,77,164,0.10)] mb-6">
        <span class="font-brand text-3xl text-navy-900"><?= rupiah($m['harga']); ?></span>
        <span class="text-sm text-ink/50">Stok tersedia: <?= (int)$m['stok']; ?></span>
      </div>

      <?php if ($m['status']==='tersedia' && $m['stok']>0): ?>
        <form action="<?= BASE_URL ?>/proses/tambah_keranjang.php" method="POST" class="flex gap-3">
          <input type="hidden" name="menu_id" value="<?= (int)$m['id']; ?>">
          <input type="number" name="jumlah" value="1" min="1" max="<?= (int)$m['stok']; ?>"
                 class="input-soft !w-24 text-center">
          <button type="submit" class="btn-accent flex-1 !py-3">
            <iconify-icon icon="mdi:cart-plus" width="18"></iconify-icon> Tambah ke Keranjang
          </button>
        </form>
      <?php else: ?>
        <div class="bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-lg text-sm font-semibold">
          Menu sedang habis.
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>
<?php include '../includes/footer.php'; ?>