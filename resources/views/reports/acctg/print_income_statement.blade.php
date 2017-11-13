@extends('layouts.printable_reports')
@section('content')
    <div class="text-center" style="font-size:14px; font-weight: bold;">
        Income Statement <br/>
        {{ \Carbon\Carbon::parse($from_date)->toFormattedDateString() }} - {{ \Carbon\Carbon::parse($to_date)->toFormattedDateString() }}
    </div>
    <table class="table">
        <thead>
            <tr>
                <th colspan="2">&nbsp;</th>
                <th colspan="2" class="text-center">PREVIOUS MONTH</th>
                <th colspan="2" class="text-center">CURRENT MONTH</th>
                <th colspan="2" class="text-center">BALANCE TO DATE</th>
            </tr>
            <tr>
                <th>ACCT CODE</th>
                <th>ACCOUNT NAME</th>
                <th class="text-center">DEBIT</th>
                <th class="text-center">CREDIT</th>
                <th class="text-center">DEBIT</th>
                <th class="text-center">CREDIT</th>
                <th class="text-center">DEBIT</th>
                <th class="text-center">CREDIT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th colspan="8">REVENUE</th>
            </tr>
            @foreach($RevenueAccounts as $RevenueAccount)
                @if( strlen($RevenueAccount->account_code) < 5 )
                    <tr>
                        <td colspan="8">&nbsp;</td>
                    </tr>
                @endif

                <tr>
                    <td>{{ $RevenueAccount->account_code }}</td>
                    <td>{{ strtoupper($RevenueAccount->account_desc) }}</td>
                    <td class="text-right">{{ number_format($RevenueAccount->previous_month_debit,2) }}</td>
                    <td class="text-right">{{ number_format($RevenueAccount->previous_month_credit,2) }}</td>

                    <td class="text-right">{{ number_format($RevenueAccount->current_month_debit,2) }}</td>
                    <td class="text-right">{{ number_format($RevenueAccount->current_month_credit,2) }}</td>

                    <td class="text-right">{{ number_format($RevenueAccount->balance_to_date_debit,2) }}</td>
                    <td class="text-right">{{ number_format($RevenueAccount->balance_to_date_credit,2) }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2" class="text-right">GROSS INCOME</th>
                <th class="text-right">{{ number_format($TotalRevenue->previous_month_debit,2) }}</th>
                <th class="text-right">{{ number_format($TotalRevenue->previous_month_credit,2) }}</th>
                <th class="text-right">{{ number_format($TotalRevenue->current_month_debit,2) }}</th>
                <th class="text-right">{{ number_format($TotalRevenue->current_month_credit,2) }}</th>
                <th class="text-right">{{ number_format($TotalRevenue->balance_to_date_debit,2) }}</th>
                <th class="text-right">{{ number_format($TotalRevenue->balance_to_date_credit,2) }}</th>
            </tr>
        </tbody>
        <thead>
        <tr>
            <th colspan="8">EXPENSES</th>
        </tr>
        </thead>
        <tbody>
        @foreach($ExpenseAccounts as $ExpenseAccount)
            @if( strlen($ExpenseAccount->account_code) < 5 )
                <tr>
                    <td colspan="8">&nbsp;</td>
                </tr>
            @endif
            <tr>
                <td>{{ $ExpenseAccount->account_code }}</td>
                <td>{{ strtoupper($ExpenseAccount->account_desc) }}</td>
                <td class="text-right">{{ number_format($ExpenseAccount->previous_month_debit,2) }}</td>
                <td class="text-right">{{ number_format($ExpenseAccount->previous_month_credit,2) }}</td>

                <td class="text-right">{{ number_format($ExpenseAccount->current_month_debit,2) }}</td>
                <td class="text-right">{{ number_format($ExpenseAccount->current_month_credit,2) }}</td>

                <td class="text-right">{{ number_format($ExpenseAccount->balance_to_date_debit,2) }}</td>
                <td class="text-right">{{ number_format($ExpenseAccount->balance_to_date_credit,2) }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="2" class="text-right">TOTAL EXPENSES</th>
            <th class="text-right">{{ number_format($TotalExpense->previous_month_debit,2) }}</th>
            <th class="text-right">{{ number_format($TotalExpense->previous_month_credit,2) }}</th>
            <th class="text-right">{{ number_format($TotalExpense->current_month_debit,2) }}</th>
            <th class="text-right">{{ number_format($TotalExpense->current_month_credit,2) }}</th>
            <th class="text-right">{{ number_format($TotalExpense->balance_to_date_debit,2) }}</th>
            <th class="text-right">{{ number_format($TotalExpense->balance_to_date_credit,2) }}</th>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="2" class="text-right">NET INCOME/(LOSS)</th>
            <th class="text-right">{{ number_format($TotalRevenue->previous_month_credit - $TotalRevenue->previous_month_debit  - $TotalExpense->previous_month_debit + $TotalExpense->previous_month_credit,2) }}</th>
            <th></th>
            <th class="text-right">{{ number_format($TotalRevenue->current_month_credit - $TotalRevenue->current_month_debit - $TotalExpense->current_month_debit + $TotalExpense->current_month_credit,2) }}</th>
            <th></th>
            <th class="text-right">{{ number_format($TotalRevenue->balance_to_date_credit - $TotalRevenue->balance_to_date_debit - $TotalExpense->balance_to_date_debit + $TotalExpense->balance_to_date_credit,2) }}</th>
            <th></th>
        </tr>
        </tfoot>
    </table>
@endsection