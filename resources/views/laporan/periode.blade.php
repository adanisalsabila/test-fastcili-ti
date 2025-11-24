@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'periode' // Sesuaikan dengan elemen aktif Anda
])

@push('styles')
<style>
    :root {
        --orange-dark: #EA580C;
        --orange-main: #F97316;
        --orange-light: #FFF7ED;
        --green-dark: #15803D;
        --green-main: #22C55E;
        --green-light: #F0FDF4;
        --danger-gradient: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
        --text-main: #334155;
        --border-color: #E2E8F0;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--orange-main) 0%, var(--orange-dark) 100%);
    }
    .bg-gradient-danger {
        background: var(--danger-gradient);
    }
    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
    }

    /* New Dropdown Styling */
    .filter-bar {
        background-color: #fff;
        border: 1px solid var(--border-color);
        border-radius: .5rem;
    }
    .year-select-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
    }
    .year-select-custom {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: #f8f9fa;
        border: 1px solid var(--border-color);
        padding: 0.5rem 2.5rem 0.5rem 1rem;
        border-radius: .375rem;
        font-weight: 600;
        color: var(--text-main);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .year-select-custom:hover {
        border-color: var(--orange-main);
        box-shadow: 0 0 0 2px var(--orange-light);
    }
    .year-select-wrapper::after {
        content: ''; /* Kosongkan content karena kita pakai background-image */
        position: absolute;
        right: 1rem;
        pointer-events: none;
        width: 0.8em; /* Atur ukuran ikon */
        height: 0.8em;
        background-color: var(--text-main); /* Warna ikon */
        -webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'%3E%3Cpath d='M201.4 342.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 274.7 86.6 137.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z'/%3E%3C/svg%3E");
        mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'%3E%3Cpath d='M201.4 342.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 274.7 86.6 137.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z'/%3E%3C/svg%3E");
        -webkit-mask-repeat: no-repeat;
        mask-repeat: no-repeat;
        -webkit-mask-position: center;
        mask-position: center;
        transition: transform 0.2s ease;
    }

    /* Accordion Styling */
    .accordion-button-custom {
        font-size: 1.1rem;
        font-weight: 600;
        border: 1px solid var(--border-color);
        border-radius: .375rem;
        padding: 1rem 1.25rem;
        text-decoration: none;
        display: block;
        width: 100%;
        text-align: left;
        margin-bottom: 0.5rem; /* Space between buttons */
        transition: all 0.2s ease;
    }
    /* State: Collapsed (default) */
    .accordion-button-custom.collapsed {
        background-color: #f8f9fa;
        color: var(--text-main);
    }
    .accordion-button-custom.collapsed:hover {
        border-color: var(--orange-main);
    }
    /* State: Expanded */
    .accordion-button-custom {
       color: var(--orange-dark);
       background-color: var(--orange-light);
       border-color: var(--orange-main);
    }
    
    /* Green version for 'Selesai' */
    .accordion-button-custom.success.collapsed {
        background-color: #f8f9fa;
        color: var(--text-main);
    }
    .accordion-button-custom.success.collapsed:hover {
        border-color: var(--green-main);
    }
    .accordion-button-custom.success {
        color: var(--green-dark);
        background-color: var(--green-light);
        border-color: var(--green-main);
    }

    /* Table & Rows */
    .report-card {
        border: 1px solid var(--border-color);
        border-left-width: 5px;
        background-color: #fff;
    }
    .report-card.status-pending { border-left-color: var(--orange-main); }
    .report-card.status-done { border-left-color: var(--green-main); }

</style>
@endpush

@section('content')
<div class="content">
    <h3 class="mb-4 text-gray-800">Manajemen Periode Laporan</h3>

    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
        </div>
    @endif

    <div class="card filter-bar shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center p-3">
            <form action="{{ route('periode.index') }}" method="GET" class="d-flex align-items-center">
                <label for="tahun" class="mr-3 font-weight-bold text-muted">Periode Tahun:</label>
                <div class="year-select-wrapper">
                    <select name="tahun" id="tahun" class="year-select-custom" onchange="this.form.submit()">
                        @forelse ($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @empty
                            <option value="{{ now()->year }}" selected>{{ now()->year }}</option>
                        @endforelse
                    </select>
                </div>
            </form>
            <div>
                <button type="button" class="btn btn-primary" onclick="modalAction('{{ route('periode.modal.export') }}')">
                    <i class="fas fa-file-export mr-1"></i> Ekspor
                </button>
                <button type="button" class="btn btn-danger" onclick="modalAction('{{ route('periode.modal.delete', ['tahun' => $selectedYear]) }}')">
                    <i class="fas fa-trash mr-1"></i> Hapus Periode
                </button>
            </div>
        </div>
    </div>

    <!-- Laporan Belum Selesai -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-3 font-weight-bold">Laporan Belum Selesai / Dalam Proses</h5>
        </div>
        <div class="card-body">
            @forelse ($laporanBelumSelesai as $bulan => $laporans)
                <a class="accordion-button-custom collapsed" data-toggle="collapse" href="#collapse-{{ Str::slug($bulan) }}" role="button" aria-expanded="false" aria-controls="collapse-{{ Str::slug($bulan) }}">
                    {{ $bulan }} ({{ $laporans->count() }} Laporan)
                </a>
                <div class="collapse" id="collapse-{{ Str::slug($bulan) }}">
                    <div class="table-responsive mt-2 mb-3">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fasilitas</th> <th>Lokasi</th> <th>Jumlah</th> <th>Deskripsi</th> <th>Pelapor</th> <th>Status</th> <th>Tgl Lapor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporans as $laporan)
                                <tr class="report-card status-pending">
                                    <td>{{ $laporan->fasilitas->nama_fasilitas ?? 'N/A' }}</td>
                                    <td>{{ $laporan->fasilitas->ruangan->gedung->nama_gedung ?? 'N/A' }}</td>
                                    <td>{{ $laporan->jumlah_kerusakan }}</td>
                                    <td>{{ Str::limit($laporan->deskripsi, 50) }}</td>
                                    <td>{{ $laporan->pelaporLaporan->first()->user->nama ?? 'N/A' }}</td>
                                    <td><span class="badge bg-warning text-dark">{{ $laporan->status->nama_status ?? 'N/A' }}</span></td>
                                    <td>{{ $laporan->tanggal_lapor->translatedFormat('d M Y') ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted p-4">Tidak ada laporan yang sedang diproses untuk periode ini.</p>
            @endforelse
        </div>
    </div>
    
    <!-- Laporan Sudah Selesai -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
             <h5 class="mb-3 font-weight-bold">Laporan Sudah Selesai</h5>
        </div>
        <div class="card-body">
             @forelse ($laporanSelesai as $bulan => $laporans)
                <a class="accordion-button-custom success" data-toggle="collapse" href="#collapse-selesai-{{ Str::slug($bulan) }}" role="button" aria-expanded="true" aria-controls="collapse-selesai-{{ Str::slug($bulan) }}">
                    {{ $bulan }} ({{ $laporans->count() }} Laporan)
                </a>
                <div class="collapse show" id="collapse-selesai-{{ Str::slug($bulan) }}">
                    <div class="table-responsive mt-2 mb-3">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fasilitas</th> <th>Lokasi</th> <th>Jumlah</th> <th>Deskripsi</th> <th>Pelapor</th> <th>Teknisi</th> <th>Tgl Lapor</th> <th>Tgl Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporans as $laporan)
                                <tr class="report-card status-done">
                                    <td>{{ $laporan->fasilitas->nama_fasilitas ?? 'N/A' }}</td>
                                    <td>{{ $laporan->fasilitas->ruangan->gedung->nama_gedung ?? 'N/A' }}</td>
                                    <td>{{ $laporan->jumlah_kerusakan }}</td>
                                    <td>{{ Str::limit($laporan->deskripsi, 30) }}</td>
                                    <td>{{ $laporan->pelaporLaporan->first()->user->nama ?? 'N/A' }}</td>
                                    <td>{{ $laporan->penugasan->last()?->user->nama ?? 'N/A' }}</td>
                                    <td>{{ $laporan->tanggal_lapor->translatedFormat('d M Y') ?? '-' }}</td>
                                    <td>{{ $laporan->tanggal_selesai->translatedFormat('d M Y') ?? '-' }}</td>
                                    {{-- @dd($laporan) --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
             @empty
                <p class="text-center text-muted p-4">Tidak ada laporan yang sudah selesai untuk periode ini.</p>
             @endforelse
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="false" aria-hidden="true"></div>
@endsection

@push('scripts')
<script>
    function modalAction(url = '') {
        if (url) {
            $('#myModal').load(url, function() {
                $(this).modal('show');
            });
        }
    }
</script>
@endpush
