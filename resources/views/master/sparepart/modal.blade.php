<div class="modal fade" id="modalSparepart" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalSparepartLabel">Form Sparepart</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="formSparepart">
                @csrf
                <input type="hidden" id="modal_id" name="id">

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Kode Part</label>
                            <div class="input-group">
                                <input type="text" name="kode_part" id="modal_kode_part"
                                    class="form-control bg-light" placeholder="Auto-Generated" readonly required>
                                <button class="btn btn-outline-secondary" type="button" id="btn-toggle-barcode"
                                    title="Input Barcode">
                                    <i class="bi bi-upc-scan"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Kategori</label>
                            <input type="text" name="kategori" id="modal_kategori" class="form-control"
                                placeholder="MISAL: BAUT, BAN, OLI" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Nama Barang</label>
                            <input type="text" name="nama_part" id="modal_nama_part" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Harga Beli</label>
                            <input type="text" name="harga_beli" id="modal_harga_beli"
                                class="form-control format-rupiah" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Harga Jual</label>
                            <input type="text" name="harga_jual" id="modal_harga_jual"
                                class="form-control format-rupiah" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small">Stok</label>
                            <input type="number" name="stok" id="modal_stok" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small">Lokasi Rak</label>
                            <input type="text" name="rak" id="modal_rak" class="form-control"
                                placeholder="CONTOH: A1, B2">
                        </div>
                        <div class="col-12 mt-2">
    <label class="form-label fw-bold small">Status Aktif</label>
    <select name="status" id="modal_status" class="form-select">
        <option value="1">AKTIF (DAPAT DIJUAL)</option>
        <option value="0">NON-AKTIF (STOK MATI/TIDAK DIJUAL)</option>
    </select>
</div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" id="btn-save">
                        <i class="bi bi-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMutasi" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Mutasi Stok</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="formMutasi">
                @csrf
                <input type="hidden" name="sparepart_id" id="mutasi_sparepart_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Barang:</label>
                        <div id="label_nama_part" class="form-control-plaintext text-primary fw-bold p-0"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Jenis Mutasi</label>
                        <select name="jenis_mutasi" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="masuk">Stok Masuk (Kulakan/Tambah)</option>
                            <option value="keluar">Stok Keluar (Rusak/Hilang)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" min="1" placeholder="0"
                            required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-info text-white w-100 shadow-sm">Simpan Mutasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalStokMenipis" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-white border-bottom-0 pb-0">
                <h5 class="modal-title d-flex align-items-center text-danger fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Stok Menipis
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between small fw-bold text-muted mb-3 px-2">
                    <span>NAMA SPAREPART</span>
                    <div class="d-flex gap-5">
                        <span>SISA</span>
                        <span>AKSI</span>
                    </div>
                </div>
                <div id="list-stok-menipis" style="max-height: 400px; overflow-y: auto;">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahStokSimple" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h6 class="modal-title fw-bold">Tambah Stok</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-2">
                <p class="text-muted small mb-1">Part Name:</p>
                <h5 id="simple-part-name" class="fw-bold mb-4 text-dark">Nama Sparepart</h5>

                <form id="formTambahStokSimple">
                    <input type="hidden" id="simple-part-id">
                    <div class="mb-4">
                        <input type="number" id="simple-jumlah"
                            class="form-control form-control-lg text-center fw-bold" value="0" min="1"
                            style="border-radius: 12px; border: 2px solid #eee;">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 12px;">
                        Update Stok
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
