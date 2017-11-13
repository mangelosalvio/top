@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search', $search_data)

        <div class="col-md-12">
            {{ $customers->links() }}
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    CUSTOMERS
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;"></th>
                            <th>CUSTOMER CODE</th>
                            <th>LAST NAME</th>
                            <th>FIRST NAME</th>
                            <th>MIDDLE NAME</th>
                            <th>MOBILE</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $i => $customer)
                            <tr>
                                <td><a href="{{ url("/customers/{$customer->id}/edit") }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->last_name }}</td>
                                <td>{{ $customer->first_name }}</td>
                                <td>{{ $customer->middle_name }}</td>
                                <td>{{ $customer->mobile_no }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            {{ $customers->links() }}
        </div>
    </div>


</div>
@endsection
