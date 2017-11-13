<?php

namespace App\Http\Controllers;

use App\AmortizationTable;
use App\ChartOfAccount;
use App\Collateral;
use App\Customer;
use App\Library\LoanComputation;
use App\Library\NumToWords;
use App\Loan;
use App\LoanCalculator;
use App\Setting;
use App\TransType;
use fpdi\FPDI;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use Laracasts\Utilities\JavaScript\JavaScriptFacade as JavaScript;

class LoansController extends Controller
{

    public $arr_rules;
    public $route;

    public function __construct(){
        /*$LoanCalc = new LoanCalculator(100000,22,12,4,'2016-10-26');
        dd($LoanCalc);*/

        $this->route = "loans";

        $this->arr_rules  = [
            'collateral_id' => 'required',
            'date' => 'required',
            'trans_type_id' => 'required',
            'customer_id' => 'required',

            'amount' => 'required|numeric',
            'term' => 'required|numeric',
            'interest_rate' => 'required|numeric',
            'rebate_rate' => 'required|numeric',
        ];

        $search_data = [ 'search_url' => $this->route ];

        $trans_types = TransType::all()->pluck('label','id');
        $customers = Customer::all()->pluck('name','id');
        $collaterals = Collateral::all()->pluck('label','id');

        return view()->share(compact(['search_data','customers','collateral_classes','trans_types','collaterals']));
    }

    public function index(Request $request){
        $keyword = $request->input('keyword');

        $loans = Loan::orderBy('date','desc');

        if ( $keyword ) {
            $loans = Loan::whereHas('customer', function($query) use ($keyword){
                $query->where('last_name','like',"%$keyword%")
                    ->orWhere('first_name','like',"%$keyword%")
                    ->orWhere('middle_name','like',"%$keyword%");
            })
                ->orWhere('id','like',"$keyword%");
        }

        if ( $request->has('search_id') ) {
            $loans = $loans->orWhere('id',$request->input('search_id'));
        }

        if ( $request->has('search_customer_name') ) {
            $loans = $loans->orWhereHas('customer', function($query) use ($request){
                $query->where('last_name','like',"%{$request->input('search_customer_name')}%")
                    ->orWhere('first_name','like',"%{$request->input('search_customer_name')}%")
                    ->orWhere('middle_name','like',"%{$request->input('search_customer_name')}%");
            });
        }

        if ( $request->has(['search_date_purchased_from_date','search_date_purchased_to_date']) ) {
            $loans = $loans->orWhereBetween('date_purchased',[ $request->input('search_date_purchased_from_date'),$request->input('search_date_purchased_to_date') ]);
        }

        if ( $request->has(['search_check_date_from_date','search_check_date_to_date']) ) {
            $loans = $loans->orWhere(function($query) use ($request) {
                $query->whereNotNull('check_date')
                    ->whereBetween('check_date',[ $request->input('search_check_date_from_date'),$request->input('search_check_date_to_date') ]);
            });
        }



        $loans = $loans->paginate();

        return view("{$this->route}.search_{$this->route}",compact([
            'loans'
        ]));
    }

    public function create(){

        return view("{$this->route}.{$this->route}",compact([
        ]));
    }

    public function store(Request $request){

        $this->validate($request,$this->arr_rules);

        $form_data = $request->all();
        $form_data['date_purchased'] = empty( $form_data['date_purchased'] ) ? null : $form_data['date_purchased'];

        $Loan = Loan::create($form_data);

        /**
         * Update computed columns
         */


        /**
         * if no date purchased, do not calculate schedule
         */

        $this->calculateLoanDetails($Loan);



        return Redirect::to("/{$this->route}/{$Loan->id}/edit")->with('flash_message','Information Saved');
    }

    public function edit(Loan $loan){

        JavaScript::put([
            'collateral_id' => $loan->collateral_id
        ]);

        return view("{$this->route}.{$this->route}", compact([
            'loan'
        ]));

    }

    public function update(Request $request, Loan $loan){

        $this->validate($request,$this->arr_rules);

        $form_data = $request->all();
        $form_data['date_purchased'] = empty( $form_data['date_purchased'] ) ? null : $form_data['date_purchased'];

        $loan->update($form_data);
        $this->calculateLoanDetails($loan);

        $this->createAccounts($request, $loan);

        return Redirect::to("/{$this->route}/{$loan->id}/edit")->with('flash_message','Information Saved');
    }

