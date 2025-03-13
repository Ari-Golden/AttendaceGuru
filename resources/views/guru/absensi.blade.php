@extends('layouts.attendance')

@section('content')
<div class="max-w-md mt-10 p-6 mx-auto bg-white rounded shadow-md">
    <!-- Notifikasi -->
    @if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
    @endif

    @if(session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
    @endif

    <h2 class="mb-4 text-xl font-bold">Absensi Guru</h2>

    <form method="POST" action="{{ route('attendance.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Detail Titik Koordinat Sekolah -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Detail Titik Koordinat Sekolah</label>
            <input type="text" id="description" name="description" value="{{ optional($tikorSekolah)->description }}" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md">
            <input type="hidden" id="latitude_tikor" name="latitude_tikor" value="{{ optional($tikorSekolah)->latitude }}" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md">
            <input type="hidden" id="longitude_tikor" name="longitude_tikor" value="{{ optional($tikorSekolah)->longitude }}" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md">
            <input type="hidden" id="radius_tikor" name="radius_tikor" value="{{ optional($tikorSekolah)->radius }}">
        </div>

        <!-- Nama Guru -->
        <div>
            <label for="nama_guru" class="block text-sm font-medium text-gray-700">Nama Guru</label>
            <input type="text" id="nama_guru" name="nama_guru" value="{{ Auth::user()->name }}" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md">
        </div>

        <!-- ID Guru -->
        <div>
            <label for="id_guru" class="block text-sm font-medium text-gray-700">ID Guru</label>
            <input type="text" id="id_guru" name="id_guru" value="{{ Auth::user()->id }}" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md cursor-not-allowed">
        </div>

        <!-- Tanggal Absen -->
        <div>
            <label for="tgl_absen" class="block text-sm font-medium text-gray-700">Tanggal Absen</label>
            <input type="text" id="tgl_absen" name="tgl_absen" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md">
        </div>

        <!-- Jam Absen -->
        <div>
            <label for="jam_absen" class="block text-sm font-medium text-gray-700">Jam Absen</label>
            <input type="text" id="jam_absen" name="jam_absen" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md">
        </div>

        <!-- Lokasi Absen -->
        <div>
            <label for="lokasi_absen" class="block text-sm font-medium text-gray-700">Lokasi Absen</label>
            <input type="hidden" id="lokasi_absen" name="lokasi_absen" placeholder="Mengambil lokasi..." readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md">
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
        </div>

        <!-- Peta -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Peta Lokasi</label>
            <div id="map" class="w-full h-64 mt-1 border border-gray-300 rounded-md"></div>
        </div>

        <!-- Foto Selfie -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Ambil Foto Selfie</label>
            <video id="video" class="w-full border rounded-md transform scale-x-[-1]" autoplay></video>

            <canvas id="canvas" class="hidden"></canvas>

            <img id="photoPreview" class="hidden w-full mt-2 border rounded-md" />
            

            <input type="hidden" id="foto_selfie" name="foto_selfie">
            <button type="button" id="capture" class="mt-2 px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Ambil Foto</button>
        </div>

        <!-- Tombol Absen -->
        <div class="flex space-x-4 mt-4">
            <button type="submit" name="status" value="masuk" class="w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">Absen Masuk</button>
            <button type="submit" name="status" value="pulang" class="w-full px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">Absen Pulang</button>
        </div>
    </form>
</div>

<!-- Leaflet & Geolocation -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil waktu server untuk tanggal dan jam absen
        function updateRealtimeClock() {
        const now = new Date(); // Ambil waktu lokal perangkat

        // Format tanggal (YYYY-MM-DD)
        const tanggal = now.toISOString().split('T')[0];

        // Format jam (HH:mm:ss)
        const jam = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        // Masukkan ke dalam input form
        document.getElementById('tgl_absen').value = tanggal;
        document.getElementById('jam_absen').value = jam;
    }

    // Jalankan fungsi pertama kali
    updateRealtimeClock();

    // Update jam setiap detik
    setInterval(updateRealtimeClock, 1000);
        // Ambil lokasi pengguna
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;
                    document.getElementById('lokasi_absen').value = `Latitude: ${latitude}, Longitude: ${longitude}`;

                    initializeMap(latitude, longitude);
                },
                (error) => {
                    console.error('Error mendapatkan lokasi:', error);
                }
            );
        }

        // Fungsi inisialisasi peta
        function initializeMap(latitude, longitude) {
            const map = L.map('map').setView([latitude, longitude], 15);

            // Tambahkan layer peta
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Ambil titik koordinat sekolah dari input hidden
            const sekolahLat = parseFloat(document.getElementById('latitude_tikor').value);
            const sekolahLng = parseFloat(document.getElementById('longitude_tikor').value);
            const sekolahRadius = parseFloat(document.getElementById('radius_tikor').value);

            // Marker lokasi pengguna
            const userMarker = L.marker([latitude, longitude], {
                    icon: L.icon({
                        iconUrl: 'https://cdn-icons-png.flaticon.com/32/684/684908.png',
                        iconSize: [30, 30]
                    })
                }).addTo(map)
                .bindPopup('<b>Lokasi Anda</b>').openPopup();

            // Marker lokasi sekolah
            const schoolMarker = L.marker([sekolahLat, sekolahLng], {
                    icon: L.icon({
                        iconUrl: 'https://cdn-icons-png.flaticon.com/32/684/684809.png',
                        iconSize: [30, 30]
                    })
                }).addTo(map)
                .bindPopup('<b>Lokasi Sekolah</b><br>Radius: ' + sekolahRadius + ' meter');

            // Lingkaran radius sekolah
            L.circle([sekolahLat, sekolahLng], {
                color: 'blue',
                fillColor: '#blue',
                fillOpacity: 0.3,
                radius: sekolahRadius
            }).addTo(map);
        }


        // Foto selfie
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('capture');
        const fotoSelfieInput = document.getElementById('foto_selfie');
        const photoPreview = document.getElementById('photoPreview');

        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then((stream) => {
                video.srcObject = stream;
                video.play();
            })
            .catch((error) => console.error('Gagal mengakses kamera:', error));

        captureButton.addEventListener('click', function() {
            // Ambil ukuran video dan terapkan ke canvas
            canvas.width = video.videoWidth / 2;  // Kurangi resolusi
            canvas.height = video.videoHeight / 2;
            const context = canvas.getContext('2d');

            // Balik (flip) gambar secara horizontal
            context.translate(canvas.width, 0);
            context.scale(-1, 1);

            // Gambar frame dari video ke canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Kembalikan transformasi supaya tidak mempengaruhi gambar lain
            context.setTransform(1, 0, 0, 1, 0, 0);

            // Konversi ke base64
            const fotoBase64 = canvas.toDataURL('image/png',0.5);
            fotoSelfieInput.value = fotoBase64;

            // Sembunyikan video, tampilkan hasil foto
            video.classList.add('hidden');
            photoPreview.src = fotoBase64;
            photoPreview.classList.remove('hidden');
        });

    });
</script>

@endsection