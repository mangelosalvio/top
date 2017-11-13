@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search', $search_data)

        <div class="col-md-12">
            {{ $collections->links() }}
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    COLLECTIONS
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;"></th>
                            <th>COLLECTION #</th>
                            <th>OR DATE</th>
                            <th>ACCOUNT CODE</th>
                            <th>CUSTOMER</th>
                            <th>OR NO</th>
                            <th class="text-right">TOTAL AMOUNT</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($collections as $i => $collection)
                            <tr>
                                <td><a href="{{ url("/$route/{$collection->id}/edit") }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                                <td>{{ str_pad($collection->id,7,0,STR_PAD_LEFT) }}</td>
                                <td>{{ Carbon\Carbon::parse($collection->or_date)->toFormattedDateString() }}</td>
                                <td>{{ $collection->account_code or '' }}</td>
                                <td>{{ $collection->loan->customer->name or $collection->received_from }}</td>
                                <td>{{ $collection->or_no }}</td>
                                <td class="text-right">{{ number_format($collection->total_payment_amount,2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            {{ $collections->links() }}
        </div>
    </div>


</div>
@endsection
