<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransTypeTableSeeder extends Seeder
{
    /**T
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('trans_types')->insert([
            [
                'label' => 'CHATTEL-NEW'
            ],[
                'label' => 'CHATTEL-RENEWAL'
            ],[
                'label' => 'CHATTEL-WCG'
            ],[
                'label' => 'CHATTEL-RESTRUCTURED'
            ],[
                'label' => 'REM-NEW'
            ],[
                'label' => 'REM-RENEWAL'
            ],[
                'label' => 'REM-WCG'
            ],[
                'label' => 'REM-RESTRUCTURED'
            ]
        ]);
    }
}
