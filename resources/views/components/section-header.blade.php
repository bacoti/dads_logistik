@props([
    'title' => '',
    'subtitle' => '',
    'icon' => ''
])

<div class="mb-6">
    <div class="flex items-center">
        @if($icon)
            <div class="flex-shrink-0 mr-3 text-red-600">
                {!! $icon !!}
            </div>
        @endif
        <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            @if($subtitle)
                <p class="text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    <div class="mt-4 border-b border-gray-200"></div>
</div>
