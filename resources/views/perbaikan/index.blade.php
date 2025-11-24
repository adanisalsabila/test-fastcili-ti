@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'perbaikan_teknisi',
])

@section('content')
    <div class="content">
        <h3>Daftar Perbaikan</h3>
        <div class="card p-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-row-bordered" id="table_perbaikan">
                        <thead>
                            <tr>
                                <th>No</th>
                                @if (auth()->user()->id_level === 3)
                                    <th scope="col">Foto Kerusakan</th>
                                @endif
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Teknisi</th>
                                <th scope="col">Tenggat</th>
                                <th scope="col">Status</th>
                                <th scope="col">Catatan Teknisi</th>
                                <th scope="col">Dokumentasi Perbaikan</th>
                                @if (auth()->user()->id_level === 3)
                                    <th scope="col">Catatan Sarpras</th>
                                @endif
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporan_kerusakan as $index => $laporan)
                                <tr>
                                    <td></td>
                                    @if (auth()->user()->id_level == 3)
                                        {{-- Foto Kerusakan --}}
                                        <td>
                                            <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $laporan->laporan->foto_kerusakan) }}"
                                                onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';"
                                                alt="Foto Kerusakan" height="65"
                                                onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';">
                                        </td>
                                    @endif
                                    {{-- Deskripsi --}}
                                    <td>{{ $laporan->laporan->deskripsi ?? '-' }}</td>

                                    {{-- Nama Teknisi --}}
                                    <td>{{ $laporan->user->nama ?? '-' }}</td>

                                    {{-- Tenggat Laporan --}}
                                    <td>
                                        {{ $laporan->tenggat ? $laporan->tenggat->translatedFormat('l, d F Y') : '-' }}
                                    </td>

                                    <td>

                                        <span
                                            class="badge badge-pill py-2
                                            @if ($laporan->status_perbaikan == 'Selesai Dikerjakan') badge-success
                                            @elseif ($laporan->status_perbaikan == 'Ditolak')
                                                badge-danger
                                            @else
                                                badge-warning @endif">
                                            @if ($laporan->laporan->id_status == 4)
                                                {{ $laporan->laporan->status->nama_status }}
                                            @else
                                                {{ $laporan->status_perbaikan }}
                                            @endif
                                        </span>
                                    </td>

                                    {{-- Catatan Teknisi --}}
                                    <td>{{ Str::limit($laporan->catatan_teknisi, 30) ?? '-' }}</td>

                                    {{-- Dokumentasi --}}
                                    <td>
                                        @if ($laporan->dokumentasi)
                                            <img src="{{ asset('storage/uploads/dokumentasi/' . $laporan->dokumentasi) }}"
                                                onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';"
                                                alt="Dokumentasi" height="65">
                                        @else
                                            <span class="text-danger">(Belum ada dokumentasi)</span>
                                        @endif
                                    </td>

                                    @if (auth()->user()->id_level == 3)
                                        {{-- Catatan Sarpras --}}
                                        <td>{{ Str::limit($laporan->komentar_sarpras, 30) ?? '-' }}</td>
                                    @endif

                                    {{-- Tombol Aksi --}}
                                    <td>
                                        <div class="d-flex">
                                            @php
                                                $isLate = \Carbon\Carbon::now()->greaterThan($laporan->tenggat);
                                                $isEditable = $laporan->status_perbaikan != 'Selesai Dikerjakan';
                                                $isWaiting = $laporan->status_perbaikan == 'Selesai';
                                                $isRejected = !is_null($laporan->komentar_sarpras);
                                                $isReported = !is_null($laporan->dokumentasi);

                                                $laporanUrl = url('/perbaikan/edit/' . $laporan->id_penugasan);
                                                $detailUrl = url('/perbaikan/detail/' . $laporan->id_penugasan);
                                            @endphp
                                            @if (auth()->user()->id_level == 3)
                                                @if ($isWaiting && $isEditable)
                                                    <button class="btn btn-sm btn-warning mr-2" disabled>
                                                        Menunggu Verifikasi
                                                    </button>
                                                @elseif ($isEditable && !$isLate)
                                                    <button onclick="modalAction('{{ $laporanUrl }}')"
                                                        class="btn btn-sm btn-danger mr-2">
                                                        {{ $isRejected ? 'Edit Laporan' : 'Laporkan' }}
                                                    </button>
                                                @elseif ($isLate && $isEditable)
                                                    <button class="btn btn-sm btn-secondary mr-2" disabled>
                                                        Tenggat Terlewat
                                                    </button>
                                                @endif
                                            @elseif (auth()->user()->id_level == 1 || auth()->user()->id_level == 2)
                                                <button onclick="modalAction('{{ $detailUrl }}')"
                                                    class="btn btn-sm btn-info">
                                                    Detail
                                                </button>
                                            @endif

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
        var dataLaporan;

        $(document).on('submit', '.form-delete', function(e) {
            e.preventDefault(); // Cegah submit form langsung
            let form = this;
            let url = $(this).data('url');

            Swal.fire({
                title: 'Apakah Anda yakin ingin menghapus data ini?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
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
        const user = {
            id_level: {{ auth()->user()->id_level }} // Data user di-pass dari backend
        };

        $(document).ready(function() {
            // Common configuration options
            var commonConfig = {
                language: {
                    emptyTable: "<i class='fas fa-info-circle'></i> Tidak ada data perbaikan yang tersedia",
                    zeroRecords: "<i class='fas fa-info-circle'></i> Tidak ada data perbaikan seperti keyword yang ingin dicari"
                },
                rowCallback: function(row, data, index) {
                    // Ganti isi kolom "No" (kolom ke-0)
                    var info = this.api().page.info();
                    var page = info.page;
                    var length = info.length;
                    $('td:eq(0)', row).html(index + 1 + page * length);
                }
            };

            // Configuration for teknisi
            if (user.id_level === 3) {
                var datalaporan = $('#table_perbaikan').DataTable({
                    ...commonConfig,
                    columnDefs: [{
                        targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                        className: 'text-center',
                    }, {
                        targets: [0, 1, 2, 5, 6, 7, 8, 9],
                        orderable: false,
                        searchable: true,
                    }, {
                        targets: [3, 4],
                        searchable: true,
                    }]
                });
            }
            // Configuration for other users
            else {
                var datalaporan = $('#table_perbaikan').DataTable({
                    ...commonConfig,
                    columnDefs: [{
                        targets: [0, 1, 2, 3, 4, 5, 6, 7],
                        className: 'text-center',
                    }, {
                        targets: [0, 1, 4, 5, 6, 7],
                        orderable: false,
                        searchable: true,
                    }, {
                        targets: [2, 3],
                        searchable: true,
                    }]
                });
            }
        });
    </script>
@endpush
