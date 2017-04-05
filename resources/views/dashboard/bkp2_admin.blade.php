<?php
$header_title = 'Dashboard';
?>
@extends('base_sidebar')



@section('content')

<div id="dashboardAdmin" class="col-sm-9  col-md-9 main">

    <br />

    <h1>DASHBOARD</h1>
    <br />
    
    <div class="row sobre-linha-acima hide hidden">
    </div><!-- /.row -->

    <div class="row bloco-tipo-1"> <!-- FIRST MAIN ROW -->

    </div><!-- /.row -->

    <div class="row bloco-tipo-2"><!-- SECOND MAIN ROW -->
              
    </div>

    <hr/>
    @if(count(@$posts_destaques))
    <div class="row"><!-- THIRD MAIN ROW -->        
        <div class="col-sm-12 col-md-12 anula-padding posts-destaques"><!--container-->
            <div>
                <img src="{{ URL::to('/').'/img/pin-ico.jpg'}}"/><h4>MEUS POSTS DE DESTAQUE</h4>{!! link_to_action('PostController@destaques','VER TODOS',array(),['class'=>'btn  btn-preto btn-pequeno align-center']) !!}

                @foreach (@$posts_destaques as $recent)
                    <div class="col-sm-12 col-md-6 ultimos-posts-cada">                                
                        @if ($recent->midia->first())
                            <div class="img-destaque-box">
                                <div class="img-destaque" style="background-image: url({{URL::to('/').'/'.$recent->midia->first()->imagem}});"></div>
                            </div>
                        @else 
                            <div class="img-destaque-box">
                                <div class="sem-img-destaque"></div>
                            </div>
                        @endif
                        
                        <div class="ultimos-posts-detalhes">
                            <p><b>{{date("d.m.Y",strtotime($recent->created_at))}}</b></p>
                            <p>{{ str_limit(_limpaHtmlJs($recent->titulo), 100, '...')}}</p>
                            <p>{{ str_limit(_limpaHtmlJs($recent->desc), 100, '...')}}</p>
                            <p>{!! link_to_action('PostController@edit','EDITAR POST',$recent->id,['class'=>'btn btn-cinza btn-pequeno align-center']) !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div> 
        <div class="row">
            <div class="col-sm-8">
                {!! str_replace('/?', '?', $posts_destaques->render()) !!}
            </div><!-- /.col-sm-8 -->                                                       
        </div><!-- /.row -->
    </div>
    @endif
    {{--  ANULADO ESTE BLOCO POR ENQUANTO --}}
      
        <div class="row dashboard-comentarios"><!-- FOURTH MAIN ROW -->
            <hr/>
            <img src="{{ URL::to('/').'/img/chat-ico.jpg'}}"/><h4>ÚLTIMOS COMENTÁRIOS</h4>{!! link_to_action('ComentarioController@todos','VER TODOS',array(),['class'=>'btn  btn-preto btn-pequeno align-center']) !!}
            <br/>
            @foreach (@$ultimos_comentarios as $comentario)
            <!--marcação para eventuais comentários-->
            <div class="col-sm-12">
                @if ($comentario->post->midia->first())
                    <div class="col-sm-12 col-md-2 text-center">
                        <div class="img-destaque" style="background-image: url({{URL::to('/').'/'.$comentario->post->midia->first()->imagem}});"></div>
                    </div>
                @else 
                    <div class="col-sm-12 col-md-2 text-center">
                        <div class="sem-img-destaque"></div>
                    </div>
                @endif

                <div class="col-sm-12 col-md-10">
                    <p><b>{{date("d.m.Y",strtotime($comentario->created_at))}} | {{ mb_strtoupper($comentario->post->titulo) }}</b></p>
                    <p>"{{ str_limit(_limpaHtmlJs($comentario->comentario), 120)}}".</p>                
                </div>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-sm-8">
                {!! str_replace('/?', '?', $ultimos_comentarios->render()) !!}
            </div><!-- /.col-sm-8 -->                                                       
        </div><!-- /.row -->
    <!-- <div class="row bloco-6">
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