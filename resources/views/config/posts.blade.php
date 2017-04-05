<?php
$header_title = 'Posts E Comentários';
?>
@extends('base_sidebar')



@section('content')

<div class="col-sm-9  col-md-9 main cliente">

    <br />


    <h1>POSTS E COMENTÁRIOS</h1>
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


    {!! Form::open(['action'=>'ConfiguracoesController@postsComentariosUpdate', 'class'=>'form-horizontal', 'files' => true]) !!}
    
    <h3>SOBRE OS POSTS</h3>
    
    <br/>
    <div class="form-group">
        {!! Form::label('name','- QUAL O NÚMERO DE POSTS A SER EXIBIDO NAS PÁGINAS', ['class'=>'control-label-left col-sm-6']) !!}
        <div class="col-sm-4">
          {!! Form::select('posts_por_pagina', $selects['posts_por_pagina'], $select_values['posts_por_pagina'], ['class'=>'form-control arrow-preto-amarelo-single form-control-pequeno']) !!}
        </div><!-- /.col-md-9 -->
    </div>
    <div class="form-group">
        {!! Form::label('name','- PARA QUEM OS POSTS SERÃO VISÍVEIS', ['class'=>'control-label-left col-sm-6']) !!}
        <div class="col-sm-4">
          {!! Form::select('posts_visibilidade', $selects['posts_visibilidade'], $select_values['posts_visibilidade'], ['class'=>'form-control arrow-preto-amarelo-single form-control-pequeno']) !!}
        </div><!-- /.col-md-9 -->
    </div>

    <br/>
    
    <h3>SOBRE OS COMENTÁRIOS</h3>
    
    <br/>
    
    <div class="form-group">
        {!! Form::label('name','- QUEM PODE COMENTAR NOS POSTS', ['class'=>'control-label-left col-sm-6']) !!}
        <div class="col-sm-4">
          {!! Form::select('comentarios_escrita', $selects['comentarios_escrita'], $select_values['comentarios_escrita'], ['class'=>'form-control arrow-preto-amarelo-single form-control-pequeno']) !!}
        </div><!-- /.col-md-9 -->
    </div>

    <div class="form-group">
        {!! Form::label('name','- PARA QUEM OS COMENTÁRIOS ESTÃO VISÍVEIS', ['class'=>'control-label-left col-sm-6']) !!}
        <div class="col-sm-4">
          {!! Form::select('comentarios_leitura', $selects['comentarios_leitura'], $select_values['comentarios_leitura'], ['class'=>'form-control arrow-preto-amarelo-single form-control-pequeno']) !!}
        </div><!-- /.col-md-9 -->
    </div>

    <div class="form-group">
        {!! Form::label('name','- EM QUAL ORDEM OS COMENTÁRIOS APARECEM', ['class'=>'control-label-left col-sm-6']) !!}
        <div class="col-sm-4">
          {!! Form::select('comentarios_ordem', $selects['comentarios_ordem'], $select_values['comentarios_ordem'], ['class'=>'form-control arrow-preto-amarelo-single form-control-pequeno']) !!}
        </div><!-- /.col-md-9 -->
    </div>

    <div class="form-group">
        {!! Form::label('name','- MOSTRAR O AVATAR NOS COMENTÁRIOS', ['class'=>'control-label-left col-sm-6']) !!}
        <div class="col-sm-4">
          {!! Form::select('comentarios_avatar', $selects['comentarios_avatar'], $select_values['comentarios_avatar'], ['class'=>'form-control arrow-preto-amarelo-single form-control-pequeno']) !!}
        </div><!-- /.col-md-9 -->
    </div>


    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            {!! Form::submit('SALVAR ATUALIZAÇÕES', ['class'=>'btn btn-amarelo btn-medio align-center pull-right']) !!}
        </div><!-- /.col-sm-6 col-sm-offset-3 -->
    </div><!-- /.form-group -->

    {!! Form::close() !!}
</div><!-- /.main -->
@endsection