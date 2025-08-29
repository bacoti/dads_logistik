<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistik transaksi berdasarkan user yang login
        $stats = [
            'today' => Transaction::where('user_id', $user->id)
                        ->whereDate('transaction_date', today())
                        ->count(),
            
            'thisWeek' => Transaction::where('user_id', $user->id)
                           ->whereBetween('transaction_date', [
                               now()->startOfWeek(), 
                               now()->endOfWeek()
                           ])
                           ->count(),
            
            'thisMonth' => Transaction::where('user_id', $user->id)
                            ->whereMonth('transaction_date', now()->month)
                            ->whereYear('transaction_date', now()->year)
                            ->count(),
            
            'pending' => Transaction::where('user_id', $user->id)
                          ->whereDate('transaction_date', today())
                          ->count() // Atau bisa diganti dengan status pending jika ada
        ];
        
        // Transaksi terbaru untuk user
        $recentTransactions = Transaction::where('user_id', $user->id)
                               ->with(['vendor', 'project', 'subProject'])
                               ->orderBy('created_at', 'desc')
                               ->take(5)
                               ->get();
        
        return view('user.dashboard', compact('stats', 'recentTransactions'));
    }
}
