<nav class="navbar navbar-default navbar-static-top topo-barra">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::to('/') }}"><span id='topo-barra-titulo'>LEW'LARA\TBWA</span></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">          
                <span id='topo-barra-subtitulo'>[ EXTRANET ]</span>
            </ul>
            <div class="nav navbar-nav navbar-right">
                @if(isset($cliente_default))
                    <div id="topo-escolha-o-cliente" class="topo-barra-alinhamento">
                        <span class="">Escolha a marca:</span>
                        <div class="hide-select">
                            {!! Form::select('topo_filtrar_por_cliente', $select_escolha_o_cliente, $cliente_default->slug, ['class'=>' col-xs-12 topo_filtrar_por_cliente arrow-preto-amarelo-single form-control-pequeno acaoOnChange', 'id'=>'topo_filtrar_por_cliente']) !!}
                        </div>
                    </div><!-- /.topo-barra-alinhamento -->
                @endif
                @if ($notifications_count > 0)
                    <div class="topo-barra-alinhamento topo-barra-notificacoes">
                        <a class="notificacoes-com-novas" href="{{ action('NotificationController@lista') }}">NOVAS NOTIFICAÇÕES</a>&nbsp;
                        <i>{{ $notifications_count }}</i>
                    </div>
                @else
                    <div class="topo-barra-alinhamento topo-barra-notificacoes">
                        <a class="notificacoes-sem-novas" href="{{ action('NotificationController@lista') }}">NOTIFICAÇÕES</a>&nbsp;
                    </div>
                @endif
                <div class="topo-barra-alinhamento">
                    <a id="topo-barra-perfil-dropdown-trigger"><i id="topo-barra-icone-perfil"></i> PERFIL </a> 

                    <div id="topo-barra-perfil-dropdown">
                        <a href="{{ action('MeuPerfilController@edit') }}">Meu cadastro</a>
                        <br>
                        <a href="{{ route('logout') }}">Logout</a>
                    </div><!-- /.topo-barra-perfil-dropdown -->
                </div>
                @if(isset($irBlog))
                    <a href="{{ URL::to('/') }}"id="topo-barra-btn-ir-para-site" class="btn btn-preto btn-pequeno">Ir para o admin</a>
                @else
                    <a href="{{ action('blog\HomeBlogController@index') }}"id="topo-barra-btn-ir-para-site" class="btn btn-preto btn-pequeno" target="_blank">Ir para o blog</a>
                @endif
                <a id="topo-barra-icone"></a>	
            </div>
        </div><!-- /.navbar-collapse -->
        
    </div>
</nav>