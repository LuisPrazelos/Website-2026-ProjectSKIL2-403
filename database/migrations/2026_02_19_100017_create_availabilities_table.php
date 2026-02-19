<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->dateTime('exceptionAvailabilityDate')->nullable();
            $table->dateTime('pickUpTimeStart')->nullable();
            $table->dateTime('pickUpTimeStop')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};

