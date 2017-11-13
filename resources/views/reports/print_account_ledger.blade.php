@extends('layouts.reports')
@section('content')
    <style>

        * {
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px !important;
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
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead th {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }

        table.ledger-table{
        }

        table.ledger-table tr th:nth-child(n+3),
        table.ledger-table tr td:nth-child(n+3){
            text-align: right;
            padding: 0px 6px !important;
        }
        table.ledger-table tr td {
            padding: 0px 6px !important;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 text-center">
                UNLIFINANCE CORPORATION <br/>
                Corner Rosario-Amapola St. Bacolod City<br/><br/>
                CUSTOMER'S ACCOUNT LEDGER
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
                <span class="b-bottom">{{ $loan->lrAccount->account_code or '' }}</span>
            </div>
            <div class="col-xs-4 col-xs-offset-2">
                <span class="b-bottom">{{ $loan->transType->label  }}</span>
            </div>
        </div>


        <div class="row indent-top">
            <div class="col-xs-2">Name:</div>
            <div class="col-xs-4 b-bottom">{{ strtoupper($loan->customer->name) }}</div>
            <div class="col-xs-2">Amount of Loan</div>
            <div class="col-xs-4 text-right">{{ number_format($loan->amount,2) }}</div>
        </div>

        <div class="row">
            <div class="col-xs-2">
                Address:
            </div>
            <div class="col-xs-4 b-bottom">
                {{ $loan->customer->current_address }}
            </div>

            <div class="col-xs-1">
                Rate:
            </div>
            <div class="col-xs-2">
                {{ $loan->interest_rate }}+{{ $loan->rebate_rate }}
            </div>
            <div class="col-xs-1">
                UII:
            </div>
            <div class="col-xs-2 text-right">
                {{ number_format($loan->interest_amount,2) }}
            </div>
        </div>

        <div class="row">
            <div class="col-xs-2">
                Contact No:
            </div>
            <div class="col-xs-4 b-bottom">
                {{ $loan->customer->mobile_number }}
            </div>

            <div class="col-xs-1">
                Rebate:
            </div>
            <div class="col-xs-2">
                {{ $loan->rebate_first }}/{{ $loan->rebate_second }}
            </div>
            <div class="col-xs-3 text-right">
                {{ number_format($loan->rebate_amount,2) }}
            </div>
        </div>

        <div class="row">
            <div class="col-xs-3 col-xs-offset-6">
                Promissory Note:
            </div>
            <div class="col-xs-3 text-right">
                {{ number_format($loan->pn_amount,2) }}
            </div>
        </div>

        <div class="row">
            <div class="col-xs-2">
                Unit:
            </div>
            <div class="col-xs-4 b-bottom">
                {{ $loan->collateral->collateral_desc }}
            </div>

            <div class="col-xs-3">
                First Installment Due:
            </div>
            <div class="col-xs-3">
                {{ Carbon\Carbon::parse($loan->date_purchased)->addMonth()->toFormattedDateString()  }}
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1">
                MN:
            </div>
            <div class="col-xs-2 b-bottom">
                {{ $loan->collateral->motor }}
            </div>

            <div class="col-xs-1">
                SN:
            </div>
            <div class="col-xs-2 b-bottom">
                {{ $loan->collateral->serial }}
            </div>
        </div>

        <div class="row">
            <div class="col-xs-1 col-xs-offset-3" style="white-space: nowrap;">
                Plate No:
            </div>
            <div class="col-xs-2 b-bottom">
                {{ $loan->collateral->plate }}
            </div>
            <div class="col-xs-2">
                Date Availed:
            </div>
            <div class="col-xs-1">
                {{ Carbon\Carbon::parse($loan->date_purchased)->format("m/d/Y")  }}
            </div>

            <div class="col-xs-1">
                Term
            </div>
            <div class="col-xs-2">
                {{ $loan->term }} Mos.
            </div>
        </div>

        <div class="row">
            <div class="col-xs-2">
                Comaker:
            </div>
            <div class="col-xs-4 b-bottom">
                {{ $loan->comaker }} &nbsp;
            </div>
        </div>

        <div class="row">
            <div class="col-xs-2">
                Insurance:
            </div>
            <div class="col-xs-4 b-bottom">
                &nbsp;
            </div>

            <div class="col-xs-1">
                Due Date:
            </div>
            <div class="col-xs-2">
                {{ Carbon\Carbon::parse($loan->date_purchased)->day  }}
            </div>
        </div>

        <div class="row">
            <div class="col-xs-2">
                Expiry:
            </div>
            <div class="col-xs-4 b-bottom">
                {{ Carbon\Carbon::parse($loan->date_purchased)->addMonth($loan->term)->toFormattedDateString()  }}
            </div>

            <div class="col-xs-1">
                Installments:
            </div>
            <div class="col-xs-2">
                {{ number_format($loan->installment_first,2) }} / {{ number_format($loan->installment_second,2) }}
            </div>
        </div>


        <div class="row clearfix" style="margin-top:16px;">
            <!-- Amortizatoin table here  -->
            <table>
                <thead>
                <tr>
                    <th style="width:10%;">Date</th>
                    <th style="width:10%;">Or No</th>
                    <th style="width:10%;">Total Amount</th>
                    <th style="width:10%;">Discount</th>
                    <th style="width:10%;">Liquidated</th>
                    <th style="width:10%;">Outstanding</th>
                    <th>Remarks</th>
                </tr>
                </thead>
            </table>

            <div style="float: right; width:30%;">
                <div style="width:100%;">
                    <table class="ledger-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>DUE</th>
                            <th>INSTALLMENT</th>
                            <th>OUTBAL</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="width:20px;"></td>
                            <td style="width:40%"></td>
                            <td></td>
                            <td> {{ number_format($loan->pn_amount,2) }}</td>
                        </tr>
                        @foreach( $loan->amortizationTables as $i => $AmortizationTable )
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td style="white-space: nowrap;">{{ Carbon\Carbon::parse($AmortizationTable->due_date)->toFormattedDateString() }}</td>
                                <td class="text-right">{{ number_format($AmortizationTable->installment_amount,2) }}</td>
                                <td class="text-right">{{ number_format($AmortizationTable->outstanding_balance,2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="width:100%;">
                    <table class="ledger-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>DUE</th>
                            <th>UII</th>
                            <th>OUTINT</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="width:20px;"></td>
                            <td style="width:40%"></td>
                            <td></td>
                            <td> {{ number_format($loan->interest_amount,2) }}</td>
                        </tr>
                        @foreach( $loan->amortizationTables as $i => $AmortizationTable )
                            <tr>
                                <td style="width:20px;">{{ $i+1 }}</td>
                                <td style="width:40%; white-space: nowrap;">{{ Carbon\Carbon::parse($AmortizationTable->due_date)->toFormattedDateString() }}</td>
                                <td class="text-right">{{ number_format($AmortizationTable->interest_amount,2) }}</td>
                                <td class="text-right">{{ number_format($AmortizationTable->outstanding_interest,2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection