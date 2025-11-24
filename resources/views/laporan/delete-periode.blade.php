<style>
    .no-copy {
        -webkit-user-select: none; /* Safari */
        -moz-user-select: none;    /* Firefox */
        -ms-user-select: none;     /* IE10+/Edge */
        user-select: none;         /* Standard */
    }
    .konfirmasi-box {
        background-color: #f8f9fa;
        border: 1px dashed #ced4da;
        padding: 1rem;
        border-radius: .25rem;
        font-family: 'Courier New', Courier, monospace;
        font-size: 1.1rem; /* Ukuran font diperbesar */
    }
</style>

<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
        <form action="{{ route('periode.destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="tahun_hapus" value="{{ $selectedYear }}">
            <div class="modal-header bg-gradient-danger text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus Laporan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-danger">
                    <strong>Peringatan Keras!</strong> Anda akan menghapus SEMUA data laporan untuk periode tahun <strong>{{ $selectedYear }}</strong>. Tindakan ini permanen.
                </div>
                <div class="card border-0">
                    <div class="card-header bg-light">
                         <h6 class="mb-3 font-weight-bold">
                            <i class="fas fa-shield-alt mr-2 text-danger"></i>Langkah Keamanan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group no-copy mb-4">
                            <label for="konfirmasi" class="font-weight-bold" style="font-size: 1.1rem;">1. Ketik Kalimat Konfirmasi</label>
                            <p class="text-muted small">Untuk melanjutkan, ketik kalimat di dalam kotak di bawah ini.</p>
                            <div class="konfirmasi-box">
                                <p><strong>Saya ingin menghapus semua laporan kerusakan untuk periode {{ $selectedYear }}</strong></p>
                            </div>
                            <input type="text" class="form-control mt-2" name="konfirmasi" id="konfirmasi" required placeholder="Ketik kalimat di atas di sini...">
                        </div>
                        <div class="form-group">
                            <label for="password" class="font-weight-bold" style="font-size: 1.1rem;">2. Masukkan Password Anda</label>
                             <p class="text-muted small">Masukkan password Anda untuk verifikasi akhir.</p>
                            <input type="password" class="form-control" name="password" id="password" required placeholder="Masukkan password...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash-alt mr-1"></i> Ya, Hapus Semua Laporan
                </button>
            </div>
        </form>
    </div>
</div>
