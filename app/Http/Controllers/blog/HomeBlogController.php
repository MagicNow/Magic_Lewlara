<?php namespace App\Http\Controllers\blog;

use App\Http\Controllers\Controller;
use App\Post;
use App\PostHistorico;
use App\Midia;
use Auth; // usado no _clienteDefault
use App\Cliente; 
use Route; 
use DB; 
use Redirect;
use App\User;
use App\Categoria;
use App\Subcategoria;
use App\Tag;
use App\PostFavorito;
use App\Config;
use App\Comentario;
use App\Concorrente;
use Input;
use Request;
use Session;

class HomeBlogController extends Controller {
	protected $cliente_default;
	protected $menu_categorias;
	protected $menu_tags;
	protected $menu_arquivos;
	protected $top_posts;
	protected $configs_geral;
	protected $loggedUser;
	/*
	|--------------------------------------------------------------------------
	| Home Blog Controller
	|--------------------------------------------------------------------------
	|
	*/
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct($cliente_default=null)
	{ 
		$route = Route::current()->parameters();
		$this->middleware('auth');

		$this->cliente_default = isset($route['cliente_default']) ? _clienteDefault($route['cliente_default']) :  _clienteDefault($this->cliente_default);
		$this->menu_categorias = _blogListarCategorias($this->cliente_default); //HELPER PARA LISTAR CATEGORIAS
		$this->menu_tags =_blogListarTags($this->cliente_default); //HELPER PARA LISTAR AS TAGS MAIS USADAS
		$this->menu_arquivos =  $this->listar_datas_posts();//METODO QUE RETORNA UM VETOR COM O MESES DO ANO ATUAL QUE CONTEM OS POSTS DO MES ANO
		$this->top_posts = $this->listar_top_posts(); //METODO PARA LISTAR TOP POSTS
		$this->loggedUser = Auth::user();
		$this->configs_geral = $this->definir_configs();


		$cliente_onchange_to_url = action('blog\HomeBlogController@index');
		view()->share('cliente_onchange_to_url', $cliente_onchange_to_url);
	}	
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{	
		//pegando a quantidade de posts por pagina definido
		$por_pagina = $this->configs_geral['posts_por_pagina'];

		//verificando se o usuario tem permissão para ver os posts
		if($this->configs_geral['posts_visibilidade'] != $this->loggedUser->group->first()->id && $this->configs_geral['posts_visibilidade'] != 0 ){ 	
 	 		return Redirect::action('DashboardController@index', $this->cliente_default->slug);	 
	 	 }
		if($this->cliente_default == null){ 	
			return Redirect::action('blog\HomeBlogController@index', $cliente_default->slug);
		}

		$posts_cliente = Post::PostsAtivos()->ClienteSlug($this->cliente_default->slug)->orderBy('publicar_em','desc')->paginate($por_pagina);
		$posts_cliente_destaque = Post::ClienteSlug($this->cliente_default ->slug)->where('destaque',1)->orderBy('publicar_em', 'desc')->take(5)->get();
		// echo "<pre>";
		// print_r($posts_cliente_destaque);exit();
		return view('blog/pags/home_blog', compact('posts_cliente_destaque','posts_cliente'))->with(array('menu_categorias'=>$this->menu_categorias, 'cliente_default'=>$this->cliente_default,'menu_tags'=>$this->menu_tags,'menu_arquivos'=> $this->menu_arquivos,'top_posts'=>$this->top_posts,'irBlog'=>'blog'));
	}
	public function search($cliente_default){

		if($this->cliente_default == null){ 	
			return Redirect::action('blog\HomeBlogController@click_categoria', array($cliente_default->slug, $categoria->slug));
		}

		$result = 'teste';

		return view('blog/pags/click_categoria',compact('result'))->with(array('menu_categorias'=>$this->menu_categorias, 'cliente_default'=>$this->cliente_default,'menu_tags' =>$this->menu_tags, 'menu_arquivos'=> $this->menu_arquivos , 'top_posts' =>$this->top_posts,'irBlog'=>'blog'));

	}