    public function destroy(Loan $loan){

        if ($loan->loanLedgers()->count()) {
            $loan->loanLedgers()->delete();
        }

        $GL = $loan->gl();
        if ( $GL ) {
            $GL->delete();
        }
        $loan->delete();
        return Redirect::to("/{$this->route}")->with('flash_message','Loan Deleted');
    }

    public function calculateLoanDetails(Loan $Loan){

        $LoanCalc = new LoanCalculator($Loan->amount,$Loan->interest_rate,
            $Loan->term,$Loan->rebate_rate,$Loan->date_purchased);

        $Loan->interest_amount = $LoanCalc->getInterestAmount();
        $Loan->rebate_amount = $LoanCalc->getRebateAmount();
        $Loan->installment_first = $LoanCalc->getFirstInstallmentAmount();
        $Loan->installment_second = $LoanCalc->getSecondInstallmentAmount();
        $Loan->rebate_first = $LoanCalc->getFirstRebate();
        $Loan->rebate_second = $LoanCalc->getSecondRebate();
        $Loan->net_first = $LoanCalc->getNetFirst();
        $Loan->net_second = $LoanCalc->getNetSecond();
        $Loan->net_amount = $LoanCalc->getNetAmount();
        $Loan->pn_amount = $LoanCalc->getPnAmount();
        $Loan->cash_out = $LoanCalc->getPnAmount();

        $Loan->save();

        if ( $Loan->date_purchased ) {
            $arr_amortization_table = $LoanCalc->getAmortizationTable();
            $Loan->amortizationTables()->delete();
            foreach ( $arr_amortization_table as $amortization_table ) {
                $Loan->amortizationTables()->create($amortization_table);
            }
        }
    }

    public function otherAdditions(Loan $loan){
        return $loan->otherAdditions;
    }

    public function otherDeductions(Loan $loan){
        return $loan->otherDeductions;
    }

    public function printProcessingSlip(Loan $loan){
        return view('reports.print_processing_slip', compact([
            'loan'
        ]));
    }

    public function printCreditInvestigationReport(Loan $loan){
        return view('reports.print_credit_investigation_report', compact([
            'loan'
        ]));
    }

    public function printChattelMortgage(Loan $loan){
        // initiate FPDI
        $pdf = new FPDI('P','mm','Legal');
        // add a page
        $pdf->AddPage();
        // set the source file
        $pdf->setSourceFile('chattel_mortgage.pdf');

        // import page 1
        $tplIdx1 = $pdf->importPage(1);
        // use the imported page and place it at position 10,10 with a width of 100 mm
        $size = $pdf->getTemplateSize($tplIdx1);
        $pdf->SetFont('Arial','','12');


        $pdf->useTemplate($tplIdx1, null, null, 0, 0, false);

        $pdf->SetXY(40, 56);
        $pdf->Write(0,strtoupper($loan->customer->name));

        $pdf->SetXY(120, 56);
        $pdf->Write(0,"FILIPINO");

        $pdf->SetXY(40, 75);
        $pdf->MultiCell(0,4,$loan->customer->current_address);

        $pdf->SetXY(60, 90);
        $pdf->Write(0,"UNLI FINANCE CORP.");

        $pdf->SetXY(120, 90);
        $pdf->Write(0,"CORPORATION");

        $pdf->SetXY(40, 113);
        $pdf->Write(0,"COR. ROSARIO AMAPOLA, BACOLOD CITY");

        $pdf->SetXY(40, 118);
        $pdf->Write(0,"JOVEN J. PIMENTEL");

        $pdf->SetXY(40, 160);

        $content = "MAKE : " . $loan->collateral->make ."\n\n";
        $content .= "TYPE : " . $loan->collateral->type ."\n\n";
        $content .= "MOTOR NUMBER : " . $loan->collateral->motor ."\n\n";
        $content .= "SERIAL NUMBER : " . $loan->collateral->serial ."\n\n";
        $content .= "PLATE NUMBER : " . $loan->collateral->plate ."\n\n";
        $content .= "MV FILE NO. : " . $loan->collateral->mv_file_no ."\n";

        $pdf->MultiCell(0,4, $loan->collateral->collateral_desc . "\n\n" . $content);


        $formatter = new NumToWords(round($loan->pn_amount,2));
        $words = $formatter->getWord();
        $pdf->SetFont('Arial','','9');
        $pdf->SetXY(155, 270);
        $pdf->Write(0,$words);
        $pdf->SetFont('Arial','','12');

        $pdf->SetXY(175, 270);
        $pdf->Write(0,number_format($loan->pn_amount,2));


        $pdf->AddPage();
        $tplIdx2 = $pdf->importPage(2);
        // use the imported page and place it at position 10,10 with a width of 100 mm
        $size = $pdf->getTemplateSize($tplIdx2);
        $pdf->useTemplate($tplIdx2, null, null, 0, 0, false);

        $pdf->AddPage();
        $tplIdx3 = $pdf->importPage(3);
        // use the imported page and place it at position 10,10 with a width of 100 mm
        $size = $pdf->getTemplateSize($tplIdx3);
        $pdf->useTemplate($tplIdx3, null, null, 0, 0, false);

        $pdf->SetXY(55, 124);
        $pdf->Write(0,"CITY OF BACOLOD");

        $pdf->SetXY(130, 153);
        $pdf->Write(0,$loan->customer->name);

        $pdf->SetXY(130, 187);
        $pdf->Write(0,$loan->customer->name);

        $pdf->SetXY(30, 184);
        $pdf->MultiCell(0,4,"UNLI FINANCE CORP\nJOVEN PIMENTEL\nMANAGER");

        $pdf->Output();
    }

