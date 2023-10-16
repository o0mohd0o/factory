<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

class GenerateNewBondNumAction
{
    public function generateNewBondNum($bondTable): int
    {
        $latestBondNum = DB::table($bondTable)->max('bond_num');
        $newBondNum = $latestBondNum ? $latestBondNum + 1 : 1;
        while (DB::table($bondTable)->where('bond_num', $newBondNum)->exists()) {
            $newBondNum += 1;
        }
        return $newBondNum;
    }
}
