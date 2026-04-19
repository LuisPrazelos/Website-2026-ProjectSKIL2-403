<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopping_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personId')->nullable();
            $table->string('name')->nullable(false);
            $table->boolean('isCompleted')->nullable(false)->default(false);
            $table->text('internalComment')->nullable();
            $table->timestamps();

            $table->foreign('personId')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->index('personId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopping_lists');
    }
};

