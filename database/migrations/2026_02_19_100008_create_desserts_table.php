<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('desserts')) {
            Schema::create('desserts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->decimal('price', 10, 2)->default(0);
                $table->text('description')->nullable();
                $table->text('preparation_method')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('picture_id')->nullable();
                $table->decimal('portion_size', 8, 2)->default(0);
                $table->foreignId('measurement_unit_id')->nullable()->constrained()->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('desserts');
    }
};
