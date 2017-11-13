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
                    COLLATERALS
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;"></th>
                            <th>CUSTOMER</th>
                            <th>CLASS</th>
                            <th>MAKE</th>
                            <th>TYPE</th>
                            <th>MODEL</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($collaterals as $i => $collateral)
                            <tr>
                                <td><a href="{{ url("/collaterals/{$collateral->id}/edit") }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                                <td>{{ $collateral->customer->name or '' }}</td>
                                <td>{{ $collateral->collateralClass->class_desc or ''}}</td>
                                <td>{{ $collateral->make }}</td>
                                <td>{{ $collateral->type }}</td>
                                <td>{{ $collateral->model }}</td>
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
