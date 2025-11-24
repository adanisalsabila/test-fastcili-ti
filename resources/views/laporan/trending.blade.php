@extends('layouts.app', [
'class' => '',
'elementActive' => 'trending',
])

@section('content')
<div class="content">
    <h3 class="mb-4">Laporan Kerusakan Trending</h3>
    <div class="card px-2 px-md-4">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center pb-3 pb-md-3 pt-3 pt-md-5">
            <div class="d-flex align-items-center mb-3 mb-md-0 w-100">
                <div class="w-100" style="max-width: 580px;">
                    <input type="text" class="form-control rounded-pill" id="search"
                        placeholder="Cari Laporan..." value="{{ $search }}">
                    <small class="form-text text-muted text-small">Cari berdasarkan fasilitas atau deskripsi laporan</small>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-start justify-content-md-end">
                <span class="badge badge-pill badge-danger px-2 px-md-3 py-2 mr-3 mb-3">
                    <i class="fas fa-fire mr-1"></i> Top 10 Laporan
                </span>
                <span class="badge badge-warning px-2 px-md-3 py-2 mb-3">
                    <i class="fas fa-sort-amount-down-alt mr-1"></i> Diurutkan berdasarkan: Skor Laporan
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div id="trending-container">
                @php
                    $firstNotAssignedShown = false;
                @endphp
                @foreach ($data as $laporan)
                @php
                $rank = $laporan['rank']; // Menggunakan rank yang sudah di-set di controller
                $borderColor = match($rank) {
                1 => 'border-left: 4px solid #ffc107;',
                2 => 'border-left: 4px solid #6c757d;',
                3 => 'border-left: 4px solid #cd7f32;',
                default => 'border-left: 4px solid #e9ecef;'
                };
                @endphp
                <div class="card trending-card mb-3" style="{{ $borderColor }}">
                    <div class="card-body p-2 p-md-3">
                        <div class="d-flex flex-column flex-md-row">
                            <div class="rank-display d-flex flex-row flex-md-column align-items-center justify-content-center mr-0 mr-md-3 mb-2 mb-md-0 p-2" style="width: auto; min-width: 60px;">
                                <span class="rank-number font-weight-bold">#{{ $rank }}</span>
                                <div class="trend-indicator mt-0 mt-md-1 ml-2 ml-md-0">
                                    @if($rank <= 3)
                                        <i class="fas fa-fire text-danger"></i>
                                        @else
                                        <i class="fas fa-chart-line text-primary"></i>
                                        @endif
                                </div>
                            </div>
                            <div class="flex-grow-1 d-flex flex-column">
                                <div class="d-flex flex-column flex-md-row">
                                    <div class="mr-0 mr-md-3 mb-3 mb-md-0 w-100" style="max-width: 250px;">
                                        <img src="{{ asset('storage/uploads/laporan_kerusakan/' . $laporan['laporan']->foto_kerusakan) }}"
                                            onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';"
                                            alt="Foto Kerusakan"
                                            class="img-fluid rounded w-100"
                                            style="height: 180px; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1 d-flex flex-column">
                                        <div>
                                            <h3 class="card-title font-weight-bold mb-2">{{ $laporan['laporan']->fasilitas->nama_fasilitas ?? '-' }}</h3>
                                            <p class="card-text text-secondary font-weight-bold mb-2">{{ $laporan['laporan']->fasilitas->ruangan->nama_ruangan }} - {{ $laporan['laporan']->fasilitas->ruangan->gedung->nama_gedung }}</p>
                                            <p class="card-text text-muted mb-3" style="font-size: 0.9rem;">{{ $laporan['laporan']->deskripsi ?? '-' }}</p>
                                        </div>
                                        @if (auth()->user()->id_level == 4 || auth()->user()->id_level == 5 || auth()->user()->id_level == 6)
                                        <div class="mt-auto">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                                <div class="mb-2 mb-md-0 d-flex flex-wrap gap-2">
                                                    @php
                                                        $statusColor = match ($laporan['laporan']->id_status) {
                                                            1 => 'bg-secondary',
                                                            2 => 'bg-info',
                                                            3 => 'bg-danger',
                                                            4 => 'bg-success',
                                                            default => 'bg-dark',
                                                        };
                                                    @endphp
                                                    <span class="badge badge-pill {{ $statusColor }} text-white px-2 px-md-3 py-2 mr-3 mb-2">
                                                        <i class="fa-solid fa-circle-info mr-1"></i> {{ $laporan['laporan']->status->nama_status }}
                                                    </span>
                                                    <span class="badge badge-pill badge-primary px-2 px-md-3 py-2 mr-3 mb-2">
                                                        <i class="fas fa-users mr-1"></i> Pelapor: {{ $laporan['total_pelapor'] }}
                                                    </span>
                                                </div>
                                                <div class="ml-auto">
                                                    <span class="badge badge-warning px-2 px-md-3 py-2 py-md-3 mr-3 mb-2">
                                                        <i class="fas fa-star mr-1"></i>Skor: <i class="text-skor"> {{ $laporan['skor'] }}</i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="mt-auto">
                                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                                <div class="mb-2 mb-md-0 d-flex flex-wrap gap-2">
                                                    <span class="badge badge-pill badge-primary px-2 px-md-3 py-2 mr-3">
                                                        <i class="fas fa-users mr-1"></i> Pelapor: {{ $laporan['total_pelapor'] }}
                                                    </span>
                                                    <span class="badge badge-pill badge-warning px-2 px-md-3 py-2 mr-3">
                                                        <i class="fas fa-star mr-1"></i> Skor: {{ $laporan['skor'] }}
                                                    </span>
                                                </div>
                                                @if (!$firstNotAssignedShown)
                                                    @php $firstNotAssignedShown = true; @endphp
                                                    <button class="btn btn-danger btn-sm btn-md-lg"
                                                        onclick="modalAction('{{ url('/lapor_kerusakan/penilaian/' . $laporan['laporan']->id_laporan) }}')">
                                                        <i class="fas fa-star mr-1"></i> Beri Nilai
                                                    </button>
                                                @else
                                                    <span class="btn btn-secondary btn-lg px-4 py-2 disabled">
                                                        <i class="fas fa-clock mr-1"></i> Menunggu Penilaian Lain
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
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
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="false"
    aria-hidden="true"></div>
