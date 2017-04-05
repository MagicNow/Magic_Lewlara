<nav role="navigation" class="navbar navbar-default menublog">

    <div class="navbar-header">
        <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">                    
            <span class="sr-only">Lewlara</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>                
        </button>                
    </div>

    <div id="navbarCollapse" class="collapse navbar-collapse ">
        <ul class="nav navbar-nav categoria">
            <!--LISTANDO O MENU DE CATEGORIAS -->
           @foreach($menu_categorias as $categoria)
             <li>
                    <?php $cat_ativa = ''; ?>
                @if(isset($categoria_ativa) && $categoria_ativa->id == $categoria->id)
                    <?php $cat_ativa = 'active'; ?>
                @endif
                <a href="{{ action('blog\HomeBlogController@click_categoria',array($cliente_default->slug,$categoria->slug)) }}" title="{{$categoria->name}}" class="{{$cat_ativa}}">{{ $categoria->name}}</a>

                @if($categoria->subcategoria()->count())
                    <ul class="subcategoria">
                            
                        @foreach($categoria->subcategoria as $subcategoria)
                            <?php if(!$subcategoria->post()->count()){  continue; } ?>
                            <li>
                                @if(isset($subcategoria_ativa) && $subcategoria_ativa->id == $subcategoria->id)
                                    <a title="{{$subcategoria->name}}" class="active">{{ $subcategoria->name}}</a>
                                @else
                                    <a href="{{ action('blog\HomeBlogController@click_categoria',array($cliente_default->slug,$categoria->slug, $subcategoria->slug)) }}" title="{{$subcategoria->name}}" >{{ $subcategoria->name}}</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
            @endforeach  
        
        </ul>
    </div>
</nav>