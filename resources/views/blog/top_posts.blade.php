@if ($top_posts)
{{-- <ul class="nav nav-sidebar">
    <li class="menu-lateral"> <i class="top-posts"></i> <a class="titulo-menu-lateral" href="#" title="TOP POSTS" class="no-item-link"> TOP POSTS</a> </li>
    <div class="row row-top-posts">

    @foreach($top_posts as $top_post)
        
            <div class="col-xs-12 col-sm-12 col-md-12">
                <a href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$top_post->slug))}}"title="@if($top_post->midia->first()) {{ $top_post->midia->first()->titulo}} @endif">   
                    <img  class="img-top-posts" src="@if($top_post->midia->first()) {{ URL::to('/').'/'.$top_post->midia->first()->imagem}} @endif" alt="">
                </a>
                <div class="caption">
                    <a href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$top_post->slug))}}" class="no-item-link" title="{{ str_limit(_limpaHtmlJs($top_post->titulo), 50, '...') }}">
                        <h3 class="titulo-top-posts">{{ str_limit(_limpaHtmlJs($top_post->titulo), 50, '...') }}</h3>
                    </a>
                    <i class="icone-top-posts"> </i>
                      <a href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$top_post->slug))}}" class="no-item-link" title="{{ str_pad($top_post->num_favoritos, 2, "0", STR_PAD_LEFT) }}">
                        <p class="num-top-posts">{{ str_pad($top_post->num_favoritos, 2, "0", STR_PAD_LEFT) }}</p>
                      </a>
                </div>    
            </div>
    @endforeach 
    </div> 
</ul> --}}
@endif