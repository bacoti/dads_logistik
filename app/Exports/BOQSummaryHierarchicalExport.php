<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BOQSummaryHierarchicalExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    protected $summaryData;

    public function __construct($summaryData)
    {
        $this->summaryData = $summaryData;
    }

    public function array(): array
    {
        $data = [];
        
        // Group data hierarchically
        $groupedData = collect($this->summaryData)->groupBy('project_name')->map(function($projectItems) {
            return $projectItems->groupBy('sub_project_name')->map(function($subProjectItems) {
                return $subProjectItems->groupBy('cluster')->map(function($clusterItems) {
                    return $clusterItems->groupBy('dn_number');
                });
            });
        });

        foreach ($groupedData as $projectName => $subProjects) {
            // Project header
            $data[] = ['📂 ' . $projectName, '', '', '', '', '', '', '', ''];
            
            foreach ($subProjects as $subProjectName => $clusters) {
                // Sub Project header
                $data[] = ['  📁 ' . $subProjectName, '', '', '', '', '', '', '', ''];
                
                foreach ($clusters as $clusterName => $dns) {
                    // Cluster header
                    $data[] = ['    🏗️ Cluster: ' . ($clusterName ?: 'No Cluster'), '', '', '', '', '', '', '', ''];
                    
                    foreach ($dns as $dnNumber => $materials) {
                        // DN header
                        $data[] = ['      📋 DN: ' . ($dnNumber ?: 'No DN'), '', '', '', '', '', '', '', ''];
                        
                        // Materials
                        foreach ($materials as $material) {
                            $status = '';
                            if ($material['received_quantity'] == $material['actual_usage']) {
                                $status = '✅ Aktual';
                            } elseif ($material['remaining_stock'] > 0) {
                                $status = '📦 Sisa Stok';
                            } else {
                                $status = '⚠️ Kelebihan Pakai';
                            }
                            
                            $data[] = [
                                '        📦 ' . $material['material_name'],
                                $material['category_name'] ?: 'No Category',
                                $material['unit'],
                                number_format($material['received_quantity'], 2),
                                number_format($material['actual_usage'], 2),
                                number_format($material['actual_usage'], 2), // BOQ Actual
                                number_format($material['remaining_stock'], 2),
                                $status,
                                $material['received_quantity'] > 0 ? round(($material['actual_usage'] / $material['received_quantity']) * 100, 1) . '%' : '0%'
                            ];
                        }
                        
                        // Add spacing
                        $data[] = ['', '', '', '', '', '', '', '', ''];
                    }
                }
            }
            
            // Add spacing between projects
            $data[] = ['', '', '', '', '', '', '', '', ''];
            $data[] = ['', '', '', '', '', '', '', '', ''];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Struktur / Material',
            'Kategori',
            'Unit',
            'Diterima (DO)',
            'Terpakai',
            'BOQ Actual',
            'Sisa',
            'Status',
            'Persentase'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 20,
            'C' => 10,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 20,
            'I' => 12
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row
            '1' => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '2196F3']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
            // Column A (hierarchy structure)
            'A:A' => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            // Data columns
            'B:I' => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                
                // Style project headers (rows with 📂)
                for ($row = 2; $row <= $highestRow; $row++) {
                    $cellValue = $sheet->getCell('A' . $row)->getValue();
                    
                    if (strpos($cellValue, '📂') !== false) {
                        // Project header styling
                        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1976D2']],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]]
                        ]);
                    } elseif (strpos($cellValue, '📁') !== false) {
                        // Sub Project header styling
                        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4CAF50']],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                        ]);
                    } elseif (strpos($cellValue, '🏗️') !== false) {
                        // Cluster header styling
                        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '000000']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFF9C4']],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                        ]);
                    } elseif (strpos($cellValue, '📋') !== false) {
                        // DN header styling
                        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '000000']],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F5F5F5']],
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                        ]);
                    } elseif (strpos($cellValue, '📦') !== false) {
                        // Material row styling
                        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray([
                            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                        ]);
                        
                        // Conditional formatting for status
                        $statusCell = $sheet->getCell('H' . $row)->getValue();
                        if (strpos($statusCell, '✅') !== false) {
                            $sheet->getStyle('H' . $row)->applyFromArray([
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'C8E6C9']]
                            ]);
                        } elseif (strpos($statusCell, '⚠️') !== false) {
                            $sheet->getStyle('H' . $row)->applyFromArray([
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFCDD2']]
                            ]);
                        } elseif (strpos($statusCell, '📦') !== false) {
                            $sheet->getStyle('H' . $row)->applyFromArray([
                                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFE0B2']]
                            ]);
                        }
                    }
                }
                
                // Auto-fit row heights
                $sheet->getDefaultRowDimension()->setRowHeight(-1);
                
                // Freeze first row
                $sheet->freezePane('A2');
            }
        ];
    }
}