<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ url('/level/update/' . $level->id_level) }}" id="form_edit">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Level</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Level</label>
                    <input type="text" class="form-control" name="kode_level" value="{{ $level->kode_level }}"
                        required>
                    <small class="text-danger error-text" id="error-kode_level"></small>

                </div>
                <div class="form-group">
                    <label>Nama Level</label>
                    <input type="text" class="form-control" name="nama_level" value="{{ $level->nama_level }}"
                        required>
                    <small class="text-danger error-text" id="error-nama_level"></small>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).on('submit', '#form_edit', function(e) {
        e.preventDefault();
        $('.error-text').text('');

        let form = $(this);

        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            dataType: "json",
            beforeSend: function() {
                form.find('button[type=submit]').prop('disabled', true).text('Menyimpan...');
            },
            success: function(response) {
                form.find('button[type=submit]').prop('disabled', false).text('Simpan');
                if (response.success) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: true
                    }).then(() => {
                        dataLevel.ajax.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.message || 'Terjadi kesalahan saat mengubah data.',
                    });
                }
            },
            error: function(xhr) {
                form.find('button[type=submit]').prop('disabled', false).text('Simpan');
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
                        text: xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat memproses data.',
                    });
                }
            }
        });
    });
</script>
