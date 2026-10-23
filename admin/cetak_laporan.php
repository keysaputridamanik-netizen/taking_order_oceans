<?php
require_once __DIR__ . '/../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) redirect('/admin/login.php');

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$ta = mysqli_real_escape_string($koneksi, $tgl_awal);
$tk = mysqli_real_escape_string($koneksi, $tgl_akhir);

$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o JOIN pelanggan p ON o.pelanggan_id=p.id
                             WHERE DATE(o.waktu_order) BETWEEN '$ta' AND '$tk'
                             ORDER BY o.waktu_order ASC");
$data = []; $total = 0;
while ($row = mysqli_fetch_assoc($q)) {
    $data[] = $row;
    if ($row['status'] === 'selesai') $total += $row['total_harga'];
}
?>
<!DOCTYPE html>
<html lang="id"><head>
<meta charset="UTF-8"><title>Cetak Laporan</title>
<style>
  @page { size: A4; margin: 15mm; }
  * { box-sizing: border-box; }
  body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #2B3A4A; margin: 0; }
  .header { text-align: center; border-bottom: 3px double #0C3B7C; padding-bottom: 12px; margin-bottom: 18px; }
  .brand-lock { display: inline-flex; align-items: center; gap: 10px; margin-bottom: 4px; }
  .brand-lock img { height: 40px; }
  .brand-lock .brand-name { font-size: 20px; font-weight: 800; color: #0C3B7C; letter-spacing: 1px; }
  .header p { margin: 2px 0; font-size: 11px; color: #2B3A4A; }
  .title { text-align: center; font-size: 14px; font-weight: 700; color: #0C3B7C; margin: 8px 0 14px; letter-spacing: 1px; }
  .meta { font-size: 11px; margin-bottom: 12px; }
  .meta p { margin: 2px 0; }
  table { width: 100%; border-collapse: collapse; font-size: 11px; }
  th, td { border: 1px solid #2B3A4A; padding: 6px 8px; }
  th { background: #F0F8FF; text-align: center; font-weight: 700; color: #0C3B7C; }
  .right { text-align: right; }
  .center { text-align: center; }
  .total { margin-top: 14px; text-align: right; font-size: 13px; font-weight: 700; color: #0C3B7C; }
  .ttd { margin-top: 50px; text-align: right; font-size: 11px; }
  .ttd .name { margin-top: 60px; display: inline-block; border-top: 1px solid #2B3A4A; padding-top: 3px; min-width: 160px; text-align: center; }
</style>
</head>
<body>

<div class="header">
  <div class="brand-lock">
    <img src="<?= BASE_URL ?>/assets/logo-brand.png" alt="Logo">
    <span class="brand-name">TAKING ORDER CAFE</span>
  </div>
  <p>Jl. Contoh Alamat No. 123, Kota Anda</p>
  <p>Telp: 021-1234567 | info@takingordercafe.com</p>
</div>

<div class="title">LAPORAN ORDER</div>

<div class="meta">
  <p>Periode: <?= date('d/m/Y', strtotime($tgl_awal)); ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)); ?></p>
  <p>Tanggal Cetak: <?= date('d/m/Y H:i'); ?></p>
</div>

<table>
  <thead>
    <tr>
      <th style="width:32px">No</th>
      <th style="width:130px">Kode Order</th>
      <th>Nama Pelanggan</th>
      <th style="width:55px">Meja</th>
      <th style="width:100px">Total</th>
      <th style="width:80px">Status</th>
      <th style="width:110px">Waktu</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($data)): ?>
      <tr><td colspan="7" class="center">Tidak ada data pada periode ini.</td></tr>
    <?php else: $no=1; foreach ($data as $o): ?>
      <tr>
        <td class="center"><?= $no++; ?></td>
        <td><?= htmlspecialchars($o['kode_order']); ?></td>
        <td><?= htmlspecialchars($o['nama']); ?></td>
        <td class="center"><?= htmlspecialchars($o['no_meja']); ?></td>
        <td class="right"><?= rupiah($o['total_harga']); ?></td>
        <td class="center"><?= ucfirst($o['status']); ?></td>
        <td class="center"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<div class="total">Total Pendapatan (Order Selesai): <?= rupiah($total); ?></div>

<div class="ttd">
  <p>Kota Anda, <?= date('d/m/Y'); ?></p>
  <p>Administrator,</p>
  <p class="name"><?= htmlspecialchars($_SESSION['admin_nama']); ?></p>
</div>

<script>window.onload = () => window.print(); window.onafterprint = () => window.close();</script>
</body></html>