@extends('layouts.attendance', ['title' => 'Absen Harian'])

@section('content')
<div class="p-4">
    <!-- Notifikasi -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 max-w-md mx-auto">
        <form method="POST" action="{{ route('attendance.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Detail Titik Koordinat Sekolah</label>
                <input type="text" id="description" name="description" value="{{ optional($tikorSekolah)->description }}" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-300">
                <input type="hidden" id="latitude_tikor" name="latitude_tikor" value="{{ optional($tikorSekolah)->latitude }}">
                <input type="hidden" id="longitude_tikor" name="longitude_tikor" value="{{ optional($tikorSekolah)->longitude }}">
                <input type="hidden" id="radius_tikor" name="radius_tikor" value="{{ optional($tikorSekolah)->radius }}">
            </div>

            <div>
                <label for="nama_guru" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Guru</label>
                <input type="text" id="nama_guru" name="nama_guru" value="{{ Auth::user()->name }}" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-300">
            </div>

            <div>
                <label for="id_guru" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID Guru</label>
                <input type="text" id="id_guru1" name="id_guru1" value="{{ Auth::user()->id_guru }}" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md cursor-not-allowed dark:bg-gray-700 dark:text-gray-300">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="tgl_absen" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Absen</label>
                    <input type="text" id="tgl_absen" name="tgl_absen" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-300">
                </div>
                <div>
                    <label for="jam_absen" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Absen</label>
                    <input type="text" id="jam_absen" name="jam_absen" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md dark:bg-gray-700 dark:text-gray-300">
                </div>
            </div>

            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Peta Lokasi</label>
                <div id="map" class="w-full h-64 mt-1 border border-gray-300 rounded-md"></div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ambil Foto Selfie</label>
                <div class="mt-1 flex justify-center">
                    <video id="video" class="w-full h-auto border rounded-md transform scale-x-[-1]" autoplay></video>
                    <canvas id="canvas" class="hidden"></canvas>
                    <img id="photoPreview" class="hidden w-full h-auto mt-2 border rounded-md" />
                </div>
                <input type="hidden" id="foto_selfie" name="foto_selfie">
                <button type="button" id="capture" class="mt-2 w-full px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Ambil Foto</button>
            </div>

            <div class="flex space-x-4 mt-4">
                <button type="submit" name="status" value="masuk" class="w-full px-4 py-3 text-white bg-green-500 rounded-md hover:bg-green-600 font-semibold">Absen Masuk</button>
                <button type="submit" name="status" value="pulang" class="w-full px-4 py-3 text-white bg-red-500 rounded-md hover:bg-red-600 font-semibold">Absen Pulang</button>
            </div>
        </form>
    </div>
</div>

<script>
 document.addEventListener('DOMContentLoaded', function() {
    function updateRealtimeClock() {
        fetch("{{ route('get.server.time') }}")
            .then(response => response.json())
            .then(data => {
                document.getElementById('tgl_absen').value = data.tanggal;
                document.getElementById('jam_absen').value = data.jam;
            });
    }
    setInterval(updateRealtimeClock, 1000);
    updateRealtimeClock();

    function getLocation(retry = 3) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;
                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;
                    initializeMap(latitude, longitude);
                },
                (error) => {
                    console.error('Gagal mendapatkan lokasi:', error);
                    if (retry > 0) {
                        setTimeout(() => getLocation(retry - 1), 2000);
                    } else {
                        alert('Gagal mendapatkan lokasi. Silakan coba lagi.');
                    }
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            alert('Geolocation tidak didukung di perangkat ini');
        }
    }
    getLocation();

    function initializeMap(latitude, longitude) {
        const sekolahLat = parseFloat(document.getElementById('latitude_tikor').value) || 0;
        const sekolahLng = parseFloat(document.getElementById('longitude_tikor').value) || 0;
        const sekolahRadius = parseFloat(document.getElementById('radius_tikor').value) || 0;

        const map = L.map('map').setView([latitude, longitude], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        if (sekolahLat && sekolahLng) {
            L.marker([sekolahLat, sekolahLng]).addTo(map).bindPopup('<b>Lokasi Sekolah</b>');
            if (sekolahRadius > 0) {
                L.circle([sekolahLat, sekolahLng], { radius: sekolahRadius }).addTo(map);
            }
        }

        L.marker([latitude, longitude]).addTo(map).bindPopup('<b>Lokasi Anda</b>').openPopup();
    }
   
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureButton = document.getElementById('capture');
    const fotoSelfieInput = document.getElementById('foto_selfie');
    const photoPreview = document.getElementById('photoPreview');

    navigator.mediaDevices.getUserMedia({ video: true })
        .then((stream) => {
            video.srcObject = stream;
            video.play();
        })
        .catch((error) => console.error('Gagal mengakses kamera:', error));

    captureButton.addEventListener('click', function() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        context.setTransform(1, 0, 0, 1, 0, 0);
        const fotoBase64 = canvas.toDataURL('image/png');
        fotoSelfieInput.value = fotoBase64;
        video.classList.add('hidden');
        photoPreview.src = fotoBase64;
        photoPreview.classList.remove('hidden');
    });
});
</script>
@endsection