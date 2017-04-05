<?php namespace App\Http\Controllers;

use App\User;
use App\Cliente;
use App\Categoria;
use App\Subcategoria;
//use App\Http\Request;
use App\Http\Controllers\Controller;

use Request; // for Request::all()

//use Illuminate\Http\Request; // for function name(Request $request) and after use  $request->all()
use Input;
use Auth;


class CategoriaController extends Controller {
	 

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

	private function _selectCategorias($cliente){
		return $select_categorias = Categoria::clienteSlug($cliente->slug)->orderBy('name','asc')->lists('name','id');
	}

	private function _rules($tipo=null,$action='insert',$categoria=null,$cliente_id){
		// regras para validação
		if($tipo == 'categoria'){
			if($action == 'insert'){
				return array(
					'cliente_id'=>'required|exists:clientes,id',
					'name'=>'required|min:3|unique:categorias,name,NULL,id,cliente_id,'.$cliente_id,					
					'slug'=>'required|unique:categorias,slug,NULL,id,cliente_id,'.$cliente_id	
				);
			} else if($action == 'update'){		
				return array(
					'cliente_id'=>'required|exists:clientes,id',
					'name'=>'required|min:3|unique:categorias,name,'.$categoria->id.',id,cliente_id,'.$cliente_id,			
					'slug'=>'required|unique:categorias,slug,'.$categoria->id.',id,cliente_id,'.$cliente_id	
				);	
			}
		} else if($tipo == 'subcategoria'){
			$categoria_id = $cliente_id;  // corrigir a nomenclatura do objeto
			$subcategoria = $categoria;	 // corrigir a nomenclatura do objeto
			if($action == 'insert'){
				return array(
					'categoria_id'=>'required|exists:categorias,id',
					'name'=>'required|min:3|unique:sub_categorias,name,NULL,id,categoria_id,'.$categoria_id,					
					'slug'=>'required|unique:sub_categorias,slug,NULL,id,categoria_id,'.$categoria_id	
				);
			} else if($action == 'update'){		
				return array(
					'categoria_id'=>'required|exists:categorias,id',
					'name'=>'required|min:3|unique:sub_categorias,name,'.$subcategoria->id.',id,categoria_id,'.$categoria_id,					
					'slug'=>'required|unique:sub_categorias,slug,'.$subcategoria->id.',id,categoria_id,'.$categoria_id	
				);
			}
		}		
	}

	private function _customAttributes($tipo=null){
		if($tipo == 'categoria'){
			return array(
				'name' => 'Nome da Categoria',
				'slug' => 'URL da Categoria'
			);	
		} else if($tipo == 'subcategoria'){
			return array(
				'name' => 'Nome da Subcategoria',
				'categoria_id' => 'Qual Categoria Pertence'
			);	
		}
	}


	public function lista($cliente_default=null)
	{
		$por_pagina = 15;
		$cliente_default = _clienteDefault($cliente_default);

		// array para popular o select com os clientes do usuário logado
		$select_escolha_o_cliente = $this->_selectEscolhaOCliente();

		$categorias = Categoria::clienteSlug($cliente_default->slug)->orderBy('name','asc')->paginate($por_pagina);

		$select_categorias = $this->_selectCategorias($cliente_default);

		return view('categoria_subcategoria.lista', compact('categorias','cliente_default','select_escolha_o_cliente','select_categorias'));
	}

	public function categoriaStore()
	{
		// efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
		$this->validateWithCustomAttribute(Request::instance(),  $this->_rules('categoria','insert',$categoria=null,Request::input('cliente_id')), $messages=array(), $this->_customAttributes('categoria'));

		// instancia objecto
		$categoria = new Categoria;
		// popula objeto com os campos do formulario
		$categoria->fill(Request::all());

		// busca o cliente a ser associado a partir do id passando no form
		$cliente = Cliente::findOrFail(Request::input('cliente_id'));
		// cria associação do cliente na tabela tipos_acao
		$categoria->cliente()->associate($cliente);
		// salva
		$categoria->save();

		return view('categoria_subcategoria.mensagem_cadastrado')->with('categoria', $categoria); 
	}

