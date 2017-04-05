<?php 
$header_title = 'Busca';
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

    <div class="container-fluid">
        <div class="container-fluid conteudo-blog">
            <div class="row-fluid">
                <h4 class="titulo-conteudo">RESULTADOS DA BUSCA POR "{{ $buscar_por }}"</h4>
                <div class="col-xs-12 col-sm-9 col-md-9 conteudo-blog-posts"> 
                    <!-- LISTANDO TODOS OS POSTS-->
                    <div class="row row-conteudo">
                    @foreach($posts_cliente as $posts)   
                        <div class="media">
                            <div class="media-left media-top">
                                 <a class="link_thumb" href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$posts->slug))}}" title="@if($posts->midia->first()) {{ $posts->midia->first()->titulo}} @endif">
                                    <img class="media-object img-conteudo-post-destaque" src="@if($posts->midia->first())
                                    {{ URL::to('/').'/'.$posts->midia->first()->imagem}} 
                                    @endif" alt="...">
                                </a>
                            </div>
                            <div class="media-body">
                                <a href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$posts->slug))}}" class="no-item-link" title="{{ str_limit(_limpaHtmlJs($posts->titulo), 80, '...')}}">
                                    <h4 class="media-heading titulo-post-destaque">{{ str_limit(_limpaHtmlJs($posts->titulo), 80, '...')}} </h4>
                                </a>
                                <p class="conteudo-post-destaque"> {{str_limit(_limpaHtmlJs($posts->desc), 250, '...')}}</p>
                                <div class="btn btn-amarelo btn-medio align-center btn-top-post">
                                    <img src="{{ URL::to('/img/ico_categoria.png') }}" class="logo-cliente-slider"> 
                                        @foreach ($posts->tag as $key => $tag)
                                            @if($key < count($posts->tag)-1)
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
                                @if($posts->concorrente->first())
                                    <div class="btn btn-cinza btn-medio align-center btn-top-post">
                                        <a class="link-post" href="{{ action('blog\HomeBlogController@click_concorrente',array($cliente_default->slug,$posts->concorrente->first()->id)) }}">
                                        CONCORRENTE: 
                                                {{ mb_strtoupper($posts->concorrente->first()->name) }}
                                            </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <hr class="hr-conteudo">
                    @endforeach 
                    </div>
                    <div class="row">
                        <div class="col-sm-10">
                           
                            {!! str_replace('/?', '?', $posts_cliente->render()) !!}
                        </div><!-- /.col-sm-8 -->
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
