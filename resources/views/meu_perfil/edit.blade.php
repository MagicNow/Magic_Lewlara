<?php
$header_title = 'Meu Perfil';
?>

<?php
$scripts = array(
    0 => array(
        'src' => URL::to('/').elixir('js/pages/perfil.js')
    ),
);
?>


@extends('base_sidebar')



@section('content')

<div class="col-sm-9  col-md-9 main usuario perfil">

    <br />


    <h1>EDITAR MEU PERFIL</h1>
    <br />

    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div id="ajax-msg" class="col-sm-12">
        <ul id="ajax-msgs">

        </ul>
    </div>


    {!! Form::model($usuario,['method'=>'PUT','action'=>['UserController@update',$usuario->id], 'class'=>'form-horizontal', 'id'=>'ajaxProfile', 'files' => true]) !!}

    <!--IMAGEM-->

    <div class="form-group">
        <div class="img-perfil-container col-sm-3">
            @if ($usuario->photo)
            <img src="{{ URL::to('/upload/usuario').'/'.$usuario->id.'/'.$usuario->photo }}" class="img-perfil">
            @endif
        </div>
        <?php /*
        <small class="col-sm-9 ">- NomeDaImagem.jpg </small>
        <small class="col-sm-9 ">- 03/10/2014 às 14:24</small>
        <small class="col-sm-9 ">- 3 Mb</small>
        <small class="col-sm-9 ">- 350 x 350 pixels</small>
        */ ?>
        {!! Form::label('photo','UPLOAD DE IMAGEM', ['class'=>'control-label-left col-sm-3']) !!}
        <div class="col-sm-3">
            <div id="usuario_novo_imagem_selecionada" class="form-control"></div>
        </div><!-- /.col-sm-6 -->
        <div class="col-sm-3">
            <span id="perfil_imagem_upload_bt" class="btn btn-cinza btn-pequeno btn-file btn-lateral-form">
                SELECIONAR IMAGEM   {!! Form::file('photo', ['class'=>'','accept'=>'image/*']) !!}
            </span>
        </div><!-- /.col-sm-3 -->						
        <div class="row">
            <small class="col-sm-6 col-sm-offset-6">
                <br />
                TAMANHO DA IMAGEM 100PX X 100PX  |  .PNG ou .JPG
            </small>
        </div><!-- /.row -->
    </div>

    <!--PRIMEIRO E SEGUNDO NOME-->

    <div class="form-group">
        {!! Form::label('first_name','Nome:', ['class'=>'control-label col-sm-3']) !!}
        <div id="label-names" class="info-placeholder pt7 col-sm-6">
            {{ $usuario->first_name.' '.$usuario->last_name }}
        </div>
        <div class="edit-bt col-sm-3">
            <a class="toggle-edit-block btn btn-cinza btn-pequeno btn-lateral-form"> EDITAR </a>
        </div>
        <!--bloco de edição que abre no clique do botão-->
        @include('meu_perfil/edit-blocks/edit-first-last-name')
    </div>

    <!--NOME DE USUÁRIO-->

    <div class="form-group">
        {!! Form::label('username','Nome de usuário:', ['class'=>'control-label col-sm-3']) !!}
        <div id="label-username" class="info-placeholder pt7 col-sm-6">
            {{ $usuario->username }}
        </div>
        <div class="edit-bt col-sm-3">
            <a class="toggle-edit-block btn btn-cinza btn-pequeno btn-lateral-form"> EDITAR </a>
        </div>
        <!--bloco de edição que abre no clique do botão-->
        @include('meu_perfil/edit-blocks/edit-username')
    </div>

    <!--SENHA-->

    <div class="form-group">
        {!! Form::label('first_name','Senha:', ['class'=>'control-label col-sm-3']) !!}
        <div class="info-placeholder pt7 col-sm-6"> ******** </div>
        <div class="edit-bt col-sm-3">
            <a class="toggle-edit-block btn btn-cinza btn-pequeno btn-lateral-form"> EDITAR </a>
        </div>
        <!--bloco de edição que abre no clique do botão-->
        @include('meu_perfil/edit-blocks/edit-password')
    </div>

    <!--LINKS PESSOAIS-->

    <div class="form-group">
        <?php $link_pessoal_count = 0; ?>
        {!! Form::label('first_name','Link Pessoal:', ['class'=>'control-label col-sm-3']) !!}
        <div id="label-personal-links" class="info-placeholder pt7 col-sm-6">
            @foreach ($usuario->link_pessoal as $link_pessoal)	
            {{ $usuario->link_pessoal[$link_pessoal_count] }}<br/>
            <?php $link_pessoal_count++; ?>
            @endforeach
        </div>
        <div class="edit-bt col-sm-3">
            <a class="toggle-edit-block btn btn-cinza btn-pequeno btn-lateral-form"> EDITAR </a>
        </div>
        <!--bloco de edição que abre no clique do botão-->
        @include('meu_perfil/edit-blocks/edit-links')
    </div>

    <!--E-MAIL-->

    <div class="form-group">
        {!! Form::label('first_name','E-mail:', ['class'=>'control-label col-sm-3']) !!}
        <div id="label-email" class="info-placeholder pt7 col-sm-6">
            {{ $usuario->email }}
        </div><!-- /.col-md-9 -->
        <div class="edit-bt col-sm-3">
            <a class="toggle-edit-block btn btn-cinza btn-pequeno btn-lateral-form"> EDITAR </a>
        </div>
        <!--bloco de edição que abre no clique do botão-->
        @include('meu_perfil/edit-blocks/edit-email')
    </div>

    <!--NÍVEL DE ACESSO-->

    <div class="form-group">
        {!! Form::label('first_name','Nível do usuário:', ['class'=>'control-label col-sm-3']) !!}
        <div class="info-placeholder pt7 col-sm-6">
            {{ $usuario->group()->First()->name }}
        </div><!-- /.col-md-9 -->
        <?php //<div class="edit-bt col-sm-3"><a class="toggle-edit-block btn btn-cinza btn-pequeno btn-lateral-form"> EDITAR </a></div> ?>
        <?php //@include('meu_perfil/edit-blocks/edit-access-level') ?>
    </div>

    {!! Form::close() !!}

</div><!-- /.main -->
@endsection