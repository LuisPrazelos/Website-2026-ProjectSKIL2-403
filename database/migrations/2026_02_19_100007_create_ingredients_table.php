<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id('ingredientId');
            $table->string('ingredientName');
            $table->unsignedBigInteger('standardUnitId');
            $table->decimal('minimumAmount', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('standardUnitId')
                ->references('measurementUnitId')
                ->on('measurement_units')
                ->onDelete('cascade');

            $table->index('standardUnitId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};

