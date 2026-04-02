<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredient_desserts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dessert_id')->nullable(false)->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->nullable(false)->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 4)->nullable(false)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_desserts');
    }
};
