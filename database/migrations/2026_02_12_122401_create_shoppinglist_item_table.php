<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shoppinglist_item', function (Blueprint $table) {
            $table->id();

            // ===== Columns =====
            $table->unsignedBigInteger('shoppinglistId'); // FK -> shoppinglists.id
            $table->unsignedBigInteger('ingredientId');   // FK -> ingredients.id

            $table->decimal('quantity', 8, 2)->nullable(); // bv. 2.00
            $table->string('unit')->nullable();            // bv. "pcs", "g", "ml"
            $table->boolean('isChecked')->default(false);

            $table->timestamps();

            // ===== Foreign key constraints =====
            $table->foreign('shoppinglistId')->references('id')->on('shoppinglists')->onDelete('cascade');
            $table->foreign('ingredientId')->references('id')->on('ingredients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shoppinglist_item');
    }
};
