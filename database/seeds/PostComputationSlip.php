<?php

use App\Http\Controllers\ComputationSlipsController;
use App\Loan;
use Illuminate\Database\Seeder;

class PostComputationSlip extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Loan::whereBetween('check_date',['2017-10-01','2017-11-31'])
            ->get()->each(function($Loan){
                ComputationSlipsController::post($Loan);
            });
    }
}
