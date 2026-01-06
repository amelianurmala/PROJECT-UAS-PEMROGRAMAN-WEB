<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container-fluid mt-4 px-md-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Riwayat Peminjaman</h4>
            <p class="text-muted small">Pantau status peminjaman dan pengembalian buku.</p>
        </div>
        <a href="/project-uas/peminjaman/cetak" class="btn btn-outline-primary btn-sm d-none d-md-inline" target="_blank">
            <i class="bi bi-printer me-1"></i> Cetak Laporan
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 ps-3">Info Buku & Peminjam</th>
                        <th class="text-center">Tgl Pinjam</th>
                        <th class="text-center">Tgl Kembali</th>
                        <th class="text-center">Denda</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($data) && count($data) > 0): ?>
                        <?php foreach($data as $p): ?>
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center">
                                    <img src="/project-uas/public/gambar_buku/<?= !empty($p['gambar']) ? htmlspecialchars($p['gambar']) : 'default_book.jpg' ?>" 
                                         class="rounded me-3 shadow-sm" 
                                         style="width: 40px; height: 55px; object-fit: cover;"
                                         alt="Cover Buku"
                                         onerror="this.src='/project-uas/public/gambar_buku/default_book.jpg'">
                                    <div>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($p['judul']) ?></div>
                                        <div class="text-muted small">
                                            <i class="bi bi-person me-1"></i><?= htmlspecialchars($p['nama_peminjam'] ?? $p['nama'] ?? 'Unknown') ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center small">
                                <div><?= date('d M Y', strtotime($p['tanggal_pinjam'])) ?></div>
                                <small class="text-muted"><?= date('H:i', strtotime($p['tanggal_pinjam'])) ?></small>
                            </td>
                            <td class="text-center small">
                                <div><?= date('d M Y', strtotime($p['tanggal_kembali'])) ?></div>
                                <?php 
                                $today = strtotime(date('Y-m-d'));
                                $due_date = strtotime($p['tanggal_kembali']);
                                $days_diff = ($due_date - $today) / (60 * 60 * 24);
                                
                                if ($p['status'] == 'Dipinjam'):
                                    if ($days_diff < 0): ?>
                                        <small class="text-danger fw-bold"><?= abs(floor($days_diff)) ?> hari terlambat</small>
                                    <?php elseif ($days_diff <= 2): ?>
                                        <small class="text-warning">Segera jatuh tempo</small>
                                    <?php endif;
                                endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($p['denda'] > 0): ?>
                                    <span class="text-danger fw-bold">Rp <?= number_format($p['denda'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($p['status'] == 'Dipinjam'): ?>
                                    <span class="badge rounded-pill bg-warning text-dark px-3">
                                        <i class="bi bi-clock me-1"></i>Dipinjam
                                    </span>
                                <?php elseif($p['status'] == 'Terlambat'): ?>
                                    <span class="badge rounded-pill bg-danger px-3">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Terlambat
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-success px-3">
                                        <i class="bi bi-check-circle me-1"></i>Selesai
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-3">
                                <?php if($p['status'] == 'Dipinjam' || $p['status'] == 'Terlambat'): ?>
                                    <a href="/project-uas/peminjaman/kembalikan?id=<?= $p['id'] ?>" 
                                       class="btn btn-success btn-sm px-3"
                                       onclick="return confirm('Proses pengembalian buku \'<?= htmlspecialchars($p['judul']) ?>\'?\n\n<?= $p['denda'] > 0 ? 'Denda: Rp ' . number_format($p['denda'], 0, ',', '.') : 'Tidak ada denda' ?>')">
                                        <i class="bi bi-arrow-return-left me-1"></i>Kembalikan
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-light btn-sm px-3 text-muted" disabled>
                                        <i class="bi bi-check-all me-1"></i>Tuntas
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                                <p class="text-muted mb-0">Belum ada data peminjaman.</p>
                                <a href="/project-uas/buku" class="btn btn-primary btn-sm mt-3">
                                    <i class="bi bi-book me-1"></i>Lihat Katalog Buku
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (is_array($data) && count($data) > 0): ?>
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="mb-2">
                        <i class="bi bi-book text-primary fs-3"></i>
                    </div>
                    <h5 class="mb-0"><?= count($data) ?></h5>
                    <small class="text-muted">Total Peminjaman</small>
                </div>
                <div class="col-md-3">
                    <div class="mb-2">
                        <i class="bi bi-clock text-warning fs-3"></i>
                    </div>
                    <h5 class="mb-0"><?= count(array_filter($data, fn($p) => $p['status'] == 'Dipinjam')) ?></h5>
                    <small class="text-muted">Sedang Dipinjam</small>
                </div>
                <div class="col-md-3">
                    <div class="mb-2">
                        <i class="bi bi-check-circle text-success fs-3"></i>
                    </div>
                    <h5 class="mb-0"><?= count(array_filter($data, fn($p) => $p['status'] == 'Selesai')) ?></h5>
                    <small class="text-muted">Sudah Kembali</small>
                </div>
                <div class="col-md-3">
                    <div class="mb-2">
                        <i class="bi bi-cash-coin text-danger fs-3"></i>
                    </div>
                    <h5 class="mb-0">Rp <?= number_format(array_sum(array_column($data, 'denda')), 0, ',', '.') ?></h5>
                    <small class="text-muted">Total Denda</small>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>