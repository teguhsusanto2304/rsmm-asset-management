<li>
    <a href="{{ route('categories.index', ['id'=>$category->id]) }}"
       class="flex items-center gap-2 p-2 rounded-lg
       {{ request('category') == $category->id ? 'bg-primary/10 text-primary font-bold' : 'hover:bg-[#f0f3f4]' }}">
        <span class="material-symbols-outlined text-lg">domain</span>
        {{ $category->name }}
    </a>

    @if ($category->children->count())
        <ul class="pl-4 mt-1 border-l">
            @foreach ($category->children as $child)
                @include('admin.categories.partials.tree', ['category' => $child])
            @endforeach
        </ul>
    @endif
</li>
