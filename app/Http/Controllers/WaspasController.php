<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKerusakan;
use App\Models\PenugasanTeknisi;
use App\Models\KriteriaPenilaian;
use Illuminate\Support\Facades\DB;

class WaspasController extends Controller
{
    public function index()
    {
        $ranked = $this->getPrioritas();

        // Kirim data ke views
        return view('laporan_prioritas.index', ['ranked' => $ranked]);
    }

    public function getPrioritas()
    {
        if (auth()->user()->id_level == 1 || auth()->user()->id_level == 2) {
            // Ambil ID penugasan terbaru per laporan
            $subLatestPenugasan = DB::table('penugasan_teknisi')
                ->select(DB::raw('MAX(id_penugasan) as id_penugasan'))
                ->groupBy('id_laporan');

            // Ambil data penugasan terbaru (id_laporan dan id_user)
            $latestPenugasan = DB::table('penugasan_teknisi as pt')
                ->joinSub($subLatestPenugasan, 'latest', function ($join) {
                    $join->on('pt.id_penugasan', '=', 'latest.id_penugasan');
                })
                ->select('pt.id_laporan', 'pt.id_user');

            // Data utama
            $data = DB::table('laporan_kerusakan as l')
                ->join('kriteria_penilaian as k', 'l.id_laporan', '=', 'k.id_laporan')
                ->leftJoinSub($latestPenugasan, 'p', function ($join) {
                    $join->on('l.id_laporan', '=', 'p.id_laporan');
                })
                ->leftJoin('users as u', 'u.id_user', '=', 'p.id_user')
                ->join('fasilitas as f', 'f.id_fasilitas', '=', 'l.id_fasilitas')
                ->join('ruangan as r', 'r.id_ruangan', '=', 'f.id_ruangan')
                ->join('gedung as g', 'g.id_gedung', '=', 'r.id_gedung')
                ->join('status_laporan as s', 'l.id_status', '=', 's.id_status')
                ->whereIn('l.id_status', [2, 3])
                ->select(
                    'l.id_laporan',
                    'l.deskripsi',
                    'l.foto_kerusakan',
                    'l.tanggal_lapor',
                    'u.nama as nama_teknisi',
                    'f.nama_fasilitas',
                    'r.nama_ruangan',
                    'g.nama_gedung',
                    's.nama_status',
                    'k.tingkat_kerusakan',
                    'k.frekuensi_digunakan',
                    'k.dampak',
                    'k.estimasi_biaya',
                    'k.potensi_bahaya'
                )
                ->get();

            // Konversi ke array
            $dataArray = $data->map(function ($item) {
                return (array) $item;
            })->toArray();

            // Kriteria dan Bobot
            $kriteria = [
                'tingkat_kerusakan' => ['weight' => 0.3, 'type' => 'benefit'],
                'frekuensi_digunakan' => ['weight' => 0.1, 'type' => 'benefit'],
                'dampak' => ['weight' => 0.05, 'type' => 'benefit'],
                'estimasi_biaya' => ['weight' => 0.35, 'type' => 'cost'],
                'potensi_bahaya' => ['weight' => 0.2, 'type' => 'benefit'],
            ];

            // Hitung max/min
            $normalMatrix = [];
            $maxMin = [];

            foreach ($kriteria as $key => $meta) {
                $column = array_column($dataArray, $key);
                $maxMin[$key] = empty($column) ? 1 : ($meta['type'] === 'benefit' ? max($column) : min($column));
            }

            // Normalisasi matriks
            foreach ($dataArray as $row) {
                $id = $row['id_laporan'];
                $normalMatrix[$id] = [];

                foreach ($kriteria as $key => $meta) {
                    $normalMatrix[$id][$key] = $meta['type'] === 'benefit'
                        ? $row[$key] / $maxMin[$key]
                        : $maxMin[$key] / $row[$key];
                }
            }

            // Hitung WSM, WPM, dan WASPAS
            $results = [];

            foreach ($normalMatrix as $id => $nilai) {
                $WSM = 0;
                $WPM = 1;

                foreach ($nilai as $key => $val) {
                    $bobot = $kriteria[$key]['weight'];
                    $WSM += $val * $bobot;
                    $WPM *= pow($val, $bobot);
                }

                $Q = 0.5 * $WSM + 0.5 * $WPM;

                // Ambil data asli berdasarkan ID
                $original = collect($dataArray)->firstWhere('id_laporan', $id);

                $results[] = [
                    'id_laporan' => $original['id_laporan'],
                    'foto_kerusakan' => $original['foto_kerusakan'],
                    'deskripsi' => $original['deskripsi'] ?? null,
                    'status' => $original['nama_status'] ?? null,
                    'fasilitas' => $original['nama_fasilitas'] ?? null,
                    'ruangan' => $original['nama_ruangan'] ?? null,
                    'gedung' => $original['nama_gedung'] ?? null,
                    'teknisi' => $original['nama_teknisi'] ?? null,
                    'tanggal_lapor' => $original['tanggal_lapor'] ?? null,
                    'Q' => round($Q, 4),
                    'tingkat_kerusakan' => $original['tingkat_kerusakan'] ?? null,
                    'frekuensi_digunakan' => $original['frekuensi_digunakan'] ?? null,
                    'dampak' => $original['dampak'] ?? null,
                    'estimasi_biaya' => $original['estimasi_biaya'] ?? null,
                    'potensi_bahaya' => $original['potensi_bahaya'] ?? null,
                ];
            }

            // Ranking berdasarkan nilai Q
            // usort($results, fn($a, $b) => $b['Q'] <=> $a['Q']);

            // Multi-level sort: Q desc, tanggal_lapor asc (semakin lama semakin atas)
            usort($results, function ($a, $b) {
                if ($b['Q'] === $a['Q']) {
                    return strtotime($a['tanggal_lapor']) <=> strtotime($b['tanggal_lapor']);
                }
                return $b['Q'] <=> $a['Q'];
            });

            $ranked = [];
            $rank = 1;
            foreach ($results as $item) {
                $item['rank'] = $rank++;

                // Tambahkan data penugasan teknisi
                $penugasan = \App\Models\PenugasanTeknisi::where('id_laporan', $item['id_laporan'])->orderByDesc('id_penugasan')->first();
                $item['penugasan'] = $penugasan;

                $ranked[] = $item;
            }

            return $ranked;
        } else {
            $ranked = [];
            return $ranked;
        }
    }
}
