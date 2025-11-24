<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-0 shadow modal-scrollable">
        <div class="modal-header bg-gradient-success text-white">
            <h5 class="modal-title font-weight-bold">
                <i class="fas fa-history"></i>&nbsp;&nbsp;Detail Riwayat Perbaikan
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="mainModalClose">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body p-4">
            <div class="row">
                <div class="col-12" id="detailContainer">
                    <div class="card border-0 mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold">Detail Perbaikan</h6>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mr-2" id="toggleImageBtn">
                                    <i class="fas fa-image mr-1"></i>
                                    <span id="toggleImageText">Lihat Dokumentasi Perbaikan</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="toggleKerusakanBtn">
                                    <i class="fas fa-image mr-1"></i>
                                    <span id="toggleKerusakanText">Lihat Foto Kerusakan</span>
                                </button>
                            </div>
                        </div>
        <div class="modal-body text-center">
            <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $laporan->laporan->foto_kerusakan) }}"
                onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';" alt="Foto Kerusakan"
                style="max-width: 300px; height: auto;" class="img-fluid rounded mb-3">

            <table class="table table-sm table-bordered table-striped text-left">
                <tr>
                    <th class="text-right col-3">Fasilitas :</th>
                    <td class="col-9">{{ $laporan->laporan->fasilitas->nama_fasilitas }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Gedung :</th>
                    <td class="col-9">{{ $laporan->laporan->fasilitas->ruangan->gedung->nama_gedung }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Fasilitas :</th>
                    <td class="col-9">{{ $laporan->laporan->fasilitas->ruangan->nama_ruangan }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Deskripsi :</th>
                    <td class="col-9">
                        @if ($laporan->laporan && $laporan->laporan->laporanPelapor && $laporan->laporan->laporanPelapor->count())
                            <ul class="mb-0">
                                <li>{{ $laporan->laporan->deskripsi }}</li>
                                @foreach ($laporan->laporan->laporanPelapor as $pelapor)
                                    @if ($pelapor->id_laporan == $laporan->laporan->id)
                                        <li>{{ $pelapor->deskripsi_tambahan }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            {{ $laporan->laporan->deskripsi }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Lapor :</th>
                    <td class="col-9">
                        {{ $laporan->created_at->translatedFormat('l, d F Y') }}
                    </td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Selesai :</th>
                    <td class="col-9">
                        {{ $laporan->tanggal_selesai->translatedFormat('l, d F Y') ?? '-' }}
                    </td>
                </tr>
                <tr>
                    @php
                        $statusColor = match ($laporan->laporan->id_status) {
                            1 => 'bg-secondary',
                            2 => 'bg-primary',
                            3 => 'bg-info',
                            4 => 'bg-success text-white',
                            5 => 'bg-danger text-white',
                            default => 'bg-dark',
                        };
                    @endphp
                    <th class="text-right col-3">Status :</th>
                    <td class="col-9">
                        <span class="badge {{ $statusColor }}">{{ $laporan->laporan->status->nama_status }}</span>
                    </td>
                </tr>
                <tr>
                    <th class="text-right col-3">Teknisi :</th>
                    <td class="col-9">{{ $laporan->user->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Catatan Teknisi :</th>
                    <td class="col-9">{{ $laporan->catatan_teknisi ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Catatan Sarpras :</th>
                    <td class="col-9">{{ $laporan->komentar_sarpras ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Rating :</th>
                    <td class="col-9">
                        @php
                            $ratings = $laporan->laporan->laporanPelapor;
                            $avgRating = $ratings ? $ratings->avg('rating_pengguna') : null;
                        @endphp

                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 d-none" id="expandedImageContainer">
                                    <div class="card border-0 mb-4">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 font-weight-bold">Dokumentasi Perbaikan</h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="text-center">
                                                <img src="{{ asset('storage/uploads/dokumentasi/' . $laporan->dokumentasi) }}"
                                                    onerror="this.onerror=null;this.src='{{ asset('images/fasilitas-rusak.jpeg') }}';"
                                                    alt="Dokumentasi Perbaikan" 
                                                    class="img-fluid w-100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-none" id="expandedKerusakanContainer">
                                    <div class="card border-0 mb-4">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 font-weight-bold">Foto Kerusakan</h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="text-center">
                                                <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $laporan->laporan->foto_kerusakan) }}"
                                                     onerror="this.onerror=null;this.src='{{ asset('images/fasilitas-rusak.jpeg') }}';"
                                                     alt="Foto Kerusakan" 
                                                     class="img-fluid w-100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <table class="table table-sm table-bordered table-striped text-left">
                                        <tr>
                                            <th class="text-right col-3">Fasilitas :</th>
                                            <td class="col-9">{{ $laporan->laporan->fasilitas->nama_fasilitas }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-right col-3">Gedung :</th>
                                            <td class="col-9">{{ $laporan->laporan->fasilitas->ruangan->gedung->nama_gedung }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-right col-3">Ruangan :</th>
                                            <td class="col-9">{{ $laporan->laporan->fasilitas->ruangan->nama_ruangan }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-right col-3">Deskripsi :</th>
                                            <td class="col-9">
                                                @if ($laporan->laporan && $laporan->laporan->laporanPelapor && $laporan->laporan->laporanPelapor->count())
                                                    <ul class="mb-0">
                                                        <li>{{ $laporan->laporan->deskripsi }}</li>
                                                        @foreach ($laporan->laporan->laporanPelapor as $pelapor)
                                                            @if ($pelapor->id_laporan == $laporan->laporan->id)
                                                                <li>{{ $pelapor->deskripsi_tambahan }}</li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    {{ $laporan->laporan->deskripsi }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right col-3">Tanggal Lapor :</th>
                                            <td class="col-9">
                                                {{ $laporan->laporan->tanggal_lapor 
                                                    ? \Carbon\Carbon::parse($laporan->laporan->tanggal_lapor)->locale('id')->translatedFormat('l, d F Y') 
                                                    : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right col-3">Tanggal Selesai :</th>
                                            <td class="col-9">
                                                {{ $laporan->laporan->tanggal_selesai 
                                                    ? \Carbon\Carbon::parse($laporan->laporan->tanggal_selesai)->locale('id')->translatedFormat('l, d F Y') 
                                                    : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            @php
                                                $statusColor = match ($laporan->laporan->id_status) {
                                                    1 => 'bg-secondary',
                                                    2 => 'bg-primary',
                                                    3 => 'bg-info',
                                                    4 => 'bg-success text-white',
                                                    5 => 'bg-danger text-white',
                                                    default => 'bg-dark',
                                                };
                                            @endphp
                                            <th class="text-right col-3">Status :</th>
                                            <td class="col-9">
                                                <span class="badge {{ $statusColor }}">{{ $laporan->laporan->status->nama_status }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right col-3">Teknisi :</th>
                                            <td class="col-9">{{ $laporan->user->nama ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-right col-3">Catatan Teknisi :</th>
                                            <td class="col-9">{{ $laporan->catatan_teknisi ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-right col-3">Catatan Sarpras :</th>
                                            <td class="col-9">{{ $laporan->komentar_sarpras ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-right col-3">Rating :</th>
                                            <td class="col-9">
                                                @php
                                                    $ratings = $laporan->laporan->laporanPelapor;
                                                    $avgRating = $ratings ? $ratings->avg('rating_pengguna') : null;
                                                @endphp

                                                @if ($avgRating)
                                                    @for ($i = 0; $i < floor($avgRating); $i++)
                                                        <span style="color: gold;">&#9733;</span>
                                                    @endfor
                                                    @if ($avgRating - floor($avgRating) >= 0.5)
                                                        <span style="color: gold;">&#9734;</span>
                                                    @endif
                                                    <small>({{ number_format($avgRating, 1) }})</small>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fas fa-times mr-1"></i> Tutup
            </button>
        </div>
    </div>
</div>

<!-- Script -->
<script>
    let isImageExpanded = false;

    $(document).on('click', '#toggleImageBtn', function () {
        const $imageContainer = $('#expandedImageContainer');
        const $kerusakanContainer = $('#expandedKerusakanContainer');

        if (!$kerusakanContainer.hasClass('d-none')) {
            $kerusakanContainer.addClass('d-none');
            $('#toggleKerusakanText').text('Lihat Foto Kerusakan');
            $('#toggleKerusakanBtn').html('<i class="fas fa-image mr-1"></i> Lihat Foto Kerusakan');
        }

        $imageContainer.toggleClass('d-none');
        const isExpanded = !$imageContainer.hasClass('d-none');
        $('#toggleImageText').text(isExpanded ? 'Sembunyikan Dokumentasi' : 'Lihat Dokumentasi Perbaikan');
        $(this).html(isExpanded 
            ? '<i class="fas fa-compress-alt mr-1"></i> Sembunyikan' 
            : '<i class="fas fa-image mr-1"></i> Lihat Dokumentasi');
    });

    $(document).on('click', '#toggleKerusakanBtn', function () {
        const $imageContainer = $('#expandedImageContainer');
        const $kerusakanContainer = $('#expandedKerusakanContainer');

        if (!$imageContainer.hasClass('d-none')) {
            $imageContainer.addClass('d-none');
            $('#toggleImageText').text('Lihat Dokumentasi Perbaikan');
            $('#toggleImageBtn').html('<i class="fas fa-image mr-1"></i> Lihat Dokumentasi');
        }

        $kerusakanContainer.toggleClass('d-none');
        const isExpanded = !$kerusakanContainer.hasClass('d-none');
        $('#toggleKerusakanText').text(isExpanded ? 'Sembunyikan Foto Kerusakan' : 'Lihat Foto Kerusakan');
        $(this).html(isExpanded 
            ? '<i class="fas fa-compress-alt mr-1"></i> Sembunyikan' 
            : '<i class="fas fa-image mr-1"></i> Lihat Foto Kerusakan');
    });

</script>

<!-- Style -->
<style>
    .bg-gradient-success {
        background: linear-gradient(135deg, #e4851a 0%, #d1793f 100%);
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    textarea {
        resize: none;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
    }
    #mainModalClose {
        opacity: 0.9;
        transition: opacity 0.2s;
    }
    #mainModalClose:hover {
        opacity: 1;
    }
    #expandedImageContainer .card-body {
        max-height: 60vh;
        overflow-y: auto;
    }
</style>
