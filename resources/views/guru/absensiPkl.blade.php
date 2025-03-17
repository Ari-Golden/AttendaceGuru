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

    <h2 class="mb-4 text-xl font-bold">Absensi Monitoring PKL</h2>

    <form method="POST" action="{{ route('attendancePkl.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Detail Titik Koordinat Sekolah -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Pilih lokasi PKL</label>
            <select id="tikor_pkl" name="tikor_pkl" class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md">
                <option value="" disabled selected>Pilih Titik Koordinat</option>
                @foreach ($tikorPkl as $tikor)
                <option value="{{ $tikor->id }}"
                    data-description="{{ $tikor->description }}"
                    data-latitude="{{ $tikor->latitude }}"
                    data-longitude="{{ $tikor->longitude }}"
                    data-radius="{{ $tikor->radius }}">
                    {{ $tikor->description }}
                </option>
                @endforeach
            </select>

            <label class="block text-sm font-medium text-gray-700 mt-2">Detail Titik Koordinat Sekolah</label>
            <input type="text" id="description" name="description" value="" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md">
            <input type="hidden" id="latitude_tikor" name="latitude_tikor" value="" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md">
            <input type="hidden" id="longitude_tikor" name="longitude_tikor" value="" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md">
            <input type="hidden" id="radius_tikor" name="radius_tikor" value="" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md">
        </div>


        <!-- Nama Guru -->
        <div>
            <label for="nama_guru" class="block text-sm font-medium text-gray-700">Nama Guru</label>
            <input type="text" id="nama_guru" name="nama_guru" value="{{ Auth::user()->name }}" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md">
        </div>

        <!-- ID Guru -->
        <div>
            <label for="id_guru" class="block text-sm font-medium text-gray-700">ID Guru</label>
            <input type="hidden" id="id_guru" name="id_guru" value="{{ Auth::user()->id }}" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md cursor-not-allowed">
            <input type="text" id="id_guru1" name="id_guru1" value="{{ Auth::user()->id_guru }}" readonly class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md cursor-not-allowed">

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
        <!-- Textarea untuk Report Monitoring PKL -->
        <div class="mb-4">
            <label for="report" class="block text-sm font-medium text-gray-700">Report Monitoring PKL</label>
            <textarea id="report" name="report" rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Masukkan laporan monitoring PKL di sini..."></textarea>
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

        let map = L.map('map').setView([-6.200, 106.816], 12); // Default Jakarta

        // Tambahkan layer peta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let userMarker, schoolMarker, schoolCircle;

        // Fungsi menampilkan marker user
        function setUserLocation(latitude, longitude) {
            if (userMarker) {
                map.removeLayer(userMarker);
            }
            userMarker = L.marker([latitude, longitude], {
                icon: L.icon({
                    iconUrl: 'https://cdn-icons-png.flaticon.com/32/684/684908.png',
                    iconSize: [30, 30]
                })
            }).addTo(map).bindPopup('<b>Lokasi Anda</b>').openPopup();
        }

        // Dapatkan lokasi pengguna
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    let latitude = position.coords.latitude;
                    let longitude = position.coords.longitude;

                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;
                    document.getElementById('lokasi_absen').value = `Latitude: ${latitude}, Longitude: ${longitude}`;

                    setUserLocation(latitude, longitude);
                    map.setView([latitude, longitude], 15);
                },
                function(error) {
                    console.error('Error mendapatkan lokasi:', error);
                }
            );
        }

        // Event listener ketika lokasi PKL dipilih
        document.getElementById('tikor_pkl').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];

            let description = selectedOption.getAttribute('data-description') || '';
            let latitude = parseFloat(selectedOption.getAttribute('data-latitude')) || 0;
            let longitude = parseFloat(selectedOption.getAttribute('data-longitude')) || 0;
            let radius = parseFloat(selectedOption.getAttribute('data-radius')) || 0;

            document.getElementById('description').value = description;
            document.getElementById('latitude_tikor').value = latitude;
            document.getElementById('longitude_tikor').value = longitude;
            document.getElementById('radius_tikor').value = radius;

            if (!latitude || !longitude) return;

            // Hapus marker sebelumnya jika ada
            if (schoolMarker) {
                map.removeLayer(schoolMarker);
            }
            if (schoolCircle) {
                map.removeLayer(schoolCircle);
            }

            // Tambahkan marker untuk lokasi sekolah
            schoolMarker = L.marker([latitude, longitude], {
                icon: L.icon({
                    iconUrl: 'https://cdn-icons-png.flaticon.com/32/684/684809.png',
                    iconSize: [30, 30]
                })
            }).addTo(map).bindPopup(`<b>${description}</b><br>Radius: ${radius} meter`);

            // Tambahkan lingkaran radius sekolah
            schoolCircle = L.circle([latitude, longitude], {
                color: 'blue',
                fillColor: '#blue',
                fillOpacity: 0.3,
                radius: radius
            }).addTo(map);

            // Pindahkan tampilan peta ke titik koordinat PKL
            map.setView([latitude, longitude], 15);
        });


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
            canvas.width = video.videoWidth / 2; // Kurangi resolusi
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
            const fotoBase64 = canvas.toDataURL('image/png', 0.5);
            fotoSelfieInput.value = fotoBase64;

            // Sembunyikan video, tampilkan hasil foto
            video.classList.add('hidden');
            photoPreview.src = fotoBase64;
            photoPreview.classList.remove('hidden');
        });

    });
</script>

@endsection