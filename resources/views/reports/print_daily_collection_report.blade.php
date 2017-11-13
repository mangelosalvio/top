@extends('layouts.printable_reports')
@section('content')
    <style>
        @media print {
            * {
                font-size: 16px !important;
            }

            @page {
                size: legal landscape;
            }

            input {
                border:none;
                padding:0px;
                margin:0px;
            }
        }

        table tr th {
            text-align: center;
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
        }

        ul {
            margin: 0px;
            padding: 0px;
        }

        li {
            list-style: none;
        }

        input[type='text']{
            text-align: right;
            width:100px;
        }

        input[type='text']:read-only{
            border:none;
        }


    </style>
    <div class="text-center" style="font-weight: bold;">
        UNLIFINANCE <br/>
        DAILY COLLECTION REPORT<br/>
        {{ \Carbon\Carbon::parse($date)->toFormattedDateString() }}
    </div>
    <table class="table">
        <thead>
            <tr>
                <th colspan="3"></th>
                <th colspan="2">AMOUNT</th>
                <th colspan="3">APPLICATION</th>
                <th>SERVICE</th>
                <th>UNEARNED</th>
                <th>INTEREST</th>
            </tr>
            <tr>
                <th style="width:5%;">OR NO</th>
                <th>RECEIVED FROM</th>
                <th style="width:5%;">ACCT #</th>
                <th style="width:5%;">C O H</th>
                <th style="width:5%;">C O C I</th>
                <th style="width:5%;">PENALTY</th>
                <th style="width:5%;">REBATE</th>
                <th style="width:5%;">PRINCIPAL</th>
                <th style="width:5%;">F E E S</th>
                <th style="width:5%;">INT. INC.</th>
                <th style="width:5%;">FIN FEES</th>
                <th style="width:5%;">OTHERS</th>
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td colspan="2">BALANCE FORWARDED</td>
                    <td class="text-right"><input type='text' class="balance_forwarded"></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><input type='text' class='penalty balance'></td>
                    <td class="text-right"><input type='text' class='rebate balance'></td>
                    <td class="text-right"><input type='text' class='principal balance'></td>
                    <td class="text-right"><input type='text' class='service_fees balance'></td>
                    <td class="text-right"><input type='text' class='uii balance'></td>
                    <td class="text-right"><input type='text' class='interest_fin_fees balance'></td>
                    <td></td>
                </tr>
            @foreach($Accounts as $i => $Account)
                <tr>
                    <td>{{ $Account->or_no }}</td>
                    <td nowrap="nowrap">{{ $Account->customer->name  or $Account->received_from }}</td>
                    <td>{{ $Account->LrAccount->account_code or '' }}</td>
                    <td class="text-right"><input type='text' readonly="readonly" class='coh item' value='{{ ( $Account->cash_amount > 0 ) ? number_format($Account->cash_amount,2) : '' }}'></td>
                    <td class="text-right"><input type='text' readonly="readonly" class='coci item' value='{{ ( $Account->check_amount > 0 ) ? number_format($Account->check_amount,2) : '' }}'></td>
                    <td class="text-right"><input type='text' readonly="readonly" class='penalty item' value='{{ ( $Account->total_penalty > 0 ) ? number_format($Account->total_penalty,2) : '' }}'></td>
                    <td class="text-right"><input type='text' readonly="readonly" class='rebate item' value='{{ ( $Account->rff_credit > 0 ) ? number_format($Account->rff_credit,2) : '' }}'></td>
                    <td class="text-right"><input type='text' readonly="readonly" class='principal item' value='{{ ( $Account->principal_amount > 0 ) ? number_format($Account->principal_amount,2) : '' }}'></td>
                    <td class="text-right"><input type='text' readonly="readonly" class='service_fees item' value='{{ ( $Account->service_income > 0 ) ? number_format($Account->service_income,2) : '' }}'></td>
                    <td class="text-right"><input type='text' readonly="readonly" class='uii item' value='{{ ( $Account->uii > 0 ) ? number_format($Account->uii,2) : '' }}'></td>
                    <td class="text-right"><input type='text' readonly="readonly" class='interest_fin_fees item' value='{{ ( $Account->interest_income_credit > 0 ) ? number_format($Account->interest_income_credit,2) : '' }}'></td>
                    <td class="text-right">
                        <ul>
                            @if(isset($Account->collection))
                                @foreach ( $Account->collection->lessAccounts as $LessAccounts )
                                    <li><span style="font-size:10px;">{{ $LessAccounts->account_desc }}</span> - {{ number_format($LessAccounts->pivot->amount,2) }}</li>
                                @endforeach
                                @foreach ( $Account->collection->additionalAccounts as $AdditionalAccount )
                                    <li>{{ $AdditionalAccount->account_desc }} - {{ number_format($AdditionalAccount->pivot->amount,2) }}</li>
                                @endforeach
                            @endif
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-center">COLLECTION FOR TODAY</th>
                <th></th>
                <th class="text-right"><input type='text' readonly="readonly" class='coh subtotal' value=''></th>
                <th class="text-right"><input type='text' readonly="readonly" class='coci subtotal' value=''></th>
                <th class="text-right"><input type='text' readonly="readonly" class='penalty subtotal' value=''></th>
                <th class="text-right"><input type='text' readonly="readonly" class='rebate subtotal' value=''></th>
                <th class="text-right"><input type='text' readonly="readonly" class='principal subtotal' value=''></th>
                <th class="text-right"><input type='text' readonly="readonly" class='service_fees subtotal' value=''></th>
                <th class="text-right"><input type='text' readonly="readonly" class='uii subtotal' value=''></th>
                <th class="text-right"><input type='text' readonly="readonly" class='interest_fin_fees subtotal' value=''></th>
                <th></th>
            </tr>
            <tr>
                <th colspan="2" class="text-center">COLLECTION TO DATE</th>
                <th class="text-right"><input type='text' readonly="readonly" class='collection-to-date'></th>
                <th></th>
                <th></th>
                <th class="text-right"><input type='text' readonly="readonly" class='penalty grandtotal'></th>
                <th class="text-right"><input type='text' readonly="readonly" class='rebate grandtotal'></th>
                <th class="text-right"><input type='text' readonly="readonly" class='principal grandtotal'></th>
                <th class="text-right"><input type='text' readonly="readonly" class='service_fees grandtotal'></th>
                <th class="text-right"><input type='text' readonly="readonly" class='uii grandtotal'></th>
                <th class="text-right"><input type='text' readonly="readonly" class='interest_fin_fees grandtotal'></th>
                <th></th>

            </tr>

                <tr>
                    <th colspan="2" class="text-center">PREV COLLECTION <input type='text' value='' style="width:40%;"></th>
                    <th></th>
                    <th class="text-right"><input type='text' class='coh prev-collection'></th>
                    <th class="text-right"><input type='text' class='coci prev-collection'></th>
                    <th class="text-right"><input type='text' class='penalty prev-collection'></th>
                    <th class="text-right"><input type='text' class='rebate prev-collection'></th>
                    <th class="text-right"><input type='text' class='principal prev-collection'></th>
                    <th class="text-right"><input type='text' class='service_fees prev-collection'></th>
                    <th class="text-right"><input type='text' class='uii prev-collection'></th>
                    <th class="text-right"><input type='text' class='interest_fin_fees prev-collection'></th>
                    <th></th>
                </tr>
        </tfoot>
    </table>

    <div class="row">
        <div class="col-xs-3">
            <table>
                <tbody>
                @foreach( $Summaries as $Summary  )
                    <tr>
                        <td>{{ $Summary->class_desc }}</td>
                        <td class="text-right">{{ number_format($Summary->principal_amount,2) }}</td>
                    </tr>
                @endforeach
                </tbody>
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
                    JN.SALJAY
                </div>
                <div class="col-xs-4 text-center" style="padding-top: 70px;">
                    Accounting
                </div>
                <div class="col-xs-4 text-center" style="padding-top: 70px;">
                    JJP-Manager <br>
                    Manager
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function(){

            function computeSubtotal() {
                var columns = ['coh', 'coci', 'penalty', 'rebate', 'principal', 'service_fees', 'uii', 'interest_fin_fees'];

                var arr_subtotal = {};

                $.each(columns, function (index, column) {
                    var subtotal_value = numeral(0);
                    $('.' + column + '.item').each(function(index, obj){
                        var value = numeral($(obj).val()).value();
                        if ( value ) {
                            subtotal_value.add(value);
                        }
                    });
                    arr_subtotal[column] = subtotal_value.value();
                });

                $.each(arr_subtotal, function (key, value) {
                    $('.' + key + '.subtotal').val(numeral(value).format('0,0.00'));
                });

                var arr_grandtotal = arr_subtotal;

                $.each(columns, function (index, column) {
                    var subtotal_value = numeral(0);
                    var value = numeral($('.' + column + '.balance').val()).value();
                    if ( value ) {
                        arr_grandtotal[column] += value;
                    }
                });

                $.each(arr_grandtotal, function (key, value) {
                    $('.' + key + '.grandtotal').val(numeral(value).format('0,0.00'));
                });

                var collection_to_date = numeral(0);
                collection_to_date.add(arr_grandtotal['coh']);
                collection_to_date.add(arr_grandtotal['coci']);

                var bal = numeral($('.balance_forwarded').val()).value();
                if ( bal ) {
                    collection_to_date.add(bal);
                }

                $('.collection-to-date').val(collection_to_date.format('0,0.00'));
            }

            computeSubtotal();

            $('.balance, .balance_forwarded').keyup(function(){
                computeSubtotal();
            });
        });
    </script>

@endsection