<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('desserts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->decimal('price', 10, 2)->nullable(false)->default(0);
            $table->text('description')->nullable();
            $table->text('preparation_method')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('picture_id')
                ->nullable()
                ->constrained('pictures')
                ->nullOnDelete();
            $table->decimal('portion_size', 8, 2)->nullable(false)->default(0);
            $table->foreignId('measurement_unit_id')->nullable(false)->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desserts');
    }
};