@endsection

@push('styles')
<style>
    .card-title {
        font-size: 2rem
    }
    .card-text {
        font-size: 1rem
    }
    .trending-card {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        border-top: none;
        border-right: none;
        border-bottom: none;
    }

    .trending-card:hover {
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

    .text-skor {
        font-size: 2rem;
        color: #343a40;
    }

    .badge {
        font-size: 0.85rem;
        box-shadow: 1px 2px 10px rgba(0, 0, 0, 0.1);
        white-space: nowrap;
    }

    #trending-container {
        padding: 0 5px;
    }

    .card-title {
        font-size: 1.1rem;
    }
    
    .btn-md-lg {
        padding: 0.5rem 1rem;
        font-size: 1rem;
    }
    
    @media (min-width: 768px) {
        .btn-md-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1.25rem;
        }
        
        #trending-container {
            padding: 0 10px;
        }
    }
    
    @media (max-width: 767px) {
        .card-header {
            padding: 1rem;
        }
        
        .trending-card {
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
        $('.trending-card').each(function(index) {
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

        // Search input event with debounce
        var searchTimeout;
        $('#search').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                updateResults();
            }, 500);
        });

        function updateResults() {
            const sort = $('#sort').val();
            const search = $('#search').val();

            let params = new URLSearchParams();
            if (sort) params.append('sort', sort);
            if (search) params.append('search', search);

            window.location.href = "{{ route('trending.index') }}?" + params.toString();
        }
    });
</script>
@endpush