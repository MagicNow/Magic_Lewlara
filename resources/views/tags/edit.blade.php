<?php
$header_title = 'Cadastrar Tag';
?>
@extends('base_sidebar')

@section('content')

<div class="col-sm-9  col-md-9 main cliente">
    <br/>
    <h1>
        <img class="tag-client-img" src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
        TAGS
    </h1>
    
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    {!! Form::open(['id'=>'tag-main-form','method' => 'POST', 'action'=>'TagController@save', 'class'=>'form-horizontal', 'files' => true]) !!}
       
        <br/>
        <div class="tag-main-block">
            <div class="tag-cadastrar-column col-sm-12 col-md-6">
                <p class="col-sm-8 sobre-linha">CADASTRAR NOVA TAG</p>
                <div class="form-horizontal tag-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="tag-name" class="control-label">Nova Tag</label>
                        <div class="col-sm-6">
                            {!! Form::text('name', $tag->name,['class'=>'form-control','id'=>'tag-name']); !!}
                        </div><!-- /.col-md-9 -->
                    </div>
                    <div class="submit-row">
                        {!! Form::submit($submitText, ['name'=>'cadastrar','class'=>'btn btn-amarelo btn-medio align-center']) !!}
                    </div>
                </div>
            </div>
            <div class="tag-list-column col-md-6 col-sm-12">
                <p class=" sobre-linha">TAGS CADASTRADAS</p>
                <ul class="tag-list">
                    @foreach ($tagList as $tg)
                    <li>
                        {{ $tg->name }}
                        <a class='tag-edit' href="{{ URL::to('/tag').'/'.$tg->cliente->slug.'/'.$tg->id }}"></a>
                        <a class='tag-delete' href="{{ URL::to('/tag/delete').'/'.$tg->id }}"></a>
                    </li>
                   @endforeach
                </ul>
                {!! str_replace('/?', '?', $tagList->render()) !!}
            </div>
        </div>
        
        {!! Form::token(); !!}
        {!! Form::hidden('tag_id',$tag->id); !!}
        {!! Form::hidden('client_id',$cliente_default->id); !!}
    {!! Form::close() !!}
</div>

@endsection
