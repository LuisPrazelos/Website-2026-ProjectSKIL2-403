<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshop_allergies', function (Blueprint $table) {
            $table->id('workshopAllergyId');
            $table->unsignedBigInteger('allergyId')->nullable(false);
            $table->unsignedBigInteger('workshopId')->nullable(false);
            $table->timestamps();

            $table->foreign('allergyId')
                ->references('allergyId')
                ->on('allergies')
                ->onDelete('cascade');

            $table->foreign('workshopId')
                ->references('workshopId')
                ->on('workshops')
                ->onDelete('cascade');

            $table->index(['allergyId', 'workshopId']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshop_allergies');
    }
};

