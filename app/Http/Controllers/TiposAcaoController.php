<?php namespace App\Http\Controllers;


use App\Cliente;

use App\Http\Controllers\Controller;

use Request; // for Request::all()
use Redirect;

use App\TiposAcao;

use Input;
use Auth;

class TiposAcaoController extends Controller {
	 

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{	

	}



	public function tiposAcao($cliente_default=null)
	{
		$por_pagina = 15;

		// adiciona na url o cliente
		if($cliente_default == null){
		    $cliente_default = _clienteDefault($cliente_default);
		    return Redirect::action('TiposAcaoController@tiposAcao', array($cliente_default->slug));
		} else {
		    $cliente_default = _clienteDefault($cliente_default);
		}  


		$cliente_id = $cliente_default->id;
		$tipos_acao = TiposAcao::clienteSlug($cliente_default->slug)->orderBy('name','asc')->paginate($por_pagina);

		// array para popular o select com os clientes do usuário logado
		$filtrar_por_cliente = Auth::user()->cliente()->orderBy('name','asc')->lists('name','slug');


		return view('tipos_acao.create', compact('tipos_acao','cliente_default','filtrar_por_cliente', 'cliente_id'));
	}

	public function store()
	{
		// efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
		$this->validateWithCustomAttribute(Request::instance(),  $this->_rules('insert',null,Request::input('cliente_id')), $messages=array(), $this->_customAttributes());
		// instancia objecto
		$tipos_acao = new TiposAcao;
		// popula objeto com os campos do formulario
		$tipos_acao->fill(Request::all());

		if(Input::file('icon')){
			$file = Input::file('icon');

			$destinationPath = 'upload/tiposAcao/';

			$filename = date('Y-m-d_H-i-s').'_'.md5(uniqid("")).'.'.$file->guessExtension();
			Input::file('icon')->move($destinationPath, $filename);
			
			$tipos_acao->icon = $filename;
		} 

		// busca o cliente a ser associado a partir do id passando no form
		$cliente = Cliente::findOrFail(Request::input('cliente_id'));
		// cria associação do cliente na tabela tipos_acao
		$tipos_acao->cliente()->associate($cliente);
		// salva
		$tipos_acao->save();

		return view('tipos_acao.mensagem_cadastrado')->with('tipos_acao', $tipos_acao); 
	}

	public function destroy($tipos_acao)
	{
		$tipos_acao = TiposAcao::findOrFail($tipos_acao);
		
		$tipos_acao->delete();		

		return view('tipos_acao.mensagem_excluido')->with('tipos_acao', $tipos_acao);  
	}

	private function _rules($action='insert',$tipos_acao=null,$cliente_id){
		// regras para validação
		if($action == 'insert'){
			return array(
				'name'=>'required|min:3|unique:tipos_acao,name,NULL,id,cliente_id,'.$cliente_id,	
				'icon'=>'required|image|image_aspect:1|image_size:25',
				'cliente_id'=>'required|integer'
			);
		} else if($action == 'update'){		
			return false;	
			/*return array(
				'name'=>'required|min:3|unique:tipos_acao,name,'.$tipos_acao->name,	
				'icon'=>'image|image_aspect:1|image_size:25',
				'cliente_id'=>'required|integer'
			);*/
		}		
	}

	private function _customAttributes($acao=null){
		return array(
			'name' => 'Tipo de Ação',
			'icon' => 'Upload de Ícone'
		);
	}
}
