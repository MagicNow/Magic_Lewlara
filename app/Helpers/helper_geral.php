<?php

function generateRandomString($length = 10) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}
function _selectEscolhaOCliente(){
	return Auth::user()->cliente()->orderBy('name','asc')->lists('name','slug');
}
function _ordenarPor(){
    //array para popular o select de ordenar por
    return array(
            ''=>'Ordenar por',
            'az'=>'Ordem alfabética A-Z',
            'za'=>'Ordem alfabética Z-A',
            'data-cresc'=>'Data crescente de cadastro',
            'data-decresc'=>'Data decrescente de cadastro',
        );
}

function _selectSubcategorias($categoria_id){
	return App\Categoria::where('id',$categoria_id)->firstOrFail()->subcategoria()->lists('name','id');
}

function _human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor].'B';
}

function _is_urlEncoded($string){
	if (preg_match("@^[a-zA-Z0-9%+-_]*$@", $string)) {
		return true;
	}
	return false;
}

function _mes_paraNome($mes_numero){
	switch ($mes_numero) {
	    case '01':
	        $mes_num = 'Janeiro';
	        break;
	    case '02':
	        $mes_num = 'Fevereiro';
	        break;
	    case '03':
	        $mes_num = 'Março';
	        break;
	    case '04':
	        $mes_num = 'Abril';
	        break;
	    case '05':
	        $mes_num = 'Maio';
	        break;
	    case '06':
	        $mes_num = 'Junho';
	        break;
	    case '07':
	        $mes_num = 'Julho';
	        break;
	    case '08':
	        $mes_num = 'Agosto';
	        break;
	    case '09':
	        $mes_num = 'Setembro';
	        break;
	    case '10':
	        $mes_num = 'Outubro';
	        break;
	    case '11':
	        $mes_num = 'Novembro';
	        break;
	    case '12':
	        $mes_num = 'Dezembro';
	        break;
	}
	return $mes_num;
}
function _mes_paraNum($mes_nome){
    switch ($mes_nome) {
        case 'Janeiro':
            $mes_num = '01';
            break;
        case 'Fevereiro':
            $mes_num = '02';
            break;
        case 'Março':
            $mes_num = '03';
            break;
        case 'Abril':
            $mes_num = '04';
            break;
        case 'Maio':
            $mes_num = '05';
            break;
        case 'Junho':
            $mes_num = '06';
            break;
        case 'Julho':
            $mes_num = '07';
            break;
        case 'Agosto':
            $mes_num = '08';
            break;
        case 'Setembro':
            $mes_num = '09';
            break;
        case 'Outubro':
            $mes_num = '10';
            break;
        case 'Novembro':
            $mes_num = '11';
            break;
        case 'Dezembro':
            $mes_num = '12';
            break;
    }
    return $mes_num;
}

function _limpaHtmlJs($script_str, $comFormatacao=false, $limitarParagrafo=false) {
    $script_str = htmlspecialchars_decode($script_str);
    $search_arr = array('<script', '</script>');
    $script_str = str_ireplace($search_arr, $search_arr, $script_str);
    $split_arr = explode('<script', $script_str);
    $remove_jscode_arr = array();
    foreach($split_arr as $key => $val) {
        $newarr = explode('</script>', $split_arr[$key]);
        $remove_jscode_arr[] = ($key == 0) ? $newarr[0] : $newarr[1];
    }
    $retorno = implode('', $remove_jscode_arr);
    if ($limitarParagrafo) {
        $retorno = str_replace('<p>', '', $retorno);
        $retorno = str_replace('</p>', '<br>', $retorno);
        $retorno = explode('<br>', $retorno);
        if (count($retorno) > 0) {
            $retorno = $retorno[0];
        }
    }
    if($comFormatacao){
    	return strip_tags($retorno,'<p><br><br /><br/><img>');
    } else {
    	return strip_tags($retorno);
    }
    
}

/**
 * Get excerpt from string
 * 
 * @param String $str String to get an excerpt from
 * @param Integer $startPos Position int string to start excerpt from
 * @param Integer $maxLength Maximum length the excerpt may be
 * @return String excerpt
 */
