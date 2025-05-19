<x-app-layout>
<div class="container py-4">
    <h2 class="fw-bold">Store Details</h2>
    <div class="card mt-4">
        <div class="card-body">
            <h4 class="card-title">{{ $store->name }}</h4>
            <p class="card-text"><strong>Address:</strong> {{ $store->address }}</p>
            <p class="card-text"><strong>Contact Info:
                @php
                                    $contact = is_array($store->contact_info) ? $store->contact_info : json_decode($store->contact_info, true);
                                @endphp
                                @if($contact)
                                    <ul class="mb-0 ps-3">
                                        @foreach($contact as $key => $value)
                                            <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <em>No contact info</em>
                                @endif
            </p>
        </div>
    </div>
    <a href="{{ route('stores.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
</x-app-layout>