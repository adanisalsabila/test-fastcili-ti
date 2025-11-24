<div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ url('/lapor_kerusakan/update/' . $laporan->id_laporan) }}" id="form_edit" enctype="multipart/form-data">

            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Level</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    Keterangan: {{ $laporan->keterangan }}
                  </div>
                <div class="form-group">
                    <label>Deskripsi Kerusakan</label>
                    <textarea name="deskripsi" class="form-control" required>{{ $laporan->deskripsi }}</textarea>
                    <small class="text-danger error-text" id="error-deskripsi"></small>
                </div>
                <div class="">
                    <label for="foto_kerusakan">Foto Kerusakan</label>
                    <input type="file" name="foto_kerusakan" id="foto_kerusakan" class="form-control-file">
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
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
        const formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function() {
                $('#form_edit button[type=submit]').prop('disabled', true).text('Menyimpan...');
            },
            success: function(response) {
                $('#form_edit button[type=submit]').prop('disabled', false).text('Simpan');
                if (response.success) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.messages,
                    });
                    location.reload();
                } else {
                    alert('Gagal menyimpan data.');
                }
            },
            error: function(xhr) {
                $('#form_edit button[type=submit]').prop('disabled', false).text('Simpan');
                if (xhr.responseJSON && xhr.responseJSON.msgField) {
                    let errors = xhr.responseJSON.msgField;
                    $.each(errors, function(field, messages) {
                        $('#error-' + field).text(messages[0]);
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.messages,
                    });
                }
            }
        });
    });
</script>
