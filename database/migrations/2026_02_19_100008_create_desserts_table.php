<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desserts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->text('preparation_method')->nullable();
            $table->text('notes')->nullable();
            $table->string('image')->nullable();
            $table->decimal('portion_size', 8, 2)->default(0);
            $table->unsignedBigInteger('measurement_unit_id')->nullable();
            $table->timestamps();

            $table->foreign('measurement_unit_id')
                ->references('measurementUnitId')
                ->on('measurement_units')
                ->onDelete('set null');

            $table->index('measurement_unit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desserts');
    }
};

