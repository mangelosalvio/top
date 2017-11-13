@extends('layouts.reports')
@section('content')
    <style>
        @media print {
            * {
                font-size : 1.5vw;
            }
        }
        *{
            color:#000;
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            background-color: #fff;
            color: #000;
        }
        .b-bottom {
            border-bottom:1px solid #000;
        }

        .b-bottom-2{
            border-bottom: 5px double #000;
        }


        .w-80{
            width:80%;
        }

        .indent-1 {
            padding-left: 40px;
        }

        .indent-2 {
            padding-left: 80px;
        }

        .indent-3 {
            padding-left: 120px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2>
                    {{ config('app.name', 'Company Name') }}
                </h2>
                <h3>
                    COMPUTATION SLIP
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-2">APPLICANT:</div>
            <div class="col-xs-10 b-bottom">{{ $loan->customer->name }}</div>
        </div>

        <div class="row">
            <div class="col-xs-2">UNIT APPLIED:</div>
            <div class="col-xs-10 b-bottom">{{ $loan->collateral->collateral_desc }}</div>
        </div>

        <div class="row">
            <div class="col-xs-2">TERM:</div>
            <div class="col-xs-6 b-bottom">{{ $loan->term }} months</div>
            <div class="col-xs-1">RATE:</div>
            <div class="col-xs-3 b-bottom">{{ $loan->interest_rate }} + {{ $loan->rebate_rate }}</div>
        </div>

        <div class="row">
            <div class="col-xs-2 indent-1">Cash Price</div>
            <div class="col-xs-offset-8  col-xs-2 text-right b-bottom">{{ number_format($loan->amount,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-2 indent-1">Downpayment</div>
            <div class="col-xs-2 col-xs-offset-6 text-right">
                <div class="w-80 b-bottom">%</div>
            </div>
            <div class="col-xs-2 text-right b-bottom">&nbsp;</div>
        </div>

        <div class="row">
            <div class="col-xs-4 indent-1">Balance (Amount to be Finance)</div>
            <div class="col-xs-offset-6  col-xs-2 text-right b-bottom">&nbsp;</div>
        </div>

        <div class="row">
            <div class="col-xs-2 indent-1"><strong>FINANCE CHARGES</strong></div>
            <div class=" col-xs-2 b-bottom">
                <div class="w-80">
                    P
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-5 indent-2">Net Purchase Discount</div>
            <div class="col-xs-2 col-xs-offset-3 text-right">
                <div class="w-80 b-bottom">{{ $loan->interest_rate }}%</div>
            </div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->interest_amount,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-5 indent-2">Rebatable Purchase Discount</div>
            <div class="col-xs-2 col-xs-offset-3 text-right">
                <div class="w-80 b-bottom">{{ $loan->rebate_rate }}%</div>
            </div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->rebate_amount,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-5 indent-2">Omnbus Tax</div>
            <div class="col-xs-2 col-xs-offset-3 text-right">
                <div class="w-80 b-bottom">%</div>
            </div>
            <div class="col-xs-2 text-right b-bottom">&nbsp;</div>
        </div>

        <div class="row">
            <div class="col-xs-5 indent-2">Service Fee</div>
            <div class="col-xs-2 col-xs-offset-3 text-right">
                <div class="w-80 b-bottom">%</div>
            </div>
            <div class="col-xs-2 text-right b-bottom">&nbsp;</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-1"><strong>AMOUNT OF PROMISSORY NOTE</strong></div>
            <div class="col-xs-2 text-right b-bottom-2">&nbsp;</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-2">First Installment</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->installment_first,2) }}</div>
        </div>
        <div class="row">
            <div class="col-xs-10 indent-3">Less: Rebate (Discount)</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->rebate_first,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-3"><strong>NET AMOUNT DUE</strong></div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->net_first,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-2">Subsequent Installment (2nd to Last)</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->installment_second,2) }}</div>
        </div>
        <div class="row">
            <div class="col-xs-10 indent-3">Less: Rebate (Discount)</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->rebate_second,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-3"><strong>NET AMOUNT DUE</strong></div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->net_second,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-1"><strong>OTHER CHARGES (MISCELLANOUS EXPENSES)</strong></div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-2">Insurance Premium</div>
            <div class="col-xs-2 text-right b-bottom">&nbsp;</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-2">Documentary Stamps</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->doc_stamp,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-2">Science Stamps</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->science_stamps,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-2">Notarial Fee</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->notarial_fees,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-2">Miscellanous (Fare, Photocopies, Tips, Etc.)</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->misc_fees,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-2">Register of Deeds (Registration Expenses)</div>
        </div>

        <div class="row">
            <div class="col-xs-8 indent-3">Chattel Mortgate Fee</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->mortgage_fees,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-8 indent-3">Deed of Assignment Fee</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->deed_of_assignment_fees,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-8 indent-3">Legal and Research Fee</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->legal_and_research_fees,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-3">Total ROD Charges</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->total_rod_charges,2) }}</div>
        </div>

        <!-- ILTO (ANNOTATION OF MORTAGE AND ASSIGNMENT) -->

        <div class="row">
            <div class="col-xs-10 indent-2">ILTO (Annotation of Mortgage & Assignment)</div>
        </div>

        <div class="row">
            <div class="col-xs-8 indent-3">Transfer Fee</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->transfer_fees,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-8 indent-3">Mortgage & Assignment</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->mortgage_and_assignment_fees,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-8 indent-3">Miscellanous</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->misc_lto_fees,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-3">Total LTO Charges</div>
            <div class="col-xs-2 text-right b-bottom">{{ number_format($loan->total_lto_charges,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-10 indent-1"><strong>TOTAL MISCELLANEOUS CHARGES</strong></div>
            <div class="col-xs-2 text-right b-bottom-2">{{ number_format($loan->total_doc_fees,2) }}</div>
        </div>

        <div class="row" style="margin-top:20px;">
            <div class="col-xs-8 col-xs-offset-4 text-center">
                <i>In compliance with Republic Act No. 3765, I/we hereby certify that I/We were informed in writing of all the above</i>
            </div>
        </div>

        <div class="row" style="margin-top:40px;">
            <div class="col-xs-2 text-right">Prepared by:</div>
            <div class="col-xs-3 b-bottom">&nbsp;</div>

            <div class="col-xs-5 col-xs-offset-2 text-center">
                <div style="width:100%;" class="b-bottom">
                    {{ $loan->customer->name }}
                </div>
                SIGNATURE OF BORROWER
            </div>

        </div>

    </div>
@endsection