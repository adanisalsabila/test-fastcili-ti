@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
        <div class="card p-4 shadow-sm">
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
@endsection


@push('scripts')
    <script>
         var currentUserId = {{ auth()->id() }};
        $(document).ready(function() {
            // Fungsi: Menampilkan form dukungan laporan
            function showFormDukungan() {
                $('#id_fasilitas').prop('required', false).prop('disabled', true).closest('.form-group').hide();
                $('textarea[name="deskripsi"]').prop('required', false).prop('disabled', true).closest(
                    '.form-group').hide();
                $('input[name="foto_kerusakan"]').prop('required', false).prop('disabled', true).closest(
                    '.form-group').hide();
            }

            // Fungsi: Menampilkan form laporan baru
            function showFormBaru() {
                $('#id_fasilitas').prop('disabled', false).prop('required', true).closest('.form-group').show();
                $('textarea[name="deskripsi"]').prop('disabled', false).prop('required', true).closest(
                    '.form-group').show();
                $('input[name="foto_kerusakan"]').prop('disabled', false).prop('required', true).closest(
                    '.form-group').show();
            }

            // Event Submit Form
            $('form').on('submit', function(e) {
                e.preventDefault();

                $('#form-laporan-baru input, #form-laporan-baru select, #form-laporan-baru textarea').each(
                    function() {
                        if ($(this).is(':hidden')) {
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
@endpush
