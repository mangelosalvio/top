<?php

use App\AccountType;
use Illuminate\Database\Seeder;

class AccountTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountType::create([
            'account_type_desc' => 'Asset',
            'account_type_code' => 'A'
        ]);

        AccountType::create([
            'account_type_desc' => 'Liabilities',
            'account_type_code' => 'L'
        ]);

        AccountType::create([
            'account_type_desc' => 'Income',
            'account_type_code' => 'I'
        ]);

        AccountType::create([
            'account_type_desc' => 'Equity',
            'account_type_code' => 'R'
        ]);

        AccountType::create([
            'account_type_desc' => 'Expense',
            'account_type_code' => 'E'
        ]);
    }
}
