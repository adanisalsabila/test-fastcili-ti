<div id="modal-master" class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
        <form method="POST" action="{{ route('ruangan.store') }}" id="form_create">
            @csrf
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-door-open mr-2"></i>Tambah Data Ruangan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-info-circle mr-2 text-primary"></i>Informasi Ruangan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="text-muted small mb-1">Gedung <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                </div>
                                <select class="form-control" name="id_gedung" id="id_gedung" required>
                                    <option value="">Pilih Gedung</option>
                                    @foreach ($gedung as $g)
                                        <option value="{{ $g->id_gedung }}">{{ $g->nama_gedung }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="text-muted small mb-1">Kode Ruangan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                </div>
                                <input type="text" class="form-control" name="kode_ruangan" id="kode_ruangan" required>
                            </div>
                            <small class="text-danger error-text" id="error-kode_ruangan"></small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="text-muted small mb-1">Nama Ruangan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-door-closed"></i></span>
                                </div>
                                <input type="text" class="form-control" name="nama_ruangan" id="nama_ruangan" required>
                            </div>
                            <small class="text-danger error-text" id="error-nama_ruangan"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Reset form ketika modal ditutup
        $('#myModal').on('hidden.bs.modal', function() {
            $('#form_create')[0].reset();
            $('.error-text').text('');
        });

        $(document).on('submit', '#form_create', function(e) {
            e.preventDefault();
            $('.error-text').text(''); // Reset error messages

            var form = $(this);
            var formData = new FormData(this);
            var submitBtn = form.find('button[type="submit"]');
            var originalBtnText = submitBtn.html();

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend: function() {
                    submitBtn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Menyimpan...'
                    );
                },
                success: function(response) {
                    submitBtn.prop('disabled', false).html(originalBtnText);
                    if (response.success) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: response.message,
                            confirmButtonColor: '#28a745',
                            showConfirmButton: true
                        }).then(() => {
                            loadRuanganCards(); // Memuat ulang data ruangan
                        });
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalBtnText);
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#error-' + field).text(messages[0]);
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: xhr.responseJSON.message ||
                            'Terjadi kesalahan saat memproses data.',
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        });
    });
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #01bb1d 0%, #66ff00 100%);
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
    .input-group-text {
        background-color: #f8f9fa;
    }
    select.form-control:not([size]):not([multiple]) {
        height: calc(2.25rem + 6px);
    }
</style>