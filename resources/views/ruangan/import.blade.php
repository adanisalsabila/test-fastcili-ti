<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
        <form action="{{ url('/ruangan/import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
            @csrf
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-file-import mr-2"></i>Import Data Ruangan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-file-download mr-2 text-info"></i>Template Import
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="text-muted small mb-1">Download Template Excel</label>
                            <div>
                                <a href="{{ asset('template_ruangan.xlsx') }}" class="btn btn-info" download>
                                    <i class="fas fa-file-excel mr-2"></i> Download Template
                                </a>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>Gunakan template ini untuk memastikan format data benar
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-file-upload mr-2 text-warning"></i>File Import
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="text-muted small mb-1">Pilih File Excel</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="file_ruangan" id="file_ruangan" required>
                                <label class="custom-file-label bg-light text-center" for="file_ruangan" id="file-label">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i>Pilih File (.xlsx)
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle mr-1"></i>Format: Excel (.xlsx)
                            </small>
                            <small class="text-danger" id="error-file_ruangan"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-upload mr-1"></i> Upload Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle file input change
        $('#file_ruangan').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            if (fileName) {
                $('#file-label').html('<i class="fas fa-file-excel mr-2"></i>' + fileName);
            } else {
                $('#file-label').html('<i class="fas fa-cloud-upload-alt mr-2"></i>Pilih File (.xlsx)');
            }
        });

        // Submit form import
        $(document).on('submit', '#form-import', function(e) {
            e.preventDefault();

            var form = this;
            var fileInput = $('#file_ruangan')[0];
            var file = fileInput.files[0];
            var submitBtn = $(this).find('button[type="submit"]');
            var originalBtnText = submitBtn.html();

            $('.error-text').text(''); // reset error text

            if (!file.name.endsWith('.xlsx')) {
                $('#error-file_ruangan').text('Hanya file Excel (.xlsx) yang diperbolehkan.');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Format file tidak valid. Harus berupa .xlsx',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }

            // Siapkan FormData
            var formData = new FormData(form);

            // Kirim AJAX
            $.ajax({
                type: form.method,
                url: form.action,
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function() {
                    submitBtn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Mengupload...'
                    );
                },
                success: function(response) {
                    submitBtn.prop('disabled', false).html(originalBtnText);

                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonColor: '#28a745'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message || 'Terjadi kesalahan saat mengupload file',
                            confirmButtonColor: '#dc3545'
                        }).then(() => {
                            location.reload();
                        });

                        if (response.msgField) {
                            $.each(response.msgField, function(field, messages) {
                                $('#error-' + field).text(messages[0]);
                            });
                        }
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalBtnText);

                    let message = 'Terjadi kesalahan saat mengupload file';
                    let listErrors = '';

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        if (xhr.responseJSON.errors && Array.isArray(xhr.responseJSON.errors)) {
                            xhr.responseJSON.errors.forEach(function(err) {
                                err;
                            });
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: message + listErrors,
                        confirmButtonColor: '#dc3545'
                    });

                    // Tampilkan error per field
                    if (xhr.responseJSON && xhr.responseJSON.msgField) {
                        $.each(xhr.responseJSON.msgField, function(field, messages) {
                            $('#error-' + field).text(messages[0]);
                        });
                    }
                }
            });
        });
    });
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #ff9900 0%, #ffbb00 100%);
    }
    .card {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
    }
    .card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .modal-content {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
    }
    .custom-file-label {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        border-radius: 0.25rem;
    }
    .custom-file-input:focus ~ .custom-file-label {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
    }
</style>