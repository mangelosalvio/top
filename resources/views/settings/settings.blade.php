@extends('layouts.app')
@section('content')
<div class="container-fluid" id="app">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">Loans</div>
            <div class="panel-body">
                {!! Form::open([ "url" => "settings", "class" => "form-horizontal"]) !!}
                @if( count($settings) )
                    @foreach($settings as $index => $setting)
                        <div class="form-group">
                            {!! Form::label("setting_value[$index]", $setting->setting_desc,
                            [
                            "class" => "control-label col-md-3"
                            ]) !!}

                            {!! Form::hidden("setting_id[$index]",$setting->id) !!}

                            <div class="col-md-9" >
                                {!! Form::select("setting_value[$index]", $chart_of_accounts,$setting->setting_value,
                                [
                                "id" => "setting_value[$index]",
                                "class" => "form-control"
                                ]) !!}

                            </div>
                        </div>
                    @endforeach
                @endif
                <div class="form-group">
                    <div class="col-md-9 col-md-offset-3">
                        {!! Form::submit('Save',['class' => 'btn btn-primary']) !!}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection