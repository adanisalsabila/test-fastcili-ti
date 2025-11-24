<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <a href="#" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="{{ asset('logo-round.png') }}">
            </div>
        </a>
        <a href="#" class="simple-text logo-normal">
            {{ __('Fastcili-TI') }}
        </a>
    </div>
    <div class="sidebar-wrapper" style="display: flex; flex-direction: column; height: 100%; overflow-x: hidden;">
        <ul class="nav"
            style="flex: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none;">
            <style>
                .nav::-webkit-scrollbar {
                    display: none;
                    width: 0;
                    height: 0;
                }
            </style>
            <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                @if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2)
                    <a href="{{ route('home') }}">
                        <i class="nc-icon nc-sun-fog-29"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                @elseif (auth()->user()->id_level == 3)
                    <a href="{{ route('teknisi') }}">
                        <i class="nc-icon nc-sun-fog-29"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                @else
                    <a href="{{ route('pelapor') }}">
                        <i class="nc-icon nc-sun-fog-29"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                @endif
            </li>

            {{-- Kelola Pengguna --}}
            @if (auth()->user()->id_level == 1)
                <li class="{{ $elementActive == 'user' || $elementActive == 'level' ? 'active' : '' }}">
                    <a data-toggle="collapse" aria-expanded="false" href="#kelolaPengguna">
                        <i class="nc-icon nc-single-02"></i>
                        <p>
                            {{ __('Kelola Pengguna') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ $elementActive == 'user' || $elementActive == 'level' ? 'show' : '' }}"
                        id="kelolaPengguna">
                        <ul class="nav">
                            <li class="{{ $elementActive == 'level' ? 'active' : '' }}">
                                <a href="{{ route('level.index') }}">
                                    <span class="sidebar-mini-icon">{{ __('L') }}</span>
                                    <span class="sidebar-normal">{{ __(' Level ') }}</span>
                                </a>
                            </li>
                            <li class="{{ $elementActive == 'user' ? 'active' : '' }}">
                                <a href="{{ route('users.index') }}">
                                    <span class="sidebar-mini-icon">{{ __('U') }}</span>
                                    <span class="sidebar-normal">{{ __(' User ') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif

            {{-- Fasilitas --}}
            <li
                class="{{ $elementActive == 'gedung' || $elementActive == 'fasilitas' || $elementActive == 'ruangan' ? 'active' : '' }}">
                <a data-toggle="collapse" aria-expanded="false" href="#fas">
                    <i class="nc-icon nc-bank"></i>
                    <p>
                        {{ __('Fasilitas') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse {{ $elementActive == 'gedung' || $elementActive == 'fasilitas' || $elementActive == 'ruangan' ? 'show' : '' }}"
                    id="fas">
                    <ul class="nav">
                        <li class="{{ $elementActive == 'fasilitas' ? 'active' : '' }}">
                            <a href="{{ route('fasilitas.index') }}">
                                <span class="sidebar-mini-icon">{{ __('F') }}</span>
                                <span class="sidebar-normal">{{ __(' Fasilitas ') }}</span>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'gedung' ? 'active' : '' }}">
                            <a href="{{ route('gedung.index') }}">
                                <span class="sidebar-mini-icon">{{ __('G') }}</span>
                                <span class="sidebar-normal">{{ __(' Gedung ') }}</span>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'ruangan' ? 'active' : '' }}">
                            <a href="{{ route('ruangan.index') }}">
                                <span class="sidebar-mini-icon">{{ __('R') }}</span>
                                <span class="sidebar-normal">{{ __(' Ruangan ') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Laporan --}}
            @if (auth()->user()->id_level != 3)
                <li
                    class="{{ $elementActive == 'periode' || $elementActive == 'lapor_kerusakan' || $elementActive == 'trending' || $elementActive == 'prioritas' ? 'active' : '' }}">
                    <a data-toggle="collapse" aria-expanded="false" href="#laporan">
                        <i class="nc-icon nc-single-copy-04"></i>
                        <p>
                            {{ __('Laporan') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ $elementActive == 'periode' || $elementActive == 'lapor_kerusakan' || $elementActive == 'trending' || $elementActive == 'prioritas' ? 'show' : '' }}"
                        id="laporan">
                        <ul class="nav">
                            <li class="{{ $elementActive == 'lapor_kerusakan' ? 'active' : '' }}">
                                <a href="{{ route('perbaikan.index') }}">
                                    <span class="sidebar-mini-icon">{{ __('LK') }}</span>
                                    <span class="sidebar-normal">{{ __(' Laporan Kerusakan ') }}</span>
                                </a>
                            </li>
                            <li class="{{ $elementActive == 'trending' ? 'active' : '' }}">
                                <a href="{{ route('trending.index') }}">
                                    <span class="sidebar-mini-icon">{{ __('LT') }}</span>
                                    <span class="sidebar-normal">{{ __(' Laporan Trending ') }}</span>
                                </a>
                            </li>
                            @if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2)
                                <li class="{{ $elementActive == 'periode' ? 'active' : '' }}">
                                    <a href="{{ route('periode.index') }}">
                                        <span class="sidebar-mini-icon">{{ __('PE') }}</span>
                                        <span class="sidebar-normal">{{ __(' Periode & Ekspor Data ') }}</span>
                                    </a>
                                </li>
                                <li class="{{ $elementActive == 'prioritas' ? 'active' : '' }}">
                                    <a href="{{ route('prioritas.index') }}">
                                        <span class="sidebar-mini-icon">{{ __('PP') }}</span>
                                        <span class="sidebar-normal">{{ __(' Prioritas Perbaikan ') }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            {{-- Teknisi --}}
            @if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2 || auth()->user()->id_level == 3)
                <li class="{{ $elementActive == 'perbaikan_teknisi' || $elementActive == 'riwayat_perbaikan' ? 'active' : '' }}">
                    <a data-toggle="collapse" aria-expanded="false" href="#teknisi">
                        <i class="nc-icon nc-settings"></i>
                        <p>
                            {{ __('Teknisi') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ $elementActive == 'perbaikan_teknisi' || $elementActive == 'riwayat_perbaikan' ? 'show' : '' }}"
                        id="teknisi">
                        <ul class="nav">
                            <li class="{{ $elementActive == 'perbaikan_teknisi' ? 'active' : '' }}">
                                <a href="{{ route('perbaikan_teknisi.index') }}">
                                    <span class="sidebar-mini-icon">{{ __('DP') }}</span>
                                    <span class="sidebar-normal">{{ __(' Daftar Perbaikan ') }}</span>
                                </a>
                            </li>
                            <li class="{{ $elementActive == 'riwayat_perbaikan' ? 'active' : '' }}">
                                <a href="{{ route('riwayat_perbaikan.index') }}">
                                    <span class="sidebar-mini-icon">{{ __('RP') }}</span>
                                    <span class="sidebar-normal">{{ __(' Riwayat Perbaikan ') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            <!-- Logout Button -->
            <div style="margin-top: auto; padding-top: 20px;">
                <li class="active-pro {{ $elementActive == 'tables' ? 'active' : '' }}">
                    <form class="dropdown-item" action="{{ route('logout') }}" id="formLogOut" method="POST"
                        style="display: none;">
                        @csrf
                    </form>
                    <a onclick="confirmLogout(event)" class="bg-danger" style="display: block; margin-bottom: 20px;">
                        <i class="nc-icon nc-button-power text-white"></i>
                        <p class="text-white">{{ __('Log out') }}</p>
                    </a>
                </li>
            </div>
            <script>
                function confirmLogout(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Log out',
                        text: "Apakah Anda yakin ingin keluar?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Keluar!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('formLogOut').submit();
                        }
                    });
                }
            </script>
        </ul>
    </div>
</div>