	public function busca($cliente,$buscar_por=null){
 		if($buscar_por == null){
 			if(Input::get('buscar_por','') != ''){
 				$buscar_por = Input::get('buscar_por');
 				return Redirect::action('blog\HomeBlogController@busca',array($this->cliente_default->slug,$buscar_por));
 			} else {
 				return Redirect::action('blog\HomeBlogController@index');
 			}
 		}

		//pegando a quantidade de posts por pagina definido
		$por_pagina = $this->configs_geral['posts_por_pagina'];

		//verificando se o usuario tem permissão para ver os posts
		if($this->configs_geral['posts_visibilidade'] != $this->loggedUser->group->first()->id && $this->configs_geral['posts_visibilidade'] != 0 ){ 	
 	 		return Redirect::action('DashboardController@index', $this->cliente_default->slug);	 
	 	 }
		if($this->cliente_default == null){ 	
			return Redirect::action('blog\HomeBlogController@index', $cliente_default->slug);
		}

		//$posts_cliente = Post::PostsAtivos()->ClienteSlug($this->cliente_default->slug)->orderBy('publicar_em','desc')->paginate($por_pagina);
		\DB::enableQueryLog();
		$posts_cliente = Post::PostsAtivos()->ClienteSlug($this->cliente_default->slug)
			->where(function($query) use($buscar_por){
	            $query->where('titulo','like','%'.str_replace(' ','%',$buscar_por)."%")->orWhere('desc','like','%'.str_replace(' ','%',$buscar_por)."%")->orWhere('created_at','like','%'.str_replace(' ','%',$buscar_por)."%");
	        })

	        ->orwhereHas('tag', function($q)use($buscar_por){
    			$q->where('name', 'like', '%'.str_replace(' ','%',$buscar_por)."%");
			});


		
		//print_r(\DB::getQueryLog());exit();
        $posts_cliente = $posts_cliente->orderBy('publicar_em','desc')->paginate($por_pagina);

		// echo "<pre>";
		// print_r($posts_cliente_destaque);exit();
		return view('blog/pags/busca', compact('posts_cliente_destaque','posts_cliente','buscar_por'))->with(array('menu_categorias'=>$this->menu_categorias, 'cliente_default'=>$this->cliente_default,'menu_tags'=>$this->menu_tags,'menu_arquivos'=> $this->menu_arquivos,'top_posts'=>$this->top_posts,'irBlog'=>'blog'));
	}


	public function click_categoria($cliente_default ,Categoria $categoria, Subcategoria $subcategoria_ativa){
		
		//pegando a quantidade de posts por pagina definido
		$por_pagina = $this->configs_geral['posts_por_pagina'];

		//verificando se o usuario tem permissão para ver os posts
		if($this->configs_geral['posts_visibilidade'] != $this->loggedUser->group->first()->id && $this->configs_geral['posts_visibilidade'] != 0 ){ 	
 	 		return Redirect::action('DashboardController@index', $this->cliente_default->slug);
		}

		//Adiciona na url o cliente 
		if($this->cliente_default == null){ 	
			return Redirect::action('blog\HomeBlogController@click_categoria', array($cliente_default->slug, $categoria->slug));
		}
		
		
		if(@!$subcategoria_ativa->name){
			$posts_categoria = Post::ClienteSlug($this->cliente_default->slug)
									->CategoriaSlug($categoria->slug)
									->orderBy('publicar_em', 'desc')
									->paginate($por_pagina);
			$subcategoria_ativa = null;
		} else {
			// filter using categoria and subcategory
			$posts_categoria = Post::ClienteSlug($this->cliente_default->slug)
									->CategoriaSlug($categoria->slug)
									->SubcategoriaSlug($subcategoria_ativa->slug)
									->orderBy('publicar_em', 'desc')
									->paginate($por_pagina);
		}
		

		return view('blog/pags/click_categoria',compact('posts_categoria','categoria','subcategoria_ativa'))->with(array('menu_categorias'=>$this->menu_categorias, 'cliente_default'=>$this->cliente_default,'menu_tags' =>$this->menu_tags, 'menu_arquivos'=> $this->menu_arquivos , 'top_posts' =>$this->top_posts,'irBlog'=>'blog', 'categoria_ativa' => $categoria));
	} 

