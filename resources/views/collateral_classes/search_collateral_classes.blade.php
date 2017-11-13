@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search', $search_data)

        <div class="col-md-12">
            {{ $collateral_classes->links() }}
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    COLLATERAL CLASSES
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;"></th>
                            <th>CLASS CODE</th>
                            <th>CLASS DESC</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($collateral_classes as $i => $collateral_class)
                            <tr>
                                <td><a href="{{ url("/collateral-classes/{$collateral_class->id}/edit") }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                                <td>{{ $collateral_class->class_code }}</td>
                                <td>{{ $collateral_class->class_desc }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            {{ $collateral_classes->links() }}
        </div>
    </div>


</div>
@endsection