function _resumo($str, $startPos=0, $maxLength=200) {
	if(strlen($str) > $maxLength) {
		$excerpt   = substr($str, $startPos, $maxLength-3);
		$lastSpace = strrpos($excerpt, ' ');
		$excerpt   = substr($excerpt, 0, $lastSpace);
		$excerpt  .= '...';
	} else {
		$excerpt = $str;
	}
	
	return $excerpt;
}




function _replaceGalleryPDF($desc) {
        // verifica se galeria já está limpa ou suja
        $teste_limpo = explode('lSPager lSGallery',$desc);
        if (!array_key_exists("1",$teste_limpo)){
          //  return $desc;
        }

        //echo '<style>*{ display: block!important}</style><br/><h1>Fatiando</h1></br></br></br>';
        //echo '______________________________________________________________________________</br></br></br>';
        $segments = array();
        
        //echo '<textarea>'.$desc.'</textarea><br/></br>';
        $galerias = explode('<div class="post-galeria-posicao">', $desc);
        //$segments[] = $galerias[0]; //possível pedaço de texto antes da primeira galeria
        foreach($galerias as $galeria){
            $galeria_inner = explode('</div>', $galeria);
            $segments[] = $galeria_inner[0];//galeria em si
            if(isset($galeria_inner[1])) $segments[] = $galeria_inner[1];//possível texto entre essa galeria e a próxima, ou talvez entre essa galeria e o fim do desc
        }
        
        $n_of_segments = count($segments);
        

        for($i = 0; $i < $n_of_segments; $i++){
            
            $segment = $segments[$i];

            /*print('<pre>');
            print_r($segment);
            print('</pre>');*/

            if(strstr($segment, '<ul class="post-galeria">'))//se segmento for de galeria, pra cada galeria... 
            {
            	$images = array();
            	$cleanGallery = '<div class="galeria-pdf"><div class="primeira_imagem" align="center">';
            	
            	$segment = explode('<ul class="post-galeria">',$segment);
            	
            	$segment = $segment[1];
            	$segment = explode('</ul>',$segment);
            	$segment = $segment[0];

            	$imageChunks = explode('<img src="',$segment);
            	
            	$imageChunks = array_slice($imageChunks, 1);
            	
            	foreach($imageChunks as $img){ //para cada pedaço isolamos somente o src da imagem...
            	    $img = explode('"',$img); //...parando antes do /> da tag img
            	    $images[] = $img[0]; //e colocamos essas urls em um array images reinicializado para cada galeria achada            	    
            	}

            	$nr_img = (count($images)-1);
            	$primeira_imagem = 'galeria-img-grande';
            	$style = '';
            	foreach($images as $img){ //ainda no escopo de uma única galeria, criamos a galeria limpa
            	    
            	    if(!$primeira_imagem){ $style = 'style="width: '.(100/$nr_img).'%;"'; } 

            	    if($img) $cleanGallery .= '<img '.$style.' class="galeria-img '.$primeira_imagem.'" src="'.$img.'">'; //verificando que não estamos com nenhum node vazio sendo integrado na galeria limpa

            	    if($primeira_imagem == 'galeria-img-grande') { 
            	    	$cleanGallery.= '</div><br><br><br>';
            	    	$primeira_imagem = false; 
            	    }
            	}
            	
            	$cleanGallery .= '</div>';
            	$segments[$i] = $cleanGallery; //e colocamos a galeria limpa no lugar deste segmento que identificamos como uma galeria (suja)
            }
            
            
        }

        $new_desc = implode('', $segments);
        //echo $new_desc;
       
       	return $new_desc;
    }

function _limpaHtmlJsPDF($script_str,$comFormatacao=false) {
    $script_str = htmlspecialchars_decode($script_str);
    $search_arr = array('<script', '</script>');
    $script_str = str_ireplace($search_arr, $search_arr, $script_str);
    $split_arr = explode('<script', $script_str);
    $remove_jscode_arr = array();
    foreach($split_arr as $key => $val) {
        $newarr = explode('</script>', $split_arr[$key]);
        $remove_jscode_arr[] = ($key == 0) ? $newarr[0] : $newarr[1];
    }
    $retorno = implode('', $remove_jscode_arr);
    if($comFormatacao){
        $retorno = strip_tags($retorno,'<br><br /><br/><span><strong><img><p>');
        // remove tags vazias
        $pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/"; 
        return preg_replace($pattern, '', $retorno);         
    } else {
        return strip_tags($retorno);
    }
    
}

