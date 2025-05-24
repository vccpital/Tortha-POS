<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ isset($category) ? 'Edit' : 'Create' }} Category</h2>
    </x-slot>

    <div class="py-4 container">
        <form method="POST" action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}">
            @csrf
            @if(isset($category))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
            </div>

            <button class="btn btn-primary">{{ isset($category) ? 'Update' : 'Create' }}</button>
        </form>
    </div>
</x-app-layout>
