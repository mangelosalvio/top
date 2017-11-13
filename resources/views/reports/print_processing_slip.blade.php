@extends('layouts.reports')
@section('content')
    <style>
        @media print {
            * {
                font-size: 1.5vw;
            }
        }

        * {
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background-color: #fff;
            color: #000;
        }

        .b-bottom {
            border-bottom: 1px solid #000;
        }

        .b-bottom-2 {
            border-bottom: 5px double #000;
        }

        .w-80 {
            width: 80%;
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

        .signatory-table{
            width: 100%;
            border-collapse: collapse;
        }

        .signatory-table td{
            border: 1px solid #000;
            text-align: center;
        }

        .indent-top {
            margin-top:12px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 text-center">
                {{ config('app.name', 'Company Name') }}<br/><br/>
                PROCESSING SLIP
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <span class="b-bottom">{{ $loan->loan_no }}</span>
            </div>
            <div class="col-xs-4 text-center">
                <span class="b-bottom">{{ $loan->transType->label  }}</span>
            </div>
            <div class="col-xs-4 text-right">
                <span class="b-bottom">{{ date("F j, Y H:i:s") }}</span>
            </div>

            <div class="col-xs-4">
                APPL NO.
            </div>
            <div class="col-xs-4 text-center">
                T R A N S A C T I O N
            </div>
            <div class="col-xs-4 text-right">
                DATE AND TIME
            </div>
        </div>


        <div class="row indent-top">
            <div class="col-xs-2">Applicant:</div>
            <div class="col-xs-6 b-bottom">{{ $loan->customer->name }}</div>
            <div class="col-xs-1 text-right">DATE</div>
            <div class="col-xs-3 b-bottom">{{ date("F j, Y") }}</div>
        </div>

        <div class="row">
            <div class="col-xs-2">
                Co-maker:
            </div>
            <div class="col-xs-4">
                {{ $loan->comaker }}
            </div>

            <div class="col-xs-2">
                Cash out:
            </div>
            <div class="col-xs-4">
                :{{ number_format($loan->amount,2) }}
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                    <div class="row">
                        <div class="col-xs-12">
                            Collateral/s
                        </div>
                        <div class="col-xs-12">
                            {{ $loan->collateral->collateral_desc }}
                        </div>
                    </div>
            </div>
            <div class="col-xs-6">
                <div class="row">
                    <div class="col-xs-4">
                        Term
                    </div>
                    <div class="col-xs-8">
                        :{{ $loan->term }} mo/s Rate: {{ $loan->interest_rate }} %
                    </div>

                    <div class="col-xs-4">
                        PN Amount
                    </div>
                    <div class="col-xs-8">
                        :{{ number_format($loan->pn_amount,2) }}
                    </div>

                    <div class="col-xs-4">
                        Installment
                    </div>
                    <div class="col-xs-8">
                        :{{ number_format($loan->installment_first,2) }} / {{ number_format($loan->installment_second,2) }}
                    </div>

                    <div class="col-xs-4">
                        Rebate
                    </div>
                    <div class="col-xs-8">
                        :{{ number_format($loan->rebate_first,2) }} / {{ number_format($loan->rebate_second,2) }}
                    </div>
                </div>
                <div class="row indent-top">
                    <div class="col-xs-4">
                        Salesman
                    </div>
                    <div class="col-xs-8">
                        :{{ $loan->salesman }}
                    </div>
                </div>

            </div>

        </div>

        <div class="row" style="border-top:3px double #000;">
            <div class="col-xs-12">
                CREDIT VERIFICATION:
            </div>
            <div class="col-xs-12 text-wrap-normal" style="height:300px;">
                {!! nl2br($loan->credit_verification) !!}
            </div>
        </div>

        <div class="row indent-top">
            <div class="col-xs-offset-9 col-xs-3 text-center b-bottom">
                {{ $loan->credit_investigator }}
            </div>
            <div class="col-xs-offset-9 col-xs-3 text-center">
                Credit Investigator
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12" style="border-top: 3px double #000; border-bottom: 1px solid #000;">
                RECOMMENDATION:
            </div>
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12">
                        [&nbsp;&nbsp;&nbsp;&nbsp;] Approved <br/>
                        [&nbsp;&nbsp;&nbsp;&nbsp;] Disapproved <br/>
                    </div>
                </div>
                <div class="row indent-top">
                    <div class="col-xs-offset-9 col-xs-3 text-center b-bottom">
                        {{ $loan->manager }}
                    </div>
                    <div class="col-xs-offset-9 col-xs-3 text-center">
                        Manager
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12" style="border-top: 3px double #000; border-bottom: 1px solid #000;">
                ACTION TAKEN:
            </div>
            <div class="col-xs-12" style="border-bottom: 3px double #000;">
                <div class="row">
                    <div class="col-xs-12">
                        [&nbsp;&nbsp;&nbsp;&nbsp;] Approved <br/>
                        [&nbsp;&nbsp;&nbsp;&nbsp;] Disapproved <br/>
                    </div>
                </div>

                <div class="row indent-top">
                    @if( $loan->signatories == "P" )
                    <div class="col-xs-offset-6 col-xs-3 text-center b-bottom">
                        {{ $loan->approving_signatory }}
                    </div>
                    <div class="col-xs-offset-6 col-xs-3 text-center">
                        Approving Officer/s
                    </div>
                    @else
                        <div class="col-xs-6 text-left b-bottom" style="margin:10px 0px;">
                            BOARD 1
                        </div>
                        <div class="col-xs-6 text-left b-bottom" style="margin:10px 0px;">
                            BOARD 2
                        </div>
                        <div class="col-xs-6 text-left b-bottom" style="margin:10px 0px;">
                            BOARD 3
                        </div>
                        <div class="col-xs-6 text-left b-bottom" style="margin:10px 0px;">
                            BOARD 4
                        </div>
                        <div class="col-xs-6 text-left b-bottom" style="margin:10px 0px;">
                            BOARD 5
                        </div>

                    @endif
                </div>

            </div>
        </div>

    </div>
@endsection