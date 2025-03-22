{{-- menampilkan data reward --}}
@extends('layouts.guru')
@section('content')
<div class="flex-auto max-w-6xl p-6 mx-auto mb-4 bg-white rounded-lg shadow-md">
    <div class="p-4 rounded-lg shadow-md">
        <h3 class="mb-4 text-lg font-semibold text-gray-800">Input Periode Tutup Buku</h3>
        <div class="flex mb-4 space-x-4">
            <div class="w-1/2">
                <label for="from_date" class="block mb-2 text-sm font-medium text-gray-700">From Date</label>
                <input type="date" id="from_date" name="from_date"
                    class="block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="w-1/2">
                <label for="until_date" class="block mb-2 text-sm font-medium text-gray-700">Until Date</label>
                <input type="date" id="until_date" name="until_date"
                    class="block w-full p-2 border border-gray-300 rounded-md">
            </div>
        </div>

        <div class="mb-4">
            <button onclick="filterByDate()"
                class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Filter</button>
        </div>
    </div>
</div>

<script>
    function filterByDate() {
        const fromDate = document.getElementById('from_date').value;
        const untilDate = document.getElementById('until_date').value;

        if (fromDate && untilDate) {
            window.location.href = `?from_date=${fromDate}&until_date=${untilDate}`;
        } else {
            alert('Please select both dates');
        }
    }
</script>
<div class="max-w-6xl p-6 mx-auto bg-white rounded-lg shadow-md">
    <h2 class="mb-6 text-2xl font-bold text-gray-800">Data Reward</h2>
    @if (session('success'))
    <div class="mb-4 text-green-600">
        {{ session('success') }}
    </div>
    @endif
    <div class="overflow-x-auto">
        <div class="flex mb-4 space-x-4">
            <button onclick="downloadExcel()"
                class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-600">Download Excel</button>
            <!-- <button onclick="downloadPDF()" class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">Download
                PDF</button> -->
            <button class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">
                <a href="{{ route('reportTunjanganPdf') }}">view PDF</a>
            </button>
        </div>
        <script>
            async function downloadPDF() {
                const {
                    jsPDF
                } = window.jspdf;
                const table = document.querySelector('table');
                const rows = Array.from(table.rows);
                let pdfContent = '';

                rows.forEach(row => {
                    const cols = Array.from(row.cells).map(cell => cell.innerText);
                    pdfContent += cols.join(" ") + "\n";
                });

                const doc = new jsPDF();
                doc.text(pdfContent, 10, 10);
                doc.save('reward_data.pdf');
            }
        </script>

        <script>
            function downloadExcel() {
                const table = document.querySelector('table');
                const rows = Array.from(table.rows);
                let csvContent = "data:text/csv;charset=utf-8,";

                rows.forEach(row => {
                    const cols = Array.from(row.cells).map(cell => cell.innerText);
                    csvContent += cols.join(",") + "\r\n";
                });

                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "reward_data.csv");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        </script>
        <div class="mb-4">
            <input type="text" id="searchInput" onkeyup="searchData()"
                placeholder="Search by Nama, ID Guru, or Tanggal Absen"
                class="block w-full p-2 border border-gray-300 rounded-md">
        </div>

        <script>
            function searchData() {
                const input = document.getElementById('searchInput').value.toLowerCase();
                const table = document.querySelector('table tbody');
                const rows = table.getElementsByTagName('tr');

                for (let i = 0; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName('td');
                    let match = false;

                    for (let j = 0; j < cells.length; j++) {
                        if (cells[j].innerText.toLowerCase().includes(input)) {
                            match = true;
                            break;
                        }
                    }

                    if (match) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        </script>
          @if(isset($noDataMessage))
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg text-center">
            <p>{{ $noDataMessage }}</p>
        </div>
   		 @else
        <table class="w-full border border-gray-200 md:table">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Tgl Absen</th>
                    <th class="px-4 py-2 border">Masuk</th>
                    <th class="px-4 py-2 border">Pulang</th>
                    <th class="px-4 py-2 border">Persentase</th>
                    <th class="px-4 py-2 border">Reward Transport</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach ($rewardData as $data)
                <tr class="border-t">
                    <td class="px-4 py-2 border">{{$data['reward']->id_user }}</td>
                    <td class="px-4 py-2 border">
                        {{ $data['reward']->nama_guru }}  <br>
                        <span class="text-sm font-bold text-red-600 textcenter">

                            Masuk {{$data['reward']->standar_masuk ?? 'Belum tersedia' }} -
                            Pulang {{$data['reward']->standar_pulang ?? 'Belum tersedia' }}
                        </span>

                    </td>
                    <td class="px-4 py-2 border">{{ $data['reward']->tgl_absen }}</td>
                    <td class="px-4 py-2 border">
                        <div class="flex justify-center">
                            <span class="text-sm font-bold text-center">
                                aktual absen :{{ $data['reward']->jam_masuk }} <br>
                                selisih keterlambatan : <br>
                                {{ ceil($data['diffMasuk'] ?? 0) }} Menit
                            </span>

                        </div>
                    </td>
                    <td class="px-4 py-2 border">
                        <div class="flex justify-center">
                            <span class="text-sm font-bold text-center">
                                aktual absen :{{ $data['reward']->jam_pulang }} <br>
                                selisih keterlambatan : <br>
                                {{ ceil($data['diffPulang'] ?? 0) }} Menit
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-2 border">{{ $data['percentage'] }}%</td>
                    <td class="px-4 py-2 border">Rp. {{ number_format($data['transportReward'], 0, ',', '.') }}
                    </td>

                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-100">
                <tr>
                    <td colspan="6" class="px-4 py-2 font-bold text-right border">Total Reward Transport:</td>
                    <td class="px-4 py-2 font-bold border">
                        Rp. {{ number_format(array_sum(array_column($rewardData, 'transportReward')), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>

        </table>
      @endif
        <!-- Tampilan Mobile -->
        <div class="grid gap-4 md:hidden">
            @foreach ($rewardData as $data)
            <div class="p-4 border rounded-lg shadow-md">
                <div class="flex items-center mb-2">
                    <div class="w-12 h-12 mr-4 bg-gray-300 rounded-full"></div>
                    <div>
                        <h3 class="text-lg font-semibold">{{ $data['reward']->nama_guru }}</h3>
                        <p class="text-gray-600">{{ $data['reward']->nama_guru }}</p>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="#"
                        class="text-blue-600 hover:text-blue-800">Edit</a>
                    |
                    <form action="" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="mt-6">
        <!-- <a href="" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Generate to payroll</a> -->
    </div>
</div>
@endsection