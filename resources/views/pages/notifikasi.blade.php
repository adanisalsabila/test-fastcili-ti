@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
])

@section('content')
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Riwayat Notifikasi</h4>
                        <div class="d-flex align-items-center gap-2">
                            @if($notifications->count() > 0)
                                <form action="{{ route('notifications.deleteAll') }}" method="POST" id="delete-all-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus Semua</button>
                                </form>
                                <a class="btn btn-primary btn-sm" href="#" onclick="markAllNotificationsAsRead()">Tandai semua telah dibaca</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse ($notifications as $notification)
                            <div class="card mb-3 shadow-sm {{ is_null($notification->read_at) ? 'border-primary border-2' : 'border' }}">
                                <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                                    <div class="flex-grow-1 mb-2 mb-md-0 me-md-3">
                                        @if (auth()->user()->id_level == 3)
                                            <h5 class="card-title fw-bold text-dark mb-1">
                                                @if (is_null($notification->read_at))
                                                    <span class="badge bg-primary me-2">BARU</span>
                                                @endif
                                                {{ $notification->data['fasilitas'] ?? 'Notifikasi Baru' }}
                                            </h5>
                                            <p class="card-text text-muted mb-1">
                                                {{ $notification->data['keterangan'] ?? '-' }}
                                            </p>
                                        @elseif (auth()->user()->id_level == 2)
                                            <h5 class="card-title fw-bold text-dark mb-1">
                                                @if (is_null($notification->read_at))
                                                    <span class="badge bg-primary me-2">BARU</span>
                                                @endif
                                                {{ $notification->data['tipe'] ?? 'Notifikasi Baru' }}
                                            </h5>
                                            <p class="card-text text-muted mb-1">
                                                {{ $notification->data['pesan'] ?? '-' }}
                                            </p>
                                        @elseif (in_array(auth()->user()->id_level, [4, 5, 6]))
                                            <h5 class="card-title fw-bold text-dark mb-1">
                                                @if (is_null($notification->read_at))
                                                    <span class="badge bg-primary me-2">BARU</span>
                                                @endif
                                                {{ $notification->data['fasilitas'] ?? 'Notifikasi Baru' }}
                                            </h5>
                                            <p class="card-text text-muted mb-1">
                                                {{ $notification->data['status'] ?? '-' }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column flex-md-row gap-2">
                                        <button onclick="modalAction('{{ $notification->data['link'] ?? '#' }}')" class="btn btn-info btn-sm">Lihat Detail</button>

                                        @if (is_null($notification->read_at))
                                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="mark-as-read-form d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary btn-sm">Tandai Dibaca</button>
                                            </form>
                                        @endif

                                        <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" class="delete-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted">Tidak ada notifikasi.</p>
                        @endforelse

                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="false" aria-hidden="true"></div>
@endsection

@push('scripts')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        // AJAX: Tandai sebagai dibaca
        $(document).on('submit', '.mark-as-read-form', function (e) {
            e.preventDefault();
            const form = $(this);
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil!",
                            text: response.message,
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        alert('Gagal menandai notifikasi.');
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: "Terjadi kesalahan.",
                    });
                }
            });
        });

        // AJAX: Hapus satu notifikasi
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            const form = $(this);
            Swal.fire({
                title: 'Yakin ingin menghapus notifikasi ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: form.attr('action'),
                        data: form.serialize(),
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus notifikasi.',
                            });
                        }
                    });
                }
            });
        });

        // AJAX: Hapus semua notifikasi
        $(document).on('submit', '#delete-all-form', function (e) {
            e.preventDefault();
            const form = $(this);
            Swal.fire({
                title: 'Yakin ingin menghapus semua notifikasi?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus semua!',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: form.attr('action'),
                        data: form.serialize(),
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus semua notifikasi.',
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
