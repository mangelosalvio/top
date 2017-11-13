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
                    AGING OF ACCOUNTS RECEIVABLES
                </div>
                <div class="panel-body">
                    {!! Form::open([
                        'url' => '/aging-of-accounts-receivables',
                        'class' => 'form-horizontal'
                    ]) !!}
                    <div class="form-group">
                        {!! Form::label('month','Month', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}

                        <div class="col-sm-3">
                            {!! Form::selectMonth('month', request('month'), [
                                'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-2">
                            {!! Form::text('year', request('year'), [
                            'class' => 'form-control',
                            'placeholder' => 'year'
                            ]) !!}
                        </div>

                        <div class="col-sm-1">
                            {!! Form::submit('Generate',[
                                'class' => 'btn btn-default'
                            ]) !!}
                        </div>
                        @if ( request()->has(['month', 'year']) )
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