    public function printDisclosureStatement(Loan $loan){
        // initiate FPDI
        $pdf = new FPDI('P','mm','Legal');
        // add a page
        $pdf->AddPage();
        // set the source file
        $pdf->setSourceFile('disclosure_statement.pdf');

        // import page 1
        $tplIdx1 = $pdf->importPage(1);
        // use the imported page and place it at position 10,10 with a width of 100 mm
        $size = $pdf->getTemplateSize($tplIdx1);
        $pdf->SetFont('Arial','','10');


        $pdf->useTemplate($tplIdx1, null, null, 0, 0, false);

        $pdf->SetXY(52, 80);
        $pdf->Write(0,strtoupper($loan->customer->name));


        $pdf->SetXY(52, 84);
        $pdf->MultiCell(0,4,$loan->customer->current_address);

        $pdf->SetXY(160, 100);
        $pdf->Write(0,number_format($loan->pn_amount,2));


        $pdf->SetXY(123, 136);
        $pdf->Cell( 30, 3, number_format($loan->total_rod_charges,2), 0, 0, 'R' );

        $pdf->SetXY(123, 141);
        $pdf->Cell( 30, 3, number_format($loan->doc_stamp,2), 0, 0, 'R' );

        $pdf->SetXY(123, 145);
        $pdf->Cell( 30, 3, number_format($loan->notarial_fees,2), 0, 0, 'R' );

        $pdf->SetXY(60, 150);
        $pdf->Write(0,"LTC");

        $pdf->SetXY(123, 150);
        $pdf->Cell( 30, 3, number_format($loan->transfer_fees,2), 0, 0, 'R' );

        $pdf->SetXY(60, 155);
        $pdf->Write(0,"misc-bir");

        $pdf->SetXY(123, 155);
        $pdf->Cell( 30, 3, number_format($loan->misc_fees,2), 0, 0, 'R' );

        $pdf->SetXY(60, 160);
        $pdf->Write(0,"misc-rod");

        $pdf->SetXY(123, 160);
        $pdf->Cell( 30, 3, number_format($loan->misc_lto_fees,2), 0, 0, 'R' );

        $pdf->SetXY(60, 165);
        $pdf->Write(0,"processors fee");

        $pdf->SetXY(123, 165);
        $pdf->Cell( 30, 3, number_format($loan->total_lto_charges,2), 0, 0, 'R' );



        $pdf->SetXY(160, 172);
        $pdf->Write(0,number_format($loan->pn_amount,2));

        $pdf->SetXY(56, 185);
        $pdf->Write(0,number_format($loan->interest_rate,2));

        $pdf->SetXY(125, 190);
        $pdf->Write(0,number_format($loan->interest_amount,2));

        $pdf->SetXY(45, 262);
        $pdf->Write(0,"REBATABLE FINANCING FEE");

        $pdf->SetXY(125, 262);
        $pdf->Write(0,number_format($loan->rebate_amount,2));

        $pdf->SetXY(160, 282);
        $pdf->Write(0,number_format($loan->net_amount,2));


        $pdf->AddPage();
        $tplIdx2 = $pdf->importPage(2);
        // use the imported page and place it at position 10,10 with a width of 100 mm
        $size = $pdf->getTemplateSize($tplIdx2);
        $pdf->useTemplate($tplIdx2, null, null, 0, 0, false);

        $pdf->SetXY(55, 93);
        $pdf->Write(0,number_format($loan->term,2) . ' months');

        $pdf->SetXY(118, 93);
        $pdf->Write(0,number_format($loan->installment_first,2) . ' / ' . number_format($loan->installment_second,2));

        $pdf->SetXY(165, 93);
        $pdf->Write(0,'PN- ' . number_format($loan->pn_amount,2));

        $pdf->SetXY(15, 123);
        $pdf->Write(0,'PAST DUE CHARGES');

        $pdf->SetXY(80, 123);
        $pdf->Write(0,'5% PER MONTH ON THE UNPAID MONTHLY INSTALLMENT');

        $pdf->SetXY(15, 130);
        $pdf->Write(0,'ATTORNEY\'S FEES');

        $pdf->SetXY(80, 130);
        $pdf->Write(0,'25% ON THE OUSTANDING BALANCE');

        $pdf->SetXY(15, 137);
        $pdf->Write(0,'ACCOUNTS PAYABLE/RFF');

        $pdf->SetXY(80, 137);
        $pdf->Write(0, "(". number_format($loan->rebate_first,2) ." / ". number_format($loan->rebate_second,2) ." PER MONTH) FORFEITED IF" );

        $pdf->SetXY(15, 143);
        $pdf->Write(0,'MONTHLY INSTALLMENT IS NOT PAID ON OR BEFORE THE DUE DATE');

        $pdf->SetXY(140, 184);
        $pdf->Write(0,'JOVEN J. PIMENTEL');

        $pdf->SetXY(144, 203);
        $pdf->Write(0,'MANAGER');

        $pdf->SetXY(127, 250);
        $pdf->Write(0,$loan->customer->name);


        $pdf->Output();
    }

