<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id('workshopId');
            $table->string('name');
            $table->dateTime('date');
            $table->decimal('price_adults', 10, 2)->default(0);
            $table->decimal('price_children', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('max_participants')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};

