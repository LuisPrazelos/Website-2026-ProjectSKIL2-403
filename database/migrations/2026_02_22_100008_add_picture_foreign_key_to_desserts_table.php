<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('desserts', function (Blueprint $table) {
            $table->foreign('picture_id')
                ->references('id')
                ->on('pictures')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('desserts', function (Blueprint $table) {
            $table->dropForeign(['picture_id']);
        });
    }
};

