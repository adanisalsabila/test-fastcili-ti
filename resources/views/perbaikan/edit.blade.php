<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
        <form method="POST" action="{{ url('/perbaikan/update/' . $laporan_kerusakan->id_penugasan) }}" id="form_edit" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-tools mr-2"></i>Dokumentasi Hasil Perbaikan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">

                @if ($laporan_kerusakan->komentar_sarpras != null)
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-comment-alt mr-2"></i>Catatan Sarpras: {{ $laporan_kerusakan->komentar_sarpras }}
                    </div>
                @endif

                <div class="card border-0 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-info-circle mr-2 text-primary"></i>Catatan Teknisi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="text-muted small mb-1">Catatan Teknisi</label>
                            <textarea name="catatan_teknisi" class="form-control" rows="3">{{ $laporan_kerusakan->catatan_teknisi }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-camera mr-2 text-warning"></i>Dokumentasi Perbaikan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="text-muted small mb-1">Unggah Dokumentasi</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="dokumentasi" id="dokumentasi_perbaikan" accept="image/*">
                                <label class="custom-file-label bg-light text-center" for="dokumentasi_perbaikan" id="dokumentasi-label">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i>Pilih File Dokumentasi
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle mr-1"></i>Format: JPG, PNG, JPEG (Maks. 2MB)
                            </small>
                            <small class="text-danger" id="error-dokumentasi"></small>

                            @if ($laporan_kerusakan->dokumentasi != null)
                                <small class="form-text text-muted mt-2">*Kosongkan jika tidak ingin mengubah foto.</small>
                                <div class="mt-3 text-center">
                                    <div class="border rounded p-2" style="max-width: 100%;">
                                        <img src="{{ asset('storage/uploads/dokumentasi/' . $laporan_kerusakan->dokumentasi) }}" 
                                            alt="Dokumentasi Lama" 
                                            class="img-fluid rounded" 
                                            style="max-height: 200px;">
                                        <div class="mt-2 text-muted small">Dokumentasi Sebelumnya</div>
                                    </div>
                                </div>
                            @endif

                            <!-- Image Preview Container -->
                            <div class="image-preview-container mt-3 text-center" id="imagePreviewContainer" style="display:none;">
                                <div class="border rounded p-2" style="max-width: 100%;">
                                    <img id="imagePreview" src="#" alt="Preview Dokumentasi" 
                                         class="img-fluid rounded" 
                                         style="max-height: 200px; display: none;">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-danger" id="removeImageBtn">
                                            <i class="fas fa-trash mr-1"></i> Hapus Gambar
                                        </button>
                                    </div>
                                </div>
                            </div>

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
        // Preview gambar saat pilih file
        $('#dokumentasi_perbaikan').on('change', function() {
            var file = this.files[0];

            if (file && file.type.match('image.*')) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result).show();
                    $('#imagePreviewContainer').show();
                }

                reader.readAsDataURL(file);
            } else {
                $('#imagePreview').attr('src', '#').hide();
                $('#imagePreviewContainer').hide();
            }
        });

        // Hapus gambar dan reset input
        $('#removeImageBtn').on('click', function() {
            $('#dokumentasi_perbaikan').val('');
            $('#imagePreview').attr('src', '#').hide();
            $('#imagePreviewContainer').hide();
        });

        // Kosongkan Catatan Teknisi saat user mulai fokus (klik) di textarea
        $('textarea[name="catatan_teknisi"]').one('focus', function() {
            $(this).val('');
        });

        // Submit form dengan SweetAlert tetap dipakai
        $('#form_edit').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                beforeSend: function() {
                    $('#form_edit button[type=submit]').prop('disabled', true).text('Menyimpan...');
                    $('.text-danger').text('');
                },
                success: function(response) {
                    $('#form_edit button[type=submit]').prop('disabled', false).text('Simpan');

                    if (response.success) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: response.messages,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        if (response.msgField) {
                            $.each(response.msgField, function(field, messages) {
                                $('#error-' + field).text(messages[0]);
                            });
                        }

                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: response.messages || 'Terjadi kesalahan.',
                        });
                    }
                },
                error: function(xhr) {
                    $('#form_edit button[type=submit]').prop('disabled', false).text('Simpan');

                    let message = 'Terjadi kesalahan saat mengirim data.';
                    if (xhr.status === 413) {
                        message = 'Ukuran file terlalu besar. Maksimum 2MB.';
                    } else if (xhr.responseJSON && xhr.responseJSON.messages) {
                        message = xhr.responseJSON.messages;
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: message
                    });
                }
            });
        });

        // Reset preview saat modal ditutup
        $('#myModal').on('hidden.bs.modal', function() {
            $('#form_edit')[0].reset();
            $('.text-danger').text('');
            $('#imagePreview').attr('src', '#').hide();
            $('#imagePreviewContainer').hide();

            // Reset event one('focus') supaya bisa dipakai lagi saat buka modal berikutnya
            $('textarea[name="catatan_teknisi"]').off('focus').one('focus', function() {
                $(this).val('');
            });
        });
    });
</script>



<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #ff9900 0%, #ffae00 100%);
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
    .custom-file-label {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        border-radius: 0.25rem;
    }
    .custom-file-input:focus ~ .custom-file-label {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .input-group-text {
        background-color: #f8f9fa;
    }
    textarea {
        resize: none;
    }
    .image-preview-container {
        transition: all 0.3s ease;
    }
    #imagePreview {
        max-width: 100%;
        height: auto;
        border: 1px solid #dee2e6;
    }
</style>
