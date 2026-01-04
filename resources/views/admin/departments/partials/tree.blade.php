<li>
    <a href="{{ route('departments.index', ['id'=>$department->id]) }}"
       class="flex items-center gap-2 p-2 rounded-lg
       {{ request('department') == $department->id ? 'bg-primary/10 text-primary font-bold' : 'hover:bg-[#f0f3f4]' }}">
        <span class="material-symbols-outlined text-lg">domain</span>
        {{ $department->department }}
    </a>

    @if ($department->children->count())
        <ul class="pl-4 mt-1 border-l">
            @foreach ($department->children as $child)
                @include('admin.departments.partials.tree', ['department' => $child])
            @endforeach
        </ul>
    @endif
</li>
