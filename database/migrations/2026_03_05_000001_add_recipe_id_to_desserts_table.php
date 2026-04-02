<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('desserts', function (Blueprint $table) {
            $table->foreignId('recipe_id')->nullable()->constrained('recipes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('desserts', function (Blueprint $table) {
            $table->dropForeign(['recipe_id']);
            $table->dropColumn('recipe_id');
        });
    }
};
