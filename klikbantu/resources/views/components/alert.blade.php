@vite(['resources/css/app.css', 'resources/js/app.js'])

@if(session('error'))
<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-lg text-sm mb-4">
    {{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-lg text-sm mb-4">
    {{ session('success') }}
</div>
@endif