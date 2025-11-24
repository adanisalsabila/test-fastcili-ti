@extends('layouts.app', [
    'class' => 'register-page',
])

@section('content')
    <div class="wrapper">
        <!-- Background Video-->
        <video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
            <source src="{{ asset('mp4/bg-video.mp4') }}" type="video/mp4" />
        </video>

        <!-- Gradient Overlay -->
        <div class="gradient-overlay"></div>

        <div class="container-login">
            <div class="row">
                <!-- Terms Column - Hidden on small screens -->
                <div class="col-lg-7 col-md-5 d-none d-md-block ml-auto">
                    <h2 class="description info-title mb-2 text-white">Syarat dan Ketentuan</h2>
                    <div class="terms info-horizontal">
                        <div class="description">
                            <h5 class="info-title mb-0 text-white">1. Akses dan penggunaan</h5>
                            <p class="description text-white">
                            <ul class="text-white">
                                <li>Sistem ini hanya dapat digunakan oleh civitas akademika Politeknik Negeri Malang,
                                    termasuk mahasiswa, dosen, dan staf.</li>
                                <li>Pengguna wajib menjaga kerahasiaan kredensial.</li>
                                <li>Penyalahgunaan sistem untuk laporan palsu, spam, atau informasi tidak relevan akan
                                    dikenakan sanksi sesuai ketentuan kampus.</li>
                            </ul>
                            </p>
                        </div>
                    </div>
                    <div class="terms info-horizontal">
                        <div class="description">
                            <h5 class="info-title mb-0 text-white">2. Tanggung Jawab Pengguna</h5>
                            <p class="description text-white">
                            <ul class="text-white">
                                <li>Pengguna bertanggung jawab atas kebenaran dan keakuratan informasi yang dilaporkan.</li>
                                <li>Setiap laporan yang dikirim akan melalui proses validasi dan dapat ditindaklanjuti oleh
                                    Tim Sarana dan Prasarana (Sarpras) kampus.</li>
                                <li>Pengguna dilarang mengunggah konten yang bersifat ofensif, diskriminatif, atau melanggar
                                    hukum.</li>
                            </ul>
                            </p>
                        </div>
                    </div>
                    <div class="terms info-horizontal">
                        <div class="description">
                            <h5 class="info-title mb-0 text-white">3. Hak dan Batasan</h5>
                            <p class="description text-white">
                            <ul class="text-white">
                                <li>Tim pengembang berhak memperbarui sistem, memperbaiki bug, atau menambahkan fitur baru
                                    tanpa pemberitahuan terlebih dahulu.</li>
                                <li>Pengembang tidak bertanggung jawab atas kerugian akibat penggunaan sistem yang tidak
                                    sesuai dengan ketentuan ini.</li>
                            </ul>
                            </p>
                        </div>
                    </div>
                    <div class="terms info-horizontal">
                        <p class="mt-2 description text-end text-white"><small>Terakhir diperbarui:
                                {{ now()->format('d F Y') }}</small></p>
                    </div>
                </div>

                <!-- Register Form Column - Full width on small screens -->
                <div class="col-lg-4 col-md-6 col-12 mx-auto">
                    <div class="card card-signup text-center bg-white">
                        <div class="card-header bg-white border-0 pb-0">
                            <h4 class="card-title text-dark">{{ __('Register') }}</h4>
                            <!-- Show terms toggle button only on small screens -->
                            <button class="btn btn-link d-md-none terms-toggle text-dark" type="button"
                                data-toggle="collapse" data-target="#termsCollapse">
                                Lihat Syarat & Ketentuan
                            </button>
                        </div>

                        <!-- Collapsible terms for mobile -->
                        <div class="collapse d-md-none" id="termsCollapse">
                            <div class="card-body terms-mobile text-left bg-light m-3 rounded">
                                <button type="button" class="close text-dark" data-toggle="collapse"
                                    data-target="#termsCollapse" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="info-title mb-2 text-dark">Syarat dan Ketentuan</h5>
                                <div class="description">
                                    <h6 class="info-title mb-0 text-dark">1. Akses dan penggunaan</h6>
                                    <ul class="small text-dark">
                                        <li>Sistem ini hanya dapat digunakan oleh civitas akademika Politeknik Negeri
                                            Malang.</li>
                                        <li>Pengguna wajib menjaga kerahasiaan kredensial.</li>
                                        <li>Penyalahgunaan sistem akan dikenakan sanksi.</li>
                                    </ul>
                                </div>
                                <div class="description">
                                    <h6 class="info-title mb-0 text-dark">2. Tanggung Jawab Pengguna</h6>
                                    <ul class="small text-dark">
                                        <li>Pengguna bertanggung jawab atas kebenaran informasi.</li>
                                        <li>Laporan akan divalidasi oleh Tim Sarpras.</li>
                                        <li>Dilarang mengunggah konten melanggar hukum.</li>
                                    </ul>
                                </div>
                                <div class="description">
                                    <h6 class="info-title mb-0 text-dark">3. Hak dan Batasan</h6>
                                    <ul class="small text-dark">
                                        <li>Tim pengembang berhak memperbarui sistem.</li>
                                        <li>Tidak bertanggung jawab atas penggunaan tidak sesuai.</li>
                                    </ul>
                                </div>
                                <p class="small text-muted">Terakhir diperbarui: {{ now()->format('d F Y') }}</p>
                                <button class="btn btn-info btn-sm btn-block mt-3 rounded-pill" data-toggle="collapse"
                                    data-target="#termsCollapse">Kembali ke Registrasi</button>
                            </div>
                        </div>

                        <div class="card-body bg-white" id="registerForm">
                            <form class="form" method="POST" action="{{ route('register') }}" id="form-register">
                                @csrf
                                <div class="input-group{{ $errors->has('nama') ? ' has-danger' : '' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0">
                                            <i class="nc-icon nc-single-02 text-dark"></i>
                                        </span>
                                    </div>
                                    <input name="nama" type="text" class="form-control border-left-0"
                                        placeholder="Nama Lengkap" value="{{ old('nama') }}" required autofocus>
                                    @if ($errors->has('nama'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('nama') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="input-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0">
                                            <i class="nc-icon nc-email-85 text-dark"></i>
                                        </span>
                                    </div>
                                    <input name="email" type="email" class="form-control border-left-0"
                                        placeholder="Email" required value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="input-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0">
                                            <i class="nc-icon nc-key-25 text-dark"></i>
                                        </span>
                                    </div>
                                    <input name="password" type="password" class="form-control border-left-0"
                                        placeholder="Password" required>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0">
                                            <i class="nc-icon nc-key-25 text-dark"></i>
                                        </span>
                                    </div>
                                    <input name="password_confirmation" type="password"
                                        class="form-control border-left-0" placeholder="Konfirmasi Password" required>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-check text-left mt-3 pl-0">
                                    <label class="form-check-label text-dark">
                                        <input class="form-check-input" type="checkbox"
                                            name="agree_terms_and_conditions" value="1"
                                            {{ old('agree_terms_and_conditions') ? 'checked' : '' }}>
                                        <span class="form-check-sign"></span>
                                        Saya setuju dengan
                                        <a href="#" class="text-info" data-toggle="modal" data-target="#termsModal">
                                            Syarat dan Ketentuan
                                        </a>
                                    </label>
                                    @if ($errors->has('agree_terms_and_conditions'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('agree_terms_and_conditions') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="card-footer bg-white border-0 pt-0">
                                    <button type="submit"
                                        class="btn btn-info btn-round btn-block">{{ __('Register') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Icons -->
        <div class="social-icons">
            <a class="social-btn" target="_blank" href="https://api.whatsapp.com/send/?phone=6285105120605">
                <i class="fab fa-whatsapp text-white"></i>
            </a>
            <a class="social-btn" target="_blank"
                href="https://www.notion.so/Daily-Task-Fastcili-TI-Project-1e9218774ea6800fa360d1f6de6b05bd?pvs=4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
                    <path fill="white"
                        d="M44.62 13.13c-.23-.21-.52-.33-.83-.33-.02 0-.05.01-.08.01l-29.86 1.92c-.63.04-1.13.58-1.13 1.21v28.75c0 .34.14.65.38.88.25.23.57.35.91.33l29.86-1.93C44.51 43.93 45 43.4 45 42.76V14.02C45 13.68 44.87 13.36 44.62 13.13zM38.11 20.92c-.6.19-.79.2-.79.2v17.24c-1.02.55-1.86.81-2.74.81-1.07 0-1.68-.24-2.5-1.5-1.74-2.69-7.41-11.81-7.41-11.81v11.45l2.23.47c0 0-.06 1.3-2.01 1.45-1.71.13-5.44.32-5.44.32 0-.47.1-1.12.84-1.31.35-.09 1.4-.37 1.4-.37V22.42h-2.24c0-1.03.3-1.83 1.38-1.91l5.79-.33 7.73 11.92V21.49l-2.24-.19c0-.93.9-1.5 1.67-1.58l5.04-.28C38.82 20.09 38.79 20.7 38.11 20.92zM4.98 8.54l5.74 5.74v29.54L5.6 37.66c-.41-.58-.62-1.25-.62-1.96V8.54zM42.72 10.91l-29.06 1.83c-.99.07-1.95-.3-2.65-.99L6.24 6.97l27.19-1.89c.81-.07 1.62.17 2.28.66L42.72 10.91z">
                    </path>
                </svg>
            </a>
            <a class="social-btn" href="https://github.com/koctaa04/PBL_Fastcili-TI">
                <i class="fab fa-github text-white"></i>
            </a>
        </div>
    @endsection

    @push('scripts')
        <script>
            $('#form-register').submit(function(e) {
                e.preventDefault(); // hentikan submit form default

                var form = $(this);
                var url = form.attr('action');
                var formData = form.serialize();

                // Bersihkan error sebelumnya
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Registrasi Berhasil!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = "{{ route('login') }}"; // redirect ke login
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            let errorList = [];

                            $.each(errors, function(key, messages) {
                                var input = $('[name="' + key + '"]');
                                if (key === 'agree_terms_and_conditions') {
                                    input.closest('.form-check').find('.form-check-label').addClass(
                                        'is-invalid');
                                    input.closest('.form-check').append(
                                        '<span class="invalid-feedback" style="display:block;"><strong>' +
                                        messages[0] + '</strong></span>');
                                } else {
                                    input.addClass('is-invalid');
                                    input.after(
                                        '<span class="invalid-feedback" style="display:block;"><strong>' +
                                        messages[0] + '</strong></span>');
                                }
                                errorList.push(messages[0]);
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                html: '<ul style="text-align: left;">' + errorList.map(e =>
                                    `<li>${e}</li>`).join('') + '</ul>',
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: xhr.responseJSON.message,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: 'Silakan coba lagi nanti.',
                            });
                        }
                    }

                });
            });
        </script>
        <script>
            $(document).ready(function() {
                // Hide register form when terms are shown on mobile
                $('#termsCollapse').on('show.bs.collapse', function() {
                    $('#registerForm').slideUp(300);
                });

                // Show register form when terms are hidden on mobile
                $('#termsCollapse').on('hide.bs.collapse', function() {
                    $('#registerForm').slideDown(300);
                });
            });
        </script>
    @endpush