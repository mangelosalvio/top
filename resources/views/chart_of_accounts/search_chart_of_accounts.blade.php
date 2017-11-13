@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search', $search_data)

        <div class="col-md-12">
            {{ $chart_of_accounts->links() }}
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    CHART OF ACCOUNTS
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;"></th>
                            <th>ACCOUNT CODE</th>
                            <th>ACCOUNT DESC</th>
                            <th>ACCOUNT TYPE</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($chart_of_accounts as $i => $chart_of_account)
                            <tr>
                                <td><a href="{{ url("/$route_url/{$chart_of_account->id}/edit") }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                                <td>{{ $chart_of_account->account_code }}</td>
                                <td>{{ $chart_of_account->account_desc }}</td>
                                <td>{{ $chart_of_account->accountType->account_type_desc }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            {{ $chart_of_accounts->links() }}
        </div>
    </div>


</div>
@endsection
