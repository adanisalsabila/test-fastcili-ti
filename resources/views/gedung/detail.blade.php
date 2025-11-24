<div class="modal-dialog modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Data Gedung</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table ">
                <tbody>
                    <img class="mb-3" src="{{ asset('storage/uploads/foto_gedung/' . $gedung->foto_gedung) }}"
                        alt="Foto Kerusakan" class="card-img-top img-fluid"
                        style="width: 200px; object-fit: cover; text-align: center">
                    <tr>
                        <th style="width: 30%">Kode Gedung</th>
                        <td id="detail-kode">{{ $gedung->kode_gedung }}</td>
                    </tr>
                    <tr>
                        <th>Nama Gedung</th>
                        <td id="detail-nama">{{ $gedung->nama_gedung }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td id="detail-deskripsi">{{ $gedung->deskripsi }}</td>
                    </tr>
                </tbody>
            </table>
            {{-- Tombol menuju halaman ruangan dengan filter --}}
            <a href="{{ route('ruangan.index', ['id_gedung' => $gedung->id_gedung]) }}"
                class="btn btn-info btn-block mt-3">
                <i class="fas fa-door-open mr-1"></i> Lihat ruangan di gedung {{ Str::limit($gedung->nama_gedung, 30) }}
            </a>
        </div>
        <div class="modal-footer mt-4">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</div>
