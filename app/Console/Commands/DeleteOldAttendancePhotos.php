<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DeleteOldAttendancePhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:delete-old-photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes attendance photos older than 3 months and sets foto_selfie to NULL.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to delete old attendance photos...');

        $threeMonthsAgo = Carbon::now()->subMonths(3);

        $oldAbsensis = Absensi::where('tgl_absen', '<=', $threeMonthsAgo->toDateString())
                                ->whereNotNull('foto_selfie')
                                ->get();

        $deletedCount = 0;

        foreach ($oldAbsensis as $absensi) {
            if ($absensi->foto_selfie) {
                // Delete the file from storage
                if (Storage::disk('public')->exists($absensi->foto_selfie)) {
                    Storage::disk('public')->delete($absensi->foto_selfie);
                    $this->info('Deleted file: ' . $absensi->foto_selfie);
                    $deletedCount++;
                } else {
                    $this->warn('File not found, but path exists in DB: ' . $absensi->foto_selfie);
                }

                // Set foto_selfie to NULL in the database
                $absensi->foto_selfie = null;
                $absensi->save();
            }
        }

        $this->info('Finished deleting old attendance photos. Total files deleted: ' . $deletedCount);
    }
}