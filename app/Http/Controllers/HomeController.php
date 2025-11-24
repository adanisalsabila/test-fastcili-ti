<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Auth;

use App\Models\Gedung;
use App\Models\StatusLaporan;
use App\Models\PenugasanTeknisi;
use App\Models\KriteriaPenilaian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\PelaporLaporan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */


    public function index(WaspasController $waspasController)
    {
        // Hitung jumlah laporan
        $jmlLaporan = LaporanKerusakan::count();
        $laporanDiajukan = LaporanKerusakan::whereIn('id_status', [1])->count();
        $laporanDiproses = LaporanKerusakan::whereIn('id_status', [2, 3])->count();
        $laporanSelesai = LaporanKerusakan::whereIn('id_status', [4])->count();

        // Data grafik laporan perbulan
        $tahun = now()->year;
        $laporanPerBulan = $this->getLaporanPerBulan($tahun);

        // Data pie chart status laporan
        $statusLaporan = $this->getStatusLaporan();

        // Data grafik laporan per gedung
        $laporanPerGedung = $this->getLaporanPerGedung();

        // Data ranking SPK
        $spkRank = $waspasController->getPrioritas();

        // Debug data yang dikirim ke view
        $debugData = [
            'jmlLaporan' => $jmlLaporan,
            'laporanDiajukan' => $laporanDiajukan,
            'laporanDiproses' => $laporanDiproses,
            'laporanSelesai' => $laporanSelesai,
            'laporanPerBulan' => $laporanPerBulan,
            'statusLaporan' => $statusLaporan,
            'laporanPerGedung' => $laporanPerGedung,
            'spkRank' => $spkRank,
        ];

        return view('pages.dashboard', $debugData);
    }

    // Helper method untuk data laporan perbulan
    public function getLaporanPerBulan($tahun)
    {
        $bulanLengkap = collect([
            'Jan' => 0,
            'Feb' => 0,
            'Mar' => 0,
            'Apr' => 0,
            'May' => 0,
            'Jun' => 0,
            'Jul' => 0,
            'Aug' => 0,
            'Sep' => 0,
            'Oct' => 0,
            'Nov' => 0,
            'Dec' => 0
        ]);

        $dataLaporan = LaporanKerusakan::selectRaw('MONTH(tanggal_lapor) as bulan, COUNT(*) as jumlah')
            ->whereYear('tanggal_lapor', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->pluck('jumlah', 'bulan')
            ->mapWithKeys(function ($jumlah, $bulanAngka) {
                $namaBulan = Carbon::create()->month($bulanAngka)->format('M');
                return [$namaBulan => $jumlah];
            });

        return $bulanLengkap->merge($dataLaporan);
    }

    // Helper method untuk data status laporan
    protected function getStatusLaporan()
    {
        $statusCount = LaporanKerusakan::select('id_status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('id_status')
            ->pluck('jumlah', 'id_status');

        $statusLabel = StatusLaporan::pluck('nama_status', 'id_status');

        return $statusLabel->mapWithKeys(function ($namaStatus, $id) use ($statusCount) {
            return [$namaStatus => $statusCount[$id] ?? 0];
        });
    }

    // Helper method untuk data laporan per gedung
    public function getLaporanPerGedung()
    {
        $countPerGedung = DB::table('laporan_kerusakan')
            ->join('fasilitas', 'laporan_kerusakan.id_fasilitas', '=', 'fasilitas.id_fasilitas')
            ->join('ruangan', 'fasilitas.id_ruangan', '=', 'ruangan.id_ruangan')
            ->join('gedung', 'ruangan.id_gedung', '=', 'gedung.id_gedung')
            ->select('gedung.id_gedung', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('gedung.id_gedung')
            ->pluck('jumlah', 'gedung.id_gedung');

        $gedungLabels = Gedung::pluck('kode_gedung', 'id_gedung');

        return $gedungLabels->mapWithKeys(function ($kodeGedung, $id) use ($countPerGedung) {
            return [$kodeGedung => $countPerGedung[$id] ?? 0];
        });
    }

    public function pelapor()
    {
        $laporanAuth = PelaporLaporan::where('id_user', Auth::id())->get();
        $statusList = PelaporLaporan::where('id_user', Auth::id())
            ->whereNull('rating_pengguna')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.pelapor.index', compact('laporanAuth', 'statusList'));
    }

    public function teknisi()
    {

        $id_user = auth()->user()->id_user;

        $riwayat = PenugasanTeknisi::where('id_user', $id_user
        )->where('status_perbaikan', 'Selesai Dikerjakan')
        ->orderBy('created_at', 'desc')
        ->get();
        // dd($riwayat);
        $penugasan = PenugasanTeknisi::with('laporan')
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();

        //Hitung untuk card
        $jmlPenugasan = PenugasanTeknisi::where('id_user', $id_user)->count();
        $laporanDikerjakan = PenugasanTeknisi::where('status_perbaikan', 'Sedang dikerjakan')
            ->where('id_user', $id_user)
            ->count();
        $laporanDitolak = PenugasanTeknisi::where('status_perbaikan', 'Ditolak')
            ->where('id_user', $id_user)
            ->count();
        $laporanSelesaiDikerjakan = PenugasanTeknisi::where('status_perbaikan', 'Selesai Dikerjakan')
            ->where('id_user', $id_user)
            ->count();

        //Data Grafik Penugasan Per Gedung
        $penugasanPerGedung = $this->getPenugasanPerGedung();

        //Data Grafik Perbaikan Perbulan
        $tahun = now()->year;
        $perbaikanPerBulan = $this->getPerbaikanPerBulan($tahun);

        return view('pages.teknisi.index', compact(
            'riwayat',
            'penugasan',
            'jmlPenugasan',
            'laporanDikerjakan',
            'laporanDitolak',
            'laporanSelesaiDikerjakan',
            'penugasanPerGedung',
            'perbaikanPerBulan'
        ));
    }

    protected function getPenugasanPerGedung()
    {
        $countPerGedung = DB::table('penugasan_teknisi')
            ->join('laporan_kerusakan', 'penugasan_teknisi.id_laporan', '=', 'laporan_kerusakan.id_laporan')
            ->join('fasilitas', 'laporan_kerusakan.id_fasilitas', '=', 'fasilitas.id_fasilitas')
            ->join('ruangan', 'fasilitas.id_ruangan', '=', 'ruangan.id_ruangan')
            ->join('gedung', 'ruangan.id_gedung', '=', 'gedung.id_gedung')
            ->select('gedung.id_gedung', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('gedung.id_gedung')
            ->pluck('jumlah', 'gedung.id_gedung');

        $gedungLabels = Gedung::pluck('kode_gedung', 'id_gedung');

        return $gedungLabels->mapWithKeys(function ($kodeGedung, $id) use ($countPerGedung) {
            return [$kodeGedung => $countPerGedung[$id] ?? 0];
        });
    }

    protected function getPerbaikanPerBulan($tahun)
    {
        $bulanLengkap = collect([
            'Jan' => 0,
            'Feb' => 0,
            'Mar' => 0,
            'Apr' => 0,
            'May' => 0,
            'Jun' => 0,
            'Jul' => 0,
            'Aug' => 0,
            'Sep' => 0,
            'Oct' => 0,
            'Nov' => 0,
            'Dec' => 0
        ]);

        $dataPenugasan = PenugasanTeknisi::selectRaw('MONTH(tanggal_selesai) as bulan, COUNT(*) as jumlah')
            ->whereYear('tanggal_selesai', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->pluck('jumlah', 'bulan')
            ->mapWithKeys(function ($jumlah, $bulanAngka) {
                $namaBulan = Carbon::create()->month($bulanAngka)->format('M');
                return [$namaBulan => $jumlah];
            });

        return $bulanLengkap->merge($dataPenugasan);
    }
}
