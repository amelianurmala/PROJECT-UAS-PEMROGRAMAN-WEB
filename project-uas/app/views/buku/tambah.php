<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Koleksi Buku Baru</h5>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="/project-uas/buku/simpan" enctype="multipart/form-data">
                        
                        <div class="row">
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Judul Buku</label>
                                    <input type="text" name="judul" class="form-control" placeholder="Contoh: Pemrograman PHP Modern" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Penulis / Pengarang</label>
                                    <input type="text" name="penulis" class="form-control" placeholder="Nama lengkap penulis" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Kategori</label>
                                            <select name="kategori_id" class="form-select" required>
                                                <option value="" selected disabled>Pilih Kategori</option>
                                                <option value="1">Pendidikan</option>
                                                <option value="2">Novel</option>
                                                <option value="3">Umum</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Jumlah Stok</label>
                                            <input type="number" name="stok" class="form-control" min="1" value="1" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Cover Buku</label>
                                    <div class="border rounded p-2 text-center bg-light mb-2" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                        <img id="preview" src="#" alt="Preview" class="img-fluid rounded shadow-sm d-none" style="max-height: 180px;">
                                        <div id="placeholder">
                                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                            <p class="small text-muted mb-0">Preview Gambar</p>
                                        </div>
                                    </div>
                                    <input type="file" name="gambar" id="inputGambar" class="form-control form-control-sm" accept="image/*">
                                    <div class="form-text small">Gunakan file JPG/PNG, maks. 2MB.</div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="/project-uas/buku" class="btn btn-light px-4">Batal</a>
                            <button type="submit" class="btn btn-success px-5">
                                <i class="bi bi-check-lg me-1"></i> Simpan Koleksi
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    inputGambar.onchange = evt => {
        const [file] = inputGambar.files
        if (file) {
            preview.src = URL.createObjectURL(file)
            preview.classList.remove('d-none');
            placeholder.classList.add('d-none');
        }
    }
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>