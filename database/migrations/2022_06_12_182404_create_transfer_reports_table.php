<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class CreateTransferReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_reports', function (Blueprint $table) {
            $table->id();
            $table->string('weight');
            $table->string('transfer_to_previous_balance');
            $table->string('transfer_to_current_balance');
            $table->string('transfer_from_previous_balance');
            $table->string('transfer_from_current_balance');
            $table->date('date');
            $table->string('kind');
            $table->string('kind_name'); 
            $table->string('karat')->nullable(); 
            $table->string('shares')->nullable();
            $table->string('shares_to_transfer')->nullable();
            $table->unsignedBigInteger('doc_num');
            // $table->tinyInteger('doc_type');//[transfering to ;transfering from; opening balance]
            // $table->string('statement');
            $table->foreignId('transfer_from')->constrained('departments');
            $table->foreignId('transfer_to')->constrained('departments');
            $table->string('transfer_from_name'); //transfering from department name
            $table->string('transfer_to_name');  //transfering to department name
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
        Schema::dropIfExists('transfer_reports');
    }
}
