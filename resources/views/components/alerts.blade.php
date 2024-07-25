@if (session('success'))
    <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
        {{ session('error') }}
    </div>
@endif

