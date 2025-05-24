<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Category Details</h2>
    </x-slot>

    <div class="py-4 container">
        <div class="card p-3 shadow-sm">
            <h5>Name: {{ $category->name }}</h5>
            <p>Created at: {{ $category->created_at->format('d M Y') }}</p>
        </div>

        <a href="{{ route('categories.index') }}" class="btn btn-secondary mt-3">← Back</a>
    </div>
</x-app-layout>
