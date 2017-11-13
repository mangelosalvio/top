@extends('layouts.app')

@section('content')
<div class="container-fluid" id="app">
    <div class="row">

        @include('partials.search_loans',$search_data)

        <div class="col-md-12">
            @if( isset($loan) )
            {!! Form::model($loan,[
            'url' => "/loans/{$loan->id}",
            'class' => 'form-horizontal',
            'method' => 'put'
            ]) !!}
            @else
                {!! Form::open([
                'url' => "/loans",
                'class' => 'form-horizontal'
                ]) !!}
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Loans</div>
                <div class="panel-body">

                    @if( isset( $loan ) )
                        <div class="form-group">
                            {!! Form::label(null,'Loan #', [
                            'class' => 'col-sm-2 control-label'
                            ]) !!}
                            <div class="col-sm-4 form-control-static">
                                <strong>{{ str_pad($loan->id,7,0,STR_PAD_LEFT) }}</strong>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">

                        {!! Form::label('customer_id','Customer', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::select('customer_id', $customers, null, [
                            'placeholder' => 'Select Customer',
                            'class' => 'form-control',
                            'v-model' => 'customer_id',
                            '@change' => 'getCollaterals'
                            ]) !!}
                        </div>

                        {!! Form::label('collateral_id','Collateral', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <input type="hidden" v-model="collateral_id">
                        <div class="col-sm-4">
                            <select class="form-control" name="collateral_id" v-model="collateral_id">
                                <option v-for="collateral in collaterals" value="@{{ collateral.id }}">@{{ collateral.text }}</option>
                            </select>
                        </div>
                    </div>

                    <hr/>

                    <div class="form-group">
                        {!! Form::label('date','Date', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::date('date', null, [
                            'class' => 'form-control datepicker'
                            ]) !!}
                        </div>

                        {!! Form::label('date_purchased','Date Purchased', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::date('date_purchased', null, [
                            'class' => 'form-control datepicker'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('unit','Unit', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('unit', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('plate_no','Plate #', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('plate_no', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('comaker','Comaker', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('comaker', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('trans_type_id','Trans Type', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::select('trans_type_id', $trans_types ,null, [
                            'placeholder' => 'Select Trans Type',
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('purpose','Purpose', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('purpose', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                        {!! Form::label('salesman','Salesman', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('salesman', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('dealer','Dealer', [
                        'class' => 'col-sm-2 col-sm-offset-6 control-label'
                        ]) !!}
                        <div class="col-sm-4">
                            {!! Form::text('dealer', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <hr/>

                    <div class="form-group">
                        {!! Form::label('is_renewal','Is Renewal', [
                        'class' => 'col-sm-1 control-label'
                        ]) !!}
                        <div class="col-sm-2 form-control-static">
                            {!! Form::hidden('is_renewal',0) !!}
                            {!! Form::checkbox('is_renewal', 1, null, [
                            ]) !!}
                        </div>
                    </div>

                    <hr/>

                    <div class="form-group">
                        {!! Form::label('amount','Amount', [
                        'class' => 'col-sm-1 control-label'
                        ]) !!}
                        <div class="col-sm-2">
                            {!! Form::text('amount', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>

                        {!! Form::label('term','Term', [
                        'class' => 'col-sm-1 control-label'
                        ]) !!}
                        <div class="col-sm-2">
                            {!! Form::text('term', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>

                        {!! Form::label('interest_rate','Interest(%)', [
                        'class' => 'col-sm-1 control-label'
                        ]) !!}
                        <div class="col-sm-2">
                            {!! Form::text('interest_rate', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>

                        {!! Form::label('rebate_rate','Rebate(%)', [
                        'class' => 'col-sm-1 control-label'
                        ]) !!}
                        <div class="col-sm-2">
                            {!! Form::text('rebate_rate', null, [
                            'class' => 'form-control text-right'
                            ]) !!}
                        </div>
                    </div>

                    @if( isset( $loan ) && isset($loan->date_purchased) )

                        <hr/>

                        <div class="form-group">
                            {!! Form::label('cash_out','Cash Out', [
                            'class' => 'col-sm-2 control-label'
                            ]) !!}
                            <div class="col-sm-3">
                                {!! Form::text('cash_out', null, [
                                'class' => 'form-control text-right'
                                ]) !!}
                            </div>

                            <div class="col-sm-2 col-sm-offset-3 text-center">
                                1st
                            </div>
                            <div class="col-sm-2 text-center">
                                2nd
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('interest_amount','UII', [
                            'class' => 'col-sm-2 control-label'
                            ]) !!}
                            <div class="col-sm-3">
                                {!! Form::text('interest_amount', null, [
                                'class' => 'form-control text-right'
                                ]) !!}
                            </div>

                            {!! Form::label('installment','Installment', [
                            'class' => 'col-sm-2 col-sm-offset-1 control-label'
                            ]) !!}

                            <div class="col-sm-2">
                                {!! Form::text('installment_first', null, [
                                'class' => 'form-control text-right'
                                ]) !!}
                            </div>

                            <div class="col-sm-2">
                                {!! Form::text('installment_second', null, [
                                'class' => 'form-control text-right'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('rebate_amount','RFF', [
                            'class' => 'col-sm-2 control-label'
                            ]) !!}
                            <div class="col-sm-3">
                                {!! Form::text('rebate_amount', null, [
                                'class' => 'form-control text-right'
                                ]) !!}
                            </div>

                            {!! Form::label('rebate','Rebate', [
                            'class' => 'col-sm-2 col-sm-offset-1 control-label'
                            ]) !!}

                            <div class="col-sm-2">
                                {!! Form::text('rebate_first', null, [
                                'class' => 'form-control text-right'
                                ]) !!}
                            </div>

                            <div class="col-sm-2">
                                {!! Form::text('rebate_second', null, [
                                'class' => 'form-control text-right'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('pn_amount','PN', [
                            'class' => 'col-sm-2 control-label'
                            ]) !!}
                            <div class="col-sm-3">
                                {!! Form::text('pn_amount', null, [
                                'class' => 'form-control text-right'
                                ]) !!}
                            </div>

                            {!! Form::label('net_amount','Net Amount', [
                            'class' => 'col-sm-2 col-sm-offset-1 control-label'
                            ]) !!}

                            <div class="col-sm-2">
                                {!! Form::text('net_first', null, [
                                'class' => 'form-control text-right'
                                ]) !!}
                            </div>

                            <div class="col-sm-2">
                                {!! Form::text('net_second', null, [
                                'class' => 'form-control text-right'
                                ]) !!}
                            </div>
                        </div>

                        @if( isset($loan->date_purchased) )
                            <hr/>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="col-sm-3">Customer Code</div>
                                    <div class="col-sm-2">
                                        {!! Form::text('customer_code',null, [
                                        'class' => 'form-control',
                                        'v-model' => 'customer_code'
                                        ]) !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <button value="1" class="btn btn-default" name="create_accounts">Create/Search Accounts</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">L/R Account</div>
                                    <div class="col-sm-9">
                                        @if( isset( $loan->LrAccount ) )
                                            {{ $loan->LrAccount->account_code }} -
                                            {{ $loan->LrAccount->account_desc }}
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">UII Account</div>
                                    <div class="col-sm-9">
                                        @if( isset( $loan->UiiAccount ) )
                                            {{ $loan->UiiAccount->account_code }} -
                                            {{ $loan->UiiAccount->account_desc }}
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-3">RFF Account</div>
                                    <div class="col-sm-9">
                                        @if( isset( $loan->RffAccount ) )
                                            {{ $loan->RffAccount->account_code }} -
                                            {{ $loan->RffAccount->account_desc }}
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">A/R Account</div>
                                    <div class="col-sm-9">
                                        @if( isset( $loan->ArAccount ) )
                                            {{ $loan->ArAccount->account_code }} -
                                            {{ $loan->ArAccount->account_desc }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-sm-12" style="border-top:1px solid #eeeeee; margin-top:22px; padding-top:22px;">
                            <div class="form-group">

                                {!! Form::label('balance_forwarded','Forwarded Balance', [
                                'class' => 'col-sm-3 control-label'
                                ]) !!}

                                <div class="col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::hidden('is_balance_forwarded',0) !!}
                                            {!! Form::checkbox('is_balance_forwarded') !!}
                                            Has Balance Forwarded?
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if( isset($loan->loanLedgers) )
                        <div class="col-sm-12">
                            <h4>Loan Ledger</h4>
                        </div>
                        <div class="col-sm-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>DATE</th>
                                    <th>OR NO</th>
                                    <th class="text-right">TOTAL AMOUNT</th>
                                    <th class="text-right">REBATE</th>
                                    <th class="text-right">OUTSTANDING BALANCE</th>
                                    <th class="text-right">OUTSTANDING INTEREST</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $loan->loanLedgers as $Ledger )
                                    <tr>
                                        <td>{{ $Ledger->date }}</td>
                                        <td>{{ $Ledger->collection->or_no }}</td>
                                        <td class="text-right">{{ number_format($Ledger->payment_amount,2) }}</td>
                                        <td class="text-right">{{ number_format($Ledger->collection->rff_debit,2) }}</td>
                                        <td class="text-right">{{ number_format($Ledger->outstanding_balance,2) }}</td>
                                        <td class="text-right">{{ number_format($Ledger->outstanding_interest,2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                        @endif
                        <hr/>

                        <div class="col-sm-12">
                            <h4>Amortization Table</h4>
                        </div>
                        <div class="col-sm-12" style="border-top:1px solid #eeeeee; margin-top:22px; padding-top:22px;">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>DATE</th>
                                    <th>TERM</th>
                                    <th class="text-right">INSTALLMENT AMOUT</th>
                                    <th class="text-right">INTEREST</th>
                                    <th class="text-right">REBATE</th>
                                    <th class="text-right">OUTSTANDING BAL</th>
                                    <th class="text-right">OUTSTANDING INTEREST</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($loan->amortizationTables as $AmortizationTable)
                                    <tr>
                                        <td>{{ $AmortizationTable->due_date->toFormattedDateString() }}</td>
                                        <td>{{ $AmortizationTable->term }}</td>
                                        <td class="text-right">{{ number_format($AmortizationTable->installment_amount,2) }}</td>
                                        <td class="text-right">{{ number_format($AmortizationTable->interest_amount,2) }}</td>
                                        <td class="text-right">{{ number_format($AmortizationTable->rebate_amount,2) }}</td>
                                        <td class="text-right">{{ number_format($AmortizationTable->outstanding_balance,2) }}</td>
                                        <td class="text-right">{{ number_format($AmortizationTable->outstanding_interest,2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    @endif

                    <hr/>

                    <div class="form-group">
                        {!! Form::label('credit_verification','Credit Verification', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-10">
                            {!! Form::textarea('credit_verification', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>

                    </div>

                    <div class="form-group">
                        {!! Form::label('credit_investigator','Credit Investigator', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-10">
                            {!! Form::text('credit_investigator', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('manager','Manager', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-10">
                            {!! Form::text('manager', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('approving_signatory','Approving Signatory', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-10">
                            {!! Form::text('approving_signatory', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('signatories','Signatories', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-10">

                            <div class="radio">
                                <label>
                                    {!! Form::radio('signatories','P',null,[
                                        'id' => 'signatory_1'
                                    ]) !!}
                                    PRESIDENT
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    {!! Form::radio('signatories','B',null,[
                                    'id' => 'signatory_2'
                                    ]) !!}
                                    BOARD
                                </label>
                            </div>
                        </div>

                    </div>

                    <hr/>

                    <div class="form-group">
                        {!! Form::label('res_cert_no','Res. Cert. #', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-10">
                            {!! Form::text('res_cert_no', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('res_cert_date','Res. Cert. Date', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-10">
                            {!! Form::text('res_cert_date', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('res_cert_place','Res. Cert. Place', [
                        'class' => 'col-sm-2 control-label'
                        ]) !!}
                        <div class="col-sm-10">
                            {!! Form::text('res_cert_place', null, [
                            'class' => 'form-control'
                            ]) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            {!! Form::submit('Save', [
                            'class' => 'btn btn-primary'
                            ]) !!}

                            @if( isset( $loan ) )
                                <input type="button" id="delete_btn" class="btn btn-danger" value="Delete">
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            @if( isset( $loan ) && isset( $loan->collateral ) )
                <div class="panel">
                    <div class="panel-body">
                        @if( $loan->collateral->collateral_type == "CHATTEL" )
                        <a href="{{ url("/collaterals/{$loan->collateral->id}/print-appraisal-report") }}" class="btn btn-default" target="_blank">Print Appraisal Report</a>
                        @else
                        <a href="{{ url("/collaterals/{$loan->collateral->id}/print-appraisal-rem-report") }}" class="btn btn-default" target="_blank">Print Appraisal Report</a>
                        @endif
                        <a href="{{ url("/loans/{$loan->id}/print-processing-slip") }}" class="btn btn-default" target="_blank">Print Processing Slip</a>
                        <a href="{{ url("/loans/{$loan->id}/print-account-ledger") }}" class="btn btn-default" target="_blank">Print Account Ledger</a>
                        <a href="{{ url("/loans/{$loan->id}/print-credit-investigation-report") }}" class="btn btn-default" target="_blank">Print Credit Investigation Report</a>
                        <!-- <a href="{{ url("/loans/{$loan->id}/print-chattel-mortgage") }}" class="btn btn-default" target="_blank">Print Chattel Mortgage</a>
                        <a href="{{ url("/loans/{$loan->id}/print-disclosure-statement") }}" class="btn btn-default" target="_blank">Print Disclosure Statement</a> 
                        <a href="{{ url("/loans/{$loan->id}/print-promissory-note") }}" class="btn btn-default" target="_blank">Print Promissory Note</a>  -->
                    </div>
                </div>
            @endif




            {!! Form::close() !!}
        </div>
    </div>
</div>
@include ('footer')
<script>
    $('#delete_btn').click(function(){
        if ( confirm("Would you like to delete transaction?") ) {
            $('input[name="_method"]').val("DELETE");
            $('form').submit();
        }

    });
</script>
<script src="/js/loans.js"></script>
@endsection
