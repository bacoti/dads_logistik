<?php

namespace App\Exports;

use App\Models\MfoRequest;
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

class MfoRequestsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
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
        $query = MfoRequest::with(['user', 'project', 'subProject', 'reviewer']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->startDate) {
            $query->whereDate('request_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('request_date', '<=', $this->endDate);
        }

        if ($this->projectId) {
            $query->where('project_id', $this->projectId);
        }

        return $query->orderBy('request_date', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Pengajuan',
            'User Pengaju',
            'Project',
            'Sub Project',
            'Lokasi Project',
            'Cluster',
            'Tanggal Request',
            'Deskripsi Pengajuan',
            'Status',
            'Reviewer',
            'Tanggal Review',
            'Catatan Admin',
            'Dokumen Pendukung',
            'Created At',
            'Updated At'
        ];
    }

    public function map($mfoRequest): array
    {
        static $no = 1;
        
        return [
            $no++,
            $mfoRequest->created_at ? $mfoRequest->created_at->format('d/m/Y H:i:s') : '',
            $mfoRequest->user ? sanitizeForSpreadsheet($mfoRequest->user->name) : '',
            $mfoRequest->project ? sanitizeForSpreadsheet($mfoRequest->project->name) : '',
            $mfoRequest->subProject ? sanitizeForSpreadsheet($mfoRequest->subProject->name) : '',
            sanitizeForSpreadsheet($mfoRequest->project_location ?? ''),
            sanitizeForSpreadsheet($mfoRequest->cluster ?? ''),
            $mfoRequest->request_date ? $mfoRequest->request_date->format('d/m/Y') : '',
            sanitizeForSpreadsheet($mfoRequest->description ?? ''),
            sanitizeForSpreadsheet($this->getStatusLabel($mfoRequest->status)),
            $mfoRequest->reviewer ? sanitizeForSpreadsheet($mfoRequest->reviewer->name) : '',
            $mfoRequest->reviewed_at ? $mfoRequest->reviewed_at->format('d/m/Y H:i:s') : '',
            sanitizeForSpreadsheet($mfoRequest->admin_notes ?? ''),
            $mfoRequest->document_path ? 'Ada Dokumen' : 'Tidak Ada',
            $mfoRequest->created_at ? $mfoRequest->created_at->format('d/m/Y H:i:s') : '',
            $mfoRequest->updated_at ? $mfoRequest->updated_at->format('d/m/Y H:i:s') : ''
        ];
    }

    private function getStatusLabel($status)
    {
        $statusLabels = [
            'pending' => 'Menunggu Review',
            'reviewed' => 'Sedang Ditinjau',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak'
        ];

        return $statusLabels[$status] ?? $status;
    }

    public function title(): string
    {
        return 'Pengajuan MFO';
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '10B981'] // Green theme
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
            $sheet->getStyle('A2:P' . $highestRow)->applyFromArray([
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
                    $sheet->getStyle('A' . $row . ':P' . $row)->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F0FDF4');
                }
            }
        }

        return [];
    }
}
