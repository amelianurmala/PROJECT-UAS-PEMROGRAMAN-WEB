<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-journal-plus me-2"></i>Konfirmasi Peminjaman</h5>
                </div>
                <div class="card-body p-4">
                    
                    <div class="row mb-4 align-items-center bg-light p-3 rounded mx-0">
                        <div class="col-md-2 text-center">
                            <img src="/project-uas/public/gambar_buku/<?= !empty($buku['gambar']) ? $buku['gambar'] : 'default_book.jpg' ?>" 
                                 class="img-fluid rounded shadow-sm" style="max-height: 100px;">
                        </div>
                        <div class="col-md-10 mt-3 mt-md-0">
                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($buku['judul']) ?></h5>
                            <p class="text-muted mb-0 small">Penulis: <?= htmlspecialchars($buku['penulis']) ?></p>
                            <span class="badge bg-success-subtle text-success mt-2">Stok Tersedia: <?= $buku['stok'] ?></span>
                        </div>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger border-0 shadow-sm small">
                            <i class="bi bi-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/project-uas/peminjaman/simpan">
                        <input type="hidden" name="buku_id" value="<?= $buku['id'] ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Tanggal Pinjam</label>
                                <input type="date" 
                                       name="tanggal_pinjam" 
                                       id="tgl_pinjam"
                                       class="form-control"
                                       value="<?= date('Y-m-d') ?>"
                                       required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Tanggal Kembali (Maks. 7 Hari)</label>
                                <input type="date" 
                                       name="tanggal_kembali" 
                                       id="tgl_kembali"
                                       class="form-control"
                                       required>
                                <div class="form-text small text-info">
                                    <i class="bi bi-info-circle me-1"></i>Denda Rp 2.000/hari jika terlambat.
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning border-0 small mt-3">
                            <i class="bi bi-shield-check me-2"></i>Dengan meminjam, Anda setuju untuk menjaga buku tetap dalam kondisi baik.
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="/project-uas/buku" class="btn btn-light px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold">
                                <i class="bi bi-check2-square me-2"></i>Konfirmasi Pinjam
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const tglPinjam = document.getElementById('tgl_pinjam');
    const tglKembali = document.getElementById('tgl_kembali');

    tglPinjam.addEventListener('change', function() {
        let date = new Date(this.value);
        date.setDate(date.getDate() + 7); // Tambah 7 hari
        
        // Format ke YYYY-MM-DD
        let nextWeek = date.toISOString().split('T')[0];
        tglKembali.value = nextWeek;
        tglKembali.min = this.value; // Tanggal kembali tidak boleh sebelum tanggal pinjam
    });

    // Jalankan sekali saat load
    window.onload = () => tglPinjam.dispatchEvent(new Event('change'));
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>