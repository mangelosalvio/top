@extends('layouts.app')
@section('content')
<script type="text/javascript">
    function printIframe(id)
    {
        var iframe = document.frames ? document.frames[id] : document.getElementById(id);
        var ifWin = iframe.contentWindow || iframe;
        iframe.focus();
        ifWin.printPage();
        return false;
    }
</script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Daily Collection Report
                </div>
                <div class="panel-body">
                    {!! Form::open([
                        'url' => '/daily-collection-report',
                        'class' => 'form-horizontal'
                    ]) !!}
                    <div class="row">
                        <div class="form-group">
                            {!! Form::label('date','Date', [
                            'class' => 'col-sm-2 control-label'
                            ]) !!}

                            <div class="col-sm-3">
                                {!! Form::date('date', request('date'), [
                                'class' => 'form-control datepicker'
                                ]) !!}
                            </div>

                            <!-- {!! Form::label('prev_collection_date','Prev. Collection', [
                            'class' => 'col-sm-2 control-label'
                            ]) !!}

                            <div class="col-sm-3">
                                {!! Form::date('prev_collection_date', request('prev_collection_date'), [
                                'class' => 'form-control datepicker'
                                ]) !!}
                            </div> -->

                        </div>

                    </div>
                    <!--
                    <fieldset>
                        <legend>Balance Forward</legend>
                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('balance_forwarded','Balance Forwarded', [
                                'class' => 'col-sm-2 control-label'
                                ]) !!}

                                <div class="col-sm-2">
                                    {!! Form::text('balance_forwarded', request('balance_forwarded'), [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('penalty','Penalty', [
                                'class' => 'col-sm-2 control-label'
                                ]) !!}

                                <div class="col-sm-2">
                                    {!! Form::text('penalty', request('penalty'), [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>

                                {!! Form::label('rebate','Rebate', [
                                'class' => 'col-sm-2 control-label'
                                ]) !!}

                                <div class="col-sm-2">
                                    {!! Form::text('rebate', request('rebate'), [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>

                                {!! Form::label('principal','Principal', [
                                'class' => 'col-sm-2 control-label'
                                ]) !!}

                                <div class="col-sm-2">
                                    {!! Form::text('principal', request('principal'), [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>

                            </div>

                            <div class="form-group">
                                {!! Form::label('service_fees','Service Fees', [
                                'class' => 'col-sm-2 control-label'
                                ]) !!}

                                <div class="col-sm-2">
                                    {!! Form::text('service_fees', request('service_fees'), [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>

                                {!! Form::label('uii','Unearned Int. Inc.', [
                                'class' => 'col-sm-2 control-label'
                                ]) !!}

                                <div class="col-sm-2">
                                    {!! Form::text('uii', request('uii'), [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>

                                {!! Form::label('interest_fin_fees','Interest Fin Fees', [
                                'class' => 'col-sm-2 control-label'
                                ]) !!}

                                <div class="col-sm-2">
                                    {!! Form::text('interest_fin_fees', request('interest_fin_fees'), [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>

                            </div>

                        </div>
                    </fieldset>

                    -->
                    <div class="row">
                        <div class="col-sm-1 col-sm-offset-2">
                            {!! Form::submit('Generate',[
                            'class' => 'btn btn-default'
                            ]) !!}
                        </div>
                        @if ( request()->has(['date']) )
                            <div class="col-sm-1">
                                <input type="button" value="Print" onclick="printIframe('frame');" class="btn btn-default"/>
                            </div>
                        @endif
                    </div>

                    {!! Form::close() !!}

                    <hr/>


                    @if ( isset( $url ) )
                    <div class="col-sm-12">
                        <iframe id="frame" src="{{ $url }}" frameborder="0" style="width:100%; height:400px; overflow-y: auto;"></iframe>
                    </div>
                    @endif


                </div>
            </div>
        </div>

    </div>


</div>
@endsection
