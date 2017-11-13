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
                @if( isset($general_ledger) )
                    {!! Form::model($general_ledger,[
                    'url' => "/{$route}/{$general_ledger->id}",
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
                    <div class="panel-heading">GENERAL LEDGER</div>
                    <div class="panel-body">
                        @if( isset($general_ledger) )
                        <div class="form-group">
                            {!! Form::label('id','GL #', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9">
                                {!! Form::text('id', null, [
                                'class' => 'form-control',
                                'readonly' => true,
                                'v-model' => 'general_ledger_id'
                                ]) !!}
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            {!! Form::label('journal_id','Journal', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9">
                                {!! Form::select('journal_id', $journals, null, [
                                'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('date','Date', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9">
                                {!! Form::text('date', null, [
                                'class' => 'form-control datepicker',
                                ]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('particulars','Particulars', [
                            'class' => 'col-sm-3 control-label'
                            ]) !!}

                            <div class="col-sm-9">
                                {!! Form::textarea('particulars', null, [
                                'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::submit('Save', [
                                'class' => 'btn btn-primary'
                                ]) !!}

                                @if( isset( $general_ledger ) )
                                    <input type="button" id="delete_btn" class="btn btn-danger" value="Delete">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        General Ledger Details
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

                        <div class="form-group" v-for="general_ledger_detail in general_ledger_details">
                            <div class="col-sm-1 text-right form-control-static" >
                                <span class="glyphicon glyphicon-trash" style="cursor: pointer;" @click="removeDetail(general_ledger_detail)"></span>
                            </div>
                            <div class="col-sm-3">
                                <input type="hidden" name="general_ledger_details[chart_of_account_id][]" v-model="general_ledger_detail.chart_of_account_id" >
                                @{{ general_ledger_detail.account_code }}
                            </div>

                            <div class="col-sm-3">
                                @{{ general_ledger_detail.account_desc }}
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="general_ledger_details[debit][]" class="form-control text-right" v-model="general_ledger_detail.debit">
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="general_ledger_details[credit][]" class="form-control text-right" v-model="general_ledger_detail.credit">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2 col-sm-offset-7 text-right" style="font-weight: bold;">
                                @{{ total_debit }}
                            </div>

                            <div class="col-sm-2 text-right" style="font-weight: bold;">
                                @{{ total_credit }}
                            </div>

                        </div>

                        <!-- END OTHER ADDITIONS -->

                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::submit('Save', [
                                'class' => 'btn btn-primary'
                                ]) !!}

                            </div>
                        </div>

                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    @include('dialogs.account')

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

        $('.focus-next').keydown(function(e){
            if (e.which == 13)  {
                e.preventDefault();
                if ( $(this).parent().next().find("input.focus-next").length <= 0 ) {
                    $(this).parent().next().next().find("input.focus-next").focus();
                } else {
                    $(this).parent().next().find("input.focus-next").focus();
                }

            }
        });
    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.5.3/numeral.min.js"></script>
    <script src="/js/general_ledgers.js"></script>
@endsection
