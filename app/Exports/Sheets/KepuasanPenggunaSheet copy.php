<?php

namespace App\Exports\Sheets;

use App\Models\PelaporLaporan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title as ChartTitle;

class KepuasanPenggunaSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithCharts, WithEvents
{
    private $startDate;
    private $endDate;
    private $rowCount = 0;
    private $sheetName = 'Analisis Kepuasan Pengguna';

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        // Mengasumsikan relasi antara PelaporLaporan dan Laporan
        // adalah 'pelapor_laporan.laporan_id' -> 'laporan_kerusakan.id'
        return PelaporLaporan::query()
            ->join('laporan_kerusakan', 'pelapor_laporan.id_laporan', '=', 'laporan_kerusakan.id_laporan')
            ->with(['laporan.fasilitas.ruangan.gedung', 'laporan.penugasan.user', 'user'])
            ->where('laporan_kerusakan.id_status', 4) // Kondisi bisa langsung diterapkan setelah join
            ->whereNotNull('laporan_kerusakan.tanggal_selesai')
            ->whereBetween('laporan_kerusakan.tanggal_selesai', [$this->startDate, $this->endDate])
            ->select('pelapor_laporan.*') // Penting untuk menghindari kolom ambigu
            ->orderBy('laporan_kerusakan.tanggal_selesai', 'asc');
    }

    public function title(): string
    {
        return $this->sheetName;
    }

    public function headings(): array
    {
        return [
            'Fasilitas',
            'Lokasi (Gedung - Ruangan)',
            'Jumlah Kerusakan',
            'Deskripsi',
            'Pelapor',
            'Teknisi',
            'Tanggal Lapor',
            'Tanggal Selesai',
            'Rating',
            'Ulasan',
        ];
    }

    public function map($pelaporLaporan): array
    {
        $this->rowCount++;
        $laporan = $pelaporLaporan->laporan;
        return [
            $laporan->fasilitas->nama_fasilitas ?? 'N/A',
            ($laporan->fasilitas->ruangan->gedung->nama_gedung ?? 'N/A') . ' - ' . ($laporan->fasilitas->ruangan->nama_ruangan ?? 'N/A'),
            $laporan->jumlah_kerusakan,
            $laporan->deskripsi,
            $pelaporLaporan->user->nama ?? 'N/A',
            $laporan->penugasan->last()?->user->nama ?? 'N/A',
            $laporan->tanggal_lapor->translatedFormat('d M Y'),
            $laporan->tanggal_selesai->translatedFormat('d M Y'),
            $pelaporLaporan->rating_pengguna,
            $pelaporLaporan->feedback_pengguna,
        ];
    }

    private function createChart($title, $topLeft, $bottomRight, $labelsRange, $valuesRange): Chart
    {
        $dataSeriesLabels = [new DataSeriesValues('String', "'{$this->sheetName}'!{$labelsRange}", null, 1)];
        $dataSeriesValues = [new DataSeriesValues('Number', "'{$this->sheetName}'!{$valuesRange}", null, 5)];

        // --- PERBAIKAN DI SINI ---
        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_STANDARD, // Mengganti null dengan nilai default
            range(0, count($dataSeriesValues) - 1),
            $dataSeriesLabels,
            [], // Mengganti null dengan array kosong untuk kategori
            $dataSeriesValues
        );
        // --- AKHIR PERBAIKAN ---

        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $chartTitle = new ChartTitle($title);

        $chart = new Chart('chart', $chartTitle, $legend, $plotArea);
        $chart->setTopLeftPosition($topLeft);
        $chart->setBottomRightPosition($bottomRight);
        return $chart;
    }

    private function createPieChart($title, $topLeft, $bottomRight, $labelsRange, $valuesRange): Chart
    {
        $labels = [new DataSeriesValues('String', "'{$this->sheetName}'!{$labelsRange}", null, 5)];
        $values = [new DataSeriesValues('Number', "'{$this->sheetName}'!{$valuesRange}", null, 5)];

        // --- PERBAIKAN DI SINI ---
        $series = new DataSeries(
            DataSeries::TYPE_PIECHART,
            null, // Pie chart tidak memiliki grouping, jadi null di sini OK
            range(0, count($values) - 1),
            $labels,
            [], // Mengganti null dengan array kosong untuk kategori
            $values
        );
        // --- AKHIR PERBAIKAN ---

        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $chartTitle = new ChartTitle($title);

        $chart = new Chart('pie', $chartTitle, $legend, $plotArea, true, DataSeries::EMPTY_AS_GAP, null, null);
        $chart->setTopLeftPosition($topLeft);
        $chart->setBottomRightPosition($bottomRight);
        return $chart;
    }

    public function charts(): array
    {
        // --- PERBAIKAN 1: MENCEGAH ERROR 'null' ---
        // Jika tidak ada baris data sama sekali, kembalikan array kosong agar tidak error.
        if ($this->rowCount === 0) {
            return [];
        }
        // --- AKHIR PERBAIKAN 1 ---

        // Chart 1: Total Rating
        $chart1 = $this->createChart('Jumlah Rating Pengguna (Keseluruhan)', 'L5', 'S20', '$M$2', '$N$3:$N$7');

        // Chart 2: Pie Mahasiswa
        $chart2 = $this->createPieChart('Rating oleh Mahasiswa (Level 4)', 'L22', 'S37', '$P$2:$T$2', '$P$3:$T$3');

        // Chart 3: Pie Dosen
        $chart3 = $this->createPieChart('Rating oleh Dosen (Level 5)', 'U5', 'AA20', '$P$2:$T$2', '$P$4:$T$4');

        // Chart 4: Pie Tendik
        $chart4 = $this->createPieChart('Rating oleh Tendik (Level 6)', 'U22', 'AA37', '$P$2:$T$2', '$P$5:$T$5');

        return [$chart1, $chart2, $chart3, $chart4];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getColumnDimension('M')->setVisible(true);
                $event->sheet->getColumnDimension('N')->setVisible(true);
                $event->sheet->getColumnDimension('P')->setVisible(true);
                $event->sheet->getColumnDimension('Q')->setVisible(true);
                $event->sheet->getColumnDimension('R')->setVisible(true);
                $event->sheet->getColumnDimension('S')->setVisible(true);
                $event->sheet->getColumnDimension('T')->setVisible(true);

                // --- Data untuk Grafik Rating Keseluruhan ---
                $ratingData = PelaporLaporan::query()
                    ->whereHas('laporan', fn($q) => $q->where('id_status', 4)->whereBetween('tanggal_selesai', [$this->startDate, $this->endDate]))
                    ->select('rating_pengguna', DB::raw('COUNT(*) as jumlah'))
                    ->groupBy('rating_pengguna')
                    ->pluck('jumlah', 'rating_pengguna');

                $event->sheet->setCellValue('M2', 'Rating');
                $event->sheet->setCellValue('N2', 'Jumlah');
                for ($i = 1; $i <= 5; $i++) {
                    $event->sheet->setCellValue('M' . ($i + 2), "Rating $i");
                    $event->sheet->setCellValue('N' . ($i + 2), $ratingData->get($i, 0));
                }

                // --- Data untuk Grafik Pie per Role ---
                $roleRatingData = PelaporLaporan::query()
                    ->join('users', 'pelapor_laporan.id_user', '=', 'users.id_user')
                    ->whereHas('laporan', fn($q) => $q->where('id_status', 4)->whereBetween('tanggal_selesai', [$this->startDate, $this->endDate]))
                    ->whereIn('users.id_level', [4, 5, 6])
                    ->select('users.id_level', 'pelapor_laporan.rating_pengguna', DB::raw('COUNT(*) as jumlah'))
                    ->groupBy('users.id_level', 'pelapor_laporan.rating_pengguna')
                    ->get();

                // --- PERBAIKAN 2: MENGGANTI fromArray DENGAN setCellValue ---
                $pieDataHeader = ['Role', 'Rating 1', 'Rating 2', 'Rating 3', 'Rating 4', 'Rating 5'];
                $pieDataBody = [
                    ['Mahasiswa (4)', 0, 0, 0, 0, 0],
                    ['Dosen (5)', 0, 0, 0, 0, 0],
                    ['Tendik (6)', 0, 0, 0, 0, 0],
                ];

                // Tulis header (Role, Rating 1, dst.)
                foreach ($pieDataHeader as $colIndex => $header) {
                    // Kolom dimulai dari 'O' (indeks ke-15)
                    $colLetter = Coordinate::stringFromColumnIndex(15 + $colIndex);
                    $event->sheet->setCellValue($colLetter . '2', $header);
                }

                // Tulis body (Mahasiswa, Dosen, Tendik dengan nilai default 0)
                foreach ($pieDataBody as $rowIndex => $rowData) {
                    $row = 3 + $rowIndex;
                    foreach ($rowData as $colIndex => $cellData) {
                        $colLetter = Coordinate::stringFromColumnIndex(15 + $colIndex);
                        $event->sheet->setCellValue($colLetter . $row, $cellData);
                    }
                }

                // Isi data rating aktual dari database
                foreach ($roleRatingData as $data) {
                    $row = $data->id_level == 4 ? 3 : ($data->id_level == 5 ? 4 : 5);
                    // Kolom dimulai dari 'P' untuk Rating 1 (indeks ke-16)
                    $col = Coordinate::stringFromColumnIndex(16 + $data->rating_pengguna - 1);
                    $event->sheet->setCellValue($col . $row, $data->jumlah);
                }
            }
        ];
    }
}
