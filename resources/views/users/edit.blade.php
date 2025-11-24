<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
        <form method="POST" action="{{ url('/users/update/' . $users->id_user) }}" id="form_edit">
            @csrf
            @method('PUT')
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-user-edit mr-2"></i>Edit Data User
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
                                        <option value="{{ $l->id_level }}" @if ($l->id_level == $users->id_level) selected @endif>
                                            {{ $l->nama_level }}</option>
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
                                <input type="text" class="form-control" name="nama" value="{{ $users->nama }}" required placeholder="Masukkan nama lengkap">
                            </div>
                            <small class="text-danger error-text" id="error-nama"></small>
                        </div>

                        <div class="form-group mb-3">
                            <label class="text-muted small mb-1">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="text" class="form-control" name="email" value="{{ $users->email }}" required placeholder="Masukkan alamat email">
                            </div>
                            <small class="text-danger error-text" id="error-email"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
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

        $('.error-text').text(''); // Hapus pesan error sebelumnya

        let form = $(this);
        let submitBtn = form.find('button[type="submit"]');
        let originalBtnText = submitBtn.html();

        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            dataType: "json",
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
                        dataUser.ajax.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.message || 'Gagal menyimpan data.',
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalBtnText);

                if (xhr.status === 422 && xhr.responseJSON?.msgField) {
                    let errors = xhr.responseJSON.msgField;
                    $.each(errors, function(field, messages) {
                        $('#error-' + field).text(messages[0]);
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Validasi Gagal!",
                        text: xhr.responseJSON.message || 'Silakan periksa input Anda.',
                        confirmButtonColor: '#dc3545'
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi Kesalahan!",
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses data.',
                        confirmButtonColor: '#dc3545'
                    });
                }
            }
        });
    });
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #00b4ff 100%);
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