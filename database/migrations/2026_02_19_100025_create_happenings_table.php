<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('happenings', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->text('remarks')->nullable();
            $table->dateTime('event_date');
            $table->integer('person_count')->unsigned();
            $table->decimal('price_per_person', 8, 2)->default(0);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('theme_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('theme_id')
                ->references('id')
                ->on('themes')
                ->onDelete('set null');

            $table->foreign('status_id')
                ->references('id')
                ->on('states')
                ->onDelete('set null');

            $table->index(['theme_id', 'status_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('happenings');
    }
};
