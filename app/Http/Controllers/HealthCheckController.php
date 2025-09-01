<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Transaction;

class HealthCheckController extends Controller
{
    /**
     * Application health check endpoint
     * Access via: /health-check
     */
    public function check()
    {
        $health = [
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'checks' => []
        ];

        // 1. Database connectivity check
        try {
            DB::connection()->getPdo();
            $health['checks']['database'] = ['status' => 'ok'];
        } catch (\Exception $e) {
            $health['status'] = 'unhealthy';
            $health['checks']['database'] = ['status' => 'error', 'message' => $e->getMessage()];
        }

        // 2. Cache system check
        try {
            $cacheKey = 'health_check_' . time();
            Cache::put($cacheKey, 'test', 60);
            $retrieved = Cache::get($cacheKey);
            
            if ($retrieved === 'test') {
                $health['checks']['cache'] = ['status' => 'ok'];
            } else {
                $health['status'] = 'degraded';
                $health['checks']['cache'] = ['status' => 'warning', 'message' => 'Cache read/write issue'];
            }
            
            Cache::forget($cacheKey);
        } catch (\Exception $e) {
            $health['status'] = 'degraded';
            $health['checks']['cache'] = ['status' => 'error', 'message' => $e->getMessage()];
        }

        // 3. Storage check
        try {
            $storagePath = storage_path('app/health_check.txt');
            file_put_contents($storagePath, 'test');
            $content = file_get_contents($storagePath);
            unlink($storagePath);
            
            if ($content === 'test') {
                $health['checks']['storage'] = ['status' => 'ok'];
            } else {
                $health['status'] = 'degraded';
                $health['checks']['storage'] = ['status' => 'warning', 'message' => 'Storage read/write issue'];
            }
        } catch (\Exception $e) {
            $health['status'] = 'degraded';
            $health['checks']['storage'] = ['status' => 'error', 'message' => $e->getMessage()];
        }

        // 4. Application statistics
        try {
            $stats = [
                'users' => [
                    'total' => User::count(),
                    'admins' => User::where('role', 'admin')->count(),
                    'field_users' => User::where('role', 'user')->count(),
                    'po_users' => User::where('role', 'po')->count(),
                ],
                'transactions' => [
                    'total' => Transaction::count(),
                    'today' => Transaction::whereDate('created_at', today())->count(),
                    'this_month' => Transaction::whereMonth('created_at', now()->month)->count(),
                ]
            ];
            
            $health['statistics'] = $stats;
        } catch (\Exception $e) {
            $health['checks']['statistics'] = ['status' => 'error', 'message' => $e->getMessage()];
        }

        // Set HTTP status based on health
        $statusCode = 200;
        if ($health['status'] === 'unhealthy') {
            $statusCode = 503; // Service Unavailable
        } elseif ($health['status'] === 'degraded') {
            $statusCode = 200; // OK but with warnings
        }

        return response()->json($health, $statusCode);
    }

    /**
     * Simple status endpoint for load balancers
     * Access via: /status
     */
    public function status()
    {
        try {
            DB::connection()->getPdo();
            return response('OK', 200);
        } catch (\Exception $e) {
            return response('ERROR', 503);
        }
    }
}
