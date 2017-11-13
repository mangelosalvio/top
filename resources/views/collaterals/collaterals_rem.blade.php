@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search',$search_data)

        <div class="col-md-12">
            @if( isset($collateral) )
            {!! Form::model($collateral,[
            'url' => "/collaterals-rem/{$collateral->id}",
            'class' => 'form-horizontal',
            'method' => 'put'
            ]) !!}
            @else
                {!! Form::open([
                'url' => "/collaterals-rem",
                'class' => 'form-horizontal'
                ]) !!}
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">CUSTOMER'S REM COLLATERAL</div>
                <div class="panel-body">
                    <div class="form-group">
                        {!! Form::label('date','Date', [
                        'class' => 'col-sm-2 col-sm-offset-6 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('date', null, [
                            'class' => 'form-control date'
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
                        {!! Form::label('title_no','Titile No', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('title_no', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('appraised_date','Date Appraised', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('appraised_date', null, [
                            'class' => 'form-control date'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('lot_no','Lot No', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('lot_no', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('date_issued','Date Issued', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('date_issued', null, [
                            'class' => 'form-control date'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('area','Area', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('area', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('registered_owner','Registered Owner', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('registered_owner', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('operator','Operator', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}

                        <div class="col-md-10">
                            {!! Form::textarea('operator', null, [
                            'class' => 'form-control',
                            'rows' => '5'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('lot_location','Lot Location', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}

                        <div class="col-md-10">
                            {!! Form::textarea('lot_location', null, [
                            'class' => 'form-control',
                            'rows' => '5'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('building_description','Building Description', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-10">
                            {!! Form::text('building_description', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('location','Location', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('location', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('description','Description', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('description', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('building_type','Building Type', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('building_type', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group"></div>

                    <div class="form-group">
                        {!! Form::label('no_of_story','No. of Story', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('no_of_story', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('flooring','Flooring', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('flooring', null, [
                            'class' => 'form-control date'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('frame_formation','Frame Formation', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('frame_formation', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('door','Door', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('door', null, [
                            'class' => 'form-control date'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('walling','Walling', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('walling', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('windows','Windows', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('windows', null, [
                            'class' => 'form-control date'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('partitions','Partitions', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('partitions', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('toilet_and_bath','T&B', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('toilet_and_bath', null, [
                            'class' => 'form-control date'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('roofing','Roofing', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('roofing', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('floor_area','Floor Area', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('floor_area', null, [
                            'class' => 'form-control date'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('beams_and_trusses','Beams and Trusses', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('beams_and_trusses', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('maintenance','Maintenance', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('maintenance', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('ceilings','Ceilings', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('ceilings', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('year_constructed','Year Constructed', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('year_constructed', null, [
                            'class' => 'form-control date'
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('other_improvements','Other Improvements', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}

                        <div class="col-md-10">
                            {!! Form::textarea('other_improvements', null, [
                            'class' => 'form-control',
                            'rows' => '5'
                            ]) !!}
                        </div>
                    </div>

                    <hr/>

                    <div class="form-group">
                        <div class="col-sm-3 text-center">
                            PROPERTY VALUATIONS: <br/>
                            ITEMS
                        </div>
                        <div class="col-sm-3 col-sm-offset-3 text-center">
                            MARKET VALUE
                        </div>
                        <div class="col-sm-3 text-center">
                            APPRAISED VALUE
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('land','a. Land', [
                        'class' => 'col-sm-3'
                        ]) !!}
                        <div class="col-sm-offset-3 col-sm-3">
                            {!! Form::text('land_market_value', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>
                        <div class="col-sm-3">
                            {!! Form::text('land_appraised_value', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('building','b. Building', [
                        'class' => 'col-sm-3'
                        ]) !!}
                        <div class="col-sm-offset-3 col-sm-3">
                            {!! Form::text('building_market_value', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>
                        <div class="col-sm-3">
                            {!! Form::text('building_appraised_value', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('other_improvements','c. Other Improvements', [
                        'class' => 'col-sm-3'
                        ]) !!}
                        <div class="col-sm-offset-3 col-sm-3">
                            {!! Form::text('other_improvements_market_value', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>
                        <div class="col-sm-3">
                            {!! Form::text('other_improvements_appraised_value', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('total','Total >>>', [
                        'class' => 'col-sm-3'
                        ]) !!}
                        <div class="col-sm-offset-3 col-sm-3">
                            {!! Form::text('total_market_value', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>

                        <div class="col-sm-3">
                            {!! Form::text('total_appraised_value', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>
                    </div>

                    <hr/>

                    <div class="form-group">
                        <div class="col-sm-12">
                            BASIS OF VALUATION
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('bir_zonal_value','BIR Zonal Value', [
                        'class' => 'col-sm-offset-1 col-sm-4'
                        ]) !!}
                        <div class="col-sm-offset-4 col-sm-3">
                            {!! Form::text('bir_zonal_value', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('appraisers_association_value','Bankers/Appraisers Association Value', [
                        'class' => 'col-sm-offset-1 col-sm-4'
                        ]) !!}
                        <div class="col-sm-offset-4 col-sm-3">
                            {!! Form::text('appraisers_association_value', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('market_value_of_neighborhood','Market Value of Neighborhood', [
                        'class' => 'col-sm-offset-1 col-sm-4'
                        ]) !!}
                        <div class="col-sm-offset-4 col-sm-3">
                            {!! Form::text('market_value_of_neighborhood', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('reproduction_cost_of_building','Reproduciton Cost of Building', [
                        'class' => 'col-sm-offset-1 col-sm-4'
                        ]) !!}
                        <div class="col-sm-offset-4 col-sm-3">
                            {!! Form::text('reproduction_cost_of_building', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('assessed_value','Assesed Value', [
                        'class' => 'col-sm-offset-1 col-sm-4'
                        ]) !!}
                        <div class="col-sm-offset-4 col-sm-3">
                            {!! Form::text('assessed_value', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::submit('Save', [
                            'class' => 'btn btn-primary form-control'
                            ]) !!}
                        </div>
                    </div>

                    @if( isset( $collateral ) )
                    <div class="row" style="margin-top: 8px;">
                        <div class="col-sm-12">
                            <input type="button" id="delete_btn" class="btn btn-danger form-control" value="Delete">
                        </div>
                    </div>
                    @endif
                </div>
            </div>




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
