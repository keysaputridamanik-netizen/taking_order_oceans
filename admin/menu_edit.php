<?php
require_once __DIR__ . '/../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) redirect('/admin/login.php');

$judul = "Edit Menu";
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
$m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM menu WHERE id=$id"));
if (!$m) redirect('/admin/menu.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_menu']);
    $kat    = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga  = (float)$_POST['harga'];
    $desc   = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $stok   = (int)$_POST['stok'];
    $status = $_POST['status'];
    $gambar = uploadGambarMenu($_FILES['gambar'] ?? []);

    $set_gambar = $gambar ? ", gambar='$gambar'" : "";
    $sql = "UPDATE menu SET nama_menu='$nama', kategori='$kat', harga=$harga,
            deskripsi='$desc', stok=$stok, status='$status' $set_gambar WHERE id=$id";
    if (mysqli_query($koneksi, $sql)) {
        if ($gambar && !empty($m['gambar']) && file_exists(__DIR__.'/../assets/uploads/menu/'.$m['gambar'])) {
            @unlink(__DIR__.'/../assets/uploads/menu/'.$m['gambar']);
        }
        $_SESSION['pesan'] = "Menu berhasil diperbarui.";
        redirect('/admin/menu.php');
    }
}
?>
<div class="flex min-h-screen">
  <?php renderSidebar('menu'); ?>
  <div class="flex-1 min-w-0">
    <header class="bg-white border-b border-[rgba(14,77,164,0.08)] h-16 flex items-center px-6 md:px-8 sticky top-0 z-30">
      <a href="<?= BASE_URL ?>/admin/menu.php" class="inline-flex items-center gap-1.5 text-sm text-ink/60 hover:text-navy-800 mr-4">
        <iconify-icon icon="mdi:arrow-left" width="16"></iconify-icon> Kembali
      </a>
      <h1 class="font-semibold text-navy-900">Edit Menu</h1>
    </header>

    <main class="p-6 md:p-8">
      <div class="max-w-2xl">
        <form method="POST" enctype="multipart/form-data" class="card-soft p-6 space-y-5">
          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-1.5">Nama Menu</label>
              <input type="text" name="nama_menu" value="<?= htmlspecialchars($m['nama_menu']); ?>" required class="input-soft">
            </div>
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-1.5">Kategori</label>
              <input type="text" name="kategori" value="<?= htmlspecialchars($m['kategori']); ?>" required class="input-soft">
            </div>
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-1.5">Harga</label>
              <input type="number" name="harga" value="<?= $m['harga']; ?>" required class="input-soft">
            </div>
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-1.5">Stok</label>
              <input type="number" name="stok" value="<?= (int)$m['stok']; ?>" required class="input-soft">
            </div>
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-1.5">Status</label>
              <select name="status" class="input-soft">
                <option value="tersedia" <?= $m['status']==='tersedia'?'selected':'' ?>>Tersedia</option>
                <option value="habis"     <?= $m['status']==='habis'?'selected':'' ?>>Habis</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-1.5">Ganti Gambar</label>
              <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp" class="input-soft">
            </div>
          </div>

          <?php if (!empty($m['gambar']) && file_exists(__DIR__.'/../assets/uploads/menu/'.$m['gambar'])): ?>
            <div>
              <p class="text-xs font-semibold text-ink/60 uppercase tracking-wider mb-2">Gambar Saat Ini</p>
              <img src="<?= BASE_URL ?>/assets/uploads/menu/<?= htmlspecialchars($m['gambar']); ?>"
                   class="w-32 h-32 object-cover rounded-lg border border-[rgba(14,77,164,0.08)]">
            </div>
          <?php endif; ?>

          <div>
            <label class="block text-sm font-semibold text-navy-900 mb-1.5">Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="input-soft"><?= htmlspecialchars($m['deskripsi']); ?></textarea>
          </div>
          <div class="flex gap-2 pt-2">
            <button type="submit" class="btn-primary">
              <iconify-icon icon="mdi:content-save-outline" width="16"></iconify-icon> Update
            </button>
            <a href="<?= BASE_URL ?>/admin/menu.php" class="btn-ghost">Batal</a>
          </div>
        </form>
      </div>
    </main>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>