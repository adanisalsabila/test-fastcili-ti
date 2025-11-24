<div id="modal-master" class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow">
        <form method="POST" action="{{ route('level.store') }}" id="form_create">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">Tambah Data Level</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-4 px-5">
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-dark">Kode Level</label>
                    <input type="text" class="form-control form-control-lg rounded" name="kode_level" id="kode_level" required placeholder="Masukkan kode level">
                    <small class="text-danger error-text" id="error-kode_level"></small>
                </div>
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-dark">Nama Level</label>
                    <input type="text" class="form-control form-control-lg rounded" name="nama_level" id="nama_level" required placeholder="Masukkan nama level">
                    <small class="text-danger error-text" id="error-nama_level"></small>
                </div>
            </div>
            <div class="modal-footer bg-light py-3">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Tutup
                </button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).on('submit', '#form_create', function(e) {
        e.preventDefault();

        $('.error-text').text(''); // Hapus error sebelumnya

        let form = $(this);

        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            dataType: "json",
            beforeSend: function() {
                form.find('button[type=submit]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
            },
            success: function(response) {
                form.find('button[type=submit]').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Simpan');
                if (response.success) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.message,
                        showConfirmButton: true
                    }).then(() => {
                        dataLevel.ajax.reload(); // Reload datatable
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.message || 'Terjadi kesalahan saat menyimpan data.',
                    });
                }
            },
            error: function(xhr) {
                form.find('button[type=submit]').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Simpan');
                if (xhr.status === 422 && xhr.responseJSON?.msgField) {
                    let errors = xhr.responseJSON.msgField;
                    $.each(errors, function(field, message) {
                        $('#error-' + field).text(message[0]);
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Validasi Gagal!",
                        text: xhr.responseJSON.message || 'Periksa kembali input Anda.',
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses data.',
                    });
                }
            }
        });
    });
</script>