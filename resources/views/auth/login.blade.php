@extends('layouts.app', [
    'class' => 'login-page',
])

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <div class="wrapper">
        <!-- Background Video-->
        <video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
            <source src="{{ asset('mp4/bg-video.mp4') }}" type="video/mp4" />
        </video>

        <!-- Gradient Overlay -->
        <div class="gradient-overlay"></div>

        <div class="container-login">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                <form class="form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="card card-login">
                        <div class="card-header ">
                            <div class="card-header ">
                                <h3 class="header text-center">{{ __('Login') }}</h3>
                                <p class="text-center">Masukkan email dan password dari akun yang sudah terdaftar untuk
                                    mengakses sistem</p>
                            </div>
                        </div>

                        <div class="card-body ">
                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="nc-icon nc-single-02"></i>
                                    </span>
                                </div>
                                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    placeholder="{{ __('Email') }}" type="email" name="email"
                                    value="{{ old('email', Cookie::get('remember_email')) }}" required autofocus>
                            </div>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="nc-icon nc-key-25"></i>
                                    </span>
                                </div>
                                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    name="password" placeholder="{{ __('Password') }}" type="password"
                                    value="{{ Cookie::get('remember_password') }}" required>

                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" name="remember" type="checkbox" value="1"
                                            {{ old('remember') || Cookie::get('remember_email') ? 'checked' : '' }}>
                                        <span class="form-check-sign"></span>
                                        {{ __('Remember me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="text-center">
                                <button type="submit" class="btn btn-warning btn-round mb-3">{{ __('Sign in') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
                <a href="{{ route('password.request') }}" class="btn btn-add">
                    {{ __('Forgot password') }}
                </a>
                <a href="{{ route('register') }}" class="btn btn-add float-right">
                    {{ __('Create Account') }}
                </a>
            </div>
        </div>
    </div>
    <!-- Social Icons -->
    <div class="social-icons">
        {{-- Tombol untuk menampilkan Kontak --}}
        <a class="social-btn" target="_blank" href="https://api.whatsapp.com/send/?phone=6285105120605"><i
                class="fab fa-whatsapp"></i></a>
        <a class="social-btn" target="_blank"
            href="https://www.notion.so/Daily-Task-Fastcili-TI-Project-1e9218774ea6800fa360d1f6de6b05bd?pvs=4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
                <path
                    d="M44.62 13.13c-.23-.21-.52-.33-.83-.33-.02 0-.05.01-.08.01l-29.86 1.92c-.63.04-1.13.58-1.13 1.21v28.75c0 .34.14.65.38.88.25.23.57.35.91.33l29.86-1.93C44.51 43.93 45 43.4 45 42.76V14.02C45 13.68 44.87 13.36 44.62 13.13zM38.11 20.92c-.6.19-.79.2-.79.2v17.24c-1.02.55-1.86.81-2.74.81-1.07 0-1.68-.24-2.5-1.5-1.74-2.69-7.41-11.81-7.41-11.81v11.45l2.23.47c0 0-.06 1.3-2.01 1.45-1.71.13-5.44.32-5.44.32 0-.47.1-1.12.84-1.31.35-.09 1.4-.37 1.4-.37V22.42h-2.24c0-1.03.3-1.83 1.38-1.91l5.79-.33 7.73 11.92V21.49l-2.24-.19c0-.93.9-1.5 1.67-1.58l5.04-.28C38.82 20.09 38.79 20.7 38.11 20.92zM4.98 8.54l5.74 5.74v29.54L5.6 37.66c-.41-.58-.62-1.25-.62-1.96V8.54zM42.72 10.91l-29.06 1.83c-.99.07-1.95-.3-2.65-.99L6.24 6.97l27.19-1.89c.81-.07 1.62.17 2.28.66L42.72 10.91z">
                </path>
            </svg>
        </a>
        <a class="social-btn" href="https://github.com/koctaa04/PBL_Fastcili-TI"><i class="fab fa-github"></i></a>
    </div>
@endsection
