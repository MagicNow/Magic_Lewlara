<?php namespace App\Http\Controllers;


use App\Cliente;

use App\Http\Controllers\Controller;

use Request; // for Request::all()
use Redirect;
use App\Concorrente;

use Input;
use Auth;

class ConcorrenteController extends Controller {
	 

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{	

	}



	public function todos($ordenar_por_default=null,$cliente_default=null)
	{
		$por_pagina = 15;
		// adiciona na url o cliente
		if($cliente_default == null){
		    $cliente_default = _clienteDefault($cliente_default);
		    return Redirect::action('ConcorrenteController@todos', array('az', $cliente_default->slug));
		} else {
		    $cliente_default = _clienteDefault($cliente_default);
		}  


		$select_ordenar_por = $this->_ordenarPor();

		// busca objectos concorrentes relativos ao cliente definido
		//$concorrentes = Concorrente::clienteSlug($cliente_default->slug)->orderBy('name','asc')->paginate($por_pagina);
		$concorrentes = Concorrente::clienteSlug($cliente_default->slug);

		if($ordenar_por_default==null){
			$concorrentes = $concorrentes->orderBy('name','asc');
		} else {
			switch ($ordenar_por_default) {
			    case 'az':
			        $concorrentes = $concorrentes->orderBy('name','asc');
			        break;
			    case 'za':
			        $concorrentes = $concorrentes->orderBy('name','desc');
			        break;
			    case 'data-cresc':
			        $concorrentes = $concorrentes->orderBy('created_at','desc');
			        break;
			    case 'data-decresc':
			        $concorrentes = $concorrentes->orderBy('created_at','asc');
			        break;
			    default:
			    	$concorrentes = $concorrentes->orderBy('name','asc');
			}			
		}

		$concorrentes = $concorrentes->paginate($por_pagina);

		foreach ($concorrentes as $concorrente) {
			$concorrente->link = explode('|', $concorrente->link);
		}
		
		// array para popular o select com os clientes do usuário logado
		$filtrar_por_cliente = Auth::user()->cliente()->orderBy('name','asc')->lists('name','slug');

		return view('concorrente.todos', compact('concorrentes','cliente_default','ordenar_por_default','select_ordenar_por','filtrar_por_cliente'));
	}

	public function create($cliente_default=null)
	{
		$cliente_default = _clienteDefault($cliente_default);

		// array para popular o select com os clientes do usuário logado
		$filtrar_por_cliente = Auth::user()->cliente()->orderBy('name','asc')->lists('name','slug');

		return view('concorrente.create',compact('cliente_default','filtrar_por_cliente','clientes_select'));    
	}

	public function store()
	{
		// efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
		$this->validateWithCustomAttribute(Request::instance(),  $this->_rules('insert'), $messages=array(), $this->_customAttributes());
		// instancia objecto
		$concorrente = new Concorrente;
		// popula objeto com os campos do formulario
		$concorrente->fill(Request::all());

		if(is_array(Request::input('link'))){
			// faz trim em cada elemento do array + remove os nulos | brancos | false do array
			$link = array_filter(array_map('trim',Request::input('link')));
			// faz implode ( junta ) cada link separando por pipe
			$concorrente->link = implode("|", $link);
		} else {
			// se for string faz apenas trim
			$concorrente->link = trim(Request::input('link'));
		}

		if(Input::file('logo')){
			$file = Input::file('logo');

			$destinationPath = 'upload/concorrente/';

			$filename = date('Y-m-d_H-i-s').'_'.md5(uniqid("")).'.'.$file->guessExtension();
			Input::file('logo')->move($destinationPath, $filename);
			
			$concorrente->logo = $filename;
		} 

		// busca o cliente a ser associado a partir do id passado no form
		$cliente = Cliente::findOrFail(Request::input('cliente_id'));
		// cria associação do cliente na tabela concorrentes
		$concorrente->cliente()->associate($cliente);
		// salva
		$concorrente->save();

		return view('concorrente.mensagem_cadastrado')->with('concorrente', $concorrente); 
	}

	public function edit(Concorrente $concorrente, $cliente_default=null){

		if($cliente_default == null){
			$cliente_default = _clienteDefault($cliente_default = $concorrente->cliente->slug);
		} else {
			$cliente_default = _clienteDefault($cliente_default);
		}

		// array para popular o select com os clientes do usuário logado
		$filtrar_por_cliente = Auth::user()->cliente()->orderBy('name','asc')->lists('name','slug');

		$concorrente->link = explode('|', $concorrente->link);

		return view('concorrente.edit',compact('concorrente','cliente_default','filtrar_por_cliente','clientes_select'));
	}

	public function update(Concorrente $concorrente)
	{
		// efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
		$this->validateWithCustomAttribute(Request::instance(),  $this->_rules('update',$concorrente), $messages=array(), $this->_customAttributes());
	
		// popula objeto com os campos do formulario
		$concorrente->fill(Request::all());

		if(is_array(Request::input('link'))){
			// faz trim em cada elemento do array + remove os nulos | brancos | false do array
			$link = array_filter(array_map('trim',Request::input('link')));
			// faz implode ( junta ) cada link separando por pipe
			$concorrente->link = implode("|", $link);
		} else {
			// se for string faz apenas trim
			$concorrente->link = trim(Request::input('link'));
		}

		if(Input::file('logo')){
			$file = Input::file('logo');

			$destinationPath = 'upload/concorrente/';

			$filename = date('Y-m-d_H-i-s').'_'.md5(uniqid("")).'.'.$file->guessExtension();
			Input::file('logo')->move($destinationPath, $filename);
			
			$concorrente->logo = $filename;
		} 

		// busca o cliente a ser associado a partir do id passado no form
		$cliente = Cliente::findOrFail(Request::input('cliente_id'));
		// cria associação do cliente na tabela concorrentes
		$concorrente->cliente()->associate($cliente);
		// atualiza
		$concorrente->update();

		return view('concorrente.mensagem_editado')->with('concorrente', $concorrente); 
	}

	public function destroy($concorrente_id)
	{
		$concorrente = Concorrente::findOrFail($concorrente_id);
		
		$concorrente->delete();		

		return view('concorrente.mensagem_excluido')->with('concorrente', $concorrente);  
	}

	private function _ordenarPor(){
		// array para popular o select de ordenar por
		return array(
				''=>'Ordenar por',
				'az'=>'Ordem alfabética A-Z',
				'za'=>'Ordem alfabética Z-A',
				'data-cresc'=>'Data crescente de cadastro',
				'data-decresc'=>'Data decrescente de cadastro',
			);
	}

	private function _rules($action='insert',$concorrente=null){
		// regras para validação
		if($action == 'insert'){
			return array(
				'name'=>'required|min:3|unique:tipos_acao,name',
				'link.0'=>'required|url',
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
				'logo'=>'required|image|image_size:100,85',
				'cliente_id'=>'required|exists:clientes,id'
			);
		} else if($action == 'update'){			
			return array(
				'name'=>'required|min:3|unique:tipos_acao,name,'.$concorrente->id,	
				'link.0'=>'required|url',
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
				'logo'=>'image|image_size:100,85',
				'cliente_id'=>'required|exists:clientes,id'
			);
		}		
	}

	private function _customAttributes($acao=null){
		return array(
			'cliente_id' => 'Cliente',
			'name' => 'Nome do Concorrente',	
			'link.0' => 'Link do Concorrente',		
			'logo' => 'Upload de Logo'
		);
	}
}
