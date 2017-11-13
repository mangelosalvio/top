{!! Form::open([
    'url' => "/$search_url",
    'method' => 'get'
]) !!}
<div class="col-md-offset-5 col-md-1">

    @if( isset($display_add_btn) )
        @if( $display_add_btn )
            <div class="text-right">
                <a href="{{ url("/$search_url/create") }}" class="btn btn-default">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </a>
            </div>
        @endif
    @else
        <div class="text-right">
            <a href="{{ url("/$search_url/create") }}" class="btn btn-default">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
            </a>
        </div>
    @endif
</div>
<div class="col-md-6" style="margin-bottom: 12px;">
    <div class="input-group">
        <input type="text" name="keyword" class="form-control" placeholder="Search for..." value="{{ request()->get('keyword') }}">
                <span class="input-group-btn">
                    <button class="btn btn-default"><span class="glyphicon glyphicon-search"
                                                                        aria-hidden="true"></span></button>
                </span>
    </div>
</div>
{!! Form::close() !!}

<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            Advanced Search
        </div>
        <div class="panel-body">
            {!! Form::open([
            'url' => '/loans',
            'method' => 'GET',
            'class' => 'form-horizontal'
            ]) !!}
            <div class="form-group">
                {!! Form::label('search_id','Loan #', [
                'class' => 'col-sm-2 control-label'
                ]) !!}

                <div class="col-sm-4">
                    {!! Form::text('search_id', request()->get('search_id'), [
                    'class' => 'form-control'
                    ]) !!}
                </div>

                {!! Form::label('search_date','Date Pur.(From - To)', [
                'class' => 'col-sm-2 control-label'
                ]) !!}

                <div class="col-sm-2">
                    {!! Form::date('search_date_purchased_from_date', request()->get('search_date_purchased_from_date'), [
                    'class' => 'form-control datepicker'
                    ]) !!}
                </div>
                <div class="col-sm-2">
                    {!! Form::date('search_date_purchased_to_date', request()->get('search_date_purchased_to_date'), [
                    'class' => 'form-control datepicker'
                    ]) !!}
                </div>

            </div>

            <div class="form-group">
                {!! Form::label('search_customer_name','Customer', [
                'class' => 'col-sm-2 control-label'
                ]) !!}

                <div class="col-sm-4">
                    {!! Form::text('search_customer_name',request()->get('search_customer_name'), [
                    'class' => 'form-control'
                    ]) !!}
                </div>

                {!! Form::label('search_date','Check Date(From - To)', [
                'class' => 'col-sm-2 control-label'
                ]) !!}

                <div class="col-sm-2">
                    {!! Form::date('search_check_date_from_date', request()->get('search_check_date_from_date'), [
                    'class' => 'form-control datepicker'
                    ]) !!}
                </div>
                <div class="col-sm-2">
                    {!! Form::date('search_check_date_to_date', request()->get('search_check_date_to_date'), [
                    'class' => 'form-control datepicker'
                    ]) !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 col-sm-offset-2">
                    {!! Form::submit('Search',[
                    'class' => 'btn btn-default'
                    ]) !!}

                    {!! Form::reset('Reset',[
                    'class' => 'btn btn-warning'
                    ]) !!}
                </div>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
</div>