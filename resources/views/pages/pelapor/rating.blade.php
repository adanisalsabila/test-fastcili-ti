<div id="modal-master" class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
        <form method="POST" action="{{ route('pelapor.rating', ['id' => $laporan->id_laporan]) }}" id="form_create">
            @csrf
            @method('PUT')
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-star mr-2"></i>Beri Rating dan Ulasan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4">
                <!-- Informasi Laporan Section -->
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-info-circle mr-2 text-primary"></i>Informasi Perbaikan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small mb-1">Fasilitas</label>
                                <p class="font-weight-bold">
                                    <i class="fas fa-tools mr-2 text-warning"></i>
                                    {{ $laporan->laporan->fasilitas->nama_fasilitas ?? '-' }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small mb-1">Selesai Diperbaiki</label>
                                <p class="font-weight-bold">
                                    <i class="fas fa-calendar-alt mr-2 text-info"></i>
                                    {{ $laporan->laporan->tanggal_selesai->translatedFormat('l, d F Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Teknisi</label>
                            <p class="font-weight-bold">
                                <i class="fas fa-user-tie mr-2 text-success"></i>
                                {{ $laporan->penugasan->user->nama }}
                            </p>
                        </div>
                        @if($laporan->penugasan->catatan_teknisi)
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Catatan Teknisi</label>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-3">{{ $laporan->penugasan->catatan_teknisi }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Rating Section -->
                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-comment-alt mr-2 text-primary"></i>Penilaian Anda
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark mb-3">Berikan Rating:</label>
                            <div class="rating-container text-center mb-4">
                                <div class="rating">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating_pengguna"
                                            value="{{ $i }}" {{ old('rating_pengguna') == $i ? 'checked' : '' }} />
                                        <label for="star{{ $i }}" title="{{ $i }} bintang">★</label>
                                    @endfor
                                </div>
                                @error('rating_pengguna')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Ulasan Anda</label>
                                <textarea class="form-control" name="feedback_pengguna" id="feedback_pengguna" rows="4" 
                                    placeholder="Bagaimana pengalaman Anda dengan perbaikan ini?" required>{{ old('feedback_pengguna') }}</textarea>
                                @error('feedback_pengguna')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane mr-1"></i> Kirim Penilaian
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #01bb1d 0%, #66ff00 100%);
    }
    .modal-content {
        border-radius: 0.5rem;
    }
    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
    }
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .card-header {
        padding: 0.75rem 1.25rem;
    }
    .rating {
        direction: rtl;
        unicode-bidi: bidi-override;
        display: inline-block;
    }
    .rating > input {
        display: none;
    }
    .rating > label {
        position: relative;
        width: 1.2em;
        font-size: 2.5rem;
        color: #ddd;
        cursor: pointer;
        margin: 0 2px;
        transition: all 0.2s;
    }
    .rating > label:hover,
    .rating > label:hover ~ label,
    .rating > input:checked ~ label {
        color: gold;
        transform: scale(1.1);
    }
    .rating-container {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 0.5rem;
    }
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    @media (max-width: 576px) {
        .rating-container {
            padding: 0.75rem; /* Perkecil padding */
        }
        .rating > label {
            font-size: 1.8rem; /* Perkecil ukuran bintang */
            margin: 0 1px;     /* Perkecil jarak antar bintang */
        }
        .modal-dialog.modal-lg {
            max-width: 95%; /* Supaya modal tidak terlalu besar di layar kecil */
        }
        .modal-body {
            padding: 1rem; /* Perkecil padding modal */
        }
        textarea.form-control {
            min-height: 100px; /* Perkecil tinggi textarea */
        }
        .rating-container.text-center {
            text-align: center !important; /* Pastikan rata tengah tetap */
        }
    }
</style>

<script>
    $(document).ready(function() {
        $('#form_create').on('submit', function(e) {
            e.preventDefault();
            $('.error-text').text('');

            $.ajax({
                type: "PUT",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('#form_create button[type=submit]').prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Mengirim...'
                    );
                },
                success: function(response) {
                    $('#form_create button[type=submit]').prop('disabled', false).html(
                        '<i class="fas fa-paper-plane mr-1"></i> Kirim Penilaian');
                    if (response.success) {
                        $('#modal-master').modal('hide');
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: response.message,
                            confirmButtonColor: '#28a745'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: response.message || "Terjadi kesalahan saat menyimpan.",
                            confirmButtonColor: '#dc3545'
                        });
                    }
                },
                error: function(xhr) {
                    $('#form_create button[type=submit]').prop('disabled', false).html(
                        '<i class="fas fa-paper-plane mr-1"></i> Kirim Penilaian');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $('#error-' + field).text(messages[0]);
                        });
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: xhr.responseJSON?.message || "Kesalahan server.",
                        confirmButtonColor: '#dc3545'
                    });
                }
            });
        });
    });
</script>