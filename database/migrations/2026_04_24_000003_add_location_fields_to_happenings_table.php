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
        Schema::table('happenings', function (Blueprint $table) {
            if (!Schema::hasColumn('happenings', 'on_location')) {
                $table->boolean('on_location')->default(false)->after('status_id');
            }
            if (!Schema::hasColumn('happenings', 'location')) {
                $table->string('location')->nullable()->after('on_location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('happenings', function (Blueprint $table) {
            $table->dropColumn(['on_location', 'location']);
        });
    }
};
