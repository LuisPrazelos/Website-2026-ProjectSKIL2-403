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
            $table->unsignedBigInteger('dessertId');
            $table->unsignedBigInteger('ingredientId');
            $table->decimal('amount', 10, 4)->default(0);

            $table->foreign('dessertId')
                ->references('id')
                ->on('desserts')
                ->onDelete('cascade');

            $table->foreign('ingredientId')
                ->references('ingredientId')
                ->on('ingredients')
                ->onDelete('cascade');

            $table->index(['dessertId', 'ingredientId']);
            $table->index('ingredientId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_desserts');
    }
};

