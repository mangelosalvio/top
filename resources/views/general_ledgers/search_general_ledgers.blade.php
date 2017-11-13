@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search', $search_data)

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Advanced Search
                </div>
                <div class="panel-body">
                    {!! Form::open([
                        'url' => '/general-ledgers',
                        'method' => 'GET',
                        'class' => 'form-horizontal'
                    ]) !!}
                    <div class="form-group">
                        {!! Form::label('search_id','GL #', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}

                        <div class="col-sm-4">
                            {!! Form::text('search_id', request()->get('search_id'), [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('search_date','Date (From - To)', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}

                        <div class="col-sm-2">
                            {!! Form::date('search_from_date', request()->get('search_from_date'), [
                                'class' => 'form-control datepicker'
                            ]) !!}
                        </div>
                        <div class="col-sm-2">
                            {!! Form::date('search_to_date', request()->get('search_to_date'), [
                                'class' => 'form-control datepicker'
                            ]) !!}
                        </div>

                    </div>

                    <div class="form-group">
                        {!! Form::label('search_journal_id','Journal', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}

                        <div class="col-sm-4">
                            {!! Form::select('search_journal_id', $journals ,request()->get('search_journal_id'), [
                            'class' => 'form-control',
                            ]) !!}
                        </div>

                        {!! Form::label('particulars','Particulars', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}

                        <div class="col-sm-4">
                            {!! Form::text('search_particulars', request()->get('search_particulars'), [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('search_reference','Reference', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}

                        <div class="col-sm-4">
                            {!! Form::text('search_reference',request()->get('search_reference'), [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2 col-sm-offset-2">
                            {!! Form::submit('Search',[
                                'class' => 'btn btn-default'
                            ]) !!}

                            {!! Form::reset('Reset',[
                            'class' => 'btn btn-warning'
                            ]) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>

        </div>

        <div class="col-md-12">
            {{ $general_ledgers->appends([
                'keyword' => request()->get('keyword'),
                'search_id' => request()->get('search_id'),
                'search_from_date' => request()->get('search_from_date'),
                'search_to_date' => request()->get('search_to_date'),
                'search_reference' => request()->get('search_reference'),
                'search_particulars' => request()->get('search_particulars'),
                'search_journal_id' => request()->get('search_journal_id'),
            ])->links() }}
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    GENERAL LEDGER
                </div>
                <div class="panel-body">
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="width: 1%;"></th>
                            <th>GL#</th>
                            <th>DATE</th>
                            <th>JOURNAL</th>
                            <th>PARTICULARS</th>
                            <th>REFERENCE</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($general_ledgers as $i => $general_ledger)
                            <tr>
                                <td><a href="{{ url("/$route/{$general_ledger->id}/edit") }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
                                <td>{{ str_pad($general_ledger->id,7,0,STR_PAD_LEFT) }}</td>
                                <td>{{ $general_ledger->date }}</td>
                                <td>{{ $general_ledger->journal->journal_desc }}</td>
                                <td>{{ $general_ledger->particulars }}</td>
                                <td>{{ $general_ledger->reference }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            {{ $general_ledgers->appends([
                'keyword' => request()->get('keyword'),
                'search_id' => request()->get('search_id'),
                'search_from_date' => request()->get('search_from_date'),
                'search_to_date' => request()->get('search_to_date'),
                'search_reference' => request()->get('search_reference'),
                'search_particulars' => request()->get('search_particulars'),
                'search_journal_id' => request()->get('search_journal_id'),
            ])->links() }}
        </div>
    </div>


</div>
@endsection
