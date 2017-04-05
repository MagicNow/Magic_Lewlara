<?php 
$header_title = $post->titulo;
?>
<?php
$scripts = array(
    0 => array(
    'src' => URL::to('/libs') . '/lightslider/js/lightslider.js'
    ),
    1 => array(
    'src' => URL::to('/libs') . '/TagCanvas/js/jquery.tagcanvas.js'
    )
);
?>
@extends('blog/baseblog')
@section('content')
    <div class="container-fluid">
        <div class="row destaque">
            @if(isset($anterior))
                <a id="arrow-prev" href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$anterior))}}"></a>
            @endif
            @if(isset($proximo))
                <a id="arrow-next" href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$proximo))}}"></a>
            @endif
        	 <img height='300px' src="@if($post->midia->first()) {{ URL::to('/').'/'.$post->midia->first()->imagem}}  @endif" alt="" 
             class="img-responsive destaque-img" title="@if($post->midia->first()) {{$post->midia->first()->titulo}} @endif" >            
             
        </div>
        <div class="container-fluid conteudo-blog">
            <div class="row-fluid">
                <div class="col-xs-12 col-sm-9 col-md-9 conteudo-blog-posts"> 
                    <!-- LISTANDO O POST-->
                    <div class="row row-conteudo">
                        <div class="conteudo-do-post">
                            <span class="date-post">{!! date('d/m/Y ',strtotime($post->created_at)) !!}</span>
                            <h2 class="titulo-post" title="{{$post->titulo}}">{!! $post->titulo !!} </h2>
                           
                            {!! $post->desc !!}
                        </div>
                        <div class="pagination-arrows">
                             @if(isset($anterior))
                                <a id="arrow-prev-bottom" href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$anterior))}}"></a>
                            @endif
                            @if(isset($proximo))
                                <a id="arrow-next-bottom" href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$proximo))}}"></a>
                            @endif
                        </div>
                       
                        <hr>
                             <p>
                                
                                <a href="{{$post->slug}}/actions" data-action="{{$opp}}" class="click-favorito"> 
                                    <div class="icons-icon icons-icon-favorito">&nbsp;</div>
                                    <h4 class="icons-titulo  icons-titulo-favorito">DESTACAR ESSE POST (<p class="icons-titulo-favorito icons-titulo-favorito-num">{{$post->postfavorito->count()}}</p> X FAVORITO) </h4>
                                </a>
                            </p>
                             @if($post->tag->first())
                                <div class="icons-icon icons-icon-tag"> </div>
                                <h4 class="icons-titulo">TAGS</h4>  
                                @foreach($post->tag as $tag)      
                                    <a class="btn-tag" href="{{ action('blog\HomeBlogController@click_tag',array($cliente_default->slug,$tag->slug)) }}"> {{$tag->name}}</a>
                                @endforeach
                            @endif
                            @if($post->concorrente->first())
                            <br><br>
                                <div class="icons-icon icons-icon-circle"> </div>
                                <h4 class="icons-titulo">CONCORRENTE</h4>  
                                @foreach($post->concorrente as $concorrente)      
                                    <a class="btn-tag" href="{{ action('blog\HomeBlogController@click_concorrente',array($cliente_default->slug,$concorrente->id)) }}"> {{$concorrente->name}}</a>
                                @endforeach
                            @endif
                        <hr>
                        <p> <div class="icons-icon icons-icon-comentario"> </div> <h4 class="icons-titulo">COMENTÁRIOS ({{sizeof($comentarios_post)}})</h4> 
                        </p>           
                        {{-- NOVO COMENTÁRIO --}}
                        @if($comentario_escrita == true)
                            <div clas="row">
                                <div class="col-xs-12 col-sm-2 col-md-1">
                                    @if($avatar==true && Auth::user()->photo != NULL)
                                        <img src="{{ URL::to('/upload/usuario').'/'.Auth::user()->id.'/'.Auth::user()->photo }}"class="comentario-blog-user-photo">
                                    @else
                                        <img src="{{ URL::to('/upload/usuario/photodefault/default-user.png')}}"class="comentario-blog-user-photo">
                                    @endif
                                </div>
                                <div class="col-xs-12 col-sm-10 col-md-11">
                                    <div class="comentario-blog">
                                        <textarea class="comentario-blog-tipo comentario-blog-tipo-novo comentario" data-action="novo" rows="10" id="novo" href="{{$post->slug}}/comentario">    
                                        </textarea>
                                        <span  class="mensagem"></span>
                                        <span  class="comentario-blog-caracter">CARACTERES</span>
                                        <span id="novo-limite" class="limite-caracteres comentario-blog-caracter">1578</span>   
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- COMENTÁRIOS ANTERIORES --}}
                        @if($comentarios_leitura ==true)
                        <div clas="row">
                            @foreach($comentarios_post as $comentario)
                                <div class="col-xs-12 col-sm-2 col-md-1">
                                    @if($avatar==true && $comentario->user()->first()->photo !=null)
                                        <img src="{{ URL::to('/upload/usuario').'/'.$comentario->user_id.'/'.$comentario->user()->first()->photo}}"class="comentario-blog-user-photo">
                                    @else
                                        <img src="{{ URL::to('/upload/usuario/photodefault/default-user.png')}}"class="comentario-blog-user-photo">
                                    @endif
                                </div>
                                <div class="col-xs-12 col-sm-10 col-md-11">
                                    <div class="comentario-blog">
                                        <div class="comentario-blog-tipo comentario-blog-tipo-outros" id="{{'comentario-'.$comentario->id}}">
                                            <p>{{$comentario->comentario}}</p> 
                                            @if($comentario->user_id ==Auth::user()->id)
                                                <a class="comentario-blog-btn-edit" id="{{'comentario-'.$comentario->id.'-btn'}}" name="button">EDITAR COMENTÁRIO</a>
                                            @endif
                                        </div>
                                        <span class="comentario-author">{{$comentario->user->first_name}} {{$comentario->user->last_name}} - {{ date('d/m/Y H:i:s', strtotime($comentario->created_at))}}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endif
                </div>
                
            </div>
                <aside class="col-xs-12 col-sm-3 col-md-3 sidebar blog-sidebar">
                    @include('blog/tags')
                    @include('blog/arquivos')
                    @include('blog/top_posts')
                </aside>	
            </div>
        </div>
    </div>
@endsection


