@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search',$search_data)

        <div class="col-md-12">
            @if( isset($chart_of_account) )
            {!! Form::model($chart_of_account,[
            'url' => "/$route_url/{$chart_of_account->id}",
            'class' => 'form-horizontal',
            'method' => 'put'
            ]) !!}
            @else
                {!! Form::open([
                'url' => "/$route_url",
                'class' => 'form-horizontal'
                ]) !!}
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">CHART OF ACCOUNTS</div>
                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('account_code','Account Code', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('account_code', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('account_desc','Account Description', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('account_desc', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('account_type_id','Account Type', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::select('account_type_id',$account_types, null, [
                            'class' => 'form-control',
                            'placeholder' => 'Select Account Type'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <div class="checkbox">
                                <label>
                                    {!! Form::hidden('is_bank_account',0) !!}
                                    {!! Form::checkbox('is_bank_account') !!}
                                    Is Bank Account
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            {!! Form::submit('Save', [
                            'class' => 'btn btn-primary'
                            ]) !!}

                            @if( isset($chart_of_account) )
                                <input type="button" class="btn btn-danger" value="Delete">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

@include('partials.delete_script')
@endsection
