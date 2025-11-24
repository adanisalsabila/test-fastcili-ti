<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKerusakan;
use App\Exports\LaporanPeriodeExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PeriodeController extends Controller
{
    /**
     * Menampilkan halaman manajemen periode.
     */
    public function index(Request $request)
    {
        $selectedYear = $request->input('tahun', now()->year);

        // Ambil daftar tahun unik dari data laporan untuk dropdown
        $availableYears = LaporanKerusakan::selectRaw('YEAR(tanggal_lapor) as year')
            ->union(LaporanKerusakan::selectRaw('YEAR(tanggal_selesai) as year'))
            ->whereNotNull('tanggal_lapor')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // 1. Laporan Belum Selesai (id_status != 4), dikelompokkan per bulan
        $laporanBelumSelesai = LaporanKerusakan::with(['fasilitas.ruangan.gedung', 'pelaporLaporan.user', 'status'])
            ->where('id_status', '!=', 4)
            ->whereYear('tanggal_lapor', $selectedYear)
            ->orderBy('tanggal_lapor', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal_lapor->translatedFormat('F Y'); // Grup per Bulan Tahun
            });

        // 2. Laporan Sudah Selesai (id_status == 4), dikelompokkan per bulan
        $laporanSelesai = LaporanKerusakan::with(['fasilitas.ruangan.gedung', 'pelaporLaporan.user', 'penugasan.user', 'status'])
            ->where('id_status', 4)
            ->whereNotNull('tanggal_selesai')
            ->whereYear('tanggal_selesai', $selectedYear)
            ->whereHas('penugasan', function ($query) {
                $query->where('status_perbaikan', 'Selesai Dikerjakan');
            })
            ->orderBy('tanggal_selesai', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal_selesai->translatedFormat('F Y'); // Grup per Bulan Tahun
            });

        return view('laporan.periode', compact('laporanBelumSelesai', 'laporanSelesai', 'selectedYear', 'availableYears'));
    }

    /**
     * Menampilkan konten untuk modal ekspor.
     */
    public function showExportModal()
    {
        return view('laporan.export-periode');
    }

    /**
     * Menampilkan konten untuk modal konfirmasi hapus.
     */
    public function showDeleteModal($tahun)
    {
        return view('laporan.delete-periode', ['selectedYear' => $tahun]);
    }

    /**
     * Mengekspor data laporan ke Excel.
     */
    public function export(Request $request)
    {
        // 1. Definisikan Aturan Validasi
        $rules = [
            'periode_type' => 'required|in:relative,absolute',
            'periode_angka' => 'required_if:periode_type,relative|integer|min:1',
            'periode_satuan' => 'required_if:periode_type,relative|in:bulan,tahun',
            'tanggal_awal' => 'nullable|required_if:periode_type,absolute|date',
            'tanggal_akhir' => 'nullable|required_if:periode_type,absolute|date|after_or_equal:tanggal_awal',
        ];

        // 2. Definisikan Pesan Error Kustom dalam Bahasa Indonesia
        $messages = [
            'periode_type.required' => 'Tipe periode wajib dipilih.',
            'periode_angka.required_if' => 'Jumlah periode harus diisi jika tipe periode adalah relatif.',
            'periode_satuan.required_if' => 'Satuan periode (bulan/tahun) harus dipilih.',
            'tanggal_awal.required_if' => 'Tanggal awal wajib diisi untuk periode absolut.',
            'tanggal_awal.date' => 'Format tanggal awal tidak valid.',
            'tanggal_akhir.required_if' => 'Tanggal akhir wajib diisi untuk periode absolut.',
            'tanggal_akhir.date' => 'Format tanggal akhir tidak valid.',
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir harus sama dengan atau setelah tanggal awal.',
        ];

        // 3. Jalankan Validasi dengan Aturan dan Pesan Kustom
        $request->validate($rules, $messages);

        // Logika penentuan tanggal tetap sama
        $endDate = now();
        $startDate = null;

        if ($request->periode_type == 'relative') {
            if ($request->periode_satuan == 'bulan') {
                $startDate = now()->subMonths($request->periode_angka)->startOfDay();
            } else { // tahun
                $startDate = now()->subYears($request->periode_angka)->startOfDay();
            }
        } else { // absolute
            $startDate = Carbon::parse($request->tanggal_awal)->startOfDay();
            $endDate = Carbon::parse($request->tanggal_akhir)->endOfDay();
        }

        // Default 1 tahun terakhir jika tidak ada input (sepertinya baris ini tidak akan pernah tereksekusi karena sudah divalidasi, tapi aman untuk dibiarkan)
        if (is_null($startDate)) {
            $startDate = now()->subYear();
        }

        return Excel::download(new LaporanPeriodeExport($startDate, $endDate), 'laporan-kerusakan-periode.xlsx', \Maatwebsite\Excel\Excel::XLSX, [
            'charts' => true]);
    }

    /**
     * Menghapus semua laporan dalam periode tahun yang dipilih.
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun_hapus' => 'required|integer',
            'konfirmasi' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $tahun = $request->tahun_hapus;
        $kalimatKonfirmasi = "Saya ingin menghapus semua laporan kerusakan untuk periode " . $tahun;

        // 1. Validasi password user yang login
        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['password' => 'Password yang Anda masukkan salah.']);
        }

        // 2. Validasi kalimat konfirmasi
        if ($request->konfirmasi !== $kalimatKonfirmasi) {
            return back()->withErrors(['konfirmasi' => 'Kalimat konfirmasi tidak sesuai.']);
        }

        // 3. Proses penghapusan data
        LaporanKerusakan::where(function ($query) use ($tahun) {
            $query->whereYear('tanggal_lapor', $tahun)
                ->orWhereYear('tanggal_selesai', $tahun);
        })->delete();

        return redirect()->route('periode.index')->with('success', 'Seluruh laporan untuk periode tahun ' . $tahun . ' berhasil dihapus.');
    }
}
