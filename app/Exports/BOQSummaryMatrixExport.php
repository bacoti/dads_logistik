<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Load performance helper functions
require_once app_path('Helpers/ExportHelper.php');

class BOQSummaryMatrixExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithEvents, ShouldAutoSize, WithChunkReading
{
    protected $summaryData;
    protected $dnList;
    protected $materialList;
    protected $dataMatrix;

    public function __construct($summaryData)
    {
        // Pre-process and cache data for super fast access
        $this->summaryData = $this->preprocessData($summaryData);
        $this->prepareDNAndMaterialLists();
        $this->prepareDataMatrix();
    }

    /**
     * Optimize data preprocessing
     */
    private function preprocessData(array $summaryData): array
    {
        // Pre-sanitize all data in one pass using the enhanced helper function
        return array_map(function($item) {
            return [
                'material_name' => sanitizeForSpreadsheet($item['material_name'] ?? ''),
                'category_name' => sanitizeForSpreadsheet($item['category_name'] ?? ''),
                'unit' => sanitizeForSpreadsheet($item['unit'] ?? ''),
                'project_name' => sanitizeForSpreadsheet($item['project_name'] ?? ''),
                'sub_project_name' => sanitizeForSpreadsheet($item['sub_project_name'] ?? ''),
                'cluster' => sanitizeForSpreadsheet($item['cluster'] ?? ''),
                'dn_number' => sanitizeForSpreadsheet($item['dn_number'] ?? ''),
                'received_quantity' => (float)($item['received_quantity'] ?? 0),
                'actual_usage' => (float)($item['actual_usage'] ?? 0),
                'remaining_stock' => (float)($item['remaining_stock'] ?? 0)
            ];
        }, $summaryData);
    }

    /**
     * Prepare DN and Material lists with optimized data structures
     */
    private function prepareDNAndMaterialLists()
    {
        // Use associative arrays for O(1) lookups
        $dnMap = [];
        $materialMap = [];
        
        foreach ($this->summaryData as $item) {
            $dnKey = $item['cluster'] . '|' . $item['dn_number'];
            
            if (!isset($dnMap[$dnKey])) {
                $dnMap[$dnKey] = [
                    'dn_number' => $item['dn_number'],
                    'cluster' => $item['cluster'],
                    'project_name' => $item['project_name'],
                    'sub_project_name' => $item['sub_project_name'],
                    'key' => $dnKey
                ];
            }
            
            if (!isset($materialMap[$item['material_name']])) {
                $materialMap[$item['material_name']] = true;
            }
        }
        
        $this->dnList = array_values($dnMap);
        $this->materialList = array_keys($materialMap);
        
        // Sort for consistent output
        sort($this->materialList);
        usort($this->dnList, function($a, $b) {
            return strcmp($a['project_name'] . $a['cluster'] . $a['dn_number'], 
                         $b['project_name'] . $b['cluster'] . $b['dn_number']);
        });
    }

    /**
     * Pre-calculate data matrix for super fast array() method
     */
    private function prepareDataMatrix()
    {
        // Create lookup map for O(1) data access
        $dataLookup = [];
        foreach ($this->summaryData as $item) {
            $key = $item['material_name'] . '|' . $item['cluster'] . '|' . $item['dn_number'];
            $dataLookup[$key] = $item;
        }
        
        $this->dataMatrix = [];
        
        foreach ($this->materialList as $index => $material) {
            $row = [($index + 1), $material]; // No and Material name
            
            foreach ($this->dnList as $dn) {
                $lookupKey = $material . '|' . $dn['cluster'] . '|' . $dn['dn_number'];
                
                if (isset($dataLookup[$lookupKey])) {
                    $data = $dataLookup[$lookupKey];
                    $row[] = $data['received_quantity'] > 0 ? $data['received_quantity'] : 0;
                    $row[] = $data['actual_usage'] > 0 ? $data['actual_usage'] : 0;
                    $row[] = $data['actual_usage'] > 0 ? $data['actual_usage'] : 0; // BOQ Actual same as actual usage
                    $row[] = $data['remaining_stock'] != 0 ? $data['remaining_stock'] : 0;
                } else {
                    $row[] = 0;
                    $row[] = 0;
                    $row[] = 0;
                    $row[] = 0;
                }
            }
            
            $this->dataMatrix[] = $row;
        }
    }

    public function chunkSize(): int
    {
        return 1000; // Process in chunks for memory efficiency
    }

    public function array(): array
    {
        // Return pre-calculated matrix for maximum speed
        return $this->dataMatrix;
    }

    public function headings(): array
    {
        // Pre-calculated headers for maximum speed
        static $cachedHeaders = null;
        
        if ($cachedHeaders === null) {
            $cachedHeaders = $this->generateOptimizedHeaders();
        }
        
        return $cachedHeaders;
    }

