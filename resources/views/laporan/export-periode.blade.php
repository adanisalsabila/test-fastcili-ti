{{-- @push('styles') --}}
<style>
    /* Custom form styling for this modal */
    .periode-option-card {
        border: 2px solid #e9ecef;
        border-radius: .5rem;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .periode-option-card:hover {
        border-color: #F97316;
        background-color: #FFF7ED;
    }
    .periode-option-card.active {
        border-color: #F97316;
        background-color: #FFF7ED;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.25);
    }
    .periode-option-card .form-check-label {
        font-weight: 600;
        color: #343a40;
    }
    .periode-option-card .form-check-input {
        /* Sembunyikan radio button asli */
        display: none;
    }
    .periode-controls {
        padding-top: 1rem;
        margin-top: 1rem;
        border-top: 1px solid #e9ecef;
    }
    .custom-select-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
        flex: 1;
    }
    .form-control-custom {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: #fff;
        border: 1px solid #dee2e6;
        padding: 0.5rem 1rem;
        border-radius: .375rem;
        font-weight: 500;
        color: #495057;
        width: 100%;
        transition: all 0.2s ease;
        height: calc(1.5em + 1rem + 2px);
    }
    .custom-select-wrapper::after {
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
</style>
{{-- @endpush --}}

<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow-lg">
        <form action="{{ route('periode.export') }}" method="POST">
            @csrf
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-file-export mr-2"></i>Ekspor Laporan Periode
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                
                <!-- Opsi Periode Relatif -->
                <div class="periode-option-card active mb-3" id="card_relative" onclick="selectPeriodeOption('relative')">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="periode_type" id="type_relative" value="relative" checked>
                        <label class="form-check-label" for="type_relative">
                            <i class="fas fa-clock mr-2 text-primary"></i>Pilih Periode Relatif
                        </label>
                    </div>
                    <div class="periode-controls" id="relative_inputs">
                        <div class="d-flex align-items-center" style="gap: 1rem;">
                            <input type="number" name="periode_angka" class="form-control" value="1" style="max-width: 100px;">
                            <div class="custom-select-wrapper">
                                <select name="periode_satuan" class="form-control-custom">
                                    <option value="tahun" selected>Tahun Terakhir</option>
                                    <option value="bulan">Bulan Terakhir</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Opsi Periode Spesifik -->
                <div class="periode-option-card" id="card_absolute" onclick="selectPeriodeOption('absolute')">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="periode_type" id="type_absolute" value="absolute">
                        <label class="form-check-label" for="type_absolute">
                            <i class="fas fa-calendar-alt mr-2 text-success"></i>Pilih Periode Spesifik
                        </label>
                    </div>
                    <div class="periode-controls" id="absolute_inputs" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tanggal_awal" class="form-label small">Tanggal Awal</label>
                                <input type="date" name="tanggal_awal" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="tanggal_akhir" class="form-label small">Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-download mr-1"></i> Ekspor Data
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    function selectPeriodeOption(type) {
        if (type === 'relative') {
            document.getElementById('type_relative').checked = true;
            document.getElementById('card_relative').classList.add('active');
            document.getElementById('card_absolute').classList.remove('active');
            document.getElementById('relative_inputs').style.display = 'block';
            document.getElementById('absolute_inputs').style.display = 'none';
        } else {
            document.getElementById('type_absolute').checked = true;
            document.getElementById('card_absolute').classList.add('active');
            document.getElementById('card_relative').classList.remove('active');
            document.getElementById('relative_inputs').style.display = 'none';
            document.getElementById('absolute_inputs').style.display = 'block';
        }
    }
</script>
