<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpeningBalanceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opening_balance_details', function (Blueprint $table) {
            $table->id();
            $table->string('kind');
            $table->string('kind_name');
            $table->string('karat')->nullable();
            $table->string('shares')->nullable();
            $table->string('unit')->default('gram');
            $table->string('quantity');
            $table->string('salary')->default(0);
            $table->string('total_cost')->default(0);
            $table->foreignId('opening_balance_id')->constrained('opening_balances')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opening_balance_details');
    }
}
