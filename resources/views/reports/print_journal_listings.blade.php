@extends('layouts.printable_reports')
@section('content')
    <style>
        tr.red th{
            background-color: red !important;
            color: #fff;
        }
    </style>
    <div class="text-center" style="font-size:14px; font-weight: bold;">
        JOURNAL LISTINGS <br/>
        {{ \Carbon\Carbon::parse($from_date)->toFormattedDateString() }} - {{ \Carbon\Carbon::parse($to_date)->toFormattedDateString() }}
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>DATE</th>
                <th>REFERENCE</th>
                <th>PARTICULARS</th>
                <th>ACCOUNT</th>
                <th class="text-right">DEBIT</th>
                <th class="text-right">CREDIT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($Ledgers as $Ledger)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($Ledger->date)->toFormattedDateString() }}</td>
                    <td>GL#{{ $Ledger->id }} {{ $Ledger->reference or '' }}</td>
                    <td>{{ $Ledger->particulars }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach($Ledger->chartOfAccounts as $Account)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>{{ $Account->account_desc }}</td>
                        <td class="text-right">{{ number_format($Account->pivot->debit,2) }}</td>
                        <td class="text-right">{{ number_format($Account->pivot->credit,2) }}</td>
                    </tr>
                @endforeach
                <tr @if( $Ledger->chartOfAccounts()->sum('debit') != $Ledger->chartOfAccounts()->sum('credit') ) class="red" @endif >
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th class="text-right">{{ number_format($Ledger->chartOfAccounts()->sum('debit'),2) }}</th>
                    <th class="text-right">{{ number_format($Ledger->chartOfAccounts()->sum('credit'),2) }}</th>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection