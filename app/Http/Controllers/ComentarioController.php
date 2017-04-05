<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Categoria;
use App\Post;
use App\Http\Controllers\Controller;
use Request; // for Request::all()
use Input;
use Auth;  // usado no _clienteDefault
use Validator;
use Session;
use App\Notification;
use Redirect;
use App\Comentario;

class ComentarioController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    private function _selectOrdenarPor(){
        // array para popular o select de ordenar por
        return array(
                'todos'=>'Ordenar por',
                'data-cresc'=>'Mais recente',
                'data-decresc'=>'Mais antigo'
            );
    }

    public function todos($ordenar_por_default=null, $cliente_default = null) {
        $por_pagina = 15;

        // adiciona na url o cliente
        if($cliente_default == null){
            $cliente_default = _clienteDefault($cliente_default);
            return Redirect::action('ComentarioController@todos', array('todos', $cliente_default->slug));
        } else {
            $cliente_default = _clienteDefault($cliente_default);
        }  

        // array para popular o select com os clientes do usuário logado
        $select_escolha_o_cliente = _selectEscolhaOCliente();

        $select_ordenar_por = $this->_selectOrdenarPor();

        if(Auth::user()->is_cliente()){
            $comentarios = Auth::user()->comentarios()->clienteSlug($cliente_default->slug);
        } else {
            $comentarios = Comentario::clienteSlug($cliente_default->slug);
        }

        if($ordenar_por_default==null){
            $comentarios = $comentarios;
        } else {
            switch ($ordenar_por_default) {
                case 'data-cresc':
                    $comentarios = $comentarios->orderBy('created_at','desc');
                    break;
                case 'data-decresc':
                    $comentarios = $comentarios->orderBy('created_at','asc');
                    break;
                default:
                    $comentarios = $comentarios->orderBy('created_at','asc');
            }           
        }

        $comentarios_todos_sem_paginacao = $comentarios->get();
        $comentarios = $comentarios->paginate($por_pagina);


        // conta quantos usuários de cada grupo / nível
        $count_todos=count($comentarios_todos_sem_paginacao); $count_admin = 0;   $count_lewlara = 0;     $count_cliente = 0;
        foreach ($comentarios_todos_sem_paginacao as $comentario){
            $usuario = $comentario->user;
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

        return view('comentario.todos', compact('cliente_default', 'select_escolha_o_cliente','select_ordenar_por','ordenar_por_default', 'count_todos', 'count_admin', 'count_lewlara', 'count_cliente', 'comentarios'));
    }


    public function destroy(Comentario $comentario)
    {        
        
        $post = $comentario->post;
        $usuario = $comentario->user;

        // verifica se usuário pertence ao cliente do post do comentário a ser excluído
        if(!$post->cliente->user->contains(Auth::user()->id)){
           // sem permissão para excluir o post atual
            return redirect()->action('ComentarioController@todos');
            exit;
        } 
        
        // deleta comentario        
        $comentario->delete();

        // registra log       
        registraLogAcao('Excluiu comentário do usuário "'.$usuario->username.'" ('.$usuario->first_name.' '.$usuario->last_name.') no post "'.$post->titulo.'" ('.mb_strtoupper($post->cliente->name).')', $id_ativo = Auth::user()->id);
     
        return view('comentario.mensagem_excluido');   
    }

    public function update(Comentario $comentario)
    {
        $post = $comentario->post;
        $usuario = $comentario->user;

        // verifica se usuário pertence ao cliente do post do comentário a ser excluído
        if(!$post->cliente->user->contains(Auth::user()->id)){
           // sem permissão para excluir o post atual
            return redirect()->action('ComentarioController@todos');
            exit;
        } 

        // validação valida

        $validator = Validator::make(Request::all(), [
                    'comentario' => 'required|min:10'
                ]);

        if ($validator->fails()) {
            return redirect()->action('ComentarioController@todos',['todos',$post->cliente->slug])->withErrors($validator);
        }

        $comentario->fill(Request::all());
        $comentario->update();

        // registra log       
        registraLogAcao('Editou comentário do usuário "'.$usuario->username.'" ('.$usuario->first_name.' '.$usuario->last_name.') no post "'.$post->titulo.'" ('.mb_strtoupper($post->cliente->name).')', $id_ativo = Auth::user()->id);

        return view('comentario.mensagem_editado');
    }

}
