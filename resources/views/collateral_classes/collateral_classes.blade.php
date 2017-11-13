@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search',$search_data)

        <div class="col-md-12">
            @if( isset($collateral_class) )
            {!! Form::model($collateral_class,[
            'url' => "/collateral-classes/{$collateral_class->id}",
            'class' => 'form-horizontal',
            'method' => 'put'
            ]) !!}
            @else
                {!! Form::open([
                'url' => "/collateral-classes",
                'class' => 'form-horizontal'
                ]) !!}
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Collateral Classes Masterfile</div>
                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('class_code','Class Code', [
                            'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('class_code', null, [
                                'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('class_desc','Class Description', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('class_desc', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            {!! Form::submit('Save', [
                            'class' => 'btn btn-primary'
                            ]) !!}

                            @if( isset( $collateral_class ) )
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
