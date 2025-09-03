<?php

namespace App\Exports;

use App\Models\LossReport;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LossReportsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    use Exportable;

    protected $status;
    protected $startDate;
    protected $endDate;
    protected $projectId;

    public function __construct($status = null, $startDate = null, $endDate = null, $projectId = null)
    {
        $this->status = $status;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->projectId = $projectId;
    }

    public function query()
    {
        $query = LossReport::with(['user', 'project', 'subProject', 'reviewer']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->startDate) {
            $query->whereDate('loss_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('loss_date', '<=', $this->endDate);
        }

        if ($this->projectId) {
            $query->where('project_id', $this->projectId);
        }

        return $query->orderBy('loss_date', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Lapor',
            'User Pelapor',
            'Project',
            'Sub Project',
            'Lokasi Project',
            'Cluster',
            'Tanggal Kehilangan',
            'Tipe Material',
            'Kronologi Kehilangan',
            'Catatan Tambahan',
            'Status',
            'Reviewer',
            'Tanggal Review',
            'Catatan Admin',
            'Dokumen Pendukung',
            'Created At'
        ];
    }

    public function map($lossReport): array
    {
        static $no = 1;
        
        return [
            $no++,
            $lossReport->created_at ? $lossReport->created_at->format('d/m/Y H:i:s') : '',
            $lossReport->user ? $lossReport->user->name : '',
            $lossReport->project ? $lossReport->project->name : '',
            $lossReport->subProject ? $lossReport->subProject->name : '',
            $lossReport->project_location ?? '',
            $lossReport->cluster ?? '',
            $lossReport->loss_date ? $lossReport->loss_date->format('d/m/Y') : '',
            $lossReport->material_type ?? '',
            $lossReport->loss_chronology ?? '',
            $lossReport->additional_notes ?? '',
            $this->getStatusLabel($lossReport->status),
            $lossReport->reviewer ? $lossReport->reviewer->name : '',
            $lossReport->reviewed_at ? $lossReport->reviewed_at->format('d/m/Y H:i:s') : '',
            $lossReport->admin_notes ?? '',
            $lossReport->supporting_document_path ? 'Ada Dokumen' : 'Tidak Ada',
            $lossReport->created_at ? $lossReport->created_at->format('d/m/Y H:i:s') : ''
        ];
    }

    private function getStatusLabel($status)
    {
        $statusLabels = [
            'pending' => 'Menunggu Review',
            'reviewed' => 'Sedang Ditinjau',
            'approved' => 'Disetujui',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak'
        ];

        return $statusLabels[$status] ?? $status;
    }

    public function title(): string
    {
        return 'Laporan Kehilangan';
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:Q1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '3B82F6'] // Blue theme
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
            $sheet->getStyle('A2:Q' . $highestRow)->applyFromArray([
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
                    $sheet->getStyle('A' . $row . ':Q' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F8FAFC');
                }
            }
        }

        return [];
    }
}
