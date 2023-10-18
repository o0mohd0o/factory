<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeTransfersDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_transfers_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items');
            $table->double('actual_shares')->unsigned()->nullable();
            $table->string('unit')->default('gram');
            $table->unsignedBigInteger('quantity')->default(1);
            $table->unsignedBigInteger('weight');
            $table->string('salary')->default(0);
            $table->string('total_cost')->default(0);
            $table->foreignId('office_transfer_id')->constrained('office_transfers')->onDelete('cascade');
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
        Schema::dropIfExists('office_transfer_details');
    }
}
