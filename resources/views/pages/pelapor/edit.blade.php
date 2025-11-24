<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{ route('pelapor.update', ['id' => $laporan->id]) }}" id="form_edit"
            enctype="multipart/form-data">

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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#form_create').on('submit', function(e) {
            e.preventDefault();
            $('.error-text').text('');

            console.log("Data yang dikirim:", $(this).serialize());

            $.ajax({
                type: "PUT",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('#form_create button[type=submit]').prop('disabled', true).text(
                        'Menyimpan...');
                },
                success: function(response) {
                    $('#form_create button[type=submit]').prop('disabled', false).text(
                        'Simpan');
                    if (response.success) {
                        $('#modal-master').modal('hide');
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: response.message,
                        }).then(() => {
                            window.location.href = "{{ url('/pelapor') }}";
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: "Terjadi kesalahan saat menyimpan.",
                        });
                    }
                },
                error: function(xhr) {
                    $('#form_create button[type=submit]').prop('disabled', false).text(
                        'Simpan');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $('#error-' + field).text(messages[0]);
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: "Kesalahan server.",
                        });
                    }
                }
            });
        });
    });
</script>
