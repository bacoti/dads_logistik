<?php

namespace App\Http\Controllers\Po;

use App\Http\Controllers\Controller;
use App\Models\PoMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPoMaterials = PoMaterial::where('user_id', Auth::id())->count();
        $pendingPoMaterials = PoMaterial::where('user_id', Auth::id())->where('status', 'pending')->count();
        $approvedPoMaterials = PoMaterial::where('user_id', Auth::id())->where('status', 'approved')->count();
        $rejectedPoMaterials = PoMaterial::where('user_id', Auth::id())->where('status', 'rejected')->count();

        $stats = [
            'total_po' => $totalPoMaterials,
            'active_po' => PoMaterial::where('user_id', Auth::id())->where('status', 'active')->count(),
            'completed_po' => PoMaterial::where('user_id', Auth::id())->where('status', 'completed')->count(),
            'this_month' => PoMaterial::where('user_id', Auth::id())
                ->whereMonth('release_date', now()->month)
                ->whereYear('release_date', now()->year)
                ->count(),
        ];

        $recentPoMaterials = PoMaterial::with(['project', 'subProject'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('po.dashboard', compact(
            'stats', 
            'recentPoMaterials', 
            'totalPoMaterials', 
            'pendingPoMaterials', 
            'approvedPoMaterials', 
            'rejectedPoMaterials'
        ));
    }
}
