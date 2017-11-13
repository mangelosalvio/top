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

            @include('partials.search_loans',$search_data)

            <div class="col-md-12">
                @if( isset($computation_slip) )
                    {!! Form::model($computation_slip,[
                    'url' => "/computation-slips/{$computation_slip->id}",
                    'class' => 'form-horizontal',
                    'method' => 'put'
                    ]) !!}
                @else
                    {!! Form::open([
                    'url' => "/computation-slips",
                    'class' => 'form-horizontal'
                    ]) !!}
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">Compuation Slip</div>
                    <div class="panel-body">
                        <div class="form-group">
                            {!! Form::hidden('id',null,[
                                'v-model' => 'loan_id'
                            ]) !!}
                            {!! Form::label('applicant','Applicant', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}
                            <div class="col-sm-9 form-control-static">
                                {{ $computation_slip->customer->name }}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('unit_applied','Unit Applied', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}
                            <div class="col-sm-9">
                                {!! Form::text('unit_applied', $computation_slip->collateral->collateral_desc, [
                                'class' => 'form-control'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('term','Term', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}
                            <div class="col-sm-6 form-control-static">
                                {{ $computation_slip->term }} months
                            </div>

                            {!! Form::label('term','Rate', [
                            'class' => 'col-sm-1 control-label'
                            ]) !!}

                            <div class="col-sm-2 form-control-static">
                                {{ $computation_slip->rebate_rate }} %
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('Cash Price','Cash Price', [
                            'class' => 'col-sm-2 col-sm-offset-2 control-label'
                            ]) !!}
                            <div class="col-sm-offset-6 col-sm-2 form-control-static text-right">
                                {{ number_format($computation_slip->amount,2) }}
                            </div>
                            {!! Form::hidden('amount',null, [
                                'v-model' => 'amount'
                            ]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('term','FINANCING CHARGES', [
                            'class' => 'col-sm-3'
                            ]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('net_purchase_discount','Net Purchase Discount', [
                            'class' => 'col-sm-4 indent-1'
                            ]) !!}
                            <div class="col-sm-offset-4 col-sm-2 form-control-static text-right">
                                {{ $computation_slip->interest_rate }} %
                            </div>
                            <div class="col-sm-2 text-right form-control-static">
                                {{ number_format($computation_slip->interest_amount,2) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('rebatable_purchase_discount','Rebatable Purchase Discount', [
                            'class' => 'col-sm-4 indent-1'
                            ]) !!}
                            <div class="col-sm-offset-4 col-sm-2 form-control-static text-right">
                                {{ $computation_slip->rebate_rate }} %
                            </div>
                            <div class="col-sm-2 text-right form-control-static">
                                {{ number_format($computation_slip->rebate_amount,2) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('term','AMOUNT OF PROMISSORY', [
                            'class' => 'col-sm-3'
                            ]) !!}
                            <div class="col-sm-9 text-right form-control-static">
                                <strong>{{ number_format($computation_slip->pn_amount,2) }}</strong>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('first_installment','First Installment', [
                            'class' => 'col-sm-4 indent-1'
                            ]) !!}

                            <div class="col-sm-offset-6 col-sm-2 text-right form-control-static">
                                {{ number_format($computation_slip->installment_first,2) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('first_rebate_discount','Less: Rebate (Discount)', [
                            'class' => 'col-sm-5 indent-2'
                            ]) !!}

                            <div class="col-sm-offset-5 col-sm-2 text-right form-control-static">
                                {{ number_format($computation_slip->rebate_first,2) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('first_net_amount_due','Net Amount Due', [
                            'class' => 'col-sm-5 indent-2'
                            ]) !!}

                            <div class="col-sm-offset-5 col-sm-2 text-right form-control-static">
                                {{ number_format($computation_slip->net_first,2) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('subsequent_installment','Subsequent Installment (2nd to Last)',[
                            'class' => 'col-sm-4 indent-1'
                            ]) !!}

                            <div class="col-sm-offset-6 col-sm-2 text-right form-control-static">
                                {{ number_format($computation_slip->installment_second,2) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('first_rebate_discount','Less: Rebate (Discount)', [
                            'class' => 'col-sm-5 indent-2'
                            ]) !!}

                            <div class="col-sm-offset-5 col-sm-2 text-right form-control-static">
                                {{ number_format($computation_slip->rebate_second,2) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('first_net_amount_due','Net Amount Due', [
                            'class' => 'col-sm-5 indent-2'
                            ]) !!}
                            <div class="col-sm-offset-5 col-sm-2 text-right form-control-static">
                                {{ number_format($computation_slip->net_second,2) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label(null,'OTHER CHARGES (MISCELLANOUS EXPENSES)', [
                            'class' => 'col-sm-12'
                            ]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('doc_stamps','Documentary Stamps', [
                            'class' => 'col-sm-4 indent-1'
                            ]) !!}
                            <div class="col-sm-offset-6 col-sm-2">
                                {!! Form::text('doc_stamp',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'doc_stamp',
                                'readonly' => 'true'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('science_stamps','Science Stamps', [
                            'class' => 'col-sm-4 indent-1'
                            ]) !!}
                            <div class="col-sm-offset-6 col-sm-2">
                                {!! Form::text('science_stamps',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'science_stamps'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('notarial_fees','Notarial Fee', [
                            'class' => 'col-sm-4 indent-1'
                            ]) !!}
                            <div class="col-sm-offset-6 col-sm-2">
                                {!! Form::text('notarial_fees',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'notarial_fees'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('misc_fees','Miscellanous (Fare Photocopies, Tips, Etc.)', [
                            'class' => 'col-sm-4 indent-1'
                            ]) !!}
                            <div class="col-sm-offset-6 col-sm-2">
                                {!! Form::text('misc_fees',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'misc_fees'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label(null,'Registration of Deeds (Registration Expenses)', [
                            'class' => 'col-sm-4 indent-1'
                            ]) !!}

                        </div>

                        <div class="form-group">
                            {!! Form::label('mortgage_fees','Chattel and Mortgage Fee', [
                            'class' => 'col-sm-4 indent-2'
                            ]) !!}
                            <div class="col-sm-offset-4 col-sm-2">
                                {!! Form::text('mortgage_fees',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'mortgage_fees',
                                'readonly' => true
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('deed_of_assignment_fees','Deed of Assignment Fee', [
                            'class' => 'col-sm-4 indent-2'
                            ]) !!}
                            <div class="col-sm-offset-4 col-sm-2">
                                {!! Form::text('deed_of_assignment_fees',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'deed_of_assignment_fees'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('legal_and_research_fees','Legal and Research Fees', [
                            'class' => 'col-sm-4 indent-2'
                            ]) !!}
                            <div class="col-sm-offset-4 col-sm-2">
                                {!! Form::text('legal_and_research_fees',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'legal_and_research_fees'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('total_rod_charges','Total ROD Charges', [
                            'class' => 'col-sm-4 indent-2',
                            ]) !!}
                            <div class="col-sm-offset-6 col-sm-2">
                                {!! Form::text('total_rod_charges',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'total_rod_charges',
                                'readonly' => true
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label(null,'ILTO (Annotation of Mortgage & Assignment)', [
                            'class' => 'col-sm-4 indent-1',
                            ]) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('transfer_fees','Transfer Fee', [
                            'class' => 'col-sm-4 indent-2'
                            ]) !!}
                            <div class="col-sm-offset-4 col-sm-2">
                                {!! Form::text('transfer_fees',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'transfer_fees'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('mortgage_and_assignment_fees','Mortgage & Assignment', [
                            'class' => 'col-sm-4 indent-2'
                            ]) !!}
                            <div class="col-sm-offset-4 col-sm-2">
                                {!! Form::text('mortgage_and_assignment_fees',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'mortgage_and_assignment_fees'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('misc_lto_fees','Miscellanous', [
                            'class' => 'col-sm-4 indent-2'
                            ]) !!}
                            <div class="col-sm-offset-4 col-sm-2">
                                {!! Form::text('misc_lto_fees',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'misc_lto_fees'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('total_lto_charges','Total LTO Charges', [
                            'class' => 'col-sm-4 indent-2',
                            ]) !!}
                            <div class="col-sm-offset-6 col-sm-2">
                                {!! Form::text('total_lto_charges',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'total_lto_charges',
                                'readonly' => true
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('total_doc_fees','TOTAL MISCELLANEOUS CHARGES', [
                            'class' => 'col-sm-4 indent-1',
                            ]) !!}
                            <div class="col-sm-offset-6 col-sm-2">
                                {!! Form::text('total_doc_fees',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'total_doc_fees',
                                'readonly' => true
                                ]) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::submit('Save', [
                                'class' => 'btn btn-primary'
                                ]) !!}

                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Less:
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            {!! Form::label('service_fees','Service Fee', [
                            'class' => 'col-sm-4 indent-1',
                            ]) !!}
                            <div class="col-sm-offset-6 col-sm-2">
                                {!! Form::text('service_fees',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'service_fees'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('od_insurance_fees','OD Insurance', [
                            'class' => 'col-sm-4 indent-1',
                            ]) !!}
                            <div class="col-sm-offset-6 col-sm-2">
                                {!! Form::text('od_insurance_fees',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'od_insurance_fees'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label(null,'Doc Fees', [
                            'class' => 'col-sm-4 indent-1',
                            ]) !!}
                            <div class="col-sm-offset-6 col-sm-2">
                                {!! Form::text('total_doc_fees',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'total_doc_fees',
                                'readonly' => true
                                ]) !!}
                            </div>
                        </div>

                        <!-- OTHER ADDITIONS -->
                        <div class="form-group">
                            <div class="col-sm-12">
                                <strong>Other Additions</strong>
                            </div>
                            <div class="col-sm-9">
                                {!! Form::select(null, $chart_of_accounts, null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Select Additions',
                                    'v-model' => 'other_addition.chart_of_account_id'
                                ]) !!}

                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" v-model="other_addition.amount">
                            </div>
                            <div class="col-sm-1">
                                <input type="button" value="Add" class="btn btn-default form-control"  @click="addAddition">
                            </div>
                        </div>

                        <div class="form-group" v-for="addition in other_additions">
                            <div class="col-sm-1 text-right form-control-static" >
                                <span class="glyphicon glyphicon-trash" style="cursor: pointer;" @click="removeAddition(addition)"></span>
                            </div>
                            <div class="col-sm-9">
                                <select name="other_additions[chart_of_account_id][]"  v-model="addition.chart_of_account_id" class="form-control">
                                    <option v-for="account in chart_of_accounts" :value="account.id">@{{ account.account_desc }}</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="other_additions[amount][]" class="form-control text-right" v-model="addition.amount">
                            </div>
                        </div>

                        <!-- END OTHER ADDITIONS -->

                        <!-- OTHER DEDUCTIONS -->
                        <div class="form-group">
                            <div class="col-sm-12">
                                <strong>Other Deductions</strong>
                            </div>
                            <div class="col-sm-9">
                                {!! Form::select(null, $chart_of_accounts, null, [
                                'class' => 'form-control',
                                'placeholder' => 'Select Deduction',
                                'v-model' => 'other_deduction.chart_of_account_id'
                                ]) !!}

                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" v-model="other_deduction.amount">
                            </div>
                            <div class="col-sm-1">
                                <input type="button" value="Add" class="btn btn-default form-control"  @click="addDeduction">
                            </div>
                        </div>

                        <div class="form-group" v-for="deduction in other_deductions">
                            <div class="col-sm-1 text-right form-control-static" >
                                <span class="glyphicon glyphicon-trash" style="cursor: pointer;" @click="removeDeduction(deduction)"></span>
                            </div>
                            <div class="col-sm-9">
                                <select name="other_deductions[chart_of_account_id][]"  v-model="deduction.chart_of_account_id" class="form-control">
                                    <option v-for="account in chart_of_accounts" :value="account.id">@{{ account.account_desc }}</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="other_deductions[amount][]" class="form-control text-right" v-model="deduction.amount">
                            </div>
                        </div>

                        <!-- END OTHER DEDUCTIONS -->

                        <div class="form-group">
                            {!! Form::label(null,'Net Proceeds', [
                            'class' => 'col-sm-4 indent-1',
                            ]) !!}
                            <div class="col-sm-offset-6 col-sm-2">
                                {!! Form::text('net_proceeds',null,[
                                'class' => 'form-control text-right',
                                'v-model' => 'net_proceeds'
                                ]) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::submit('Save', [
                                'class' => 'btn btn-primary'
                                ]) !!}

                            </div>
                        </div>

                    </div>

                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Check Details
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            {!! Form::label('cv_no','CV #', [
                            'class' => 'col-sm-2 indent-1',
                            ]) !!}
                            <div class="col-sm-4">
                                {!! Form::text('cv_no',null,[
                                'class' => 'form-control',
                                'v-model' => 'cv_no'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('check_no','Check #', [
                            'class' => 'col-sm-2 indent-1',
                            ]) !!}
                            <div class="col-sm-4">
                                {!! Form::text('check_no',null,[
                                'class' => 'form-control',
                                'v-model' => 'check_no'
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('check_date','Check Date', [
                            'class' => 'col-sm-2 indent-1',
                            ]) !!}
                            <div class="col-sm-4">
                                {!! Form::date('check_date',null,[
                                'class' => 'form-control datepicker',
                                'v-model' => 'check_date'
                                ]) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::submit('Save', [
                                'class' => 'btn btn-primary'
                                ]) !!}

                            </div>
                        </div>
                    </div>

                </div>

                <div class="panel">
                    <div class="panel-body">
                        <a href="{{ url("/computation-slips/{$computation_slip->id}/print-computation-slip") }}" class="btn btn-default" target="_blank">Print Computation Slip</a>
                        <a href="{{ url("/computation-slips/{$computation_slip->id}/print-cash-voucher") }}" class="btn btn-default" target="_blank">Print Cash Voucher</a>
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
            if (confirm("Would you like to confirm deletion?")) {
                $('input[name="_method"]').val("DELETE");
                $('form').submit();
            }

        });
    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.5.3/numeral.min.js"></script>
    <script src="/js/computation_slips.js"></script>
@endsection