	public function click_tags($cliente_default ){
		//verificando se o usuario tem permissão para ver os posts
		if($this->configs_geral['posts_visibilidade'] != $this->loggedUser->group->first()->id && $this->configs_geral['posts_visibilidade'] != 0 ){ 	
 	 		return Redirect::action('DashboardController@index', $this->cliente_default->slug);
 	 		 
	 	 }
	
		$menu_tags = $this->menu_tags;
		$listar_tags = null;
		//Array de letras para listar o click tags
		$letras = range('A', 'Z');
		$j = 0; //indice do meu array listagem de tags

			//Carregando o array com a listagem de tags
			if(count($menu_tags)>0){
				for ($i = 0; $i < count($letras); $i++){
					$cont=0; 
					foreach($menu_tags as $key =>$tags){
						if(strtoupper(substr($tags['nome'],0,1))==$letras[$i] && $tags['total'] > 0){
							if($cont==0){
										$listar_tags[$j]['letra'] =$letras[$i];
										foreach($menu_tags as $key =>$tag){
										if(strtoupper(substr($tag['nome'],0,1))==$letras[$i] && $tag['total'] > 0){	
											$listar_tags[$j]['tags'][] = array('nome' => $tag['nome'],'total'=>$tag['total'],'slug' =>$tag['slug'] );
											
										}
									}
								$j++;	
							}
							$cont=1;	
						}
					}
				}
			}
		return view('blog/pags/click_tags',compact('letras','listar_tags'))->with(array('menu_categorias'=>$this->menu_categorias, 'cliente_default'=>$this->cliente_default, 'menu_arquivos'=> $this->menu_arquivos,'top_posts' => $this->top_posts, 'menu_tags'=>$this->menu_tags,'irBlog'=>'blog'));
	}
	public function click_tag($cliente_default, $tag_slug){
		//pegando a quantidade de posts por pagina definido
		$por_pagina = $this->configs_geral['posts_por_pagina'];
		//verificando se o usuario tem permissão para ver os posts
		if($this->configs_geral['posts_visibilidade'] != $this->loggedUser->group->first()->id && $this->configs_geral['posts_visibilidade'] != 0 ){ 	
 	 		return Redirect::action('DashboardController@index', $this->cliente_default->slug);
 	 		 
	 	 }
	
		$tag = Tag::ClienteSlug($this->cliente_default->slug)->where('slug','=',$tag_slug)->with('post')->first();
		
		if(!count($tag)){ return Redirect::action('blog\HomeBlogController@index'); }
		$tag_slug = $tag['name'];
		$posts_tag = $tag->post()->ClienteSlug($this->cliente_default->slug)->orderBy('publicar_em','desc')->paginate($por_pagina);

		return view('blog/pags/click_tag',compact('posts_tag','tag_slug'))->with(array('menu_categorias'=>$this->menu_categorias, 'cliente_default'=>$this->cliente_default,'menu_tags'=>$this->menu_tags, 'menu_arquivos'=> $this->menu_arquivos , 'top_posts' =>$this->top_posts,'irBlog'=>'blog'));
	}

