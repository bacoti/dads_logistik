<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ComprehensiveExport implements WithMultipleSheets
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $projectId;
    protected $status;

    public function __construct($startDate = null, $endDate = null, $projectId = null, $status = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->projectId = $projectId;
        $this->status = $status;
    }

    public function sheets(): array
    {
        return [
            new TransactionsDetailExport($this->startDate, $this->endDate, $this->projectId),
            new MonthlyReportsExport($this->status, $this->startDate, $this->endDate),
            new LossReportsExport($this->status, $this->startDate, $this->endDate, $this->projectId),
            new MfoRequestsExport($this->status, $this->startDate, $this->endDate, $this->projectId),
        ];
    }
}
