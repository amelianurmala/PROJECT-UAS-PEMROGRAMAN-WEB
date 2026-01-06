<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i>Edit Data Buku</h5>
                    <a href="/project-uas/buku" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body p-4">
                    <?php if (!isset($buku) || !is_array($buku)): ?>
                        <div class="alert alert-warning border-0 shadow-sm">
                            <i class="bi bi-exclamation-circle me-2"></i> Data buku tidak ditemukan atau telah dihapus.
                        </div>
                    <?php else: ?>

                    <form method="POST" action="/project-uas/buku/update" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $buku['id'] ?>">
                        <input type="hidden" name="gambar_lama" value="<?= $buku['gambar'] ?>">

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Judul Buku</label>
                                    <input type="text" name="judul" class="form-control" 
                                           value="<?= htmlspecialchars($buku['judul']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Penulis</label>
                                    <input type="text" name="penulis" class="form-control" 
                                           value="<?= htmlspecialchars($buku['penulis']) ?>" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Kategori</label>
                                            <select name="kategori_id" class="form-select" required>
                                                <option value="1" <?= $buku['kategori_id']==1?'selected':'' ?>>Pendidikan</option>
                                                <option value="2" <?= $buku['kategori_id']==2?'selected':'' ?>>Novel</option>
                                                <option value="3" <?= $buku['kategori_id']==3?'selected':'' ?>>Umum</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Stok Tersedia</label>
                                            <input type="number" name="stok" class="form-control" 
                                                   value="<?= $buku['stok'] ?>" min="0" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label class="form-label fw-bold">Ganti Cover Buku</label>
                                    <input type="file" name="gambar" class="form-control" accept="image/*">
                                    <div class="form-text text-muted">Format: JPG, PNG, atau WebP. Maksimal 2MB.</div>
                                </div>
                            </div>

                            <div class="col-md-4 text-center border-start">
                                <label class="form-label fw-bold d-block mb-3">Cover Saat Ini</label>
                                <div class="img-preview-container p-2 border rounded bg-light">
                                    <?php if (!empty($buku['gambar']) && file_exists('public/gambar_buku/' . $buku['gambar'])): ?>
                                        <img src="/project-uas/public/gambar_buku/<?= $buku['gambar'] ?>" 
                                             class="img-fluid rounded shadow-sm" 
                                             style="max-height: 250px; object-fit: cover;" 
                                             alt="Cover Buku">
                                    <?php else: ?>
                                        <div class="py-5 text-muted">
                                            <i class="bi bi-image" style="font-size: 3rem;"></i>
                                            <p><small>Tidak ada gambar</small></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-light px-4">Reset</button>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>

                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>