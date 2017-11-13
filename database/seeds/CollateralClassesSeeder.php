<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollateralClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('collateral_classes')->insert([[
            'class_code' => '10201',
            'class_desc' => 'LR-AUTO'
        ],[
            'class_code' => '10202',
            'class_desc' => 'LR-JEEP'
        ],[
            'class_code' => '10203',
            'class_desc' => 'LR-THE'
        ],[
            'class_code' => '10205',
            'class_desc' => 'LR-MISC'
        ],[
            'class_code' => '10206',
            'class_desc' => 'LR-LR-APPL'
        ],[
            'class_code' => '10208',
            'class_desc' => 'ITEMS IN LITIGATION'
        ],[
            'class_code' => '10209',
            'class_desc' => 'PAST DUE ACCOUNTS'
        ]]);
    }
}
