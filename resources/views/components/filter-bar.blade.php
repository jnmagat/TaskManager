{{-- resources/views/components/filter-bar.blade.php --}}
@props([
    'searchName'    => 'search',
    'showFrom'      => false,
    'showTo'        => false,
    'showDueDate'   => false,

    'levels'        => null,
    'category'      => null,
    'priority'      => null,
    'status'        => null,
    'assignees'     => null,

    'clearRoute'    => null,
    'searchPlaceholder' => 'Search…', 
])

@php
    $dropdowns = [
        'level'     => $levels,
        'category'  => $category,
        'priority'  => $priority,
        'status'    => $status,
        'assignees' => $assignees,
    ];

    $active = collect([
        $searchName, 'registered_from', 'registered_to', 'due_date',
        'level', 'category', 'priority', 'status', 'assignees',
    ])->filter(fn ($k) => request()->filled($k));

    $inputBase = 'px-3 py-2 rounded bg-gray-800 border border-gray-600 text-gray-200
                  focus:outline-none focus:ring-2 focus:ring-indigo-500';
@endphp

<form method="GET" action="" class="mb-4 flex flex-wrap items-center gap-3">

    {{-- search box --}}
    <input 
    name="{{ $searchName }}" 
    value="{{ request($searchName) }}"
    placeholder="{{ $searchPlaceholder }}"   
    class="w-48 {{ $inputBase }}">

    {{-- dynamic dropdowns --}}
    @foreach($dropdowns as $name => $options)
        @if($options)
            <select name="{{ $name }}" class="w-36 {{ $inputBase }}">
                <option value="">{{ ucfirst($name) }}</option>
                @foreach($options as $opt)
                    <option value="{{ $opt->id ?? $opt }}"
                            @selected(request($name) == ($opt->id ?? $opt))>
                        {{ $opt->name ?? $opt }}
                    </option>
                @endforeach
            </select>
        @endif
    @endforeach

    {{-- dates --}}
    @if($showFrom)
        <input type="date" name="registered_from"
               value="{{ request('registered_from') }}"
               class="{{ $inputBase }}">
    @endif

    @if($showTo)
        <input type="date" name="registered_to"
               value="{{ request('registered_to') }}"
               class="{{ $inputBase }}">
    @endif

    @if($showDueDate)
        <input type="date" name="due_date"
               value="{{ request('due_date') }}"
               class="{{ $inputBase }}">
    @endif

    {{-- buttons --}}
    <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        Filter
    </button>

    @if($active->isNotEmpty())
        <a href="{{ $clearRoute ? route($clearRoute) : url()->current() }}"
           class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-600">
            Clear
        </a>
    @endif
</form>