	public function click_concorrente($cliente_default, $concorrente_slug){
		//pegando a quantidade de posts por pagina definido
		$por_pagina = $this->configs_geral['posts_por_pagina'];
		//verificando se o usuario tem permissão para ver os posts
		if($this->configs_geral['posts_visibilidade'] != $this->loggedUser->group->first()->id && $this->configs_geral['posts_visibilidade'] != 0 ){ 	
 	 		return Redirect::action('DashboardController@index', $this->cliente_default->slug);
 	 		 
	 	 }
	
		$concorrente = Concorrente::ClienteSlug($this->cliente_default->slug)->where('id','=',$concorrente_slug)->with('post')->first();
		
		if(!count($concorrente)){ return Redirect::action('blog\HomeBlogController@index'); }
		$concorrente_slug = $concorrente['name'];
		$posts_concorrente = $concorrente->post()->ClienteSlug($this->cliente_default->slug)->orderBy('publicar_em','desc')->paginate($por_pagina);

		return view('blog/pags/click_concorrente',compact('posts_concorrente','concorrente_slug'))->with(array('menu_categorias'=>$this->menu_categorias, 'cliente_default'=>$this->cliente_default,'menu_tags'=>$this->menu_tags, 'menu_arquivos'=> $this->menu_arquivos , 'top_posts' =>$this->top_posts,'irBlog'=>'blog'));
	}

