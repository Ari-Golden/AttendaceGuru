@extends('layouts.guru')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-xl font-bold mb-4">Tentukan Lokasi Absen</h1>
    
   

    <!-- Peta dengan ARIA label -->
    <div 
        id="map" 
        class="w-full h-64 rounded"
        role="application"
        aria-label="Peta interaktif untuk menentukan lokasi absensi"
        tabindex="0">
    </div>

    <form action="{{ route('attendance-location.store') }}" method="POST" class="mt-4">
        {{-- <form action="{{ route('attendance-location.store') }}" method="POST" class="mt-4">     --}}
        @csrf
        
        <!-- Input Koordinat -->
        <div class="grid gap-4 mb-4">
            <div>
                <label for="latitude" class="block text-sm font-medium mb-2">
                    Latitude
                    <span class="sr-only">(Garis lintang)</span>
                </label>
                <input 
                    type="text" 
                    id="latitude" 
                    name="latitude" 
                    class="w-full p-2 border rounded"
                    aria-describedby="latHelp"
                    required>
                <p id="latHelp" class="sr-only">
                    Nilai desimal antara -90 hingga 90 derajat
                </p>
            </div>

            <div>
                <label for="longitude" class="block text-sm font-medium mb-2">
                    Longitude
                    <span class="sr-only">(Garis bujur)</span>
                </label>
                <input 
                    type="text" 
                    id="longitude" 
                    name="longitude" 
                    class="w-full p-2 border rounded"
                    aria-describedby="lngHelp"
                    required>
                <p id="lngHelp" class="sr-only">
                    Nilai desimal antara -180 hingga 180 derajat
                </p>
            </div>
        </div>

        <!-- Input Radius dengan Slider -->
        <div class="mb-6">
            <label for="radius" class="block text-sm font-medium mb-2">
                Radius Absen (meter)
                <span class="sr-only">(Besar radius dalam satuan meter)</span>
            </label>
            <div class="flex items-center gap-4">
                <input 
                    type="range" 
                    id="radius" 
                    name="radius"
                    min="10" 
                    max="1000" 
                    step="10" 
                    value="100"
                    class="flex-1"
                    aria-describedby="radiusHelp">
                <input 
                    type="number" 
                    id="radiusDisplay" 
                    class="w-20 p-2 border rounded"
                    min="10" 
                    max="1000"
                    readonly>
            </div>
            <p id="radiusHelp" class="text-sm text-gray-600 mt-1">
                Rentang: 10m - 1000m
            </p>
        </div>

        <!-- Input Deskripsi Lokasi -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium mb-2">
                Deskripsi Lokasi
                <span class="sr-only">(Deskripsi singkat tentang lokasi absensi)</span>
            </label>
            <input 
                type="text" 
                id="description" 
                name="description" 
                class="w-full p-2 border rounded"
                aria-describedby="descriptionHelp"
                required>
            <p id="descriptionHelp" class="sr-only">
                Deskripsi singkat tentang lokasi absensi
            </p>
        </div>

        <button 
            type="submit" 
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Simpan Lokasi
        </button>
    </form>
</div>

<script>
    // Inisialisasi Peta
    const map = L.map('map', {
        keyboard: true,
        keyboardPanDelta: 100
    }).setView([-6.2897839381461, 107.39104326814413], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 20,
        
    }).addTo(map);

    // Marker dengan deskripsi aksesibel
    let marker = L.marker(map.getCenter(), {
        draggable: true,
        alt: 'Lokasi absensi saat ini',
        title: 'Geser untuk mengubah lokasi'
    }).addTo(map);

    // Update koordinat saat marker digeser
    marker.on('dragend', function(e) {
        const latLng = e.target.getLatLng();
        updateCoordinates(latLng.lat, latLng.lng);

    });

    // Klik peta untuk menambah marker
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoordinates(e.latlng.lat, e.latlng.lng);
    });

    // Geocoder dengan ARIA label
    const geocoder = L.Control.geocoder({
        defaultMarkGeocode: false,
        geocoder: L.Control.Geocoder.nominatim(),
        placeholder: 'Cari alamat atau tempat...',
        errorMessage: 'Lokasi tidak ditemukan',
        suggestMinLength: 3,
        queryOptions: {
            bounded: 1,
            countrycodes: 'id' // Batasi ke Indonesia
        }
    }).on('markgeocode', function(e) {
        map.setView(e.geocode.center, 15);
        marker.setLatLng(e.geocode.center);
        updateCoordinates(e.geocode.center.lat, e.geocode.center.lng);
    }).addTo(map);

    // Fungsi update koordinat
    function updateCoordinates(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
    }

    // Update radius secara realtime
    const radiusInput = document.getElementById('radius');
    const radiusDisplay = document.getElementById('radiusDisplay');
    
    radiusInput.addEventListener('input', function() {
        radiusDisplay.value = this.value;
    });
    
    // Inisialisasi nilai awal
    radiusDisplay.value = radiusInput.value;

    // Keyboard navigation untuk search
    document.getElementById('search-location').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            geocoder.options.geocoder.geocode(this.value, function(results) {
                if (results.length > 0) {
                    geocoder.fire('markgeocode', { geocode: results[0] });
                }
            });
        }
    });
</script>
@endsection