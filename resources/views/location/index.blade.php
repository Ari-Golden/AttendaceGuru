@extends('layouts.guru')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Daftar Lokasi Absensi</h1>
                    <a href="{{ route('attendance-location.create') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg transition-all duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Lokasi
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Koordinat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Radius</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($locations as $location)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $location->description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div 
                                        id="map-{{ $location->id }}" 
                                        class="w-32 h-32 rounded-md shadow-sm"
                                        style="height: 100px;"
                                        data-lat="{{ $location->latitude }}"
                                        data-lng="{{ $location->longitude }}"
                                        data-radius="{{ $location->radius }}">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($location->latitude, 6) }}, {{ number_format($location->longitude, 6) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        {{ $location->radius }} meter
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $location->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-4">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('attendance-location.edit', $location->id) }}" 
                                           class="bg-yellow-500 text-white px-3 py-1 rounded">
                                            Edit
                                        </a>
                                                
                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('attendance-location.destroy', $location->id) }}" method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada lokasi yang ditambahkan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .leaflet-container {
        background: #f8fafc !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        document.querySelectorAll('[id^="map-"]').forEach((mapElement) => {
            const lat = parseFloat(mapElement.getAttribute('data-lat'));
            const lng = parseFloat(mapElement.getAttribute('data-lng'));
            const radius = parseFloat(mapElement.getAttribute('data-radius'));

            if (!isNaN(lat) && !isNaN(lng) && !isNaN(radius)) {
                let map = L.map(mapElement, {
                    attributionControl: false,
                    zoomControl: false,
                    dragging: false,
                    doubleClickZoom: false,
                    boxZoom: false,
                    scrollWheelZoom: false,
                    tap: false
                }).setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                L.marker([lat, lng]).addTo(map);
                L.circle([lat, lng], {
                    color: '#3b82f6',
                    fillColor: '#60a5fa',
                    fillOpacity: 0.2,
                    radius: radius
                }).addTo(map);
            } else {
                console.error("Koordinat tidak valid untuk elemen: ", mapElement);
            }
        });
    }, 500);
});
</script>
@endpush

@endsection
