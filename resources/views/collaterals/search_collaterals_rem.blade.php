@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search', $search_data)

        <div class="col-md-12">
            {{ $collaterals->links() }}
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    CUSTOMER'S REM COLLATERAL
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;"></th>
                            <th>CUSTOMER</th>
                            <th>CLASS</th>
                            <th>TITLE NO</th>
                            <th>LOT NO</th>
                            <th>AREA</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($collaterals as $i => $collateral)
                            <tr>
                                <td><a href="{{ url("/collaterals-rem/{$collateral->id}/edit") }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                                <td>{{ $collateral->customer->name }}</td>
                                <td>{{ $collateral->collateralClass->class_desc}}</td>
                                <td>{{ $collateral->title_no }}</td>
                                <td>{{ $collateral->lot_no }}</td>
                                <td>{{ $collateral->area }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            {{ $collaterals->links() }}
        </div>
    </div>


</div>
@endsection
