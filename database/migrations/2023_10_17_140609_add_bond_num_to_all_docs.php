<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBondNumToAllDocs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('bond_num');
        });
        Schema::table('gold_transforms', function (Blueprint $table) {
            $table->unsignedBigInteger('bond_num');
        });
        Schema::table('print_qrcodes', function (Blueprint $table) {
            $table->unsignedBigInteger('bond_num');
        });
        Schema::table('office_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('bond_num');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn(['bond_num']);
        });
        Schema::table('gold_transforms', function (Blueprint $table) {
            $table->dropColumn(['bond_num']);
        });
        Schema::table('print_qrcodes', function (Blueprint $table) {
            $table->dropColumn(['bond_num']);
        });
        Schema::table('office_transfers', function (Blueprint $table) {
            $table->dropColumn(['bond_num']);
        });
    }
}
