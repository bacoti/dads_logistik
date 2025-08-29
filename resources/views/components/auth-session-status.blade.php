@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl font-medium text-sm flex items-center']) }}>
        <i class="fas fa-check-circle text-green-600 mr-2"></i>
        {{ $status }}
    </div>
@endif
