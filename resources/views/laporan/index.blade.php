@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'lapor_kerusakan',
])

@section('content')
    <div class="content">
        <h3>Laporan Kerusakan</h3>
        @if (auth()->user()->id_level != 2)
            <div class="card p-4">
                <div class="card-header">
                    <h3 class="mb-0">Form Laporan Kerusakan</h3>
                    <div class="card-body">
                        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Kiri: Pilih Gedung & Ruangan -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="id_gedung">Pilih Gedung</label>
                                        <select name="id_gedung" id="id_gedung" class="form-control" required>
                                            <option value="">-- Pilih Gedung --</option>
                                            @foreach ($gedung as $g)
                                                <option value="{{ $g->id_gedung }}">{{ $g->nama_gedung }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group" id="ruangan-group" style="display: none;">
                                        <label for="id_ruangan">Pilih Ruangan</label>
                                        <select name="id_ruangan" id="id_ruangan" class="form-control" required>
                                            <option value="">-- Pilih Ruangan --</option>
                                        </select>
                                    </div>

                                    <!-- Form Laporan Baru (muncul saat klik 'Bukan salah satu') -->
                                    <div id="form-laporan-baru" style="display: none;" class="mt-4">
                                        <div class="form-group">
                                            <label for="id_fasilitas">Fasilitas</label>
                                            <select name="id_fasilitas" id="id_fasilitas" class="form-control"
                                                required></select>
                                        </div>

                                        <div class="form-group">
                                            <label for="deskripsi">Deskripsi (*Tambahkan lokasi spesifik jika
                                                diperlukan)</label>
                                            <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="jumlah_kerusakan">Jumlah fasilitas yang rusak</label>
                                            <input type="number" name="jumlah_kerusakan" class="form-control" required>
                                            <small class="text-muted d-block mt-1">*Jumlah fasiltas yang rusak tidak bisa lebih
                                                dari jumlah fasilitas</small>
                                        </div>

                                        <div class="form-group">
                                            <!-- Custom File Input -->
                                            <label for="foto_kerusakan" class="d-block mb-2">Foto Kerusakan</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="foto_kerusakan"
                                                    id="foto_kerusakan" accept="image/*" required>
                                                <label class="custom-file-label bg-warning text-dark text-center w-100"
                                                    for="foto_kerusakan" id="file-label">
                                                    <i class="fas fa-upload mr-2"></i>Pilih foto kerusakan
                                                </label>
                                            </div>
                                            <small class="text-muted d-block mt-1">Format: JPG, PNG, JPEG (Maks. 2MB)</small>
                                            <small class="text-danger" id="error-foto_kerusakan"></small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kanan: Card Laporan Sudah Ada -->
                                <div class="col-md-8">
                                    <div id="laporan-terlapor-container" style="display: none;" class="mt-2">
                                        <label>Laporan yang Sudah Ada:</label>
                                        <div class="mb-3">
                                            <button type="button" id="bukan-laporan" class="btn btn-warning">
                                                Bukan salah satu laporan di bawah?
                                            </button>
                                        </div>
                                        <div id="laporan-terlapor-list" class="row"></div>

                                    </div>

                                    <!-- Jika memilih dukung laporan -->
                                    <div id="form-dukungan" style="display: none;" class="mt-4">
                                        <input type="hidden" name="dukungan_laporan" id="dukungan_laporan">
                                        <div class="form-group">
                                            <label>Tambahkan deskripsi (opsional)</label>
                                            <textarea name="tambahan_deskripsi" class="form-control" rows="3"></textarea>
                                            <label for="tambahan_deskripsi" class="text-muted small">*Kosongkan jika ingin deskripsi sama dengan deskripsi dari laporan yang didukung</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <button type="submit" id="btn-submit" class="btn btn-success" disabled>Kirim
                                        Laporan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        @if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2)
            <div class="card p-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Daftar Laporan Kerusakan</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-row-bordered" id="table_laporan">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Foto Kerusakan</th>
                                    <th width="10%">Fasilitas</th>
                                    <th width="15%">Tempat</th>
                                    <th width="10%">Pelapor</th>
                                    <th width="6%">Jumlah kerusakan</th>
                                    <th width="15%">Deskripsi</th>
                                    <th width="7%">Status</th>
                                    <th width="7%">Tanggal Lapor</th>
                                    <th width="6%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporan as $i => $item)
                                    <tr>
                                        <td></td>
                                        <td>
                                            <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $item->laporan->foto_kerusakan) }}"
                                                onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';"
                                                alt="Foto Kerusakan" class="card-img-top img-fluid"
                                                style="height: 120px; object-fit: cover;">
                                        </td>
                                        <td>{{ $item->laporan->fasilitas->nama_fasilitas }}</td>
                                        <td>{{ $item->laporan->fasilitas->ruangan->gedung->nama_gedung }} -
                                            {{ $item->laporan->fasilitas->ruangan->nama_ruangan }}</td>
                                        <td>{{ $item->user->nama }}</td>
                                        <td>{{ $item->laporan->jumlah_kerusakan }}</td>
                                        <td>{{ $item->deskripsi_tambahan ?? '-' }}</td>
                                        <td>

                                            @php
                                                $statusColor = match ($item->laporan->status->id_status) {
                                                    1 => 'bg-warning',
                                                    2 => 'bg-primary text-white',
                                                    3 => 'bg-secondary text-white',
                                                    4 => 'bg-success text-white',
                                                    default => 'bg-dark',
                                                };
                                            @endphp
                                            <p class=" p-2 badge {{ $statusColor }}">
                                                {{ $item->laporan->status->nama_status }}</p>
                                        </td>
                                        <td>{{ $item->laporan->tanggal_lapor->translatedFormat('l, d F Y') }}</td>


                                        <td>
                                            <button class="btn btn-danger btn-sm btn-delete"
                                                data-id="{{ $item->id }}" data-nama="{{ $item->user->nama }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card shadow-lg border-0 rounded-4 mt-5">
                <div class="card-header bg-warning text-white rounded-top-4">
                    <h3 class="mb-3">Riwayat Laporan</h3>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-striped table-bordered" id="table_pelapor">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col">Nama Fasilitas</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Tanggal Lapor</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporanAuth as $lapor => $l)
                                    <tr>
                                        <td></td>
                                        <td>{{ $l->laporan->fasilitas->nama_fasilitas }}</td>
                                        <td>{{ $l->deskripsi_tambahan }}</td>
                                        <td>{{ $l->created_at->translatedFormat('l, d F Y') }}</td>
                                        <td>
                                            @php
                                                $statusColor = match ($l->laporan->id_status) {
                                                    1 => 'bg-secondary text-white',
                                                    2 => 'bg-info text-white',
                                                    3 => 'bg-danger text-white',
                                                    4 => 'bg-success text-white',
                                                    default => 'bg-dark',
                                                };
                                            @endphp
                                            <span class="badge {{ $statusColor }} px-3 py-2">
                                                {{ $l->laporan->status->nama_status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button
                                                onclick="modalAction('{{ route('pelapor.detail', ['id' => $l->id]) }}')"
                                                class="btn btn-sm btn-info text-white">
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
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
        // Handle file input change
        $('#foto_kerusakan').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            if (fileName) {
                $('#file-label').html('<i class="fas fa-file-image mr-2"></i>' + fileName);
            } else {
                $('#file-label').html('<i class="fas fa-upload mr-2"></i>Pilih Foto Kerusakan');
            }
        });
    </script>
    <script>
        var currentUserId = {{ auth()->id() }};
        $(document).ready(function() {
            // Fungsi: Menampilkan form dukungan laporan
            function showFormDukungan() {
                $('#id_fasilitas').prop('required', false).prop('disabled', true).closest('.form-group').hide();
                $('textarea[name="deskripsi"]').prop('required', false).prop('disabled', true).closest('.form-group').hide();
                $('input[name="foto_kerusakan"]').prop('required', false).prop('disabled', true).closest('.form-group').hide();
                $('input[name="jumlah_kerusakan"]').prop('required', false).prop('disabled', true).closest('.form-group').hide(); // <== ini penting!
            }

            // Fungsi: Menampilkan form laporan baru
            function showFormBaru() {
                $('#id_fasilitas').prop('disabled', false).prop('required', true).closest('.form-group').show();
                $('textarea[name="deskripsi"]').prop('disabled', false).prop('required', true).closest('.form-group').show();
                $('input[name="foto_kerusakan"]').prop('disabled', false).prop('required', true).closest('.form-group').show();
                $('input[name="jumlah_kerusakan"]').prop('disabled', false).prop('required', true).closest('.form-group').show(); // <== ini penting!
            }

            // Event Submit Form
            $('form').on('submit', function(e) {
                e.preventDefault();

                $('#form-laporan-baru').find('input, select, textarea').each(function() {
                    if (!$(this).is(':visible')) {
                        $(this).prop('disabled', true).prop('required', false);
                    }
                });

                const form = this;
                const formData = new FormData(form);

                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ url('/lapor_kerusakan') }}";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr) {
                        let msg = "Terjadi kesalahan";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: msg,
                        });
                    }
                });
            });

            // Dropdown: Gedung → Ruangan
            $('#id_gedung').on('change', function() {
                const idGedung = $(this).val();
                $('#ruangan-group').show();
                $('#id_ruangan').html('<option value="">Memuat...</option>');

                var url = '{{ url('/get-ruangan') }}/' + idGedung;
                $.get(url, function(data) {
                    let options = '<option value="">-- Pilih Ruangan --</option>';
                    data.forEach(r => options +=
                        `<option value="${r.id_ruangan}">${r.nama_ruangan}</option>`);
                    $('#id_ruangan').html(options);
                });

                $('#laporan-terlapor-container, #form-dukungan, #form-laporan-baru').hide();
            });

            // Dropdown: Ruangan → Laporan & Fasilitas
            $('#id_ruangan').on('change', function() {
                const idRuangan = $(this).val();
                $('#laporan-terlapor-list').empty();
                $('#laporan-terlapor-container, #form-dukungan, #form-laporan-baru').hide();

                var url = '{{ url('/get-fasilitas-terlapor') }}/' + idRuangan;
                $.get(url, function(data) {
                    const filteredData = data.filter(f => f.id_user !== currentUserId);

                    if (filteredData.length > 0) {
                        let html = '';
                        filteredData.forEach(f => {
                            html += `
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <img src="{{ url('/storage/uploads/laporan_kerusakan/${f.foto_kerusakan}') }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">${f.nama_fasilitas}</h5>
                                    <p class="card-text">${f.deskripsi}</p>
                                    <button type="button" class="btn btn-outline-primary pilih-laporan" data-id="${f.id_laporan}">Laporkan Ini</button>
                                </div>
                            </div>
                        </div>`;
                        });
                        $('#laporan-terlapor-list').html(html);
                        $('#laporan-terlapor-container').show();
                        $('#form-laporan-baru').hide();
                    } else {
                        $('#laporan-terlapor-container').hide();
                        $('#form-laporan-baru').show();
                    }
                });

                var url = '{{ url('/get-fasilitas-belum-lapor') }}/' + idRuangan;
                $.get(url, function(data) {
                    let options = '<option value="">-- Pilih Fasilitas --</option>';
                    data.forEach(f => options +=
                        `<option value="${f.id_fasilitas}">${f.nama_fasilitas}</option>`);
                    $('#id_fasilitas').html(options);
                });
            });

            // Klik tombol: Dukung laporan sudah ada
            $(document).on('click', '.pilih-laporan', function() {
                const selectedId = $(this).data('id');
                $('.pilih-laporan').removeClass('btn-primary').addClass('btn-outline-primary');
                $(this).removeClass('btn-outline-primary').addClass('btn-primary');

                $('#dukungan_laporan').val(selectedId);
                $('#form-dukungan').show();
                $('#form-laporan-baru').hide();

                showFormDukungan();
                updateSubmitButtonState();
            });

            // Klik tombol: Buat laporan baru
            $('#bukan-laporan').on('click', function() {
                $('#dukungan_laporan').val('');
                $('#form-dukungan').hide();
                $('#form-laporan-baru').show();

                showFormBaru();
            });

            // Validasi tombol submit
            function updateSubmitButtonState() {
                const isDukung = $('#dukungan_laporan').val() !== '';
                const isLaporanBaru = $('#form-laporan-baru').is(':visible');

                let enable = false;

                if (isDukung) {
                    // const tambahanDeskripsi = $('textarea[name="tambahan_deskripsi"]').val().trim();
                    // enable = tambahanDeskripsi.length > 0;
                    enable = true;
                }

                if (isLaporanBaru) {
                    const fasilitas = $('#id_fasilitas').val();
                    const deskripsi = $('textarea[name="deskripsi"]').val().trim();
                    const foto = $('input[name="foto_kerusakan"]')[0].files.length > 0;

                    enable = fasilitas !== '' && deskripsi.length > 0 && foto;
                }

                $('#btn-submit').prop('disabled', !enable);
            }

            // Pantau input
            $(document).on('input change',
                '#id_fasilitas, textarea[name="deskripsi"], input[name="foto_kerusakan"], textarea[name="tambahan_deskripsi"]',
                updateSubmitButtonState);

            // Reset tombol saat pilih gedung/ruangan berubah
            $('#id_gedung, #id_ruangan').on('change', function() {
                $('#btn-submit').prop('disabled', true);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#table_laporan').DataTable({
                columnDefs: [{
                    targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                    className: 'text-center',
                }, {
                    targets: [0, 1, 4, 9],
                    orderable: false,
                    searchable: false,
                }],
                language: {
                    emptyTable: "<i class='fas fa-info-circle'></i> Tidak ada data laporan kerusakan yang tersedia",
                    zeroRecords: "<i class='fas fa-info-circle'></i> Tidak ada data laporan kerusakan seperti keyword yang ingin dicari"
                },
                rowCallback: function(row, data, index) {
                    // Ganti isi kolom "No" (kolom ke-0)
                    var info = this.api().page.info();
                    var page = info.page;
                    var length = info.length;
                    $('td:eq(0)', row).html(index + 1 + page * length);
                }
            });
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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let nama = $(this).data('nama');

            Swal.fire({
                title: 'Yakin Hapus Data?',
                html: `<strong>Data pelapor atas nama <u>${nama}</u></strong> akan dihapus.<br><br>
                <span class="text-danger">Tindakan ini berdampak besar! Jika ini adalah satu-satunya pelapor untuk laporan terkait, maka laporan utama juga akan ikut terhapus.</span>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('lapor_kerusakan/delete') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Berhasil!', response.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Gagal', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Terjadi kesalahan saat menghapus.', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endpush
