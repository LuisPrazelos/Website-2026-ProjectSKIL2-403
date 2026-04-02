<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_evolutions', function (Blueprint $table) {
            $table->id('priceEvolutionId');
            $table->unsignedBigInteger('ingredientId')->nullable(false);
            $table->decimal('price', 10, 4)->nullable(false)->default(0);
            $table->decimal('amount', 10, 4)->nullable(false)->default(0);
            $table->date('date')->nullable(false);
            $table->string('source')->nullable(false);
            $table->timestamps();

            $table->foreign('ingredientId')
                ->references('id')
                ->on('ingredients')
                ->onDelete('cascade');

            $table->index('ingredientId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_evolutions');
    }
};
