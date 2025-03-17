@extends('layouts.attendance')
@section('content')
<div class="flex flex-col items-center mt-20  rounded-lg h-screen">   
   <span class="text-2xl font-bold font-verdana text-center text-blue-900 mb-2">Welcome to Dashboard Guru</span>
   
   <span class="text-2xl font-bold font-verdana text-center text-blue-900 mb-2">SMK PGRI Talagasari</span>
   <div class="grid mt-2 grid-cols-4 sm:grid-cols-4 md:grid-cols-4 gap-2 p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg h-screen">
       <!-- Card Menu Absen Harian -->
       <a href="{{ route('attendanceview') }}" class="block p-6  text-blue-500 rounded-lg  hover:text-blue-900 transition duration-300 text-center">
           <div class="flex flex-col items-center ">
               <i class="fas fa-calendar-check text-2xl mb-4"></i>
               <span class="text-sm font-bold">Absen Harian</span>
           </div>
       </a>

       <!-- Card Menu Absen PKL -->
       <a href="{{ route('attendancePkl') }}" class="block p-6  text-blue-500 rounded-lg  hover:text-blue-900 transition duration-300 text-center">
           <div class="flex flex-col items-center">
               <i class="fas fa-user-clock text-2xl mb-4"></i>
               <span class="text-sm font-bold">Absen Monitoring PKL</span>
           </div>
       </a>

       <!-- Card Menu Report Absen -->
       <a href="{{ route('reward-guru') }}" class="block p-6  text-blue-500 rounded-lg  hover:text-blue-900 transition duration-300 text-center">
           <div class="flex flex-col items-center">
               <i class="fas fa-chart-line text-2xl mb-4"></i>
               <span class="text-sm font-bold">Laporan Absen</span>
           </div>
       </a>
   </div>
   <footer class="fixed bottom-0 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800">
       <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2025 <span class="font-semibold">PT. Golden National Lecacy</span> <a href="#" class="hover:underline">AttendanceGuruTM</a>. All Rights Reserved.
   </footer>
</div>
   @endsection
  