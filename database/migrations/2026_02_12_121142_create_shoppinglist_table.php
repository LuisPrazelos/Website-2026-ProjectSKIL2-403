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
        Schema::create('shoppinglist', function (Blueprint $table) {
            $table->id();

            // ===== Columns =====
            $table->unsignedBigInteger('personId'); // FK -> people.id
            $table->string('name')->nullable();     // bv. "Week 7"
            $table->boolean('isCompleted')->default(false);
            $table->string('internalComment')->nullable();

            $table->timestamps();

            // ===== Foreign key constraints =====
            $table->foreign('personId')->references('id')->on('people');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shoppinglist');
    }
};
