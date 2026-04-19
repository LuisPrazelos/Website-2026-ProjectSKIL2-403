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
            $table->string('name')->nullable(false);
            $table->dateTime('date')->nullable(false);
            $table->decimal('price_adults', 10, 2)->nullable(false)->default(0);
            $table->decimal('price_children', 10, 2)->nullable(false)->default(0);
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->integer('duration_minutes')->nullable(false);
            $table->integer('max_participants')->nullable(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};

