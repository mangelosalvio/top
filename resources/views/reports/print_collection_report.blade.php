@extends('layouts.printable_reports')
@section('content')
    <div class="text-center" style="font-size:14px; font-weight: bold;">
        Collection Report<br/>
        {{ \Carbon\Carbon::parse($from_date)->toFormattedDateString() }} - {{ \Carbon\Carbon::parse($to_date)->toFormattedDateString() }}
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>COL #</th>
                <th>LOAN #</th>
                <th>L/R CODE</th>
                <th>OR #</th>
                <th>OR DATE</th>
                <th>NAME</th>
                <th class="text-right">CASH AMOUNT</th>
                <th class="text-right">CHECK AMOUNT</th>
                <th class="text-right">TOTAL AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($Accounts as $i => $Account)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ str_pad($Account->id,7,0,STR_PAD_LEFT) }}</td>
                    <td>{{ str_pad($Account->loan_id,7,0,STR_PAD_LEFT) }}</td>
                    <td>{{ $Account->LrAccount->account_code }}</td>
                    <td>{{ $Account->or_no }}</td>
                    <td>{{ \Carbon\Carbon::parse($Account->or_date)->toFormattedDateString() }}</td>
                    <td>{{ $Account->customer->name }}</td>
                    <td class="text-right">{{ number_format($Account->cash_amount,2) }}</td>
                    <td class="text-right">{{ number_format($Account->check_amount,2) }}</td>
                    <td class="text-right">{{ number_format($Account->total_payment_amount,2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-right">{{ number_format($Accounts->sum('cash_amount'),2) }}</th>
                <th class="text-right">{{ number_format($Accounts->sum('check_amount'),2) }}</th>
                <th class="text-right">{{ number_format($Accounts->sum('total_payment_amount'),2) }}</th>
            </tr>
        </tfoot>
    </table>
@endsection