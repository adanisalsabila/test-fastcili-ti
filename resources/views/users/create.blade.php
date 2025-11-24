<div id="modal-master" class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
        <form method="POST" action="{{ route('users.store') }}" id="form_create">
            @csrf
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-user-plus mr-2"></i>Tambah Data User
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-user-cog mr-2 text-primary"></i>Informasi User
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="text-muted small mb-1">Level User <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                </div>
                                <select class="form-control" name="id_level" id="id_level" required>
                                    <option value="">--- Pilih Level ---</option>
                                    @foreach ($level as $l)
                                        <option value="{{ $l->id_level }}">{{ $l->nama_level }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-muted small mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control" name="nama" id="nama" required placeholder="Masukkan nama lengkap">
                            </div>
                            <small class="text-danger error-text" id="error-nama"></small>
                        </div>

                        <div class="form-group">
                            <label class="text-muted small mb-1">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="text" class="form-control" name="email" id="email" required placeholder="Masukkan alamat email">
                            </div>
                            <small class="text-danger error-text" id="error-email"></small>
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-muted small mb-1">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="text" class="form-control" name="password" id="password" required placeholder="Masukkan password">
                            </div>
                            <small class="text-danger error-text" id="error-password"></small>
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
        // Reset form saat modal ditutup
        $('#myModal').on('hidden.bs.modal', function() {
            $('#form_create')[0].reset();
            $('.error-text').text('');
        });

        $(document).on('submit', '#form_create', function(e) {
            e.preventDefault();
            $('.error-text').text('');

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
                            if (typeof dataUser !== 'undefined') {
                                dataUser.ajax.reload();
                            }
                        });
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalBtnText);
                    let response = xhr.responseJSON;

                    // Tampilkan error field
                    if (response && response.msgField) {
                        $.each(response.msgField, function(field, messages) {
                            $('#error-' + field).text(messages[0]);
                        });
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response && response.message
                            ? response.message
                            : "Terjadi kesalahan. Silakan coba lagi.",
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
    .text-muted {
        font-size: 0.85rem;
    }
</style>