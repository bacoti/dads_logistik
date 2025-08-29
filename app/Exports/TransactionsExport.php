<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $projectId;

    public function __construct($startDate = null, $endDate = null, $projectId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->projectId = $projectId;
    }

    public function query()
    {
        $query = Transaction::with(['user', 'project', 'vendor', 'subProject', 'details.material']);

        if ($this->startDate) {
            $query->whereDate('transaction_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('transaction_date', '<=', $this->endDate);
        }

        if ($this->projectId) {
            $query->where('project_id', $this->projectId);
        }

        return $query->orderBy('transaction_date', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Transaksi',
            'User',
            'Tipe Transaksi',
            'Project',
            'Sub Project',
            'Vendor',
            'Location',
            'Cluster', 
            'Site ID',
            'Materials',
            'Keterangan',
            'Created At'
        ];
    }

    public function map($transaction): array
    {
        static $no = 1;
        
        // Ambil detail materials jika ada
        $materials = $transaction->details->pluck('material.name')->filter()->join(', ');
        if (empty($materials)) {
            $materials = '-';
        }
        
        return [
            $no++,
            $transaction->transaction_date ? $transaction->transaction_date->format('d/m/Y') : '',
            $transaction->user ? $transaction->user->name : '',
            ucfirst($transaction->type ?? ''),
            $transaction->project ? $transaction->project->name : '',
            $transaction->subProject ? $transaction->subProject->name : '',
            $transaction->vendor ? $transaction->vendor->name : '',
            $transaction->location ?? '',
            $transaction->cluster ?? '',
            $transaction->site_id ?? '',
            $materials,
            $transaction->notes ?? '',
            $transaction->created_at ? $transaction->created_at->format('d/m/Y H:i:s') : ''
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'E2E8F0']],
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ],
        ];
    }
}
