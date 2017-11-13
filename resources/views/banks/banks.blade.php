@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search',$search_data)

        <div class="col-md-12">
            @if( isset($bank) )
            {!! Form::model($bank,[
            'url' => "/banks/{$bank->id}",
            'class' => 'form-horizontal',
            'method' => 'put'
            ]) !!}
            @else
                {!! Form::open([
                'url' => "/banks",
                'class' => 'form-horizontal'
                ]) !!}
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Banks Masterfile</div>
                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('bank_desc','Bank', [
                            'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('bank_desc', null, [
                                'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('chart_of_account_id','Account', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::select('chart_of_account_id',$chart_of_accounts ,null, [
                            'class' => 'form-control',
                            'placeholder' => 'Select Account'
                            ]) !!}
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            {!! Form::submit('Save', [
                            'class' => 'btn btn-primary'
                            ]) !!}

                            @if( isset( $bank ) )
                                <input type="button" id="delete_btn" class="btn btn-danger" value="Delete">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

<script>
    $('#delete_btn').click(function(){
        $('input[name="_method"]').val("DELETE");
        $('form').submit();
    });
</script>
@endsection
