<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Buku - SIPERPUS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            @page {
                size: A4 landscape;
                margin: 1cm;
            }
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
        }
        
        .header-laporan {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }
        
        .header-laporan h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .header-laporan h2 {
            font-size: 16pt;
            margin: 5px 0;
        }
        
        .header-laporan p {
            font-size: 10pt;
            margin: 3px 0;
        }
        
        .info-laporan {
            margin-bottom: 20px;
            font-size: 11pt;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table th {
            background-color: #f8f9fa;
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }
        
        table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 10pt;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .footer-laporan {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .ttd {
            text-align: center;
            width: 200px;
        }
        
        .ttd-space {
            height: 80px;
        }
        
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9pt;
            display: inline-block;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }
        
        .badge-success {
            background-color: #28a745;
            color: #fff;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: #fff;
        }
        
        .statistik {
            background-color: #f8f9fa;
            padding: 15px;
            border: 1px solid #000;
            margin-bottom: 20px;
        }
        
        .statistik h5 {
            font-size: 12pt;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Tombol Cetak & Filter (Tidak ikut tercetak) -->
        <div class="no-print mb-4 text-center">
            <button onclick="window.print()" class="btn btn-primary btn-lg me-2">
                <i class="bi bi-printer"></i> Cetak Laporan
            </button>
            <button onclick="window.close()" class="btn btn-secondary btn-lg">
                <i class="bi bi-x"></i> Tutup
            </button>
            
            <div class="mt-3">
                <form method="GET" action="/project-uas/peminjaman/cetak" class="row g-2 justify-content-center">
                    <div class="col-auto">
                        <select name="status" class="form-select">
                            <option value="all" <?= ($_GET['status'] ?? 'all') == 'all' ? 'selected' : '' ?>>Semua Status</option>
                            <option value="Dipinjam" <?= ($_GET['status'] ?? '') == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                            <option value="Selesai" <?= ($_GET['status'] ?? '') == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                            <option value="Terlambat" <?= ($_GET['status'] ?? '') == 'Terlambat' ? 'selected' : '' ?>>Terlambat</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <input type="month" name="bulan" class="form-control" value="<?= $_GET['bulan'] ?? date('Y-m') ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Header Laporan -->
        <div class="header-laporan">
            <h1>Sistem Perpustakaan Digital</h1>
            <h2>LAPORAN DATA PEMINJAMAN BUKU</h2>
            <p>Periode: <?= date('d F Y', strtotime($bulan . '-01')) ?> - <?= date('d F Y') ?></p>
        </div>

        <!-- Info Laporan -->
        <div class="info-laporan">
            <table style="border: none; margin-bottom: 10px;">
                <tr style="border: none;">
                    <td style="border: none; width: 150px;">Tanggal Cetak</td>
                    <td style="border: none; width: 20px;">:</td>
                    <td style="border: none;"><?= date('d F Y, H:i') ?> WIB</td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;">Dicetak Oleh</td>
                    <td style="border: none;">:</td>
                    <td style="border: none;"><?= htmlspecialchars($_SESSION['user']['nama'] ?? 'Admin') ?></td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;">Filter Status</td>
                    <td style="border: none;">:</td>
                    <td style="border: none;"><?= $status == 'all' ? 'Semua Status' : ucfirst($status) ?></td>
                </tr>
            </table>
        </div>

        <!-- Statistik -->
        <?php if (isset($statistik)): ?>
        <div class="statistik">
            <h5>Statistik Peminjaman</h5>
            <table style="border: none;">
                <tr style="border: none;">
                    <td style="border: none; width: 250px;"><strong>Total Peminjaman</strong></td>
                    <td style="border: none; width: 20px;">:</td>
                    <td style="border: none;"><strong><?= $statistik['total_peminjaman'] ?> Transaksi</strong></td>
                    
                    <td style="border: none; width: 250px;"><strong>Sedang Dipinjam</strong></td>
                    <td style="border: none; width: 20px;">:</td>
                    <td style="border: none;"><strong><?= $statistik['sedang_dipinjam'] ?> Buku</strong></td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Sudah Dikembalikan</strong></td>
                    <td style="border: none;">:</td>
                    <td style="border: none;"><strong><?= $statistik['sudah_kembali'] ?> Buku</strong></td>
                    
                    <td style="border: none;"><strong>Terlambat</strong></td>
                    <td style="border: none;">:</td>
                    <td style="border: none;"><strong><?= $statistik['terlambat'] ?> Buku</strong></td>
                </tr>
                <tr style="border: none;">
                    <td style="border: none;"><strong>Total Denda</strong></td>
                    <td style="border: none;">:</td>
                    <td style="border: none;" colspan="4"><strong>Rp <?= number_format($statistik['total_denda'], 0, ',', '.') ?></strong></td>
                </tr>
            </table>
        </div>
        <?php endif; ?>

        <!-- Tabel Data -->
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Judul Buku</th>
                    <th>Peminjam</th>
                    <th style="width: 100px;">Tgl Pinjam</th>
                    <th style="width: 100px;">Tgl Kembali</th>
                    <th style="width: 100px;">Denda</th>
                    <th style="width: 80px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (is_array($data) && count($data) > 0): 
                    $no = 1;
                    $total_denda = 0;
                    foreach($data as $p): 
                        $total_denda += $p['denda'];
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td>
                        <strong><?= htmlspecialchars($p['judul']) ?></strong><br>
                        <small>Penulis: <?= htmlspecialchars($p['penulis'] ?? '-') ?></small>
                    </td>
                    <td><?= htmlspecialchars($p['nama_peminjam'] ?? $p['nama'] ?? '-') ?></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($p['tanggal_pinjam'])) ?></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($p['tanggal_kembali'])) ?></td>
                    <td class="text-right">
                        <?= $p['denda'] > 0 ? 'Rp ' . number_format($p['denda'], 0, ',', '.') : '-' ?>
                    </td>
                    <td class="text-center">
                        <?php if($p['status'] == 'Dipinjam'): ?>
                            <span class="badge badge-warning">Dipinjam</span>
                        <?php elseif($p['status'] == 'Terlambat'): ?>
                            <span class="badge badge-danger">Terlambat</span>
                        <?php else: ?>
                            <span class="badge badge-success">Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5" class="text-right"><strong>TOTAL DENDA</strong></td>
                    <td class="text-right"><strong>Rp <?= number_format($total_denda, 0, ',', '.') ?></strong></td>
                    <td></td>
                </tr>
                <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data peminjaman untuk periode ini.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Footer / Tanda Tangan -->
        <div class="footer-laporan">
            <div class="ttd">
                <p>Mengetahui,</p>
                <p><strong>Kepala Perpustakaan</strong></p>
                <div class="ttd-space"></div>
                <p style="border-top: 1px solid #000; display: inline-block; padding-top: 5px; margin-top: 10px;">
                    <strong>(_________________)</strong>
                </p>
            </div>
            
            <div class="ttd">
                <p><?= date('d F Y') ?></p>
                <p><strong>Petugas Perpustakaan</strong></p>
                <div class="ttd-space"></div>
                <p style="border-top: 1px solid #000; display: inline-block; padding-top: 5px; margin-top: 10px;">
                    <strong><?= htmlspecialchars($_SESSION['user']['nama'] ?? 'Admin') ?></strong>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto print saat halaman load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>