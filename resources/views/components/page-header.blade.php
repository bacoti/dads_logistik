@props([
    'title' => '',
    'subtitle' => '',
    'breadcrumbs' => [],
    'icon' => '',
    'action' => null
])

<div class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Breadcrumb Navigation -->
        @if(!empty($breadcrumbs))
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        <li class="inline-flex items-center">
                            @if($index > 0)
                                <svg class="w-5 h-5 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                            
                            @if(isset($breadcrumb['url']) && $index < count($breadcrumbs) - 1)
                                <a href="{{ $breadcrumb['url'] }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                                    @if(isset($breadcrumb['icon']) && $index === 0)
                                        {!! $breadcrumb['icon'] !!}
                                    @endif
                                    {{ $breadcrumb['title'] }}
                                </a>
                            @else
                                <span class="inline-flex items-center text-sm font-medium text-gray-700">
                                    {{ $breadcrumb['title'] }}
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center min-w-0 flex-1">
                @if($icon)
                    <div class="flex-shrink-0 mr-4 text-red-600">
                        {!! $icon !!}
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        {{ $title }}
                    </h1>
                    @if($subtitle)
                        <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
            
            @if(isset($action))
                <div class="flex-shrink-0 ml-4">
                    {{ $action }}
                </div>
            @endif
        </div>
    </div>
</div>
