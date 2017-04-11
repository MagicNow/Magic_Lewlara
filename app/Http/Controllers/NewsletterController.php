<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Post;
use App\Http\Controllers\Controller;
use App\Newslettergroup;
use Request; // for Request::all()
use Input;
use Auth; // usado no _clienteDefault
use Redirect;
use Session;
use App\Categoria;
use DB;
use App\User;
use App\Newsletter;
use Mail;
use Validator;

// email render
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;


class NewsletterController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    private function _selectCategorias($cliente) {
        return Categoria::ClienteSlug($cliente->slug)->orderBy('name', 'asc')->lists('name', 'id');
    }

    private function _postsValidos($cliente_default){
        if(!is_object($cliente_default)){
            $cliente_default = _clienteDefault($cliente_default);
        }

        // verifica se existem posts selecionados
        if(!session('postsSelecionados')){
            redirect()->action('NewsletterController@novaPosts', $cliente_default->slug)->withErrors('Selecione ao menos um Post')->send();   
        }

        $postsValidos = array();
        // verifica se tem posts selecionados para o cliente atual
        foreach (session('postsSelecionados') as $postId => $value) {
            $post = Post::find($postId);
            if($post){
                if($post->cliente->id == $cliente_default->id){
                    $post->atualizacao = $value['atualizacao'];
                    array_push($postsValidos, $post);
                }
            }
        }

        // verifica se foram encontrado posts válidos | posts do cliente atual
        if(!$postsValidos){
            redirect()->action('NewsletterController@novaPosts',$cliente_default->slug)->withErrors('Selecione ao menos um Post')->send();   
        }

        return $postsValidos;
    }

    private function _gruposValidos($cliente_default){

        if(!is_object($cliente_default)){
            $cliente_default = _clienteDefault($cliente_default);
        }

        // verifica se existem grupos selecionados
        if(!session('gruposSelecionados')){
            return null;
        }
       

        $gruposValidos = array();
        // verifica se tem grupos selecionados para o cliente atual
        foreach (session('gruposSelecionados') as $grupoId => $value) {
            $grupo = Newslettergroup::find($grupoId);
            if($grupo){
                if($grupo->cliente->id == $cliente_default->id){
                    array_push($gruposValidos, $grupo);
                }
            }
        }
        // verifica se foram encontrado grupos válidos | posts do cliente atual
        if(!$gruposValidos){
            return null;
        }

        return $gruposValidos;
    }

    private function _pessoasValidas($cliente_default){

        if(!is_object($cliente_default)){
            $cliente_default = _clienteDefault($cliente_default);
        }

        // verifica se existem posts selecionados
        if(!session('pessoasSelecionadas')){
            return null;
            //redirect()->action('NewsletterController@novaPessoas',$cliente_default->slug)->withErrors('Selecione ao menos uma pessoa para disparar')->send();   
        }
       

        $pessoasValidas = array();
        // verifica se tem pessoas selecionados para o cliente atual
        foreach (session('pessoasSelecionadas') as $pessoaId => $value) {
            $pessoa = User::find($pessoaId);
            
            // TESTAR IF USER HAS
            if ($cliente_default->user->contains($pessoa->id)) {
                array_push($pessoasValidas, $pessoa);
            }
        }
        // verifica se foram encontrado posts válidos | posts do cliente atual
        if(!$pessoasValidas){
            return null;
            //redirect()->action('NewsletterController@novaPessoas',$cliente_default->slug)->withErrors('Selecione ao menos uma pessoa para disparar')->send();   
        }

        return $pessoasValidas;
    }

    public function lista($cliente_default = null)
    {
        $por_pagina = 15;

        // adiciona na url o cliente
        if($cliente_default == null){
            $cliente_default = _clienteDefault($cliente_default);
            return Redirect::action('NewsletterController@lista', $cliente_default->slug);
        } else {
            $cliente_default = _clienteDefault($cliente_default);
        }   

        // array para popular o select com os clientes do usuário logado
        $select_escolha_o_cliente = _selectEscolhaOCliente();


        // select datas
        $select_datas_todas_disponiveis = DB::select("SELECT DISTINCT(DATE_FORMAT(created_at,'%Y-%m')) as datapost FROM newsletter WHERE cliente_id = ? order by created_at desc", array($cliente_default->id));

        $select_datas = array();

        $select_datas = array();
        foreach ($select_datas_todas_disponiveis as $dt) {
            $dtt = explode('-',$dt->datapost);
            $ano = $dtt[0];  $mes = $dtt[1];

            $select_datas[$dt->datapost] = $ano.' | '._mes_paraNome($mes);            
        }


        $newsletters = Newsletter::clienteSlug($cliente_default->slug);

        if (session('filtro_data_newsletter_lista')) {
            $newsletters = $newsletters->where('created_at','<=',date('Y-m-t',strtotime(session('filtro_data_newsletter_lista'))))->where('created_at','>=',date('Y-m-01',strtotime(session('filtro_data_newsletter_lista'))));
        }

        $newsletters = $newsletters->paginate($por_pagina);

        return view('newsletter.lista', compact('cliente_default', 'select_escolha_o_cliente','select_datas', 'newsletters'));
    }
  
    public function novaPosts($cliente_default = null)
    {
        // nova newsletter seleciona posts

        // adiciona na url o cliente
        if($cliente_default == null){            
            Session::forget('pessoasSelecionadas');
            $cliente_default = _clienteDefault($cliente_default);
            return Redirect::action('NewsletterController@novaPosts', $cliente_default->slug);
        } else {
            $cliente_default = _clienteDefault($cliente_default);
        }        

        $por_pagina = 15;        

        // array para popular o select com os clientes do usuário logado
        $select_escolha_o_cliente = _selectEscolhaOCliente();

        // select datas
        $select_datas_todas_disponiveis = DB::select("SELECT DISTINCT(DATE_FORMAT(publicar_em,'%Y-%m')) as datapost FROM posts WHERE cliente_id = ? order by publicar_em desc", array($cliente_default->id));

        $select_datas = array();

        $select_datas = array();
        foreach ($select_datas_todas_disponiveis as $dt) {
            $dtt = explode('-',$dt->datapost);
            $ano = $dtt[0];  $mes = $dtt[1];

            $select_datas[$dt->datapost] = $ano.' | '._mes_paraNome($mes);            
        }
        
        // busca categorias para popular o select
        $select_categorias = $this->_selectCategorias($cliente_default);

        if ($select_categorias) {
            // busca subcategorias para popular o select            
            if (session('filtro_categorias')) { // se há categoria selecionada via sessão, busca as subcategorias dela
                $select_subcategorias = _selectSubCategorias(session('filtro_categorias'));
            } else {
                // se NÃO há categoria em sessão, pega a primeira categoria
                $select_subcategorias = _selectSubCategorias($primeira_categoria_id = key($select_categorias));
            }
        } else {
            $select_subcategorias = array('null' => 'null');
        }


        // busca posts com os filtros em sessão
        
        $posts = Post::clienteSlug($cliente_default->slug)->orderBy('publicar_em', 'desc');

        if (session('filtro_data')) {
            $posts = $posts->where('publicar_em','<=',date('Y-m-t',strtotime(session('filtro_data'))))->where('publicar_em','>=',date('Y-m-01',strtotime(session('filtro_data'))));
        }

        if (session('filtro_categorias')) {
            $posts = $posts->where('categoria_id',session('filtro_categorias'));
        }

        if (session('filtro_subcategorias')) {
            $posts = $posts->where('subcategoria_id',session('filtro_subcategorias'));
        }

        if (session('filtro_busca')) {
            $posts = $posts->where(function($query){
                $query->where('titulo','like','%'.str_replace(' ','%',session('filtro_busca'))."%")->orWhere('desc','like','%'.str_replace(' ','%',session('filtro_busca'))."%");
            });
        }

        $posts = $posts->paginate($por_pagina);

        // busca posts selecionados para pré marcar
        $postsSelecionados = session('postsSelecionados',array());

        return view('newsletter.nova_posts', compact('cliente_default', 'select_escolha_o_cliente', 'select_datas', 'select_categorias', 'select_subcategorias','posts', 'postsSelecionados'));
    }

    public function novaPessoas($cliente_default = null)
    {
        // nova newsletter seleciona pessoas

        // adiciona na url o cliente se não houver
        if($cliente_default == null){
            $cliente_default = _clienteDefault($cliente_default);
            return Redirect::action('NewsletterController@novaPessoas', $cliente_default->slug);
        }  else {
            $cliente_default = _clienteDefault($cliente_default);
        } 

        $postsValidos = $this->_postsValidos($cliente_default);
  
        $grupos = Newslettergroup::ClienteSlug($cliente_default->slug)->get();

        $pessoas = User::clienteSlug($cliente_default->slug)->orderBy('first_name','asc')->get();

        // conta quantos usuários de cada grupo / nível
        $count_admin = 0;   $count_lewlara = 0;     $count_cliente = 0;
        foreach ($pessoas as $usuario){
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

        // busca grupos selecionados para pré marcar
        $gruposSelecionados = session('gruposSelecionados',array());

        // busca pessoas selecionadas para pré marcar
        $pessoasSelecionadas = session('pessoasSelecionadas',array());


        $cliente_onchange_to_url = action('NewsletterController@novaPosts');

        return view('newsletter.nova_pessoas', compact('cliente_default', 'pessoas', 'count_admin', 'count_lewlara', 'count_cliente','pessoasSelecionadas','gruposSelecionados','cliente_onchange_to_url','grupos'));
        
    }

    public function ajaxFiltros()
    { 
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
    }

    public function ajaxFiltrosLista()
    { 
        if(!empty(Request::get('datas'))){
            Session::put('filtro_data_newsletter_lista', Request::get('datas'));
        } else {
            Session::forget('filtro_data_newsletter_lista');
        }
    }

    public function ajaxPostSelecionado()
    {
        if(Request::get('acao') == 'reset'){
            Session::forget('postsSelecionados');
            die();
        }

        if(!(int)Request::get('postId')){
            die();
        }

        if(session('postsSelecionados')){
            $postsSelecionados = session('postsSelecionados');
        } else {
            $postsSelecionados = array();
        }

        $atualizacao = Request::get('atualizacao') == 'set' ? true : false;
        if(Request::get('acao') == 'set'){
            $postsSelecionados[Request::get('postId')] = array(
                'acao' => true,
                'atualizacao' => $atualizacao
            );
            Session::put('postsSelecionados', $postsSelecionados);
        } else if(Request::get('acao') == 'unset'){
            unset($postsSelecionados[Request::get('postId')]);
            Session::put('postsSelecionados', $postsSelecionados);
        }
    }


    public function ajaxPessoaSelecionada()
    {
        if(Request::get('acao') == 'reset'){
            Session::forget('pessoasSelecionadas');
            die();
        }

        if(!(int)Request::get('pessoaId') && !is_array(Request::get('pessoaId')) ){
            die();
        }

        if(session('pessoasSelecionadas')){
            $pessoasSelecionadas = session('pessoasSelecionadas');
        } else {
            $pessoasSelecionadas = array();
        }

        if(is_array(Request::get('pessoaId'))){
            foreach (Request::get('pessoaId') as $pessoaId) {
                if(Request::get('acao') == 'set'){
                    $pessoasSelecionadas[$pessoaId] = true;                    
                } else if(Request::get('acao') == 'unset'){
                    unset($pessoasSelecionadas[$pessoaId]);                    
                }
            }
        }

        if(is_numeric(Request::get('pessoaId'))){
            if(Request::get('acao') == 'set'){
                $pessoasSelecionadas[Request::get('pessoaId')] = true;
            } else if(Request::get('acao') == 'unset'){
                unset($pessoasSelecionadas[Request::get('pessoaId')]);                
            }
        } 

        Session::put('pessoasSelecionadas', $pessoasSelecionadas);

    }


    public function ajaxTitle()
    {
        Session::forget('title');
        Session::put('title', Request::get('title'));
    }

    public function ajaxGrupoSelecionado()
    {
        if(Request::get('acao') == 'reset'){
            Session::forget('gruposSelecionados');
            die();
        }

        if(!(int)Request::get('grupoId') && !is_array(Request::get('grupoId')) ){
            die();
        }

        if(session('gruposSelecionados')){
            $gruposSelecionados = session('gruposSelecionados');
        } else {
            $gruposSelecionados = array();
        }

        if(is_array(Request::get('grupoId'))){
            foreach (Request::get('grupoId') as $grupoId) {
                if(Request::get('acao') == 'set'){
                    $gruposSelecionados[$grupoId] = true;                    
                } else if(Request::get('acao') == 'unset'){
                    unset($gruposSelecionados[$grupoId]);                    
                }
            }
        }

        if(is_numeric(Request::get('grupoId'))){
            if(Request::get('acao') == 'set'){
                $gruposSelecionados[Request::get('grupoId')] = true;
            } else if(Request::get('acao') == 'unset'){
                unset($gruposSelecionados[Request::get('grupoId')]);                
            }
        } 

        Session::put('gruposSelecionados', $gruposSelecionados);

    }

    public function novaDisparar($cliente_default = null)
    {
        $cliente_default = _clienteDefault($cliente_default);

        // busca os posts validos selecionados para o cliente atual - validações de posts
        $postsValidos = $this->_postsValidos($cliente_default);

        // busca os grupos selecionados validos para o cliente atual - validações de grupos
        $gruposValidos = $this->_gruposValidos($cliente_default);

        // busca as pessoas validas selecionadas para o cliente atual - validações de pessoas
        $pessoasValidas = $this->_pessoasValidas($cliente_default);

        // verifica se tem grupos ou pessoas validos
        
        if(!$gruposValidos && !$pessoasValidas){
            return redirect()->action('NewsletterController@novaPessoas',$cliente_default->slug)->withErrors('Selecione ao menos uma pessoa ou grupo para disparar')->send(); 
        }

        // efetiva o cadastro da newsletter
        $newsletter = $this->novaCadastra($postsValidos, $gruposValidos, $pessoasValidas, $cliente_default);

        // gera HTML para ser enviado via e-mail
        $dtt = explode('-',$newsletter->created_at);
        $newsletter->ano = $dtt[0];  
        $newsletter->mes = $dtt[1];
        $newsletter->mesNome = _mes_paraNome($newsletter->mes);

        $cssToInlineStyles = new CssToInlineStyles();

        $html = view('newsletter/show2', compact('newsletter', 'cliente_default'))->render();
        $css = file_get_contents(__DIR__ . '/../../../public/css/email_newsletter.css');

        $cssToInlineStyles->setHTML($html);
        $cssToInlineStyles->setCSS($css);

        // html gerado
        $Htmlfinal = $cssToInlineStyles->convert();

            // define destinatários
            $email_destinatario = array();

            foreach ($newsletter->pessoa()->get() as $pessoa) {
                array_push($email_destinatario, $pessoa->email);
            }
            //dd(session('title'),'dsa');
            if(session('title')){
                $titleNews = session('title');
            }else{
                $titleNews = mb_strtoupper($newsletter->cliente->name).' - '.$newsletter->assunto;
            }

            // salva os dados do usuário para disparo do e-mail
            $dados_email = array(   
                'msg' => $Htmlfinal,
                'email_destinatario'=>$email_destinatario,
                'assunto'=> $titleNews,
                'newsletter' => $newsletter,
                'cliente_default' => $cliente_default
            );

            // DISPARA E-MAIL            parametro use para passar variáveis pra dentro da função
            Mail::send('emails.blank', $dados_email, function($message) use ($dados_email)
            {               
                $message->to('noreply@lewlara.com')->bcc($dados_email['email_destinatario'])->subject($dados_email['assunto']); //->cc('bar@example.com');
            });


        // exibir tela de sucesso
        return view('newsletter.nova_sucesso', compact('newsletter','cliente_default'));
    }

    private function novaCadastra($postsValidos = null, $gruposValidos = null, $pessoasValidas = null, $cliente_default = null){
        if(!$postsValidos || !$cliente_default){ return false; }

        // cria array com Ids dos posts validos            
        if(is_array($postsValidos)){
            $postsValidosArrayId = array();
            $arr = array();
            foreach ($postsValidos as $post) {
                $arr[$post->id] = array(
                    'atualizacao' => $post->atualizacao
                );
                $postsValidosArrayId = $arr;
            }
        } else {
            $postsValidosArrayId = array($postsValidos);
        }

        if($pessoasValidas){
            // cria array com Ids das pessoas validas
             if(is_array($pessoasValidas)){
                $pessoasValidasArrayId = array();
                foreach ($pessoasValidas as $pessoa) {
                    array_push($pessoasValidasArrayId, $pessoa->id);
                }            
            } else {
                $pessoasValidasArrayId = array($pessoasValidas);
            }
        } else {
            $pessoasValidasArrayId = array();
        }

        $pessoasValidasGrupoArrayId = array();
        if($gruposValidos){
            foreach ($gruposValidos as $grupo) {
                foreach($grupo->pessoas as $pessoa){
                    //array_push($pessoasValidasArrayId, $pessoa->id);
                    array_push($pessoasValidasGrupoArrayId, $pessoa->id);                    
                }
            }
            $pessoasValidasGrupoArrayId = array_unique($pessoasValidasGrupoArrayId);
        }        

        $pessoasValidasArrayId = array_diff($pessoasValidasArrayId,$pessoasValidasGrupoArrayId);

        // cadastra newsletter + posts no banco
        $newsletter = new Newsletter;
        
        $newsletter->user_id = Auth::user()->id;
        $newsletter->cliente_id = $cliente_default->id;
        $newsletter->save();

        if(session('title')){
            $titleNews = session('title');
        }else{
            $titleNews = 'Newsletter '.$newsletter->id;
        }
        $newsletter->assunto = $titleNews;
        $newsletter->update();

        // vincula os posts selecionados
        $newsletter->post()->attach($postsValidosArrayId);

        // vincula as pessoas selecionadas
        $newsletter->pessoa()->attach($pessoasValidasArrayId);

        // vincula as pessoas de grupos selecionados || está registrando todas aparições 
        if($gruposValidos){            
            foreach ($gruposValidos as $grupo) {
                $newsletter->groups()->sync(array($grupo->id),false);
                foreach($grupo->pessoas as $pessoa){
                    $newsletter->pessoa()->sync(array($pessoa->id=>array('newsletter_group_id'=>$grupo->id)), false);
                }
            }
        }  

        

        // reseta posts selecionados  pessoas selecionadas grupos selecionados
        Session::forget('postsSelecionados');
        Session::forget('pessoasSelecionadas');
        Session::forget('gruposSelecionados');

        return $newsletter;
    }

    public function show(Newsletter $newsletter)
    {
        /*print('<pre>');
        print_r($newsletter->assunto);
        print('</pre>');

        print('<pre>');
        print_r($newsletter->cliente->slug);
        print('</pre>');

        print('<pre>');
        print_r($newsletter->created_at);
        print('</pre>');

        print('<pre>');
        print_r($newsletter->post()->lists('titulo'));
        print('</pre>');*/

        $dtt = explode('-',$newsletter->created_at);
        $newsletter->ano = $dtt[0];  
        $newsletter->mes = $dtt[1];
        $newsletter->mesNome = _mes_paraNome($newsletter->mes);


        // não foi chamada a função clienteDefault pois ela verifica se o usuário logado tem permissão, e nesta página o usuário não precisa estar logado
        $cliente_default = Cliente::where('slug', $newsletter->cliente->slug)->firstOrFail();

        return view('newsletter.show2', compact('newsletter','cliente_default'));
    }
    
    public function ajaxNewsletterDestroy()
    {
        if(empty(Request::get('newsletter'))){
            echo 'selecione ao menos uma newsletter para aplicar a ação';
            die();
        }

        foreach (Request::get('newsletter') as $newsletter) {
            // procura nas newletters do usuário logado se existe a newsletter com id recebido
            $n = Auth::user()->newsletter()->find($newsletter);

            if(!$n){
                echo 'Newsletter selecionada não encontrada'; //, apenas ignora e passa para a próximo';
                continue;
            } else {
                $n->delete();
            }
        }
    }

    public function ajaxVerPosts()
    {
        $newsletter = Newsletter::findOrFail(Request::get('newsletter_id'));

        $bloqueado = 1;
        foreach (Auth::user()->cliente()->get() as $cliente) {
            if($cliente->newsletter->contains($newsletter->id)){
                $bloqueado = 0;
            }
        }

        if($bloqueado) { return 'Bloqueado'; die(); }

        return view('newsletter.modal.modal_verposts')->with('newsletter', $newsletter);
    }

    public function ajaxVerPessoas()
    {
        $newsletter = Newsletter::findOrFail(Request::get('newsletter_id'));

        $bloqueado = 1;
        foreach (Auth::user()->cliente()->get() as $cliente) {
            if($cliente->newsletter->contains($newsletter->id)){
                $bloqueado = 0;
            }
        }

        if($bloqueado) { return 'Bloqueado'; die(); }

        return view('newsletter.modal.modal_verpessoas')->with('newsletter', $newsletter);
    }

    public function grupos($cliente_default=null)
    {
        // adiciona na url o cliente
        if($cliente_default == null){
            $cliente_default = _clienteDefault($cliente_default);
            return Redirect::action('NewsletterController@grupos', $cliente_default->slug);
        } else {
            $cliente_default = _clienteDefault($cliente_default);
        } 

        $grupos = Newslettergroup::ClienteSlug($cliente_default->slug)->get();

        return view('newsletter.grupos.create', compact('cliente_default','grupos'));
    }

    public function grupoStore()
    {
        $cliente_default = _clienteDefault(null);

        $validator = Validator::make(Request::all(), [
            'newsletter_group_name' => 'required|min:3'
        ]);

        if ($validator->fails()) {
            return redirect()->action('NewsletterController@grupos',$cliente_default->slug)->withInput()->withErrors($validator);
        } 

        // salva grupo
        $grupo = new Newslettergroup;
        $grupo->name = Input::get('newsletter_group_name');
        $grupo->cliente()->associate($cliente_default);
        $grupo->save();

        // redireciona para próxima tela de seleção de pessoas
        return redirect()->action('NewsletterController@grupoEdit',$grupo->id);        
    }

    public function grupoEdit(Newslettergroup $grupo)
    {
        $cliente_default = _clienteDefault(null);
        
        // se o grupo a ser editado não for do cliente atual, retorna pra home
        if($cliente_default->id != $grupo->cliente_id){ return redirect('/'); }

        $pessoas = User::clienteSlug($cliente_default->slug)->orderBy('first_name','asc')->get();

        // conta quantos usuários de cada grupo / nível
        $count_admin = 0;   $count_lewlara = 0;     $count_cliente = 0;
        foreach ($pessoas as $usuario){
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

        $cliente_onchange_to_url = action('NewsletterController@grupos');

        $pessoasSelecionadas = $grupo->pessoas->lists('first_name','id');        

        return view('newsletter.grupos.edit', compact('grupo','pessoas', 'count_admin', 'count_lewlara', 'count_cliente', 'pessoasSelecionadas', 'cliente_default', 'cliente_onchange_to_url'));
    }

    public function grupoUpdate(Newslettergroup $grupo)
    {
        $validator = Validator::make(Request::all(), [
            'pessoas' => 'required|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } 

        $grupo->pessoas()->sync(Input::get('pessoas', array()));
        $grupo->update();
        
        return redirect()->action('NewsletterController@grupos'); 
    }
}
