<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Transaction;
use App\Models\MonthlyReport;
use App\Models\PoMaterial;
use App\Models\MfoRequest;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminSummaryExport implements WithMultipleSheets
{
    use Exportable;

    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        return [
            'Ringkasan' => new AdminSummarySheet($this->startDate, $this->endDate),
            'Transaksi' => new TransactionsExport($this->startDate, $this->endDate),
            'Laporan Bulanan' => new MonthlyReportsExport(null, $this->startDate, $this->endDate),
            'Statistik User' => new UserStatisticsSheet(),
        ];
    }
}

class AdminSummarySheet implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $data = collect();

        // Summary Statistics
        $totalUsers = User::count();
        $totalTransactions = Transaction::count();
        $totalReports = MonthlyReport::count();
        $pendingReports = MonthlyReport::where('status', 'pending')->count();
        $approvedReports = MonthlyReport::where('status', 'approved')->count();

        // Date range statistics if provided
        if ($this->startDate && $this->endDate) {
            $periodTransactions = Transaction::whereBetween('transaction_date', [$this->startDate, $this->endDate])->count();
            $periodReports = MonthlyReport::whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        }

        $data->push(['Kategori', 'Jumlah', 'Keterangan']);
        $data->push(['Total Users', $totalUsers, 'Semua role']);
        $data->push(['Total Transaksi', $totalTransactions, 'Semua waktu']);
        $data->push(['Total Laporan', $totalReports, 'Semua status']);
        $data->push(['Laporan Pending', $pendingReports, 'Menunggu review']);
        $data->push(['Laporan Approved', $approvedReports, 'Sudah disetujui']);

        if (isset($periodTransactions)) {
            $data->push(['Transaksi Periode', $periodTransactions, "Dari {$this->startDate} s/d {$this->endDate}"]);
            $data->push(['Laporan Periode', $periodReports, "Dari {$this->startDate} s/d {$this->endDate}"]);
        }

        return $data;
    }

    public function headings(): array
    {
        return []; // Headers are included in the data
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'fill' => ['fillType' => 'solid', 'color' => ['rgb' => 'DC2626']],
                'font' => ['color' => ['rgb' => 'FFFFFF']],
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ],
        ];
    }
}

class UserStatisticsSheet implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    public function collection()
    {
        $users = User::selectRaw('role, count(*) as total')
            ->groupBy('role')
            ->get();

        $data = collect();
        foreach ($users as $user) {
            $roleName = [
                'admin' => 'Administrator',
                'po' => 'PO Officer',
                'user' => 'Field User'
            ];

            $data->push([
                $roleName[$user->role] ?? $user->role,
                $user->total
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return ['Role', 'Jumlah User'];
    }

    public function title(): string
    {
        return 'Statistik User';
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
