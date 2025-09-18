<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Exports\Sheets\TransactionsDataSheet;
use App\Exports\Sheets\TransactionsSummarySheet;
use App\Exports\Sheets\TransactionsTrendsSheet;

class TransactionsDashboardExport implements WithMultipleSheets
{
    use Exportable;

    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        return [
            new TransactionsSummarySheet($this->startDate, $this->endDate),
            new TransactionsTrendsSheet($this->startDate, $this->endDate, 12),
            new TransactionsDataSheet($this->startDate, $this->endDate),
        ];
    }
}
