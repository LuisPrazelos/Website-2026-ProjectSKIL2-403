<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('happening_desserts')) {
            Schema::create('happening_desserts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('happening_id');
                $table->unsignedBigInteger('dessert_id');
                $table->integer('quantity')->default(1);
                $table->string('allergies')->nullable();
                $table->timestamps();

                $table->foreign('happening_id')->references('id')->on('happenings')->onDelete('cascade');
                $table->foreign('dessert_id')->references('id')->on('desserts')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('happening_desserts');
    }
};
