<div class="edit-block col-sm-9">
    {!! Form::label('email','Novo E-mail:', ['class'=>'control-label-left soft-label col-sm-3' ]) !!}
    <div class="col-sm-6">
        {!! Form::email('email', null, ['class'=>'form-control']) !!}
    </div><!-- /.col-md-9 -->
    <div class='col-sm-12'>
        <div class="col-sm-3">
            <a id="save-email" class="save-edit-block btn btn-cinza btn-pequeno btn-file btn-lateral-form">
                SALVAR
            </a>
        </div>
        <div class="col-sm-3">
            <a class="cancel-edit-block btn btn-cinza btn-pequeno btn-file btn-lateral-form">
                CANCELAR
            </a>
        </div>
    </div>
</div>