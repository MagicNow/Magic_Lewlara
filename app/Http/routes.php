<?php

Route::get('teste_pdf', function() {
    $pdf = App::make('dompdf'); //Note: in 0.6.x this will be 'dompdf.wrapper'
    $pdf->loadHTML('<h1>Test</h1>');
    return $pdf->stream();
});

Route::get('teste_bcdiv', function() {
    echo 'Teste de bcdiv <br />';

    echo bcdiv('105', '6.55957', 3);
});

Route::get('create_groups', function() {
    $check = DB::table('groups')
            ->insert(array(
        'name' => 'Admin',
        'created_at' => date('Y-m-d H:i:s')
    ));
    if ($check) {
        echo "<br />admin success";
    } else {
        echo "<br />admin fail";
    }

    $check2 = DB::table('groups')
            ->insert(array(
        'name' => 'LewLara',
        'created_at' => date('Y-m-d H:i:s')
    ));
    if ($check2) {
        echo "<br />usuário success";
    } else {
        echo "<br />usuário fail";
    }

    $check3 = DB::table('groups')
            ->insert(array(
        'name' => 'Cliente',
        'created_at' => date('Y-m-d H:i:s')
    ));
    if ($check3) {
        echo "<br />cliente success";
    } else {
        echo "<br />cliente fail";
    }
});


// !!! PRIMEIRO RODAR O CREATE_GROUPS   !!!  //
Route::get('create_users', function() {
    $id = DB::table('users')
            ->insertGetId(array(
        'first_name' => 'Marcelo',
        'last_name' => 'Machado',
        'email' => 'mgoebelm@gmail.com',
        'username' => 'mgoebelm',
        'password' => bcrypt('123456'),
        'active' => '1',
        'created_at' => date('Y-m-d H:i:s')
    ));
    $check = DB::table('group_user')
            ->insert(array(
        'group_id' => '2', // usuario
        'user_id' => $id
    ));
    if ($check) {
        echo "<br /> Marcelo success";
    } else {
        echo "<br /> Marcelo fail";
    }


    $id = DB::table('users')
            ->insertGetId(array(
        'first_name' => 'Heitor',
        'last_name' => 'Glockner',
        'email' => 'heitor@pingg.com.br',
        'username' => 'heitor',
        'password' => bcrypt('123456'),
        'active' => '1',
        'created_at' => date('Y-m-d H:i:s')
    ));
    $check = DB::table('group_user')
            ->insert(array(
        'group_id' => '1', // admin
        'user_id' => $id
    ));
    if ($check) {
        echo "<br /> Heitor success";
    } else {
        echo "<br /> Heitor fail";
    }
});





// ROUTE BINDINGS 
// ROUTE BINDINGS 

Route::bind('usuario_id', function($usuario_id) {
    // busca o objeto usuario a partir do id
    return \App\User::findOrFail($usuario_id);
});


Route::bind('comentario_id',function($comentario_id){
    // busca o objeto comentario a partir do id
    return \App\Comentario::findOrFail($comentario_id);
});

Route::bind('cliente_slug',function($cliente_slug){

    // busca o objeto cliente a partir do slug
    return \App\Cliente::where('slug', $cliente_slug)->firstOrFail();
});

Route::bind('concorrente_id', function($concorrente_id) {
    // busca o objeto concorrente a partir do id
    return \App\Concorrente::findOrFail($concorrente_id);
});

Route::bind('categoria_id', function($categoria_id) {
    // busca o objeto Categoria a partir do id
    return \App\Categoria::findOrFail($categoria_id);
});

Route::bind('categoria_slug', function($categoria_slug) {
    // busca o objeto Categoria a partir do slug
    return \App\Categoria::where('slug',$categoria_slug)->firstOrFail();
});


Route::bind('subcategoria_id', function($subcategoria_id) {
    // busca o objeto Subcategoria a partir do id
    return \App\Subcategoria::findOrFail($subcategoria_id);
});

Route::bind('subcategoria_slug', function($subcategoria_slug) {
    // busca o objeto Subcategoria a partir do slug
    return \App\Subcategoria::where('slug',$subcategoria_slug)->firstOrFail();
});

Route::bind('post_id', function($post_id) {
    // busca o objeto post a partir do id
    return \App\Post::findOrFail($post_id);
});

