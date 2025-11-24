@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
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
                                    <p class="card-category">Total Laporan</p>
                                    <p class="card-title">{{ $jmlLaporan }}</p>
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
                                    <p class="card-category">Laporan Diajukan</p>
                                    <p class="card-title">{{ $laporanDiajukan }}
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
                                    <p class="card-category">Laporan Diproses</p>
                                    <p class="card-title">{{ $laporanDiproses }}
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
                                    <p class="card-category">Laporan Selesai</p>
                                    <p class="card-title">{{ $laporanSelesai }}
                                    <p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Laporan Masuk</h5>
                        <p class="card-category">Laporan per bulan</p>
                    </div>
                    <div class="card-body ">
                        <canvas id=laporanPerBulan width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Top 5 Prioritas Perbaikan</h5>
                        <p class="card-category">Berdasarkan hasil perhitungan SPK Metode WASPAS</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-row-bordered" id="table_admin">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Fasilitas</th>
                                        <th>Lokasi</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                        <th>Nilai</th>
                                        <th>Teknisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (collect($spkRank)->take(5) as $item)
                                        <tr>
                                            <td>{{ $item['rank'] ?? '-' }}</td>
                                            <td>{{ $item['fasilitas'] ?? '-' }}</td>
                                            <td>{{ $item['ruangan'] ?? '-' }} - {{ $item['gedung'] ?? '-' }}</td>
                                            <td>{{ $item['deskripsi'] ?? 'Tidak ada deskripsi' }}</td>
                                            <td>
                                                @php
                                                    $status = $item['status'] ?? 'Tidak diketahui';

                                                    $statusColor = match ($status) {
                                                        'Diproses' => 'bg-primary text-white',
                                                        'Diperbaiki' => 'bg-secondary text-white',
                                                        default => 'bg-primary text-white',
                                                    };
                                                @endphp
                                                <span class="badge p-2 {{ $statusColor }}">
                                                    {{ $status }}
                                                </span>
                                            </td>

                                            <td>{{ isset($item['Q']) ? number_format($item['Q'], 4) : '890' }}</td>
                                            <td>
                                                {{ $item['teknisi'] ?? 'Belum Ditugaskan' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card ">
                    <div class="card-header ">
                        <h5 class="card-title">Status Laporan</h5>
                        <p class="card-category">Laporan per status</p>
                    </div>
                    <div class="card-body ">
                        <canvas id="statusLaporanChart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-chart">
                    <div class="card-header">
                        <h5 class="card-title">Laporan per gedung</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="laporanGedungChart" width="400" height="115"></canvas>
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
        $(document).ready(function() {
            $('#table_admin').DataTable({
                columnDefs: [{
                    targets: [0, 1, 2, 3, 4, 5, 6],
                    className: 'text-center',
                    orderable: false,
                    searchable: false,
                }],
                language: {
                    emptyTable: "<i class='fas fa-info-circle'></i> Tidak ada data prioritas yang tersedia",
                    zeroRecords: "<i class='fas fa-info-circle'></i> Tidak ada data prioritas seperti keyword yang ingin dicari"
                },
                rowCallback: function(row, data, index) {
                    // Ganti isi kolom "No" (kolom ke-0)
                    var info = this.api().page.info();
                    var page = info.page;
                    var length = info.length;
                    $('td:eq(0)', row).html(index + 1 + page * length);
                }
            });

            const laporanPerBulanData = {!! json_encode($laporanPerBulan) !!};

            const ctx1 = document.getElementById('laporanPerBulan').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: Object.keys(laporanPerBulanData),
                    datasets: [{
                        label: 'Jumlah Perbaikan',
                        data: Object.values(laporanPerBulanData),
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

            //pie chart status laporan
            const ctx2 = document.getElementById('statusLaporanChart');
            new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($statusLaporan->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($statusLaporan->values()) !!},
                        backgroundColor: ['#ffc107', '#17a2b8', '#fd7e14', '#28a745', '#dc3545']
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: true
                        }
                    }
                }
            });

            //graik laporan per gedung
            const ctx3 = document.getElementById('laporanGedungChart');
            new Chart(ctx3, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($laporanPerGedung->keys()) !!},
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: {!! json_encode($laporanPerGedung->values()) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endpush
