@extends('layouts.guru')
@section('content')
    <div class="max-w-md p-6 mx-auto bg-white rounded shadow-md">
        <h2 class="mb-4 text-xl font-bold">Absensi Guru</h2>
        <form method="POST" action="{{ route('guru.attendance.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <!-- Nama Guru -->
            <div>
                <label for="nama_guru" class="block text-sm font-medium text-gray-700">Nama Guru</label>
                <select id="nama_guru" name="nama_guru" class="block w-full mt-1 bg-white border-gray-300 rounded-md">
                    <option value="{{ Auth::user()->name }}">{{ Auth::user()->name }}</option>
                </select>
            </div>
            <!-- ID Guru -->
            <div>
                <label for="id_guru" class="block text-sm font-medium text-gray-700">ID Guru</label>
                <input type="text" id="id_guru" name="id_guru" value="{{ Auth::user()->id }}" readonly
                    class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md cursor-not-allowed">
            </div>
            <!-- Canvas untuk menampilkan preview kamera -->
            <div>
                <label for="foto_selfie" class="block text-sm font-medium text-gray-700">Foto Selfie</label>
                <div class="relative mt-1">
                    <!-- Video untuk preview kamera -->
                    <video id="camera-preview" autoplay playsinline
                        class="w-full h-64 object-cover border border-gray-300 rounded-md scale-x-[-1]"></video>
                    <!-- Canvas untuk menangkap foto -->
                    <canvas id="photo-canvas" class="hidden"></canvas>
                    <!-- Gambar untuk menampilkan hasil foto -->
                    <img id="photo-preview" class="hidden object-cover w-full h-64 mt-2 border border-gray-300 rounded-md"
                        alt="Preview Foto">
                </div>
                @error('foto_selfie')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <!-- Tombol Capture Foto -->
            <button type="button" id="capture-btn"
                class="w-full px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                Ambil Foto
            </button>
            <!-- Input Lokasi Absen -->
            <div>
                <label for="lokasi_absen" class="block text-sm font-medium text-gray-700">Lokasi Absen</label>
                <input type="text" id="lokasi_absen" name="lokasi_absen" placeholder="Mengambil lokasi..." readonly
                    class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md cursor-not-allowed">
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
            </div>
            <!-- Peta -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Peta Lokasi</label>
                <div id="map" class="w-full h-64 mt-1 border border-gray-300 rounded-md"></div>
            </div>
            <!-- Hidden input untuk menyimpan foto selfie -->
            <input type="hidden" id="foto_selfie" name="foto_selfie">
            <!-- Tanggal Absen -->
            <div>
                <label for="tgl_absen" class="block text-sm font-medium text-gray-700">Tanggal Absen</label>
                <input type="text" id="tgl_absen" name="tgl_absen" readonly
                    class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md cursor-not-allowed">
            </div>
            <!-- Jam Absen -->
            <div>
                <label for="jam_absen" class="block text-sm font-medium text-gray-700">Jam Absen</label>
                <input type="text" id="jam_absen" name="jam_absen" readonly
                    class="block w-full mt-1 bg-gray-100 border-gray-300 rounded-md cursor-not-allowed">
            </div>
            <!-- Tombol Absen Masuk dan Pulang -->
            <div class="flex space-x-4">
                <button type="submit" name="status" value="masuk"
                    class="w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">Absen Masuk</button>
                <button type="submit" name="status" value="pulang"
                    class="w-full px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">Absen Pulang</button>
            </div>
        </form>
    </div>
    <!-- CSS untuk Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- JavaScript untuk mengakses kamera, live location, tanggal/jam absen, dan peta -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('camera-preview');
            const canvas = document.getElementById('photo-canvas');
            const captureBtn = document.getElementById('capture-btn');
            const photoPreview = document.getElementById('photo-preview');
            const fotoSelfieInput = document.getElementById('foto_selfie');
            const lokasiAbsenInput = document.getElementById('lokasi_absen');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            let stream;

            // Akses kamera
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then((mediaStream) => {
                    stream = mediaStream;
                    video.srcObject = stream;
                })
                .catch((error) => {
                    console.error('Error accessing the camera:', error);
                    alert('Tidak dapat mengakses kamera. Pastikan perangkat mendukung kamera.');
                });

            // Capture foto saat tombol ditekan
            captureBtn.addEventListener('click', () => {
                // Atur ukuran canvas sesuai dengan video
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                // Gambar frame dari video ke canvas
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Konversi gambar ke base64 dan simpan ke input hidden
                const photoDataUrl = canvas.toDataURL('image/jpeg'); // Format base64
                fotoSelfieInput.value = photoDataUrl;

                // Tampilkan gambar di elemen <img> sebagai preview
                photoPreview.src = photoDataUrl;
                photoPreview.classList.remove('hidden'); // Tampilkan elemen preview

                // Hentikan streaming kamera setelah foto diambil
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                // Sembunyikan video preview
                video.classList.add('hidden');

                // Tampilkan pesan sukses
                // alert('Foto berhasil diambil!');
            });

            // Set tanggal dan jam absen otomatis
            const now = new Date();
            const optionsDate = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            };
            const optionsTime = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };

            // Format tanggal untuk database (YYYY-MM-DD)
            const formattedDate = now.toLocaleDateString('en-CA', optionsDate); // ISO 8601 format
            document.getElementById('tgl_absen').value = formattedDate;

            // Format jam untuk tampilan (HH:MM:SS)
            document.getElementById('jam_absen').value = now.toLocaleTimeString('id-ID', optionsTime);

            // Fungsi untuk inisialisasi peta
            function initializeMap(latitude, longitude) {
                console.log('Initializing map with:', latitude, longitude);

                const map = L.map('map').setView([latitude, longitude], 15); // Zoom level 15
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                L.marker([latitude, longitude]).addTo(map)
                    .bindPopup('Lokasi Anda').openPopup();
            }

            // Fungsi untuk mendapatkan lokasi pengguna
            function getLocationAndInitializeMap() {
                // Mengambil lokasi live menggunakan Geolocation API
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const latitude = position.coords.latitude;
                            const longitude = position.coords.longitude;

                            // Simpan latitude dan longitude ke input hidden
                            latitudeInput.value = latitude;
                            longitudeInput.value = longitude;

                            // Tampilkan lokasi di input lokasi absen
                            lokasiAbsenInput.value = `Latitude: ${latitude}, Longitude: ${longitude}`;

                            // Inisialisasi peta dengan lokasi pengguna
                            initializeMap(latitude, longitude);
                        },
                        (error) => {
                            console.error('Error getting location:', error);

                            // Gunakan lokasi default jika Geolocation gagal
                            const defaultLatitude = -6.2088; // Contoh: Latitude Jakarta
                            const defaultLongitude = 106.8456; // Contoh: Longitude Jakarta

                            latitudeInput.value = defaultLatitude;
                            longitudeInput.value = defaultLongitude;

                            // Tampilkan lokasi default di input lokasi absen
                            lokasiAbsenInput.value =
                                `Latitude: ${defaultLatitude}, Longitude: ${defaultLongitude}`;

                            // Inisialisasi peta dengan lokasi default
                            initializeMap(defaultLatitude, defaultLongitude);
                        }
                    );
                } else {
                    console.error('Geolocation tidak didukung oleh browser.');

                    // Gunakan lokasi default jika Geolocation tidak didukung
                    const defaultLatitude = -6.2088; // Contoh: Latitude Jakarta
                    const defaultLongitude = 106.8456; // Contoh: Longitude Jakarta

                    latitudeInput.value = defaultLatitude;
                    longitudeInput.value = defaultLongitude;

                    // Tampilkan lokasi default di input lokasi absen
                    lokasiAbsenInput.value = `Latitude: ${defaultLatitude}, Longitude: ${defaultLongitude}`;

                    // Inisialisasi peta dengan lokasi default
                    initializeMap(defaultLatitude, defaultLongitude);
                }
            }

            // Panggil fungsi untuk mendapatkan lokasi dan inisialisasi peta
            getLocationAndInitializeMap();
        });
    </script>
@endsection
