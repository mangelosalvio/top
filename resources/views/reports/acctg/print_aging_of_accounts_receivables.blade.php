@extends('layouts.printable_reports')
@section('content')
    <style type="text/css">
        @media print{
            @page { size: legal landscape; }
        }

        table td:nth-child(n+5){
            text-align: right;;
        }
        table {
            border-collapse: collapse;
        }
        table th{
            text-align: center;
        }
        table tfoot th{
            text-align: right;
        }
        table td, table th{
            padding: 2px;
            border:1px solid #000;
        }
    </style>
    <div class="text-center" style="font-size:14px; font-weight: bold;">
        AGING OF ACCOUNTS RECEIVABLES <br/>
        {{ \Carbon\Carbon::create($year,$month)->format("F Y") }}
    </div>
    <table>
        <thead>
        <tr>
            <th>LOAN #</th>
            <th>TERMS</th>
            <th>CUSTOMER</th>
            <th>DUE DATE</th>
            <th>PN AMOUNT</th>
            <th>OUTSTANDING BAL.</th>
            <th>OUTSTANDING INT.</th>
            <th>OUTSTANDING REBATE</th>
            <th>BILLING FOR THE MONTH</th>
            <th>BALANCE IF UPDATED</th>
            <th>TOTAL OVERDUE</th>
            <th>MONTHS OVERDUE</th>
            <th>1-30 DAYS</th>
            <th>31-60 DAYS</th>
            <th>61-90 DAYS</th>
            <th>91-120 DAYS</th>
            <th>121 DAYS & OVER</th>
            <th>TOTAL</th>
        </tr>
        </thead>
        <tbody>
        @foreach($Loans as $Loan)
            <tr>
                <td>{{ $Loan->id }}</td>
                <td>{{ (int)$Loan->term }}</td>
                <td>{{ $Loan->customer_name }}</td>
                <td>{{ \Carbon\Carbon::parse($Loan->date_purchased)->addMonth($Loan->term)->format("m/d/Y") }}</td>
                <td>{{ number_format($Loan->pn_amount,2) }}</td>
                <td>{{ number_format($Loan->outstanding_balance,2) }}</td>
                <td>{{ number_format($Loan->outstanding_interest,2) }}</td>
                <td>{{ number_format($Loan->outstanding_rebate,2) }}</td>
                <td>{{ number_format($Loan->billing_for_the_month,2) }}</td>
                <td>{{ number_format($Loan->balance_if_updated,2) }}</td>
                <td>{{ number_format($Loan->total_overdue,2) }}</td>
                <td>{{ number_format($Loan->months_overdue,2) }}</td>

                @foreach($Loan->receivables as $receivable)
                    <td>{{ number_format($receivable,2) }}</td>
                @endforeach
                <td>{{ number_format($Loan->total_receivables,2) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>{{ number_format($Loans->sum('pn_amount'),2) }}</th>
            <th>{{ number_format($Loans->sum('outstanding_balance'),2) }}</th>
            <th>{{ number_format($Loans->sum('outstanding_interest'),2) }}</th>
            <th>{{ number_format($Loans->sum('outstanding_rebate'),2) }}</th>
            <th>{{ number_format($Loans->sum('billing_for_the_month'),2) }}</th>
            <th>{{ number_format($Loans->sum('balance_if_updated'),2) }}</th>
            <th>{{ number_format($Loans->sum('total_overdue'),2) }}</th>
            <th></th>
            @foreach($total_receivables as $total_receivable)
                <th>{{ number_format($total_receivable,2) }}</th>
            @endforeach
            <th>{{ number_format(array_sum($total_receivables),2) }}</th>
        </tr>
        </tfoot>
    </table>
@endsection