	public function click_interna($cliente_default, $post_slug, $preview=false){
		//verificando se o usuario tem permissão para ver os posts
		if($this->configs_geral['posts_visibilidade'] != $this->loggedUser->group->first()->id && $this->configs_geral['posts_visibilidade'] != 0 ){ 	
 	 		return Redirect::action('DashboardController@index', $this->cliente_default->slug);
	 	 }
	 	 $postsnew ='';
	 	if(!$preview){
	 		//pegando os post pelo slug 
			$post = Post::PostsAtivos()->ClienteSlug($this->cliente_default->slug)->where('slug','=',$post_slug)->first();
			
			if(!session('posts_categoria')){
				Session::put('posts_categoria', $post->categoria->first()->slug);
				$slug = $post->categoria->first()->slug;
			}else{
				$slug2 = $post->categoria()->where('slug','=',session('posts_categoria'))->get();
				
				if(isset($slug2->slug)){
					if(session('posts_categoria') == $slug2->slug){
						$slug = session('posts_categoria');
					}else{
						Session::put('posts_categoria', $slug2->slug);
						$slug = $slug2->slug;
					}
				}else{
					Session::put('posts_categoria', $post->categoria->first()->slug);
					$slug = $post->categoria->first()->slug;
				}
			}
			$postsnew = Post::PostsAtivos()->ClienteSlug($this->cliente_default->slug)->orderBy('publicar_em', 'desc')->CategoriaSlug($slug)->get();

			foreach ($postsnew as $key => $value) {
				//echo $post->id;echo '<br>';
				
				if($post->id == $value->id){

					if(isset($postsnew[$key-1])){
					
						$anterior = $postsnew[$key-1]->slug;
					}else{
						$anterior = Null;
					}
					if(isset($postsnew[$key+1])){
						$proximo = $postsnew[$key+1]->slug;
					}else{
						$proximo = Null;
					}
					// print_r($anterior);
					// echo '<br>';
					// print_r($proximo);
					// exit();
				}
			}
			
	 	} else {
	 		$post_id = $post_slug;
	 		//pegando os post pelo id
			$post = Post::ClienteSlug($this->cliente_default->slug)->where('id','=',$post_id)->first();

			if ($post->post_historico_id > 0) {
	            // busca rascunho em aberto para o post atual
	            $postHistorico = PostHistorico::find($post->post_historico_id);
	            $post->fill($postHistorico->toArray());
	            $post->tag = $postHistorico->tag()->get();
	            $post->tiposAcao = $postHistorico->TiposAcao()->get();
	            $post->midia = $postHistorico->midia;
	        }
	 	}
	 	if(!$post){
	 		return Redirect::action('DashboardController@index');
	 	}
	 	
		$avatar = false; // variavel booleana  que contém a opção de mostrar ou não o avatar

		//verificando se para mostrar o avatar no comentários
		if($this->configs_geral['comentarios_avatar']== "1"){
			$avatar = true;
		}
		$comentario_escrita =  false;
		if($this->configs_geral['comentarios_escrita'] == $this->loggedUser->group->first()->id || $this->configs_geral['comentarios_escrita'] == 0 ){
			$comentario_escrita= true;	
		}
		$ordem = "desc"; // variavel que contém a ordem dos comentários	
		//verificando qual a ordem para mostrar
		if($this->configs_geral['comentarios_ordem']== "mais_antigo"){
			$ordem = "asc";
		}
		//pegando os comentarios do post em questão
		$comentarios_post = $post->comentario()->orderBy('created_at',$ordem)->get();
		if($this->configs_geral['comentarios_leitura'] == $this->loggedUser->group->first()->id || $this->configs_geral['comentarios_leitura'] == 0 ){	
			$comentarios_leitura = true;				
		}else{
			$comentarios_leitura= false;
		}

		//verificando se o usuario já favoritou o post, variavel opp leva a operação que vai ser levada para view. 
		if($post->PostFavorito->where('user_id',$this->loggedUser->id)->count() > 0 ){
			$opp = 'unlike'; //se favorito opp recebe dislike
		}else{
			$opp= 'like'; //senão ela recebe like
		}
		return view('blog/pags/click_interna', compact('post','comentarios_post','anterior','proximo','comentario_escrita','avatar','comentarios_leitura','opp'))->with(array('menu_categorias'=>$this->menu_categorias, 'cliente_default'=>$this->cliente_default,'menu_tags'=>$this->menu_tags,'menu_arquivos'=> $this->menu_arquivos,'top_posts'=>$this->top_posts,'irBlog'=>'blog'));
	}
	public function click_arquivo(){
		//verificando se o usuario tem permissão para ver os posts
		if($this->configs_geral['posts_visibilidade'] != $this->loggedUser->group->first()->id && $this->configs_geral['posts_visibilidade'] != 0 ){ 	
 	 		return Redirect::action('DashboardController@index', $this->cliente_default->slug);		 
	 	 }
	 	$post_arquivos =_blogListarArquivos($this->cliente_default); // Helper para listar as datas que contem posts
	
		return view('blog/pags/click_arquivo', compact('post_arquivos'))->with(array('menu_categorias'=>$this->menu_categorias, 'cliente_default'=>$this->cliente_default,'menu_tags'=>$this->menu_tags,'top_posts' =>$this->top_posts,'irBlog'=>'blog'));
	}
	//Esse metodo serve para listar os posts por mes e ano
	public function  click_arquivo_mes($cliente_default, $mes_ano){
		//pegando a quantidade de posts por pagina definido
		$por_pagina = $this->configs_geral['posts_por_pagina'];
		//verificando se o usuario tem permissão para ver os posts
		if($this->configs_geral['posts_visibilidade'] != $this->loggedUser->group->first()->id && $this->configs_geral['posts_visibilidade'] != 0 ){ 	
 	 		return Redirect::action('DashboardController@index', $this->cliente_default->slug);
 	 	 }
		$mes_ano=explode('-', $mes_ano);
		if(count($mes_ano)>1){
			$mes = ucwords($mes_ano[0]);
			$ano=$mes_ano[1];
			$mes = _mes_paraNum($mes);
		}else{
			return Redirect::action('blog\HomeBlogController@click_arquivo', array($cliente_default->slug));
		}
		
		$posts_mes_ano = Post::ClienteSlug($this->cliente_default ->slug)->where(DB::raw('MONTH(publicar_em)'), '=', $mes)-> where(DB::raw('YEAR(publicar_em)'), '=', $ano)->orderBy('publicar_em', 'desc')->paginate($por_pagina);
		return view('blog/pags/click_arquivo_mes', compact('posts_mes_ano','mes_ano'))->with(array('menu_categorias'=>$this->menu_categorias, 'cliente_default'=>$this->cliente_default,'menu_tags'=>$this->menu_tags,'menu_arquivos'=>$this->menu_arquivos,'top_posts' =>$this->top_posts,'irBlog'=>'blog'));
	}
	//esse metedo serve para listar as datas que contem posts do ano atual
	public function listar_datas_posts(){
		$ano_atual	= date("Y"); // pegando ano atual
		$post_arquivos =_blogListarArquivos($this->cliente_default); // Helper para listar as datas que contem posts
		$menu_arquivos = null;
		if($post_arquivos != null){
			//For para carregar o Array que vai conter o meses que tem post do meu ano atual para listar no menu arquivos
			foreach ($post_arquivos as $i => $post_arquivo){

				foreach ($post_arquivo as $j => $ano_arquivo) {
					if($ano_arquivo['ano']==$ano_atual){
						//Array dos meses que tem post
						$menu_arquivos[] = array('ano' => $ano_arquivo['ano'],'mes' =>$ano_arquivo['mes'] , 'numpost' =>$ano_arquivo['numpost'] ); 
					}

				}
			}
		} else {
			$menu_arquivos = null;
		}
		
		return $menu_arquivos;
	}



