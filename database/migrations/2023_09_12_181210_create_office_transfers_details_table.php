<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeTransferDetailsTable extends Migration
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
            $table->string('kind');
            $table->string('kind_name');
            $table->string('karat')->nullable();
            $table->string('shares')->nullable();
            $table->string('unit')->default('gram');
            $table->string('quantity');
            $table->string('salary')->default(0);
            $table->string('total_cost')->default(0);
            $table->foreignId('office_transfer_id')->constrained('office_transfers')->onDelete('cascade');
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
        Schema::dropIfExists('office_transfer_details');
    }
}
