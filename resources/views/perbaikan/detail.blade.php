<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-gradient-primary text-white">
            <h5 class="modal-title font-weight-bold">
                <i class="fas fa-clipboard-list mr-2"></i>Detail Data Perbaikan
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body p-4">
            <!-- Informasi Laporan Card -->
            <div class="card border-0 mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-3 font-weight-bold">
                        <i class="fas fa-info-circle mr-2 text-primary"></i>Informasi Laporan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="text-center">
                                <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $perbaikan->laporan->foto_kerusakan) }}"
                                    alt="Foto Kerusakan" class="img-fluid rounded shadow-sm border"
                                    style="max-height: 250px; width: auto;"
                                    onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Fasilitas</label>
                                <p class="font-weight-bold">
                                    <i class="fas fa-tools mr-2 text-warning"></i>
                                    {{ $perbaikan->laporan->fasilitas->nama_fasilitas ?? '-' }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small mb-1">Deskripsi Kerusakan</label>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0">{{ $perbaikan->laporan->deskripsi }}</p>
                                </div>
                            </div>

                            <!-- Komentar Sarpras Section -->
                            @if ($perbaikan->komentar_sarpras)
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Komentar Sarpras</label>
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-0">{{ $perbaikan->komentar_sarpras }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Tanggal Lapor</label>
                                    <p class="font-weight-bold">
                                        <i class="fas fa-calendar-alt mr-2 text-info"></i>
                                        {{ $perbaikan->laporan->tanggal_lapor
                                            ? $perbaikan->laporan->tanggal_lapor->translatedFormat('l, d F Y')
                                            : '-' }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Teknisi</label>
                                    <p class="font-weight-bold">
                                        <i class="fas fa-user-tie mr-2 text-success"></i>
                                        {{ $perbaikan->user->nama }}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Tenggat Perbaikan</label>
                                    <p class="font-weight-bold">
                                        <i class="fas fa-calendar-alt mr-2 text-info"></i>
                                        {{ $perbaikan->tenggat
                                            ? $perbaikan->tenggat->translatedFormat('l, d F Y')
                                            : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Perbaikan Card -->
            @if ($perbaikan->status_perbaikan == 'Selesai Dikerjakan')
                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-check-circle mr-2 text-success"></i>Informasi Perbaikan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="text-center">
                                    <img src="{{ asset('storage/uploads/dokumentasi/' . $perbaikan->dokumentasi) }}"
                                        onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';"
                                        alt="Dokumentasi Perbaikan" class="img-fluid rounded shadow-sm border"
                                        style="max-height: 250px; width: auto;">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Status</label>
                                    <p>
                                        @if ($perbaikan->laporan->id_status == 4)
                                            <span class="badge badge-danger px-3 py-2">
                                                <i class="fas fa-check mr-1"></i>
                                                {{ $perbaikan->laporan->status->nama_status }}
                                            </span>
                                        @else
                                            <span class="badge badge-success px-3 py-2">
                                                <i class="fas fa-check mr-1"></i>
                                                {{ $perbaikan->status_perbaikan }}
                                            </span>
                                        @endif
                                    </p>
                                </div>

                                <!-- Catatan Teknisi Section -->
                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Catatan Teknisi</label>
                                    <div class="bg-light p-3 rounded">
                                        <p class="mb-0">{{ $perbaikan->catatan_teknisi ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small mb-1">Selesai Dikerjakan</label>
                                    <p class="font-weight-bold">
                                        <i class="fas fa-calendar-check mr-2 text-primary"></i>
                                        {{ $perbaikan->tanggal_selesai
                                            ? $perbaikan->tanggal_selesai->translatedFormat('l, d F Y')
                                            : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Informasi Rating Card -->
            @if ($perbaikan->laporan->id_status == '4')
                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-check-circle mr-2 text-success"></i>Rating dan Ulasan dari Pelapor
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <!-- Tampilan Rating berupa Bintang sebanyak 5 -->
                            <div class="col-12 text-center mb-3">
                                @php
                                    $fullStars = floor($ratingAkhir);
                                    $halfStar = ($ratingAkhir - $fullStars) >= 0.5 ? true : false;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp
                    
                                <div class="d-flex justify-content-center align-items-center" style="font-size: 2rem;">
                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <i class="fas fa-star" style="color: #FFA500;"></i>
                                    @endfor
                    
                                    @if ($halfStar)
                                        <i class="fas fa-star-half-alt" style="color: #FFA500;"></i>
                                    @endif
                    
                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <i class="far fa-star" style="color: #FFA500;"></i>
                                    @endfor
                                </div>
                    
                                <small class="text-muted mt-1 d-block">
                                    Rating Akhir: {{ number_format($ratingAkhir, 2) }} / 5
                                </small>
                            </div>
                    
                            <!-- Informasi Pendukung dan Rating -->
                            <div class="col-12 text-center mb-3">
                                <p class="mb-1 font-weight-bold">
                                    {{ $jumlahRatingDiberikan }} dari {{ $jumlahPendukung }} orang yang mendukung laporan ini telah memberikan rating.
                                </p>
                            </div>
                    
                            <!-- Daftar Ulasan -->
                            <div class="col-12 mt-3">
                                <label class="text-muted small mb-1">Ulasan Pengguna (Top 10)</label>
                                <ul class="list-group">
                                    @forelse ($ulasan as $item)
                                        <li class="list-group-item">
                                            <i class="fas fa-comment-dots text-primary mr-2"></i>{{ $item }}
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted">Belum ada ulasan.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>                    
                </div>
            @endif
        </div>
        <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fas fa-times mr-1"></i> Tutup
            </button>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #00b4ff 100%);
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

    .badge-success {
        background-color: #28a745;
    }

    .fas.fa-star, .fas.fa-star-half-alt, .far.fa-star {
        transition: color 0.3s ease;
    }
</style>
