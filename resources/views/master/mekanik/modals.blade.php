<div class="modal fade" id="modalMekanik" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="modalTitle">TAMBAH MEKANIK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formMekanik">
                <input type="hidden" name="id" id="mekanikId">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">NAMA MEKANIK</label>
                        <input type="text" class="form-control rounded-3" name="nama_mekanik" id="nama_mekanik"
                            required placeholder="Contoh: AGUS BENGKEL">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">NO WHATSAPP</label>
                        <input type="number" class="form-control rounded-3" name="whatsapp" id="whatsapp" required
                            placeholder="08xxxxxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">ALAMAT</label>
                        <textarea class="form-control rounded-3" name="alamat" id="alamat" rows="3" placeholder="Alamat lengkap..."></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted">STATUS</label>
                        <select class="form-select rounded-3" name="status" id="status">
                            <option value="aktif">AKTIF</option>
                            <option value="tidak aktif">TIDAK AKTIF</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm" id="btnSimpan">SIMPAN
                        DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditMekanik" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Data Mekanik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditMekanik" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_mekanik" id="edit_nama_mekanik" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. WhatsApp</label>
                        <input type="number" name="whatsapp" id="edit_whatsapp" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" id="edit_alamat" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="edit_status" class="form-select">
                            <option value="aktif">AKTIF</option>
                            <option value="tidak aktif">TIDAK AKTIF</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