    /**
     * Generate optimized headers with minimal processing
     */
    private function generateOptimizedHeaders(): array
    {
        $dnCount = count($this->dnList);
        
        // Row 1: Year header (pre-allocated array for speed)
        $yearHeaders = array_fill(0, 2 + ($dnCount * 4), '2024');
        $yearHeaders[0] = '2024';
        $yearHeaders[1] = '';
        
        // Row 2: Project headers
        $projectHeaders = ['', ''];
        $projectName = 'DONE CLR PROJECT OPNAME'; // From screenshot
        for ($i = 0; $i < $dnCount; $i++) {
            $projectHeaders[] = $projectName;
            $projectHeaders[] = '';
            $projectHeaders[] = '';
            $projectHeaders[] = '';
        }
        
        // Row 3: DN with Cluster headers (optimized concatenation)
        $dnHeaders = ['No', 'Nama Material'];
        foreach ($this->dnList as $dn) {
            $dnHeaders[] = $dn['dn_number'] . "\n" . $dn['cluster'];
            $dnHeaders[] = '';
            $dnHeaders[] = '';
            $dnHeaders[] = '';
        }
        
        // Row 4: Sub-column headers (pre-filled array)
        $subHeaders = ['', ''];
        $subColumns = ['DO', 'Terpakai', 'BOQ Actual', 'Sisa'];
        for ($i = 0; $i < $dnCount; $i++) {
            $subHeaders = array_merge($subHeaders, $subColumns);
        }
        
        return [$yearHeaders, $projectHeaders, $dnHeaders, $subHeaders];
    }

    public function columnWidths(): array
    {
        static $cachedWidths = null;
        
        if ($cachedWidths === null) {
            $cachedWidths = [
                'A' => 5,  // No column
                'B' => 25  // Material name column
            ];
            
            // Pre-calculate column letters for DN columns using column indexes
            $columnIndex = 3; // C = 3 (1-based)
            $dnCount = count($this->dnList);
            for ($i = 0; $i < $dnCount; $i++) {
                for ($j = 0; $j < 4; $j++) {
                    $colLetter = Coordinate::stringFromColumnIndex($columnIndex);
                    $cachedWidths[$colLetter] = 10;
                    $columnIndex++;
                }
            }
        }
        
        return $cachedWidths;
    }

    public function styles(Worksheet $sheet)
    {
        $dnCount = count($this->dnList);
        $materialCount = count($this->materialList);
        // Last column index: start at C (3) and add dn blocks of 4 columns, then subtract 1 because index is inclusive
        $lastColumnIndex = 3 + ($dnCount * 4) - 1;
        if ($lastColumnIndex < 1) {
            $lastColumnIndex = 1;
        }
        $lastColumn = Coordinate::stringFromColumnIndex($lastColumnIndex);
        $dataRange = 'C5:' . $lastColumn . ($materialCount + 4);

        return [
            // Header styles (optimized range)
            '1:4' => [
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4CAF50']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ],
            // No column
            'A:A' => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E8F5E8']]
            ],
            // Material name column
            'B:B' => [
                'font' => ['bold' => false, 'size' => 9],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E8F5E8']]
            ],
            // Data cells (pre-calculated range)
            $dataRange => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'font' => ['size' => 9]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $this->applyOptimizedFormatting($event->sheet->getDelegate());
            }
        ];
    }

    /**
     * Apply formatting with optimized batch operations
     */
    private function applyOptimizedFormatting(Worksheet $sheet)
    {
        $dnCount = count($this->dnList);
        $materialCount = count($this->materialList);
        $lastColumnIndex = 3 + ($dnCount * 4) - 1;
        if ($lastColumnIndex < 1) {
            $lastColumnIndex = 1;
        }
        $lastColumn = Coordinate::stringFromColumnIndex($lastColumnIndex);
        $lastRow = $materialCount + 4;

        // Batch merge operations for better performance
        $mergeCells = [];

        // Year, Project, DN header merges using column indexes
        $columnIndex = 3; // C
        for ($i = 0; $i < $dnCount; $i++) {
            $startCol = Coordinate::stringFromColumnIndex($columnIndex);
            $endCol = Coordinate::stringFromColumnIndex($columnIndex + 3);
            $mergeCells[] = $startCol . '1:' . $endCol . '1';
            $mergeCells[] = $startCol . '2:' . $endCol . '2';
            $mergeCells[] = $startCol . '3:' . $endCol . '3';
            $columnIndex += 4;
        }

        // Apply all merges at once with validation
        foreach ($mergeCells as $range) {
            if (preg_match('/^[A-Z]+[0-9]+:[A-Z]+[0-9]+$/', $range)) {
                $sheet->mergeCells($range);
            }
        }

        // Merge No and Material headers vertically
        $sheet->mergeCells('A1:A4');
        $sheet->mergeCells('B1:B4');

        // Set row heights efficiently
        $rowDimensions = [
            1 => 20, 2 => 20, 3 => 30, 4 => 15
        ];
        foreach ($rowDimensions as $row => $height) {
            $sheet->getRowDimension($row)->setRowHeight($height);
        }

        // Apply conditional formatting for BOQ Actual columns in batches
        $boqActualRanges = [];
        $columnIndex = 3; // C
        for ($i = 0; $i < $dnCount; $i++) {
            $boqColumn = Coordinate::stringFromColumnIndex($columnIndex + 2); // 3rd column of each DN group
            $boqActualRanges[] = $boqColumn . '5:' . $boqColumn . $lastRow;
            $columnIndex += 4;
        }

        // Apply yellow background to all BOQ Actual columns at once
        foreach ($boqActualRanges as $range) {
            if (preg_match('/^[A-Z]+[0-9]+:[A-Z]+[0-9]+$/', $range)) {
                $sheet->getStyle($range)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFEB3B']]
                ]);
            }
        }

        // Add borders efficiently
        $headerRange = 'A1:' . $lastColumn . $lastRow;
        if (preg_match('/^[A-Z]+[0-9]+:[A-Z]+[0-9]+$/', $headerRange)) {
            $sheet->getStyle($headerRange)
                  ->getBorders()
                  ->getAllBorders()
                  ->setBorderStyle(Border::BORDER_THIN);
        }

        // Freeze panes
        $sheet->freezePane('C5');
    }
}