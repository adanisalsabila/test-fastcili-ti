@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'prioritas',
])

@section('content')
    <div class="content">
        <h3 class="mb-3 mb-md-4">Data Prioritas Perbaikan</h3>
        <div class="card px-2 px-md-4">
            <div
                class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center pb-3 pb-md-3 pt-3 pt-md-5">
                <!-- Kolom kiri: Filter -->
                <div class="w-100 mb-3 mb-md-0" style="max-width: 580px;">
                    <input type="text" class="form-control rounded-pill" id="search"
                        placeholder="Cari Laporan Prioritas...">
                    <small class="form-text text-muted text-small">
                        *Cari berdasarkan deskripsi laporan atau status
                    </small>
                    <span class="badge badge-secondary px-3 py-2 mt-4 mb-2 d-inline-block">
                        <i class="fas fa-sort-amount-down-alt mr-1"></i>
                        Diurutkan berdasarkan: <strong>Nilai WASPAS</strong>
                    </span>
                </div>
                <div class="d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-start justify-content-md-end">
                    <a class="btn btn-info text-white" data-toggle="modal" data-target="#waspasModal">
                        <i class="fas fa-calculator mr-1"></i> Lihat Perhitungan WASPAS
                    </a>

                </div>
            </div>


            <div class="card-body p-0">
                <div id="priority-container">
                    @php
                        $firstNotAssignedShown = false;
                    @endphp
                    @foreach ($ranked as $r)
                        @php
                            $penugasan = $r['penugasan'];
                            $statusPerbaikan = $penugasan->status_perbaikan ?? null;
                            $rank = $r['rank'];

                            $borderColor = match ($rank) {
                                1 => 'border-left: 4px solid #dc3545;',
                                2 => 'border-left: 4px solid #fd7e14;',
                                3 => 'border-left: 4px solid #ffc107;',
                                default => 'border-left: 4px solid #e9ecef;',
                            };

                            $statusColor = match ($statusPerbaikan ?? 'Belum Dikerjakan') {
                                'Belum Dikerjakan' => 'badge-secondary',
                                'Sedang Dikerjakan' => 'badge-warning',
                                'Selesai Dikerjakan' => 'badge-info',
                                'Selesai Diperbaiki' => 'badge-success',
                                default => 'badge-secondary',
                            };
                        @endphp
                        <div class="card priority-card mb-3" style="{{ $borderColor }}">
                            <div class="card-body p-2 p-md-3">
                                <div class="d-flex flex-column flex-md-row">
                                    <div class="rank-display d-flex flex-row flex-md-column align-items-center justify-content-center mr-0 mr-md-3 mb-2 mb-md-0 p-2"
                                        style="min-width: 60px;">
                                        <span class="rank-number font-weight-bold">#{{ $rank }}</span>
                                        <div class="priority-indicator mt-0 mt-md-1 ml-2 ml-md-0">
                                            @if ($rank <= 3)
                                                <i class="fas fa-exclamation-circle text-danger"></i>
                                            @else
                                                <i class="fas fa-arrow-up text-primary"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 d-flex flex-column">
                                        <div class="d-flex flex-column flex-md-row">
                                            <div class="mr-0 mr-md-3 mb-3 mb-md-0 w-100" style="max-width: 220px;">
                                                <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $r['foto_kerusakan']) }}"
                                                    onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';"
                                                    alt="Foto Kerusakan" class="img-fluid rounded w-100"
                                                    style="height: 140px; object-fit: cover;">
                                            </div>
                                            <div class="flex-grow-1 d-flex flex-column">
                                                <div class="mb-3">
                                                    <p class="card-text">{{ $r['deskripsi'] ?? '-' }}</p>
                                                </div>
                                                <div class="d-flex flex-wrap align-items-center mb-2">
                                                    <span class="badge badge-pill badge-dark px-2 px-md-3 py-2 mr-2 mb-2">
                                                        <i class="fas fa-calculator mr-1"></i> Nilai WASPAS:
                                                        {{ number_format($r['Q'], 4) }}
                                                    </span>
                                                    <span
                                                        class="badge badge-pill 
                                                        @if ($statusPerbaikan === 'Tidak Selesai') badge-warning 
                                                        @else 
                                                            {{ $statusColor }} @endif 
                                                        px-2 px-md-3 py-2 mr-2 mb-2">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        @if ($statusPerbaikan === 'Tidak Selesai')
                                                            Sedang Dikerjakan
                                                        @else
                                                            {{ $statusPerbaikan ?? 'Belum Dikerjakan' }}
                                                        @endif
                                                    </span>

                                                    @if ($penugasan && $penugasan->komentar_sarpras)
                                                        <span
                                                            class="badge badge-pill badge-light border px-2 px-md-3 py-2 mb-2">
                                                            <i class="fas fa-clipboard mr-1"></i> Catatan:
                                                            {{ Str::limit($penugasan->komentar_sarpras, 20) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-end">
                                                        @if (!$penugasan && !$firstNotAssignedShown)
                                                            {{-- Belum ada penugasan teknisi --}}
                                                            @php $firstNotAssignedShown = true; @endphp
                                                            <button
                                                                onclick="modalAction('{{ url('/laporan/penugasan/' . $r['id_laporan']) }}')"
                                                                class="btn btn-danger btn-lg px-4 py-2">
                                                                <i class="fas fa-user-tie mr-1"></i> Tugaskan Teknisi
                                                            </button>
                                                        @elseif (!$penugasan)
                                                            <button class="btn btn-secondary btn-lg px-4 py-2 disabled">
                                                                <i class="fas fa-user-slash mr-1"></i> Belum Ditugaskan Teknisi
                                                            </button>
                                                        @elseif ($statusPerbaikan === 'Selesai Dikerjakan')
                                                            {{-- Teknisi sudah menyelesaikan, sarpras perlu verifikasi --}}
                                                            <button
                                                                onclick="modalAction('{{ url('/laporan/verifikasi/' . $r['id_laporan']) }}')"
                                                                class="btn btn-info btn-lg px-4 py-2">
                                                                <i class="fas fa-check-double mr-1"></i> Verifikasi
                                                            </button>
                                                        @elseif ($statusPerbaikan === 'Tidak Selesai')
                                                            {{-- Penugasan Tidak Selesai --}}
                                                            <span class="btn btn-secondary btn-lg px-4 py-2 disabled">
                                                                <i class="fas fa-clock mr-1"></i> Menunggu perbaikan
                                                            </span>
                                                        @elseif ($penugasan && $statusPerbaikan !== 'Selesai' && \Carbon\Carbon::parse($penugasan->tenggat)->isPast())
                                                            {{-- Sudah ditugaskan, belum selesai, dan lewat tenggat waktu --}}
                                                            <button
                                                                onclick="modalAction('{{ url('/laporan/ganti-teknisi/' . $r['id_laporan']) }}')"
                                                                class="btn btn-warning btn-lg px-4 py-2">
                                                                <i class="fas fa-user-edit mr-1"></i> Ganti Teknisi
                                                            </button>
                                                        @else
                                                            {{-- Teknisi sudah ditugaskan, belum mulai atau belum update --}}
                                                            <span class="btn btn-secondary btn-lg px-4 py-2 disabled">
                                                                <i class="fas fa-clock mr-1"></i> Menunggu perbaikan
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- WASPAS Calculation Modal -->
    <div class="modal fade" id="waspasModal" tabindex="-1" role="dialog" aria-labelledby="waspasModalLabel"
        aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title font-weight-bold" id="waspasModalLabel">
                        <i class="fas fa-calculator mr-2"></i>Proses Perhitungan WASPAS
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3 p-md-4">
                    <div class="calculation-steps">
                        <!-- Step 1: Criteria and Weights -->
                        <div class="step mb-4 mb-md-5">
                            <div class="d-flex align-items-center mb-2 mb-md-3">
                                <span class="badge badge-warning mr-2"
                                    style="font-size: 1rem; padding: 0.5rem 0.75rem;">1</span>
                                <h5 class="m-0 font-weight-bold text-dark">Kriteria dan Bobot</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="bg-light-blue">
                                        <tr>
                                            <th class="text-center font-weight-bold">Kriteria</th>
                                            <th class="text-center font-weight-bold">Bobot</th>
                                            <th class="text-center font-weight-bold">Tipe</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Tingkat Kerusakan</td>
                                            <td class="text-center">0.30</td>
                                            <td class="text-center"><span class="badge badge-success">Benefit</span></td>
                                        </tr>
                                        <tr>
                                            <td>Frekuensi Digunakan</td>
                                            <td class="text-center">0.10</td>
                                            <td class="text-center"><span class="badge badge-success">Benefit</span></td>
                                        </tr>
                                        <tr>
                                            <td>Dampak</td>
                                            <td class="text-center">0.05</td>
                                            <td class="text-center"><span class="badge badge-success">Benefit</span></td>
                                        </tr>
                                        <tr>
                                            <td>Estimasi Biaya</td>
                                            <td class="text-center">0.35</td>
                                            <td class="text-center"><span class="badge badge-danger">Cost</span></td>
                                        </tr>
                                        <tr>
                                            <td>Potensi Bahaya</td>
                                            <td class="text-center">0.20</td>
                                            <td class="text-center"><span class="badge badge-success">Benefit</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Step 2: Original Data -->
                        <div class="step mb-4 mb-md-5">
                            <div class="d-flex align-items-center mb-2 mb-md-3">
                                <span class="badge badge-warning mr-2"
                                    style="font-size: 1rem; padding: 0.5rem 0.75rem;">2</span>
                                <h5 class="m-0 font-weight-bold text-dark">Data Awal</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="bg-light-blue">
                                        <tr>
                                            <th class="text-center font-weight-bold">Laporan</th>
                                            <th class="text-center font-weight-bold">Tingkat Kerusakan</th>
                                            <th class="text-center font-weight-bold">Frekuensi</th>
                                            <th class="text-center font-weight-bold">Dampak</th>
                                            <th class="text-center font-weight-bold">Estimasi Biaya</th>
                                            <th class="text-center font-weight-bold">Potensi Bahaya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ranked as $r)
                                            <tr>
                                                <td>{{ Str::limit($r['deskripsi'], 30) }}</td>
                                                <td class="text-center">{{ $r['tingkat_kerusakan'] ?? '-' }}</td>
                                                <td class="text-center">{{ $r['frekuensi_digunakan'] ?? '-' }}</td>
                                                <td class="text-center">{{ $r['dampak'] ?? '-' }}</td>
                                                <td class="text-center">{{ $r['estimasi_biaya'] ?? '-' }}</td>
                                                <td class="text-center">{{ $r['potensi_bahaya'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Step 3: Normalization -->
                        <div class="step mb-4 mb-md-5">
                            <div class="d-flex align-items-center mb-2 mb-md-3">
                                <span class="badge badge-warning mr-2"
                                    style="font-size: 1rem; padding: 0.5rem 0.75rem;">3</span>
                                <h5 class="m-0 font-weight-bold text-dark">Normalisasi Matriks</h5>
                            </div>
                            <div class="alert alert-info mb-2 mb-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <div>
                                        <p class="mb-1">Untuk kriteria benefit: <code>X_ij = nilai_awal /
                                                max(nilai_kriteria)</code></p>
                                        <p class="mb-0">Untuk kriteria cost: <code>X_ij = min(nilai_kriteria) /
                                                nilai_awal</code></p>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="bg-light-blue">
                                        <tr>
                                            <th class="text-center font-weight-bold">Laporan</th>
                                            <th class="text-center font-weight-bold">Tingkat Kerusakan</th>
                                            <th class="text-center font-weight-bold">Frekuensi</th>
                                            <th class="text-center font-weight-bold">Dampak</th>
                                            <th class="text-center font-weight-bold">Estimasi Biaya</th>
                                            <th class="text-center font-weight-bold">Potensi Bahaya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ranked as $r)
                                            <tr>
                                                <td>{{ Str::limit($r['deskripsi'], 30) }}</td>
                                                <td class="text-center">
                                                    {{ number_format($r['tingkat_kerusakan'] / max(array_column($ranked, 'tingkat_kerusakan')), 4) }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($r['frekuensi_digunakan'] / max(array_column($ranked, 'frekuensi_digunakan')), 4) }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($r['dampak'] / max(array_column($ranked, 'dampak')), 4) }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format(min(array_column($ranked, 'estimasi_biaya')) / $r['estimasi_biaya'], 4) }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($r['potensi_bahaya'] / max(array_column($ranked, 'potensi_bahaya')), 4) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Step 4: WSM Calculation -->
                        <div class="step mb-4 mb-md-5">
                            <div class="d-flex align-items-center mb-2 mb-md-3">
                                <span class="badge badge-warning mr-2"
                                    style="font-size: 1rem; padding: 0.5rem 0.75rem;">4</span>
                                <h5 class="m-0 font-weight-bold text-dark">Hitung WSM (Weighted Sum Model)</h5>
                            </div>
                            <div class="alert alert-info mb-2 mb-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <div>
                                        <p class="mb-0"><code>WSM = Σ(X_ij × bobot_kriteria)</code></p>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="bg-light-blue">
                                        <tr>
                                            <th class="text-center font-weight-bold">Laporan</th>
                                            <th class="text-center font-weight-bold">Perhitungan WSM</th>
                                            <th class="text-center font-weight-bold">Hasil WSM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ranked as $r)
                                            @php
                                                $normalizedTK =
                                                    $r['tingkat_kerusakan'] /
                                                    max(array_column($ranked, 'tingkat_kerusakan'));
                                                $normalizedFD =
                                                    $r['frekuensi_digunakan'] /
                                                    max(array_column($ranked, 'frekuensi_digunakan'));
                                                $normalizedD = $r['dampak'] / max(array_column($ranked, 'dampak'));
                                                $normalizedEB =
                                                    min(array_column($ranked, 'estimasi_biaya')) / $r['estimasi_biaya'];
                                                $normalizedPB =
                                                    $r['potensi_bahaya'] / max(array_column($ranked, 'potensi_bahaya'));

                                                $wsm =
                                                    $normalizedTK * 0.3 +
                                                    $normalizedFD * 0.1 +
                                                    $normalizedD * 0.05 +
                                                    $normalizedEB * 0.35 +
                                                    $normalizedPB * 0.2;
                                            @endphp
                                            <tr>
                                                <td>{{ Str::limit($r['deskripsi'], 30) }}</td>
                                                <td>
                                                    ({{ number_format($normalizedTK, 4) }}×0.3)
                                                    +
                                                    ({{ number_format($normalizedFD, 4) }}×0.1) +
                                                    ({{ number_format($normalizedD, 4) }}×0.05) +
                                                    ({{ number_format($normalizedEB, 4) }}×0.35) +
                                                    ({{ number_format($normalizedPB, 4) }}×0.2)
                                                </td>
                                                <td class="text-center font-weight-bold">{{ number_format($wsm, 4) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Step 5: WPM Calculation -->
                        <div class="step mb-4 mb-md-5">
                            <div class="d-flex align-items-center mb-2 mb-md-3">
                                <span class="badge badge-warning mr-2"
                                    style="font-size: 1rem; padding: 0.5rem 0.75rem;">5</span>
                                <h5 class="m-0 font-weight-bold text-dark">Hitung WPM (Weighted Product Model)</h5>
                            </div>
                            <div class="alert alert-info mb-2 mb-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <div>
                                        <p class="mb-0"><code>WPM = Π(X_ij ^ bobot_kriteria)</code></p>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="bg-light-blue">
                                        <tr>
                                            <th class="text-center font-weight-bold">Laporan</th>
                                            <th class="text-center font-weight-bold">Perhitungan WPM</th>
                                            <th class="text-center font-weight-bold">Hasil WPM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ranked as $r)
                                            @php
                                                $normalizedTK =
                                                    $r['tingkat_kerusakan'] /
                                                    max(array_column($ranked, 'tingkat_kerusakan'));
                                                $normalizedFD =
                                                    $r['frekuensi_digunakan'] /
                                                    max(array_column($ranked, 'frekuensi_digunakan'));
                                                $normalizedD = $r['dampak'] / max(array_column($ranked, 'dampak'));
                                                $normalizedEB =
                                                    min(array_column($ranked, 'estimasi_biaya')) / $r['estimasi_biaya'];
                                                $normalizedPB =
                                                    $r['potensi_bahaya'] / max(array_column($ranked, 'potensi_bahaya'));

                                                $wpm =
                                                    pow($normalizedTK, 0.3) *
                                                    pow($normalizedFD, 0.1) *
                                                    pow($normalizedD, 0.05) *
                                                    pow($normalizedEB, 0.35) *
                                                    pow($normalizedPB, 0.2);
                                            @endphp
                                            <tr>
                                                <td>{{ Str::limit($r['deskripsi'], 30) }}</td>
                                                <td>
                                                    ({{ number_format($normalizedTK, 4) }}<sup>0.3</sup>)
                                                    ×
                                                    ({{ number_format($normalizedFD, 4) }}<sup>0.1</sup>) ×
                                                    ({{ number_format($normalizedD, 4) }}<sup>0.05</sup>) ×
                                                    ({{ number_format($normalizedEB, 4) }}<sup>0.35</sup>) ×
                                                    ({{ number_format($normalizedPB, 4) }}<sup>0.2</sup>)
                                                </td>
                                                <td class="text-center font-weight-bold">{{ number_format($wpm, 4) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Step 6: WASPAS Calculation -->
                        <div class="step mb-4 mb-md-5">
                            <div class="d-flex align-items-center mb-2 mb-md-3">
                                <span class="badge badge-warning mr-2"
                                    style="font-size: 1rem; padding: 0.5rem 0.75rem;">6</span>
                                <h5 class="m-0 font-weight-bold text-dark">Hitung Nilai WASPAS (Q)</h5>
                            </div>
                            <div class="alert alert-info mb-2 mb-md-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <div>
                                        <p class="mb-0"><code>Q = 0.5 × WSM + 0.5 × WPM</code></p>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="bg-light-blue">
                                        <tr>
                                            <th class="text-center font-weight-bold">Laporan</th>
                                            <th class="text-center font-weight-bold">WSM</th>
                                            <th class="text-center font-weight-bold">WPM</th>
                                            <th class="text-center font-weight-bold">Q = 0.5×WSM + 0.5×WPM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ranked as $r)
                                            @php
                                                $normalizedTK =
                                                    $r['tingkat_kerusakan'] /
                                                    max(array_column($ranked, 'tingkat_kerusakan'));
                                                $normalizedFD =
                                                    $r['frekuensi_digunakan'] /
                                                    max(array_column($ranked, 'frekuensi_digunakan'));
                                                $normalizedD = $r['dampak'] / max(array_column($ranked, 'dampak'));
                                                $normalizedEB =
                                                    min(array_column($ranked, 'estimasi_biaya')) / $r['estimasi_biaya'];
                                                $normalizedPB =
                                                    $r['potensi_bahaya'] / max(array_column($ranked, 'potensi_bahaya'));

                                                $wsm =
                                                    $normalizedTK * 0.3 +
                                                    $normalizedFD * 0.1 +
                                                    $normalizedD * 0.05 +
                                                    $normalizedEB * 0.35 +
                                                    $normalizedPB * 0.2;
                                                $wpm =
                                                    pow($normalizedTK, 0.3) *
                                                    pow($normalizedFD, 0.1) *
                                                    pow($normalizedD, 0.05) *
                                                    pow($normalizedEB, 0.35) *
                                                    pow($normalizedPB, 0.2);
                                                $q = 0.5 * $wsm + 0.5 * $wpm;
                                            @endphp
                                            <tr>
                                                <td>{{ Str::limit($r['deskripsi'], 30) }}</td>
                                                <td class="text-center">{{ number_format($wsm, 4) }}</td>
                                                <td class="text-center">{{ number_format($wpm, 4) }}</td>
                                                <td class="text-center font-weight-bold text-danger">
                                                    {{ number_format($q, 4) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Step 7: Final Ranking -->
                        <div class="step">
                            <div class="d-flex align-items-center mb-2 mb-md-3">
                                <span class="badge badge-warning mr-2"
                                    style="font-size: 1rem; padding: 0.5rem 0.75rem;">7</span>
                                <h5 class="m-0 font-weight-bold text-dark">Hasil Perankingan</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="bg-light-blue">
                                        <tr>
                                            <th class="text-center font-weight-bold">Rank</th>
                                            <th class="text-center font-weight-bold">Deskripsi Laporan</th>
                                            <th class="text-center font-weight-bold">Nilai WASPAS (Q)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ranked as $r)
                                            <tr>
                                                <td class="text-center font-weight-bold">#{{ $r['rank'] }}</td>
                                                <td>{{ Str::limit($r['deskripsi'], 50) }}</td>
                                                <td class="text-center font-weight-bold text-danger">
                                                    {{ number_format($r['Q'], 4) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Existing Modal -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="false"
        aria-hidden="true"></div>
@endsection

@push('styles')
    <style>
        .priority-card {
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            border-top: none;
            border-right: none;
            border-bottom: none;
        }

        .priority-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .rank-display {
            background-color: #f8f9fa;
            border-radius: 6px;
        }

        .rank-number {
            font-size: 1.5rem;
            color: #343a40;
        }

        .badge {
            font-size: 0.85rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            white-space: nowrap;
        }

        #priority-container {
            padding: 0 5px;
        }

        .card-text {
            font-size: 1rem;
            color: #495057;
        }

        /* Status badge colors */
        .badge-secondary {
            background-color: #6c757d;
        }

        .badge-primary {
            background-color: #007bff;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .badge-success {
            background-color: #28a745;
        }

        /* Responsive button sizing */
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .btn-md-lg {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }

        /* Make badges responsive */
        .badge-pill {
            font-size: 0.8rem !important;
            padding: 0.4rem 0.8rem !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #01bb1d 0%, #66ff00 100%);
        }

        .bg-light-blue {
            background-color: #e3f2fd;
        }

        .calculation-steps .step {
            border-left: 4px solid #28a745;
            padding-left: 15px;
            margin-bottom: 20px;
        }

        .table {
            font-size: 0.85rem;
        }

        .table th {
            vertical-align: middle;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .shadow-lg {
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
        }

        .modal-content {
            border-radius: 0.5rem;
        }

        .alert-info {
            background-color: #e7f8ff;
            border-color: #b8e7ff;
            color: #006c9e;
        }

        @media (min-width: 768px) {
            .btn-md-lg {
                padding: 0.75rem 1.5rem;
                font-size: 1.25rem;
            }

            .badge-pill {
                font-size: 0.9rem !important;
                padding: 0.5rem 1rem !important;
            }

            #priority-container {
                padding: 0 10px;
            }

            .calculation-steps .step {
                margin-bottom: 30px;
            }

            .table {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 767px) {
            .card-header {
                padding: 1rem;
            }

            .priority-card {
                margin-bottom: 1.5rem;
            }

            .rank-display {
                flex-direction: row !important;
                width: 100% !important;
                justify-content: flex-start !important;
                margin-bottom: 1rem !important;
            }

            .rank-number {
                margin-right: 1rem;
            }

            .priority-indicator {
                margin-left: 1rem;
            }

            .badge {
                margin-right: 0.5rem !important;
                margin-bottom: 0.5rem !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        // Array of colors for cards
        const cardColors = [
            'linear-gradient(105deg, #faf8f5 0%, #f6ae43 110%)',
            'linear-gradient(105deg, #faf8f5 0%, #f6ae43e6 110%)',
            'linear-gradient(105deg, #faf8f5 0%, #f6ae43cb 110%)',
            'linear-gradient(105deg, #faf8f5 0%, #f6ae43b7 110%)',
            'linear-gradient(105deg, #faf8f5 0%, #f6ae43a0 110%)',
            'linear-gradient(105deg, #faf8f5 0%, #f6ae438a 110%)',
            'linear-gradient(105deg, #faf8f5 0%, #f6ae436c 110%)',
            'linear-gradient(105deg, #faf8f5 0%, #f6ae435d 110%)',
            'linear-gradient(105deg, #faf8f5 0%, #f6ae4348 110%)',
            'linear-gradient(105deg, #faf8f5 0%, #f6ae432f 110%)'
        ];

        $(document).ready(function() {
            // Apply different background to each card
            $('.priority-card').each(function(index) {
                // Use modulo to cycle through colors if there are more cards than colors
                const colorIndex = index % cardColors.length;
                $(this).css('background', cardColors[colorIndex]);

                // Add hover effect that darkens the card slightly
                $(this).hover(
                    function() {
                        $(this).css('background',
                            cardColors[colorIndex].replace('135deg', '145deg') +
                            ' !important');
                    },
                    function() {
                        $(this).css('background',
                            cardColors[colorIndex] + ' !important');
                    }
                );
            });

            // Search functionality
            $('#search').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('.priority-card').each(function() {
                    const cardText = $(this).text().toLowerCase();
                    if (cardText.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
@endpush
