@extends('layouts.printable_reports')
@section('content')
    <div class="text-center" style="font-weight: bold;">
        {{ config('app.name', 'Company Name') }} <br/>
        GENERAL LEDGER ACTIVITY REPORT <br/>
        From {{ \Carbon\Carbon::parse($from_date)->toFormattedDateString() }} - {{ \Carbon\Carbon::parse($to_date)->toFormattedDateString() }}
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>ACCOUNT CODE</th>
                <th>ACCOUNT</th>
                <th>REF</th>
                <th>DATE</th>
                <th>EXPLANATION</th>
                <th class="text-right">DEBIT</th>
                <th class="text-right">CREDIT</th>
                <th class="text-right">BALANCE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $Account->account_code }}</td>
                <td>{{ $Account->account_desc }}</td>
                <td></td>
                <td></td>
                <td></td>

                <td class="text-right">{{ $Account->beg_balance_debit != 0 ? number_format($Account->beg_balance_debit,2) : '' }}</td>
                <td class="text-right">{{ $Account->beg_balance_credit != 0 ? number_format($Account->beg_balance_credit,2) : '' }}</td>
            </tr>

            @foreach( $AccountActivities as $AccountActivity )
                <tr>
                    <td></td>
                    <td></td>
                    <td>GL{{ str_pad($AccountActivity->id,7,0,STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($AccountActivity->date)->format("m/d/Y") }}</td>
                    <td>{{ $AccountActivity->particulars }}</td>
                    <td class="text-right">{{ $AccountActivity->debit != 0 ? number_format($AccountActivity->debit,2) : '' }}</td>
                    <td class="text-right">{{ $AccountActivity->credit != 0 ? number_format($AccountActivity->credit,2) : '' }}</td>
                    <td class="text-right">{{ number_format($AccountActivity->balance,2) }}</td>
                </tr>
            @endforeach
        </tbody>


    </table>
@endsection