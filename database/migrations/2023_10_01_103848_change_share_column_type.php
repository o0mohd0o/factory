<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeShareColumnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('department_items', function (Blueprint $table) {
            DB::statement('ALTER TABLE `department_items` CHANGE `shares` `shares` DOUBLE NULL DEFAULT NULL;');
            DB::statement('ALTER TABLE `department_items` CHANGE `karat` `karat` DOUBLE NULL DEFAULT NULL;');
            DB::statement('ALTER TABLE `department_items` CHANGE `current_weight` `current_weight` DOUBLE NULL DEFAULT NULL;');
            DB::statement('ALTER TABLE `department_items` CHANGE `previous_weight` `previous_weight` DOUBLE NULL DEFAULT NULL;');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('department_items', function (Blueprint $table) {
            //
        });
    }
}
