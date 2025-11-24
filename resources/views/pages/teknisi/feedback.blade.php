<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-0 shadow modal-scrollable">
        <div class="modal-header bg-gradient-success text-white">
            <h5 class="modal-title font-weight-bold">
                <i class="fa-solid fa-comments"></i>&nbsp;&nbsp;Feedback Teknisi
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="mainModalClose">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form method="POST" enctype="multipart/form-data" action="{{ route('teknisi.feedbacksimpan', ['id' => $penugasan->id_penugasan]) }}" id="form_create">
            @csrf
            @method('PUT')
            <div class="modal-body p-4">
                <div class="form-group mt-3">
                    <label for="dokumentasi">Dokumentasi</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="dokumentasi"
                            id="dokumentasi" accept="image/*" required>
                        <label class="custom-file-label bg-warning text-dark text-center w-100"
                            for="dokumentasi" id="file-label">
                            <i class="fas fa-upload mr-2"></i>Unggah Dokumentasi
                        </label>
                    </div>
                    <span class="text-danger error-text" id="error-dokumentasi"></span>
                </div>
            
                <div class="form-group mt-3">
                    <label for="catatan_teknisi">Catatan</label>
                    <input type="text" name="catatan_teknisi" id="catatan_teknisi" class="form-control" required>
                    <span class="text-danger error-text" id="error-catatan_teknisi"></span>
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

            const formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
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
                        });
                        location.reload();
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

<style>
    .bg-gradient-success {
        background: linear-gradient(135deg, #2092d4 0%, #3060e6 100%);
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    textarea {
        resize: none;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
    }
    #mainModalClose {
        opacity: 0.9;
        transition: opacity 0.2s;
    }
    #mainModalClose:hover {
        opacity: 1;
    }
    #expandedImageContainer .card-body {
        max-height: 60vh;
        overflow-y: auto;
    }
</style>
