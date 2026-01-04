<li>
    <a href="{{ route('locations.index', ['id'=>$location->id]) }}"
       class="flex items-center gap-2 p-2 rounded-lg
       {{ request('location') == $location->id ? 'bg-primary/10 text-primary font-bold' : 'hover:bg-[#f0f3f4]' }}">
        <span class="material-symbols-outlined text-lg">domain</span>
        {{ $location->name }}
    </a>

    @if ($location->children->count())
        <ul class="pl-4 mt-1 border-l">
            @foreach ($location->children as $child)
                @include('admin.locations.partials.tree', ['location' => $child])
            @endforeach
        </ul>
    @endif
</li>
