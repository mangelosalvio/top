@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search',$search_data)

        <div class="col-md-12">
            @if( isset($collateral) )
            {!! Form::model($collateral,[
            'url' => "/collaterals/{$collateral->id}",
            'class' => 'form-horizontal',
            'method' => 'put'
            ]) !!}
            @else
                {!! Form::open([
                'url' => "/collaterals",
                'class' => 'form-horizontal'
                ]) !!}
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Collaterals Masterfile</div>
                <div class="panel-body">
                    <div class="form-group">
                        {!! Form::label('date','Date', [
                        'class' => 'col-sm-2 col-sm-offset-6 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::date('date', null, [
                            'class' => 'form-control datepicker'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('customer_id','Customer', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::select('customer_id', $customers, null, [
                            'placeholder' => 'Select Customer',
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('collateral_class_id','Collateral Class', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::select('collateral_class_id', $collateral_classes, null, [
                            'placeholder' => 'Select Collateral Class',
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('make','Make', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('make', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('registration','Registration', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('registration', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('type','Type', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('type', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('mv_file_no','MV File No', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('mv_file_no', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('model','Model', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('model', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('assembled_by','Assembled by', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('assembled_by', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('motor','Motor', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('motor', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('owner','Owner', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('owner', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('serial','Serial', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('serial', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('market_value','Market Value', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('market_value', isset($collateral) ? $collateral->market_value : 0, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('plate','Plate', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('plate', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('loan_value','Loan Value', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('loan_value', isset($collateral) ? $collateral->loan_value : 0, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('odometer','Odometer', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('odometer', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                    </div>

                    <div class="form-group">
                        {!! Form::label('route','Route', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('route', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('od_coverage','OD Coverage', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('od_coverage', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('insurance','Insurance', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('insurance', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('expiry_date','Expiry Date', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('expiry_date', null, [
                            'class' => 'form-control date'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('operator','Operator', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('operator', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('lto_agency','LTO Agency', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('lto_agency', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <hr/>

                    <div class="form-group">
                        {!! Form::label('paint_condition','Paint', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('paint_condition', isset($collateral) ? $collateral->paint_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('paint_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('tire_condition','Tire', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('tire_condition', isset($collateral) ? $collateral->tire_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('tire_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('body_condition','Body', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('body_condition', isset($collateral) ? $collateral->body_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('body_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('chrome_condition','Chrome', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('chrome_condition', isset($collateral) ? $collateral->chrome_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('chrome_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('upholstery_condition','Upholstery', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('upholstery_condition', isset($collateral) ? $collateral->upholstery_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('upholstery_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('engine_condition','Engine', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('engine_condition', isset($collateral) ? $collateral->engine_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('engine_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('transmission_condition','Transmission', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('transmission_condition', isset($collateral) ? $collateral->transmission_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('transmission_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('rear_axle_condition','Rear Axle', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('rear_axle_condition', isset($collateral) ? $collateral->rear_axle_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('rear_axle_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('clutch_condition','Clutch', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('clutch_condition', isset($collateral) ? $collateral->clutch_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('clutch_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('steering_condition','Steering', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('steering_condition', isset($collateral) ? $collateral->steering_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('steering_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('brakes_condition','Brakes', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('brakes_condition', isset($collateral) ? $collateral->brakes_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('brakes_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('accessories_condition','Accessories', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('accessories_condition', isset($collateral) ? $collateral->accessories_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('accessories_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('glass_condition','Glass', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('glass_condition', isset($collateral) ? $collateral->glass_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('glass_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('panel_instru_condition','Panel Instru', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-1">
                            {!! Form::text('panel_instru_condition', isset($collateral) ? $collateral->panel_instru_condition : 2, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        <div class="col-sm-9">
                            {!! Form::text('panel_instru_remarks', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <hr/>

                    <div class="form-group">
                        {!! Form::label('additional_collaterals','Additional Collaterals', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}

                        <div class="col-md-10">
                            {!! Form::textarea('additional_collaterals', null, [
                            'class' => 'form-control',
                            'rows' => '5'
                            ]) !!}
                        </div>
                    </div>

                    <hr/>

                    <div class="form-group">
                        {!! Form::label('appraised_by','Appraised By', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-10">
                            {!! Form::text('appraised_by', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('appraised_date','Appraised Date', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-10">
                            {!! Form::text('appraised_date', null, [
                            'class' => 'form-control date'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('place','Place', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-10">
                            {!! Form::text('place', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            {!! Form::submit('Save', [
                            'class' => 'btn btn-primary'
                            ]) !!}

                            @if( isset( $collateral ) )
                                <input type="button" id="delete_btn" class="btn btn-danger" value="Delete">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if( isset( $collateral ) && isset( $collateral->loan ) )
                <div class="panel">
                    <div class="panel-body">
                        <a href="{{ url("/collaterals/{$collateral->id}/print-appraisal-report") }}" class="btn btn-default" target="_blank">Print Appraisal Report</a>
                    </div>
                </div>
            @endif




            {!! Form::close() !!}
        </div>
    </div>
</div>

<script>
    //var t = moment().format('MMMM Do YYYY, h:mm:ss a');
    //alert(t);

    $('#delete_btn').click(function(){
        if ( confirm("Would you like to confirm ?") ) {
            $('input[name="_method"]').val("DELETE");
            $('form').submit();
        }
    });
</script>
@endsection
