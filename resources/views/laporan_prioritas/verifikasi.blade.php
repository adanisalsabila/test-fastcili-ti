<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-0 shadow modal-scrollable">
        <form id="formVerifikasi" method="POST" action="{{ url('/verifikasi-perbaikan') }}" data-status-ntf="{{ $laporan->status->id_status }}">
            @csrf
            <input type="hidden" name="id_laporan" value="{{ $laporan->id_laporan }}">
            <input type="hidden" name="id_penugasan" value="{{ $laporan->penugasan->last()?->id_penugasan ?? '' }}">

            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-check-circle mr-2"></i>Verifikasi Perbaikan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="mainModalClose">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4">
                <div class="row">
                    <!-- Left Column - Information (now full width when expanded) -->
                    <div class="col-12" id="detailContainer">
                        <div class="card border-0 mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-3 font-weight-bold">Detail Perbaikan</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="toggleImageBtn">
                                    <i class="fas fa-image mr-1"></i>
                                    <span id="toggleImageText">Lihat Dokumentasi</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="text-muted small mb-1">Status Perbaikan</label>
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge-{{ $laporan->penugasan->last()?->status_perbaikan == 'selesai' ? 'success' : 'warning' }} mr-2">
                                                    {{ $laporan->penugasan->last()?->status_perbaikan ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="text-muted small mb-1">Teknisi</label>
                                            <p class="mb-3 font-weight-bold">
                                                <i class="fas fa-user-tie mr-2 text-primary"></i>
                                                {{ $laporan->penugasan->last()?->user->nama ?? '-' }}
                                            </p>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="text-muted small mb-1">Catatan Teknisi</label>
                                            <div class="bg-light p-3 rounded">
                                                <p class="mb-3">
                                                    <i class="fas fa-sticky-note mr-2 text-info"></i>
                                                    {{ $laporan->penugasan->last()?->catatan_teknisi ?? 'Tidak ada catatan' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="documentation-preview text-center">
                                            <img src="{{ asset('storage/uploads/dokumentasi/' . $laporan->penugasan->last()?->dokumentasi) }}"
                                                 onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';"
                                                 alt="Belum ada dokumentasi diberikan" 
                                                 class="img-fluid rounded shadow-sm border"
                                                 style="max-height: 200px; width: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Expanded Image (hidden by default) -->
                    <div class="col-12 d-none" id="expandedImageContainer">
                        <div class="card border-0 mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-3 font-weight-bold">Dokumentasi Perbaikan</h6>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="hideImageBtn">
                                    <i class="fas fa-compress-alt mr-1"></i> Sembunyikan
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="text-center">
                                    <img src="{{ asset('storage/uploads/dokumentasi/' . $laporan->penugasan->last()?->dokumentasi) }}"
                                         onerror="this.onerror=null;this.src='{{ asset('foto_kerusakan.jpg') }}';"
                                         alt="Dokumentasi Perbaikan" 
                                         class="img-fluid w-100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if ($laporan->status->id_status != 4)
                    <!-- Notes Section -->
                    <div class="card border-0">
                        <div class="card-header bg-light">
                            <h6 class="mb-3 font-weight-bold">Catatan Verifikasi</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="keterangan" class="text-muted small">Berikan catatan jika menolak verifikasi</label>
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="3" 
                                        placeholder="Masukkan alasan penolakan (jika diperlukan)"></textarea>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if ($laporan->status->id_status == 4)
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save mr-1"></i> Menuju Halaman Prioritas
                    </button>
                </div>
            @else
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                    <button type="submit" name="verifikasi" value="tolak" class="btn btn-danger">
                        <i class="fas fa-times-circle mr-1"></i> Tolak
                    </button>
                    <button type="submit" name="verifikasi" value="setuju" class="btn btn-success">
                        <i class="fas fa-check-circle mr-1"></i> Setuju (Selesai)
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>

<script>
    let clickedVerifikasi = null;
    let isImageExpanded = false;

    // Toggle image expansion
    $(document).on('click', '#toggleImageBtn', function() {
        if (!isImageExpanded) {
            $('#expandedImageContainer').removeClass('d-none');
            $('#toggleImageText').text('Sembunyikan Dokumentasi');
            $('#toggleImageBtn').html('<i class="fas fa-compress-alt mr-1"></i> Sembunyikan');
            isImageExpanded = true;
        } else {
            $('#expandedImageContainer').addClass('d-none');
            $('#toggleImageText').text('Lihat Dokumentasi');
            $('#toggleImageBtn').html('<i class="fas fa-image mr-1"></i> Lihat Dokumentasi');
            isImageExpanded = false;
        }
    });

    // Alternative hide button
    $(document).on('click', '#hideImageBtn', function() {
        $('#expandedImageContainer').addClass('d-none');
        $('#toggleImageText').text('Lihat Dokumentasi');
        $('#toggleImageBtn').html('<i class="fas fa-image mr-1"></i> Lihat Dokumentasi');
        isImageExpanded = false;
    });

    // Close modal when clicking outside
    $(document).on('click', '.modal', function(e) {
        if ($(e.target).hasClass('modal')) {
            $('#myModal').modal('hide');
        }
    });

    $(document).on('click', 'button[name="verifikasi"]', function() {
        clickedVerifikasi = $(this).val();
    });

    $(document).on('submit', '#formVerifikasi', function(e) {
        const statusNtf = $(this).data('status-ntf');

            // Jika trendingNo == '-', jangan submit, arahkan saja ke prioritas.index
            if (statusNtf === 4) {
                e.preventDefault();

                Swal.fire({
                    icon: "warning",
                    title: "Laporan sudah diverifikasi dan dinyatakan selesai.",
                    text: "Anda akan diarahkan ke halaman Prioritas.",
                    confirmButtonText: "OK",
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('prioritas.index') }}";
                    }
                });

                return; // Stop form submission
            }

        e.preventDefault();

        const formData = new FormData(this);
        formData.append('verifikasi', clickedVerifikasi);

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function() {
                $('#formVerifikasi button[type=submit]').prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Menyimpan...');
            },
            success: function(response) {
                $('#formVerifikasi button[type=submit]').prop('disabled', false).html(function() {
                    return clickedVerifikasi === 'setuju' 
                        ? '<i class="fas fa-check-circle mr-1"></i> Setuju (Selesai)' 
                        : '<i class="fas fa-times-circle mr-1"></i> Tolak';
                });
                
                if (response.success) {
                    $('#myModal').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.messages,
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: response.messages,
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function(xhr) {
                $('#formVerifikasi button[type=submit]').prop('disabled', false).html(function() {
                    return clickedVerifikasi === 'setuju' 
                        ? '<i class="fas fa-check-circle mr-1"></i> Setuju (Selesai)' 
                        : '<i class="fas fa-times-circle mr-1"></i> Tolak';
                });
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "Terjadi kesalahan saat menyimpan data.",
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    });
</script>

<style>
    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
    .documentation-preview {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #expandedImageContainer .card-body {
        max-height: 60vh;
        overflow-y: auto;
    }
</style>