Route::bind('newsletter_id', function($newsletter_id) {
    // busca o objeto post a partir do id
    return \App\Newsletter::findOrFail($newsletter_id);
});

Route::bind('newsletter_group_id',function($newsletter_group_id){
    // busca o objeto a partir do id
    return \App\Newslettergroup::findOrFail($newsletter_group_id);
});


// PÁGINA INICIAL 
// PÁGINA INICIAL 
//Route::get('/', 'DashboardController@index');

Route::get('home/{cliente_default?}', 'DashboardController@index');
Route::get('home', ['as' => 'home', 'uses' => 'DashboardController@index']);

// Através de middleware permitimos para acessar somente se user autenticado for admin
// LOGIN LOGIN 
// LOGIN LOGIN 
Route::get('login', ['as' => 'login', 'uses' => 'CustomAuth@getLogin']);
Route::post('login', ['as' => 'login', 'uses' => 'CustomAuth@postLogin']);
Route::get('logout', ['as' => 'logout', 'uses' => 'CustomAuth@getLogout']);

// SOLICITAR CADASTRO
// SOLICITAR CADASTRO        
Route::get('solicitar-cadastro', ['as' => 'solicitar-cadastro', 'uses' => 'CustomAuth@solicitarCadastro']);
Route::post('solicitar-cadastro', ['as' => 'solicitar-cadastro-post', 'uses' => 'CustomAuth@solicitarCadastroSend']);

// NOTIFICATION NOTIFICATIONS NOTIFICAÇÕES
// NOTIFICATION NOTIFICATIONS NOTIFICAÇÕES
// LISTA TODASS
Route::get('notificacoes/{cliente_default?}', ['uses' => 'NotificationController@lista', 'middleware' => 'ifAdminOrUsuarioOrCliente']);
// DISPARAR NOTIFICAÇÃO
Route::get('disparar-notificacao/{cliente_default?}', ['uses' => 'NotificationController@dispararNotificacao', 'middleware' => 'ifAdminOrUsuarioOrCliente']);
Route::post('disparar-notificacao', ['uses' => 'NotificationController@registrarNotificacao', 'middleware' => 'ifAdminOrUsuarioOrCliente']);

// POST POSTS 
// POST POSTS 

    // TODOS OS POSTS
    Route::get('post/todos/{cliente_default?}', ['uses'=>'PostController@todos', 'middleware' => 'ifAdminOrUsuarioOrCliente']);

    // TODOS OS POSTS DESTACADOS
    Route::get('post/todos-destacados/{cliente_default?}', ['uses'=>'PostController@todosDestacados', 'middleware' => 'ifAdminOrUsuarioOrCliente']);

    // AJAX  FILTROS TODOS POSTS
    Route::post('post/ajax_filtrosTodosPosts', ['uses'=>'PostController@ajaxFiltrosTodosPosts', 'middleware' => 'ifAdminOrUsuarioOrCliente']);
    
    
    // POSTS EM DESTAQUE
    Route::get('post/destaques/{cliente_default?}', ['uses'=>'PostController@destaques', 'middleware' => 'ifAdminOrUsuario']);
        // AJAX RETIRAR DESTAQUE
        Route::get('post/destaques/{post_id}/retirar', ['uses'=>'PostController@ajaxPostDestaque', 'middleware' => 'ifAdminOrUsuario', 'as'=>'post_destaque_retirar']);

    // POST UPLOAD AJAX
    Route::post('post/ajax_upload', ['uses' => 'PostController@ajaxUpload', 'middleware' => 'ifAdminOrUsuario', 'as'=>'file_upload']);
    // NOVO POST
    Route::get('post/novo/{cliente_default?}', ['uses'=>'PostController@create', 'middleware' => 'ifAdminOrUsuario']);
    Route::post('post/novo', ['uses'=>'PostController@store', 'middleware' => 'ifAdminOrUsuario']);   
    // POST requisição ajax busca Subcategoria
    Route::post('post/ajaxSubcategoria', ['uses'=>'PostController@ajaxSubcategoria', 'middleware' => 'ifAdminOrUsuarioOrCliente']);
    // POST EDITAR
    Route::get('post/{post_id}/edit/{cliente_default?}', ['uses'=>'PostController@edit', 'middleware' => 'ifAdminOrUsuario']);
    Route::put('post/{post_id}', ['uses'=>'PostController@update', 'middleware' => 'ifAdminOrUsuario']);
    // POST EXCLUIR DESTROY
    Route::get('post/{post_id}/destroy', ['as'=>'post_destroy','uses'=>'PostController@destroy', 'middleware' => 'ifAdminOrUsuario']);
    // FILTROS
    // AJAX  POST DESTROY
    Route::post('post/ajax_postDestroy', ['uses'=>'PostController@ajaxPostDestroy', 'middleware' => 'ifAdminOrUsuario']);
    // MODAL MODAIS MODALS 
        // MODAL INSERT LIBRARY
        Route::post('post/ajax_insertLibrary', ['uses'=>'PostController@ajaxInsertLibrary', 'middleware' => 'ifAdminOrUsuario']);
        // MODAL CREATE LIBRARY
        Route::post('post/ajax_createLibrary', ['uses'=>'PostController@ajaxCreateLibrary', 'middleware' => 'ifAdminOrUsuario']);
        // MODAL DEFINE LIBRARY
        Route::post('post/ajax_defineLibrary', ['uses'=>'PostController@ajaxDefineLibrary', 'middleware' => 'ifAdminOrUsuario']);
        Route::post('post/ajax_organizeLibrary', ['uses'=>'PostController@ajaxOrganizeLibrary', 'middleware' => 'ifAdminOrUsuario']);
        // MODAL UPLOAD AJAX VIA URL
        Route::post('post/ajax_upload_viaurl', ['uses'=>'PostController@ajaxUploadViaUrl', 'middleware' => 'ifAdminOrUsuario']);
        
