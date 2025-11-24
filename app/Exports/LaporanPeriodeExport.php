<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\AnalisisTrenSheet;
use App\Exports\Sheets\KepuasanPenggunaSheet;

class LaporanPeriodeExport implements WithMultipleSheets
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            new AnalisisTrenSheet($this->startDate, $this->endDate),
            new KepuasanPenggunaSheet($this->startDate, $this->endDate),
        ];
    }
}
