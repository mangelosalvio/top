@extends('layouts.printable_reports')
@section('content')
    <div class="text-center" style="font-size:14px; font-weight: bold;">
        Availment Report <br/>
        {{ \Carbon\Carbon::parse($from_date)->toFormattedDateString() }} - {{ \Carbon\Carbon::parse($to_date)->toFormattedDateString() }}
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>NAME</th>
                <th>LOAN #</th>
                <th>L/R ACCOUNT</th>
                <th>DATE GRANTED</th>
                <th class="text-right">AMOUNT</th>
                <th class="text-right">PN VALUE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($Accounts as $i => $Account)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $Account->customer->name }}</td>
                    <td>{{ str_pad($Account->id,7,0,STR_PAD_LEFT) }}</td>
                    <td>{{ $Account->LrAccount->account_code or '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($Account->date_purchased)->toFormattedDateString() }}</td>
                    <td class="text-right">{{ number_format($Account->amount,2) }}</td>
                    <td class="text-right">{{ number_format($Account->pn_amount,2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right">{{ number_format($Accounts->sum('amount'),2) }}</td>
                <td class="text-right">{{ number_format($Accounts->sum('pn_amount'),2) }}</td>
            </tr>
        </tfoot>
    </table>
@endsection