// GERAR PDF
// GERAR PDF
        // NOVO PDF
        Route::get('post/pdf/{cliente_default?}', ['uses'=>'PostController@pdfPosts', 'middleware' => 'ifAdminOrUsuario']);
        // NOVO PDF GERAR
        Route::get('post/pdf/{cliente_default}/gerar', ['uses'=>'PostController@pdfGerar', 'middleware' => 'ifAdminOrUsuario']);
        // AJAX  FILTROS
        Route::post('post/ajax_filtrosPDF', ['uses'=>'PostController@ajaxFiltrosPDF', 'middleware' => 'ifAdminOrUsuario']);
        // AJAX SELECIONA POST SELECIONADO
        Route::post('post/ajax_postSelecionadoPDF', ['uses'=>'PostController@ajaxPostSelecionadoPDF', 'middleware' => 'ifAdminOrUsuario']);

// COMENTARIOS COMENTÁRIOS
// COMENTARIOS COMENTÁRIOS
        
        // COMENTÁRIO DESTROY EXCLUIR EXCLUSÃO
        Route::get('comentarios/{comentario_id}/destroy', ['as'=>'comentario_destroy','uses'=>'ComentarioController@destroy', 'middleware' => 'ifAdminOrUsuarioOrCliente']);

        // TODOS COMENTARIOS
        Route::get('comentarios/{ordenar_por_default?}/{cliente_default?}', ['uses'=>'ComentarioController@todos', 'middleware' => 'ifAdminOrUsuarioOrCliente']); 
         
        // EDITAR COMENTARIO
        Route::put('comentarios/{comentario_id}', ['uses'=>'ComentarioController@update', 'middleware' => 'ifAdminOrUsuarioOrCliente']);


