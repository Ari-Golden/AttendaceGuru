@extends('layouts.guru')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-xl font-bold mb-4">Edit Lokasi Absensi</h1>

    <!-- Peta -->
    <div id="map" class="w-full h-64 rounded"></div>

    <form action="{{ route('attendance-location.update', $location->id) }}" method="POST" class="mt-4">
        @csrf
        @method('PUT') 
        
        <!-- Input Koordinat -->
        <div class="grid gap-4 mb-4">
            <div>
                <label for="latitude" class="block text-sm font-medium mb-2">Latitude</label>
                <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $location->latitude) }}" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label for="longitude" class="block text-sm font-medium mb-2">Longitude</label>
                <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $location->longitude) }}" class="w-full p-2 border rounded" required>
            </div>
        </div>

        <!-- Input Radius -->
        <div class="mb-6">
            <label for="radius" class="block text-sm font-medium mb-2">Radius Absen (meter)</label>
            <div class="flex items-center gap-4">
                <input type="range" id="radius" name="radius" min="10" max="1000" step="10" value="{{ old('radius', $location->radius) }}" class="flex-1">
                <input type="number" id="radiusDisplay" class="w-20 p-2 border rounded" min="10" max="1000" value="{{ old('radius', $location->radius) }}" readonly>
            </div>
        </div>

        <!-- Input Deskripsi Lokasi -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium mb-2">Deskripsi Lokasi</label>
            <input type="text" id="description" name="description" value="{{ old('description', $location->description) }}" class="w-full p-2 border rounded" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Lokasi</button>
    </form>
</div>

<!-- Leaflet JS & CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil nilai awal dari input koordinat
        let lat = parseFloat(document.getElementById("latitude").value) || -6.200000;
        let lng = parseFloat(document.getElementById("longitude").value) || 106.816666;
        let radius = parseInt(document.getElementById("radius").value) || 100;

        // Inisialisasi peta
        let map = L.map('map').setView([lat, lng], 15);

        // Tambahkan Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan marker ke peta
        let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        // Tambahkan lingkaran radius
        let circle = L.circle([lat, lng], {
            color: 'blue',
            fillColor: '#3b82f6',
            fillOpacity: 0.3,
            radius: radius
        }).addTo(map);

        // Event ketika marker dipindahkan
        marker.on('dragend', function(e) {
            let position = marker.getLatLng();
            document.getElementById("latitude").value = position.lat.toFixed(6);
            document.getElementById("longitude").value = position.lng.toFixed(6);

            // Update posisi lingkaran
            circle.setLatLng(position);
        });

        // Event ketika radius diubah
        document.getElementById("radius").addEventListener("input", function() {
            let newRadius = parseInt(this.value);
            document.getElementById("radiusDisplay").value = newRadius;
            circle.setRadius(newRadius);
        });
    });
</script>
@endsection
