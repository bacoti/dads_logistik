<?php

namespace App\Exports;

use App\Models\MonthlyReport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Load performance helper functions
require_once app_path('Helpers/ExportHelper.php');

class MonthlyReportsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
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
            $report->user ? sanitizeForSpreadsheet($report->user->name) : '',
            sanitizeForSpreadsheet($this->getMonthName($report->month)),
            $report->year,
            sanitizeForSpreadsheet($report->title ?? ''),
            sanitizeForSpreadsheet($report->description ?? ''),
            sanitizeForSpreadsheet($this->getStatusLabel($report->status)),
            $report->reviewer ? sanitizeForSpreadsheet($report->reviewer->name) : '',
            $report->reviewed_at ? $report->reviewed_at->format('d/m/Y H:i:s') : '',
            sanitizeForSpreadsheet($report->admin_notes ?? ''),
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

    public function title(): string
    {
        return 'Laporan Bulanan';
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'EF4444'] // Red theme
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Data rows styling
        $highestRow = $sheet->getHighestRow();
        if ($highestRow > 1) {
            $sheet->getStyle('A2:M' . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);

            // Zebra striping for better readability
            for ($row = 2; $row <= $highestRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A' . $row . ':M' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('FEF2F2');
                }
            }
        }

        return [];
    }
}