    public function printPromissoryNote(Loan $loan){
        // initiate FPDI
        $pdf = new FPDI('P','mm','Legal');
        // add a page
        $pdf->AddPage();
        // set the source file
        $pdf->setSourceFile('promissory_note.pdf');

        // import page 1
        $tplIdx1 = $pdf->importPage(1);
        // use the imported page and place it at position 10,10 with a width of 100 mm
        $size = $pdf->getTemplateSize($tplIdx1);
        $pdf->SetFont('Arial','','12');


        $pdf->useTemplate($tplIdx1, null, null, 0, 0, false);

        $pdf->SetXY(33, 26);
        $pdf->Write(0,number_format($loan->pn_amount,2));

        $pdf->SetXY(80, 50);
        $pdf->Write(0,"UNLIFINANCE CORP.");

        $pdf->SetXY(35, 58);
        $pn_format = new NumToWords($loan->pn_amount);
        $pdf->Write(0,$pn_format->getWord());

        $pdf->SetXY(140, 68);
        $pdf->Write(0,number_format($loan->pn_amount,2));

        $pdf->SetXY(55, 83);
        $installment_first_format = new NumToWords($loan->installment_first);
        $pdf->Write(0,$installment_first_format->getWord());

        $pdf->SetXY(160, 93);
        $pdf->Write(0,number_format($loan->installment_first,2));

        $pdf->SetXY(15, 123);
        $installment_second_format = new NumToWords($loan->installment_second);
        $pdf->Write(0,$installment_second_format->getWord());

        $pdf->SetXY(160, 123);
        $pdf->Write(0,number_format($loan->installment_second,2));

        $pdf->SetXY(60, 107);
        $months_format = new NumToWords($loan->term);
        $pdf->Write(0,$months_format->getPlainWord() ." months");

        $pdf->SetXY(130, 107);
        $pdf->Write(0,$loan->term);

        $pdf->SetXY(17, 203);
        $pdf->Write(0,strtoupper($loan->customer->name));

        $pdf->SetXY(17, 209);
        $pdf->MultiCell(95,4,$loan->customer->current_address);

        $pdf->SetXY(130, 203);
        $pdf->Write(0,$loan->comaker);
        $pdf->SetXY(130, 210);
        $pdf->Write(0,"BACOLOD CITY");



        $pdf->Output();
    }

    private function createAccounts(Request $request, Loan $loan){

        if ( $request->has('create_accounts') ) {
            $this->createLRAccount($request, $loan);
            $this->createUiiAccount($request, $loan);
            $this->createRffAccount($request, $loan);
            $this->createArAccount($request, $loan);
        }
    }

