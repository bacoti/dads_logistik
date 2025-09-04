<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\SubProject;
use App\Models\User;
use App\Exports\TransactionsDetailExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'vendor', 'project', 'subProject', 'details.material.category']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        // Filter by vendor
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by sub project
        if ($request->filled('sub_project_id')) {
            $query->where('sub_project_id', $request->sub_project_id);
        }

        // Filter by document numbers
        if ($request->filled('delivery_order_no')) {
            $query->where('delivery_order_no', 'like', "%{$request->delivery_order_no}%");
        }
        if ($request->filled('delivery_note_no')) {
            $query->where('delivery_note_no', 'like', "%{$request->delivery_note_no}%");
        }
        if ($request->filled('delivery_return_no')) {
            $query->where('delivery_return_no', 'like', "%{$request->delivery_return_no}%");
        }

        // Filter by vendor name (for custom vendor input)
        if ($request->filled('vendor_name')) {
            $query->where('vendor_name', 'like', "%{$request->vendor_name}%");
        }

        // Filter by return destination
        if ($request->filled('return_destination')) {
            $query->where('return_destination', 'like', "%{$request->return_destination}%");
        }

        // Enhanced Search - include document numbers and vendor names
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('location', 'like', "%{$search}%")
                  ->orWhere('cluster', 'like', "%{$search}%")
                  ->orWhere('site_id', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhere('delivery_order_no', 'like', "%{$search}%")
                  ->orWhere('delivery_note_no', 'like', "%{$search}%")
                  ->orWhere('delivery_return_no', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%")
                  ->orWhere('return_destination', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('project', function($projectQuery) use ($search) {
                      $projectQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('subProject', function($subProjectQuery) use ($search) {
                      $subProjectQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'transaction_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        // Add secondary sort by ID for consistent ordering
        if ($sortBy !== 'id') {
            $query->orderBy('id', 'desc');
        }

        $transactions = $query->paginate(15)->withQueryString();

        // Data untuk filter dropdown
        $vendors = Vendor::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $subProjects = SubProject::orderBy('name')->get();
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.transactions.index', compact('transactions', 'vendors', 'projects', 'subProjects', 'users'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'vendor', 'project', 'subProject', 'details.material.category']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function export(Request $request)
    {
        $startDate = $request->get('date_from');
        $endDate = $request->get('date_to');
        $projectId = $request->get('project_id');

        $fileName = 'transaksi_detail_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new TransactionsDetailExport($startDate, $endDate, $projectId), 
            $fileName
        );
    }

    /**
     * Get chart data for analytics dashboard
     */
    public function getChartData(Request $request)
    {
        $period = $request->get('period', 30); // days
        
        // Build query with all possible filters from the main filter form
        $query = Transaction::query();
        
        // Apply filters based on request parameters (same as main index filtering)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('location', 'like', "%{$search}%")
                  ->orWhere('cluster', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhere('delivery_order_no', 'like', "%{$search}%")
                  ->orWhere('delivery_note_no', 'like', "%{$search}%")
                  ->orWhere('delivery_return_no', 'like', "%{$search}%")
                  ->orWhere('return_destination', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->get('project_id'));
        }

        if ($request->filled('sub_project_id')) {
            $query->where('sub_project_id', $request->get('sub_project_id'));
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->get('vendor_id'));
        }

        if ($request->filled('vendor_name')) {
            $query->where('vendor_name', 'like', '%' . $request->get('vendor_name') . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->get('date_to'));
        }

        // Document number filters
        if ($request->filled('delivery_order_no')) {
            $query->where('delivery_order_no', 'like', '%' . $request->get('delivery_order_no') . '%');
        }

        if ($request->filled('delivery_note_no')) {
            $query->where('delivery_note_no', 'like', '%' . $request->get('delivery_note_no') . '%');
        }

        if ($request->filled('delivery_return_no')) {
            $query->where('delivery_return_no', 'like', '%' . $request->get('delivery_return_no') . '%');
        }

        if ($request->filled('return_destination')) {
            $query->where('return_destination', 'like', '%' . $request->get('return_destination') . '%');
        }

        // Project Chart Data - Group by project and sum counts per type
        $projectQuery = clone $query;
        $projectQuery = $projectQuery->with('project')
            ->selectRaw('project_id, COUNT(*) as count')
            ->join('projects', 'transactions.project_id', '=', 'projects.id')
            ->groupBy('project_id')
            ->get();

        $projectData = $projectQuery->map(function($item) {
            return [
                'name' => $item->project ? $item->project->name : 'Unknown Project',
                'count' => $item->count
            ];
        })->toArray();

        // Location Chart Data - Group by location and type
        $locationQuery = clone $query;
        $locationQuery = $locationQuery->selectRaw('location, type, COUNT(*) as count')
            ->groupBy('location', 'type')
            ->get()
            ->groupBy('location');

        $locationData = [];
        foreach($locationQuery as $location => $types) {
            $locationData[$location] = [
                'penerimaan' => 0,
                'pengambilan' => 0,
                'pengembalian' => 0,
                'peminjaman' => 0
            ];
            
            foreach($types as $typeData) {
                $locationData[$location][$typeData->type] = $typeData->count;
            }
        }

        // Transaction totals by type
        $totalsQuery = clone $query;
        $totals = $totalsQuery->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $totalsData = [
            'penerimaan' => $totals['penerimaan'] ?? 0,
            'pengambilan' => $totals['pengambilan'] ?? 0,
            'pengembalian' => $totals['pengembalian'] ?? 0,
            'peminjaman' => $totals['peminjaman'] ?? 0,
        ];

        // Summary data
        $summaryQuery = clone $query;
        return response()->json([
            'projectData' => $projectData,
            'locationData' => $locationData,
            'totals' => $totalsData,
            'summary' => [
                'totalTransactions' => array_sum($totalsData),
                'totalProjects' => $summaryQuery->distinct('project_id')->count('project_id'),
                'totalLocations' => $summaryQuery->distinct('location')->count('location'),
                'filtersApplied' => collect($request->except(['period']))->filter()->count() > 0
            ]
        ]);
    }
}
