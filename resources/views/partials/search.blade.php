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