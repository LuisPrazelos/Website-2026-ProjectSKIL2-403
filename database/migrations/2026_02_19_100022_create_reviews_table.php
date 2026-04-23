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
            $table->smallInteger('score')->nullable(false);
            $table->text('content')->nullable();
            $table->date('date')->nullable(false);
            $table->unsignedBigInteger('userId')->nullable(false);
            $table->unsignedBigInteger('dessertId')->nullable();
            $table->unsignedBigInteger('workshopId')->nullable();
            $table->timestamps();

            $table->foreign('userId')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

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
