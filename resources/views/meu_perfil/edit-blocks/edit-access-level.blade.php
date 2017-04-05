<div class="edit-block col-sm-9">
    {!! Form::label('group','Nível do Usuário', ['class'=>'control-label col-sm-3']) !!}
    <div class="col-sm-6">
        {!! Form::select('group', $groups_select, $usuario->group()->lists('id','name'), ['class'=>'form-control arrow-preto-amarelo']) !!}
    </div><!-- /.col-md-9 -->
    <div class='col-sm-12'>
        <div class="col-sm-3">
            <a id="save-access-level" class="save-edit-block btn btn-cinza btn-pequeno btn-file btn-lateral-form">
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