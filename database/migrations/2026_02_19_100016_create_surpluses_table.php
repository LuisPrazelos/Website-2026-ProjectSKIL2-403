<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surpluses', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable(false);
            $table->integer('total_amount')->nullable(false)->default(0);
            $table->decimal('sale', 10, 2)->nullable(false)->default(0);
            $table->string('status')->nullable(false)->default('available');
            $table->date('expiration_date')->nullable(false);
            $table->unsignedBigInteger('dessert_id')->nullable(false);
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('dessert_id')
                ->references('id')
                ->on('desserts')
                ->onDelete('cascade');

            $table->index('dessert_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surpluses');
    }
};
