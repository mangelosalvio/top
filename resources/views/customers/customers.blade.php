@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        @include('partials.search',$search_data)

        <div class="col-md-12">
            @if( isset($customer) )
            {!! Form::model($customer,[
            'url' => "/customers/{$customer->id}",
            'class' => 'form-horizontal',
            'method' => 'put'
            ]) !!}
            @else
                {!! Form::open([
                'url' => '/customers',
                'class' => 'form-horizontal'
                ]) !!}
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Customer Masterfile</div>
                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('last_name','Last Name', [
                            'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('last_name', null, [
                                'class' => 'form-control'
                            ]) !!}
                        </div>

                        @if( isset( $customer ) )
                            {!! Form::label('customer_code','Customer Code', [
                                'class' => 'col-sm-2 control-label'
                            ]) !!}
                            <div class="col-sm-4">
                                {!! Form::text(null, $customer->id, [
                                'class' => 'form-control',
                                'readonly' => 'true'
                                ]) !!}
                            </div>
                        @else
                        @endif
                    </div>

                    <div class="form-group">
                        {!! Form::label('first_name','First Name', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('first_name', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        @if( isset( $customer ) )
                            {!! Form::label('date_entered','Date Entered', [
                            'class' => 'col-sm-2 control-label'
                            ]) !!}
                            <div class="col-sm-4">
                                {!! Form::text('created_at', null, [
                                'class' => 'form-control',
                                'readonly' => 'true'
                                ]) !!}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        {!! Form::label('middle_name','Middle Name', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('middle_name', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('marital_status','Marital Status', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('marital_status', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('spouse','Spouse', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('spouse', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <hr/>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('current_address','Current Address', [
                                'class' => 'col-sm-4 control-label'
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('current_address', null, [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('previous_address','Previous Address', [
                                'class' => 'col-sm-4 control-label'
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('previous_address', null, [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('source_of_income','Source of Income', [
                                'class' => 'col-sm-4 control-label'
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('source_of_income', null, [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('present_address_code','Present Address Code', [
                                'class' => 'col-sm-4 control-label'
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('present_address_code', null, [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('mobile_number','Phone/Mobile Number', [
                                'class' => 'col-sm-4 control-label'
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('mobile_number', null, [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('gender','Gender', [
                                'class' => 'col-sm-4 control-label'
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('gender', null, [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('no_of_dependents','No of Dependents', [
                                'class' => 'col-sm-4 control-label'
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('no_of_dependents', null, [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('years_of_stay','No of Years of Stay', [
                                'class' => 'col-sm-4 control-label'
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('years_of_stay', null, [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('age','Age', [
                                'class' => 'col-sm-4 control-label'
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('age', null, [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('references','References', [
                                'class' => 'col-sm-4 control-label'
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('references', null, [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('res_cert_no','Res. Cert. #', [
                                'class' => 'col-sm-4 control-label'
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('res_cert_no', null, [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('res_cert_date','Res. Cert. Date', [
                                'class' => 'col-sm-4 control-label'
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('res_cert_date', date("Y-m-d"), [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('res_cert_place','Res. Cert. Place', [
                                'class' => 'col-sm-4 control-label'
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('res_cert_place', null, [
                                    'class' => 'form-control'
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            {!! Form::submit('Save', [
                            'class' => 'btn btn-primary'
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Properties
                </div>
                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('prop_real','Real', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::text('prop_real', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('prop_appliance','Appliance', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::text('prop_appliance', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('prop_chattel','Chattel', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::text('prop_chattel', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('prop_deposit','Deposit', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::text('prop_deposit', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9 col-md-offset-3">
                            {!! Form::submit('Save', [
                            'class' => 'btn btn-primary'
                            ]) !!}
                        </div>
                    </div>

                </div>

            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Credit Dealings
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        {!! Form::label('credit_dealings_1','1 - ', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::text('credit_dealings_1', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('credit_dealings_2','2 - ', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::text('credit_dealings_2', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('credit_dealings_3','3 - ', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::text('credit_dealings_3', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('credit_dealings_4','4 - ', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::text('credit_dealings_4', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>
                </div>

            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Others
                </div>

                <div class="panel-body">
                    <div class="form-group">
                        {!! Form::label('ica_court_files','ICA Court Files', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::text('ica_court_files', 'Negative', [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('general_information','General Information', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::textarea('general_information', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('residense', 'Residence', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::textarea('residense', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9 col-md-offset-3">
                            {!! Form::submit('Save', [
                            'class' => 'btn btn-primary'
                            ]) !!}
                        </div>
                    </div>

                </div>
            </div>
            @if( isset($customer) )
                <div class="row">
                    <div class="col-md-12" style="margin-bottom:18px;">
                        {!! Form::button('Delete', [
                        'class' => 'btn btn-danger form-control',
                        'id' => 'delete_btn'
                        ]) !!}
                    </div>
                </div>
            @endif
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script>
    $('#delete_btn').click(function(){
        if ( confirm("Would you like to confirm ?") ) {
            $('input[name="_method"]').val("DELETE");
            $('form').submit();
        }
    });
</script>
@endsection
