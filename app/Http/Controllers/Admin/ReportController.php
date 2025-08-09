<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $transactions = collect(); // Defaultnya koleksi kosong

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $transactions = Transaction::with(['user', 'project', 'items.material'])
                ->where('status', 'approved') // Hanya tampilkan yang sudah disetujui
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->latest()
                ->get();
        }

        return view('admin.reports.index', compact('transactions'));
    }

    public function exportExcel(Request $request)
    {
        // 1. Ambil data berdasarkan filter
        $startDate = Carbon::parse($request->input('start_date', Carbon::today()->subMonth()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::today()))->endOfDay();

        $transactions = Transaction::with(['user', 'project', 'vendor', 'items.material'])
            ->where('status', 'approved')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->latest()
            ->get();

        // 2. Buat objek Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 3. Set Header Kolom
        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Jenis Transaksi');
        $sheet->setCellValue('C1', 'Nama Project');
        $sheet->setCellValue('D1', 'Nama Material');
        $sheet->setCellValue('E1', 'Kuantitas');
        $sheet->setCellValue('F1', 'Satuan');
        $sheet->setCellValue('G1', 'User');
        $sheet->setCellValue('H1', 'Vendor');

        // Styling untuk header
        $headerStyle = $sheet->getStyle('A1:H1');
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFE0E0E0');

        // 4. Isi data ke dalam baris
        $rowNumber = 2;
        foreach ($transactions as $transaction) {
            foreach ($transaction->items as $item) {
                $sheet->setCellValue('A' . $rowNumber, $transaction->transaction_date->format('d-m-Y'));
                $sheet->setCellValue('B' . $rowNumber, ucfirst($transaction->type));
                $sheet->setCellValue('C' . $rowNumber, $transaction->project->name);
                $sheet->setCellValue('D' . $rowNumber, $item->material->name);
                $sheet->setCellValue('E' . $rowNumber, $item->quantity);
                $sheet->setCellValue('F' . $rowNumber, $item->material->unit);
                $sheet->setCellValue('G' . $rowNumber, $transaction->user->name);
                $sheet->setCellValue('H' . $rowNumber, $transaction->vendor->name ?? '-');
                $rowNumber++;
            }
        }

        // 5. Atur lebar kolom otomatis
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // 6. Siapkan file untuk di-download
        $fileName = 'Laporan_Transaksi_' . Carbon::now()->format('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Set header HTTP untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    public function exportPdf(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date', Carbon::today()->subMonth()))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', Carbon::today()))->endOfDay();

        $transactions = Transaction::with(['user', 'project', 'vendor', 'items.material'])
            ->where('status', 'approved')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->latest()
            ->get();

        $pdf = PDF::loadView('admin.reports.pdf', compact('transactions', 'startDate', 'endDate'));

        return $pdf->download('Laporan_Transaksi_' . $startDate->format('d-m-Y') . '_' . $endDate->format('d-m-Y') . '.pdf');
    }
}