// USUÁRIO USUARIO
// USUÁRIO USUARIO
// NOVO USUARIO    
Route::get('usuario/novo', ['as' => 'usuario_novo', 'uses' => 'UserController@create', 'middleware' => 'ifAdminOrUsuario']);
Route::post('usuario/novo', ['as' => 'usuario_novo_post', 'uses' => 'UserController@store', 'middleware' => 'ifAdminOrUsuario']);
// RESETAR SENHA
Route::get('usuario/resetar-senha/{usuario_id}/resetar', ['as' => 'usuario_resetar_senha_post', 'uses' => 'UserController@resetarSenhaUsuariosEnvia', 'middleware' => 'ifAdminOrUsuario']);
Route::get('usuario/resetar-senha/{default_ordenar_por?}/{default_filtrar_por_cliente?}', ['as' => 'usuario_resetar_senha', 'uses' => 'UserController@resetarSenhaUsuarios', 'middleware' => 'ifAdminOrUsuario']);
Route::get('usuario/resetar-senha-sucesso', [ 'as' => 'usuario_resetar_senha_sucesso', 'uses' => 'UserController@resetarSenhaUsuariosSucesso', 'middleware' => 'ifAdminOrUsuario']);
// INFORMAÇÕES DE ACESSO
Route::get('usuario/informacoes-de-acesso/{default_ordenar_por?}/{default_filtrar_por_cliente?}', ['as' => 'usuario_informacoes_de_acesso', 'uses' => 'UserController@informacoesDeAcesso', 'middleware' => 'ifAdminOrUsuario']);
// USUÁRIO EDITAR
Route::get('usuario/{usuario_id}/edit', ['as' => 'usuario_edit', 'uses' => 'UserController@edit', 'middleware' => 'ifAdminOrUsuario']);
Route::put('usuario/{usuario_id}', ['as' => 'usuario_update', 'uses' => 'UserController@update', 'middleware' => 'ifAdminOrUsuario']);
// USUÁRIO DESTROY EXCLUIR EXCLUSÃO
Route::get('usuario/{usuario_id}/destroy', ['as' => 'usuario_destroy', 'uses' => 'UserController@destroy', 'middleware' => 'ifAdminOrUsuario']);
// LISTA USUARIOS - POR SER MAIS GENÉRICO TEM QUE FICAR NO FINAL
Route::get('usuario/{default_ordenar_por?}/{default_filtrar_por_cliente?}', ['as' => 'usuario_list', 'uses' => 'UserController@usuariosPorCliente', 'middleware' => 'ifAdminOrUsuario']);

// NEWSLETTER
// NEWSLETTER

    // LISTA
    Route::get('newsletter/lista/{cliente_default?}', ['uses'=>'NewsletterController@lista', 'middleware' => 'ifAdminOrUsuario']);
    // NOVA NEWSLETTER POSTS
    Route::get('newsletter/nova/posts/{cliente_default?}', ['uses'=>'NewsletterController@novaPosts', 'middleware' => 'ifAdminOrUsuario']);
    // AJAX SELECIONA POST SELECIONADO
    Route::post('newsletter/ajax_postSelecionado', ['uses'=>'NewsletterController@ajaxPostSelecionado', 'middleware' => 'ifAdminOrUsuario']);
    // NOVA NEWSLETTER PESSOAS
    Route::get('newsletter/nova/pessoas/{cliente_default?}', ['uses'=>'NewsletterController@novaPessoas', 'middleware' => 'ifAdminOrUsuario']);
    // AJAX SELECIONA GRUPO SELECIONADO
    Route::post('newsletter/ajax_grupoSelecionado', ['uses'=>'NewsletterController@ajaxGrupoSelecionado', 'middleware' => 'ifAdminOrUsuario']);
    // AJAX SELECIONA PESSOA SELECIONADA
    Route::post('newsletter/ajax_pessoaSelecionada', ['uses'=>'NewsletterController@ajaxPessoaSelecionada', 'middleware' => 'ifAdminOrUsuario']);
    // AJAX title
    Route::post('newsletter/ajax_title', ['uses'=>'NewsletterController@ajaxTitle', 'middleware' => 'ifAdminOrUsuario']);
    // NOVA NEWSLETTER DISPARAR
    Route::get('newsletter/nova/{cliente_default?}/disparar', ['uses'=>'NewsletterController@novaDisparar', 'middleware' => 'ifAdminOrUsuario']);
 
    // AJAX  FILTROS NOVA
    Route::post('newsletter/ajax_filtros', ['uses'=>'NewsletterController@ajaxFiltros', 'middleware' => 'ifAdminOrUsuario']);
    // AJAX  FILTROS LISTA
    Route::post('newsletter/ajax_filtrosLista', ['uses'=>'NewsletterController@ajaxFiltrosLista', 'middleware' => 'ifAdminOrUsuario']);
    // AJAX NEWSLETTER DESTROY
    Route::post('newsletter/ajax_newsletterDestroy', ['uses'=>'NewsletterController@ajaxNewsletterDestroy', 'middleware' => 'ifAdminOrUsuario']);
    // AJAX MODAL VER POSTS 
    Route::post('newsletter/ajax_verPosts', ['uses'=>'NewsletterController@ajaxVerPosts', 'middleware' => 'ifAdminOrUsuario']);
    // AJAX MODAL VER PESSOAS 
    Route::post('newsletter/ajax_verPessoas', ['uses'=>'NewsletterController@ajaxVerPessoas', 'middleware' => 'ifAdminOrUsuario']);

    // LISTA GRUPOS
    Route::get('newsletter/grupos/{cliente_default?}', ['uses'=>'NewsletterController@grupos', 'middleware' => 'ifAdminOrUsuario']);
        // NOVO GRUPO
        Route::post('newsletter/grupos/novo', ['uses'=>'NewsletterController@grupoStore', 'middleware' => 'ifAdminOrUsuario']);
        // GRUPO EDIÇÃO EDIT
        Route::get('newsletter/grupos/{newsletter_group_id?}/edit', ['uses'=>'NewsletterController@grupoEdit', 'middleware' => 'ifAdminOrUsuario']);
        Route::put('newsletter/grupos/{newsletter_group_id?}', ['uses'=>'NewsletterController@grupoUpdate', 'middleware' => 'ifAdminOrUsuario']);


    // EXIBE SHOW NEWSLETTER
    Route::get('newsletter/{newsletter_id}', ['uses'=>'NewsletterController@show']);


