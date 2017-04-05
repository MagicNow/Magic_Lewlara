<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Cliente;
use App\Tag;
use Request; // for Request::all()
use Session;
use Input;

class TagController extends Controller {

    public function __construct() {
    }
    
    public function save() {
            $loggedUser = Auth::user();
            if(!$loggedUser) return redirect('/'); //Se não há um objeto de usuário logado, retornamos o vivente para a tela inicial
         
            $this->validateWithCustomAttribute(Request::instance(), $this->_rules(), $messages=array(), $this->_customAttributes());

            $idcliente = Request::input('client_id');
            
            if ($idtag = Request::input('tag_id')) { //se recebemos um id no post, pegamos a tag que estamos alterando...
                $tag = Tag::findOrFail($idtag);
                $successMessage = 'Tag alterada com sucesso!';
            } else { //...do contrário, iniciamos o objeto que eventualmente salvaremos
                $tag = new Tag();
                $successMessage = 'Tag cadastrada com sucesso!';
            }
            //criação ou alteração, o resto do método se desenrola do mesmo modo
            $tag->name = Request::input('name');
            $tag->slug = str_slug(Request::input('name'));
            $tag->cliente_id = $idcliente;
            $tag->save();
            
            $cliente_default = $tag->cliente; //pegamos o cliente dono da tag que foi salva, pois mostramos o logo dele na tela de sucesso
            $callToAction = 'CADASTRAR NOVA TAG'; //mensagem do botão de retorno da tela de sucesso
            //return view('tags.success', compact('cliente_default','successMessage','callToAction'));
            return redirect()->action('TagController@edit');
    }
       
    public function edit($idcliente = null, $idtag = null){
            //se estamos alterando uma tag, instanciamos o que será mandado para view
            //também determinamos o 'call to action' da view, dependendo da operação
            if ($idtag) {
                $tag = Tag::find($idtag);
                $submitText = 'ATUALIZAR TAG';
            }else{
                $submitText = 'CADASTRAR TAG';
            }
            
            //pegar usuário logado para obter lista de clientes que alimentará o select de clientes na view
            $loggedUser = Auth::user();
            //Se não há um objeto de usuário logado, retornamos o vivente para a tela inicial
            if(!$loggedUser) return redirect('/');      
            
            if ($loggedUser->group->contains(1)) $clientes_select = Cliente::all()->toArray(); //se um dos grupos do usuário logado é id 1, ele é um admin, e então pegamos todos os clientes do banco...
            else $clientes_select = $loggedUser->cliente->toArray(); //...do contrário, pegamos somente clientes vinculados ao usuário logado
            
            foreach ($clientes_select as $client_option){ //transformamos a collection em um array para integração com o helper de <select> view
               $clientes_array[$client_option['slug']] = $client_option['name'];
            }
            if(!$idcliente) $idcliente = $clientes_select[0]['slug']; //se não obtivemos da chamada da função um id de cliente, o id será o primeiro da lista de clientes disponiveis
            $cliente_default = Cliente::where('slug','=',$idcliente)->First(); //e aqui obtemos o objeto do que será o cliente principal da view
            $tagList = $cliente_default->tag()->orderBy('name')->paginate(25); //e agora pegamos a lista de tags do cliente devidamente páginada          
            if(!isset($idtag)) $tag = new Tag(); //se não temos uma tag para mostrar, mostraremos esse objeto vazio para evitar o problema de Undefined variable: tag 
            
            return view('tags.edit', compact('clientes_select', 'tag', 'cliente_default', 'clientes_array','tagList','submitText'));   
    }

    public function delete($tag_id) {
        if(!isset($tag_id)) return redirect('/'); //se por algum motivo o vivente não está passando o argumento deste método, vamos para a home
        
        $tag = Tag::find($tag_id); //estando tudo certo, recuperamos a tag que vamos deletar

        if(!count($tag)) return redirect('/'); //se por algum motivo o vivente está tentando deletar algo já deletado, mandamos ele pra home

        $cliente_default = $tag->cliente; //pegamos o cliente dono da tag, pois mostramos o logo na tela de sucesso da operação
        $tag->delete();
        
        //como a view de sucesso espera uma mensagem e um texto de call to action
        //do controller, setamos estas aqui
        $successMessage = "Tag deletada com sucesso!";
        $callToAction = "VOLTAR";
        return view('tags.success', compact('cliente_default','successMessage','callToAction'));
    }
    
    private function _rules(){
        return array('name'=>'required|min:3');
    }
    private function _customAttributes(){
        return array('name' => 'Tag');
    }
}
