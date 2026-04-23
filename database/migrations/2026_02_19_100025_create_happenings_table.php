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
            $table->text('message')->nullable(false);
            $table->text('remarks')->nullable();
            $table->dateTime('event_date')->nullable(false);
            $table->integer('person_count')->unsigned()->nullable(false);
            $table->decimal('price_per_person', 8, 2)->nullable(false)->default(0);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->unsignedBigInteger('theme_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->boolean('on_location')->default(false);
            $table->string('location')->nullable();
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

            $table->index('user_id');
        });

        // Create happening_desserts pivot table
        Schema::create('happening_desserts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('happening_id');
            $table->unsignedBigInteger('dessert_id');
            $table->integer('quantity');
            $table->string('allergies')->nullable();
            $table->timestamps();

            $table->foreign('happening_id')->references('id')->on('happenings')->onDelete('cascade');
            $table->foreign('dessert_id')->references('id')->on('desserts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('happening_desserts');
        Schema::dropIfExists('happenings');
    }
};
