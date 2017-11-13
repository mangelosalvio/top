<?php

use App\Journal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Journal::create([
           'journal_desc' => 'General Journal',
           'journal_code' => 'JV'
        ]);

        Journal::create([
            'journal_desc' => 'Cash Receipts Journal',
            'journal_code' => 'CR'
        ]);

        Journal::create([
            'journal_desc' => 'Disbursements Journal',
            'journal_code' => 'DV'
        ]);
    }
}
