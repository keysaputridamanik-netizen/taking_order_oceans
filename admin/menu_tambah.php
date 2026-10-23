<?php
require_once __DIR__ . '/../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) redirect('/admin/login.php');

$judul = "Tambah Menu";
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/sidebar.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_menu']);
    $kat    = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga  = (float)$_POST['harga'];
    $desc   = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $stok   = (int)$_POST['stok'];
    $status = $_POST['status'];
    $gambar = uploadGambarMenu($_FILES['gambar'] ?? []);

    $gambar_sql = $gambar ? "'$gambar'" : "NULL";
    $sql = "INSERT INTO menu (nama_menu,kategori,harga,deskripsi,stok,status,gambar)
            VALUES ('$nama','$kat',$harga,'$desc',$stok,'$status',$gambar_sql)";
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['pesan'] = "Menu berhasil ditambahkan.";
        redirect('/admin/menu.php');
    }
    $error = "Gagal menyimpan: " . mysqli_error($koneksi);
}
?>
<div class="flex min-h-screen">
  <?php renderSidebar('menu'); ?>
  <div class="flex-1 min-w-0">
    <header class="bg-white border-b border-[rgba(14,77,164,0.08)] h-16 flex items-center px-6 md:px-8 sticky top-0 z-30">
      <a href="<?= BASE_URL ?>/admin/menu.php" class="inline-flex items-center gap-1.5 text-sm text-ink/60 hover:text-navy-800 mr-4">
        <iconify-icon icon="mdi:arrow-left" width="16"></iconify-icon> Kembali
      </a>
      <h1 class="font-semibold text-navy-900">Tambah Menu</h1>
    </header>

    <main class="p-6 md:p-8">
      <div class="max-w-2xl">
        <?php if ($error): ?>
          <div class="alert-error mb-5">
            <iconify-icon icon="mdi:alert-circle-outline" width="18"></iconify-icon>
            <?= $error ?>
          </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="card-soft p-6 space-y-5">
          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-1.5">Nama Menu</label>
              <input type="text" name="nama_menu" required class="input-soft">
            </div>
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-1.5">Kategori</label>
              <input type="text" name="kategori" required placeholder="Kopi / Makanan" class="input-soft">
            </div>
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-1.5">Harga</label>
              <input type="number" name="harga" required class="input-soft">
            </div>
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-1.5">Stok</label>
              <input type="number" name="stok" value="0" required class="input-soft">
            </div>
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-1.5">Status</label>
              <select name="status" class="input-soft">
                <option value="tersedia">Tersedia</option>
                <option value="habis">Habis</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-1.5">Gambar</label>
              <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp" class="input-soft">
              <p class="text-xs text-ink/50 mt-1">JPG/PNG/WEBP, maks 3MB.</p>
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-navy-900 mb-1.5">Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="input-soft"></textarea>
          </div>
          <div class="flex gap-2 pt-2">
            <button type="submit" class="btn-primary">
              <iconify-icon icon="mdi:content-save-outline" width="16"></iconify-icon> Simpan
            </button>
            <a href="<?= BASE_URL ?>/admin/menu.php" class="btn-ghost">Batal</a>
          </div>
        </form>
      </div>
    </main>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>