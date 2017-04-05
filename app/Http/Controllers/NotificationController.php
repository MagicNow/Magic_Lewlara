<?php namespace App\Http\Controllers;

use Auth;
use Redirect;
use App\Cliente;
use App\Notification;
use App\User;
use App\Post;
use Request;

class NotificationController extends Controller {


	public function __construct()
	{

	}

	public function lista($cliente_default = null)
	{
		// adiciona na url o cliente
		if($cliente_default == null){
		    $cliente_default = _clienteDefault($cliente_default);
		    return Redirect::action('NotificationController@lista', $cliente_default->slug);
		} else {
		    $cliente_default = _clienteDefault($cliente_default);
		}     

		$por_pagina = 20;

		// array para popular o select com os clientes do usuário logado
		$select_escolha_o_cliente = _selectEscolhaOCliente();

		// pega notifications

		// busca to user logado  AND // to cliente atual
		$na = Notification::where('to_user',Auth::user()->id)->where('to_cliente',$cliente_default->id);

		/*if(Auth::user()->is_admin() or Auth::user()->is_usuario()){
			// busca to cliente do user logado
			$na = $na->orWhere('to_cliente',$cliente_default->id)->where('read',0);
		}*/
		
		$notifications_paginate = $na->orderBy('id','DESC')->paginate($por_pagina);	

		$notifications = array();
		$x = 0;
		foreach ($notifications_paginate as $nt) {
			switch ($nt->type) {
				case '1-1':
					$from_user = User::find($nt->from);
					if(!$from_user){ continue; }

					$post = Post::find($nt->parametro1);
					if(!$post){ continue; }

					$notifications[$x]['icon'] = 'icon-publicou-post';
					$notifications[$x]['link'] = '';
					$notifications[$x]['desc'] = $from_user->first_name.' '.$from_user->last_name.' PUBLICOU o post <a href="'.action('blog\HomeBlogController@click_interna',array($post->cliente->slug,$post->slug)).'" target="_blank">'.$post->titulo.'</a>';
					$notifications[$x]['data-hora'] = date('d/m/Y H:i',strtotime($nt->time));
					$notifications[$x]['data'] = date('d/m/Y',strtotime($nt->time));
					break;
				case '1-2':
					$from_user = User::find($nt->from);
					if(!$from_user){ continue; }

					$post = Post::find($nt->parametro1);
					if(!$post){ continue; }

					$notifications[$x]['icon'] = 'icon-editou-post';
					$notifications[$x]['link'] = '';
					$notifications[$x]['desc'] = $from_user->first_name.' '.$from_user->last_name.' EDITOU o post <a href="'.action('blog\HomeBlogController@click_interna',array($post->cliente->slug,$post->slug)).'" target="_blank">'.$post->titulo.'</a>';
					$notifications[$x]['data-hora'] = date('d/m/Y H:i',strtotime($nt->time));
					$notifications[$x]['data'] = date('d/m/Y',strtotime($nt->time));
					break;
				default:
					# code...
					break;
			}
			$x++;
		}



		$this->notificacaoLida($cliente_default->id,Auth::user()->id);



		return view('notification.lista', compact('select_escolha_o_cliente','cliente_default','notifications_paginate','notifications')); 
	}


	private function notificacaoLida($cliente=null,$to_user=null){
		if($cliente == null or $to_user == null) { return false; }

		$affectedRows = Notification::where('to_cliente', $cliente)->where('to_user', $to_user)->update(['read' => 1]);
	}

	public function dispararNotificacao($cliente_default=null)
	{
		// adiciona na url o cliente
		if($cliente_default == null){
		    $cliente_default = _clienteDefault($cliente_default);
		    return Redirect::action('NotificationController@dispararNotificacao', $cliente_default->slug);
		} else {
		    $cliente_default = _clienteDefault($cliente_default);
		}     

		// array para popular o select com os clientes do usuário logado
		$select_escolha_o_cliente = _selectEscolhaOCliente();

		$select_assuntos = array(
							null         =>'Selecione o Assunto',
							'post-create'=>'Novo Post',
							'post-update'=>'Atualização de Post'
							);

		$select_posts = array(null=>'Selecione o Post')+Post::clienteSlug($cliente_default->slug)->lists('titulo','id');

		$pessoas = $cliente_default->user()->get();

		return view('notification.disparar_notificacao', compact('cliente_default','select_escolha_o_cliente','select_assuntos','select_posts','pessoas'));
	}

	public function registrarNotificacao()
	{
		// validação
		$this->validateWithCustomAttribute(Request::instance(), $this->_rulesNotificacao(), $this->_customMessages(), $this->_customAttributes());


		// cria notificação

		// from $post->user->id
		// to  user  $to_users
		// to  cliente  $post->cliente_id
		// parametro 1  post  $post->id
		// type = 1-1  criou post  1-2 editou post
		
		$post = Post::findOrFail(Request::get('post'));

		$cliente = Cliente::where('slug',\Session::get('clienteDefault'))->first();
		if(!count($cliente)){ 
			$errors = new \Illuminate\Support\MessageBag;
			$errors->add('Cliente', 'Cliente Inválido');
			
			return redirect()->back()->withErrors($errors); 
		}
		// array de ids to user
		$to_users = Request::get('pessoas');


		if(Request::get('assunto') == 'post-create'){
			$type = '1-1';
		} else if(Request::get('assunto') == 'post-update'){
			$type = '1-2';
		}
		
		foreach ($to_users as $to_user)
		{
		    $na = new Notification;
		    $na->from = $post->user->id;
		    $na->to_user = $to_user;
		    $na->to_cliente = $post->cliente_id;
		    $na->parametro1 = $post->id;
		    $na->type = $type;
		    $na->save();
		}

		// exibe tela sucesso
		return view('notification.mensagem_disparado');

	}

	private function _rulesNotificacao(){
		return array(
		    'assunto' => 'required',
		    'post' => 'required|exists:posts,id',
		    'pessoas' => 'required|min:1'
		);
	}

	private function _customAttributes() {
	    return array(
	        'cliente_slug' => 'Cliente',
	        'assunto' => 'Assunto',
	        'post' => 'Post',
	        'pessoas' => 'Pessoa'
	    );
	}

	private function _customMessages() {
	    return array(
	    	'pessoas.required'=> 'Selecione no mínimo 1 (uma) Pessoa.',
	    	'min'=>'Selecione no mínimo 1 (uma) Pessoa.'
	    	);
	}

}
