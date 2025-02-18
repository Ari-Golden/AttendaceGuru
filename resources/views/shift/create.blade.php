@extends('layouts.guru')

@section('content')
<div class="max-w-2xl p-6 mx-auto bg-white rounded-lg shadow-md">
    <h2 class="mb-6 text-2xl font-bold text-gray-800">Create New Shift</h2>
    <form action="{{ route('shift-code.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="jam_masuk" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input type="time" id="jam_masuk" name="jam_masuk" required
                class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-blue-300">
        </div>
        <div class="mb-4">
            <label for="jam_pulang" class="block text-sm font-medium text-gray-700">End Time</label>
            <input type="time" id="jam_pulang" name="jam_pulang" required
                class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-blue-300">
        </div>
        <div class="mb-4">
            <label for="note" class="block text-sm font-medium text-gray-700">Note</label>
            <textarea id="note" name="note" required
                class="block w-full p-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:ring-blue-300"></textarea>
        </div>
        <button type="submit" class="w-full px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-opacity-50">
            Submit
        </button>
    </form>
</div>
@endsection