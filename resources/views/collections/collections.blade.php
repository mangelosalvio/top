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
                @if( isset($collection) )
                    {!! Form::model($collection,[
                    'url' => "/{$route}/{$collection->id}",
                    'class' => 'form-horizontal',
                    'method' => 'put'
                    ]) !!}
                @else
                    {!! Form::open([
                    'url' => "/{$route}",
                    'class' => 'form-horizontal'
                    ]) !!}
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">COLLECTIONS</div>
                    <div class="panel-body">
                        @if( isset($collection) )
                            <div class="form-group">
                                {!! Form::label('id','COL #', [
                                'class' => 'col-sm-3 control-label'
                                ]) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('id', null, [
                                    'class' => 'form-control',
                                    'readonly' => true,
                                    'v-model' => 'collection_id'
                                    ]) !!}
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            {!! Form::label('account_code','Account Code', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}
                            {!! Form::hidden('loan_id', null, [
                                'v-model' => 'loan.id'
                            ]) !!}

                            <div class="col-sm-7">
                                {!! Form::text('account_code', null, [
                                'class' => 'form-control',
                                'v-model' => 'account_code',
                                '@keydown.enter' => 'getLoanFromAccountCode'
                                ]) !!}
                            </div>

                            <div class="col-sm-2">
                                <input type="button" @click="getLoanFromAccountCode" class="btn btn-default" value="Search" >
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('customer_name','Customer Name', [
                            'class' => 'col-sm-3 control-label',
                            ]) !!}

                            <div class="col-sm-9 form-control-static">
                                @{{ loan.customer.name }}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('cash_out','Cash Out', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9 form-control-static">
                                @{{ loan.cash_out }}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('uii','UII', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9 form-control-static">
                                @{{ loan.interest_amount }}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('rff','RFF', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9 form-control-static">
                                @{{ loan.rebate_amount }}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('promissory_note','Prom Note', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9 form-control-static">
                                @{{ loan.pn_amount }}
                            </div>
                        </div>

                        <hr/>

                        <div class="form-group">
                            {!! Form::label('first_due_term','1st due/term', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-3">
                                @{{ loan.first_due_term }}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('first_due_term','Current Balance', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-3">
                                {!! Form::text('current_balance',null,[
                                    'class' => 'form-control',
                                    'v-model' => 'loan.current_balance'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('uii_balance','UII Balance', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-3">
                                {!! Form::text('uii_balance',null,[
                                'class' => 'form-control',
                                'v-model' => 'loan.uii_balance'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('rff_balance','RFF Balance', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-3">
                                {!! Form::text('rff_balance',null,[
                                'class' => 'form-control',
                                'v-model' => 'loan.rff_balance'
                                ]) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            {!! Form::label('ar_balance','A/R Balance', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-3">
                                {!! Form::text('ar_balance',null,[
                                'class' => 'form-control',
                                'v-model' => 'loan.ar_balance'
                                ]) !!}
                            </div>
                        </div>

                        <hr/>

                        <div class="form-group">
                            <div class="col-sm-12">
                                PENALTY
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">&nbsp;</div>
                            <div class="col-sm-9">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::hidden('is_penalty_computed',0) !!}
                                        {!! Form::checkbox('is_penalty_computed', 1, null, [
                                            'v-model' => 'is_penalty_computed',
                                            '@change' => 'applyPenalty()'
                                        ]) !!}
                                        Compute Penalty ?
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">Last Transaction as Date</div>
                            <div class="col-sm-3">
                                {!! Form::date('last_transaction_date', null, [
                                    'class' => 'form-control datepicker',
                                    'v-model' => 'last_transaction_date',
                                    ':readonly' => '!is_penalty_computed'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">Penalty Rate</div>
                            <div class="col-sm-3">
                                {!! Form::text('penalty_rate', null, [
                                'class' => 'form-control',
                                'v-model' => 'penalty_rate',
                                ':readonly' => '!is_penalty_computed'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">Penalty as of Date</div>
                            <div class="col-sm-3">
                                {!! Form::date('penalty_as_of_date', null, [
                                'class' => 'form-control datepicker',
                                'v-model' => 'penalty_as_of_date',
                                ':readonly' => '!is_penalty_computed'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">Days Allowance</div>
                            <div class="col-sm-3">
                                {!! Form::text('days_allowance', null, [
                                'class' => 'form-control',
                                'v-model' => 'days_allowance',
                                ':readonly' => '!is_penalty_computed'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3 col-sm-offset-3">
                                {!! Form::submit('Calculate',[
                                    'class' => 'btn btn-default'
                                ]) !!}
                            </div>
                        </div>

                        <hr/>

                        @if( isset($collection) )

                            <div class="form-group">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>DUE DATE</th>
                                        <th class="text-right">DAYS DELAYED</th>
                                        <th class="text-right">ARREARS</th>
                                        <th class="text-right">PENALTY</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ( $collection->penalties as $Penalty )
                                            <tr>
                                                <td>{{ $Penalty->due_date }}</td>
                                                <td class="text-right">{{ $Penalty->days_delayed }}</td>
                                                <td class="text-right">{{ number_format($Penalty->arrears,2) }}</td>
                                                <td class="text-right">{{ number_format($Penalty->penalty,2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right">{{ number_format($collection->penalties()->sum('penalty'),2) }}</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                        @endif

                        <hr/>

                        <div class="form-group">
                            <div class="col-sm-3">Total Penalty</div>
                            <div class="col-sm-3">
                                {!! Form::text('total_penalty', null, [
                                'class' => 'form-control text-right',
                                'v-model' => 'total_penalty',
                                ':readonly' => '!is_penalty_computed'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">Penalty Disc Rate</div>
                            <div class="col-sm-3">
                                {!! Form::text('penalty_disc_rate', null, [
                                'class' => 'form-control text-right',
                                'v-model' => 'penalty_disc_rate',
                                ':readonly' => '!is_penalty_computed'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">Discount in Pesos</div>
                            <div class="col-sm-3">
                                {!! Form::text('penalty_disc_amount', null, [
                                'class' => 'form-control text-right',
                                'v-model' => 'penalty_disc_amount',
                                'readonly' => true
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">Net Penalty Due</div>
                            <div class="col-sm-3">
                                {!! Form::text('net_penalty_due', null, [
                                'class' => 'form-control text-right',
                                'v-model' => 'net_penalty_due',
                                'readonly' => true
                                ]) !!}
                            </div>
                        </div>



                        <hr/>

                        <div class="form-group">
                            {!! Form::label('principal_amount','Principal', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-3 col-sm-offset-3">
                                {!! Form::text('principal_amount', null, [
                                    'class' => 'form-control text-right',
                                    'v-model' => 'principal_amount',
                                    '@keydown.enter' => 'getLoanComputation'
                                ]) !!}
                            </div>
                            <div class="col-sm-2">
                                <input type="button" value="Calculate RFF/UI" class="btn btn-default" @click="getLoanComputation" >
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('rff','RFF', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-3">
                                {!! Form::text('rff_debit', null, [
                                'class' => 'form-control text-right',
                                'v-model' => 'rff_debit'
                                ]) !!}
                            </div>

                            <div class="col-sm-3">
                                {!! Form::text('rff_credit', null, [
                                'class' => 'form-control text-right',
                                'v-model' => 'rff_credit'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('uii','UII', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-3">
                                {!! Form::text('uii_debit', null, [
                                'class' => 'form-control text-right',
                                'v-model' => 'uii_debit'
                                ]) !!}
                            </div>

                            <div class="col-sm-3">
                                {!! Form::text('interest_income_credit', null, [
                                'class' => 'form-control text-right',
                                'v-model' => 'interest_income_credit'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('penalty','Penalty', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-3 col-sm-offset-3">
                                {!! Form::text('net_penalty_due', null, [
                                'class' => 'form-control text-right'
                                ]) !!}
                            </div>
                        </div>

                        <hr/>

                        <div class="row">
                            <div class="col-sm-12">
                                Less Other Accounts:
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                {!! Form::select(null, $chart_of_accounts, null, [
                                'class' => 'form-control focus-next',
                                'placeholder' => 'Select Account',
                                'v-model' => 'form_less_other_account.chart_of_account_id',
                                ]) !!}
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control text-right focus-next" v-model="form_less_other_account.amount">
                            </div>
                            <div class="col-sm-1">
                                <input type="button" value="Add" class="btn btn-default form-control"  @click="addLessOtherAccount">
                            </div>

                        </div>

                        <div class="form-group" v-for="less_other_account in less_other_accounts">
                            <div class="col-sm-2 text-right form-control-static" >
                                <span class="glyphicon glyphicon-trash" style="cursor: pointer;" @click="removeLessOtherAccount(less_other_account)"></span>
                            </div>
                            <div class="col-sm-4">
                                <input type="hidden" name="less_other_account[id][]" v-model="less_other_account.id" >
                                <select name="less_other_account[chart_of_account_id][]"  v-model="less_other_account.chart_of_account_id" class="form-control">
                                    <option v-for="account in chart_of_accounts" :value="account.id">@{{ account.label }}</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="less_other_account[amount][]" class="form-control text-left" v-model="less_other_account.amount">
                            </div>
                        </div>

                        <hr/>

                        <div class="row">
                            <div class="col-sm-12">
                                Add Other Accounts
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                {!! Form::select(null, $chart_of_accounts, null, [
                                'class' => 'form-control focus-next',
                                'placeholder' => 'Select Account',
                                'v-model' => 'form_add_other_account.chart_of_account_id',
                                ]) !!}
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control text-right focus-next" v-model="form_add_other_account.amount">
                            </div>
                            <div class="col-sm-1">
                                <input type="button" value="Add" class="btn btn-default form-control"  @click="addOtherAccount">
                            </div>
                        </div>

                        <div class="form-group" v-for="add_other_account in add_other_accounts">
                            <div class="col-sm-2 text-right form-control-static" >
                                <span class="glyphicon glyphicon-trash" style="cursor: pointer;" @click="removeAddOtherAccount(add_other_account)"></span>
                            </div>
                            <div class="col-sm-4">
                                <input type="hidden" name="add_other_account[id][]" v-model="add_other_account.id" >
                                <select name="add_other_account[chart_of_account_id][]"  v-model="add_other_account.chart_of_account_id" class="form-control">
                                    <option v-for="account in chart_of_accounts" :value="account.id">@{{ account.label }}</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="add_other_account[amount][]" class="form-control text-left" v-model="add_other_account.amount">
                            </div>

                        </div>

                        <hr/>

                        <div class="form-group">
                            <div class="col-sm-3">Total Amount Due</div>
                            <div class="col-sm-3">
                                {!! Form::text('total_amount_due', null, [
                                'class' => 'form-control',
                                'v-model' => 'total_amount_due',
                                'readonly' => true
                                ]) !!}
                            </div>
                        </div>


                        <hr/>

                        <div class="form-group">
                            <div class="col-sm-3">Recieved From</div>
                            <div class="col-sm-3">
                                {!! Form::text('received_from', null, [
                                'class' => 'form-control'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">OR Number</div>
                            <div class="col-sm-3">
                                {!! Form::text('or_no', null, [
                                'class' => 'form-control'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">OR Date</div>
                            <div class="col-sm-3">
                                {!! Form::date('or_date', null, [
                                'class' => 'form-control datepicker'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">Cash Amount</div>
                            <div class="col-sm-3">
                                {!! Form::text('cash_amount', null, [
                                'class' => 'form-control',
                                'v-model' => 'cash_amount'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">Check Amount</div>
                            <div class="col-sm-3">
                                {!! Form::text('check_amount', null, [
                                'class' => 'form-control',
                                'v-model' => 'check_amount'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">Total Payment</div>
                            <div class="col-sm-3">
                                {!! Form::text('total_payment_amount', null, [
                                'class' => 'form-control',
                                'v-model' => 'total_payment_amount'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">Bank Branch</div>
                            <div class="col-sm-3">
                                {!! Form::text('bank_branch', null, [
                                'class' => 'form-control'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">Check no</div>
                            <div class="col-sm-3">
                                {!! Form::text('check_no', null, [
                                'class' => 'form-control'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-3">Check Date</div>
                            <div class="col-sm-3">
                                {!! Form::text('check_date', null, [
                                'class' => 'form-control date'
                                ]) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::submit('Save', [
                                'class' => 'btn btn-primary'
                                ]) !!}

                                @if( isset( $collection ) )
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

    @include('footer')
    <script>
        //var t = moment().format('MMMM Do YYYY, h:mm:ss a');
        //alert(t);

        $('#delete_btn').click(function () {
            if ( confirm("Would you like to delete transaction?") ) {
                $('input[name="_method"]').val("DELETE");
                $('form').submit();
            }
        });

        $('.focus-next').keydown(function (e) {
            if (e.which == 13) {
                e.preventDefault();
                $(this).parent().next().find("input").focus();
            }
        });
    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.5.3/numeral.min.js"></script>
    <script src="/js/collections.js"></script>
@endsection