    private function createLRAccount(Request $request, Loan $loan){
        $customer_code = $request->input('customer_code');
        $class = substr($loan->collateral->collateralClass->class_code,-2);

        /**
         * check L/R ACCOUNT
         */

        $lr_account = Setting::whereSettingKey('LR_ACCT')->first()->setting_value;
        $lr_account_code = $lr_account . $class . $customer_code;

        /**
         * search if account code is found
         */
        $account_desc = 'L/R - ' . $loan->customer->name;
        if ( ChartOfAccount::whereAccountCode($lr_account_code)->first() == null ) {
            $LrAccount = ChartOfAccount::create([
                'account_code' => $lr_account_code,
                'account_desc' => $account_desc,
                'account_type_id' => '1',
                'main_account_id' => ChartOfAccount::whereAccountCode(102)->first()->id
            ]);
        } else {
            $LrAccount = ChartOfAccount::whereAccountCode($lr_account_code)->first();
            $LrAccount->main_account_id = ChartOfAccount::whereAccountCode(102)->first()->id;
            $LrAccount->save();
        }

        $loan->lr_account_id = $LrAccount->id;
        $loan->save();
    }

    private function createUiiAccount(Request $request, Loan $loan){
        $customer_code = $request->input('customer_code');
        $class = substr($loan->collateral->collateralClass->class_code,-2);


        $account = Setting::whereSettingKey('UII_ACCT')->first()->setting_value;
        $account_code = $account . $class . $customer_code;

        /**
         * search if account code is found
         */
        $account_desc = 'UII - ' . $loan->customer->name;
        if ( ChartOfAccount::whereAccountCode($account_code)->first() == null ) {
            $Account = ChartOfAccount::create([
                'account_code' => $account_code,
                'account_desc' => $account_desc,
                'account_type_id' => '2',
                'main_account_id' => ChartOfAccount::whereAccountCode(206)->first()->id
            ]);
        } else {
            $Account = ChartOfAccount::whereAccountCode($account_code)->first();
            $Account->main_account_id = ChartOfAccount::whereAccountCode(206)->first()->id;
            $Account->save();
        }

        $loan->uii_account_id = $Account->id;
        $loan->save();
    }

    private function createRffAccount(Request $request, Loan $loan){
        $customer_code = $request->input('customer_code');
        $class = substr($loan->collateral->collateralClass->class_code,-2);


        $account = Setting::whereSettingKey('RFF_ACCT')->first()->setting_value;
        $account_code = $account . $class . $customer_code;

        /**
         * search if account code is found
         */
        $account_desc = 'RFF - ' . $loan->customer->name;
        if ( ChartOfAccount::whereAccountCode($account_code)->first() == null ) {
            $Account = ChartOfAccount::create([
                'account_code' => $account_code,
                'account_desc' => $account_desc,
                'account_type_id' => '2',
                'main_account_id' => ChartOfAccount::whereAccountCode(207)->first()->id
            ]);
        } else {
            $Account = ChartOfAccount::whereAccountCode($account_code)->first();
            $Account->main_account_id = ChartOfAccount::whereAccountCode(207)->first()->id;
            $Account->save();
        }



        $loan->rff_account_id = $Account->id;
        $loan->save();
    }

    private function createArAccount(Request $request, Loan $loan){
        $customer_code = $request->input('customer_code');
        $class = substr($loan->collateral->collateralClass->class_code,-2);


        $account = Setting::whereSettingKey('AR_ACCT')->first()->setting_value;
        $account_code = $account . $class . $customer_code;

        /**
         * search if account code is found
         */
        $account_desc = 'A/R - ' . $loan->customer->name;
        if ( ChartOfAccount::whereAccountCode($account_code)->first() == null ) {
            $Account = ChartOfAccount::create([
                'account_code' => $account_code,
                'account_desc' => $account_desc,
                'account_type_id' => '1',
                'main_account_id' => ChartOfAccount::whereAccountCode(109)->first()->id
            ]);
        } else {
            $Account = ChartOfAccount::whereAccountCode($account_code)->first();
            $Account->main_account_id = ChartOfAccount::whereAccountCode(109)->first()->id;
            $Account->save();
        }



        $loan->ar_account_id = $Account->id;
        $loan->save();
    }

    public function getLoan(Loan $loan){
        return Loan::with('customer')->whereId($loan->id)->first();
    }

    public function printAccountLedger(Loan $loan){
        return view('reports.print_account_ledger', compact([
            'loan'
        ]));
    }
}

