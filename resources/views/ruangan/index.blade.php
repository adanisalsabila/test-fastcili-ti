@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'ruangan',
])

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Kelola Data Ruangan</h3>
            @if (auth()->user()->id_level == 1  || auth()->user()->id_level == 2)
            <div class="d-flex justify-content-center flex-wrap">
                <button onclick="modalAction('{{ url('/ruangan/import') }}')" class="btn btn-warning text-truncate mr-2">
                    Impor Data Ruangan
                </button>
                <button onclick="modalAction('{{ url('/ruangan/create') }}')" class="btn btn-success text-truncate">
                    Tambah Data Ruangan
                </button>
            </div>
            @endif
        </div>
        <div class="card p-4">
            <div class="card-body">
                {{-- Search and Filtering --}}
                <div class="row pr-auto">
                    <div class="col-12">

                        {{-- Search --}}
                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">Cari Data Ruangan:</label>
                            <input type="text" class="form-control mb-2" id="search" placeholder="Cari ruangan...">
                        </div>

                        {{-- Filter Gedung --}}
                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">Filter Gedung:</label>
                            <select class="form-control mb-2" id="id_gedung" name="id_gedung" required>
                                <option value="">- Semua Gedung -</option>
                                @foreach ($gedung as $item)
                                    <option value="{{ $item->id_gedung }}" {{ request('id_gedung') == $item->id_gedung ? 'selected' : '' }}>
                                        {{ $item->nama_gedung }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                {{-- Card View --}}
                <span class="badge badge-info p-2 mb-3">
                    <i class="fas fa-sort-amount-down-alt mr-1"></i> Diurutkan berdasarkan: Data Terakhir Ditambahkan
                </span>
                <div class="row g-3" id="ruangan-container">
                    <!-- Ruangan cards will be loaded here -->
                </div>

                {{-- Pagination --}}
                <div class="row mt-4">
                    <div class="col-md-12 d-flex justify-content-center">
                        <div id="pagination-links"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="false"
        aria-hidden="true"></div>
@endsection

@push('styles')
    <style>
        /* Responsive form-group for search & filter */
        @media (min-width: 768px) {
            .form-group {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
            }

            .form-group > label {
                flex: 0 0 200px;
                margin-bottom: 0;
            }

            .form-group > .form-control,
            .form-group > select {
                flex: 1;
            }
        }

        /* Container untuk buttons */
        .card-tools {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            width: 100%;
        }

        /* Base button style dengan ukuran tetap */
        .btn-action {
            width: 240px;
            /* Ukuran tetap */
            height: 50px;
            /* Tinggi tetap */
            padding: 0.5rem;
            border-radius: 6px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: clamp(0.8rem, 2vw, 1rem);
            /* Font size responsive */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: all 0.3s ease;
        }

        /* Untuk layar kecil */
        @media (max-width: 768px) {
            .btn-action {
                width: 200px;
                height: 45px;
                font-size: clamp(0.7rem, 2vw, 0.9rem);
            }
        }

        @media (max-width: 576px) {
            .btn-action {
                width: 100%;
                max-width: 220px;
                height: 42px;
            }
        }

        .badge.badge-info {
            background-color: #f49a00;
        }

        /* Ruangan Card Styling */
        .ruangan-card {
            margin-bottom: 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .ruangan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .ruangan-card-body {
            padding: 15px;
            flex-grow: 1;
        }

        .ruangan-card-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .ruangan-card-text {
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #555;
        }

        .ruangan-card-footer {
            padding: 10px 15px;
            background-color: #fef5ed;
            border-top: 1px solid #eee;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .ruangan-card-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .ruangan-kode {
            font-weight: bold;
            color: #ffa200;
        }

        .ruangan-gedung {
            color: #6c757d;
            font-style: italic;
            font-size: 0.85rem;
        }

        /* Responsive grid settings */
        #ruangan-container {
            margin-right: -12px;
            margin-left: -12px;
        }

        #ruangan-container>[class*="col-"] {
            padding-right: 12px;
            padding-left: 12px;
            margin-bottom: 24px;
        }

        /* Button styling */
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        /* Pagination styling */
        .pagination {
            margin-top: 20px;
        }

        .page-item.active .page-link {
            background-color: #ffa200;
            border-color: #ffa200;
        }

        .page-link {
            color: #ffa200;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const gedungId = urlParams.get('id_gedung');
            if (gedungId) {
                $('#id_gedung').val(gedungId).trigger('change');
            }
        });
    </script>
    <script>
        function adjustButtonText() {
            const buttons = document.querySelectorAll('.card-tools .btn');

            buttons.forEach(button => {
                // Reset font size untuk perhitungan ulang
                button.style.fontSize = '';

                // Dapatkan dimensi button dan teks
                const buttonWidth = button.offsetWidth;
                const textWidth = button.scrollWidth;

                // Jika teks melebihi lebar button, kurangi font size
                if (textWidth > buttonWidth) {
                    const fontSize = parseFloat(window.getComputedStyle(button).fontSize);
                    const newFontSize = fontSize * 0.9; // Kurangi 10%
                    button.style.fontSize = `${newFontSize}px`;
                }
            });
        }

        // Panggil fungsi saat load dan resize
        window.addEventListener('load', adjustButtonText);
        window.addEventListener('resize', adjustButtonText);

        var currentPage = 1;
        var perPage = 12; // 4 cards x 3 rows
        var searchTimeout;

        $(document).ready(function() {
            // Load initial data
            loadRuanganCards();

            // Search input event with debounce
            $('#search').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    currentPage = 1;
                    loadRuanganCards();
                }, 500);
            });

            // Gedung filter change event
            $('#id_gedung').on('change', function() {
                currentPage = 1;
                loadRuanganCards();
            });
        });

        function loadRuanganCards() {
            const searchTerm = $('#search').val();
            const idGedung = $('#id_gedung').val();

            $.ajax({
                url: "{{ url('ruangan') }}",
                type: "GET",
                dataType: "json",
                data: {
                    search: searchTerm,
                    id_gedung: idGedung,
                    page: currentPage,
                    per_page: perPage
                },
                success: function(response) {
                    const container = $('#ruangan-container');
                    container.empty();

                    if (response.data && response.data.length > 0) {
                        response.data.forEach((ruangan) => {
                            const cardHtml = `
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                    <div class="card ruangan-card">
                                        <div class="ruangan-card-body" style="cursor: pointer;" onclick="window.location.href = '{{ url('/fasilitas?id_ruangan=${ruangan.id_ruangan}') }}'">
                                            <h5 class="ruangan-card-title">${ruangan.nama_ruangan}</h5>
                                            <p class="ruangan-card-text"><strong>Kode:</strong> <span class="ruangan-kode">${ruangan.kode_ruangan || '-'}</span></p>
                                            <p class="ruangan-card-text"><strong>Gedung:</strong> <span class="ruangan-gedung">${ruangan.gedung?.nama_gedung || 'Tidak ada gedung'}</span></p>
                                        </div>
                                        <div class="ruangan-card-footer p-2 border-top bg-light">
                                            <div class="d-flex justify-content-between align-items-center">
                                                
                                                {{-- Tombol menuju halaman Fasilitas dengan filter --}}
                                                <a href="{{ url('/fasilitas?id_ruangan=${ruangan.id_ruangan}') }}" class="btn btn-sm btn-info d-flex align-items-center">
                                                    <i class="fas fa-door-open me-1"></i> Lihat Fasilitas
                                                </a>
                                                @if (auth()->user()->id_level == 1  || auth()->user()->id_level == 2)
                                                <div class="d-flex ">
                                                    <button onclick="modalAction('{{ url('/ruangan/edit') }}/${ruangan.id_ruangan}')" 
                                                            class="btn btn-sm btn-warning mr-2" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </button>

                                                    <form class="form-delete d-inline" action="{{ url('/ruangan/delete') }}/${ruangan.id_ruangan}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            `;
                            container.append(cardHtml);
                        });
                    } else {
                        container.append(
                            '<div class="col-12 text-center py-4"><p class="text-muted">Tidak ada data ruangan seperti keyword yang ingin dicari</p></div>'
                        );
                    }

                    // Update pagination
                    updatePagination(response);
                },
                error: function(xhr) {
                    console.error('Error loading ruangan:', xhr);
                    alert('Gagal memuat data ruangan');
                }
            });
        }

        function updatePagination(response) {
            const paginationContainer = $('#pagination-links');
            paginationContainer.empty();

            if (response.last_page > 1) {
                let paginationHtml = '<ul class="pagination">';

                // Previous button
                if (response.current_page > 1) {
                    paginationHtml += `
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="changePage(${response.current_page - 1})" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    `;
                } else {
                    paginationHtml += `
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true">&laquo;</span>
                        </li>
                    `;
                }

                // Page numbers
                for (let i = 1; i <= response.last_page; i++) {
                    if (i === response.current_page) {
                        paginationHtml += `
                            <li class="page-item active">
                                <span class="page-link">${i}</span>
                            </li>
                        `;
                    } else {
                        paginationHtml += `
                            <li class="page-item">
                                <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                            </li>
                        `;
                    }
                }

                // Next button
                if (response.current_page < response.last_page) {
                    paginationHtml += `
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="changePage(${response.current_page + 1})" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    `;
                } else {
                    paginationHtml += `
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true">&raquo;</span>
                        </li>
                    `;
                }

                paginationHtml += '</ul>';
                paginationContainer.append(paginationHtml);
            }
        }

        function changePage(page) {
            currentPage = page;
            loadRuanganCards();
            return false;
        }

        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        $(document).on('submit', '.form-delete', function(e) {
            e.preventDefault();
            let form = this;

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
                        url: $(form).attr('action'),
                        data: $(form).serialize(),
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: "success",
                                    title: "Berhasil!",
                                    text: response.message,
                                    timer: 3000,
                                    showConfirmButton: true
                                });
                                loadRuanganCards();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal!",
                                    text: response.message,
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.msgField) {
                                let errors = xhr.responseJSON.msgField;
                                $.each(errors, function(field, message) {
                                    $('#error-' + field).text(message[0]);
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal!",
                                    text: xhr.responseJSON.message ||
                                        'Terjadi kesalahan',
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
