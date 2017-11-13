@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search', $search_data)

        <div class="col-md-12">
            {{ $check_vouchers->appends([
                'keyword' => request()->get('keyword')
            ])->links() }}
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    CHECK VOUCHER
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;"></th>
                            <th>DATE</th>
                            <th>CV #</th>
                            <th>CUSTOMER</th>
                            <th class="text-right">AMOUNT</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($check_vouchers as $i => $check_voucher)
                            <tr>
                                <td><a href="{{ url("/$route/{$check_voucher->id}/edit") }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                                <td>{{ $check_voucher->date }}</td>
                                <td>{{ $check_voucher->cv_no }}</td>
                                <td>{{ $check_voucher->customer_name }}</td>
                                <td class="text-right">{{ number_format($check_voucher->amount,2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            {{ $check_vouchers->appends([
                'keyword' => request()->get('keyword')
            ])->links() }}
        </div>
    </div>


</div>
@endsection
