<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Cliente;
use App\Config;
use App\Http\Controllers\Controller;
use Request; // for Request::all()
use Session;
use Input;

class ConfiguracoesController extends Controller {

    public function __construct() {
        
    }

    public function postsComentariosEdit() {
        $loggedUser = Auth::user(); //pegamos o usuário logado para fins de verificação e segurança
        
        if($loggedUser) $permission = $loggedUser->group->contains(1);
        
        if (!$loggedUser || !$permission) return redirect('/'); //Se não há um objeto de usuário logado ou o usuário logado não é admin, retornamos o vivente para a tela inicial

//        var select sendo um array com os diversos selects existentes para config
//        e as diversas opções para cada selec, de modo que 2 selects diferentes com 2 opções cada
//        ficariam com a seguinte estrutura:
//        
//        $select = array(
//            'propriedadeDeConfiguração1' =>
//                array(
//                    'valor_opt1'=>'nome amigável para opção1',
//                    'valor_opt2'=>'nome amigável para opção2'
//                    ),
//            'propriedadeDeConfiguração2' =>
//                array(
//                    'valor_opt1'=>'nome amigável para opção1',
//                    'valor_opt1'=>'nome amigável para opção2'
//                    )
//            )
//
//          'propriedadeDeConfiguração1' corresponde ao nome dessa propriedade no banco
        $selects = array();
        $select_values = array();
        
        $groups = array(0=>'TODOS',1=>'ADMINISTRADORES',2=>'LEWLARA',3=>'CLIENTES');//possíveis níveis de permissão do sistema
        
        $selects['posts_por_pagina'] = array_combine(range(1,30),range(1,30));
        $selects['posts_visibilidade'] = $groups;
        $selects['comentarios_escrita'] = $groups;
        $selects['comentarios_leitura'] = $groups;
        $selects['comentarios_ordem'] = array('mais_antigo'=>'MAIS ANTIGO PARA MAIS RECENTE','mais_recente'=>'MAIS RECENTE PARA MAIS ANTIGO');
        $selects['comentarios_avatar'] = array(0=>'NÃO',1=>'SIM');
       
        //aqui pegamos o valor de cada propriedade da page de configurações da qual sabemos da existência no banco
        //e do vetor que as guarda (possibleConfigs), setamos um segundo vetor (select_values) com todos valores, onde key = nome da opção no banco
        $possibleConfigs = array('posts_por_pagina','posts_visibilidade','comentarios_escrita','comentarios_leitura','comentarios_ordem','comentarios_avatar');
        
        foreach($possibleConfigs as $config){
            $select_values[$config] = Config::where('opcao','=',$config)->First()->valor;
        }

        return view('config.posts', compact('selects','select_values'));
    }
    
    public function postsComentariosUpdate() {
        $loggedUser = Auth::user(); //pegamos o usuário logado para fins de verificação e segurança
        
        if ($loggedUser) $permission = $loggedUser->group->contains(1);
        
        if (!$loggedUser || !$permission) return redirect('/'); //Se não há um objeto de usuário logado ou o usuário logado não é admin, retornamos o vivente para a tela inicial
        
        //aqui pegamos o valor de cada propriedade da page de configurações da qual sabemos da existência no banco
        //e do vetor que as guarda (possibleConfigs) corremos por um loop salvando todos os novos valores, contando com a sincronia de que
        //os selects criados tem como key, o nome da propriedade no banco
        $possibleConfigs = array('posts_por_pagina','posts_visibilidade','comentarios_escrita','comentarios_leitura','comentarios_ordem','comentarios_avatar');
        
        foreach($possibleConfigs as $config){
            $select_values[$config] = Config::where('opcao','=',$config)->First();
            $select_values[$config]->valor = Request::input($config);
            $select_values[$config]->save();
        }
        
        return view('config.posts-success');
    }
    
    public function logoEdit() {
        $loggedUser = Auth::user(); //pegamos o usuário logado para fins de verificação e segurança
        
        if($loggedUser) $permission = $loggedUser->group->contains(1);
        
        if (!$loggedUser || !$permission) return redirect('/'); //Se não há um objeto de usuário logado ou o usuário logado não é admin, retornamos o vivente para a tela inicial

        return view('config.logo');
    }

    public function logoUpdate() {
        $loggedUser = Auth::user(); //pegamos o usuário logado para fins de verificação e segurança
        
        if($loggedUser) $permission = $loggedUser->group->contains(1);
        
        if (!$loggedUser || !$permission) return redirect('/'); //Se não há um objeto de usuário logado ou o usuário logado não é admin, retornamos o vivente para a tela inicial
        //validamos o envio da imagem com os parâmetros sendo o retorno das funções privadas no final desta classe
        $this->validateWithCustomAttribute(Request::instance(), $this->_rules(), $this->_messages(), $this->_customAttributes());

        if (Input::file('logo')) {
            $file = Input::file('logo');

            $destinationPath = 'upload/sistema/';

            $filename = 'logo-sistema' . '.' . $file->guessExtension(); //o nome desta imagem sempre será o mesmo, somente com a extensão mudando
            Input::file('logo')->move($destinationPath, $filename);

            $img = Config::where('opcao','=','logo-sistema')->First();
            $img->valor = $filename;
            $img->save();
            
            return view('config.logo-sucesso');
        }
    }

    private function _rules() {
        return array('logo' => 'required|image|image_size:220,55');
    }

    private function _customAttributes() {
        return array('logo' => 'Imagem');
    }

    private function _messages() {
        return array();
    }

}
