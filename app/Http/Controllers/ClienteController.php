<?php namespace App\Http\Controllers;

use App\User;
use App\Cliente;
//use App\Http\Request;
use App\Http\Controllers\Controller;

use Request; // for Request::all()

//use Illuminate\Http\Request; // for function name(Request $request) and after use  $request->all()
use Input;
use Auth;

class ClienteController extends Controller {
	 

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{	

	}

	private function _selectEscolhaOCliente(){
		return Auth::user()->cliente()->orderBy('name','asc')->lists('name','slug');
	}


	private function _selectOrdenarPor(){
		// array para popular o select de ordenar por
		return array(
				''=>'Ordenar por',
				'az'=>'Ordem alfabética A-Z',
				'za'=>'Ordem alfabética Z-A',
				'data-cresc'=>'Data crescente de cadastro',
				'data-decresc'=>'Data decrescente de cadastro',
			);
	}

	private function _rules($action='insert',$cliente=null){
		// regras para validação
		if($action == 'insert'){
			return array(
				'name'=>'required|min:3',
				'link.0'=>'url',
				'link.1'=>'url',
				'link.2'=>'url',
				'link.3'=>'url',
				'link.4'=>'url',
				'link.5'=>'url',
				'link.6'=>'url',
				'link.7'=>'url',
				'link.8'=>'url',
				'link.9'=>'url',
				'link.10'=>'url',
				'link.11'=>'url',
				'link.12'=>'url',
				'slug'=>'required|unique:clientes,slug',
				'logo'=>'image|image_size:100,85',
				'active'=>'required|integer'
			);
		} else if($action == 'update'){			
			return array(
				'name'=>'required|min:3',
				'link.0'=>'url',
				'link.1'=>'url',
				'link.2'=>'url',
				'link.3'=>'url',
				'link.4'=>'url',
				'link.5'=>'url',
				'link.6'=>'url',
				'link.7'=>'url',
				'link.8'=>'url',
				'link.9'=>'url',
				'link.10'=>'url',
				'link.11'=>'url',
				'link.12'=>'url',
				'slug'=>'required|unique:clientes,slug,'.$cliente->id, //passando id do cliente a ser atualizado
				'logo'=>'image|image_size:100,85',
				'active'=>'required|integer'
			);
		}		
	}

	private function _customAttributes($acao=null){
		return array(
			'name' => 'Marca',
			'link' => 'Link',
			'slug' => 'Personalizar URL',
			'logo' => 'Logo'
		);
	}


	public function index($default_ordenar_por=null)
	{  	
		if($default_ordenar_por==null){
			$clientes = Cliente::orderBy('name','asc');
		} else {
			switch ($default_ordenar_por) {
			    case 'az':
			        $clientes = Cliente::orderBy('name','asc');
			        break;
			    case 'za':
			        $clientes = Cliente::orderBy('name','desc');
			        break;
			    case 'data-cresc':
			        $clientes = Cliente::orderBy('created_at','desc');
			        break;
			    case 'data-decresc':
			        $clientes = Cliente::orderBy('created_at','asc');
			        break;
			    default:
			    	$clientes = Cliente::orderBy('name','asc');
			}			
		}

		$clientes = $clientes->with([ 'user' => function($query) {
								$query->whereHas('group', function($subquery) {
									$subquery->where('id', 1);
									$subquery->orWhere('id', 4);
								});
							}])
							->paginate(15);


		$select_ordenar_por = $this->_selectOrdenarPor();

		return view('cliente.todos', compact('clientes', 'select_ordenar_por', 'default_ordenar_por'));
	} 

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function create()
	{  		
		return view('cliente.create');
	}


	public function store()
	{

		// efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
		$this->validateWithCustomAttribute(Request::instance(),  $this->_rules('insert'), $messages=array(), $this->_customAttributes());

		// instancia classe
		$cliente = new Cliente;
		// define atributos a partir dos dados da requisição
		$cliente->fill(Request::all());

		$cliente->slug = str_slug($cliente->slug);

		if(Input::file('logo')){
			$file = Input::file('logo');

			$destinationPath = 'upload/cliente/'.$cliente->slug.'/';

			$filename = date('Y-m-d_H-i-s').'_'.md5(uniqid("")).'.'.$file->guessExtension();
			Input::file('logo')->move($destinationPath, $filename);
			
			$cliente->logo = $filename;
		} 

		// faz trim em cada elemento do array + remove os nulos | brancos | false do array
		$link = array_filter(array_map('trim',Request::input('link')));
		// faz implode ( junta ) cada link separando por pipe
		$cliente->link = implode("|", $link);
	
		// salva instância salvando em banco
		$cliente->save();

		registraLogAcao('Cadastrou um novo cliente "'.$cliente->name.'"',$id_ativo = Request::user()->id);

		return view('cliente.mensagem_cadastrado')->with('cliente', $cliente);  
		
	}


	public function ajaxClienteVisivel()
	{  		
		if(!is_numeric(Request::input('cliente_id')) && !Request::input('cliente_id')>0){
			return 'cliente id inválido';			
		}

		$cliente = Cliente::findOrFail(Request::input('cliente_id'));

		$toupdate = array(
				'active' => Request::input('cliente_active')
			);

		if($cliente->update($toupdate)){
			if(Request::input('cliente_active') == 1){
				$status = 'visível';
			} else {
				$status = 'não visível';
			}
			registraLogAcao('Alterou visibilidade do cliente "'.$cliente->name.'" para '.$status,$id_ativo = Request::user()->id);
			return 'sucesso';
		} else {
			return 'erro';
		}

	}


	public function edit(Cliente $cliente){

		$cliente->link = explode('|', $cliente->link);

		//dd($cliente);

		return view('cliente.edit',compact('cliente'));
	}


	public function update(Cliente $cliente)
	{
		// efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
		$this->validateWithCustomAttribute(Request::instance(),  $this->_rules('update',$cliente), $messages=array(), $this->_customAttributes());

		// instancia $cliente ja estanciada ( injetada ) na função

		// define atributos a partir dos dados da requisição
		$cliente->fill(Request::all());

		$cliente->slug = str_slug($cliente->slug);

		if(Input::file('logo')){
			$file = Input::file('logo');

			$destinationPath = 'upload/cliente/'.$cliente->slug.'/';

			$filename = date('Y-m-d_H-i-s').'_'.md5(uniqid("")).'.'.$file->guessExtension();
			Input::file('logo')->move($destinationPath, $filename);
			
			$cliente->logo = $filename;
		} 

		// faz trim em cada elemento do array + remove os nulos | brancos | false do array
		$link = array_filter(array_map('trim',Request::input('link')));
		// faz implode ( junta ) cada link separando por pipe
		$cliente->link = implode("|", $link);
	
		// salva instância salvando em banco
		$cliente->update();

		registraLogAcao('Editou o cliente "'.$cliente->name.'"',$id_ativo = Request::user()->id);

		return view('cliente.mensagem_editado')->with('cliente', $cliente);  
		
	}



	public function destroy(Cliente $cliente)
	{

		$cliente->delete();

		registraLogAcao('Excluiu o cliente "'.$cliente->name.'"',$id_ativo = Request::user()->id);
	 
		return view('cliente.mensagem_excluido',compact('cliente'));		
	}
	
}
