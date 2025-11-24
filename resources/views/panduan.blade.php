@extends('layouts.app', [
    'class' => 'panduan-page',
    'elementActive' => ''
])

@section('content')
<div class="container mt-content">
    <!-- Halaman Utama -->
    <section class="panduan-section">
        <div class="text-center">
            <div class="panduan-wrapper mb-5">
                <h2>Selamat Datang di</h2>
                <h1>Fastcili-TI <i class="fas fa-building"></i></h1>
                <p class="subtitle">
                    Fastcili-TI adalah sistem manajemen pelaporan dan perbaikan fasilitas kampus berbasis web yang bertujuan untuk memudahkan mahasiswa, dosen, dan staf dalam melaporkan kerusakan fasilitas serta membantu bagian sarana dan prasarana ataupun pihak terkait dalam mengelola, memantau, dan menindaklanjuti perbaikan fasilitas yang akan menunjang kegiatan pembelajaran.
                </p>
            </div>
        </div>
    </section>

    <div class="card-nav-tabs card-plain">
        <div class="card-header-primary">
            <div class="nav-tabs-navigation">
                    <ul class="nav nav-tabs center-tabs" data-tabs="tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#admin" data-toggle="tab">Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#pelapor" data-toggle="tab">Pelapor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#sarpras" data-toggle="tab">Sarana Prasarana</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#teknisi" data-toggle="tab">Teknisi</a>
                        </li>
                    </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content text-center">
                <div class="tab-pane fade show active" id="admin">
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-sun-fog-29"></i> <b>Dashboard</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Dashboard menyajikan ringkasan data laporan fasilitas secara real-time, termasuk total laporan masuk, status verifikasi, laporan aktif, dan laporan yang telah selesai, lengkap dengan grafik visual jumlah laporan per bulan untuk memudahkan pemantauan dan analisis.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/dashboard.jpg') }}" alt="Dashboard Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-single-02"></i> <b>Level</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman ini digunakan untuk mengelola data level pengguna dalam sistem, seperti Admin, Sarana Prasarana, Teknisi, Mahasiswa, Dosen, dan Tendik. Admin dapat menambah, mengedit, atau menghapus level sesuai kebutuhan manajemen akses pengguna.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/level.png') }}" alt="Level Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-badge"></i> <b>User</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman User digunakan untuk mengelola data pengguna dalam sistem, termasuk menambahkan, mengedit, menghapus, mengimpor data dari file Excel, serta mengatur status aktif atau nonaktif pengguna berdasarkan level akses masing-masing.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/user.png') }}" alt="User Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-box"></i> <b>Fasilitas</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman Fasilitas digunakan untuk mengelola data fasilitas yang tersedia di setiap ruangan dan gedung, termasuk menambahkan, mengedit, menghapus, mencari, memfilter, serta mengimpor data fasilitas dari file Excel.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/fasilitas.png') }}" alt="Fasilitas Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-bank"></i> <b>Gedung</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman ini memungkinkan admin untuk menambahkan, mencari, dan mengelola daftar gedung yang terdaftar di sistem, lengkap dengan informasi kode dan deskripsi tiap gedung.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/gedung.png') }}" alt="Gedung Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-shop"></i> <b>Ruangan</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman ini digunakan admin untuk mengelola data ruangan yang tersedia, seperti menambahkan, mengedit, menghapus, dan mencari ruangan berdasarkan gedung.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/ruangan.png') }}" alt="Ruangan Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-single-copy-04"></i> <b>Laporan Kerusakan</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman ini menyajikan data laporan kerusakan dalam format tabel dinamis. Setiap baris mencakup atribut seperti foto kerusakan, jenis fasilitas, gedung, ruangan, pelapor, deskripsi, status, dan tanggal. Admin dapat melakukan tindakan administratif seperti penghapusan data.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/laporankerusakan.png') }}" alt="Laporan Kerusakan Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-paper"></i> <b>Prioritas Perbaikan</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman tersebut menampilkan daftar laporan kerusakan fasilitas yang telah melalui proses verifikasi dan diurutkan berdasarkan tingkat urgensi perbaikannya. Laporan-laporan ini diprioritaskan menggunakan sistem ranking, sehingga memudahkan pihak terkait dalam menentukan fasilitas mana yang harus segera ditindaklanjuti dan ditugaskan ke teknisi.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/prioritasPerbaikan.png') }}" alt="Laporan Perbaikan Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-settings"></i> <b>Daftar Perbaikan</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman Daftar Perbaikan menampilkan seluruh data laporan kerusakan fasilitas yang harus ditindaklanjuti. Dalam halaman ini, teknisi dapat melihat informasi penting dari setiap laporan. Selain itu, tersedia kolom status yang menunjukkan progres perbaikan, apakah masih dalam tahap pengecekan, perbaikan, atau telah selesai. Teknisi juga dapat menambahkan catatan teknisi sebagai dokumentasi pekerjaan, serta mengunggah dokumentasi perbaikan.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/laporanPerbaikan.png') }}" alt="Daftar Perbaikan Panduan">
                            </div>
                        </section>
                    </div>
                </div>
                <div class="tab-pane fade" id="pelapor">
                    <div class="tab-wrapper">
                        <div class="subtitle">
                            <p>Dalam sistem Fastcili-TI, pengguna yang berperan sebagai pelapor terdiri dari tiga kategori utama, yaitu mahasiswa, tenaga kependidikan (tendik), dan dosen. Ketiganya memiliki akses untuk melaporkan data atau informasi yang relevan sesuai peran masing-masing, seperti pencapaian, kegiatan, maupun kebutuhan administrasi. </p>
                        </div>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-sun-fog-29"></i> <b>Dashboard</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Dashboard pelapor menyajikan ringkasan data pencatatan secara real-time, mencakup total aktivitas atau laporan yang telah dicatat, serta status verifikasi data.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/dashboard.jpg') }}" alt="Dashboard Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-box"></i> <b>Fasilitas</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Menu Fasilitas untuk pelapor menampilkan daftar data fasilitas yang tersedia di lingkungan kampus. Melalui menu ini, pelapor dapat melihat detail fasilitas, termasuk nama, lokasi gedung, lokasi ruangan, dan jumlah fasilitas. Sehingga pengguna dapat memastikan informasi fasilitas yang akan dilaporkan sudah sesuai dan terdata dalam sistem.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/fasilitas.png') }}" alt="Fasilitas Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-bank"></i> <b>Gedung</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman Gedung untuk pelapor berfungsi menampilkan data gedung yang ada di lingkungan jurusan secara lengkap dan terstruktur. Di halaman ini, pelapor dapat melihat informasi penting seperti nama gedung serta jumlah lantai atau ruang yang dimiliki. Fitur ini memudahkan pelapor dalam memastikan lokasi fasilitas sebelum melakukan pelaporan.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/gedung.png') }}" alt="Gedung Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-shop"></i> <b>Ruangan</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman Ruangan untuk pelapor menampilkan daftar lengkap data ruangan yang tersedia di setiap gedung, termasuk nama ruangan dan lokasi gedungnya. Melalui halaman ini, pelapor dapat dengan mudah mengidentifikasi dan memilih ruangan yang relevan sebelum membuat laporan. 
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/ruangan.png') }}" alt="Ruangan Panduan">
                            </div>
                        </section>
                    </div>
                </div>
                <div class="tab-pane fade" id="sarpras">
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-sun-fog-29"></i> <b>Dashboard</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Dashboard menyajikan ringkasan data laporan fasilitas secara real-time, termasuk total laporan masuk, status verifikasi, laporan aktif, dan laporan yang telah selesai, lengkap dengan grafik visual jumlah laporan per bulan untuk memudahkan pemantauan dan analisis.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/dashboard.jpg') }}" alt="Dashboard Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-box"></i> <b>Fasilitas</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman Fasilitas digunakan untuk mengelola data fasilitas yang tersedia di setiap ruangan dan gedung, termasuk menambahkan, mengedit, menghapus, mencari, memfilter, serta mengimpor data fasilitas dari file Excel.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/fasilitas.png') }}" alt="Fasilitas Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-bank"></i> <b>Gedung</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman ini memungkinkan Sarana Prasarana (Sarpras) untuk menambahkan, mencari, dan mengelola daftar gedung yang terdaftar di sistem, lengkap dengan informasi kode dan deskripsi tiap gedung.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/gedung.png') }}" alt="Gedung Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-shop"></i> <b>Ruangan</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman ini digunakan untuk mengelola data ruangan yang tersedia, seperti menambahkan, mengedit, menghapus, dan mencari ruangan berdasarkan gedung.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/ruangan.png') }}" alt="Ruangan Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-single-copy-04"></i> <b>Laporan Kerusakan</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman ini menyajikan data laporan kerusakan dalam format tabel dinamis. Setiap baris mencakup atribut seperti foto kerusakan, jenis fasilitas, gedung, ruangan, pelapor, deskripsi, status, dan tanggal. Sarana Prasarana dapat melakukan tindakan administratif seperti penghapusan data.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/laporankerusakan.png') }}" alt="Laporan Kerusakan Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-bell-55"></i> <b>Verifikasi Laporan</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman ini digunakan oleh Sarpras untuk memverifikasi laporan kerusakan yang sedang trending berdasarkan jumlah pelapor. Sarpras dapat meninjau deskripsi, jumlah pelapor, dan memberikan penilaian pada laporan untuk menentukan urgensi atau tingkat prioritas penanganan.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/verifikasiLaporan.png') }}" alt="Verifikasi Laporan Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-paper"></i> <b>Prioritas Perbaikan</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman tersebut menampilkan daftar laporan kerusakan fasilitas yang telah melalui proses verifikasi dan diurutkan berdasarkan tingkat urgensi perbaikannya. Laporan-laporan ini diprioritaskan menggunakan sistem ranking, sehingga memudahkan pihak terkait dalam menentukan fasilitas mana yang harus segera ditindaklanjuti dan ditugaskan ke teknisi.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/prioritasPerbaikan.png') }}" alt="Laporan Perbaikan Panduan">
                            </div>
                        </section>
                    </div>
                </div>
                <div class="tab-pane fade" id="teknisi">
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-sun-fog-29"></i> <b>Dashboard</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Dashboard menyajikan ringkasan data laporan fasilitas secara real-time, termasuk total laporan masuk, status verifikasi, laporan aktif, dan laporan yang telah selesai, lengkap dengan grafik visual jumlah laporan per bulan untuk memudahkan pemantauan dan analisis.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/dashboard.jpg') }}" alt="Dashboard Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-box"></i> <b>Fasilitas</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman Fasilitas digunakan untuk mengelola data fasilitas yang tersedia di setiap ruangan dan gedung, termasuk menambahkan, mengedit, menghapus, mencari, memfilter, serta mengimpor data fasilitas dari file Excel.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/fasilitas.png') }}" alt="Fasilitas Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-bank"></i> <b>Gedung</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman ini memungkinkan Taknisi untuk menambahkan, mencari, dan mengelola daftar gedung yang terdaftar di sistem, lengkap dengan informasi kode dan deskripsi tiap gedung.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/gedung.png') }}" alt="Gedung Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-shop"></i> <b>Ruangan</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman ini digunakan untuk mengelola data ruangan yang tersedia, seperti menambahkan, mengedit, menghapus, dan mencari ruangan berdasarkan gedung.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/ruangan.png') }}" alt="Ruangan Panduan">
                            </div>
                        </section>
                    </div>
                    <div class="tab-wrapper">
                        <section class="panduan-flex-section">
                            <div class="panduan-text">
                                <p><i class="nc-icon nc-settings"></i> <b>Daftar Perbaikan</b></p>
                                <p class="mt-2 text-md text-gray-600 text-justify"> Halaman Daftar Perbaikan menampilkan seluruh data laporan kerusakan fasilitas yang harus ditindaklanjuti. Dalam halaman ini, teknisi dapat melihat informasi penting dari setiap laporan. Selain itu, tersedia kolom status yang menunjukkan progres perbaikan, apakah masih dalam tahap pengecekan, perbaikan, atau telah selesai. Teknisi juga dapat menambahkan catatan teknisi sebagai dokumentasi pekerjaan, serta mengunggah dokumentasi perbaikan.
                                </p>
                            </div>
                            <div class="panduan-image">
                                <img class="object-cover w-full h-64 rounded-lg md:h-96" src="{{ asset('img/gambarpanduan/laporanPerbaikan.png') }}" alt="Daftar Perbaikan Panduan">
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="landing/style_panduan.css">
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Hanya jalankan jika elemen navbar ada di halaman
        const navbar = document.querySelector('.navbar.fixed-top');
        if (!navbar) return;

        let lastScrollTop = 0;

        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // Kondisi 1: Saat scroll di paling atas
            if (scrollTop === 0) {
                navbar.classList.remove('navbar-scrolled');
                navbar.classList.remove('navbar-hidden');
            } else {
                // Tambahkan background putih jika belum ada
                navbar.classList.add('navbar-scrolled');

                // Kondisi 2 & 3: Scroll ke bawah (sembunyikan) atau ke atas (tampilkan)
                if (scrollTop > lastScrollTop) {
                    // Scroll ke Bawah
                    navbar.classList.add('navbar-hidden');
                } else {
                    // Scroll ke Atas
                    navbar.classList.remove('navbar-hidden');
                }
            }
            
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // Untuk menghindari bug di mobile
        });
    });
</script>
@endpush