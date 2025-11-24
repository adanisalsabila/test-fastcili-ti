@extends('layouts.app', [
    'class' => 'login-page',
    'elementActive' => ''
])

@section('content')
    <div class="wrapper">
        <!-- Background Video-->
        <video class="bg-video" playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
            <source src="{{ asset('mp4/bg-video.mp4') }}" type="video/mp4" />
        </video>
        
        <!-- Gradient Overlay -->
        <div class="gradient-overlay"></div>
        
        <!-- Main Content Container -->
        <div class="content-container">
            <!-- Default Content -->
            <div class="text-container default-content active">
                <div class="content-box">
                    <h1><i>Fastcili-TI</i></h1>
                    <p>Sistem Manajemen Pelaporan & Perbaikan Fasilitas<br>Kampus Politeknik Negeri Malang</p>
                    <a href="{{ route('login') }}" class="btn-start">Mulai</a>
                </div>
            </div>
            
            <!-- Team Content (Hidden by default) -->
            <div class="text-container team-content">
                <div class="content-box team-box">
                    <h1><i>Tim Pengembang</i></h1>
                    <div class="team-members">
                        <div class="team-member">
                            <div class="member-photo" style="background-image: url('{{ asset('img/team/yeftaa.jpg') }}')"></div>
                            <h3>Yefta Octavianus</h3>
                            <p>Project Manager</p>
                        </div>
                        <div class="team-member">
                            <div class="member-photo" style="background-image: url('{{ asset('img/team/niniss.jpg') }}')"></div>
                            <h3>Annisa Eka Puspita</h3>
                            <p>Frontend Developer</p>
                        </div>
                        <div class="team-member">
                            <div class="member-photo" style="background-image: url('{{ asset('img/team/dwikk.jpg') }}')"></div>
                            <h3>Dwi Ahmad Khairy</h3>
                            <p>Frontend Developer</p>
                        </div>
                        <div class="team-member">
                            <div class="member-photo" style="background-image: url('{{ asset('img/team/fahreza.jpg') }}')"></div>
                            <h3>Muhammad Fahreza</h3>
                            <p>Backend Developer</p>
                        </div>
                        <div class="team-member">
                            <div class="member-photo" style="background-image: url('{{ asset('img/team/odyy.jpg') }}')"></div>
                            <h3>Rafi Ody Prasetyo</h3>
                            <p>Backend Developer</p>
                        </div>
                    </div>
                    <button class="btn-back btn-start">Kembali</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Social Icons -->
    <div class="social-icons">
        {{-- Tombol untuk menampilkan Team --}}
        <a class="social-btn team-btn" href="#"><i class="fas fa-users"></i></a>
        
        {{-- Tombol untuk menampilkan Kontak --}}
        <a class="social-btn" target="_blank" href="https://www.notion.so/Daily-Task-Fastcili-TI-Project-1e9218774ea6800fa360d1f6de6b05bd?pvs=4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
                <path d="M44.62 13.13c-.23-.21-.52-.33-.83-.33-.02 0-.05.01-.08.01l-29.86 1.92c-.63.04-1.13.58-1.13 1.21v28.75c0 .34.14.65.38.88.25.23.57.35.91.33l29.86-1.93C44.51 43.93 45 43.4 45 42.76V14.02C45 13.68 44.87 13.36 44.62 13.13zM38.11 20.92c-.6.19-.79.2-.79.2v17.24c-1.02.55-1.86.81-2.74.81-1.07 0-1.68-.24-2.5-1.5-1.74-2.69-7.41-11.81-7.41-11.81v11.45l2.23.47c0 0-.06 1.3-2.01 1.45-1.71.13-5.44.32-5.44.32 0-.47.1-1.12.84-1.31.35-.09 1.4-.37 1.4-.37V22.42h-2.24c0-1.03.3-1.83 1.38-1.91l5.79-.33 7.73 11.92V21.49l-2.24-.19c0-.93.9-1.5 1.67-1.58l5.04-.28C38.82 20.09 38.79 20.7 38.11 20.92zM4.98 8.54l5.74 5.74v29.54L5.6 37.66c-.41-.58-.62-1.25-.62-1.96V8.54zM42.72 10.91l-29.06 1.83c-.99.07-1.95-.3-2.65-.99L6.24 6.97l27.19-1.89c.81-.07 1.62.17 2.28.66L42.72 10.91z"></path>
            </svg>
        </a>
        <a class="social-btn" href="https://github.com/koctaa04/PBL_Fastcili-TI"><i class="fab fa-github"></i></a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const teamBtn = document.querySelector('.team-btn');
            const backBtn = document.querySelector('.btn-back');
            const defaultContent = document.querySelector('.default-content');
            const teamContent = document.querySelector('.team-content');
            
            teamBtn.addEventListener('click', function(e) {
                e.preventDefault();
                defaultContent.classList.remove('active');
                teamContent.classList.add('active');
            });
            
            backBtn.addEventListener('click', function(e) {
                e.preventDefault();
                teamContent.classList.remove('active');
                defaultContent.classList.add('active');
            });
        });
    </script>
@endsection