// CLIENTE CLIENTES
// CLIENTE CLIENTES

    // CLIENTE VISÍVEL VISIVEL requisição ajax
    Route::post('cliente/ajaxClienteVisivel', ['as'=>'ajax_cliente_visivel','uses'=>'ClienteController@ajaxClienteVisivel', 'middleware' => 'ifAdminOrUsuario']);
    // CLIENTE CLIENTES EDITAR EDIÇÃO
    Route::get('cliente/{cliente_slug}/edit', ['as'=>'cliente_edit','uses'=>'ClienteController@edit', 'middleware' => 'ifAdminOrUsuario']);
    Route::patch('cliente/{cliente_slug}', ['as'=>'cliente_update','uses'=>'ClienteController@update', 'middleware' => 'ifAdminOrUsuario']);
    // CLIENTE CLIENTES EXCLUIR EXCLUSÃO
    Route::get('cliente/{cliente_slug}/destroy', ['as'=>'cliente_destroy','uses'=>'ClienteController@destroy', 'middleware' => 'ifAdminOrUsuario']);
    // CLIENTE CLIENTES NOVO CLIENTE
    Route::get('cliente/novo', ['as'=>'cliente_novo','uses'=>'ClienteController@create', 'middleware' => 'ifAdminOrUsuario']);
    Route::post('cliente/novo', ['as'=>'cliente_novo','uses'=>'ClienteController@store', 'middleware' => 'ifAdminOrUsuario']);
    // TIPOS DE AÇÃO
    // TIPOS DE AÇÃO
        // TIPOS DE AÇÃO LISTA TIPOS
        Route::get('cliente/tipos-de-acao/{default_filtrar_por_cliente?}', ['as'=>'cliente_tipos_acao', 'uses'=>'TiposAcaoController@tiposAcao', 'middleware' => 'ifAdminOrUsuario']); 
        // TIPOS DE AÇÃO CADASTRAR CADASTRO STORE
        Route::post('cliente/tipos-de-acao', ['as'=>'cliente_tipos_acao_post', 'uses'=>'TiposAcaoController@store', 'middleware' => 'ifAdminOrUsuario']); 
        // TIPOS DE AÇÃO DESTROY EXCLUIR EXCLUSÃO
        Route::get('cliente/tipos-de-acao/{tipos_acao_id}/destroy', ['as'=>'cliente_tipos_acao_destroy', 'uses'=>'TiposAcaoController@destroy', 'middleware' => 'ifAdminOrUsuario']); 
    // CONCORRENTE CONCORRENTES
    // CONCORRENTE CONCORRENTES
        // CONCORRENTE CONCORRENTES CADASTRO STORE 
        Route::get('cliente/concorrente/novo/{default_filtrar_por_cliente?}', ['as'=>'usuario_concorrente_novo','uses'=>'ConcorrenteController@create', 'middleware' => 'ifAdminOrUsuario']);
        Route::post('cliente/concorrente/novo', ['as'=>'usuario_concorrente_novo_post','uses'=>'ConcorrenteController@store', 'middleware' => 'ifAdminOrUsuario']);
        // CONCORRENTE CONCORRENTES LISTAR TODOS
        Route::get('cliente/concorrente/todos/{ordenar_por_default?}/{cliente_default?}', ['as'=>'usuario_concorrente_todos','uses'=>'ConcorrenteController@todos', 'middleware' => 'ifAdminOrUsuario']);
        // CONCORRENTE CONCORRENTES EDITAR EDIÇÃO
        Route::get('cliente/concorrente/{concorrente_id}/edit/{cliente_default?}', ['as'=>'cliente_concorrente_edit','uses'=>'ConcorrenteController@edit', 'middleware' => 'ifAdminOrUsuario']);
        Route::put('cliente/concorrente/{concorrente_id}', ['as'=>'cliente_concorrente_update','uses'=>'ConcorrenteController@update', 'middleware' => 'ifAdminOrUsuario']);
        // CONCORRENTE CONCORRENTES DESTROY EXCLUIR EXCLUSÃO
        Route::get('cliente/concorrente/{concorrente_id}/destroy', ['as'=>'cliente_concorrente_destroy','uses'=>'ConcorrenteController@destroy', 'middleware' => 'ifAdminOrUsuario']);
    // CATEGORIAS E SUBCATEGORIAS
    // CATEGORIAS E SUBCATEGORIAS
        // LISTA E CADASTRA
        Route::get('cliente/categoria/{cliente_default?}', ['uses'=>'CategoriaController@lista', 'middleware' => 'ifAdminOrUsuario']); 
        // CATEGORIA
            // CATEGORIA CADASTRA
            Route::post('cliente/categoria', ['uses'=>'CategoriaController@categoriaStore', 'middleware' => 'ifAdminOrUsuario']); 
            // CATEGORIA EDITAR EDIÇÃO
            Route::get('cliente/categoria/{categoria_id}/edit', ['uses'=>'CategoriaController@categoriaEdit', 'middleware' => 'ifAdminOrUsuario']); 
            Route::put('cliente/categoria/{categoria_id}', ['uses'=>'CategoriaController@categoriaUpdate', 'middleware' => 'ifAdminOrUsuario']);
            // CATEGORIA DESTROY EXCLUIR EXCLUSÃO
            Route::get('cliente/categoria/{categoria_id}/destroy', ['as'=>'categoria_destroy', 'uses'=>'CategoriaController@categoriaDestroy', 'middleware' => 'ifAdminOrUsuario']); 
        // SUBCATEGORIA
            // SUBCATEGORIA CADASTRA
            Route::post('cliente/subcategoria', ['as'=>'subcategoria_cadastra', 'uses'=>'CategoriaController@subcategoriaStore', 'middleware' => 'ifAdminOrUsuario']); 
            // SUBCATEGORIA EDITAR EDIÇÃO
            Route::get('cliente/subcategoria/{subcategoria_id}/edit', ['uses'=>'CategoriaController@subcategoriaEdit', 'middleware' => 'ifAdminOrUsuario']); 
            Route::put('cliente/subcategoria/{subcategoria_id}', ['uses'=>'CategoriaController@subcategoriaUpdate', 'middleware' => 'ifAdminOrUsuario']);
            // SUBCATEGORIA DESTROY EXCLUIR EXCLUSÃO
            Route::get('cliente/subcategoria/{subcategoria_id}/destroy', ['as'=>'subcategoria_destroy', 'uses'=>'CategoriaController@subcategoriaDestroy', 'middleware' => 'ifAdminOrUsuario']); 

    // CLIENTE CLIENTES LISTA TODOS CLIENTES - POR SER MAIS GENÉRICO TEM QUE FICAR NO FINAL
    Route::get('cliente/{default_ordenar_por?}', ['as'=>'cliente_list','uses'=>'ClienteController@index', 'middleware' => 'ifAdminOrUsuario']); 



