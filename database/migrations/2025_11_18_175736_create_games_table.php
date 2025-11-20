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
        Schema::create('games', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('club_id');
            $table->unsignedBigInteger('table_id');

            // Players
            $table->unsignedBigInteger('player1_id');
            $table->unsignedBigInteger('player2_id');
            $table->unsignedBigInteger('player3_id')->nullable();
            $table->unsignedBigInteger('player4_id')->nullable();

            // Results
            $table->string('winners')->nullable();   // comma-separated IDs
            $table->string('losers')->nullable();    // comma-separated IDs

            // Billing
            $table->enum('billing_type', ['per_minute', 'full_game']);
            $table->decimal('price_per_minute', 10, 2)->nullable();
            $table->decimal('full_game_price', 10, 2)->nullable();

            // Start time + status
            $table->timestamp('start_time')->nullable();
            $table->enum('status', ['ongoing', 'completed'])->default('ongoing');

            $table->timestamps();

            // Relations
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
