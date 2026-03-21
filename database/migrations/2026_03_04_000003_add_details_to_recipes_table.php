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
        Schema::table('recipes', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('portion_size')->nullable(); // e.g., "40g" or "150ml" - Storing quantity here
            $table->foreignId('portion_size_unit_id')->nullable()->constrained('measurement_units')->onDelete('set null'); // Add unit id
            $table->decimal('selling_price', 8, 2)->nullable();
            $table->text('instructions')->nullable();
            $table->string('photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['portion_size_unit_id']);
            $table->dropColumn([
                'category_id',
                'portion_size',
                'selling_price',
                'instructions',
                'portion_size_unit_id',
                'photo'
            ]);
        });
    }
};
