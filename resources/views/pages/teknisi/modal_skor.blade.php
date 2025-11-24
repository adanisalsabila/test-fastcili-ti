<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-0 shadow modal-scrollable">
        <div class="modal-header">
            <h5 class="modal-title">Daftar Teknisi dan Skor Kinerja</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>Nama Teknisi</th>
                            <th class="text-center">Skor Kinerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teknisi as $i => $t)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $t->user->nama }}</td>
                                <td class="text-center ">
                                    @php
                                        $score = $t->credit_score;
                                        $badgeClass = 'badge-danger ';
                                        if ($score == 100) {
                                            $badgeClass = 'badge-success';
                                        } elseif ($score > 75) {
                                            $badgeClass = 'badge-warning text-white';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}"
                                        style="font-size: 1.1rem; padding: 0.5rem 0.9rem;">
                                        {{ number_format($score) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer bg-light">
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                <i class="fas fa-times mr-1"></i> Tutup
            </button>
        </div>

    </div>
</div>
