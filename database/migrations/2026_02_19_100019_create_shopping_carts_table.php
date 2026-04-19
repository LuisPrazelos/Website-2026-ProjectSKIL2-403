<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopping_carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orderId')->nullable(false);
            $table->unsignedBigInteger('dessertId')->nullable();
            $table->unsignedBigInteger('leftoverId')->nullable();
            $table->integer('amount')->nullable(false)->default(1);
            $table->timestamps();

            $table->foreign('orderId')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('dessertId')
                ->references('id')
                ->on('desserts')
                ->onDelete('set null');

            $table->foreign('leftoverId')
                ->references('id')
                ->on('surpluses')
                ->onDelete('set null');

            $table->index('orderId');
            $table->index('dessertId');
            $table->index('leftoverId');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopping_carts');
    }
};

