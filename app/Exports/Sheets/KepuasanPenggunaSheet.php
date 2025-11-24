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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title as ChartTitle;
// Concern yang dibutuhkan
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;

// Kembalikan WithCharts, tambahkan ShouldAutoSize dan WithStyles
class KepuasanPenggunaSheet implements FromQuery, WithTitle, WithHeadings, WithMapping, WithCharts, WithEvents, ShouldAutoSize, WithStyles
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
        return PelaporLaporan::query()
            ->join('laporan_kerusakan', 'pelapor_laporan.id_laporan', '=', 'laporan_kerusakan.id_laporan')
            ->with(['laporan.fasilitas.ruangan.gedung', 'laporan.penugasan.user', 'user'])
            ->where('laporan_kerusakan.id_status', 4)
            ->whereNotNull('laporan_kerusakan.tanggal_selesai')
            ->whereBetween('laporan_kerusakan.tanggal_selesai', [$this->startDate, $this->endDate])
            ->select('pelapor_laporan.*')
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
        // Hitung baris di sini, sesuai dengan kode yang Anda berikan sebelumnya
        $this->rowCount++;
        $laporan = $pelaporLaporan->laporan;
        return [
            $laporan?->fasilitas?->nama_fasilitas ?? 'N/A',
            ($laporan?->fasilitas?->ruangan?->gedung?->nama_gedung ?? 'N/A') . ' - ' . ($laporan?->fasilitas?->ruangan?->nama_ruangan ?? 'N/A'),
            $laporan?->jumlah_kerusakan ?? 0,
            $laporan?->deskripsi ?? '',
            $pelaporLaporan->user?->nama ?? 'N/A',
            $laporan?->penugasan?->last()?->user?->nama ?? 'N/A',
            $laporan?->tanggal_lapor?->translatedFormat('d M Y') ?? 'N/A',
            $laporan?->tanggal_selesai?->translatedFormat('d M Y') ?? 'N/A',
            $pelaporLaporan->rating_pengguna ?? 0,
            $pelaporLaporan->feedback_pengguna ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        return [1 => ['font' => ['bold' => true]]];
    }

    // Kembalikan fungsi helper dengan parameter $name untuk nama unik
    private function createChart($name, $title, $topLeft, $bottomRight, $labelsRange, $valuesRange): Chart
    {
        $barCategories = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'{$this->sheetName}'!\$M\$3:\$M\$7", null, 5)];
        $barValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'{$this->sheetName}'!\$N\$3:\$N\$7", null, 5)];
        $barSeries = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_STANDARD,
            range(0, 4),
            [new DataSeriesValues('String', "'{$this->sheetName}'!{$labelsRange}", null, 1)],
            $barCategories,
            $barValues
        );

        $plotArea = new PlotArea(null, [$barSeries]);
        // $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $chartTitle = new ChartTitle($title);

        $chart = new Chart($name, $chartTitle, null, $plotArea);
        $chart->setTopLeftPosition($topLeft);
        $chart->setBottomRightPosition($bottomRight);
        return $chart;
    }

    private function createPieChart($name, $title, $topLeft, $bottomRight, $labelsRange, $valuesRange): Chart
    {
        $labels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $labelsRange, null, 5)];
        $values = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $valuesRange, null, 5)];
        $series = new DataSeries(
            DataSeries::TYPE_PIECHART,
            null,
            range(0, 4),
            $labels,
            [],
            $values
        );

        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $chartTitle = new ChartTitle($title);

        $chart = new Chart($name, $chartTitle, $legend, $plotArea, true);
        $chart->setTopLeftPosition($topLeft);
        $chart->setBottomRightPosition($bottomRight);
        return $chart;
    }

    // Kembalikan method charts()
    public function charts(): array
    {
        if ($this->rowCount === 0) {
            return [];
        }

        // Panggil helper dengan NAMA UNIK
        $chart1 = $this->createChart('chartKepuasanRating', 'Jumlah Rating Pengguna (Keseluruhan)', 'L5', 'S20', '$M$2', '$N$3:$N$7');
        $chart2 = $this->createPieChart('chartKepuasanMhs', 'Rating oleh Mahasiswa', 'L22', 'S37', "'{$this->sheetName}'!\$Q\$2:\$U\$2", "'{$this->sheetName}'!\$Q\$3:\$U\$3");
        $chart3 = $this->createPieChart('chartKepuasanDosen', 'Rating oleh Dosen', 'T5', 'Z20', "'{$this->sheetName}'!\$Q\$2:\$U\$2", "'{$this->sheetName}'!\$Q\$4:\$U\$4");
        $chart4 = $this->createPieChart('chartKepuasanTendik', 'Rating oleh Tendik', 'T22', 'Z37', "'{$this->sheetName}'!\$Q\$2:\$U\$2", "'{$this->sheetName}'!\$Q\$5:\$U\$5");

        return [$chart1, $chart2, $chart3, $chart4];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                if ($this->rowCount === 0) return;

                $worksheet = $event->sheet->getDelegate();

                // Format data utama menjadi tabel
                $table = new Table('A1:J' . ($this->rowCount + 1), 'Tabel_Kepuasan');
                $tableStyle = new TableStyle();
                $tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM9);
                $tableStyle->setShowRowStripes(true);
                $table->setStyle($tableStyle);
                $worksheet->addTable($table);

                // Tulis data untuk grafik (logika ini sudah benar)
                $ratingData = PelaporLaporan::query()->whereHas('laporan', fn($q) => $q->where('id_status', 4)->whereBetween('tanggal_selesai', [$this->startDate, $this->endDate]))->select('rating_pengguna', DB::raw('COUNT(*) as jumlah'))->groupBy('rating_pengguna')->pluck('jumlah', 'rating_pengguna');
                $worksheet->fromArray(['Rating', 'Jumlah'], null, 'M2');
                for ($i = 1; $i <= 5; $i++) {
                    $worksheet->fromArray(["Rating $i", $ratingData->get($i, 0)], null, "M" . ($i + 2));
                }

                $roleRatingData = PelaporLaporan::query()->join('users', 'pelapor_laporan.id_user', '=', 'users.id_user')->whereHas('laporan', fn($q) => $q->where('id_status', 4)->whereBetween('tanggal_selesai', [$this->startDate, $this->endDate]))->whereIn('users.id_level', [4, 5, 6])->select('users.id_level', 'pelapor_laporan.rating_pengguna', DB::raw('COUNT(*) as jumlah'))->groupBy('users.id_level', 'pelapor_laporan.rating_pengguna')->get()->groupBy('id_level');
                $pieDataHeader = ['Role', 'Rating 1', 'Rating 2', 'Rating 3', 'Rating 4', 'Rating 5'];
                $worksheet->fromArray($pieDataHeader, null, 'P2');
                $roles = [4 => 'Mahasiswa', 5 => 'Dosen', 6 => 'Tendik'];
                $row = 3;
                foreach ($roles as $level => $namaRole) {
                    $ratings = $roleRatingData->get($level, collect())->pluck('jumlah', 'rating_pengguna');
                    $rowData = [$namaRole];
                    for ($i = 1; $i <= 5; $i++) {
                        $rowData[] = $ratings->get($i, 0);
                    }
                    $worksheet->fromArray($rowData, null, "P{$row}");
                    $row++;
                }

                // --- PERBAIKAN: GANTI setVisible DENGAN MENGUBAH WARNA FONT ---
                // Definisikan style font putih
                $hidingStyle = [
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF']
                    ]
                ];
                // Terapkan style ke semua range data yang ingin disembunyikan
                $worksheet->getStyle('M2:N7')->applyFromArray($hidingStyle); // Data Bar Chart
                $worksheet->getStyle('P2:U5')->applyFromArray($hidingStyle); // Data Pie Chart
            }
        ];
    }
}
