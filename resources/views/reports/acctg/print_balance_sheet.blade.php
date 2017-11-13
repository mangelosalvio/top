@extends('layouts.printable_reports')
@section('content')

    <style type="text/css">
        .grand-total th span {
            border-bottom: 3px double #000;
        }
    </style>

    <div class="text-center" style="font-weight: bold;">
        {{ config('app.name', 'Company Name') }} <br>
        {{ \Carbon\Carbon::create($year,$month)->format("F Y") }}
    </div>
    <table class="table">
        <thead>
            <tr>
                <th colspan="2">ASSETS</th>
                <th class="text-right">PREVIOUS MONTH</th>
                <th class="text-right">CURRENT MONTH</th>
                <th class="text-right">BALANCE TO DATE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($AssetAccounts as $AssetAccount)
                @if( strlen($AssetAccount->account_code) < 5 )
                    <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                @endif

                <tr>
                    <td>{{ $AssetAccount->account_code }}</td>
                    <td>{{ strtoupper($AssetAccount->account_desc) }}</td>
                    <td class="text-right">{{ number_format($AssetAccount->previous_month_amount,2) }}</td>
                    <td class="text-right">{{ number_format($AssetAccount->current_month_amount,2) }}</td>
                    <td class="text-right">{{ number_format($AssetAccount->balance_to_date,2) }}</td>
                </tr>
            @endforeach
            <tr class="grand-total">
                <th colspan="2" class="text-right">TOTAL ASSETS</th>
                <th class="text-right"><span>{{ number_format($TotalAssets->previous_month_amount,2) }}</span></th>
                <th class="text-right"><span>{{ number_format($TotalAssets->current_month_amount,2) }}</span></th>
                <th class="text-right"><span>{{ number_format($TotalAssets->balance_to_date,2) }}</span></th>
            </tr>

            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>

            <tr>
                <th colspan="2">LIABILITIES</th>
                <th class="text-right">PREVIOUS MONTH</th>
                <th class="text-right">CURRENT MONTH</th>
                <th class="text-right">BALANCE TO DATE</th>
            </tr>

            @foreach($LiabilityAccounts as $LiabilityAccount)
                @if( strlen($AssetAccount->account_code) < 5 )
                    <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr>
                @endif

                <tr>
                    <td>{{ $LiabilityAccount->account_code }}</td>
                    <td>{{ strtoupper($LiabilityAccount->account_desc) }}</td>
                    <td class="text-right">{{ number_format($LiabilityAccount->previous_month_amount,2) }}</td>
                    <td class="text-right">{{ number_format($LiabilityAccount->current_month_amount,2) }}</td>
                    <td class="text-right">{{ number_format($LiabilityAccount->balance_to_date,2) }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2" class="text-right">TOTAL LIABILITIES</th>
                <th class="text-right">{{ number_format($TotalLiabilities->previous_month_amount,2) }}</th>
                <th class="text-right">{{ number_format($TotalLiabilities->current_month_amount,2) }}</th>
                <th class="text-right">{{ number_format($TotalLiabilities->balance_to_date,2) }}</th>
            </tr>

            <tr>
                <th colspan="4">EQUITY</th>
            </tr>

            @foreach($EquityAccounts as $EquityAccount)
                <tr>
                    <td>{{ $EquityAccount->account_code }}</td>
                    <td>{{ strtoupper($EquityAccount->account_desc) }}</td>
                    <td class="text-right">{{ number_format($EquityAccount->previous_month_amount,2) }}</td>
                    <td class="text-right">{{ number_format($EquityAccount->current_month_amount,2) }}</td>
                    <td class="text-right">{{ number_format($EquityAccount->balance_to_date,2) }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2" class="text-right">TOTAL EQUITY</th>
                <th class="text-right">{{ number_format($TotalEquity->previous_month_amount,2) }}</th>
                <th class="text-right">{{ number_format($TotalEquity->current_month_amount,2) }}</th>
                <th class="text-right">{{ number_format($TotalEquity->balance_to_date,2) }}</th>
            </tr>

            <tr>
                <th colspan="2" class="text-right">NET INCOME/LOSS</th>
                <th class="text-right">{{ number_format($NetIncome->previous_month_amount,2) }}</th>
                <th class="text-right">{{ number_format($NetIncome->current_month_amount,2) }}</th>
                <th class="text-right">{{ number_format($NetIncome->balance_to_date,2) }}</th>
            </tr>

            <tr>
                <th colspan="5">&nbsp;</th>
            </tr>
            <tr class="grand-total">
                <th colspan="2" class="text-right">TOTAL LIABILITIES AND EQUITY</th>
                <th class="text-right"><span>{{ number_format($TotalLiabilityEquity->previous_month_amount,2) }}</span></th>
                <th class="text-right"><span>{{ number_format($TotalLiabilityEquity->current_month_amount,2) }}</span></th>
                <th class="text-right"><span>{{ number_format($TotalLiabilityEquity->balance_to_date,2) }}</span></th>
            </tr>
        </tbody>
    </table>
@endsection