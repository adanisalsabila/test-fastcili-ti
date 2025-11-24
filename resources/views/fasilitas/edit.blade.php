<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
        <form method="POST" action="{{ url('/fasilitas/update/' . $fasilitas->id_fasilitas) }}" id="form_edit">
            @csrf
            @method('PUT')
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-edit mr-2"></i>Edit Data Fasilitas
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-map-marker-alt mr-2 text-primary"></i>Lokasi Fasilitas
                        </h6>
                        <span class="badge badge-info">
                            <i class="fas fa-info-circle mr-1"></i> Informasi Lokasi
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted small mb-1">Gedung</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-building text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control bg-light" 
                                               value="{{ $fasilitas->ruangan->gedung->nama_gedung }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted small mb-1">Ruangan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-door-open text-success"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control bg-light" 
                                               value="{{ $fasilitas->ruangan->nama_ruangan }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-tools mr-2 text-warning"></i>Detail Fasilitas
                        </h6>
                        <span class="badge badge-warning">
                            <i class="fas fa-pencil-alt mr-1"></i> Dapat Diedit
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted small mb-1">Nama Fasilitas <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-tag"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="nama_fasilitas" 
                                               value="{{ $fasilitas->nama_fasilitas }}" required>
                                    </div>
                                    <small id="error-nama_fasilitas" class="text-danger error-text"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted small mb-1">Kode Fasilitas <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-barcode"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" name="kode_fasilitas" 
                                               value="{{ $fasilitas->kode_fasilitas }}" required>
                                    </div>
                                    <small id="error-kode_fasilitas" class="text-danger error-text"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="text-muted small mb-1">Jumlah <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-boxes"></i>
                                            </span>
                                        </div>
                                        <input type="number" class="form-control" name="jumlah" 
                                               value="{{ $fasilitas->jumlah }}" min="1" required>
                                    </div>
                                    <small id="error-jumlah" class="text-danger error-text"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="text-muted small mb-1">Status</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control bg-light" 
                                               value="{{ $fasilitas->status ?? 'Aktif' }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).on('submit', '#form_edit', function(e) {
        e.preventDefault();

        $('.error-text').text('');
        const submitBtn = $(this).find('button[type="submit"]');
        const originalBtnText = submitBtn.html();

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Memperbarui...'
                );
            },
            success: function(response) {
                submitBtn.prop('disabled', false).html(originalBtnText);
                if (response.success) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "Perubahan Disimpan!",
                        text: response.message,
                        confirmButtonColor: '#28a745',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Menyimpan",
                        text: response.message,
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalBtnText);
                if (xhr.responseJSON && xhr.responseJSON.msgField) {
                    let errors = xhr.responseJSON.msgField;
                    $.each(errors, function(field, message) {
                        $('#error-' + field).text(message[0]);
                    });
                }
                Swal.fire({
                    icon: "error",
                    title: "Terjadi Kesalahan",
                    text: xhr.responseJSON?.message || "Gagal menyimpan perubahan",
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    });
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0069d9 0%, #0047ab 100%);
    }
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
    }
    .modal-content {
        border: none;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
    }
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }
    .form-control {
        border-left: none;
    }
    .form-control:disabled {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .btn-outline-secondary {
        border-color: #dee2e6;
    }
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
    }
</style>