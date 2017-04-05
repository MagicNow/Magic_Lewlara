<div class="edit-block col-sm-9">
    <div class='col-sm-12'>
        {!! Form::label('current_password','Senha Atual', ['class'=>'control-label-left soft-label col-sm-3']) !!}
        <div class="col-sm-6">
            {!! Form::password('current_password', ['class'=>'form-control']) !!}
        </div><!-- /.col-md-9 -->
    </div>

    <div class='col-sm-12'>
        {!! Form::label('password','Nova Senha', ['class'=>'control-label-left soft-label col-sm-3']) !!}
        <div class="col-sm-6 pt7">
            {!! Form::password('password', ['class'=>'form-control']) !!}
        </div><!-- /.col-md-9 -->
    </div>

    <div class='col-sm-12'>
        {!! Form::label('password_confirmation','Repetir Senha', ['class'=>'control-label-left soft-label col-sm-3']) !!}
        <div class="col-sm-6 pt7">
            {!! Form::password('password_confirmation', ['class'=>'form-control']) !!}
        </div><!-- /.col-md-9 -->
    </div>
    <br/>
    <div class='col-sm-12'>
        <div class="col-sm-3">
            <a id="save-password" class="save-edit-block btn btn-cinza btn-pequeno btn-file btn-lateral-form">
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