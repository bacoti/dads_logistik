<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionsTrendsSheet implements FromCollection, WithTitle, ShouldAutoSize
{
    protected $months = 6;
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null, $months = 6)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->months = $months;
    }

    public function collection()
    {
        $now = Carbon::now();
        $rows = collect([array_merge(['Period'], ['Total Transactions','Total Quantity'])]);

        for ($m = $this->months - 1; $m >= 0; $m--) {
            $periodStart = $now->copy()->startOfMonth()->subMonths($m);
            $periodEnd = $periodStart->copy()->endOfMonth();

            $q = Transaction::whereBetween('transaction_date', [$periodStart->format('Y-m-d'), $periodEnd->format('Y-m-d')]);
            $totalTx = $q->count();
            $totalQty = $q->join('transaction_details','transactions.id','=','transaction_details.transaction_id')->sum('transaction_details.quantity');

            $rows->push([$periodStart->format('Y-m'), $totalTx, $totalQty]);
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Trends';
    }
}
