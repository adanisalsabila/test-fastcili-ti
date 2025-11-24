<div id="modal-master" class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
        <form method="POST" action="{{ route('fasilitas.store') }}" id="form_create">
            @csrf
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Data Fasilitas
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-map-marker-alt mr-2 text-primary"></i>Informasi Lokasi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted small mb-1">Gedung <span class="text-danger">*</span></label>
                                    <select name="id_gedung" id="gedung" class="form-control" required>
                                        <option value="">Pilih Gedung</option>
                                        @foreach ($gedung as $g)
                                            <option value="{{ $g->id_gedung }}">{{ $g->nama_gedung }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Pilih gedung tempat fasilitas berada</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted small mb-1">Ruangan <span class="text-danger">*</span></label>
                                    <select name="id_ruangan" id="ruangan" class="form-control" required>
                                        <option value="">Pilih Ruangan</option>
                                        @foreach ($ruangan as $r)
                                            <option value="{{ $r->id_ruangan }}">{{ $r->nama_ruangan }}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Pilih ruangan tempat fasilitas berada</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-tools mr-2 text-warning"></i>Detail Fasilitas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted small mb-1">Kode Fasilitas <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="kode_fasilitas" id="kode_fasilitas" required>
                                    </div>
                                    <small class="form-text text-muted">Masukkan kode unik fasilitas</small>
                                    <small class="text-danger error-text" id="error-kode_fasilitas"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-muted small mb-1">Nama Fasilitas <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-cube"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="nama_fasilitas" id="nama_fasilitas" required>
                                    </div>
                                    <small class="form-text text-muted">Masukkan nama fasilitas</small>
                                    <small class="text-danger error-text" id="error-nama_fasilitas"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="text-muted small mb-1">Jumlah <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        </div>
                                        <input type="number" class="form-control" name="jumlah" id="jumlah" min="1" required>
                                    </div>
                                    <small class="form-text text-muted">Masukkan jumlah fasilitas yang tersedia</small>
                                    <small class="text-danger error-text" id="error-jumlah"></small>
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
    let isSubmitting = false;

    $(document).on('submit', '#form_create', function(e) {
        e.preventDefault();

        if (isSubmitting) return;
        isSubmitting = true;

        $('.error-text').text('');
        const submitBtn = $(this).find('button[type="submit"]');
        const originalBtnText = submitBtn.html();

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function() {
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Menyimpan...'
                );
            },
            success: function(response) {
                isSubmitting = false;
                submitBtn.prop('disabled', false).html(originalBtnText);
                if (response.success) {
                    $('#modal-master').modal('hide'); 
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.message,
                        confirmButtonColor: '#28a745',
                        showConfirmButton: true
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.message,
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function(xhr) {
                isSubmitting = false;
                submitBtn.prop('disabled', false).html(originalBtnText);
                if (xhr.responseJSON && xhr.responseJSON.msgField) {
                    let errors = xhr.responseJSON.msgField;
                    $.each(errors, function(field, message) {
                        $('#error-' + field).text(message[0]);
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: "Terjadi kesalahan saat menyimpan data. Silakan coba lagi.",
                        confirmButtonColor: '#dc3545'
                    });
                }
            }
        });
    });

    $(document).ready(function() {
        $('#gedung').change(function() {
            let gedungId = $(this).val();
            $('#ruangan').html('<option value="">Memuat...</option>').prop('disabled', true);

            if (gedungId) {
                $.ajax({
                    url: "{{ route('fasilitas.getRuangan', '') }}/" + gedungId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log('Response:', data); 
                        let options = '<option value="">Pilih Ruangan</option>';

                        if (data && data.length > 0) {
                            $.each(data, function(i, ruangan) {
                                options += '<option value="' + ruangan.id_ruangan + '">' + ruangan.nama_ruangan + '</option>';
                            });
                        } else {
                            options = '<option value="">Tidak ada ruangan</option>';
                        }

                        $('#ruangan').html(options).prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', status, error); 
                        $('#ruangan').html('<option value="">Gagal memuat ruangan</option>')
                            .prop('disabled', true);
                    }
                });
            } else {
                $('#ruangan').html('<option value="">Pilih Gedung terlebih dahulu</option>')
                    .prop('disabled', true);
            }
        });

        $('#modal-master').on('hidden.bs.modal', function () {
            $('#ruangan').html('<option value="">Pilih Ruangan</option>').prop('disabled', false);
        });

        $('#modal-master').on('show.bs.modal', function () {
            $('#gedung').val(''); 
            $('#ruangan').html('<option value="">Pilih Ruangan</option>').prop('disabled', false);
            $('.error-text').text('');
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
</style>