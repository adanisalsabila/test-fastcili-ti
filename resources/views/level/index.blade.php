@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'level',
])

@section('content')
    <div class="content">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Kelola Data Level</h3>
            <div class="card-tools">
                <button onclick="modalAction('{{ url('/level/create') }}')" class="btn btn-success">
                    Tambah Data Level
                </button>
            </div>
        </div>
        <div class="card p-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-row-bordered" id="table_level">
                        <thead>
                            <tr>
                                <th width="10%">#</th>
                                <th width="30%">Kode Level</th>
                                <th width="45%">Nama Level</th>
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
        var dataLevel;
        $(document).ready(function() {
            dataLevel = $('#table_level').DataTable({
                processing: true,
                serverSide: true, 
                ajax: {
                    url: window.location.href,
                    data: function(d) {

                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'text-center'
                    },
                    {
                        data: 'kode_level',
                        className: 'text-center'
                    },
                    {
                        data: 'nama_level',
                        className: 'text-center'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        className: 'text-center'
                    }
                ],
                columnDefs: [{
                    targets: [0, 1],
                    orderable: false,
                    searchable: true
                }],
                language: {
                    emptyTable: "<i class='fas fa-info-circle'></i> Tidak ada data level yang tersedia",
                    zeroRecords: "<i class='fas fa-info-circle'></i> Tidak ada data level seperti keyword yang ingin dicari"
                }
            });

            $("#form-delete").validate({
                rules: {},
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataLevel.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        },
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });

        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }


        $(document).on('submit', '.form-delete', function(e) {
            e.preventDefault(); // Cegah submit form langsung
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
                                dataLevel.ajax.reload();
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
