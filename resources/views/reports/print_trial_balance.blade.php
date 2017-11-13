@extends('layouts.printable_reports')
@section('content')
    <div class="text-center" style="font-size:14px; font-weight: bold;">
        Trial Balance Report <br/>
        {{ \Carbon\Carbon::parse($from_date)->toFormattedDateString() }} - {{ \Carbon\Carbon::parse($to_date)->toFormattedDateString() }}
    </div>
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th colspan="2" class="text-center">BEGINNING BALANCE</th>
                <th colspan="2" class="text-center">THIS MONTH</th>
                <th colspan="2" class="text-center">BALANCE</th>
            </tr>
            <tr>
                <th>ACCOUNT CODE</th>
                <th>ACCOUNT</th>
                <th class="text-right">DEBIT</th>
                <th class="text-right">CREDIT</th>
                <th class="text-right">DEBIT</th>
                <th class="text-right">CREDIT</th>

                <th class="text-right">BALANCE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($Accounts as $Account)
                <tr>
                    <td>{{ $Account->account_code }}</td>
                    <td style="white-space: nowrap;">{{ $Account->account_desc }}</td>
                    <td class="text-right">{{ number_format($Account->beg_bal->debit,2) }}</td>
                    <td class="text-right">{{ number_format($Account->beg_bal->credit,2) }}</td>

                    <td class="text-right">{{ number_format($Account->current->debit,2) }}</td>
                    <td class="text-right">{{ number_format($Account->current->credit,2) }}</td>


                    <td class="text-right">{{ number_format($Account->balance,2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th class="text-right">{{ number_format($Summary->beg_bal->debit,2) }}</th>
            <th class="text-right">{{ number_format($Summary->beg_bal->credit,2) }}</th>
            <th class="text-right">{{ number_format($Summary->current->debit,2) }}</th>
            <th class="text-right">{{ number_format($Summary->current->credit,2) }}</th>

            <th class="text-right">{{ number_format($Summary->balance,2) }}</th>
        </tr>
        </tfoot>
    </table>
@endsection