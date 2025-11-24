<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
        <form id="form_penugasan" method="POST" action="{{ url('/penugasan-teknisi') }}">
            @csrf
            <input type="hidden" name="id_laporan" value="{{ $laporan->id_laporan }}">

            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-user-cog mr-2"></i>Penugasan Teknisi
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4">
                <div class="row">
                    <!-- Left Column - Form -->
                    <div class="col-md-6">
                        <div class="card border-0 mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-3 font-weight-bold">Form Penugasan</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="id_user" class="form-label text-muted small">Pilih Teknisi</label>
                                    <select name="id_user" id="id_user" class="form-control" required>
                                        <option value="">- Pilih Teknisi -</option>
                                        @foreach ($teknisi as $t)
                                            <option value="{{ $t->id_user }}">{{ $t->nama }}</option>
                                        @endforeach
                                    </select>
                                    <small id="error-id_user" class="text-danger error-text"></small>
                                </div>
                                <div class="form-group">
                                    <label for="tenggat" class="form-label text-muted small">Tenggat Waktu</label>
                                    <input type="date" name="tenggat" id="tenggat" class="form-control" required>
                                    <small id="error-tenggat" class="text-danger error-text"></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Detail Laporan -->
                    <div class="col-md-6">
                        <div class="card border-0 h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-3 font-weight-bold">Detail Laporan</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Deskripsi</label>
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-3">{{ $laporan->deskripsi }}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="text-muted small mb-1">Ruangan</label>
                                            <p class="font-weight-bold">
                                                <i class="fas fa-door-open mr-2 text-info"></i>
                                                {{ $laporan->fasilitas->ruangan->nama_ruangan ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="text-muted small mb-1">Gedung</label>
                                            <p class="font-weight-bold">
                                                <i class="fas fa-building mr-2 text-primary"></i>
                                                {{ $laporan->fasilitas->ruangan->gedung->nama_gedung ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Fasilitas</label>
                                    <p class="font-weight-bold">
                                        <i class="fas fa-tools mr-2 text-warning"></i>
                                        {{ $laporan->fasilitas->nama_fasilitas ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-user-check mr-1"></i> Tugaskan Teknisi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).on('submit', '#form_penugasan', function(e) {
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
                $('#form_penugasan button[type=submit]').prop('disabled', true).text(
                'Menyimpan...');
            },
            success: function(response) {
                $('#form_penugasan button[type=submit]').prop('disabled', false).text('Okay');
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
                    alert('Gagal menyimpan data.');
                }
            },
            error: function(xhr) {
                $('#form_penugasan button[type=submit]').prop('disabled', false).text('Okay');
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        $('#error-' + field).text(messages[0]);
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: "Terjadi kesalahan saat menyimpan data.",
                    });
                }
            }
        });
    });
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #ff9900 0%, #ffbb00 100%);
    }

    .card {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
    }

    .card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .modal-content {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
    }
</style>
