@extends('layouts.guru')

@section('content')
<div class="max-w-2xl p-6 mx-auto bg-white rounded-lg shadow-md">
    <h2 class="mb-6 text-2xl font-bold text-gray-800">Edit Shift</h2>
    <form action="{{ route('shift-code.update', $shiftCode->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="mb-4">
            <label for="note" class="block text-sm font-medium text-gray-700">Shift Name</label>
            <input type="text" id="note" name="note" value="{{ $shiftCode->note }}" class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-blue-300">
        </div>
        <div class="mb-4">
            <label for="jam_masuk" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input type="time" id="jam_masuk" name="jam_masuk" value="{{ $shiftCode->jam_masuk }}" class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-blue-300">
        </div>
        <div class="mb-4">
            <label for="jam_pulang" class="block text-sm font-medium text-gray-700">End Time</label>
            <input type="time" id="jam_pulang" name="jam_pulang" value="{{ $shiftCode->jam_pulang }}" class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-blue-300">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-opacity-50">Update Shift</button>
        </div>
    </form>
</div>
@endsection