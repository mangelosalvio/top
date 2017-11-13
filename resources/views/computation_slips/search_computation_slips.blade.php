@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search_loans', $search_data)

        <div class="col-md-12">
            {{ $loans->appends([
                'search_id' => request()->get('search_id'),
                'search_customer_name' => request()->get('search_customer_name'),
                'search_date_purchased_from_date' => request()->get('search_date_purchased_from_date'),
                'search_date_purchased_to_date' => request()->get('search_date_purchased_to_date'),
                'search_check_date_from_date' => request()->get('search_check_date_from_date'),
                'search_check_date_to_date' => request()->get('search_check_date_to_date'),
            ])->links() }}
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    COMPUTATION SLIPS
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;"></th>
                            <th>DATE</th>
                            <th>CUSTOMER</th>
                            <th>AMOUNT</th>
                            <th>TRANS TYPE</th>
                            <th>DATE PURCHASED</th>
                            <th>CHECK DATE</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($loans as $i => $loan)
                            <tr>
                                <td><a href="{{ url("/$route/{$loan->id}/edit") }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                                <td>{{ $loan->date }}</td>
                                <td>{{ $loan->customer->name }}</td>
                                <td>{{ number_format($loan->amount,2) }}</td>
                                <td>{{ $loan->transType->label }}</td>
                                <td>{{ (isset( $loan->date_purchased )) ?  \Carbon\Carbon::parse($loan->date_purchased)->format("m/d/Y") : '' }}</td>
                                <td>{{ (isset( $loan->check_date )) ?  \Carbon\Carbon::parse($loan->check_date)->format("m/d/Y") : '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            {{ $loans->appends([
                'search_id' => request()->get('search_id'),
                'search_customer_name' => request()->get('search_customer_name'),
                'search_date_purchased_from_date' => request()->get('search_date_purchased_from_date'),
                'search_date_purchased_to_date' => request()->get('search_date_purchased_to_date'),
                'search_check_date_from_date' => request()->get('search_check_date_from_date'),
                'search_check_date_to_date' => request()->get('search_check_date_to_date'),
            ])->links() }}
        </div>
    </div>


</div>
@endsection
