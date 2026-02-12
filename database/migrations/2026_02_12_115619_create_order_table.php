<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();

            /* Foreign keys */
            $table->unsignedBigInteger('personId');
            $table->unsignedBigInteger('availability')->nullable();
            $table->unsignedBigInteger('requestProposalId')->nullable();
            $table->unsignedBigInteger('decorationId')->nullable();

            /* Data fields */
            $table->date('orderDate');
            $table->string('deliveryAddress')->nullable();
            $table->boolean('isProposal');
            $table->boolean('isOrdered');
            $table->boolean('isPrepared');
            $table->boolean('isPickedUp');
            $table->boolean('isCancelled');
            $table->boolean('hasToBeDelivered');
            $table->string('internalComment')->nullable();

            $table->timestamps();

            // ===== Foreign key constraints =====
            $table->foreign('personId')->references('id')->on('people');
            $table->foreign('availability')->references('id')->on('availabilities');
            $table->foreign('requestProposalId')->references('id')->on('request_proposals');
            $table->foreign('decorationId')->references('id')->on('decorations');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
