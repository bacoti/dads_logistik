<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

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

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('location', 'like', "%{$search}%")
                  ->orWhere('cluster', 'like', "%{$search}%")
                  ->orWhere('site_id', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'transaction_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $transactions = $query->paginate(15)->withQueryString();

        // Data untuk filter dropdown
        $vendors = Vendor::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.transactions.index', compact('transactions', 'vendors', 'projects', 'users'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'vendor', 'project', 'subProject', 'details.material.category']);
        return view('admin.transactions.show', compact('transaction'));
    }
}
