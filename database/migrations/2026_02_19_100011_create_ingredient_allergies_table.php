<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ingredient_allergies', function (Blueprint $table) {
            $table->unsignedBigInteger('ingredientId');
            $table->unsignedBigInteger('allergyId');

            $table->primary(['ingredientId', 'allergyId']);

            $table->foreign('ingredientId')
                ->references('ingredientId')
                ->on('ingredients')
                ->onDelete('cascade');

            $table->foreign('allergyId')
                ->references('allergyId')
                ->on('allergies')
                ->onDelete('cascade');

            $table->index('allergyId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_allergies');
    }
};

