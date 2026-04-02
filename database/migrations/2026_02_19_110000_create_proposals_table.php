<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->text('message')->nullable(false); // Boodschap
            $table->integer('person_count')->nullable(false); // Aantal personen
            $table->dateTime('delivery_date')->nullable(false); // Afhaaldatum/leveldatum
            $table->boolean('on_location')->nullable(false)->default(false); // Boolean op locatie

            // Added fields for response
            $table->decimal('price_per_person', 8, 2)->nullable();
            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->unsignedBigInteger('theme_id')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('theme_id')
                ->references('id')
                ->on('themes')
                ->onDelete('set null');
        });

        // Create a pivot table for proposals and desserts
        Schema::create('proposal_desserts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id')->nullable(false);
            $table->unsignedBigInteger('dessert_id')->nullable(false);
            $table->integer('quantity')->nullable(false);
            $table->string('allergies')->nullable();
            $table->timestamps();

            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');
            $table->foreign('dessert_id')->references('id')->on('desserts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_desserts');
        Schema::dropIfExists('proposals');
    }
};
