<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHesabatTransferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hesabat_transfer_details', function (Blueprint $table) {
            $table->id();
            $table->string('kind');
            $table->string('kind_name');
            $table->string('karat');
            $table->string('unit');
            $table->string('quantity');
            $table->foreignId('hesabat_transfer_id')->constrained('hesabat_transfers')->onDelete('cascade');
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
        Schema::dropIfExists('hesabat_transfer_details');
    }
}
