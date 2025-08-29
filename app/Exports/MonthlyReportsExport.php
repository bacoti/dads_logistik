<?php

namespace App\Exports;

use App\Models\MonthlyReport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyReportsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    use Exportable;

    protected $status;
    protected $startDate;
    protected $endDate;

    public function __construct($status = null, $startDate = null, $endDate = null)
    {
        $this->status = $status;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = MonthlyReport::with(['user', 'reviewer']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'User',
            'Bulan Laporan',
            'Tahun',
            'Judul Laporan',
            'Deskripsi',
            'Status',
            'Reviewer',
            'Tanggal Review',
            'Admin Notes',
            'File Attachment',
            'Created At',
            'Updated At'
        ];
    }

    public function map($report): array
    {
        static $no = 1;
        
        return [
            $no++,
            $report->user ? $report->user->name : '',
            $this->getMonthName($report->month),
            $report->year,
            $report->title ?? '',
            $report->description ?? '',
            $this->getStatusLabel($report->status),
            $report->reviewer ? $report->reviewer->name : '',
            $report->reviewed_at ? $report->reviewed_at->format('d/m/Y H:i:s') : '',
            $report->admin_notes ?? '',
            $report->file_path ? 'Ada File' : 'Tidak Ada',
            $report->created_at ? $report->created_at->format('d/m/Y H:i:s') : '',
            $report->updated_at ? $report->updated_at->format('d/m/Y H:i:s') : ''
        ];
    }

    private function getMonthName($month)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $months[$month] ?? '';
    }

    private function getStatusLabel($status)
    {
        $statusLabels = [
            'pending' => 'Menunggu Review',
            'reviewed' => 'Sudah Direview',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak'
        ];

        return $statusLabels[$status] ?? $status;
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