// TAG TAGS 
// TAG TAGS 
Route::any('tag/success', array('uses' => 'TagController@success'));
Route::any('tag/delete/{id?}', array('uses' => 'TagController@delete'));
Route::any('tag/save/', array('uses' => 'TagController@save'));
Route::any('tag/{idclient?}/{idtag?}', array('uses' => 'TagController@edit'));


//PASSWORD REDEFINE
//PASSWORD RESET RESETAR
Route::any('redefine/email', array('uses' => 'PasswordController@email'));
Route::any('redefine/success', array('uses' => 'PasswordController@success'));
Route::any('redefine/{passtoken?}', array('uses' => 'PasswordController@main'));

//CONFIGURAÇÕES DE SISTEMA
Route::any('atualizar-logo/', array('uses' => 'ConfiguracoesController@logoEdit'));
Route::any('atualizar-logo/success', array('uses' => 'ConfiguracoesController@logoUpdate'));
Route::any('posts-comentarios/', array('uses' => 'ConfiguracoesController@postsComentariosEdit'));
Route::any('posts-comentarios/success', array('uses' => 'ConfiguracoesController@postsComentariosUpdate'));

//MEU PERFIL
Route::any('meu-perfil/', array('uses' => 'MeuPerfilController@edit'));
Route::any('meu-perfil/api/update/{object}', array('uses' => 'MeuPerfilController@ajaxMeuPerfilUpdate'));
Route::any('meu-perfil/api/get/{object}', array('uses' => 'MeuPerfilController@ajaxMeuPerfilGet'));
//Route::any('meu-perfil/success', array('uses' => 'MeuPerfilController@update'));

