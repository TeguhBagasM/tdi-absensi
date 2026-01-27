<div class="modal fade" id="manualCheckinModal" tabindex="-1" aria-labelledby="manualCheckinLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="manualCheckinLabel">Manual Check-in</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="manualCheckinForm">
                @csrf
                <div class="modal-body">
                    <div class="alert-container mb-3"></div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status Kehadiran <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required onchange="handleStatusChange()">
                            <option value="">-- Pilih Status --</option>
                            <option value="wfh">WFH (Bekerja dari Rumah)</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Keterangan/Alasan <span class="text-danger">*</span></label>
                        <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="Jelaskan alasan Anda..." required></textarea>
                        <small class="text-muted">
                            <span id="wfh-info" style="display: none;">WFH hanya diizinkan 1x dalam seminggu sesuai kebijakan perusahaan.</span>
                        </small>
                    </div>

                    <div class="mb-3" id="file-group" style="display: none;">
                        <label for="file" class="form-label">Bukti (File) <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Format: PDF, JPG, PNG | Maksimal: 5MB</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function handleStatusChange() {
        const status = document.getElementById('status').value;
        const wfhInfo = document.getElementById('wfh-info');
        const fileGroup = document.getElementById('file-group');
        const fileInput = document.getElementById('file');

        // Show WFH info
        wfhInfo.style.display = status === 'wfh' ? 'inline' : 'none';

        // Show file upload untuk izin dan sakit
        if (status === 'izin' || status === 'sakit') {
            fileGroup.style.display = 'block';
            fileInput.required = true;
        } else {
            fileGroup.style.display = 'none';
            fileInput.required = false;
        }
    }

    document.getElementById('manualCheckinForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

        fetch('{{ route("attendance.store-manual") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (response.status === 422) {
                return response.json().then(data => {
                    throw { type: 'validation', data };
                });
            }
            return response.json();
        })
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: data.message,
                timer: 2000
            }).then(() => {
                location.reload();
            });
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;

            if (error.type === 'validation') {
                const alertContainer = document.querySelector('#manualCheckinForm .alert-container');
                alertContainer.innerHTML = '';

                const errors = error.data.errors;
                Object.keys(errors).forEach(field => {
                    errors[field].forEach(message => {
                        const alertHtml = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>${field}:</strong> ${message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;
                        alertContainer.innerHTML += alertHtml;
                    });
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan'
                });
            }
        });
    });
</script>
