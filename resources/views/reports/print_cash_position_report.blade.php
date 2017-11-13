@extends('layouts.printable_reports')
@section('content')
    <style>
        @media print {
            @page {
                size: letter landscape;
            }
            input {
                border:none;
                padding:0px;
                margin:0px;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        table tr th {
            text-align: center;
            border:none !important;
        }

        table tr td {
            border:none !important;
        }

        table tr th {
            padding: 2px !important;
        }

        table tr td{
            padding: 2px !important;
        }

        table tfoot th {
            border-bottom: 3px double #000 !important;
            border-top: 3px double #000 !important;
            font-weight: bold;
        }

        ul {
            margin: 0px;
            padding: 0px;
        }

        li {
            list-style: none;
        }

        td.indent-1 {
            padding-left: 8px !important;
        }

        td.indent-2 {
            padding-left: 16px !important;
        }

        td.indent-3 {
            padding-left: 24px !important;
        }
        tr.underline td {
            border-bottom: 1px solid #000 !important;
        }

        input[type='text']{
            text-align: right;
        }

        input[type='text']:read-only{
            border:none;
        }

    </style>
    <div style="font-weight: bold;">
        {{ config('app.name', 'Company Name') }}<br/>
        CASH POSITION, DISBURSEMENT, AND AVAILMENT REPORT<br/>
        Transaction Date : {{ \Carbon\Carbon::parse($date)->toFormattedDateString() }}
    </div>
    <table class="table">
        <thead>
            <tr>
                <th colspan="2"></th>
                @foreach( $Banks as $Bank )
                    <th></th>
                    <th class="text-right">{{ $Bank->bank_desc }}</th>
                @endforeach

                <th class="text-right">GRAND TOTAL</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="2">Previous Balances</td>
            @foreach( $Banks as $i=> $Bank )
                <td></td>
                <td class="text-right"><input type="text" class="text-right c{{ $i }} c previous-balance"></td>
            @endforeach

            <td class="text-right"><input type="text" class="text-right total" readonly></td>
        </tr>
        <tr>
            <td colspan="2">RECEIPTS</td>
        </tr>
        <tr>
            <td class="indent-1" colspan="2">COLLECTIONS</td>
        </tr>
        <tr>
            <td class="indent-2" colspan="2">OR# {{ $Collections->min_or_no }} to {{ $Collections->max_or_no }}</td>
            @foreach( $Banks as $i => $Bank )
                <td></td>
                <td class="text-right"><input type="text" class="c{{ $i }} c item collection" readonly value='{{ number_format($Bank->total_collection,2) }}'></td>
            @endforeach
            <td class="text-right"><input type="text" class="text-right total" readonly></td>
        </tr>
        <tr>
            <td class="indent-1" colspan="2">Fund Transfer</td>
            @foreach( $Banks as $i => $Bank )
                <td></td>
                <td class="text-right"><input type="text" class="text-right c{{ $i }} c item collection"></td>
            @endforeach
            <td class="text-right"><input type="text" class="text-right total" readonly></td>
        </tr>
        <tr>
            <td class="indent-1" colspan="2">Adjustments</td>
            @foreach( $Banks as $i => $Bank )
                <td></td>
                <td class="text-right"><input type="text" class="text-right c{{ $i }} c item collection"></td>
            @endforeach
            <td class="text-right"><input type="text" class="text-right total" readonly></td>
        </tr>
        <tr>
            <td class="indent-1" colspan="2">Others</td>
            @foreach( $Banks as $i => $Bank )
                <td></td>
                <td class="text-right"><input type="text" class="text-right c{{ $i }} c item collection"></td>
            @endforeach
            <td class="text-right"><input type="text" class="text-right total" readonly></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td class="indent-1" colspan="2">Today's Funds</td>
            @foreach( $Banks as $i => $Bank )
                <td></td>
                <td class="text-right"><input type="text" readonly class="text-right c{{ $i }} c todays-funds"></td>
            @endforeach
            <td class="text-right"><input type="text" class="text-right total" readonly></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">Total Available Fund</td>
            @foreach( $Banks as $i => $Bank )
                <td></td>
                <td class="text-right"><input type="text" readonly class="text-right c{{ $i }} c total-available-funds"></td>
            @endforeach
            <td class="text-right"><input type="text" class="text-right total" readonly></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">LESS: DISBURSEMENTS</td>
        </tr>
        <tr class="underline">
            <td>CV No</td>
            <td>Payee/Description</td>

            @foreach( $Banks as $Bank )
                <td>Check No.</td>
                <td class="text-right">Amount</td>
            @endforeach
        </tr>
        @foreach($Disbursements as $Disbursement)
        <tr>
            <td>{{ $Disbursement->cv_no }}</td>
            <td>{{ $Disbursement->customer_name }}</td>

            @foreach($Banks as $i => $Bank)
                @if( $Disbursement->chart_of_account_id == $Bank->account->id )
                    <td>{{ $Disbursement->check_no }}</td>
                    <td class="text-right"><input type='text' readonly class="c{{ $i }} c item disbursement" value='{{ number_format($Disbursement->amount,2) }}'></td>
                @else
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                @endif
            @endforeach
            <td class="text-right"><input type="text" class="text-right total" readonly></td>
        </tr>
        @endforeach
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">Total per CV</td>

            @foreach( $Banks as $Bank )
                <td></td>
                <td class="text-right"><input type='text' class="c" readonly value='{{ number_format($Bank->total_disbursements,2) }}'></td>
            @endforeach
            <td class="text-right"><input type="text" class="text-right total" readonly></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>

        <tr>
            <td  colspan="2">Returned Checks</td>
            @foreach( $Banks as $i => $Bank )
                <td></td>
                <td class="text-right"><input type="text" class="text-right c{{ $i }} c item disbursement"></td>
            @endforeach
            <td class="text-right"><input type="text" class="text-right total" readonly></td>
        </tr>

        <tr>
            <td  colspan="2">Others</td>
            @foreach( $Banks as $i => $Bank )
                <td></td>
                <td class="text-right"><input type="text" class="text-right c{{ $i }} c item disbursement"></td>
            @endforeach
            <td class="text-right"><input type="text" class="text-right total" readonly></td>
        </tr>

        <tr>
            <td colspan="2">Total Disbursements</td>

            @foreach( $Banks as $i => $Bank )
                <td></td>
                <td class="text-right"><input type='text' readonly class="c{{ $i }} c total-disbursements" value='{{ number_format($Bank->total_disbursements,2) }}'></td>
            @endforeach
        </tr>

        <tr>
            <td>&nbsp;</td>
        </tr>

        <tr>
            <td colspan="2">ENDING BALANCES</td>

            @foreach( $Banks as $i => $Bank )
                <td></td>
                <td class="text-right"><input type='text' class="c{{ $i }} c ending-balance" readonly value='{{ number_format($Bank->ending_balance,2) }}'></td>
            @endforeach
            <td class="text-right"><input type="text" class="text-right total" readonly></td>
        </tr>

        </tbody>

    </table>

    <div class="row">
        <div class="col-xs-8" style="margin: 16px 0px;">
            <div style="font-weight: bold; border-bottom:3px double #000;">
                AVAILMENT REPORT
            </div>
            <table>
                <thead>
                <tr>
                    <th>ACCOUNT</th>
                    <th>PROM NOTE</th>
                    <th>CASH OUT</th>
                    <th>NET PROCEEDS</th>
                    <th># OF ACCOUNTS</th>
                    <th>S FEES</th>
                </tr>
                </thead>
                <tbody>
                @foreach( $Summaries as $Summary  )
                    <tr>
                        <td>{{ $Summary->class_desc }}</td>
                        <td class="text-right">{{ number_format($Summary->pn_amount,2) }}</td>
                        <td class="text-right">{{ number_format($Summary->amount,2) }}</td>
                        <td class="text-right">{{ number_format($Summary->net_proceeds,2) }}</td>
                        <td class="text-right">{{ ( $Summary->pn_amount <= 0 ) ? number_format(0,2) : number_format($Summary->number_of_accounts,2)  }}</td>
                        <td class="text-right">{{ number_format($Summary->service_fees,2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Previous</th>
                    <th class="text-right">{{ number_format($PreviousSummary->pn_amount,2) }}</th>
                    <th class="text-right">{{ number_format($PreviousSummary->amount,2) }}</th>
                    <th class="text-right">{{ number_format($PreviousSummary->net_proceeds,2) }}</th>
                    <th class="text-right">{{ number_format($PreviousSummary->number_of_accounts,2) }}</th>
                    <th class="text-right">{{ number_format($PreviousSummary->service_fees,2) }}</th>
                </tr>

                <tr>
                    <th>Today</th>
                    <th class="text-right">{{ number_format($TodaySummary->pn_amount,2) }}</th>
                    <th class="text-right">{{ number_format($TodaySummary->amount,2) }}</th>
                    <th class="text-right">{{ number_format($TodaySummary->net_proceeds,2) }}</th>
                    <th class="text-right">{{ number_format($TodaySummary->number_of_accounts,2) }}</th>
                    <th class="text-right">{{ number_format($TodaySummary->service_fees,2) }}</th>
                </tr>

                <tr>
                    <th>Total</th>
                    <th class="text-right">{{ number_format($TotalSummary->pn_amount,2) }}</th>
                    <th class="text-right">{{ number_format($TotalSummary->amount,2) }}</th>
                    <th class="text-right">{{ number_format($TotalSummary->net_proceeds,2) }}</th>
                    <th class="text-right">{{ number_format($TotalSummary->number_of_accounts,2) }}</th>
                    <th class="text-right">{{ number_format($TotalSummary->service_fees,2) }}</th>
                </tr>
                </tfoot>
            </table>
        </div>
        <div class="col-xs-9">
            <div class="row">
                <div class="col-xs-4 text-center">
                    Prepared by:
                </div>
                <div class="col-xs-4 text-center">
                    Checked by:
                </div>
                <div class="col-xs-4 text-center">
                    Noted by:
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4 text-center" style="padding-top: 70px;">
                    <span contenteditable="true">&nbsp;</span>
                </div>
                <div class="col-xs-4 text-center" style="padding-top: 70px;">
                    Accounting
                </div>
                <div class="col-xs-4 text-center" style="padding-top: 70px;">
                    
                    Manager
                </div>
            </div>
        </div>
    </div>


    <script>
        $(function(){

            function computeSubtotal() {
                @foreach( $Banks as $i => $Bank )
                    var todays_funds = numeral(0);
                    $('.c{{ $i }}.item.collection').each(function(index,obj){
                        var value = numeral($(obj).val()).value();
                        if ( value != null ) {
                            todays_funds.add(value);
                        }
                    });

                    $('.c{{ $i }}.todays-funds').val(todays_funds.format('0,0.00'));

                    var total_available_funds = numeral(0);

                    var previous_balance = numeral($('.c{{ $i }}.previous-balance').val()).value();
                    if ( previous_balance != null ) {
                        total_available_funds.add(previous_balance);
                    }
                    total_available_funds.add(todays_funds.value());
                    $('.c{{ $i }}.total-available-funds').val(total_available_funds.format('0,0.00'));

                    //Disbursements

                    var total_disbursements = numeral(0);
                    $('.c{{ $i }}.item.disbursement').each(function(index,obj){
                        var value = numeral($(obj).val()).value();
                        if ( value != null ) {
                            total_disbursements.add(value);
                        }
                    });

                    $('.c{{ $i }}.total-disbursements').val(total_disbursements.format('0,0.00'));

                    var ending_balance = numeral(total_available_funds.value() - total_disbursements.value());
                    $('.c{{ $i }}.ending-balance').val(ending_balance.format('0,0.00'));
                @endforeach

                /* *
                 * Compute Grand Total
                 */

                $('table tbody tr').each(function(index, obj){

                    if ( $(this).find('.c').length > 0 ) {
                        var total = numeral(0);
                        $(this).find('.c').each(function( i, o ) {
                            var value = numeral($(o).val()).value();
                            if ( value != null ) {
                                total.add(value);
                            }
                        });

                        $(this).find('.total').val(total.format('0,0.00'));
                    }


                });
            }

            @foreach( $Banks as $i => $Bank )
                $(".c{{ $i }}.item.collection").keyup(function () {
                    computeSubtotal();
                });

            $(".c{{ $i }}.item.disbursement").keyup(function () {
                computeSubtotal();
            });

            $(".c{{ $i }}.previous-balance").keyup(function () {
                computeSubtotal();
            });
            @endforeach

            computeSubtotal();
        });
    </script>

@endsection