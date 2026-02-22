<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('reviewId');
            $table->smallInteger('score');
            $table->text('content')->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('userId')->nullable();
            $table->unsignedBigInteger('dessertId')->nullable();
            $table->unsignedBigInteger('workshopId')->nullable();
            $table->timestamps();

            $table->foreign('userId')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('dessertId')
                ->references('id')
                ->on('desserts')
                ->onDelete('set null');

            $table->foreign('workshopId')
                ->references('workshopId')
                ->on('workshops')
                ->onDelete('set null');

            $table->index(['userId', 'dessertId', 'workshopId']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
