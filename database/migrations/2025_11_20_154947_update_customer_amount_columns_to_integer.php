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
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('billed_amount')->change();
            $table->integer('paid_amount')->change();
            $table->integer('pending_amount')->change();
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->decimal('billed_amount', 8, 2)->change();
            $table->decimal('paid_amount', 8, 2)->change();
            $table->decimal('pending_amount', 8, 2)->change();
        });
    }

};
