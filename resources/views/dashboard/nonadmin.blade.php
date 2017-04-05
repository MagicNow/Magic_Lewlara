<?php
$header_title = 'Dashboard';
?>
@extends('base_sidebar')



@section('content')

<div id="dashboardNonAdmin" class="col-sm-9  col-md-9 main">

    <br />

    <h1>DASHBOARD</h1>
    <br />

    <div class="row sobre-linha-acima hide hidden">
    </div><!-- /.row -->

    <div class="row bloco-tipo-2"><!-- SECOND MAIN ROW -->
        <div class="col-sm-12 col-md-6 sem-padding"><!--container-->
            <div>
                <img src="{{ URL::to('/').'/img/chat-ico.jpg'}}"/><h4>MEUS COMENTÁRIOS</h4><!-- {!! link_to_action('ComentarioController@todos','VER TODOS',array(),['class'=>'btn  btn-preto btn-pequeno align-center']) !!} -->
                <br/>
                @foreach ($meus_ultimos_comentarios as $comentario)
                    <!--marcação para eventuais comentários-->
                    <div class="col-xs-12 sem-padding">
                        @if ($comentario->post->midia->first())
                            <div class="img-destaque-box">
                                <div class="img-destaque" style="background-image: url({{URL::to('/').'/'.$comentario->post->midia->first()->imagem}});"></div>
                            </div>
                        @else 
                            <div class="img-destaque-box">
                                <div class="sem-img-destaque"></div>
                            </div>
                        @endif

                        <div class="posts-detalhes">
                            <p><b>{{date("d.m.Y",strtotime($comentario->created_at))}} | {{ mb_strtoupper($comentario->post->titulo) }}</b></p>
                            <p>"{{ str_limit(_limpaHtmlJs($comentario->comentario), 120)}}".</p>                
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-sm-12 col-md-6 sem-padding"><!--container-->
            <div>
                <img src="{{ URL::to('/').'/img/star-ico.jpg'}}"/><h4>MEUS POSTS FAVORITOS</h4>{{-- {!! link_to_action('PostController@todos','VER TODOS',array(),['class'=>'btn  btn-preto btn-pequeno align-center']) !!} --}}
                <br/>
                @foreach ($posts_favoritos as $post)
                <div class="col-sm-12 ultimos-posts-cada">                                
                    @if ($post->midia->first())
                        <div class="img-destaque-box">
                            <div class="img-destaque" style="background-image: url({{URL::to('/').'/'.$post->midia->first()->imagem}});"></div>
                        </div>
                    @else 
                        <div class="img-destaque-box">
                            <div class="sem-img-destaque"></div>
                        </div>
                    @endif
                    
                    <div class="posts-detalhes">
                        <p><b>{{date("d.m.Y",strtotime($post->created_at))}}</b></p>
                        <p>{{ str_limit(_limpaHtmlJs($post->titulo), 30, '...')}}</p>
                        <p>{{ str_limit(_limpaHtmlJs($post->desc), 85, '...')}}</p>
                        <p>{!! link_to_action('blog\HomeBlogController@click_interna','LER POST',array($post->cliente->slug,$post->slug),['class'=>'btn btn-cinza btn-pequeno align-center','target'=>'_blank']) !!}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- <div class="row bloco-5">
        <hr/>
        <div class="col-sm-6">
            <div></div>
        </div>
        <div class="col-sm-6">
            <div class="col-sm-12"></div>
        </div>
    </div> -->
</div><!-- /.main -->
@endsection