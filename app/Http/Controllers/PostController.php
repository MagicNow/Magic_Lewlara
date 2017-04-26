<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Categoria;
use App\Subcategoria;
use App\TiposAcao;
use App\Concorrente;
use App\PostHistorico;
use App\Post;
use App\Midia;
use App\Tag;
use App\Http\Controllers\Controller;
use Request; // for Request::all()
use Redirect;
use Input;
use Auth;  // usado no _clienteDefault
use Validator;
use Session;
use App\Notification;


class PostController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    private function _rules($action = 'insert', $post = null, $cliente_id, $rascunho) {
        // regras para validação
        if ($rascunho) {
            return array(
                'titulo' => 'required|min:3'
            );
        } else {
            if ($action == 'insert') {
                return array(
                    'titulo' => 'required|min:3',
                    'desc' => 'required|min:50',
                    'dia' => 'required_if:publicar-em-obrigatorio,1|numeric|min:1|max:31',
                    'mes' => 'required_if:publicar-em-obrigatorio,1|numeric|min:1|max:12',
                    'ano' => 'required_if:publicar-em-obrigatorio,1|numeric|min:2000|max:2050',
                    'hora' => 'required_if:publicar-em-obrigatorio,1|numeric|min:0|max:23',
                    'minuto' => 'required_if:publicar-em-obrigatorio,1|numeric|min:0|max:59|Dataviaparametros:'.Request::get('dia').','.Request::get('mes').','.Request::get('ano').','.Request::get('hora').','.Request::get('minuto'),
                    'cliente_id' => 'required|exists:clientes,id',
                    'tipos_acao' => 'required',
                    'definir_destaque_imagem' => 'Postdestaque:'.Request::get('definir_destaque_imagem').','.Request::get('definir_destaque_url')
                );
            } else if ($action == 'update') {
                return array(
                    'titulo' => 'required|min:3',
                    'desc' => 'required|min:50',
                    'dia' => 'required|numeric|min:1|max:31',
                    'mes' => 'required|numeric|min:1|max:12',
                    'ano' => 'required|numeric|min:2000|max:2050',
                    'hora' => 'required|numeric|min:0|max:23',
                    'minuto' => 'required|numeric|min:0|max:59|Dataviaparametros:'.Request::get('dia').','.Request::get('mes').','.Request::get('ano').','.Request::get('hora').','.Request::get('minuto'),
                    'cliente_id' => 'required|exists:clientes,id',
                    'tipos_acao' => 'required'
                );
            }
        }
    }

    private function _customAttributes($acao = null) {
        return array(
            'titulo' => 'Título',
            'desc' => 'Descrição',
            'tipos_acao' => 'Tipos de Ação',
            'dia' => 'Dia',
            'mes' => 'Mês',
            'ano' => 'Ano',
            'hora' => 'Hora',
            'minuto' => 'Minuto'
        );
    }

    private function _customMessages($acao = null) {
        return array(
            'tipos_acao.required' => 'Ao menos um Tipo de Ação é obrigatório.',
            'required_if' => 'O campo :attribute é obrigatório.'
        );
    }
    
    private function _replaceGallery($desc) {
        // verifica se galeria já está limpa ou suja
        $teste_limpo = explode('lSPager lSGallery',$desc);
        if (!array_key_exists("1",$teste_limpo)){
            return $desc;
        }

        //echo '<style>*{ display: block!important}</style><br/><h1>Fatiando</h1></br></br></br>';
        //echo '______________________________________________________________________________</br></br></br>';
        $segments = array();
        
        //echo '<textarea>'.$desc.'</textarea><br/></br>';
        //$galerias = explode('<hr class="galeria-separador galeria-separador-top">', $desc);
        $galerias = preg_split('@(?<=<hr class="galeria-separador galeria-separador-top">)@', $desc);

        //$segments[] = $galerias[0]; //possível pedaço de texto antes da primeira galeria
        foreach($galerias as $galeria){
            //$galeria_inner = explode('<hr class="galeria-separador galeria-separador-bottom">', $galeria);
            $galeria_inner = preg_split('@(?=<hr class="galeria-separador galeria-separador-bottom">)@', $galeria);
            $segments[] = $galeria_inner[0];//galeria em si
            if(isset($galeria_inner[1])) $segments[] = $galeria_inner[1];//possível texto entre essa galeria e a próxima, ou talvez entre essa galeria e o fim do desc
        }
        
        $n_of_segments = count($segments);
        
        for($i = 0; $i < $n_of_segments; $i++){
            
            $segment = $segments[$i];
            
            //echo '<textarea>'.$segment.'</textarea><br/>';
            if(strstr($segment, 'post-galeria-posicao'))//se segmento for de galeria, pra cada galeria... 
            {
                $images = array();
                $cleanGallery = '<div class="post-galeria-posicao" unselectable="true"><ul class="post-galeria">'; //inicio da 'galeria limpa' de acordo com o escopo da galerai desta iteração somente
                $segment = explode('lSPager lSGallery',$segment); //...isolamos a parte dos thumbs
                $segment = $segment[1]; //                          ...deixando o slider (lixo) de antes dos thumbs para trás
                $imageChunks = explode('<img src="',$segment); //   ...e pegamos todos pedaços de html que contém imagens
                foreach($imageChunks as $img){ //para cada pedaõ isolamos somente o src da imagem...
                    $img = explode('"',$img); //...parando antes do /> da tag img
                    $images[] = $img[0]; //e colocamos essas urls em um array images reinicializado para cada galeria achada
                }
                foreach($images as $img){ //ainda no escopo de uma única galeria, criamos a galeria limpa
                    if($img) $cleanGallery .= '<li data-thumb="'.$img.'"><img src="'.$img.'"></li>'; //verificando que não estamos com nenhum node vazio sendo integrado na galeria limpa
                }
                $cleanGallery .= '</ul></div>'; //fechamos a galeria limpa após alimentarmos todas suas imagens
                $segments[$i] = $cleanGallery; //e colocamos a galeria limpa no lugar deste segmento que identificamos como uma galeria (suja)
            }
        }
        $new_desc = implode('', $segments);
        //echo $new_desc;
        //die;
        return $new_desc;
    }

    private function _get_extension_url($url) {
        $url = explode('.',$url);
        $url = end($url);
        return $url;
    }

    private function _selectCategorias($cliente) {
        return Categoria::ClienteSlug($cliente->slug)->orderBy('name', 'asc')->lists('name', 'id');
    }

    private function _selectConcorrentes($cliente) {
        return Concorrente::ClienteSlug($cliente->slug)->orderBy('name', 'asc')->lists('name', 'id');
    }

    private function _selectTags($cliente) {
        return Tag::ClienteSlug($cliente->slug)->orderBy('name', 'asc')->lists('name', 'id');
    }

    private function _checkboxTiposAcao($cliente) {
        return TiposAcao::ClienteSlug($cliente->slug)->orderBy('name', 'asc')->lists('name', 'id');
    }

    private function _postsValidos($cliente_default){
        if(!is_object($cliente_default)){
            $cliente_default = _clienteDefault($cliente_default);
        }

        // verifica se existem posts selecionados
        if(!session('postsSelecionados_pdf')){
            redirect()->action('PostController@pdfPosts',$cliente_default->slug)->withErrors('Selecione ao menos um Post')->send();   
        }

        $postsValidos = array();
        // verifica se tem posts selecionados para o cliente atual
        foreach (session('postsSelecionados_pdf') as $postId => $value) {
            $post = Post::find($postId);
            if($post){
                if($post->cliente->id == $cliente_default->id){
                    array_push($postsValidos, $post);
                }
            }
        }

        // verifica se foram encontrado posts válidos | posts do cliente atual
        if(!$postsValidos){
            redirect()->action('PostController@pdfPosts',$cliente_default->slug)->withErrors('Selecione ao menos um Post')->send();    
        }

        return $postsValidos;
    }

    public function ajaxSubcategoria() {
        if (!is_numeric(Request::input('categoria_id')) && !Request::input('categoria_id') > 0) {
            return '0';
        }

        $select_subcategorias = _selectSubCategorias(Request::input('categoria_id'));

        return json_encode($select_subcategorias);
    }

    public function todos($cliente_default = null) {
        $por_pagina = 15;

        $cliente_default = _clienteDefault($cliente_default);

        // array para popular o select com os clientes do usuário logado
        $select_escolha_o_cliente = _selectEscolhaOCliente();

        // select datas
        $select_datas_todas_disponiveis = \DB::select("SELECT DISTINCT(DATE_FORMAT(publicar_em,'%Y-%m')) as datapost FROM posts WHERE cliente_id = ? order by publicar_em desc", array($cliente_default->id));

        $select_datas = array();

        $select_datas = array();
        foreach ($select_datas_todas_disponiveis as $dt) {
            $dtt = explode('-',$dt->datapost);
            $ano = $dtt[0];  $mes = $dtt[1];

            $select_datas[$dt->datapost] = $ano.' | '._mes_paraNome($mes);            
        }
        
        // busca categorias para popular o select
        $select_categorias = $this->_selectCategorias($cliente_default);

        $select_tags = $this->_selectTags($cliente_default);

        if ($select_categorias) {
            // busca subcategorias para popular o select            
            if (session('filtro_categorias_todos_posts')) { // se há categoria selecionada via sessão, busca as subcategorias dela
                $select_subcategorias = _selectSubCategorias(session('filtro_categorias_todos_posts'));
            } else {
                // se NÃO há categoria em sessão, pega a primeira categoria
                $select_subcategorias = _selectSubCategorias($primeira_categoria_id = key($select_categorias));
            }
        } else {
            $select_subcategorias = array('null' => 'null');
        }

        // busca posts com os filtros em sessão
        
        $posts = Post::clienteSlug($cliente_default->slug)->orderBy('publicar_em', 'desc');

        if (session('filtro_data_final_todos_posts')) {
            $posts =  $posts->where('publicar_em','<=',date('Y-m-d 23:59:59',strtotime(session('filtro_data_final_todos_posts'))));
        }

        if (session('filtro_data_inicio_todos_posts')) {
            $posts =  $posts->where('publicar_em','>=',date('Y-m-d 00:00:01',strtotime(session('filtro_data_inicio_todos_posts'))));
        }
        
        // print_r(session('filtro_data_final_todos_posts'));echo "<br>";
        // print_r(date('Y-m-d',strtotime(session('filtro_data_final_todos_posts'))));exit();
        if (session('filtro_categorias_todos_posts')) {
            $posts = $posts->categoriaId(session('filtro_categorias_todos_posts'));
        }

        if (session('filtro_subcategorias_todos_posts')) {
            $posts = $posts->subCategoriaId(session('filtro_subcategorias_todos_posts'));
        }

        if (session('filtro_tag_todos_posts')) {
            $posts = $posts->tagId(session('filtro_tag_todos_posts'));
        }

        if (session('filtro_busca_todos_posts')) {
            $posts = $posts->where(function($query){
                $query->where('titulo','like','%'.str_replace(' ','%',session('filtro_busca_todos_posts'))."%")->orWhere('desc','like','%'.str_replace(' ','%',session('filtro_busca_todos_posts'))."%");
            });
        }

        $posts_sem_paginacao =  $posts->get();
        $posts = $posts->paginate($por_pagina);


        // conta quantos posts de cada status
        $count_publicados = 0;
        $count_agendados = 0;
        $count_rascunhos = 0;
        foreach ($posts_sem_paginacao as $post) {
            switch ($post->status) {
                case '1':
                    $count_rascunhos++;
                    break;
                case '2':
                    if (strtotime($post->publicar_em) > strtotime(date('Y-m-d H:i:s'))) {
                        $count_agendados++;
                    } else {
                        $count_publicados++;
                    }
                    break;
            }
        }


        return view('post.todos', compact('cliente_default', 'select_escolha_o_cliente',  'select_datas', 'select_categorias','select_tags', 'select_subcategorias', 'posts', 'count_publicados', 'count_agendados', 'count_rascunhos'));
    }


    public function todosDestacados($cliente_default = null) {

        $por_pagina = 15;

        $cliente_default = _clienteDefault($cliente_default);

        // array para popular o select com os clientes do usuário logado
        $select_escolha_o_cliente = _selectEscolhaOCliente();

        // busca categorias para popular o select
        $select_categorias = $this->_selectCategorias($cliente_default);

        $select_tags = $this->_selectTags($cliente_default);

        if ($select_categorias) {
            // busca subcategorias para popular o select            
            if (session('filtro_categorias_todos_posts')) { // se há categoria selecionada via sessão, busca as subcategorias dela
                $select_subcategorias = _selectSubCategorias(session('filtro_categorias_todos_posts'));
            } else {
                // se NÃO há categoria em sessão, pega a primeira categoria
                $select_subcategorias = _selectSubCategorias($primeira_categoria_id = key($select_categorias));
            }
        } else {
            $select_subcategorias = array('null' => 'null');
        }

        // busca posts com os filtros em sessão

        $posts = Auth::user()->postsfavoritos()->clienteSlug($cliente_default->slug)->orderBy('publicar_em', 'desc');

        if (session('filtro_data_final_todos_posts')) {
            $posts =  $posts->where('publicar_em','<=',date('Y-m-d 23:59:59',strtotime(session('filtro_data_final_todos_posts'))));
        }

        if (session('filtro_data_inicio_todos_posts')) {
            $posts =  $posts->where('publicar_em','>=',date('Y-m-d 00:00:01',strtotime(session('filtro_data_inicio_todos_posts'))));
        }
        
         //print_r(session('filtro_data_final_todos_posts'));echo "<br>";
         //print_r(date('Y-m-d',strtotime(session('filtro_data_final_todos_posts'))));exit();
        if (session('filtro_categorias_todos_posts')) {
            $posts = $posts->categoriaId(session('filtro_categorias_todos_posts'));
        }

        if (session('filtro_subcategorias_todos_posts')) {
            $posts = $posts->subCategoriaId(session('filtro_subcategorias_todos_posts'));
        }

        if (session('filtro_tag_todos_posts')) {
            $posts = $posts->tagId(session('filtro_tag_todos_posts'));
        }

        if (session('filtro_busca_todos_posts')) {
            $posts = $posts->where(function($query){
                $query->where('titulo','like','%'.str_replace(' ','%',session('filtro_busca_todos_posts'))."%")->orWhere('desc','like','%'.str_replace(' ','%',session('filtro_busca_todos_posts'))."%");
            });
        }

        $posts_sem_paginacao =  $posts->get();
        $posts = $posts->paginate($por_pagina);


        // conta quantos posts de cada status
        $count_publicados = 0;
        $count_agendados = 0;
        $count_rascunhos = 0;
        foreach ($posts_sem_paginacao as $post) {
            switch ($post->status) {
                case '1':
                    $count_rascunhos++;
                    break;
                case '2':
                    if (strtotime($post->publicar_em) > strtotime(date('Y-m-d H:i:s'))) {
                        $count_agendados++;
                    } else {
                        $count_publicados++;
                    }
                    break;
            }
        }


        return view('post.todos_destacados', compact('cliente_default', 'select_escolha_o_cliente',  'select_datas', 'select_categorias','select_tags', 'select_subcategorias', 'posts', 'count_publicados', 'count_agendados', 'count_rascunhos'));
    }


    public function create($cliente_default = null) {
        $cliente_default = _clienteDefault($cliente_default);

        // array para popular o select com os clientes do usuário logado
        $select_escolha_o_cliente = _selectEscolhaOCliente();

        // busca tags para popular o select
        $select_tags = $cliente_default->tag()->lists('name', 'id');

        // busca categorias para popular o select
        $select_categorias = $this->_selectCategorias($cliente_default);
        if(!$select_categorias){
            return Redirect::action('CategoriaController@lista')->withErrors(array('Cadastre ao menos uma Categoria para o Cliente atual')); 
        }

        // popular categorias com retorno dos erros de validação ou do post
        if(Input::old('categoria_id')){
            $categoria_id = Input::old('categoria_id');
            $subcategoria_id = Input::old('subcategoria_id');
            
            $post_categorias = Categoria::whereIn('id',$categoria_id)->get();
        

            // cria os selects e options das categorias e subcategorias
            $categorias_subcategorias_html = '';   
            $c=1;  // flag para saber se é primeira categoria para poder exibir o botão de remover   
            foreach ($categoria_id as $key => $categoria_id) {

                $categorias_options = '';
                //$select_categorias = Categoria::find($categoria_id)->lists('name','id');
                foreach ($select_categorias as $id => $name) {
                    $selected = '';
                    if($categoria_id == $id) { $selected = 'selected'; }
                    $categorias_options .= '<option value="'.$id.'" '.$selected.'>'.$name.'</option>';
                }

                $categorias_subcategorias_html .= "<div class='categoria-grupo'>
                            <br>";

                if($c>1){
                    $categorias_subcategorias_html .= "<a class='btn btn-cinza btn-extra-pequeno align-center float-right mb-10 btn-remover-categoria'>REMOVER</a>";
                }

                $categorias_subcategorias_html .= "    
                            <select class='form-control arrow-preto-amarelo onChangeAlteraSubcategoria' name='categoria_id[]'>".$categorias_options."</select>
                            <br>";

                if($subcategoria_id[$key]){
                    $sub_categoria_id = $subcategoria_id[$key];
                    $select_subcategorias = _selectSubCategorias($categoria_id);
                    $subcategorias_options = '';
                    foreach ($select_subcategorias as $id => $name) {
                        $selected = '';
                        if($sub_categoria_id == $id) { $selected = 'selected'; }
                        $subcategorias_options .= '<option value="'.$id.'" '.$selected.'>'.$name.'</option>';
                    }

                    $categorias_subcategorias_html .= "<select class='form-control arrow-preto-amarelo selectSubcategorias ' name='subcategoria_id[]'>".$subcategorias_options."</select>";

                } else {

                    $select_subcategorias = _selectSubCategorias($categoria_id);
                    if($select_subcategorias){
                        $subcategorias_options = '<option value="" selected>Selecione a Subcategoria</option>';
                        foreach ($select_subcategorias as $id => $name) {                    
                            $subcategorias_options .= '<option value="'.$id.'">'.$name.'</option>';
                        }

                        $categorias_subcategorias_html .= "<select class='form-control arrow-preto-amarelo selectSubcategorias ' name='subcategoria_id[]'>".$subcategorias_options."</select>";
                    } else {
                        $categorias_subcategorias_html .= "<select class='form-control arrow-preto-amarelo selectSubcategorias hide' name='subcategoria_id[]'><option value='' selected>Selecione a Categoria</option></select>";
                    }
                }


                $categorias_subcategorias_html .= "<br>
                                            </div><!-- /.categoria-grupo -->";
                $c++;

            }
        } else {
            $categorias_options = '';

            foreach ($select_categorias as $id => $name) {
                $categorias_options .= '<option value="'.$id.'">'.$name.'</option>';
            }

            $categorias_subcategorias_html = "<div class='categoria-grupo'>
                                    <br>
                                    <select class='form-control arrow-preto-amarelo onChangeAlteraSubcategoria' name='categoria_id[]'>".$categorias_options."</select>
                                    <br>";
            // voltar para a primeira categoria do array pois está definida como selected então pegamos as subcategorias    
            reset($select_categorias);       
            $select_subcategorias = _selectSubCategorias(key($select_categorias));
            if($select_subcategorias){
                $subcategorias_options = '<option value="" selected>Selecione a Subcategoria</option>';
                foreach ($select_subcategorias as $id => $name) {                    
                    $subcategorias_options .= '<option value="'.$id.'">'.$name.'</option>';
                }

                $categorias_subcategorias_html .= "<select class='form-control arrow-preto-amarelo selectSubcategorias ' name='subcategoria_id[]'>".$subcategorias_options."</select>";
            } else {
                $categorias_subcategorias_html .= "<select class='form-control arrow-preto-amarelo selectSubcategorias hide' name='subcategoria_id[]'><option value='' selected>Selecione a Categoria</option></select>";
            }
                $categorias_subcategorias_html .= "<br>
                                </div><!-- /.categoria-grupo -->";
        }

        // busca tipos de ação para popular os checkbox
        $checkbox_tiposAcao = $this->_checkboxTiposAcao($cliente_default);


        // busca concorrentes para popular o select
        $select_concorrentes = $this->_selectConcorrentes($cliente_default);
        

        return view('post.create', compact('cliente_default', 'select_escolha_o_cliente', 'select_tags', 'select_categorias', 'select_subcategorias', 'checkbox_tiposAcao', 'categorias_subcategorias_html', 'select_concorrentes'));
    }

    public function store() {
        // replace Gallery replaceGallery na descrição antes de efetuar a validação
        $requestInput = Request::input();
        $requestInput['desc'] = $this->_replaceGallery(Request::get('desc'));
        Request::replace($requestInput);


        $midia_img = null;
        if(Request::input('definir_destaque_flag_if_from_url') == 1){
            if(Request::input('definir_destaque_url') && trim(Request::input('definir_destaque_url')) != ''){

                // verifica se cliente é do usuário logado
                $temp_cliente_slug = Cliente::findOrFail(Input::get('cliente_id'))->slug;
                $temp_cliente_default = _clienteDefault($temp_cliente_slug);
                
                // url encode na url
                $url = explode('/',Request::input('definir_destaque_url'));
                foreach ($url as $key => $value) {
                    if($value == 'http:' || $value == 'https:'){ continue; }
                    if(_is_urlEncoded($value)){
                        $url[$key] = $value;
                    } else {
                        $url[$key] = urlencode($value);
                    }
                }
                $url = implode('/',$url);                

                // baixa arquivo imagem pro servidor
                $file   = @file_get_contents($url); 

                if($file === FALSE) { 
                    return Redirect()->back()->withErrors(array('Url da Imagem de Destaque não está acessível.'));
                }                
                  
                $dados_imagem = getimagesize($url);
                if(!$dados_imagem){
                    return Redirect()->back()->withErrors(array('Url da Imagem de Destaque não é uma imagem válida.'));
                }
                // detecta extensão
                switch ($dados_imagem[2]) {
                    case '1':
                        $ext = 'gif';
                        break;
                    case '2':
                        $ext = 'jpg';
                        break;
                    case '3':
                        $ext = 'png';
                        break;
                    case '4':
                        $ext = 'bmp';
                        break;
                }
                // define caminho final do arquivo
                $midia_img    = 'upload/posts/'.$temp_cliente_default->id.'/'.$temp_cliente_default->id . '-' . uniqid() . rand(1111, 9999) . '.'.$ext;
                // efetua ação de salvar arquivo
                $result = file_put_contents($midia_img, $file);

            }
        }

        // efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
        $this->validateWithCustomAttribute(Request::instance(), $this->_rules('insert', null, Request::input('cliente_id'), Request::input('rascunho')), $messages = $this->_customMessages(), $this->_customAttributes());
            
            if($midia_img == null){ // enviado via anexo da livraria library
                if(trim(Request::input('definir_destaque_imagem')) != '' && Request::input('definir_destaque_flag_if_from_url') == 0){
                    $midia = new Midia;
                    $midia->imagem = Request::input('definir_destaque_imagem');
                    $midia->tipo_midia = "imagem";
                    $midia->titulo = Request::input('definir_destaque_title');                
                    $midia->desc = Request::input('definir_destaque_desc');
                    $midia->link = Request::input('definir_destaque_linkar_para');
                    $midia->alinhamento = Request::input('definir_destaque_alignment');
                    $midia->save();
                }
                /*$midia = new Midia;
                $midia->imagem = Request::input('definir_destaque');
                $midia->tipo_midia = "imagem";
                $midia->alinhamento = Request::input('definir_destaque_alignment');
                $midia->titulo = Request::input('definir_destaque_title');
                
                //montar nome do arquivo, mover ele pro servidor local e setar o link de acordo
                $filename = Request::input('definir_destaque_linkar_para');
                if(!$filename) $filename = date('Y-m-d_H-i-s') . '_' . md5(uniqid(""));
                $extension = $this->_get_extension_url(Request::input('definir_destaque'));
                if(Request::input('definir_destaque')) copy( Request::input('definir_destaque'), 'upload/posts/'.Request::input('cliente_id').'/'.$filename.'.'.$extension);
                $midia->link = $filename.'.'.$extension;*/
            } else { // enviado via url
                $midia = new Midia;
                $midia->imagem = $midia_img;
                $midia->tipo_midia = "imagem";
                $midia->titulo = Request::input('definir_destaque_title');                
                $midia->desc = null;
                $midia->link = Request::input('definir_destaque_linkar_para');
                $midia->alinhamento = Request::input('definir_destaque_alignment');
                $midia->save();
            }

            
        $post = new Post;

        $post->fill(Request::all());
        $post->desc = $this->_replaceGallery($post->desc);

        $post->user_id = Auth::user()->id;

        $post->slug = str_slug(Request::input('titulo'));

        // verifica se já existe slug e adiciona dash ao final
        do {
            $ct = 1;
            $t = Post::where('slug',$post->slug)->first();
            if($t) {
                $post->slug .= '-';
            }
        } while ($t);
        
        // se for definida data  publicar_em		
        if (Request::input('publicar-em-obrigatorio')) {
            $post->publicar_em = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', strtotime(Request::input('ano') . '-' . Request::input('mes') . '-' . Request::input('dia') . ' ' . Request::input('hora') . ':' . Request::input('minuto') . ':00'))));
        } else {
            $post->publicar_em = date('Y-m-d H:i:s');
        }

        /* STATUS POST
          1 = rascunho
          2 = agendado / publicado  (aqui depende do campo publicar em)
          3 = desativado (lixo) */
        if (Request::input('rascunho') || Request::input('preview')) {
            $post->status = '1';
        } else {
            $post->status = '2';
        }

        $post->save();
        if(@$midia){ $post->midia()->sync(array($midia->id)); }
        // vincula tag selecionada ao post
        $post->tag()->attach(Request::input('tags'));

        // vincula tipos_acao selecionada ao post 
        $post->tiposAcao()->attach(Request::input('tipos_acao'));

        // vincula concorrente selecionado ao post 
        if(Request::has('concorrente')){
            $post->concorrente()->attach(Request::input('concorrente'));
        }

        // CATEGORIAS
        $categorias_subcategorias = array_combine(Input::get('categoria_id'), Input::get('subcategoria_id'));

        // valida novas categorias e subcategorias e vincula
        foreach ($categorias_subcategorias as $categoria_id => $subcategoria_id) {
            $categoria = Categoria::find($categoria_id);
            if($categoria){
                if($subcategoria_id != ''){
                    $subcategoria = Subcategoria::find($subcategoria_id);
                    if($subcategoria) { 
                        $subcategoria = $subcategoria_id; 
                    } else {
                        $subcategoria = null;
                    }
                } else {
                    $subcategoria = null;
                }

                $post->categoria()->attach($categoria_id,array('sub_categoria_id'=>$subcategoria));
            }
        }

        // if salvar rascunho
        if (Request::input('rascunho') || Request::input('preview')) {
            // salvar em post histórico
            $postHistorico = new PostHistorico;

            $postHistorico->fill($post->toArray());
            $postHistorico->user_id = Auth::user()->id;
            $postHistorico->post_id = $post->id;

            $postHistorico->save();
            if(@$midia){ $postHistorico->midia()->sync(array($midia->id)); }
            // vincula tag selecionada ao posthistorico
            $postHistorico->tag()->attach(Request::input('tags'));

            // vincula tipos_acao selecionada ao posthistorico
            $postHistorico->tiposAcao()->attach(Request::input('tipos_acao'));

            // vincula concorrente selecionado ao posthistorico 
            if(Request::has('concorrente')){
                $postHistorico->concorrente()->attach(Request::input('concorrente'));
            }
            
            // valida novas categorias e subcategorias e vincula
            foreach ($categorias_subcategorias as $categoria_id => $subcategoria_id) {
                $categoria = Categoria::find($categoria_id);
                if($categoria){
                    if($subcategoria_id != ''){
                        $subcategoria = Subcategoria::find($subcategoria_id);
                        if($subcategoria) { 
                            $subcategoria = $subcategoria_id; 
                        } else {
                            $subcategoria = null;
                        }
                    } else {
                        $subcategoria = null;
                    }

                    $postHistorico->categoria()->attach($categoria_id,array('sub_categoria_id'=>$subcategoria));
                }
            }

            // atualiza em post indicando o id do histórico / rascunho
            $post->post_historico_id = $postHistorico->id;
            $post->update();
            if(@$midia) $post->midia()->save($midia);

            if(Request::input('rascunho')){
                return view('post.mensagem_rascunho_cadastrado')->with('post', $post); 
            }
            else if(Request::input('preview')){
                return Redirect::action('PostController@edit', array($post->id,$post->cliente->slug))->with('open_preview',true);
            }

            
        } else {
            // se NÃO for rascunho

            // cria notificação

            // from Auth::user()->id
            // to  user  $to_user
            // to  cliente  $post->cliente_id
            // parametro 1  post  $post->id
            // type = 1-1  criou post
           
            // array de ids to user
            $to_users = Cliente::find($post->cliente_id)->user()->lists('id');
            
            foreach ($to_users as $to_user)
            {
                $na = new Notification;

                $na->from = Auth::user()->id;
                $na->to_user = $to_user;
                $na->to_cliente = $post->cliente_id;
                $na->parametro1 = $post->id;
                $na->type = '1-1';
                $na->save();
            }     
        }

        return view('post.mensagem_cadastrado')->with('post', $post);
    }

    public function edit(Post $post, $cliente_default = null) {
        // em $post está recebendo o post atual oficial, mas primeiro vamos verificar se não existe nenhum rascunho em aberto para esse post
        if ($post->post_historico_id > 0) {
            // busca rascunho em aberto para o post atual
            $postHistorico = PostHistorico::find($post->post_historico_id);
            $post->fill($postHistorico->toArray());
            $post->tag = $postHistorico->tag()->get();
            $post->tiposAcao = $postHistorico->TiposAcao()->get();
            $post->concorrente = $postHistorico->concorrente()->get();
            $post->midia = $postHistorico->midia;
        }

        $cliente_default = _clienteDefault($cliente_default, $post);

        // array para popular o select com os clientes do usuário logado
        $select_escolha_o_cliente = _selectEscolhaOCliente();

        // busca tags para popular o select
        $select_tags = $cliente_default->tag()->lists('name', 'id');

        // busca categorias para popular o select
        $select_categorias = $this->_selectCategorias($cliente_default);
        if(!$select_categorias){
            return Redirect::action('CategoriaController@lista')->withErrors(array('Cadastre ao menos uma Categoria para o Cliente atual')); 
        }


        // popular categorias com retorno dos erros de validação ou do post
        if(Input::old('categoria_id')){
            $categoria_id = Input::old('categoria_id');
            $subcategoria_id = Input::old('subcategoria_id');
            
            $post_categorias = Categoria::whereIn('id',$categoria_id)->get();
        } else {
            $post_categorias = $post->categoria;
            $categoria_id = $post_categorias->lists('id');

            $subcategoria_id = array();
            foreach ($post_categorias as $categoria) {
                $subcategoria_id[] = $categoria->pivot->sub_categoria_id;
            }            
        }

        // cria os selects e options das categorias e subcategorias
        $categorias_subcategorias_html = '';   
        $c=1;  // flag para saber se é primeira categoria para poder exibir o botão de remover   
        foreach ($categoria_id as $key => $categoria_id) {

            $categorias_options = '';
            //$select_categorias = Categoria::find($categoria_id)->lists('name','id');
            foreach ($select_categorias as $id => $name) {
                $selected = '';
                if($categoria_id == $id) { $selected = 'selected'; }
                $categorias_options .= '<option value="'.$id.'" '.$selected.'>'.$name.'</option>';
            }

            $categorias_subcategorias_html .= "<div class='categoria-grupo'>
                        <br>";

            if($c>1){
                $categorias_subcategorias_html .= "<a class='btn btn-cinza btn-extra-pequeno align-center float-right mb-10 btn-remover-categoria'>REMOVER</a>";
            }

            $categorias_subcategorias_html .= "    
                        <select class='form-control arrow-preto-amarelo onChangeAlteraSubcategoria' name='categoria_id[]'>".$categorias_options."</select>
                        <br>";

            if($subcategoria_id[$key]){
                $sub_categoria_id = $subcategoria_id[$key];
                $select_subcategorias = _selectSubCategorias($categoria_id);
                $subcategorias_options = '';
                foreach ($select_subcategorias as $id => $name) {
                    $selected = '';
                    if($sub_categoria_id == $id) { $selected = 'selected'; }
                    $subcategorias_options .= '<option value="'.$id.'" '.$selected.'>'.$name.'</option>';
                }

                $categorias_subcategorias_html .= "<select class='form-control arrow-preto-amarelo selectSubcategorias ' name='subcategoria_id[]'>".$subcategorias_options."</select>";

            } else {

                $select_subcategorias = _selectSubCategorias($categoria_id);
                if($select_subcategorias){
                    $subcategorias_options = '<option value="" selected>Selecione a Subcategoria</option>';
                    foreach ($select_subcategorias as $id => $name) {                    
                        $subcategorias_options .= '<option value="'.$id.'">'.$name.'</option>';
                    }

                    $categorias_subcategorias_html .= "<select class='form-control arrow-preto-amarelo selectSubcategorias ' name='subcategoria_id[]'>".$subcategorias_options."</select>";
                } else {
                    $categorias_subcategorias_html .= "<select class='form-control arrow-preto-amarelo selectSubcategorias hide' name='subcategoria_id[]'><option value='' selected>Selecione a Categoria</option></select>";
                }
            }


            $categorias_subcategorias_html .= "<br>
                                        </div><!-- /.categoria-grupo -->";
            $c++;

        }


        // busca tipos de ação para popular os checkbox
        $checkbox_tiposAcao = $this->_checkboxTiposAcao($cliente_default);

        // busca concorrentes para popular o select
        $select_concorrentes = $this->_selectConcorrentes($cliente_default);

        if(session('open_preview')){
            $open_preview = true;
        } else {
            $open_preview = false;
        }

        return view('post.edit', compact('post', 'cliente_default', 'select_escolha_o_cliente', 'select_tags', 'select_categorias', 'select_subcategorias', 'checkbox_tiposAcao', 'categorias_subcategorias_html', 'select_concorrentes', 'open_preview'));
    }

    public function update(Post $post) {
        // replace Gallery replaceGallery na descrição antes de efetuar a validação
        $requestInput = Request::input();
        $requestInput['desc'] = $this->_replaceGallery(Request::get('desc'));
        Request::replace($requestInput);

        $midia_img = null;
        if(Request::input('definir_destaque_flag_if_from_url') == 1){
            if(Request::input('definir_destaque_url') && trim(Request::input('definir_destaque_url')) != ''){

                // verifica se cliente é do usuário logado
                $temp_cliente_slug = Cliente::findOrFail(Input::get('cliente_id'))->slug;
                $temp_cliente_default = _clienteDefault($temp_cliente_slug);
                
                // url encode na url
                $url = explode('/',Request::input('definir_destaque_url'));
                foreach ($url as $key => $value) {
                    if($value == 'http:' || $value == 'https:'){ continue; }
                    if(_is_urlEncoded($value)){
                        $url[$key] = $value;
                    } else {
                        $url[$key] = urlencode($value);
                    }
                }
                $url = implode('/',$url);                
              
                // baixa arquivo imagem pro servidor
                $file   = @file_get_contents($url); 

                if($file === FALSE) { 
                    return Redirect()->back()->withErrors(array('Url da Imagem de Destaque não está acessível.'));
                }                
                  
                $dados_imagem = getimagesize($url);
                if(!$dados_imagem){
                    return Redirect()->back()->withErrors(array('Url da Imagem de Destaque não é uma imagem válida.'));
                }
                // detecta extensão
                switch ($dados_imagem[2]) {
                    case '1':
                        $ext = 'gif';
                        break;
                    case '2':
                        $ext = 'jpg';
                        break;
                    case '3':
                        $ext = 'png';
                        break;
                    case '4':
                        $ext = 'bmp';
                        break;
                }
                // define caminho final do arquivo
                $midia_img    = 'upload/posts/'.$temp_cliente_default->id.'/'.$temp_cliente_default->id . '-' . uniqid() . rand(1111, 9999) . '.'.$ext;
                // efetua ação de salvar arquivo
                $result = file_put_contents($midia_img, $file);

            }
        }

        // efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
        $this->validateWithCustomAttribute(Request::instance(), $this->_rules('update', $post, Request::input('cliente_id'), Request::input('rascunho')), $messages = $this->_customMessages(), $this->_customAttributes());


        if($midia_img == null){ // enviado via anexo da livraria library
            if(trim(Request::input('definir_destaque_imagem')) != '' && Request::input('definir_destaque_flag_if_from_url') == 0){ // entra se foi anexado algo via library
                $midia = new Midia;
                $midia->imagem = Request::input('definir_destaque_imagem');
                $midia->tipo_midia = "imagem";
                $midia->titulo = Request::input('definir_destaque_title');                
                $midia->desc = Request::input('definir_destaque_desc');
                $midia->link = Request::input('definir_destaque_linkar_para');
                $midia->alinhamento = Request::input('definir_destaque_alignment');
                $midia->save();
            } else { // não foi anexado nada
                $midia = null;
                // busca midias já existentes no post, se houver post histórico busca do histórico                
                if($post->post_historico_id > 0){
                    $postHistorico = PostHistorico::find($post->post_historico_id);
                    $temp_midia = $postHistorico->midia;
                    if(count($temp_midia)){
                        $midia = $temp_midia->first();
                    }
                } else {
                    $temp_midia = $post->midia;
                    if(count($temp_midia)){
                        $midia = $temp_midia->first();
                    }
                }
            }
        } else { // enviado via url
            $midia = new Midia;
            $midia->imagem = $midia_img;
            $midia->tipo_midia = "imagem";
            $midia->titulo = Request::input('definir_destaque_title');                
            $midia->desc = null;
            $midia->link = Request::input('definir_destaque_linkar_para');
            $midia->alinhamento = Request::input('definir_destaque_alignment');
            $midia->save();
        }

        $categorias_subcategorias = array_combine(Input::get('categoria_id'), Input::get('subcategoria_id'));
        

        //$post->midia->desc não tem descrição no formulário
        // Request::input('definir_destaque_alignment')); não tem no banco espaço para alinhamento
        //die;
        // if NOT salvar rascunho então altera o post
        if (!Request::input('rascunho') && !Request::input('preview')) {

            $post->fill(Request::all());
            $post->desc = $this->_replaceGallery($post->desc);

            $post->user_id = Auth::user()->id;

            $post->slug = str_slug(Request::input('titulo'));

            // verifica se já existe slug e adiciona post id ao final
            do {
                $ct = 1;
                $t = Post::where('slug',$post->slug)->where('id','<>',$post->id)->first();
                if($t) {
                    $post->slug .= '-'.$post->id;
                }
            } while ($t);

            $post->publicar_em = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', strtotime(Request::input('ano') . '-' . Request::input('mes') . '-' . Request::input('dia') . ' ' . Request::input('hora') . ':' . Request::input('minuto') . ':00'))));

            /* STATUS POST
              1 = rascunho
              2 = agendado / publicado  (aqui depende do campo publicar em)
              3 = desativado (lixo) */
            if (Request::input('rascunho') || Request::input('preview')) {
                $post->status = '1';
            } else {
                $post->status = '2';
            }

            if(@$midia){
                $post->midia()->sync(array($midia->id));
            }
            $post->update();

            if (is_array(Request::input('tags'))) {
                // vincula tag selecionada ao post
                $post->tag()->sync(Request::input('tags'));
            } else {
                $post->tag()->sync(array());
            }

            if (is_array(Request::input('tipos_acao'))) {
                // vincula tipos_acao selecionada ao post
                $post->tiposAcao()->sync(Request::input('tipos_acao'));
            }

            // vincula concorrente selecionado ao post 
            if(Request::has('concorrente')){            
                $post->concorrente()->sync(array(Request::input('concorrente')));
            } else {
                $post->concorrente()->sync(array());
            }


            // CATEGORIAS
            
            // remove categorias anteriormente vinculadas
            $post->categoria()->detach();

            // valida novas categorias e subcategorias e vincula
            foreach ($categorias_subcategorias as $categoria_id => $subcategoria_id) {
                $categoria = Categoria::find($categoria_id);
                if($categoria){
                    if($subcategoria_id != ''){
                        $subcategoria = Subcategoria::find($subcategoria_id);
                        if($subcategoria) { 
                            $subcategoria = $subcategoria_id; 
                        } else {
                            $subcategoria = null;
                        }
                    } else {
                        $subcategoria = null;
                    }

                    $post->categoria()->attach($categoria_id,array('sub_categoria_id'=>$subcategoria));
                }
            }

            // se NÃO for rascunho

            // cria notificação

            // from Auth::user()->id
            // to  user  $to_user
            // parametro 1  post  $post->id
            // type = 1-2  editou post

            // array de ids to user
            $to_users = Cliente::find($post->cliente_id)->user()->lists('id');
            
            foreach ($to_users as $to_user)
            {
                $na = new Notification;

                $na->from = Auth::user()->id;
                $na->to_user = $to_user;
                $na->to_cliente = $post->cliente_id;
                $na->parametro1 = $post->id;
                $na->type = '1-2';
                $na->save();
            }
                 
        }


        // salvar em post histórico mesmo não sendo apenas um rascunho
        $postHistorico = new PostHistorico;

        // if salvar rascunho
        if (Request::input('rascunho') || Request::input('preview')) {
            $postHistorico->fill(Request::all());
            $postHistorico->desc = $this->_replaceGallery(Request::input('desc'));
            
            $postHistorico->user_id = Auth::user()->id;
            $postHistorico->slug = str_slug(Request::input('titulo'));
            $postHistorico->publicar_em = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s', strtotime(Request::input('ano') . '-' . Request::input('mes') . '-' . Request::input('dia') . ' ' . Request::input('hora') . ':' . Request::input('minuto') . ':00'))));
            $postHistorico->status = '1'; // rascunho		
        } else {
            $postHistorico->fill($post->toArray());
            $postHistorico->user_id = Auth::user()->id;
        }

        // vincula o posthistorico ao post oficial
        $postHistorico->post_id = $post->id;


        $postHistorico->save();

        if(@$midia){ 
            $postHistorico->midia()->sync(array($midia->id)); 
        } 

        // vincula tag selecionada ao posthistorico
        $postHistorico->tag()->attach(Request::input('tags'));

        // vincula tipos_acao selecionada ao posthistorico
        $postHistorico->tiposAcao()->attach(Request::input('tipos_acao'));

        // vincula concorrente selecionado ao posthistorico 
        if(Request::has('concorrente')){
            $postHistorico->concorrente()->attach(Request::input('concorrente'));
        }
        // CATEGORIAS
        
        // remove categorias anteriormente vinculadas
        $postHistorico->categoria()->detach();

        // valida novas categorias e subcategorias e vincula
        foreach ($categorias_subcategorias as $categoria_id => $subcategoria_id) {
            $categoria = Categoria::find($categoria_id);
            if($categoria){
                if($subcategoria_id != ''){
                    $subcategoria = Subcategoria::find($subcategoria_id);
                    if($subcategoria) { 
                        $subcategoria = $subcategoria_id; 
                    } else {
                        $subcategoria = null;
                    }
                } else {
                    $subcategoria = null;
                }

                $postHistorico->categoria()->attach($categoria_id,array('sub_categoria_id'=>$subcategoria));
            }
        }

        // if salvar rascunho
        if (Request::input('rascunho') || Request::input('preview')) {
            // atualiza em post oficial indicando o id do histórico / rascunho
            $post->post_historico_id = $postHistorico->id;
            $post->update();

            if(Request::input('rascunho')){
                return view('post.mensagem_rascunho_editado')->with('post', $post);
            } else if(Request::input('preview')){
                return Redirect::action('PostController@edit', array($post->id,$post->cliente->slug))->with('open_preview',true);
            }
        } else {
            // atualiza em post indicando que o post foi publicado, ou seja, não tem mais rascunho vinculado
            $post->post_historico_id = 0;
            $post->update();
        }

        return view('post.mensagem_editado')->with('post', $post);
    }

    public function destroy(Post $post) {
        $post->delete();

        return view('post.mensagem_excluido')->with('post', $post);
    }

    // MODAL MODAIS MODALS 
    // MODAL MODAIS MODALS 
    // MODAL MODAIS MODALS 

    public function ajaxUpload() {
        // getting all of the post data
        $file = array('image' => Input::file('image'));
        $dragdrop = false;
        if (empty(Input::file('image'))) {
            $file = array('image' => Input::file('file'));
            $dragdrop = true;
        }

        // setting up rules
        $imageRules = array('image' => 'required|max:1000'); //mimes:jpeg,bmp,png and for max size max:10000
        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($file, $imageRules);

        if (!(Input::get('cliente_id') > 0)) {
            echo json_encode(array(
                'name' => null,
                'error' => 'Recarregue a página e tente novamente.',
            ));
            die();
        }

        if ($validator->fails()) {

            $error_messages = $validator->messages();

            $error_messages_image = $error_messages->first('image');

            echo json_encode(array(
                'name' => null,
                'error' => $error_messages_image,
            ));

            die();
        } else {
            // checking file is valid. if it was uploaded
            if ($file['image']->isValid()) {

                $cliente_slug = Cliente::findOrFail(Input::get('cliente_id'))->slug;

                $cliente_default = _clienteDefault($cliente_slug);


                $destinationPath = 'upload/posts/' . $cliente_default->id . '/'; // upload path
                $extension = $file['image']->getClientOriginalExtension(); // getting image extension
                $fileName = $cliente_default->id . '-' . uniqid() . rand(1111, 9999); // renameing image
                $fileNameWithExt = $fileName . '.' . $extension;
                $file['image']->move($destinationPath, $fileNameWithExt); // uploading file to given path

                if ($dragdrop) {
                    $ret = json_encode(array(
                        'id' => $fileName,
                        'url' => url($destinationPath . $fileNameWithExt),
                    ));
                } else {
                    $ret = json_encode(array(
                        'name' => $fileNameWithExt,
                        'error' => null,
                    ));
                }

                die($ret);
            } else {
                echo json_encode(array(
                    'name' => null,
                    'error' => 'Erro ao enviar seu arquivo',
                ));
                die();
            }
        }
    }

    public function ajaxUploadViaUrl()
    {
        if(Request::input('definir_destaque_url') && trim(Request::input('definir_destaque_url')) != ''){

            // verifica se cliente é do usuário logado
            $temp_cliente_slug = Cliente::findOrFail(Input::get('cliente_id'))->slug;
            $temp_cliente_default = _clienteDefault($temp_cliente_slug);
            
            // url encode na url
            $url = explode('/',Request::input('definir_destaque_url'));
            foreach ($url as $key => $value) {
                if($value == 'http:' || $value == 'https:'){ continue; }
                if(_is_urlEncoded($value)){
                    $url[$key] = $value;
                } else {
                    $url[$key] = urlencode($value);
                }
            }
            $url = implode('/',$url);                

            // baixa arquivo imagem pro servidor
            $file   = @file_get_contents($url); 

            if($file === FALSE) { 
                return Redirect()->back()->withErrors(array('Url da Imagem de Destaque não está acessível.'));
            }                
              
            $dados_imagem = getimagesize($url);
            if(!$dados_imagem){
                return Redirect()->back()->withErrors(array('Url da Imagem de Destaque não é uma imagem válida.'));
            }
            // detecta extensão
            switch ($dados_imagem[2]) {
                case '1':
                    $ext = 'gif';
                    break;
                case '2':
                    $ext = 'jpg';
                    break;
                case '3':
                    $ext = 'png';
                    break;
                case '4':
                    $ext = 'bmp';
                    break;
            }
            // define caminho final do arquivo
            $midia_img    = 'upload/posts/'.$temp_cliente_default->id.'/'.$temp_cliente_default->id . '-' . uniqid() . rand(1111, 9999) . '.'.$ext;
            // efetua ação de salvar arquivo
            $result = file_put_contents($midia_img, $file);
            echo $midia_img;
        }
    }

    public function ajaxInsertLibrary() {
        $cliente_slug = Cliente::findOrFail(Input::get('cliente_id'))->slug;

        $cliente_default = _clienteDefault($cliente_slug);

        return view('post.modal.insert-library')->with('cliente_default', $cliente_default);
    }

    public function ajaxCreateLibrary() {
        $cliente_slug = Cliente::findOrFail(Input::get('cliente_id'))->slug;

        $cliente_default = _clienteDefault($cliente_slug);

        return view('post.modal.create-library')->with('cliente_default', $cliente_default);
    }

    public function ajaxDefineLibrary() {
        $cliente_slug = Cliente::findOrFail(Input::get('cliente_id'))->slug;

        $cliente_default = _clienteDefault($cliente_slug);

        return view('post.modal.define-library')->with('cliente_default', $cliente_default);
    }

    public function ajaxOrganizeLibrary() {
        $cliente_slug = Cliente::findOrFail(Input::get('cliente_id'))->slug;

        $cliente_default = _clienteDefault($cliente_slug);

        $folder = 'upload/posts/' . $cliente_default->id . '/';
        $filetype = '*.*';
        $files = glob($folder.$filetype);
        $count = count($files);
         
        $filesArray = array();
        for ($i = 0; $i < $count; $i++) {
            $filesArray[date ('YmdHis', filemtime($files[$i]))] = $files[$i];
        }
         
        krsort($filesArray);

        $directories = array_filter(glob($folder . '*'), 'is_dir');

        return view('post.modal.organize-library')->with(compact(['cliente_default', 'filesArray', 'directories']));
    }

    public function ajaxPostDestroy()
    {
        if(empty(Request::get('posts'))){
            echo 'selecione ao menos um post para aplicar a ação';
            die();
        }

        foreach (Request::get('posts') as $post) {
            // procura nos posts do usuário logado se existe o post com id recebido
            $pt = Auth::user()->post()->find($post);
            if(!$pt){
                echo 'Post selecionado não encontrado, apenas ignora e passa para o próximo';
                continue;
            } else {
                $pt->delete();
            }
        }
    }


    public function ajaxFiltrosTodosPosts()
    { 
        if(!empty(Request::get('data_inicio'))){
            Session::put('filtro_data_inicio_todos_posts', Request::get('data_inicio'));
        } else {
            Session::forget('filtro_data_inicio_todos_posts');
        }

        if(!empty(Request::get('data_final'))){
            Session::put('filtro_data_final_todos_posts', Request::get('data_final'));
        } else {
            Session::forget('filtro_data_final_todos_posts');
        }

        if(!empty(Request::get('subcategorias'))){
            Session::put('filtro_categorias_todos_posts', Request::get('categorias'));
        } else {
            Session::forget('filtro_categorias_todos_posts');
        }

        if(!empty(Request::get('subcategorias'))){
            Session::put('filtro_subcategorias_todos_posts', Request::get('subcategorias'));
        } else {
            Session::forget('filtro_subcategorias_todos_posts');
        }


        if(!empty(Request::get('tag'))){
            Session::put('filtro_tag_todos_posts', Request::get('tag'));
        } else {
            Session::forget('filtro_tag_todos_posts');
        }      

        if(!empty(Request::get('busca'))){
            Session::put('filtro_busca_todos_posts', Request::get('busca'));
        } else {
            Session::forget('filtro_busca_todos_posts');
        }   
    }

 

    // POST DESTAQUE POSTS EM DESTAQUE // POST DESTAQUE POSTS EM DESTAQUE 
    // POST DESTAQUE POSTS EM DESTAQUE // POST DESTAQUE POSTS EM DESTAQUE 
    // POST DESTAQUE POSTS EM DESTAQUE // POST DESTAQUE POSTS EM DESTAQUE 
    // POST DESTAQUE POSTS EM DESTAQUE // POST DESTAQUE POSTS EM DESTAQUE 

    public function destaques($cliente_default=null)
    {
        $por_pagina = 15;
        // adiciona na url o cliente
        if($cliente_default == null){
            $cliente_default = _clienteDefault($cliente_default);
            return Redirect::action('PostController@destaques', array($cliente_default->slug));
        } else {
            $cliente_default = _clienteDefault($cliente_default);
        }  

        $posts = Post::postsAtivos()->clienteSlug($cliente_default->slug)->where('destaque',1)->paginate($por_pagina);

        return view('post.destaques', compact('posts','cliente_default'));
    }

    public function ajaxPostDestaque($post)
    {
        $cliente_default = _clienteDefault(null);
        $posts = Post::postsAtivos()->clienteSlug($cliente_default->slug)->get();
        if(count($posts) && $posts->contains($post->id)){
            $post->destaque = 0;
            $post->update();
            return redirect()->back();
            //echo 'atualizado';
        } else {
            //echo 'erro';
        }
        
    }

    // gerar pdf
    // gerar pdf
    // gerar pdf


    public function pdfPosts($cliente_default=null)
    {


        // adiciona na url o cliente
        if($cliente_default == null){
            $cliente_default = _clienteDefault($cliente_default);
            return \Redirect::action('PostController@pdfPosts', $cliente_default->slug);
        } else {
            $cliente_default = _clienteDefault($cliente_default);
        }   

        $por_pagina = 15;        

        // array para popular o select com os clientes do usuário logado
        $select_escolha_o_cliente = _selectEscolhaOCliente();

        // select datas
        $select_datas_todas_disponiveis = \DB::select("SELECT DISTINCT(DATE_FORMAT(publicar_em,'%Y-%m')) as datapost FROM posts WHERE cliente_id = ? order by publicar_em desc", array($cliente_default->id));

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
            if (session('filtro_categorias_pdf')) { // se há categoria selecionada via sessão, busca as subcategorias dela
                $select_subcategorias = _selectSubCategorias(session('filtro_categorias_pdf'));
            } else {
                // se NÃO há categoria em sessão, pega a primeira categoria
                $select_subcategorias = _selectSubCategorias($primeira_categoria_id = key($select_categorias));
            }
        } else {
            $select_subcategorias = array('null' => 'null');
        }


        // busca posts com os filtros em sessão
        
        $posts = Post::postsAtivos()->clienteSlug($cliente_default->slug)->orderBy('publicar_em', 'desc');

        if (session('filtro_data_pdf')) {
            $posts = $posts->where('publicar_em','<=',date('Y-m-t',strtotime(session('filtro_data_pdf'))))->where('publicar_em','>=',date('Y-m-01',strtotime(session('filtro_data_pdf'))));
        }

        if (session('filtro_categorias_pdf')) {
            $posts = $posts->categoriaId(session('filtro_categorias_pdf'));
        }

        /*if (session('filtro_subcategorias_pdf')) {
            $posts = $posts->where('subcategoria_id',session('filtro_subcategorias_pdf'));
        }*/

        if (session('filtro_busca_pdf')) {
            $posts = $posts->where(function($query){
                $query->where('titulo','like','%'.str_replace(' ','%',session('filtro_busca_pdf'))."%")->orWhere('desc','like','%'.str_replace(' ','%',session('filtro_busca_pdf'))."%");
            });
        }

        $posts = $posts->paginate($por_pagina);

        // busca posts selecionados para pré marcar
        $postsSelecionados_pdf = session('postsSelecionados_pdf',array());


        return view('post.pdf_posts', compact('cliente_default', 'select_escolha_o_cliente', 'select_datas', 'select_categorias', 'select_subcategorias','posts', 'postsSelecionados_pdf'));

    }

    public function pdfGerar($cliente_default=null)
    {

        $cliente_default = _clienteDefault($cliente_default);

        $postsValidos = $this->_postsValidos($cliente_default);

        $is_pdf = true;
        
        $pdf = \App::make('dompdf'); 
        //echo '<pre>';print_r($postsValidos);exit();    
        $html = view('pdf/post_gerarPDF', compact('cliente_default','is_pdf','postsValidos'))->render();

        $pdf->loadHTML($html);
        
        return $pdf->stream();
    }

    public function ajaxFiltrosPDF()
    { 
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

        /*if(!empty(Request::get('subcategorias'))){
            Session::put('filtro_subcategorias_pdf', Request::get('subcategorias'));
        } else {
            Session::forget('filtro_subcategorias_pdf');
        }  */    

        if(!empty(Request::get('busca'))){
            Session::put('filtro_busca_pdf', Request::get('busca'));
        } else {
            Session::forget('filtro_busca_pdf');
        }   
    }

    public function ajaxPostSelecionadoPDF()
    {
        if(Request::get('acao') == 'reset'){
            Session::forget('postsSelecionados_pdf');
            die();
        }

        if(!(int)Request::get('postId')){
            die();
        }

        if(session('postsSelecionados_pdf')){
            $postsSelecionados = session('postsSelecionados_pdf');
        } else {
            $postsSelecionados = array();
        }

        if(Request::get('acao') == 'set'){
            $postsSelecionados[Request::get('postId')] = true;
            Session::put('postsSelecionados_pdf', $postsSelecionados);
        } else if(Request::get('acao') == 'unset'){
            unset($postsSelecionados[Request::get('postId')]);
            Session::put('postsSelecionados_pdf', $postsSelecionados);
        }
    }
}
