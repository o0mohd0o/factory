<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class CreateOpeningBalanceReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opening_balance_reports', function (Blueprint $table) {
            $table->id();
            $table->string('weight');
            $table->string('transfer_to_previous_balance');
            $table->string('transfer_to_current_balance');
            $table->date('date');
            $table->string('kind');
            $table->string('kind_name'); 
            $table->string('karat')->nullable(); 
            $table->string('shares')->nullable();
            // $table->string('person_on_charge'); 
            $table->unsignedBigInteger('doc_num');
            $table->foreignId('transfer_to')->constrained('departments');
            $table->string('transfer_to_name');  //transfering to department name
            $table->string('type')->default('create');
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
        Schema::dropIfExists('opening_balance_reports');
    }
}
