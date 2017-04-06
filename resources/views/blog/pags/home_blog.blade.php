<?php 
$header_title = 'Home Blog';
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
        <div class="row-fluid">
            <div class="slider hasActive">
                <div class="lSSlideOuter ">
                    <div class="lSSlideWrapper usingCss">
                        <ul id="adaptive" class="gallery content-slider list-unstyled clearfix lightSlider lSSlide lsGrab">
                            <!--LISTANDO OS POSTS EM DESTAQUE PARA O SLIDER DA HOME-->
                            @foreach ($posts_cliente_destaque as $post_destaque)
                            <li class="islide">
                                <a class="titulo-slider-item" href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$post_destaque->slug))}}">
                                    <img src="@if($post_destaque->midia->first()){{ URL::to('/').'/'.$post_destaque->midia->first()->imagem}}@endif" class="img-responsive img-slider">
                                    <span>
                                       <!--  <p class="empresa-titulo-slider">
                                            <img src="{{ URL::to('/img/ico_categoria.png') }}" class="logo-cliente-slider"> 
                                           
                                            @foreach ($post_destaque->tag as $key => $tag)
                                                @if($key < count($post_destaque->tag)-1)
                                                    {{ mb_strtoupper($tag->name) }},&nbsp; 
                                                @else
                                                    {{ ' '.mb_strtoupper($tag->name)}}
                                                @endif
                                            @endforeach

                                        </p> -->
                                            <p class="date-post">{!! date('d/m/Y',strtotime($post_destaque->created_at)) !!}</p>
                                            <p class="titulo-slider">{{ str_limit(_limpaHtmlJs($post_destaque->titulo), 110, '...') }}</p>
                                        
                                            <!-- <p class="post-descricao">{{str_limit(_limpaHtmlJs($post_destaque->desc), 100, '...')}}</p> -->
                                        
                                    </span>
                                </a>
                            </li>
                            @endforeach  
                        </ul>
                    </div>
                </div>	
            </div>
        </div>
        <div class="container-fluid conteudo-blog">
            <div class="row-fluid">
                <div class="col-xs-12 col-sm-9 col-md-9 conteudo-blog-posts"> 
                    <!-- LISTANDO TODOS OS POSTS-->
                    <div class="row row-conteudo">
                    @foreach($posts_cliente as $posts)   
                        <div class="media">
                            <div class="media-left media-top">
                                 <a class="link_thumb" href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$posts->slug))}}" title="@if($posts->midia->first()) {{ $posts->midia->first()->titulo}} @endif">
                                    <div class="media-object img-conteudo-post-destaque" style="background-image: url('{{ URL::to('/').'/'.$posts->midia->first()->imagem}}');"></div>

                                    {{-- <img class="media-object img-conteudo-post-destaque" src="@if($posts->midia->first())
                                    {{ URL::to('/').'/'.$posts->midia->first()->imagem}} 
                                    @endif" alt="..."> --}}
                                </a>
                            </div>
                            <div class="media-body">
                                <a href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$posts->slug))}}" class="no-item-link" title="{{ str_limit(_limpaHtmlJs($posts->titulo), 80, '...')}}">
                                    <p class="date-post">{!! date('d/m/Y',strtotime($posts->created_at)) !!}</p>
                                    <h4 class="media-heading titulo-post-destaque">{{ str_limit(_limpaHtmlJs($posts->titulo), 80, '...')}} </h4>
                                </a>
                                <p class="conteudo-post-destaque"> {{str_limit(_limpaHtmlJs($posts->desc, false, true), 220, '...')}}</p>
                                <div class="btn btn-amarelo btn-medio align-center btn-top-post">
                                    <img src="{{ URL::to('/img/ico_categoria.png') }}" class="logo-cliente-slider"> 
                                        
                                        @foreach ($posts->tag as $key => $tag)
                                            @if($key < count($posts->tag)-1)
                                                <a class="link-post" href="{{ action('blog\HomeBlogController@click_tag',array($cliente_default->slug,$tag['slug'])) }}">
                                                    {{ mb_strtoupper($tag->name) }},&nbsp;
                                                </a>
                                            @else
                                                <a class="link-post" href="{{ action('blog\HomeBlogController@click_tag',array($cliente_default->slug,$tag['slug'])) }}">
                                                    {{ ' '.mb_strtoupper($tag->name)}}
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
