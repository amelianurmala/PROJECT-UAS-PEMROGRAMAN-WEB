<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container-fluid mt-4 px-md-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-0">Katalog Buku</h4>
            <p class="text-muted small mb-0">Kelola koleksi buku perpustakaan Anda di sini.</p>
        </div>
        <div class="d-flex gap-2 w-100 w-md-auto">
            <form action="/project-uas/buku" method="GET" class="d-flex gap-2 flex-grow-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="cari" class="form-control" placeholder="Cari judul buku..." value="<?= htmlspecialchars($keyword ?? '') ?>">
                </div>
            </form>
            <a href="/project-uas/buku/tambah" class="btn btn-primary btn-sm px-3">
                <i class="bi bi-plus-lg me-1"></i> <span class="d-none d-sm-inline">Tambah Buku</span>
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center py-3" style="width: 50px;">No</th>
                        <th>Info Buku</th>
                        <th class="d-none d-lg-table-cell">Kategori</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($buku) && count($buku) > 0): ?>
                        <?php 
                        $no = ($page - 1) * $limit + 1; 
                        foreach ($buku as $b): 
                        ?>
                        <tr>
                            <td class="text-center text-muted"><?= $no++; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="/project-uas/public/gambar_buku/<?= !empty($b['gambar']) ? htmlspecialchars($b['gambar']) : 'default_book.jpg' ?>" 
                                         class="rounded me-3 shadow-sm" 
                                         style="width: 45px; height: 60px; object-fit: cover;"
                                         alt="Cover Buku"
                                         onerror="this.src='/project-uas/public/gambar_buku/default_book.jpg'">
                                    <div>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($b['judul']); ?></div>
                                        <small class="text-muted d-block d-md-none"><?= htmlspecialchars($b['nama_kategori'] ?? 'Tanpa Kategori'); ?></small>
                                        <small class="text-secondary"><?= htmlspecialchars($b['penulis']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell">
                                <span class="badge bg-light text-dark border"><?= htmlspecialchars($b['nama_kategori'] ?? 'Tanpa Kategori'); ?></span>
                            </td>
                            <td class="text-center">
                                <?php if($b['stok'] > 0): ?>
                                    <span class="badge rounded-pill bg-success-subtle text-success px-3"><?= (int)$b['stok']; ?></span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-danger-subtle text-danger px-3">Habis</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm border shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li><a class="dropdown-item py-2" href="/project-uas/peminjaman/form?id=<?= $b['id']; ?>"><i class="bi bi-book me-2 text-primary"></i>Pinjam Buku</a></li>
                                        <li><a class="dropdown-item py-2" href="/project-uas/buku/edit?id=<?= $b['id']; ?>"><i class="bi bi-pencil me-2 text-warning"></i>Edit</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item py-2 text-danger" 
                                               href="/project-uas/buku/hapus?id=<?= $b['id']; ?>" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus buku \'<?= htmlspecialchars($b['judul']); ?>\'?')">
                                                <i class="bi bi-trash me-2"></i>Hapus
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                                <p class="text-muted mb-0">
                                    <?php if (!empty($keyword)): ?>
                                        Tidak ada buku yang cocok dengan pencarian "<strong><?= htmlspecialchars($keyword) ?></strong>"
                                    <?php else: ?>
                                        Data buku belum tersedia. Silakan tambahkan buku baru.
                                    <?php endif; ?>
                                </p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (isset($pages) && $pages > 1): ?>
    <nav class="mt-4" aria-label="Pagination">
        <ul class="pagination pagination-sm justify-content-center">
            <!-- Previous Button -->
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= max(1, $page - 1) ?>&cari=<?= urlencode($keyword ?? '') ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <?php 
            // Pagination logic: show max 5 page numbers
            $start = max(1, $page - 2);
            $end = min($pages, $page + 2);
            
            if ($start > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=1&cari=<?= urlencode($keyword ?? '') ?>">1</a>
                </li>
                <?php if ($start > 2): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
            <?php endif; ?>

            <?php for($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&cari=<?= urlencode($keyword ?? '') ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($end < $pages): ?>
                <?php if ($end < $pages - 1): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $pages ?>&cari=<?= urlencode($keyword ?? '') ?>"><?= $pages ?></a>
                </li>
            <?php endif; ?>

            <!-- Next Button -->
            <li class="page-item <?= ($page >= $pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= min($pages, $page + 1) ?>&cari=<?= urlencode($keyword ?? '') ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>