// ROTAS DO BLOG ROTAS DO BLOG  ROTAS DO BLOG  ROTAS DO BLOG  ROTAS DO BLOG  ROTAS DO BLOG 
// ROTAS DO BLOG ROTAS DO BLOG  ROTAS DO BLOG  ROTAS DO BLOG  ROTAS DO BLOG  ROTAS DO BLOG 
// ROTAS DO BLOG ROTAS DO BLOG  ROTAS DO BLOG  ROTAS DO BLOG  ROTAS DO BLOG  ROTAS DO BLOG 
// ROTAS DO BLOG ROTAS DO BLOG  ROTAS DO BLOG  ROTAS DO BLOG  ROTAS DO BLOG  ROTAS DO BLOG 



Route::group(['prefix' => 'blog', 'middleware' => 'ifAdminOrUsuarioOrCliente'], function() {//DEFININDO O PREFIXO QUE VEM ANTES DE TODAS AS ROTAS
    Route::get('/{cliente_default?}/categoria/{categoria_slug}/{subcategoria_slug?}',['uses'=>'blog\HomeBlogController@click_categoria']); //ROTA PARA O CLICK_CATEGORIA
    Route::get('/{cliente_default?}/tags', 'blog\HomeBlogController@click_tags'); //ROTA PARA O CLICK_TAGS
    Route::get('/{cliente_default?}/tags/{tag_slug}', 'blog\HomeBlogController@click_tag'); //ROTA PARA O CLICK_TAGS
    Route::get('/{cliente_default?}/concorrente/{concorrente_slug}', 'blog\HomeBlogController@click_concorrente'); //ROTA PARA O CLICK_TAGS
    Route::get('/{cliente_default?}/interna', 'blog\HomeBlogController@click_interna'); //ROTA PARA O CLICK_INTERNA
    Route::post('/{cliente_default?}/interna/{post_slug}/actions', ['uses'=>'blog\HomeBlogController@favoritar_post']); //ROTA PARA O CLICK_INTERNA    
    Route::post('/{cliente_default?}/interna/{post_slug}/comentario', ['uses'=>'blog\HomeBlogController@comentario']);

    
    Route::get('/{cliente_default?}/arquivo', ['uses'=>'blog\HomeBlogController@click_arquivo']); //ROTA PARA O CLICK ARQUIVO
    Route::get('/{cliente_default?}/arquivo/{mes_ano}', ['uses'=>'blog\HomeBlogController@click_arquivo_mes']); //ROTA PARA LISTAR POSTS POR MES E ANO
    Route::get('/{cliente_default?}/interna/{post_slug?}', ['uses'=>'blog\HomeBlogController@click_interna']); //interna do post
    Route::get('/{cliente_default?}/interna/{post_slug?}/{preview}', ['uses'=>'blog\HomeBlogController@click_interna'])->where('preview', 'preview');; //interna preview do post
    Route::get('/{cliente_default?}', ['as'=>'blog','uses'=>'blog\HomeBlogController@index']); 

    Route::post('/{cliente_default?}/interna/search', ['uses'=>'blog\HomeBlogController@search']); // talvez não utilizado
    Route::any('/{cliente_default}/busca/{buscar?}', ['uses'=>'blog\HomeBlogController@busca']);
});

//DASHBOARD
Route::get('/{cliente_default?}', 'DashboardController@index');


// AJAX RESETA TODOS FILTROS
Route::post('/ajax_resetaFiltros', ['uses' => 'UserController@ajaxResetaFiltros', 'middleware' => 'ifAdminOrUsuario']);


