<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('club_name');
            $table->string('owner_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
        
            // Status columns
            $table->boolean('account_status')->default(true); // true = active
            $table->boolean('payment_status')->default(false); // paid or unpaid
        
            // Payment tracking
            $table->date('last_paid')->nullable();
            $table->date('expiry_date')->nullable();
        
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
