<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemDailyJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_daily_journals', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('item_id');
            $table->double('debit')->default(0);
            $table->double('credit')->default(0);
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('related_department_id')->nullable();
            $table->unsignedBigInteger('worker_id')->nullable();
            $table->double('actual_shares');
            $table->unsignedBigInteger('doc_id');
            $table->string('doc_type');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('item_daily_journals');
    }
}
