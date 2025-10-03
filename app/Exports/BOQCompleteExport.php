<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BOQCompleteExport implements WithMultipleSheets
{
    protected $summaryData;

    public function __construct($summaryData)
    {
        $this->summaryData = $summaryData;
    }

    public function sheets(): array
    {
        return [
            'BOQ Summary Matrix' => new BOQSummaryMatrixExport($this->summaryData),
            'Total Summary' => new TotalSummaryExport($this->summaryData)
        ];
    }
}
