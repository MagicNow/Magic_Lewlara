<?php 
$header_title = $mes_ano[0].' / '.$mes_ano[1];
?>
<?php
$scripts = array(
    0 => array(
        'src' => URL::to('/libs') . '/TagCanvas/js/jquery.tagcanvas.js'
        )
    );
?>
    @extends('blog/baseblog')

    @section('content')

 <div class="container-fluid conteudo-blog">
        <div class="row-fluid">
            <h4 class="titulo-conteudo" title="{{$mes_ano[0]." ".$mes_ano[1]}}">{{$mes_ano[0].' / '.$mes_ano[1]}}</h4>
            <div class="col-xs-12 col-sm-9 col-md-9 conteudo-blog-posts">
                <!-- LISTANDO POSTS POR CATEGORIA -->
                 <div class="row row-conteudo">
                @foreach($posts_mes_ano as $post_mes_ano)
                    <div class="media">
                        <div class="media-left media-top">
                            <a href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$post_mes_ano->slug))}}" title="@if($post_mes_ano->midia->first()) {{ $post_mes_ano->midia->first()->titulo}} @endif">
                                <img class="media-object img-conteudo-post-destaque" src="
                                @if($post_mes_ano->midia->first())
                                {{ URL::to('/').'/'.$post_mes_ano->midia->first()->imagem}} 
                                @endif" alt="...">
                            </a>
                        </div>
                        <div class="media-body">
                            <a href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$post_mes_ano->slug))}}" class="no-item-link" title="{{ str_limit(_limpaHtmlJs($post_mes_ano->titulo), 80, '...')}}">
                                <h4 class="media-heading titulo-post-destaque">{{ str_limit(_limpaHtmlJs($post_mes_ano->titulo), 80, '...')}} </h4>
                            </a>
                            <p class="conteudo-post-destaque"> {{str_limit(_limpaHtmlJs($post_mes_ano->desc), 250, '...')}}</p>
                            <div class="btn btn-amarelo btn-medio align-center btn-top-post">
                                <img src="{{ URL::to('/img/ico_categoria.png') }}" class="logo-cliente-slider"> 
                                    
                                    @foreach ($post_mes_ano->tag as $key => $tag)
                                        @if($key < count($post_mes_ano->tag)-1)
                                            <a class="link-post" href="{{ action('blog\HomeBlogController@click_tag',array($cliente_default->slug,$tag['slug'])) }}">
                                                {{ mb_strtoupper($tag->name) }},&nbsp;
                                            </a>
                                        @else
                                            <a class="link-post" href="{{ action('blog\HomeBlogController@click_tag',array($cliente_default->slug,$tag['slug'])) }}">
                                                {{ mb_strtoupper($tag->name)}}
                                            </a>
                                        @endif
                                    @endforeach
                            
                            </div>
                            @if($post_mes_ano->concorrente->first())
                                <div class="btn btn-cinza btn-medio align-center btn-top-post">
                                    <a class="link-post" href="{{ action('blog\HomeBlogController@click_concorrente',array($cliente_default->slug,$post_mes_ano->concorrente->first()->id)) }}">
                                    CONCORRENTE: 
                                            {{ mb_strtoupper($post_mes_ano->concorrente->first()->name) }}
                                        </a>
                                </div>
                            @endif
                        </div>

                    </div>
                   <hr class="hr-conteudo">

                
                @endforeach
                </div>
                <div class="row">
                    <div class="col-sm-12">
                      
                        {!! str_replace('/?', '?', $posts_mes_ano->render()) !!}
                    </div>
                </div>

            </div>
            <aside class="col-xs-12 col-sm-3 col-md-3 sidebar blog-sidebar">
               
                @include('blog/tags')
                @include('blog/arquivos')
                @include('blog/top_posts')
            </aside>    

        </div>
    </div>
@endsection
