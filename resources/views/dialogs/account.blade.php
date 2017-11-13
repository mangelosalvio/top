<div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Create Account Code</h4>
            </div>
            <div class="modal-body">
                <div class="row form-horizontal">
                    <div class="form-group">
                        {!! Form::label('account_code','Account Code', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::text('Account Code', null, [
                            'class' => 'form-control',
                            'v-model' => 'detail.account_code',
                            'readonly' => 'readonly'
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('account_type_id','Account Desc', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::text('Account Desc', null, [
                            'class' => 'form-control',
                            'id' => 'account_desc',
                            'v-model' => 'detail.account_desc',
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('account_type_id','Account Type', [
                        'class' => 'col-sm-3 control-label'
                        ]) !!}
                        <div class="col-sm-9">
                            {!! Form::select('account_type_id',$account_types, null, [
                            'class' => 'form-control',
                            'placeholder' => 'Select Account Type',
                            'v-model' => 'detail.account_type_id'
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="saveAccount">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>