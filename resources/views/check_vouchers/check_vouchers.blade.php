@extends('layouts.app')

@section('content')

    <style>
        .indent-1 {
            padding-left: 40px;
        }

        .indent-2 {
            padding-left: 80px;
        }
    </style>
    <div class="container-fluid" id="app">
        <div class="row">

            @include('partials.search',$search_data)

            <div class="col-md-12">
                @if( isset($check_voucher) )
                    {!! Form::model($check_voucher,[
                    'url' => "/check-vouchers/{$check_voucher->id}",
                    'class' => 'form-horizontal',
                    'method' => 'put'
                    ]) !!}
                @else
                    {!! Form::open([
                    'url' => "/check-vouchers",
                    'class' => 'form-horizontal'
                    ]) !!}
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">CHECK VOUCHER</div>
                    <div class="panel-body">
                        @if( isset($check_voucher) )
                        <div class="form-group">
                            {!! Form::label('id','CV Ref', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9">
                                {!! Form::text('id', null, [
                                'class' => 'form-control',
                                'readonly' => true,
                                'v-model' => 'check_voucher_id'
                                ]) !!}
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            {!! Form::label('customer_name','Customer Name', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9">
                                {!! Form::text('customer_name', null, [
                                'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('date','Date', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9">
                                {!! Form::date('date', null, [
                                'class' => 'form-control datepicker',
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('cv_no','CV #', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9">
                                {!! Form::text('cv_no', null, [
                                'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>


                            <div class="form-group">
                            {!! Form::label('bank_id','Bank', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9">
                                {!! Form::select('bank_id', $bank_accounts, null, [
                                'placeholder' => 'Select Bank Account',
                                'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('check_no','Check #', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9">
                                {!! Form::text('check_no', null, [
                                'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('amount','Amount', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9">
                                {!! Form::text('amount', null, [
                                'class' => 'form-control',
                                'v-model' => 'amount',
                                'readonly' => true
                                ]) !!}
                            </div>
                        </div>

                            <div class="form-group">
                                {!! Form::label('explanation','Explanation', [
                                'class' => 'col-sm-3 control-label'
                                ]) !!}

                                <div class="col-sm-9">
                                    {!! Form::textarea('explanation', null, [
                                    'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>

                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::submit('Save', [
                                'class' => 'btn btn-primary'
                                ]) !!}

                                @if( isset( $check_voucher ) )
                                    <input type="button" id="delete_btn" class="btn btn-danger delete_btn" value="Delete">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Check Voucher Details
                    </div>
                    <div class="panel-body">


                        <!-- OTHER ADDITIONS -->
                        <div class="form-group">
                            <div class="col-sm-3">
                                ACCOUNT CODE
                            </div>
                            <div class="col-sm-4 text-left">ACCOUNT</div>
                            <div class="col-sm-2 text-right">DEBIT</div>
                            <div class="col-sm-2 text-right">CREDIT</div>
                            <div class="col-sm-1">&nbsp;</div>

                            <div class="col-sm-3">
                                {!! Form::text(null,null,[
                                'id' => 'account_code',
                                'class' => 'form-control',
                                'v-model' => 'detail.account_code',
                                '@keydown.enter' => 'checkAccountCode'
                                ]) !!}
                            </div>
                            <div class="col-sm-4">
                                {!! Form::text(null, null,[
                                'class' => 'form-control',
                                'v-model' => 'detail.account_desc',
                                'readonly' => 'readonly'
                                ]) !!}
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="debit" class="form-control text-right focus-next" v-model="detail.debit">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="credit" class="form-control text-right focus-next" v-model="detail.credit">
                            </div>

                            <div class="col-sm-1">
                                <input type="button" value="Add" class="btn btn-default form-control"  @click="addDetail">
                            </div>
                        </div>


                        <div class="form-group" v-for="check_voucher_detail in check_voucher_details">
                            <div class="col-sm-1 text-right form-control-static" >
                                <span class="glyphicon glyphicon-trash" style="cursor: pointer;" @click="removeDetail(check_voucher_detail)"></span>
                            </div>
                            <div class="col-sm-3">
                                <input type="hidden" name="check_voucher_details[id][]" v-model="check_voucher_detail.id" >
                                <input type="hidden" name="check_voucher_details[chart_of_account_id][]" v-model="check_voucher_detail.chart_of_account_id" >
                                @{{ check_voucher_detail.account_code }}
                            </div>
                            <div class="col-sm-3">
                                @{{ check_voucher_detail.account_desc }}
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="check_voucher_details[debit][]" class="form-control text-right" v-model="check_voucher_detail.debit">
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="check_voucher_details[credit][]" class="form-control text-right" v-model="check_voucher_detail.credit">
                            </div>

                        </div>

                        <!-- END OTHER ADDITIONS -->

                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::submit('Save', [
                                'class' => 'btn btn-primary'
                                ]) !!}

                                @if( isset( $check_voucher ) )
                                    <input type="button" id="delete_btn" class="btn btn-danger delete_btn" value="Delete">
                                    <a href="{{ url("/check-vouchers/{$check_voucher->id}/print-cash-voucher") }}" class="btn btn-default" target="_blank">Print Cash Voucher</a>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    @include('footer')
    <script>
        //var t = moment().format('MMMM Do YYYY, h:mm:ss a');
        //alert(t);

        $('.delete_btn').click(function () {
            $('input[name="_method"]').val("DELETE");
            $('form').submit();
        });

        $('.focus-next').keydown(function(e){
            if (e.which == 13)  {
                e.preventDefault();
                if ( $(this).parent().next().find("input.focus-next").length <= 0 ) {
                    console.log("here");
                    $(this).parent().next().next().find("input.focus-next").focus();
                } else {
                    $(this).parent().next().find("input.focus-next").focus();
                }

            }
        });
    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.5.3/numeral.min.js"></script>
    <script src="/js/check_vouchers.js"></script>
@endsection
