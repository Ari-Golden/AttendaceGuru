<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('location_attendances', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude', 10, 8); // Format: -90.00000000 hingga 90.00000000
            $table->decimal('longitude', 11, 8); // Format: -180.00000000 hingga 180.00000000
            $table->integer('radius'); // Dalam meter
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_attendances');
    }
};
