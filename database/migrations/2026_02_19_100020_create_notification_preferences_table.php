<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('notification_category_id');
            $table->unsignedBigInteger('notification_channel_id');
            $table->boolean('received')->default(true);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('notification_category_id')
                ->references('id')
                ->on('notification_categories')
                ->onDelete('cascade');

            $table->foreign('notification_channel_id')
                ->references('id')
                ->on('notification_channels')
                ->onDelete('cascade');

            $table->unique(['user_id', 'notification_category_id', 'notification_channel_id'], 'notif_pref_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};

