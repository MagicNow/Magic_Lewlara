<ul class="nav nav-sidebar">
    <li class="menu-lateral" > <i class="arquivos"> </i> <a class="titulo-menu-lateral"  href="{{ action('blog\HomeBlogController@click_arquivo',array($cliente_default->slug)) }}" title="ARQUIVOS" class="no-item-link" > ARQUIVOS</a> </li>
        <ul class="list-group arquivos-blog">
            @if($menu_arquivos)
                @foreach($menu_arquivos as $arquivo_mes)
                    <li class="list-group-item mes-arquivos">
                        <a href="{{ action('blog\HomeBlogController@click_arquivo_mes',array($cliente_default->slug,$arquivo_mes['mes'].'-'.$arquivo_mes['ano'])) }}" class="no-item-link" title="{{$arquivo_mes['mes']}}"> 
                            <span class="mes-arquivo"> {{$arquivo_mes['mes']}} </span>
                        </a> 
                        <a href="{{ action('blog\HomeBlogController@click_arquivo_mes',array($cliente_default->slug,$arquivo_mes['mes'].'-'.$arquivo_mes['ano'])) }}" class="no-item-link" title="{{$arquivo_mes['numpost']}}">  
                            <span class="num-mes-arquivo">{{$arquivo_mes['numpost']}} </span>  
                        </a> 
                    </li>
                @endforeach
            @endif
        </ul>
</ul>