<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('decorations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->decimal('price', 10, 2)->nullable(false)->default(0);
            $table->text('content')->nullable();
            $table->unsignedBigInteger('themeId')->nullable();
            $table->timestamps();

            $table->foreign('themeId')
                ->references('id')
                ->on('themes')
                ->onDelete('set null');

            $table->index('themeId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('decorations');
    }
};