	//esse metodo serve para listar os dois posts mais favoritos
	public function listar_top_posts(){
		$top_posts = null;
		$posts_mais_favoritos = $this->cliente_default->postsmaisfavoritos()->take(2);
		foreach ($posts_mais_favoritos as $key => $post_count) {
			$qtd = $post_count->count;
			$top_posts[$key] = Post::find($post_count->post_id);
			$top_posts[$key]['num_favoritos'] = $qtd;
		}
		return $top_posts;
	}
	//esse metodo serve para definir oque está visivel por página
	public function definir_configs(){
		$configs = Config::get();
		foreach ($configs as $key => $config) {
		 	$configs_geral[$config->opcao]=$config->valor;
		}
		return $configs_geral;
	 }
	//METODO PARA FAVORITAS O POST
	public function favoritar_post(){
		$action = Request::get('action');
		$route = Route::current()->parameters();
	    $post = $route['post_slug'];
		$post = Post::ClienteSlug($this->cliente_default->slug)->where('slug','=',$post)->first();
	  	
	  	if($action =="like"){
		  	if($post->PostFavorito->where('user_id', $this->loggedUser->id)->count() == 0){
			  	$postFavorito = new PostFavorito;
		        $postFavorito->user_id = $this->loggedUser->id;
		        $postFavorito->post_id = $post->id;                
		        $postFavorito->date = date('Y-m-d H:i:s');
		        $postFavorito->save();
		    }
	  	}else{
	  		
	  		$postFavorito = $post->PostFavorito->where('user_id',$this->loggedUser->id);
	  		foreach ($postFavorito as $favorito) {
	  			$favorito ->delete();
	  		}	
	  	}	  	
	}
	public function comentario(){

		$comentario['action'] = Request::get('comentario');
		$comentario['content'] = Request::get('conteudo');
		$route = Route::current()->parameters();
	    $post = $route['post_slug'];
	    $post = Post::ClienteSlug($this->cliente_default->slug)->where('slug','=',$post)->first();
	    $comentario['content']= _limpaHtmlJs($comentario['content']);

	    if($comentario['action']=="novo"){
	    	$novoComentario = new Comentario;
	    	$novoComentario->user_id = $this->loggedUser->id;
	    	$novoComentario->post_id = $post->id;
	    	$novoComentario->comentario = $comentario['content'];
	    	$novoComentario->save();
	    }else{
	    	$id = Request::get('id_comentario');
	    	$editarComentario = Comentario::find($id);
	    	$editarComentario->comentario = $comentario['content'];
	    	$editarComentario->update();

	    }


	}
	
}
