<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshop_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workshop_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->date('registration_date')->nullable();
            $table->integer('total_adults')->nullable(false)->default(0);
            $table->integer('total_children')->nullable(false)->default(0);
            $table->text('comment')->nullable();
            $table->boolean('is_present')->nullable(false)->default(false);
            $table->timestamps();

            $table->foreign('workshop_id')
                ->references('workshopId')
                ->on('workshops')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->index(['workshop_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshop_users');
    }
};

