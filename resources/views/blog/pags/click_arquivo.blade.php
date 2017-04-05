<?php 
$header_title = 'Arquivos';
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
            <h4 class="titulo-conteudo" title="Arquivo">ARQUIVO</h4>
            <div class="col-xs-12 col-sm-9 col-md-9"> 
            @if (count($post_arquivos))
              @foreach(@$post_arquivos as $post_arquivo)
              <h2 class="titulo-ano" title="{{$post_arquivo[0]['ano']}}"> {{ $post_arquivo[0]['ano'] }}</h2> 
              <ul class="nav nav-sidebar">
                <ul class="list-group arquivos-blog pag-arquivos-blog">
                    @foreach(@$post_arquivo as $arquivo_mes)
                    <li class="list-group-item mes-arquivos pag-arquivos">
                        <a href="{{ action('blog\HomeBlogController@click_arquivo_mes',array($cliente_default->slug,$arquivo_mes['mes'].'-'.$arquivo_mes['ano'])) }}" class="no-item-link">
                            <span class="pag-mes-arquivo" title="{{$arquivo_mes['mes']}}">{{$arquivo_mes['mes']}}</span>
                        </a>  
                        <a href="{{action('blog\HomeBlogController@click_arquivo_mes',array($cliente_default->slug,$arquivo_mes['mes'].'-'.$arquivo_mes['ano'])) }}" class="no-item-link">
                            <span class="pag-num-mes-arquivo" title="{{$arquivo_mes['numpost']}}">{{$arquivo_mes['numpost']}}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </ul>
            @endforeach
            @endif

        </div>
        <aside class="col-xs-12 col-sm-3 col-md-3 sidebar blog-sidebar">
            @include('blog/tags')
            @include('blog/top_posts')
        </aside>	

    </div>
</div>
@endsection
