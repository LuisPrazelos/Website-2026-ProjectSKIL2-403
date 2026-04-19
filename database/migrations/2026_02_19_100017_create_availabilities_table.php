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
            $table->date('date')->nullable(false);
            $table->dateTime('exceptionAvailabilityDate')->nullable();
            $table->dateTime('pickUpTimeStart')->nullable(false);
            $table->dateTime('pickUpTimeStop')->nullable(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};

