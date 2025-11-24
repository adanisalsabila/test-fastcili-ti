@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'fasilitas',
])

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Kelola Data Fasilitas</h3>
            @if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2)
                <div class="d-flex justify-content-center flex-wrap">
                    <button onclick="modalAction('{{ url('/fasilitas/import') }}')" class="btn btn-warning mr-2">
                        Impor Data Fasilitas
                    </button>
                    <button onclick="modalAction('{{ url('/fasilitas/create') }}')" class="btn btn-success">
                        Tambah Data Fasilitas
                    </button>
                </div>
            @endif
        </div>

        <div class="card p-4">
            <div class="card-body">
                {{-- Search and Filtering --}}
                <div class="row pr-auto">
                    <div class="col-12">
                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">Cari Data Fasilitas:</label>
                            <input type="text" class="form-control mb-2" id="search" placeholder="Cari fasilitas...">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">Filter Status:</label>
                            <select class="form-control mb-2" id="status_fasilitas" name="status_fasilitas" required>
                                <option value="">- Semua Status -</option>
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">Filter Gedung:</label>
                            <select class="form-control mb-2" id="id_gedung" name="id_gedung" required>
                                <option value="">- Semua Gedung -</option>
                                @foreach ($gedung as $item)
                                    <option value="{{ $item->id_gedung }}">{{ $item->nama_gedung }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">Filter Ruangan:</label>
                            <select class="form-control mb-2" id="id_ruangan" name="id_ruangan" {{ request('id_ruangan') ? '' : 'disabled' }}>
                                <option value="">- Semua Ruangan -</option>
                                @if (isset($ruangan))
                                    @foreach ($ruangan as $item)
                                        <option value="{{ $item->id_ruangan }}" data-gedung="{{ $item->id_gedung }}" {{ request('id_ruangan') == $item->id_ruangan ? 'selected' : '' }}>
                                            {{ $item->nama_ruangan }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Card View --}}
                <span class="badge badge-info p-2 mb-3">
                    <i class="fas fa-sort-amount-down-alt mr-1"></i> Diurutkan berdasarkan: Terbaru Ditambahkan
                </span>
                <div class="row g-3" id="fasilitas-container">
                    <!-- Fasilitas cards will be loaded here -->
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
        @media (min-width: 768px) {
            /* Pada layar medium ke atas, tata letak search & filter jadi 2 kolom */
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

        /* Fasilitas Card Styling */
        .fasilitas-card {
            margin-bottom: 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .fasilitas-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .fasilitas-card-body {
            padding: 15px;
            flex-grow: 1;
        }

        .fasilitas-card-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .fasilitas-card-text {
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #555;
        }

        .fasilitas-card-footer {
            padding: 10px 15px;
            background-color: #fef5ed;
            border-top: 1px solid #eee;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .fasilitas-card-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .fasilitas-jumlah {
            font-weight: bold;
            color: #28a745;
        }

        .fasilitas-kode {
            font-weight: bolder;
            color: #ffa200;
        }

        .fasilitas-ruangan {
            color: #ffa200;
        }

        .fasilitas-gedung {
            color: #6c757d;
            font-style: italic;
            font-size: 0.85rem;
        }

        /* Responsive grid settings */
        #fasilitas-container {
            margin-right: -12px;
            margin-left: -12px;
        }

        #fasilitas-container>[class*="col-"] {
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
        var perPage = 16; // 4 cards x 4 rows
        var searchTimeout;

        $(document).ready(function() {
            // Initialize ruangan dropdown as disabled
            $('#id_ruangan').prop('disabled', true);

            // Load initial data
            loadFasilitasCards();

            // Gedung change event
            $('#id_gedung').on('change', function() {
                const idGedung = $(this).val();

                if (idGedung) {
                    // Enable ruangan dropdown
                    $('#id_ruangan').prop('disabled', false);

                    // Filter ruangan options based on selected gedung
                    $('#id_ruangan option').each(function() {
                        const optionGedung = $(this).data('gedung');
                        if (optionGedung == idGedung || $(this).val() === '') {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });

                    // Reset ruangan selection
                    $('#id_ruangan').val('');
                } else {
                    // Disable ruangan dropdown if no gedung selected
                    $('#id_ruangan').prop('disabled', true);
                    $('#id_ruangan').val('');
                }

                currentPage = 1;
                loadFasilitasCards();
            });

            // Filter change events
            $('#id_ruangan').on('change', function() {
                currentPage = 1;
                loadFasilitasCards();
            });

            // Search input event with debounce
            $('#search').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    currentPage = 1;
                    loadFasilitasCards();
                }, 500);
            });

            $('#status_fasilitas').on('change', function() {
                currentPage = 1;
                loadFasilitasCards();
            });
        });

        function loadFasilitasCards() {
            const search = $('#search').val();
            const idGedung = $('#id_gedung').val();
            const idRuangan = $('#id_ruangan').val();
            const statusFasilitas = $('#status_fasilitas').val();

            $.ajax({
                url: "{{ url('fasilitas/list') }}",
                type: "GET",
                dataType: "json",
                data: {
                    search: search,
                    id_gedung: idGedung,
                    id_ruangan: idRuangan,
                    status_fasilitas: statusFasilitas,
                    page: currentPage,
                    per_page: perPage
                },
                success: function(response) {
                    const container = $('#fasilitas-container');
                    container.empty();

                    if (response.data && response.data.length > 0) {
                        response.data.forEach((fasilitas) => {
                            const statusBadgeClass = fasilitas.status_fasilitas === 'Rusak' ?
                                'badge-danger' : 'badge-success';

                            const cardHtml = `
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card fasilitas-card">
                                    <div class="fasilitas-card-body p-3">
                                        <h5 class="fasilitas-card-title">${fasilitas.nama_fasilitas}</h5>
                                        <p class="fasilitas-card-text"><strong>Kode:</strong> <span class="fasilitas-kode">${fasilitas.kode_fasilitas || '-'}</span></p>
                                        <p class="fasilitas-card-text"><strong>Nama:</strong> <span class="fasilitas-ruangan">${fasilitas.ruangan?.nama_ruangan || '-'}</span></p>
                                        <p class="fasilitas-card-text"><strong>Gedung:</strong> <span class="fasilitas-gedung">${fasilitas.ruangan?.gedung?.nama_gedung || '-'}</span></p>
                                        <p class="fasilitas-card-text"><strong>Jumlah:</strong> <span class="fasilitas-jumlah">${fasilitas.jumlah}</span></p>
                                        <span class="badge badge-pill ${statusBadgeClass} p-2 mt-2">Status: ${fasilitas.status_fasilitas}</span> 
                                    </div>
                                    @if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2)
                                    <div class="fasilitas-card-footer p-2">
                                        <div class="fasilitas-card-actions">
                                            <button onclick="modalAction('{{ url('/fasilitas/edit') }}/${fasilitas.id_fasilitas}')" 
                                                    class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <form class="form-delete d-inline" action="{{ url('/fasilitas/delete') }}/${fasilitas.id_fasilitas}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            `;
                            container.append(cardHtml);
                        });
                    } else {
                        container.append(
                            '<div class="col-12 text-center py-4"><p class="text-muted">Tidak ada data fasilitas seperti keyword yang ingin dicari</p></div>'
                        );
                    }

                    // Update pagination
                    updatePagination(response);
                },
                error: function(xhr) {
                    console.error('Error loading fasilitas:', xhr);
                    alert('Gagal memuat data fasilitas');
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
            loadFasilitasCards();
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
                                loadFasilitasCards();
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
