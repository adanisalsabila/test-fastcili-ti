<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-gradient-primary text-white">
            <h5 class="modal-title font-weight-bold">
                <i class="fas fa-building mr-2"></i>Tambah Gedung Baru
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body p-4">
            <form method="POST" action="{{ route('gedung.store') }}" enctype="multipart/form-data" id="form_create">
                @csrf
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-info-circle mr-2 text-primary"></i>Informasi Gedung
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted small mb-1">Nama Gedung</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        </div>
                                        <input type="text" name="nama_gedung" id="nama_gedung" class="form-control" required>
                                    </div>
                                    <small class="text-danger" id="error-nama_gedung"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted small mb-1">Kode Gedung</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input type="text" name="kode_gedung" id="kode_gedung" class="form-control" required>
                                    </div>
                                    <small class="text-danger" id="error-kode_gedung"></small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="text-muted small mb-1">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
                            <small class="text-danger" id="error-deskripsi"></small>
                        </div>
                    </div>
                </div>

                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-camera mr-2 text-warning"></i>Foto Gedung
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="text-muted small mb-1">Unggah Foto</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="foto_gedung" id="foto_gedung" accept="image/*" required>
                                <label class="custom-file-label bg-light text-center" for="foto_gedung" id="file-label">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i>Pilih File Gedung
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle mr-1"></i>Format: JPG, PNG, JPEG (Maks. 2MB)
                            </small>
                            <small class="text-danger" id="error-foto_gedung"></small>
                        </div>
                        
                        <!-- Image Preview Container -->
                        <div class="image-preview-container mt-3 text-center" id="imagePreviewContainer" style="display:none;">
                            <div class="border rounded p-2" style="max-width: 100%;">
                                <img id="imagePreview" src="#" alt="Preview Gambar Gedung" 
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

                <div class="modal-footer bg-light mt-4 px-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan Gedung
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle file input change and show preview
        $('#foto_gedung').on('change', function() {
            var file = this.files[0];
            var fileName = file ? file.name : '';
            
            if (fileName) {
                $('#file-label').html('<i class="fas fa-file-image mr-2"></i>' + fileName);
                
                // Show image preview
                if (file && file.type.match('image.*')) {
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                        $('#imagePreview').attr('src', e.target.result).show();
                        $('#imagePreviewContainer').show();
                    }
                    
                    reader.readAsDataURL(file);
                }
            } else {
                $('#file-label').html('<i class="fas fa-cloud-upload-alt mr-2"></i>Pilih File Gedung');
                $('#imagePreviewContainer').hide();
            }
        });

        // Remove image and reset file input
        $('#removeImageBtn').on('click', function() {
            $('#foto_gedung').val('');
            $('#file-label').html('<i class="fas fa-cloud-upload-alt mr-2"></i>Pilih File Gedung');
            $('#imagePreview').attr('src', '#').hide();
            $('#imagePreviewContainer').hide();
        });

        // Reset form when modal is closed
        $('#myModal').on('hidden.bs.modal', function() {
            $('#form_create')[0].reset();
            $('.text-danger').text('');
            $('#file-label').html('<i class="fas fa-cloud-upload-alt mr-2"></i>Pilih File Gedung');
            $('#imagePreview').attr('src', '#').hide();
            $('#imagePreviewContainer').hide();
        });

        // Form submission
        $(document).on('submit', '#form_create', function(e) {
            e.preventDefault();
            $('.text-danger').text('');
            var form = $(this);
            var formData = new FormData(this);
            var submitBtn = form.find('button[type="submit"]');
            var originalBtnText = submitBtn.html();

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: formData,
                contentType: false,
                processData: false,
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
                            loadGedungCards();
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