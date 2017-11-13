<?php

use App\ChartOfAccount;
use Illuminate\Database\Seeder;

class MainAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChartOfAccount::create([
            'account_code' => '102',
            'account_desc' => 'L/R',
            'account_type_id' => '2'
        ]);

        ChartOfAccount::create([
            'account_code' => '109',
            'account_desc' => 'A/R',
            'account_type_id' => '1'
        ]);

        ChartOfAccount::create([
            'account_code' => '207',
            'account_desc' => 'RFF',
            'account_type_id' => '2'
        ]);

        ChartOfAccount::create([
            'account_code' => '206',
            'account_desc' => 'UII',
            'account_type_id' => '2'
        ]);

        ChartOfAccount::create([
            'account_code' => '40101',
            'account_desc' => 'Interest and Finances',
            'account_type_id' => '3'
        ]);

        ChartOfAccount::create([
            'account_code' => '40102',
            'account_desc' => 'Service Fees',
            'account_type_id' => '3'
        ]);

        ChartOfAccount::create([
            'account_code' => '40103',
            'account_desc' => 'Penalties/Past Dues',
            'account_type_id' => '3'
        ]);
    }
}
