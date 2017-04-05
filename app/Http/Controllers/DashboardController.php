<?php

namespace App\Http\Controllers;

use App\User;
use App\Cliente;
use App\Categoria;
use App\Post;
use App\Comentario;
use Auth;
use HTML;

class DashboardController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    private function _selectEscolhaOCliente() {
        //usa assim: $select_escolha_o_cliente = $this->_selectEscolhaOCliente();
        return Auth::user()->cliente()->orderBy('name', 'asc')->lists('name', 'slug');
    }

    private function _rules($tipo = null, $action = 'insert', $categoria = null, $cliente_id) {
        return array();
    }

    private function _customAttributes($tipo = null) {
        return array();
    }

    public function index($cliente_default = null) {
        $loggedUser = Auth::user();

        // array para popular o select com os clientes do usuário logado
        $select_escolha_o_cliente = $this->_selectEscolhaOCliente();
        $cliente_default = _clienteDefault($cliente_default);
        
        $post = new Post();
        $posts = $cliente_default->post;
        
        $nposts = $posts->count();
        $select_posts = $posts->lists('titulo','id');
        $top_post = $posts->first();

        

        $posts_destaques = $post
                ->where('cliente_id','=',$cliente_default->id)
                ->where('status',2)
                ->where('destaque',1)
                ->orderBy('created_at','desc')
                ->paginate(10);
               // ->get();
        
           
        if ($loggedUser->group->contains(1) || $loggedUser->group->contains(4)) {

            $posts_recentes = $post
                    ->where('cliente_id','=',$cliente_default->id)
                    ->orderBy('created_at','desc')
                    ->paginate(10);

            $ultimos_comentarios = Comentario::orderBy('id','desc')->clienteSlug($cliente_default->slug)->orderBy('created_at','desc')->paginate(10);
            $posts_destaques = $loggedUser->postsfavoritos()->orderby('id','asc')->paginate(10);

            return view('dashboard.admin', compact('select_escolha_o_cliente' , 'cliente_default' , 'nposts', 'select_posts', 'posts_recentes', 'posts_destaques', 'top_post', 'ultimos_comentarios'));


        } else {


            $posts_recentes = $post
                    ->where('cliente_id','=',$cliente_default->id)
                    ->orderBy('created_at','desc')
                    ->take(2)
                    ->get();

            $posts_favoritos = $loggedUser->postsfavoritos()->orderby('id','asc')->paginate(10);
            
            $meus_ultimos_comentarios = Comentario::where('user_id',$loggedUser->id)->clienteSlug($cliente_default->slug)->orderBy('id','desc') ->paginate(10);
           
            return view('dashboard.nonadmin', compact('select_escolha_o_cliente' , 'cliente_default' , 'nposts', 'select_posts', 'meus_ultimos_comentarios', 'posts_recentes', 'posts_destaques','top_post', 'posts_favoritos'));
        }
    }

}
