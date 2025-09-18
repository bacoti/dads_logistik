<?php

namespace App\Exports\Sheets;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Database\Eloquent\Builder;

class TransactionsDataSheet implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query(): Builder
    {
        $q = Transaction::with(['user', 'project', 'subProject', 'vendor', 'details.material']);

        if ($this->startDate) $q->whereDate('transaction_date', '>=', $this->startDate);
        if ($this->endDate) $q->whereDate('transaction_date', '<=', $this->endDate);

        return $q->orderBy('transaction_date', 'desc');
    }

    public function headings(): array
    {
        return [
            'transaction_id','transaction_date','type','user','project','sub_project','location','cluster','vendor','delivery_note_no','site_id','material_id','material_name','quantity'
        ];
    }

    public function map($transaction): array
    {
        // Denormalize: produce one row per material
        // This method is called per model; we'll return the first material row and rely on Laravel-Excel FromQuery chunking to iterate.
        $detail = $transaction->details->first();
        return [
            $transaction->id,
            $transaction->transaction_date ? $transaction->transaction_date->format('Y-m-d') : null,
            $transaction->type,
            $transaction->user?->name,
            $transaction->project?->name,
            $transaction->subProject?->name,
            $transaction->location,
            $transaction->cluster,
            $transaction->vendor?->name ?? $transaction->vendor_name,
            $transaction->delivery_note_no,
            $transaction->site_id,
            $detail?->material_id,
            $detail?->material?->name,
            $detail?->quantity ?? 0,
        ];
    }
}