function _clienteDefault($cliente_default = null, $objeto = null) {

    // se especifica o cliente na url, retorna ele
    if ($cliente_default) { 
        $cliente_default = App\Cliente::where('slug', $cliente_default)->first();
        if(!$cliente_default){
            die(redirect('/'));
        }
        // verifica se o user tem acesso
        if ($cliente_default->user->contains(Auth::user()->id)) {
            Session::put('clienteDefault', $cliente_default->slug);
            return $cliente_default;
        } else {
            die(redirect('/'));
        }
    }

    // se passa o objeto atual, retorna o cliente desse objeto
    if ($objeto) { 
        Session::put('clienteDefault', $objeto->cliente->slug);
        return $objeto->cliente;
    }

    // verifica sessão
    if(Session::has('clienteDefault')){
        $cliente_default = App\Cliente::where('slug', Session::get('clienteDefault'))->firstOrFail();
        // verifica se o user tem acesso
        if ($cliente_default->user->contains(Auth::user()->id)) {
            return $cliente_default;
        } else {
            Session::forget('clienteDefault');
            die(redirect('/'));
        }
    }

    // caso contrário retorna o primeiro cliente do usuário logado
    if ($cliente_default == null) {          
        $cliente_default = Auth::user()->cliente()->orderBy('name', 'asc')->firstOrFail();
        Session::put('clienteDefault', $cliente_default->slug);
        return $cliente_default;
    }
}


//BLOG BLOG BLOG BLOG BLOG BLOG BLOG BLOG BLOG
//BLOG BLOG BLOG BLOG BLOG BLOG BLOG BLOG BLOG
//BLOG BLOG BLOG BLOG BLOG BLOG BLOG BLOG BLOG
    

    //LISTANDO CATEGORIAS DO MENU
    function _blogListarCategorias($cliente_default){
        return App\Categoria::ClienteSlug($cliente_default->slug)
                            ->orderBy('order', 'asc')
                            ->orderBy('name', 'asc')
                            ->with('post','subcategoria')
                            ->get()
                            ->filter(function ($categoria) {
                                if($categoria->post()->count()){ return true; } else { return false; }
                            });
    }
    //LISTANDO TAGS DO SIDEBAR DO BLOG
     function _blogListarTags($cliente_default){

        //PEGANDO TODAS AS TAGS RELACIONADO AO CLIENTE
        $tags = App\Tag::ClienteSlug($cliente_default->slug)->orderBy('name', 'asc')->get();
        //PEGANDO O NOME DA TAG E A QUANTIDADE DE VEZES QUE ELA APARECE
        $posts_tags = null;
        foreach ($tags as $key => $tag) {
            if($tag->post->count() < 1) { continue; }
            //$posts_tags[] = $tag->post;
            $posts_tags[$key]['nome'] = $tag->name;
            $posts_tags[$key]['total'] = $tag->post->count();
            $posts_tags[$key]['slug'] = $tag->slug;
        }
        return $posts_tags;
    }

    //LISTANDO ARQUIVOS DO BLOG
    function _blogListarArquivos($cliente_default){

        //PEGANDO TODAS AS DATAS QUE TEM POSTS
        $data_posts = \DB::select("SELECT count(*) as numpost ,(DATE_FORMAT(publicar_em,'%Y-%m')) as datapost FROM posts WHERE cliente_id = ? group by (DATE_FORMAT(publicar_em,'%Y-%m')) desc", array($cliente_default->id));

        $j=0;//indice do meu vetor
        $post_arquivos = null;
        foreach($data_posts as $key => $data_post){
            
            if($key == 0){
                $ano = substr($data_post->datapost, 0, 4);
                $mes = substr($data_post->datapost, 5, 7);
            }else if( substr($data_post->datapost, 0, 4) != $ano){
                $j++;
                
            }
            //separando os arquivos por ano
            $ano = substr($data_post->datapost, 0, 4);
            $mes =strtolower(_mes_paraNome(substr($data_post -> datapost, 5, 7)));
            $numpost = str_pad($data_post->numpost, 2, "0", STR_PAD_LEFT);

            $post_arquivos[$j][] =array('ano' => $ano , 'mes' => $mes, 'numpost' => $numpost);
        }

            return $post_arquivos;

    }
