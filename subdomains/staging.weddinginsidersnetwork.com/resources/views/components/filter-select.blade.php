<div> 
    <div class="mt-1 hs-dropdown [--auto-close:inside] relative sm:inline-flex z-[100]">
    <button id="hs-dropdown-auto-close-inside" type="button" class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border bg-white hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
      {{ $filter->name }}
      <svg class="hs-dropdown-open:rotate-180 size-2.5" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>

    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden bg-white shadow-md rounded-lg mt-2" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-auto-close-inside">
      <div class="p-1 space-y-0.5">
        @foreach(json_decode($filter->allowed_values) as $option)
        @if($filter->name == 'Event')
        <div class="flex items-center gap-x-2 py-2 px-3 rounded-lg hover:bg-gray-100 hover:cursor-pointer">
          <input id="filter[{{ $filter->name }}][{{ $option }}]" name="filter[{{ $filter->name }}][{{ $option }}]" value="{{ $option }}" type="radio" {{ request()->filter && array_key_exists($filter->name, request()->filter) && $option == request()->filter[$filter->name] ? 'checked' : '' }} class="shrink-0 rounded-full text-win-purple focus:ring-win-purple checked:border-win-purple disabled:opacity-50 disabled:pointer-events-none">
          <label for="filter[{{ $filter->name }}][{{ $option }}]" class="grow cursor-pointer">
            <span class="block text-sm">{{ \App\Models\Event::where('id', $option)->first()->name }}</span>
            
          </label>
        </div> 
        @else
        <div class="flex items-center gap-x-2 py-2 px-3 rounded-lg hover:bg-gray-100 hover:cursor-pointer">
          <input id="filter[{{ $filter->name }}][{{ $option }}]" name="filter[{{ $filter->name }}][{{ $option }}]" value="{{ $option }}" type="radio" {{ request()->filter && array_key_exists($filter->name, request()->filter) && $option == request()->filter[$filter->name] ? 'checked' : '' }} class="shrink-0 rounded-full text-win-purple focus:ring-win-purple checked:border-win-purple disabled:opacity-50 disabled:pointer-events-none">
          <label for="filter[{{ $filter->name }}][{{ $option }}]" class="grow cursor-pointer">
            <span class="block text-sm">{{ $option }}</span>
            
          </label>
        </div> 
        @endif
        @endforeach
      </div>
    </div>
  </div>
</div>