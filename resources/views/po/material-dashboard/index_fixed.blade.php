@extends('layouts.app')

@section('title', 'Material Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">📦 Material Tracking Dashboard</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('po.material-receipt.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-list mr-2"></i>Kelola Penerimaan
                    </a>
                    <a href="{{ route('po.material-usage.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-minus mr-2"></i>Catat Penggunaan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Materials -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-white bg-opacity-30 rounded-md flex items-center justify-center">
                                <i class="fas fa-boxes text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-blue-100 truncate">Total Materials</dt>
                                <dd class="text-lg font-medium text-white">{{ $stats['total_materials'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Materials -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-white bg-opacity-30 rounded-md flex items-center justify-center">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-green-100 truncate">Materials Tersedia</dt>
                                <dd class="text-lg font-medium text-white">{{ $stats['active_materials'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-white bg-opacity-30 rounded-md flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-yellow-100 truncate">Stock Menipis</dt>
                                <dd class="text-lg font-medium text-white">{{ $stats['low_stock_materials'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Out of Stock -->
            <div class="bg-gradient-to-r from-red-500 to-red-600 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-white bg-opacity-30 rounded-md flex items-center justify-center">
                                <i class="fas fa-times-circle text-white"></i>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-red-100 truncate">Stock Habis</dt>
                                <dd class="text-lg font-medium text-white">{{ $stats['out_of_stock_materials'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Today's Transactions -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-day text-indigo-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Transaksi Hari Ini</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $stats['total_transactions_today'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Alerts -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-bell text-red-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Alert Aktif</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $stats['active_alerts'] }}
                                    @if($stats['critical_alerts'] > 0)
                                        <span class="text-red-600 font-bold">({{ $stats['critical_alerts'] }} kritis)</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Transactions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-history mr-2 text-blue-500"></i>Transaksi Terbaru
                    </h3>
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @forelse($recentTransactions as $transaction)
                                <li>
                                    <div class="relative pb-8">
                                        @if (!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full flex items-center justify-center text-white
                                                    @if($transaction->transaction_type === 'receipt') bg-green-500
                                                    @elseif($transaction->transaction_type === 'usage') bg-blue-500
                                                    @elseif($transaction->transaction_type === 'return') bg-yellow-500
                                                    @else bg-gray-500
                                                    @endif">
                                                    @if($transaction->transaction_type === 'receipt')
                                                        <i class="fas fa-plus"></i>
                                                    @elseif($transaction->transaction_type === 'usage')
                                                        <i class="fas fa-minus"></i>
                                                    @elseif($transaction->transaction_type === 'return')
                                                        <i class="fas fa-undo"></i>
                                                    @else
                                                        <i class="fas fa-edit"></i>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div>
                                                    <div class="text-sm">
                                                        <span class="font-medium text-gray-900">
                                                            {{ $transaction->transaction_type_text }}
                                                        </span>
                                                    </div>
                                                    <p class="mt-0.5 text-sm text-gray-500">
                                                        {{ $transaction->formatted_quantity }} - {{ $transaction->activity_name ?? 'N/A' }}
                                                    </p>
                                                </div>
                                                <div class="mt-2 text-sm text-gray-700">
                                                    <p>{{ $transaction->transaction_date->format('d M Y') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center py-4 text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2"></i>
                                    <p>Belum ada transaksi</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('po.material-receipt.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                            Lihat semua transaksi →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Low Stock Materials -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>Material Stock Rendah
                    </h3>
                    @if($lowStockMaterials->count() > 0)
                        <div class="space-y-4">
                            @foreach($lowStockMaterials as $stock)
                                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div>
                                        <p class="font-medium text-gray-900">Material ID: {{ $stock->id }}</p>
                                        <p class="text-sm text-gray-600">Stock: {{ $stock->current_stock }} {{ $stock->unit }}</p>
                                        <p class="text-xs text-yellow-600">Minimum: {{ $stock->minimum_stock }} {{ $stock->unit }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Stock Rendah
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-check-circle text-4xl mb-2 text-green-500"></i>
                            <p>Semua material stock aman</p>
                        </div>
                    @endif
                    <div class="mt-6">
                        <a href="{{ route('po.material-stock.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                            Lihat semua stock →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Usage -->
        @if($monthlyUsage->count() > 0)
        <div class="mt-8">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-chart-bar mr-2 text-purple-500"></i>Penggunaan Material Bulan Ini
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($monthlyUsage as $usage)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $usage['material_name'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $usage['transactions_count'] }} transaksi</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-purple-600">{{ number_format($usage['total_used'], 2) }}</p>
                                        <p class="text-sm text-gray-500">{{ $usage['unit'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Active Alerts -->
        @if($activeAlerts->count() > 0)
        <div class="mt-8">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        <i class="fas fa-bell mr-2 text-red-500"></i>Alert Aktif
                    </h3>
                    <div class="space-y-4">
                        @foreach($activeAlerts as $alert)
                            <div class="flex items-center p-4 border-l-4 
                                @if($alert->severity === 'critical') border-red-400 bg-red-50
                                @elseif($alert->severity === 'warning') border-yellow-400 bg-yellow-50
                                @else border-blue-400 bg-blue-50
                                @endif rounded">
                                <div class="flex-1">
                                    <p class="font-medium 
                                        @if($alert->severity === 'critical') text-red-800
                                        @elseif($alert->severity === 'warning') text-yellow-800
                                        @else text-blue-800
                                        @endif">
                                        {{ $alert->title }}
                                    </p>
                                    <p class="text-sm 
                                        @if($alert->severity === 'critical') text-red-700
                                        @elseif($alert->severity === 'warning') text-yellow-700
                                        @else text-blue-700
                                        @endif">
                                        {{ $alert->message }}
                                    </p>
                                    <p class="text-xs 
                                        @if($alert->severity === 'critical') text-red-600
                                        @elseif($alert->severity === 'warning') text-yellow-600
                                        @else text-blue-600
                                        @endif mt-1">
                                        {{ $alert->triggered_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="ml-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($alert->severity === 'critical') bg-red-100 text-red-800
                                        @elseif($alert->severity === 'warning') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucfirst($alert->severity) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh dashboard every 5 minutes
    setTimeout(function() {
        location.reload();
    }, 300000);
</script>
@endpush
