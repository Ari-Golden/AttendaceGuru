@extends('layouts.guru')

@section('content')
    <div class="max-w-6xl p-6 mx-auto bg-white rounded-lg shadow-md">
        <h2 class="mb-4 text-2xl font-bold">Input Jadwal Shift</h2>

        <!-- Success Message -->
        @if (session('success'))
            <div class="p-3 mb-4 text-green-800 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Dropdown Shift Code -->
        <label class="block text-gray-700">Pilih Shift Code:</label>
        <select id="shift_code" name="shift_code" class="w-full p-2 mb-4 border border-gray-300 rounded-md">
            <option value="">-- Pilih Shift --</option>
            @foreach ($shifts as $shift)
                <option value="{{ $shift->id }}">{{ $shift->id }} - {{ $shift->note }}</option>
            @endforeach
        </select>

        <div class="grid grid-cols-2 gap-4">
            <!-- Tabel Guru -->
            <div>
                <h3 class="mb-2 text-lg font-bold">Daftar Guru</h3>
                <table class="min-w-full border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 border">#</th>
                            <th class="p-2 border">ID Guru</th>
                            <th class="p-2 border">Nama Guru</th>
                            <th class="p-2 border">Pilih</th>
                        </tr>
                    </thead>
                    <tbody id="userTable">
                        @foreach ($users as $user)
                            <tr>
                                <td class="p-2 border">{{ $loop->iteration }}</td>
                                <td class="p-2 border">{{ $user->id_guru }}</td>
                                <td class="p-2 border">{{ $user->name }}</td>
                                <td class="p-2 text-center border">
                                    <input type="checkbox" class="userCheckbox" data-id="{{ $user->id_guru }}"
                                        data-name="{{ $user->name }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tabel Guru Terpilih -->
            <div>
                <h3 class="mb-2 text-lg font-bold">Guru Terpilih</h3>
                <form action="{{ route('shift-schedule.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="selected_shift_code" name="shift_code">
                    <table class="min-w-full border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2 border">ID Guru</th>
                                <th class="p-2 border">Nama Guru</th>
                                <th class="p-2 border">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="selectedUsersTable"></tbody>
                    </table>
                    <input type="hidden" id="selectedUsers" name="selected_users">
                    <button type="submit" class="px-4 py-2 mt-4 text-white bg-blue-500 rounded">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.userCheckbox');
            const selectedUsersTable = document.getElementById('selectedUsersTable');
            const selectedUsersInput = document.getElementById('selectedUsers');
            const shiftCodeSelect = document.getElementById('shift_code');
            const selectedShiftCodeInput = document.getElementById('selected_shift_code');

            let selectedUsers = [];

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const userId = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-name');

                    if (this.checked) {
                        // Tambah ke tabel kanan
                        selectedUsers.push(userId);
                        const row = document.createElement('tr');
                        row.setAttribute('data-id', userId);
                        row.innerHTML = `
                        <td class="p-2 border">${userId}</td>
                        <td class="p-2 border">${userName}</td>
                        <td class="p-2 text-center border">
                            <button type="button" class="px-2 py-1 text-white bg-red-500 rounded removeUser" data-id="${userId}">X</button>
                        </td>
                    `;
                        selectedUsersTable.appendChild(row);
                    } else {
                        // Hapus dari tabel kanan
                        selectedUsers = selectedUsers.filter(id => id !== userId);
                        document.querySelector(`tr[data-id="${userId}"]`).remove();
                    }

                    updateHiddenInput();
                });
            });

            selectedUsersTable.addEventListener('click', function(e) {
                if (e.target.classList.contains('removeUser')) {
                    const userId = e.target.getAttribute('data-id');
                    document.querySelector(`.userCheckbox[data-id="${userId}"]`).checked = false;
                    document.querySelector(`tr[data-id="${userId}"]`).remove();
                    selectedUsers = selectedUsers.filter(id => id !== userId);
                    updateHiddenInput();
                }
            });

            shiftCodeSelect.addEventListener('change', function() {
                selectedShiftCodeInput.value = this.value;
            });

            function updateHiddenInput() {
                selectedUsersInput.value = JSON.stringify(selectedUsers);
            }
        });
    </script>
@endsection
