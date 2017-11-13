@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search', $search_data)

        <div class="col-md-12">
            {{ $banks->links() }}
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    BANKS
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;"></th>
                            <th>BANK</th>
                            <th>ACCOUNT</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($banks as $i => $bank)
                            <tr>
                                <td><a href="{{ url("/{$route}/{$bank->id}/edit") }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                                <td>{{ $bank->bank_desc }}</td>
                                <td>{{ $bank->account->account_desc }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            {{ $banks->links() }}
        </div>
    </div>


</div>
@endsection
