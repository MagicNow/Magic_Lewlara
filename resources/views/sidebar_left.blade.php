<aside class="col-sm-3 col-md-2 sidebar anula-padding">
	
	@if (Auth::user())

			
			@if (Auth::user()->is_admin())	
			<ul class="nav nav-sidebar">

				<li class="titulo"> <i class="posts"></i> POSTS</li>	
						
					<li><a href="{{ action('PostController@create') }}">Novo post</a></li>
					<li><a href="{{ action('PostController@todos') }}">Todos os posts</a></li>
					<li><a href="{{ action('PostController@destaques') }}">Posts em destaque</a></li>
					<li><a href="{{ action('TagController@edit',null) }}">Tags</a></li>
					<li><a href="{{ action('ComentarioController@todos') }}">Todos os comentários</a></li>
					<li><a href="{{ action('PostController@pdfPosts') }}">Gerar PDF</a></li>
				
			</ul>
			@endif
			
			@if (Auth::user()->is_cliente() or Auth::user()->is_usuario())	
			<ul class="nav nav-sidebar">

				<li class="titulo"> <i class="posts"></i> POSTS</li>	
					<li><a href="{{ action('PostController@todos') }}">Todos os posts</a></li>
					<li><a href="{{ action('PostController@todosDestacados') }}">Todos os posts destacados</a></li>
					<li><a href="{{ action('ComentarioController@todos') }}">Todos os comentários</a></li>
					<li><a href="{{ action('PostController@pdfPosts') }}">Gerar PDF</a></li>
			</ul>
			@endif

			@if (Auth::user()->is_admin())
			<ul class="nav nav-sidebar">
				<li class="titulo"> <i class="clientes"></i> CLIENTES</li>
				
					<li><a href="{{ action('ClienteController@create') }}">Novo cliente</a></li>
					<li><a href="{{ route('cliente_list') }}">Todos os clientes</a></li>
					<li><a href="{{ action('CategoriaController@lista') }}">Categorias / subcategorias </a></li>
					<li><a href="{{ action('TiposAcaoController@tiposAcao') }}">Tipos de ação</a></li>
					<li><a href="{{ action('ConcorrenteController@create') }}">Novo concorrente</a></li>
					<li><a href="{{ action('ConcorrenteController@todos') }}">Todos concorrentes</a></li>					
				
			</ul>
			@endif

			@if (Auth::user()->is_admin())
			<ul class="nav nav-sidebar">
				<li class="titulo"> <i class="usuarios"></i> USUÁRIOS</li>
				
					<li><a href="{{ action('UserController@create') }}">Novo usuário</a></li>
					<li><a href="{{ route('usuario_list') }}">Usuários por cliente</a></li>
					<li><a href="{{ action('UserController@resetarSenhaUsuarios') }}">Resetar senha de usuários</a></li>
					<li><a href="{{ route('usuario_informacoes_de_acesso') }}">Informações de acesso</a></li>	
					<li><a href="{{ action('NotificationController@dispararNotificacao') }}">Disparar notificação</a></li>
				
			</ul>
			@endif

			@if (Auth::user()->is_admin())
			<ul class="nav nav-sidebar">
				<li class="titulo"> <i class="newsletter"></i> NEWSLETTER</li>
				<li><a href="{{ action('NewsletterController@novaPosts') }}">Nova newsletter</a></li>
				<li><a href="{{ action('NewsletterController@lista') }}">Todas newsletters</a></li>
				<li><a href="{{ action('NewsletterController@grupos') }}">Gerenciar grupos</a></li>
			</ul>
			@endif

			<?php /* 
			<ul class="nav nav-sidebar">
				<li class="titulo"> <i class="busca"></i> BUSCA</li>
				<li><a href="#">One more nav</a></li>
				<li><a href="#">Another nav item</a></li>
			</ul>
			*/ ?>
			<ul class="nav nav-sidebar">
				<li class="titulo"> <i class="configuracoes"></i> CONFIGURAÇÕES</li>
				<?php /* <li><a href="#">Ícones</a></li> */ ?>
				@if (Auth::user()->is_admin())
				<li><a href="{{ action('ConfiguracoesController@logoEdit') }}">Atualizar logo do sistema</a></li>
				<li><a href="{{ action('ConfiguracoesController@postsComentariosEdit') }}">Posts e comentários</a></li>
				@endif
				<li><a href="{{ action('MeuPerfilController@edit') }}">Editar meu perfil</a></li>
			</ul>

	@endif
</aside>