@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
        {{-- Sambutan --}}
        <div class="jumbotron bg-white shadow rounded p-4  mb-4">
            <h2 class="display-5 fw-bold mb-3">Selamat Datang, {{ auth()->user()->nama }}!</h2>
            <p class="lead mt-3">Terima kasih telah berkontribusi menjaga fasilitas kampus. Anda dapat memantau status
                laporan kerusakan yang telah Anda buat, serta melihat perkembangan tindak lanjutnya.</p>
            <a href="{{ url('/lapor_kerusakan') }}" class="btn btn-primary">
                Lihat Laporan Saya
            </a>
            <hr class="mt-4">
            <p>📢 Jika ada fasilitas lain yang bermasalah, silakan buat laporan baru agar segera ditangani.</p>
        </div>
        <hr class="mb-4">

        <div class="container">
            <h3 class="mb-4 fw-bold ">Status Laporan Anda</h3>
            @foreach ($statusList as $status)
                {{-- @dd($status) --}}
                <div class="card shadow-lg border-0 rounded-4 mb-4 overflow-hidden">
                    <div class="row g-0">
                        <div
                            class="col-md-4 d-flex align-items-center justify-content-center p-3 bg-light position-relative">
                            <div class="image-overlay"></div>
                            <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $status->laporan->foto_kerusakan) }}"
                                onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';"
                                alt="Foto Kerusakan" class="img-fluid h-100 object-fit-cover">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0 fw-bold ">Laporan Kerusakan Fasilitas</h5>
                                    <span class="text-muted small mt-2"
                                        style="text-align: right">{{ $status->created_at->diffForHumans() }}</span>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-item">
                                            <span class="detail-label">Fasilitas</span>
                                            <span
                                                class="detail-value">{{ $status->laporan->fasilitas->nama_fasilitas }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <span class="detail-label">Tanggal Lapor</span>
                                            <span
                                                class="detail-value">{{ $status->created_at->translatedFormat('l, d F Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="detail-item">
                                            <span class="detail-label">Status</span>
                                            @php
                                                $statusColor = match ($status->laporan->id_status) {
                                                    1 => 'bg-warning text-white',
                                                    2 => 'bg-info text-white',
                                                    3 => 'bg-secondary text-white',
                                                    4 => 'bg-success text-white',
                                                    default => 'bg-primary text-white',
                                                };
                                            @endphp
                                            <span class="detail-value">
                                                <span class="badge {{ $statusColor }} px-3 py-2 rounded-pill">
                                                    {{ $status->laporan->status->nama_status }}
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    @if ($status->laporan->id_status == 3 || $status->laporan->id_status == 4)
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <span class="detail-label">Teknisi</span>
                                                <span
                                                    class="detail-value">{{ $status->laporan->penugasan->last()?->user->nama ?? 'Belum Ditugaskan' }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <h6 class="fw-semibold text-muted mb-2">Deskripsi Laporan</h6>
                                    <div class="p-3 bg-light rounded-3">
                                        <p class="mb-0">{{ $status->deskripsi_tambahan }}</p>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4 gap-2 flex-wrap">
                                    <button onclick="modalAction('{{ route('pelapor.detail', ['id' => $status->id]) }}')"
                                        class="btn btn-sm btn-info text-white rounded-pill px-3 mx-2">
                                        <i class="fas fa-eye me-2"></i> Detail
                                    </button>
                                    @if ($status->laporan->id_status == 4)
                                        <button
                                            onclick="modalAction('{{ route('pelapor.rate', ['id' => $status->id_laporan]) }}')"
                                            class="btn btn-success btn-md rounded-pill px-3">
                                            <i class="fa fa-star me-2"></i>Beri Rating & Ulasan
                                        </button>
                                    @elseif ($status->laporan->id_status == 1)
                                        <form class="form-delete d-inline-block"
                                            action="{{ route('pelapor.delete', ['id' => $status->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">
                                                <i class="fa fa-trash me-2"></i> Batalkan Laporan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="false"
        aria-hidden="true"></div>
@endsection

@push('styles')
    <style>
        /* Card Styling */
        .card {
            transition: all 0.3s ease;
            border: none;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Image Styling */
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0));
            z-index: 1;
        }

        .object-fit-cover {
            object-fit: cover;
            width: 100%;
            height: 100%;
            min-height: 250px;
        }

        /* Detail Item Styling */
        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
        }

        .detail-value {
            font-size: 0.95rem;
            font-weight: 500;
            color: #212529;
        }

        /* Badge Styling */
        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Button Styling */
        .btn {
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-sm {
            padding: 0.4rem 0.9rem;
        }

        /* Jumbotron Styling */
        .jumbotron {
            background-color: #f8f9fa;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        /* Table Styling */
        .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #495057;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.25rem;
            }

            .detail-item {
                margin-bottom: 0.5rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <!-- Your existing scripts remain unchanged -->
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var dataLaporan;
        $(document).ready(function() {
            $('#table_pelapor').DataTable({
                columnDefs: [{
                    targets: [0, 1, 2, 5],
                    className: 'text-center',
                    orderable: false,
                    searchable: true,
                }, {
                    targets: [3, 4],
                    className: 'text-center',
                    orderable: true,
                    searchable: true,
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
        });

        $(document).on('submit', '.form-delete', function(e) {
            e.preventDefault();
            let form = this;
            let url = $(this).data('url');

            Swal.fire({
                title: 'Apakah Anda yakin ingin membatalkan laporan?',
                text: "Laporan yang telah dibatalkan tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: "success",
                                    title: "Berhasil!",
                                    text: response.messages,
                                });
                                location.reload();
                            } else {
                                alert('Gagal menghapus data.');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.msgField) {
                                let errors = xhr.responseJSON.msgField;
                                $.each(errors, function(field, messages) {
                                    $('#error-' + field).text(messages[0]);
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal!",
                                    text: response.messages,
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
