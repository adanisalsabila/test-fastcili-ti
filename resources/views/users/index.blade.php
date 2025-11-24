@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'user',
])

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Kelola Data User</h3>
            <div class="card-tools">
                <div class="card-tools d-flex justify-content-center flex-wrap">
                    <button onclick="modalAction('{{ url('/users/import') }}')" class="btn btn-warning mr-2">
                        Impor Data User
                    </button>
                    <button onclick="modalAction('{{ url('/users/create') }}')" class="btn btn-success">
                        Tambah Data User
                    </button>
                </div>
            </div>
        </div>
        <div class="card p-4">
            <div class="card-body">
                {{-- Filtering --}}
                <div class="row pr-auto">
                    <div class="col-md-12">
                        <div class="form-group row mb-5">
                            <label class="col-2 control-label col-form-label">Filter Level :</label>
                            <div class="col-4">
                                <select class="form-control" id="id_level" name="id_level" required>
                                    <option value="">- Semua Level -</option>
                                    @foreach ($level as $item)
                                        <option value="{{ $item->id_level }}">{{ $item->nama_level }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-row-bordered" id="table_user">
                        <thead>
                            <tr>
                                <th width="10%">Profil</th>
                                <th width="20%">Nama</th>
                                <th width="25%">Email</th>
                                <th width="20%">Level User</th>
                                <th width="10%">Akses</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="true" data-keyboard="false"
        aria-hidden="true"></div>
@endsection

@push('scripts')
    <script>
        var dataUser;
        $(document).ready(function() {
            // Preselect filter option if exists in URL
            var urlParams = new URLSearchParams(window.location.search);
            var selectedLevel = urlParams.get('id_level');
            if (selectedLevel) {
                $('#id_level').val(selectedLevel);
            }

            $('#id_level').on('change', function() {
                var selectedLevel = $(this).val();
                var currentUrl = window.location.href.split('?')[0];
                var newUrl = currentUrl;

                if (selectedLevel !== "") {
                    newUrl += '?id_level=' + selectedLevel;
                }

                window.location.href = newUrl;
            });

            dataUser = $('#table_user').DataTable({
                processing: true,
                serverSide: false, // karena kita menggunakan server-side processing sederhana
                ajax: {
                    url: window.location.href,
                    data: function(d) {
                        d.id_level = $('#id_level').val();
                    }
                },
                columns: [{
                        data: 'profil',
                        name: 'profil',
                        className: 'text-center'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        className: 'text-center'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        className: 'text-center'
                    },
                    {
                        data: 'level.nama_level',
                        name: 'level.nama_level',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return data ? data : '--';
                        }
                    },
                    {
                        data: 'akses',
                        name: 'akses',
                        className: 'text-center'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        className: 'text-center'
                    }
                ],
                columnDefs: [{
                    targets: [0, 3, 4, 5],
                    orderable: false,
                    searchable: false
                }],
                language: {
                    emptyTable: "<i class='fas fa-info-circle'></i> Tidak ada data user yang tersedia",
                    zeroRecords: "<i class='fas fa-info-circle'></i> Tidak ada data user seperti keyword yang ingin dicari"
                }
            });

            // Ubah event change filter
            $('#id_level').on('change', function() {
                dataUser.ajax.reload();
            });
        });

        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        function toggleAccess(url) {
            // Extract ID from URL
            const id = url.split('/').pop();
            const currentAccess = $(`button[onclick*="${id}"]`).hasClass('btn-success') ? 0 : 1;

            Swal.fire({
                title: 'Konfirmasi',
                text: `Anda yakin ingin ${currentAccess ? 'menonaktifkan' : 'mengaktifkan'} akses ini?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'POST'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses!',
                                    text: response.message,
                                    timer: 4000,
                                    showConfirmButton: true
                                }).then(() => {
                                    dataUser.ajax.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMsg = 'Terjadi kesalahan saat memproses permintaan';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMsg
                            });
                        }
                    });
                }
            });
        }

        $(document).on('submit', '.form-delete', function(e) {
            e.preventDefault();
            let form = this;
            let url = $(this).data('url');

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
                        url: $(this).attr('action'),
                        data: $(this).serialize(),
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
                                dataUser.ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal!",
                                    text: response.message,
                                });
                            }
                        },
                        error: function(xhr) {
                            let response = xhr.responseJSON;
                            if (response && response.msgField) {
                                let errors = response.msgField;
                                $.each(errors, function(field, message) {
                                    $('#error-' + field).text(message[0]);
                                });
                            } else {
                                let errorMsg = response && response.message ?
                                    response.message :
                                    'Terjadi kesalahan saat menghapus data';
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal!",
                                    text: errorMsg,
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
