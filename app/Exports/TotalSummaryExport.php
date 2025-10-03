<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Load performance helper functions
require_once __DIR__ . '/../Helpers/ExportHelper.php';

class TotalSummaryExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithEvents, ShouldAutoSize
{
    protected $summaryData;
    protected $totalData;

    public function __construct($summaryData)
    {
        $this->summaryData = $this->preprocessData($summaryData);
        $this->prepareTotalData();
    }

    /**
     * Preprocess and sanitize data
     */
    private function preprocessData(array $summaryData): array
    {
        return array_map(function($item) {
            return [
                'material_name' => sanitizeForSpreadsheet($item['material_name'] ?? ''),
                'received_quantity' => (float)($item['received_quantity'] ?? 0),
                'actual_usage' => (float)($item['actual_usage'] ?? 0),
                'boq_actual_quantity' => (float)($item['boq_actual_quantity'] ?? 0),
                'remaining_stock' => (float)($item['remaining_stock'] ?? 0)
            ];
        }, $summaryData);
    }

    /**
     * Prepare total data by aggregating per material
     */
    private function prepareTotalData()
    {
        $materialTotals = [];

        // Aggregate data per material
        foreach ($this->summaryData as $item) {
            $materialName = $item['material_name'];

            if (!isset($materialTotals[$materialName])) {
                $materialTotals[$materialName] = [
                    'total_do' => 0,
                    'total_terpakai' => 0,
                    'total_boq_actual' => 0,
                    'total_sisa' => 0
                ];
            }

            $materialTotals[$materialName]['total_do'] += $item['received_quantity'];
            $materialTotals[$materialName]['total_terpakai'] += $item['actual_usage'];
            $materialTotals[$materialName]['total_boq_actual'] += $item['boq_actual_quantity'];
            $materialTotals[$materialName]['total_sisa'] += $item['remaining_stock'];
        }

        // Sort materials alphabetically
        ksort($materialTotals);

        // Convert to array format for export
        $this->totalData = [];
        $index = 1;

        foreach ($materialTotals as $materialName => $totals) {
            $this->totalData[] = [
                $index++,
                $materialName,
                $totals['total_do'],
                $totals['total_terpakai'],
                $totals['total_boq_actual'],
                $totals['total_sisa']
            ];
        }
    }

    public function array(): array
    {
        // Combine headers and data for proper Excel formatting
        $result = [];

        // Add header rows
        $result[] = ['2024', '', '', '', '', ''];
        $result[] = ['DONE CLR PROJECT OPNAME', '', '', '', '', ''];
        $result[] = ['TOTAL SUMMARY', '', '', '', '', ''];
        $result[] = ['No', 'Nama Material', 'TOTAL DO', 'TOTAL TERPAKAI', 'TOTAL BOQ ACTUAL', 'TOTAL SISA'];

        // Add data rows
        foreach ($this->totalData as $row) {
            $result[] = $row;
        }

        return $result;
    }

    public function headings(): array
    {
        // Return empty array since we're handling headers in array() method
        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  // No
            'B' => 30, // Nama Material
            'C' => 12, // TOTAL DO
            'D' => 15, // TOTAL TERPAKAI
            'E' => 18, // TOTAL BOQ ACTUAL
            'F' => 12  // TOTAL SISA
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $dataRowCount = count($this->totalData);
        $lastRow = $dataRowCount + 4; // +4 for header rows

        return [
            // Header styles (rows 1-4)
            '1:4' => [
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => '4CAF50']
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ]
            ],
            // No column (all rows)
            'A:A' => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E8F5E8']
                ]
            ],
            // Material name column (all rows)
            'B:B' => [
                'font' => ['bold' => false, 'size' => 10],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E8F5E8']
                ]
            ],
            // Data cells (starting from row 5)
            'C5:F' . $lastRow => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ],
                'font' => ['size' => 10]
            ],
            // BOQ Actual column highlighting (column E starting from row 5)
            'E5:E' . $lastRow => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'FFEB3B'] // Yellow background
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ],
                'font' => ['size' => 10]
            ],
            // TOTAL SISA column styling (column F starting from row 5)
            'F5:F' . $lastRow => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ],
                'font' => ['size' => 10],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'FFFFFF'] // White background to ensure visibility
                ]
            ],
            // All data area borders
            'A1:F' . $lastRow => [
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $this->applyFormatting($event->sheet->getDelegate());
            }
        ];
    }

    /**
     * Apply additional formatting
     */
    private function applyFormatting(Worksheet $sheet)
    {
        $dataRowCount = count($this->totalData);
        $lastRow = $dataRowCount + 4;

        // Merge cells for headers
        $sheet->mergeCells('A1:F1'); // Year
        $sheet->mergeCells('A2:F2'); // Project
        $sheet->mergeCells('A3:F3'); // Summary title

        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(3)->setRowHeight(25);
        $sheet->getRowDimension(4)->setRowHeight(20);

        // Apply borders to entire data range
        $sheet->getStyle('A1:F' . $lastRow)
              ->getBorders()
              ->getAllBorders()
              ->setBorderStyle(Border::BORDER_THIN);

        // Ensure TOTAL SISA column (F) is visible and properly formatted
        $sheet->getStyle('F5:F' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'font' => [
                'size' => 10,
                'bold' => false
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'FFFFFF'] // White background for clarity
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        // Freeze panes
        $sheet->freezePane('C5');

        // Auto-fit columns
        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}
