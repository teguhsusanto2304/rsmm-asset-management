@props(['roles', 'selectedRoles' => [], 'name' => 'roles'])

<div class="space-y-3">
    <label class="block text-sm font-semibold text-gray-700">
        <span class="flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">security</span>
            Permission Role
        </span>
    </label>

    @if($roles->isEmpty())
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-700">
                Belum ada role yang dibuat. 
                <a href="{{ route('roles.create') }}" class="font-semibold underline hover:text-blue-900">Buat role baru</a>
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-80 overflow-y-auto border border-gray-200 rounded-lg p-4 bg-gray-50">
            @foreach($roles as $role)
                <label class="relative flex items-start gap-3 p-3 bg-white border border-gray-200 rounded-lg hover:border-primary hover:shadow-sm cursor-pointer transition-all group">
                    <input type="checkbox" 
                           name="{{ $name }}[]" 
                           value="{{ $role->id }}"
                           {{ in_array((int)$role->id, array_map('intval', $selectedRoles)) ? 'checked' : '' }}
                           class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
                    
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900">
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </div>
                        <div class="text-xs text-gray-600 mt-1">
                            @if($role->permissions_count ?? 0)
                                <span class="inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">check_circle</span>
                                    {{ $role->permissions_count }} permissions
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-gray-500">
                                    <span class="material-symbols-outlined text-xs">info</span>
                                    No permissions
                                </span>
                            @endif
                        </div>
                        @if($role->description)
                            <p class="text-xs text-gray-600 mt-2 italic">{{ $role->description }}</p>
                        @endif
                    </div>
                </label>
            @endforeach
        </div>

        @error($name)
            <p class="text-sm text-red-600 mt-2 flex items-center gap-1">
                <span class="material-symbols-outlined text-base">error</span>
                {{ $message }}
            </p>
        @enderror

        <div class="text-xs text-gray-600 mt-2 flex items-center gap-2 p-2 bg-blue-50 rounded">
            <span class="material-symbols-outlined">info</span>
            Pilih satu atau lebih role untuk user ini
        </div>
    @endif
</div>
