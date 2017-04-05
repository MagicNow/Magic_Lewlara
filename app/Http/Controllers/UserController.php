<?php namespace App\Http\Controllers;

use App\User;
use App\Cliente;
use App\Group;
//use App\Http\Requests;
use App\Http\Controllers\Controller;

use Request; // for Request::all()
use Redirect;
//use Illuminate\Http\Request; // for function name(Request $request) and after use  $request->all()

use Input;

use Mail;
use Auth;

use DB;

use Session;

class UserController extends Controller {


	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}


	public function usuariosPorCliente($default_ordenar_por=null,$cliente_default=null)
	{  	
		$por_pagina = 15;

		// adiciona na url o cliente
		if($cliente_default == null){
		    $cliente_default = _clienteDefault($cliente_default);
		    return Redirect::action('UserController@usuariosPorCliente', array('az', $cliente_default->slug));
		} else {
		    $cliente_default = _clienteDefault($cliente_default);
		}  

	
		$usuarios_sem_paginacao = User::clienteSlug($cliente_default->slug)->get();
		// há os dois filtros, tanto ordenar por quanto por cliente
		switch ($default_ordenar_por) {
		    case 'az':
				$usuarios = User::clienteSlug($cliente_default->slug)->orderBy('first_name','asc');
		        break;
		    case 'za':
		        $usuarios = User::clienteSlug($cliente_default->slug)->orderBy('first_name','desc');
		        break;
		    case 'data-cresc':
		        $usuarios = User::clienteSlug($cliente_default->slug)->orderBy('created_at','desc');
		        break;
		    case 'data-decresc':
		        $usuarios = User::clienteSlug($cliente_default->slug)->orderBy('created_at','asc');
		        break;
		    default:
		    	$usuarios = User::clienteSlug($cliente_default->slug)->orderBy('first_name','asc');
		}	

		$usuarios = $usuarios->paginate($por_pagina);

		// array para popular o select com os clientes
		$filtrar_por_cliente = Cliente::orderBy('name','asc')->lists('name','slug');

		// array para popular o select de ordenar por
		$ordenar_por = array(
				''=>'Ordenar por',
				'az'=>'Ordem alfabética A-Z',
				'za'=>'Ordem alfabética Z-A',
				'data-cresc'=>'Data crescente de cadastro',
				'data-decresc'=>'Data decrescente de cadastro',
			);

		// conta quantos usuários de cada grupo / nível
		$count_admin = 0;	$count_lewlara = 0;		$count_cliente = 0;
		foreach ($usuarios_sem_paginacao as $usuario){
			foreach ($usuario->group as $group){
				switch ($group->id) {
					case '1':
						 $count_admin++;
						break;
					case '2':
						 $count_lewlara++;
						break;
					case '3':
						 $count_cliente++;
						break;					
				}
		 	}
		}		

		return view('usuario.usuario_por_cliente', compact('usuarios', 'ordenar_por', 'default_ordenar_por', 'filtrar_por_cliente','default_filtrar_por_cliente', 'cliente_default', 'count_admin','count_lewlara','count_cliente'));
	} 

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function create()
	{  		

		$group_user = Auth::user()->group;
		$group_user = $group_user[0]->name;
		if($group_user == 'Super Admin'){
			$clientes_select = Cliente::orderBy('name','asc')->lists('name','id');
			$groups_select = Group::orderBy('id','asc')->lists('name','id');
		}else{
			$clientes_select = Auth::user()->cliente()->lists('name','id');
			$groups_select = Group::orderBy('id','asc')->limit(3)->lists('name','id');
		}

		$random_pass = $this->randomPassword();

		return view('usuario.create',compact('groups_select','clientes_select','random_pass'));    
	}


	public function store()
	{	

		// efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
		$this->validateWithCustomAttribute(Request::instance(),  $this->_rules('insert'), $messages=array(), $this->_customAttributes());

		// instancia classe User
		$user = new User;
		// define atributos do usuário a partir dos dados da requisição
		$user->fill(Request::all());

		// define alguns atributos manualmente
		$user->active = '1';		
	
		
		// salva os dados do usuário para disparo do e-mail
		$dados_email = array(	
			'assunto' => 'Bem-vindo ao Pulso da Comunicação!',
			'email' => $user->email,	    
		    'usuario' => $user->first_name, 
		    'senha' => Request::input('password')  //senha limpa
		);

		$errors = '';

		// salva instância salvando em banco
		if($user->save()){

			// salva foto avatar default

			$avatar_source = 'img/usuario_avatar.jpg';
			$avatar_dest = 'upload/usuario/'.$user->id.'/avatar.jpg';
			@mkdir(dirname($avatar_dest), 0777, true);
			if (copy($avatar_source, $avatar_dest)) {
			    $user->photo = 'avatar.jpg';
			}

			$user->update();

			// vincula usuario cadastrado com o grupo ( nível ) selecionado
			$user->group()->attach(Request::input('group'));

			// vincula usuario cadastrado com os clientes selecionados
			$user->cliente()->attach(Request::input('cliente'));

			$vinculado_aos_clientes = array();
			foreach (Request::input('cliente') as $input_cliente) {
				$cliente =  Cliente::find($input_cliente);
				array_push($vinculado_aos_clientes,'"'.$cliente->name.'"');				
			}
			$vinculado_aos_clientes = implode(', ',$vinculado_aos_clientes);

			registraLogAcao('Cadastrou um novo usuário "'.$user->username.'" ('.$user->first_name.' '.$user->last_name.') vinculado à '.$vinculado_aos_clientes,$id_ativo = Request::user()->id);

		
			// DISPARA E-MAIL			 parametro use para passar variáveis pra dentro da função
			Mail::send('emails.usuario_novo', $dados_email, function($message) use ($dados_email)
			{				
			    $message->to($dados_email['email'])->subject($dados_email['assunto']); //->cc('bar@example.com');
			});

			if(count(Mail::failures()) > 0){
			    $errors = 'Falha ao enviar e-mail para '.$dados_email['email'];;
			}
			
		}

		return view('usuario.mensagem_cadastrado')->with('usuario', $user);    
	}


	public function edit(User $usuario){

		$clientes_select = Cliente::orderBy('name','asc')->lists('name','id');

		$groups_select = Group::lists('name','id');

		$usuario->link_pessoal = explode('|', $usuario->link_pessoal);

		return view('usuario.edit',compact('usuario','groups_select','clientes_select'));
	}

	public function update(User $usuario)
	{

		// efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
		$this->validateWithCustomAttribute(Request::instance(),  $this->_rules('update',$usuario), $messages=array(), $this->_customAttributes());
		
		// instancia $usuario ja estanciada ( injetada ) na função

		// define atributos do usuário a partir dos dados da requisição
		$usuario->fill(Request::all());

		// se link_pessoal for array
		if(is_array(Request::input('link_pessoal'))){
			// faz trim em cada elemento do array + remove os nulos | brancos | false do array
			$link_pessoal = array_filter(array_map('trim',Request::input('link_pessoal')));
			// faz implode ( junta ) cada link separando por pipe
			$usuario->link_pessoal = implode("|", $link_pessoal);
		} else {
			// se for string faz apenas trim
			$link_pessoal = trim(Request::input('link_pessoal'));
		}

		if(Input::file('photo')){
			$file = Input::file('photo');

			$destinationPath = 'upload/usuario/'.$usuario->id.'/';

			$filename = date('Y-m-d_H-i-s').'_'.md5(uniqid("")).'.'.$file->guessExtension();
			Input::file('photo')->move($destinationPath, $filename);
			
			$usuario->photo = $filename;
		} 

		// salva os dados do usuário para disparo do e-mail
		$dados_email = array(	
			'assunto' => 'Cadastro Alterado!',
			'email' => $usuario->email,	    
		    'usuario' => $usuario->first_name
		);

		if(Request::input('password')){
			$dados_email['senha'] = Request::input('password');
		} else {
			$dados_email['senha'] = 'Não foi alterada';
		}


		$errors = '';

		// salva instância salvando em banco
		if($usuario->update()){
			$group_in_array = explode(',', Request::input('group'));
			// vincula usuario cadastrado com o grupo ( nível ) selecionado			
			$usuario->group()->sync($group_in_array);

			// vincula usuario cadastrado com os clientes selecionados
			$usuario->cliente()->sync(Request::input('cliente'));

			$vinculado_aos_clientes = array();
			foreach (Request::input('cliente') as $input_cliente) {
				$cliente =  Cliente::find($input_cliente);
				array_push($vinculado_aos_clientes,'"'.$cliente->name.'"');				
			}
			$vinculado_aos_clientes = implode(', ',$vinculado_aos_clientes);

			registraLogAcao('Editou o usuário "'.$usuario->username.'" ('.$usuario->first_name.' '.$usuario->last_name.') vinculado à '.$vinculado_aos_clientes,$id_ativo = Request::user()->id);

			if(Request::input('enviar_email')){
				// DISPARA E-MAIL			 parametro use para passar variáveis pra dentro da função
				Mail::send('emails.usuario_alterado', $dados_email, function($message) use ($dados_email)
				{				
				    $message->to($dados_email['email'])->subject($dados_email['assunto']); //->cc('bar@example.com');
				});

				if(count(Mail::failures()) > 0){
				    $errors = 'Falha ao enviar e-mail para '.$dados_email['email'];
				}
			}
		}
		
		return view('usuario.mensagem_editado')->with('usuario', $usuario);     
	}





	public function destroy(User $usuario)
	{

		$usuario->delete();

		$vinculado_aos_clientes = array();
		foreach ($usuario->cliente as $cliente) {
			array_push($vinculado_aos_clientes,'"'.$cliente->name.'"');				
		}
		$vinculado_aos_clientes = implode(', ',$vinculado_aos_clientes);

		registraLogAcao('Excluiu o usuário "'.$usuario->username.'" ('.$usuario->first_name.' '.$usuario->last_name.') vinculado à '.$vinculado_aos_clientes,$id_ativo = Request::user()->id);
	
	 
		return view('usuario.mensagem_excluido')->with('usuario', $usuario);   
	}






	private function _rules($action='insert',$usuario=null){
		// regras para validação
		if($action == 'insert'){
			return array(
				'cliente'=>'required|array',			
				'username'=>'required|min:3|unique:users,username',
				'email'=>'required|email|unique:users,email',
				'first_name'=>'required|min:3',
				'last_name'=>'required|min:3',
				'password'=>'required|min:6|confirmed'
			);
		} else if($action == 'update'){			
			return array(
				'cliente'=>'required|array',			
				'username'=>'required|min:3|unique:users,username,'.$usuario->id, //passando id do usuario a ser atualizado
				'email'=>'required|email|unique:users,email,'.$usuario->id,
				'first_name'=>'required|min:3',
				'last_name'=>'required|min:3',
				'password'=>'min:6|confirmed',
				'photo'=>'image|image_aspect:1|min:50'
			);
		}		
	}

	private function _customAttributes($acao=null){
		return array(
			'cliente' => 'Escolha o(s) Cliente(s)',
			'photo'	  => 'Upload de Imagem'
		);
	}



	public function resetarSenhaUsuarios($cliente_default=null)
	{
		$por_pagina = 15;

		// adiciona na url o cliente
		if($cliente_default == null){
		    $cliente_default = _clienteDefault($cliente_default);
		    return Redirect::action('UserController@resetarSenhaUsuarios', array($cliente_default->slug));
		} else {
		    $cliente_default = _clienteDefault($cliente_default);
		}  



		$usuarios_sem_paginacao = User::clienteSlug($cliente_default->slug)->get();
		$usuarios = User::clienteSlug($cliente_default->slug)->orderBy('first_name','asc')->paginate($por_pagina);
	

		// array para popular o select com os clientes
		$filtrar_por_cliente = Cliente::orderBy('name','asc')->lists('name','slug');

		// conta quantos usuários de cada grupo / nível
		$count_tipo_usuarios['admin'] = 0;
		$count_tipo_usuarios['lewlara'] = 0;
		$count_tipo_usuarios['cliente'] = 0;
		foreach ($usuarios_sem_paginacao as $usuario){
			foreach ($usuario->group as $group){
				switch ($group->id) {
					case '1':
						 $count_tipo_usuarios['admin']++;
						break;
					case '2':
						 $count_tipo_usuarios['lewlara']++;
						break;
					case '3':
						 $count_tipo_usuarios['cliente']++;
						break;					
				}
		 	}
		}	

		return view('usuario.reseta_senha_usuarios', compact('usuarios', 'filtrar_por_cliente', 'cliente_default', 'count_tipo_usuarios'));

	}

	public function resetarSenhaUsuariosEnvia(User $usuario)
	{	
		if(isset($_SERVER['HTTP_REFERER'])){ // verifica se veio de alguma origem ou se foi digitado no navegador
			$nova_senha = generateRandomString(8);
		
			$usuario->password = $nova_senha;

			$usuario->update();

			registraLogAcao('Reenviou / Resetou senha do usuário "'.$usuario->username.'" ('.$usuario->first_name.' '.$usuario->last_name.')',$id_ativo = Request::user()->id);

			// salva os dados do usuário para disparo do e-mail
			$dados_email = array(   
			    'email_destinatario'=> $usuario->email,
			    'assunto'=>'Senha Alterada em LewLara - '.$usuario->first_name,
			    'usuario' => $usuario->username,
			    'senha' => $nova_senha
			);
	
			// DISPARA E-MAIL            parametro use para passar variáveis pra dentro da função
			Mail::send('emails.usuario_alterado', $dados_email, function($message) use ($dados_email)
			{               
			    $message->to($dados_email['email_destinatario'])->subject($dados_email['assunto']); //->cc('bar@example.com');
			});

			if(count(Mail::failures()) > 0){
			    $errors = 'Falha ao enviar e-mail para '.$dados_email['email_destinatario'];
			}
			return redirect()->route('usuario_resetar_senha_sucesso');
		} else {
			return redirect()->action('UserController@resetarSenhaUsuarios');
		}		
	}


	public function informacoesDeAcesso($default_ordenar_por=null,$cliente_default=null)
	{  	
		$por_pagina = 15;

		// adiciona na url o cliente
		if($cliente_default == null){
		    $cliente_default = _clienteDefault($cliente_default);
		    return Redirect::action('UserController@informacoesDeAcesso', array('az', $cliente_default->slug));
		} else {
		    $cliente_default = _clienteDefault($cliente_default);
		}  

		
			
		$usuarios_sem_paginacao = User::clienteSlug($cliente_default->slug)->get();
		// há os dois filtros, tanto ordenar por quanto por cliente
		switch ($default_ordenar_por) {
		    case 'az':
				$usuarios = User::clienteSlug($cliente_default->slug)->orderBy('first_name','asc');
		        break;
		    case 'za':
		        $usuarios = User::clienteSlug($cliente_default->slug)->orderBy('first_name','desc');
		        break;
		    case 'data-cresc':
		        $usuarios = User::clienteSlug($cliente_default->slug)->orderBy('created_at','desc');
		        break;
		    case 'data-decresc':
		        $usuarios = User::clienteSlug($cliente_default->slug)->orderBy('created_at','asc');
		        break;
		    default:
		    	$usuarios = User::clienteSlug($cliente_default->slug)->orderBy('first_name','asc');					
		}	
		
		$usuarios = $usuarios->paginate($por_pagina);

		

		// array para popular o select com os clientes
		$filtrar_por_cliente = Cliente::orderBy('name','asc')->lists('name','slug');

		// array para popular o select de ordenar por
		$select_ordenar_por = _ordenarPor();


		foreach ($usuarios as $usuario) {
			$usuario->log_acoes = DB::table('log_acoes')->where('id_ativo', $usuario->id)->orderBy('created_at','desc')->skip(0)->take(10)->get();
		}

		return view('usuario.informacoes_de_acesso', compact('usuarios', 'select_ordenar_por', 'default_ordenar_por', 'cliente_default', 'filtrar_por_cliente','default_filtrar_por_cliente'));
	} 


	public function ajaxResetaFiltros()
	{ 

		// newsletter
	    if(!empty(Request::get('datas'))){
	        Session::put('filtro_data', Request::get('datas'));
	    } else {
	        Session::forget('filtro_data');
	    }

	    if(!empty(Request::get('categorias'))){
	        Session::put('filtro_categorias', Request::get('categorias'));
	    } else {
	        Session::forget('filtro_categorias');
	    }

	    if(!empty(Request::get('subcategorias'))){
	        Session::put('filtro_subcategorias', Request::get('subcategorias'));
	    } else {
	        Session::forget('filtro_subcategorias');
	    }      

	    if(!empty(Request::get('busca'))){
	        Session::put('filtro_busca', Request::get('busca'));
	    } else {
	        Session::forget('filtro_busca');
	    } 

	    // gerar pdf

	    if(!empty(Request::get('datas'))){
	        Session::put('filtro_data_pdf', Request::get('datas'));
	    } else {
	        Session::forget('filtro_data_pdf');
	    }

	    if(!empty(Request::get('categorias'))){
	        Session::put('filtro_categorias_pdf', Request::get('categorias'));
	    } else {
	        Session::forget('filtro_categorias_pdf');
	    }

	    if(!empty(Request::get('subcategorias'))){
	        Session::put('filtro_subcategorias_pdf', Request::get('subcategorias'));
	    } else {
	        Session::forget('filtro_subcategorias_pdf');
	    }      

	    if(!empty(Request::get('busca'))){
	        Session::put('filtro_busca_pdf', Request::get('busca'));
	    } else {
	        Session::forget('filtro_busca_pdf');
	    }     

	    if(!empty(Request::get('filtro_data_newsletter_lista'))){
	        Session::put('filtro_data_newsletter_lista', Request::get('filtro_data_newsletter_lista'));
	    } else {
	        Session::forget('filtro_data_newsletter_lista');
	    }
	}

	private function randomPassword() {
	    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); //turn the array into a string
	}
}