	public function categoriaEdit(Categoria $categoria,$cliente_default=null)
	{
		$cliente_default = _clienteDefault($cliente_default,$categoria);

		// array para popular o select com os clientes do usuário logado
		$select_escolha_o_cliente = $this->_selectEscolhaOCliente();

		$cliente_onchange_to_url = action('CategoriaController@lista');
		
		return view('categoria_subcategoria.categoria_edit', compact('categoria','cliente_default','select_escolha_o_cliente', 'cliente_onchange_to_url'));
	}

	public function categoriaUpdate(Categoria $categoria)
	{
		// efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
		$this->validateWithCustomAttribute(Request::instance(),  $this->_rules('categoria','update',$categoria,Request::input('cliente_id')), $messages=array(), $this->_customAttributes('categoria'));
		
		// define atributos a partir dos dados da requisição
		$categoria->fill(Request::all());

		$categoria->update();
		
		return view('categoria_subcategoria.mensagem_editado')->with('categoria', $categoria); 
	}

	public function categoriaDestroy(Categoria $categoria)
	{
		$categoria->delete();

		return view('categoria_subcategoria.mensagem_excluido')->with('categoria', $categoria);  
	}




	// SUBCATEGORIA

	// SUBCATEGORIA

	// SUBCATEGORIA

	// SUBCATEGORIA

	public function subcategoriaStore()
	{
		// efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
		$this->validateWithCustomAttribute(Request::instance(),  $this->_rules('subcategoria','insert',$subcategoria=null,Request::input('categoria_id')), $messages=array(), $this->_customAttributes('subcategoria'));

		// instancia objecto
		$subcategoria = new Subcategoria;
		// popula objeto com os campos do formulario
		$subcategoria->fill(Request::all());

		// busca a categoria a ser associada a partir do id passando no form
		$categoria = Categoria::findOrFail(Request::input('categoria_id'));
		// cria associação do cliente na tabela tipos_acao
		$subcategoria->categoria()->associate($categoria);
		// salva
		$subcategoria->save();

		return view('categoria_subcategoria.mensagem_cadastrado')->with('categoria', $subcategoria); 
	}

	public function subcategoriaEdit(Subcategoria $subcategoria,$cliente_default=null)
	{

		$cliente_default = _clienteDefault($cliente_default,$subcategoria->categoria);

		// array para popular o select com os clientes do usuário logado
		$select_escolha_o_cliente = $this->_selectEscolhaOCliente();

		$select_categorias = $this->_selectCategorias($cliente_default);

		$cliente_onchange_to_url = action('CategoriaController@lista');
		
		return view('categoria_subcategoria.subcategoria_edit', compact('subcategoria','cliente_default','select_escolha_o_cliente', 'select_categorias','cliente_onchange_to_url'));
	}

	public function subcategoriaUpdate(Subcategoria $subcategoria)
	{
		// efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
		$this->validateWithCustomAttribute(Request::instance(),  $this->_rules('subcategoria','update',$subcategoria,Request::input('categoria_id')), $messages=array(), $this->_customAttributes('subcategoria'));
		
		// define atributos a partir dos dados da requisição
		$subcategoria->fill(Request::all());

		$subcategoria->slug = Request::get('slug');
		$subcategoria->name = Request::get('name');
		$subcategoria->categoria_id = Request::get('categoria_id');

		$subcategoria->update();
		
		return view('categoria_subcategoria.mensagem_editado')->with('categoria', $subcategoria); 
	}

	public function subcategoriaDestroy(Subcategoria $subcategoria)
	{
		$subcategoria->delete();

		return view('categoria_subcategoria.mensagem_excluido')->with('categoria', $subcategoria);  
	}
	
}
