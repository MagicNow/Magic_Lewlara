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

    <div class="row bloco-tipo-1"> <!-- FIRST MAIN ROW -->
        <div class="col-sm-12 col-md-6 text-center">
            <div>
                <div>
                    <img src="{{ URL::to('/').'/img/flag-ico.jpg'}}"/><h4>POST EM DESTAQUE</h4>
                    @if($top_post)
                        <p><b>{{date("d.m.Y",strtotime($top_post->created_at))}}</b></p>
                        <p>{{ str_limit(_limpaHtmlJs($top_post->desc), 140, '...')}}</p>
                    @else 
                        <br><br>
                        <p>Nenhum post em destaque</p>
                    @endif
                </div>
                <div>
                    @if($top_post)
                        {!! link_to_action('blog\HomeBlogController@click_interna','LER POST NO SITE',array($top_post->cliente->slug,$top_post->slug),['class'=>'btn btn-amarelo btn-grande align-center','target'=>'_blank']) !!}
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 text-center">
            <div>
                <div>
                    <img src="{{ URL::to('/').'/img/star-ico.jpg'}}"/><h4>TOP POST</h4>   
                    @if($top_post)
                        <p><b>{{date("d.m.Y",strtotime($top_post->created_at))}}</b></p>
                        <p>{{ str_limit(_limpaHtmlJs($top_post->desc), 140, '...')}}</p>
                    @else 
                        <br><br>
                        <p>Nenhum top post</p>
                    @endif
                </div>
                <div>
                    @if($top_post)
                        {!! link_to_action('blog\HomeBlogController@click_interna','LER POST NO SITE',array($top_post->cliente->slug,$top_post->slug),['class'=>'btn btn-amarelo btn-grande align-center','target'=>'_blank']) !!}
                    @endif
                </div>
            </div>
        </div>
    </div><!-- /.row -->

    <div class="row bloco-tipo-2"><!-- SECOND MAIN ROW -->
        <div class="col-sm-12 col-md-6 sem-padding"><!--container-->
            <div>
                <img src="{{ URL::to('/').'/img/pin-ico.jpg'}}"/><h4>ÚLTIMOS POSTS CADASTRADOS</h4>
                @foreach (@$posts_recentes as $recent)
                    <div class="col-sm-12 ultimos-posts-cada">                                
                        @if ($recent->midia->first())
                            <div class="img-destaque-box">
                                <div class="img-destaque" style="background-image: url({{URL::to('/').'/'.$recent->midia->first()->imagem}});"></div>
                            </div>
                        @else 
                            <div class="img-destaque-box">
                                <div class="sem-img-destaque"></div>
                            </div>
                        @endif
                        
                        <div class="posts-detalhes">
                            <p><b>{{date("d.m.Y",strtotime($recent->created_at))}}</b></p>
                            <p>{{ str_limit(_limpaHtmlJs($recent->titulo), 30, '...')}}</p>
                            <p>{{ str_limit(_limpaHtmlJs($recent->desc), 85, '...')}}</p>
                            <p>{!! link_to_action('blog\HomeBlogController@click_interna','LER POST',array($recent->cliente->slug,$recent->slug),['class'=>'btn btn-cinza btn-pequeno align-center','target'=>'_blank']) !!}</p>
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

    @if (count($meus_ultimos_comentarios))            
        <div class="row bloco-tipo-3"><!-- FOURTH MAIN ROW -->
            <hr/>
            <img src="{{ URL::to('/').'/img/chat-ico.jpg'}}"/><h4>MEUS COMENTÁRIOS</h4>{!! link_to_action('ComentarioController@todos','VER TODOS',array(),['class'=>'btn  btn-preto btn-pequeno align-center']) !!}
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
    @endif
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