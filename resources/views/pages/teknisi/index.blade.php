@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
        {{-- Kartu Statistik --}}
        <div class="row">

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-paper"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Total Penugasan</p>
                                    <p class="card-title">{{ $jmlPenugasan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-book-bookmark"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Laporan Sedang Dikerjakan</p>
                                    <p class="card-title">{{ $laporanDikerjakan }}</p>
                                    <p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-bullet-list-67"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Perbaikan Ditolak</p>
                                    <p class="card-title">{{ $laporanDitolak }}
                                    <p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-single-copy-04"></i>
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Laporan Selesai Dikerjakan</p>
                                    <p class="card-title">{{ $laporanSelesaiDikerjakan }}
                                    <p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Area Konten Utama --}}
        {{-- Sambutan --}}
        <div class="jumbotron bg-white shadow rounded p-5 mb-4">
            <h2 class="display-5 fw-bold mb-3">Selamat Datang, {{ auth()->user()->nama }}!</h2>
            <p class="lead mt-3">Pantau dan tangani laporan kerusakan fasilitas kampus secara efisien. Silakan cek
                daftar tugas yang telah ditugaskan kepada Anda, dan pastikan setiap perbaikan diselesaikan tepat
                waktu demi kenyamanan bersama.</p>
            <a href="{{ url('/perbaikan') }}" class="btn btn-success">
                Lihat Daftar Perbaikan
            </a>
            <hr class="my-4">
            <p>🔧 Jangan lupa untuk memperbarui status pekerjaan setelah perbaikan selesai dilakukan.</p>
        </div>
        @if ($penugasan && $penugasan->status_perbaikan != "Selesai Dikerjakan")
            <div class="card border-0 mb-4">
                <div class="card-header bg-light pb-3">
                    <h6 class="mb-0 font-weight-bold">
                        <i class="fas fa-info-circle mr-2 text-primary"></i>Penugasan Perbaikan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="text-center">
                                @php
                                    $isLate = \Carbon\Carbon::now()->greaterThan($penugasan->tenggat);
                                    $isEditable = $penugasan->status_perbaikan != 'Selesai Dikerjakan';
                                    $isRejected = !is_null($penugasan->komentar_sarpras);
                                    $isReported = !is_null($penugasan->dokumentasi);
                                    $laporanUrl = url('/perbaikan/edit/' . $penugasan->id_penugasan);
                                    $detailUrl = url('/perbaikan/detail/' . $penugasan->id_penugasan);
                                @endphp
                                <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $penugasan->laporan->foto_kerusakan) }}"
                                    alt="Foto Kerusakan" class="img-fluid rounded shadow-sm border"
                                    style="max-height: 250px; width: auto;"
                                    onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';">
                                @if ($penugasan && $penugasan->status_perbaikan == 'Selesai')
                                    <button
                                        class="btn btn-warning btn-block mt-3 py-2" style="font-size: 16px;" disabled>
                                        <i class="fa-solid fa-spinner"></i> Menunggu Verifikasi
                                    </button>
                                @else 
                                    <button onclick="modalAction('{{ $laporanUrl }}')"
                                        class="btn btn-primary btn-block mt-3 py-2" style="font-size: 16px;">
                                        <i class="fas fa-tools mr-2"></i>{{ $isRejected ? 'Edit Laporan' : 'Laporkan' }}
                                    </button>
                                @endif
                            </div>
                        </div>


                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Fasilitas</label>
                                <p class="font-weight-bold">
                                    <i class="fas fa-tools mr-2 text-warning"></i>
                                    {{ $penugasan->laporan->fasilitas->nama_fasilitas }}
                                </p>

                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small mb-1">Deskripsi Kerusakan</label>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-1">{{ $penugasan->laporan->deskripsi }}</p>

                                    @foreach ($penugasan->laporan->pelaporLaporan as $pelapor)
                                        @if ($pelapor->deskripsi_tambahan)
                                            <hr>
                                            <p class="mb-1 text-muted small">Tambahan dari pelapor (Nama :
                                                {{ $pelapor->user->nama }})</p>
                                            <p class="mb-1">{{ $pelapor->deskripsi_tambahan }}</p>
                                        @endif
                                    @endforeach
                                </div>
                            </div>


                            <!-- Komentar Sarpras Section -->
                            <div class="mb-3">
                                <label class="text-muted small mb-1">Komentar Sarpras</label>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0">
                                        {{ $penugasan->komentar_sarpras ?? '-' }}</p>

                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Tanggal Lapor</label>
                                    <p class="font-weight-bold">
                                        <i class="fas fa-calendar-alt mr-2 text-info"></i>
                                        {{ $penugasan->laporan->tanggal_lapor
                                            ? \Carbon\Carbon::parse($penugasan->laporan->tanggal_lapor)->locale('id')->translatedFormat('l, d F Y')
                                            : '-' }}

                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Tenggat Perbaikan</label>
                                    <p class="font-weight-bold">
                                        <i class="fas fa-calendar-alt mr-2 text-info"></i>

                                        {{ $penugasan->tenggat
                                            ? \Carbon\Carbon::parse($penugasan->tenggat)->locale('id')->translatedFormat('l, d F Y')
                                            : '-' }}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Ruangan</label>
                                    <p class="font-weight-bold">
                                        <i class="fas fa-door-open mr-2 text-info"></i>
                                        {{ $penugasan->laporan->fasilitas->ruangan->nama_ruangan }}


                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small mb-1">Gedung</label>
                                    <p class="font-weight-bold">
                                        <i class="fas fa-building mr-2 text-info"></i>
                                        {{ $penugasan->laporan->fasilitas->ruangan->gedung->nama_gedung }}


                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Grafik --}}
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-light pb-0">
                        <h5 class="card-title text-dark">Penugasan Per Bulan</h5>
                        <p class="card-category">Perbaikan per bulan</p>
                    </div>
                    <div class="card-body">
                        <canvas id="perbaikanPerBulan"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-chart shadow">
                    <div class="card-header bg-light pb-0">
                        <h5 class="card-title text-dark">Penugasan Per Gedung</h5>
                        <p class="card-category">Distribusi perbaikan antar gedung</p>
                    </div>
                    <div class="card-body">
                        <canvas id="penugasanGedungChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="false"
        aria-hidden="true"></div>
@endsection

@push('scripts')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        const perbaikanPerBulanData = {!! json_encode($perbaikanPerBulan) !!};
        const penugasanPerGedungData = {!! json_encode($penugasanPerGedung) !!};

        const ctx1 = document.getElementById('perbaikanPerBulan').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: Object.keys(perbaikanPerBulanData),
                datasets: [{
                    label: 'Jumlah Perbaikan',
                    data: Object.values(perbaikanPerBulanData),
                    borderColor: 'rgba(218, 165, 32, 1)',
                    backgroundColor: 'rgba(218, 165, 32, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(218, 165, 32, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(218, 165, 32, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            precision: 0
                        }
                    }
                }
            }
        });

        const ctx3 = document.getElementById('penugasanGedungChart').getContext('2d');
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: Object.keys(penugasanPerGedungData),
                datasets: [{
                    label: 'Jumlah Penugasan',
                    data: Object.values(penugasanPerGedungData),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(201, 203, 207, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(201, 203, 207, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
@endpush
