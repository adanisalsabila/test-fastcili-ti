<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit Data Gedung</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('gedung.update', ['id' => $gedung->id_gedung]) }}" id="form_edit"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="kode_gedung">Kode Gedung</label>
                    <input type="text" disabled class="form-control" name="kode_gedung" id="kode_gedung"
                        value="{{ $gedung->kode_gedung }}" required>
                    <small class="text-danger" id="error-kode_gedung"></small>
                </div>
                <div class="form-group mt-3">
                    <label for="nama_gedung">Nama Gedung</label>
                    <input type="text" class="form-control" name="nama_gedung" id="nama_gedung"
                        value="{{ $gedung->nama_gedung }}" required>
                    <small class="text-danger" id="error-nama_gedung"></small>
                </div>
                <div class="form-group mt-3">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3">{{ $gedung->deskripsi }}</textarea>
                    <small class="text-danger" id="error-deskripsi"></small>
                </div>
                <div class="form-group mt-3">
                    <label for="foto_gedung" class="d-block mb-2">Foto Gedung (Kosongkan jika tidak ingin
                        mengubah)</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="foto_gedung" id="foto_gedung"
                            accept="image/*">
                        <label class="custom-file-label bg-warning text-dark text-center w-100" for="foto_gedung"
                            id="file-label">
                            <i class="fas fa-upload mr-2"></i>Pilih File Baru
                        </label>
                    </div>
                    <small class="text-muted d-block mt-1">Format: JPG, PNG, JPEG (Maks. 2MB)</small>
                    @if ($gedung->foto_gedung)
                        <div class="mt-2">
                            <small>Foto Saat Ini:</small>
                            <img src="{{ asset('storage/uploads/foto_gedung/' . $gedung->foto_gedung) }}"
                                class="img-thumbnail mt-1" style="max-height: 100px;">
                        </div>
                    @endif
                    <small class="text-danger" id="error-foto_gedung"></small>
                </div>
                <div class="modal-footer mt-4">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle file input change
        $('#foto_gedung').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            if (fileName) {
                $('#file-label').html('<i class="fas fa-file-image mr-2"></i>' + fileName);
            } else {
                $('#file-label').html(
                    '<i class="fas fa-upload mr-2"></i>Pilih File Baru (Kosongkan jika tidak ingin mengubah)'
                );
            }
        });

        // Reset form when modal is closed
        $('#myModal').on('hidden.bs.modal', function() {
            $('#file-label').html(
                '<i class="fas fa-upload mr-2"></i>Pilih File Baru (Kosongkan jika tidak ingin mengubah)'
            );
        });

        $(document).on('submit', '#form_edit', function(e) {
            e.preventDefault();
            $('.text-danger').text(''); // reset error

            var form = $(this);
            var formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                beforeSend: function() {
                    form.find('button[type=submit]').prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                },
                success: function(response) {
                    form.find('button[type=submit]').prop('disabled', false).html('Simpan');
                    if (response.success) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            loadGedungCards();
                        });
                    }
                },
                error: function(xhr) {
                    form.find('button[type=submit]').prop('disabled', false).html('Simpan');
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#error-' + field).text(messages[0]);
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: xhr.responseJSON.message ||
                            'Terjadi kesalahan saat memproses data.',
                    });
                }
            });
        });
    });
</script>
