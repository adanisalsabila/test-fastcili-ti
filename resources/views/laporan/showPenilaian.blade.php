<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
        <form id="form_penilaian" action="{{ route('laporan.simpanPenilaian', ['id' => $laporan->id_laporan]) }}" method="POST" data-trending-no="{{ $trendingNo }}">
            @csrf
            <div class="modal-header bg-gradient-info text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-clipboard-check mr-2"></i>Penilaian Laporan Kerusakan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4">
                <!-- Detail Laporan Card -->
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-info-circle mr-2 text-danger"></i>Detail Laporan
                        </h6>
                        <a href="{{ route('trending.index') }}">
                            <span class="badge badge-warning px-3 py-1">
                                Trending No: {{ $trendingNo }}
                            </span>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $laporan->foto_kerusakan) }}"
                                    class="img-fluid rounded shadow-sm border" 
                                    alt="Foto Kerusakan" 
                                    style="max-height: 250px; width: auto;">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small mb-1">Gedung</label>
                                        <p class="font-weight-bold">
                                            <i class="fas fa-building mr-2 text-info"></i>
                                            {{ $laporan->fasilitas->ruangan->gedung->nama_gedung }}
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small mb-1">Ruangan</label>
                                        <p class="font-weight-bold">
                                            <i class="fas fa-door-open mr-2 text-success"></i>
                                            {{ $laporan->fasilitas->ruangan->nama_ruangan }}
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small mb-1">Fasilitas</label>
                                        <p class="font-weight-bold">
                                            <i class="fas fa-tools mr-2 text-warning"></i>
                                            {{ $laporan->fasilitas->nama_fasilitas }}
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small mb-1">Tanggal Lapor</label>
                                        <p class="font-weight-bold">
                                            <i class="fas fa-calendar-alt mr-2 text-secondary"></i>
                                            {{ $laporan->tanggal_lapor->locale('id')->translatedFormat('l, d F Y') }}

                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="text-muted small mb-1">Total Pelapor</label>
                                        <p class="font-weight-bold">
                                            <i class="fas fa-users mr-2 text-info"></i>
                                            {{ $laporan->pelaporLaporan->count() }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Deskripsi Pelapor</label>
                                    <div class="bg-light p-3 rounded">
                                        <ul class="mb-3 pl-3">
                                            @foreach ($laporan->pelaporLaporan as $pp)
                                                <li>{{ $pp->deskripsi_tambahan }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($trendingNo != '-')
                <!-- Form Penilaian Card -->
                <div class="card border-0">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-edit mr-2 text-danger"></i>Form Penilaian
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Tingkat Kerusakan -->
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="font-weight-bold text-danger mb-3">
                                        <i class="fas fa-bolt mr-2"></i>Tingkat Kerusakan
                                    </h6>
                                    @foreach (['Kurang Parah' => 1, 'Parah' => 3, 'Sangat Parah' => 5] as $label => $val)
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="tingkat_kerusakan_{{ $val }}" 
                                                name="tingkat_kerusakan" value="{{ $val }}" 
                                                class="custom-control-input" required>
                                            <label class="custom-control-label font-weight-bold" 
                                                for="tingkat_kerusakan_{{ $val }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Frekuensi Digunakan -->
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="font-weight-bold text-danger mb-3">
                                        <i class="fas fa-sync-alt mr-2"></i>Frekuensi Digunakan
                                    </h6>
                                    @foreach (['Jarang' => 1, 'Cukup Sering' => 3, 'Sering' => 5] as $label => $val)
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="frekuensi_{{ $val }}" 
                                                name="frekuensi_digunakan" value="{{ $val }}" 
                                                class="custom-control-input" required>
                                            <label class="custom-control-label font-weight-bold" 
                                                for="frekuensi_{{ $val }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Dampak -->
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="font-weight-bold text-danger mb-3">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Dampak
                                    </h6>
                                    @foreach (['Kurang Berdampak' => 1, 'Berdampak' => 3, 'Sangat Berdampak' => 5] as $label => $val)
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="dampak_{{ $val }}" 
                                                name="dampak" value="{{ $val }}" 
                                                class="custom-control-input" required>
                                            <label class="custom-control-label font-weight-bold" 
                                                for="dampak_{{ $val }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Potensi Bahaya -->
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="font-weight-bold text-danger mb-3">
                                        <i class="fas fa-radiation-alt mr-2"></i>Potensi Bahaya
                                    </h6>
                                    @foreach (['Tidak Berbahaya' => 1, 'Cukup Berbahaya' => 3, 'Berbahaya' => 5] as $label => $val)
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="bahaya_{{ $val }}" 
                                                name="potensi_bahaya" value="{{ $val }}" 
                                                class="custom-control-input" required>
                                            <label class="custom-control-label font-weight-bold" 
                                                for="bahaya_{{ $val }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Estimasi Biaya -->
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="font-weight-bold text-danger mb-3">
                                        <i class="fas fa-money-bill-wave mr-2"></i>Estimasi Biaya
                                    </h6>
                                    @foreach ([
                                        '< 250k' => 1,
                                        '250k ≤ biaya < 500k' => 2,
                                        '500k ≤ biaya < 750k' => 3,
                                        '750k ≤ biaya < 1500k' => 4,
                                        '≥ 1500k' => 5,
                                    ] as $label => $val)
                                        <div class="custom-control custom-radio mb-2">
                                            <input type="radio" id="biaya_{{ $val }}" 
                                                name="estimasi_biaya" value="{{ $val }}" 
                                                class="custom-control-input" required>
                                            <label class="custom-control-label font-weight-bold" 
                                                for="biaya_{{ $val }}">
                                                {!! $label !!}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @if ($trendingNo == '-')
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save mr-1"></i> Menuju Halaman Prioritas
                    </button>
                </div>
            @else
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save mr-1"></i> Simpan Penilaian
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle form submission
        $(document).on('submit', '#form_penilaian', function(e) {
            const trendingNo = $(this).data('trending-no');

            // Jika trendingNo == '-', jangan submit, arahkan saja ke prioritas.index
            if (trendingNo === '-') {
                e.preventDefault();

                Swal.fire({
                    icon: "warning",
                    title: "Laporan sudah diberi nilai",
                    text: "Anda akan diarahkan ke halaman Prioritas.",
                    confirmButtonText: "OK",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('prioritas.index') }}";
                    }
                });

                return; // Stop form submission
            }
            
            e.preventDefault();

            $('.error-text').text('');
            const formData = new FormData(this);
            const submitBtn = $(this).find('button[type="submit"]');
            const originalBtnText = submitBtn.html();

            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
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
                            text: response.messages,
                            confirmButtonColor: '#28a745',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: response.messages,
                            confirmButtonColor: '#dc3545'
                        });
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalBtnText);
                    
                    if (xhr.responseJSON && xhr.responseJSON.msgField) {
                        let errors = xhr.responseJSON.msgField;
                        $.each(errors, function(field, messages) {
                            $('#error-' + field).text(messages[0]);
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: "Terjadi kesalahan saat menyimpan data.",
                            confirmButtonColor: '#dc3545'
                        });
                    }
                }
            });
        });
    });
</script>

<style>
    .bg-gradient-info {
        background: linear-gradient(135deg, #b87817 0%, #deaa5b 100%);
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
    .custom-radio .custom-control-label::before {
        border-radius: 50%;
        cursor: pointer;
    }
    .custom-control-input:checked~.custom-control-label::before {
        background-color: #b87817;
        border-color: #b87817;
    }
</style>