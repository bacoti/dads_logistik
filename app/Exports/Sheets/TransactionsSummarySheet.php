<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionsSummarySheet implements FromCollection, WithTitle, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $q = Transaction::query();
        if ($this->startDate) $q->whereDate('transaction_date', '>=', $this->startDate);
        if ($this->endDate) $q->whereDate('transaction_date', '<=', $this->endDate);

        $totalTransactions = $q->count();
        $byType = $q->selectRaw('type, count(*) as cnt')->groupBy('type')->pluck('cnt','type')->toArray();
        $totalQuantity = $q->join('transaction_details','transactions.id','=','transaction_details.transaction_id')->sum('transaction_details.quantity');

        $rows = collect([
            ['Metric','Value'],
            ['Total Transactions', $totalTransactions],
            ['Total Quantity', $totalQuantity],
        ]);

        foreach ($byType as $type => $cnt) {
            $rows->push(["Transactions ({$type})", $cnt]);
        }

        // Top 5 projects by transaction count
        $topProjects = $q->selectRaw('project_id, count(*) as cnt')->groupBy('project_id')->orderByDesc('cnt')->limit(5)->get();
        $rows->push(['','']);
        $rows->push(['Top Projects','Count']);
        foreach ($topProjects as $p) {
            $rows->push([optional($p->project)->name ?? 'Unknown', $p->cnt]);
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Summary';
    }
}
