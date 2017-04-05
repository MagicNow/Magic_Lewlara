@if ($menu_tags)
<ul class="nav nav-sidebar tag-sidebar">
    <li class="menu-lateral"> 
        <i class="tags"> </i> 
        <a class="titulo-menu-lateral"  href="{{ action('blog\HomeBlogController@click_tags',$cliente_default->slug) }}" title="TAGS"> TAGS</a> 
    </li>

    <div id="tagcloud">
        @foreach($menu_tags as $key => $tag)
            @if($tag['total']>0 )
                <a href="{{ action('blog\HomeBlogController@click_tag',array($cliente_default->slug,$tag['slug'])) }}" rel="{{$tag['total']}}">{!! $tag['nome'] !!}</a>
            @endif
        @endforeach
    </div>

    


</ul>
@endif