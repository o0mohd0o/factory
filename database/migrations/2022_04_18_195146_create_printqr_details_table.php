<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintqrDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printqr_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('print_qrcode_id')->constrained('print_qrcodes')->onDelete('cascade');
            $table->integer('item_id');
            $table->integer('serial');
            $table->decimal('quantity',8,2)->nullable();
            $table->decimal('fare',8,2)->nullable();
            $table->decimal('sales_price',8,2)->nullable();
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
        Schema::dropIfExists('prinqr_details');
    }
}
