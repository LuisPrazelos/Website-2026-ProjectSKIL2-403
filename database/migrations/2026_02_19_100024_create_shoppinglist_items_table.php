<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shoppinglist_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shoppinglistId');
            $table->unsignedBigInteger('ingredientId');
            $table->integer('quantity')->default(1);
            $table->string('unit')->nullable();
            $table->boolean('isChecked')->default(false);
            $table->timestamps();

            $table->foreign('shoppinglistId')
                ->references('id')
                ->on('shopping_lists')
                ->onDelete('cascade');

            $table->foreign('ingredientId')
                ->references('id')
                ->on('ingredients')
                ->onDelete('cascade');

            $table->index(['shoppinglistId', 'ingredientId']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shoppinglist_items');
    }
};
