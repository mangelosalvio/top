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
            table-layout: fixed;;
        }

        .signatory-table td{
            border: 1px solid #000;
            text-align: center;
        }

        textarea{
            font-size: 1.5vw !important;
            width:100%;
            border: none;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2>
                    UNLIFINANCE CORPORATION <br/>
                    <small>Corner Rosario-Amapola St. Bacolod City</small>
                </h2>
                <h3>
                    CASH VOUCHER
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-1 col-xs-offset-8 text-right">CV#</div>
            <div class="col-xs-3 b-bottom">{{ str_pad($check_voucher->cv_no,7,0,STR_PAD_LEFT) }}</div>
        </div>
        <div class="row">
            <div class="col-xs-2">PAY TO</div>
            <div class="col-xs-6 b-bottom">{{  strtoupper($check_voucher->customer_name) }}</div>
            <div class="col-xs-1 text-right">DATE</div>
            <div class="col-xs-3 b-bottom">{{ date("F j, Y") }}</div>
        </div>

        <div class="row">
            <div class="col-xs-2">PESOS</div>
            <div class="col-xs-10 b-bottom">
                {{ $words }}
            </div>
        </div>

        <div class="row" style="height:350px; margin-top: 5px;">
            <div class="col-xs-8" style="outline:1px solid #000; height:100%;">
                <div class="row">
                    <div class="col-xs-12 text-center" style="outline:1px solid #000;">
                        E  X  P  L  A  N  A  T  I  O  N
                    </div>

                    <div class="col-xs-12">
                        <div class="row" style="font-size:10px !important;">
                            For: <br>
                            <textarea rows="10" style="white-space: pre-line;" wrap="hard">{!! $check_voucher->explanation !!}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-4" style="height:100%; outline: 1px solid #000;">
                <div class="row">
                    <div class="col-xs-12 text-center" style="outline: 1px solid #000;">
                        AMOUNT
                    </div>
                    <div class="col-xs-12 text-center" style="padding-top:50px; ">
                        {{ number_format( $check_voucher->amount ,2) }}
                    </div>
                </div>
            </div>

        </div>

        <div class="row" style="outline:1px solid #000;">
            <div class="col-xs-7" style="outline:1px solid #000; height:100%;">
                <div class="row">
                    <div class="col-xs-12">
                        JOURNAL ENTRY
                    </div>
                    <div class="col-xs-12">
                        <div class="row">
                            @foreach($check_voucher->details as $Detail)
                                <div class="col-xs-8">
                                    {{ $Detail->account->account_desc }}
                                </div>
                                <div class="col-xs-2 text-right">
                                    @if( $Detail->debit > 0 )
                                        {{ number_format($Detail->debit,2) }}
                                    @endif
                                </div>
                                <div class="col-xs-2 text-right">
                                    @if( $Detail->credit > 0 )
                                        {{ number_format($Detail->credit,2) }}
                                    @endif
                                </div>
                            @endforeach


                            <div class="col-xs-10">
                                RCBC SAVINGS
                            </div>
                            <div class="col-xs-2 text-right">
                                {{ number_format( $check_voucher->amount ,2) }}
                            </div>



                        </div>
                        <div class="row" style="padding-top:50px;">
                            <table class="signatory-table">
                                <tr>
                                    <td>
                                        Made By:
                                    </td>
                                    <td>
                                        Checked By:
                                    </td>
                                    <td>
                                        Audited By:
                                    </td>
                                    <td>
                                        Approved for Payment:
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        <br/><br/>
                                        EOG
                                    </td>
                                    <td class="text-center">
                                        <br/><br/>
                                        JJP
                                    </td>
                                    <td>
                                        <br/><br/>
                                    </td>
                                    <td class="text-center">
                                        <br/><br/>
                                        BKPASCUAL
                                    </td>
                                </tr>
                            </table>
                            <div class="row text-center">
                                {{ date("m/d/Y H:i:s") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-5" style="outline:1px solid #000; height:100%;">
                <div class="row">
                    <div class="col-xs-12">
                        Received from <br/><br/>
                        The sum of <br/>
                    </div>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 b-bottom">
                                &nbsp;
                            </div>
                            <div class="col-xs-12 b-bottom">
                                {{ $check_voucher->bank->bank_desc }} {{ $check_voucher->check_no }}
                            </div>
                            <div class="col-xs-12 b-bottom">
                                &nbsp;
                            </div>
                            <div class="col-xs-12 b-bottom">
                                &nbsp;
                            </div>
                            <div class="col-xs-12 b-bottom">
                                &nbsp;
                            </div>
                            <div class="col-xs-12">
                                as full/partial payment of the above
                            </div>

                            <div class="col-xs-12 b-bottom">
                                <br/><br/><br/>
                            </div>
                            <div class="col-xs-12 text-center">
                                PAYEE'S SIGNATURE
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection