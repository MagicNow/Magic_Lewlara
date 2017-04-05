<div class="edit-block col-sm-9">
    <div class='col-sm-12'>
        {!! Form::label('username','Novo Nome de Usuário', ['class'=>'control-label-left soft-label col-sm-3']) !!}
        <div class="col-sm-6">
            {!! Form::text('username', null, ['class'=>'form-control']) !!}
        </div><!-- /.col-md-9 -->
    </div>
    <br/>
    <div class='col-sm-12'>
        <div class="col-sm-3">
            <a id="save-username" class="save-edit-block btn btn-cinza btn-pequeno btn-file btn-lateral-form">
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