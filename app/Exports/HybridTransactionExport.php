<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Exports\Sheets\ExecutiveSummarySheet;
use App\Exports\Sheets\TransactionDetailsSheet;
use App\Exports\Sheets\MaterialAnalysisSheet;
use App\Exports\Sheets\AnalyticsChartsSheet;
use App\Exports\Sheets\RawDataSheet;

class HybridTransactionExport implements WithMultipleSheets
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $projectId;
    protected $location;
    protected $cluster;

    public function __construct($startDate = null, $endDate = null, $projectId = null, $location = null, $cluster = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->projectId = $projectId;
        $this->location = $location;
        $this->cluster = $cluster;
    }

    public function sheets(): array
    {
        return [
            new ExecutiveSummarySheet($this->startDate, $this->endDate, $this->projectId, $this->location, $this->cluster),
            new TransactionDetailsSheet($this->startDate, $this->endDate, $this->projectId, $this->location, $this->cluster),
            new MaterialAnalysisSheet($this->startDate, $this->endDate, $this->projectId, $this->location, $this->cluster),
            new AnalyticsChartsSheet($this->startDate, $this->endDate, $this->projectId, $this->location, $this->cluster),
            new RawDataSheet($this->startDate, $this->endDate, $this->projectId, $this->location, $this->cluster),
        ];
    }
}