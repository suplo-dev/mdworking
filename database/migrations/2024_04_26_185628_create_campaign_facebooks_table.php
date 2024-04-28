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
        Schema::create('campaign_facebooks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->boolean('status');
            $table->tinyInteger('type')->nullable();
            $table->integer('result')->nullable();
            $table->integer('reach')->nullable();
            $table->integer('impression')->nullable();
            $table->double('amount_spent')->nullable();
            $table->date('started_at');
            $table->date('ended_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'name', 'started_at'], 'unique_campaign_facebooks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_facebooks');
    }
};
