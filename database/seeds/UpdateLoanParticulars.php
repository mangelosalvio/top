<?php

use App\CheckVoucher;
use App\Collection;
use App\Loan;
use Illuminate\Database\Seeder;

class UpdateLoanParticulars extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Loans = Loan::all();
        foreach ($Loans as $Loan) {
            $GL = $Loan->gl();
            if ( $GL ) {
                $GL->particulars = strtoupper("CV{$Loan->cv_no} {$Loan->customer->name} DISBURSEMENT");
                $GL->save();
            }
        }

        $CheckVouchers = CheckVoucher::all();
        foreach ($CheckVouchers as $CheckVoucher) {
            $GL = $CheckVoucher->gl();
            if ( $GL ) {
                $GL->particulars = strtoupper("CV{$CheckVoucher->cv_no} {$CheckVoucher->customer_name} DISBURSEMENT");
                $GL->save();
            }
        }

        $Collections = Collection::all();
        foreach ($Collections as $collection) {
            $GL = $collection->gl();
            if ( $GL ) {
                if ( $collection->loan) {
                    $GL->particulars = strtoupper("OR{$collection->or_no} {$collection->loan->customer->name} collection");
                } else {
                    $GL->particulars = strtoupper("OR{$collection->or_no} {$collection->received_from} collection");
                }
                $GL->save();
            }
        }
    }
}
