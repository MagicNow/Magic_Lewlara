<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Cliente;
use App\Group;
use App\Http\Controllers\Controller;
use Request; // for Request::all()
use Hash;
use URL;
use Validator;
use Input;

class MeuPerfilController extends Controller {

    public function __construct() {
        
    }

    public function edit() {
        $loggedUser = Auth::user(); //pegamos o usuário logado para fins de verificação e segurança

        if (!$loggedUser)
            return redirect('/'); //Se não há um objeto de usuário logado ou o usuário logado não é admin, retornamos o vivente para a tela inicial

        $usuario = $loggedUser;
        $usuario->link_pessoal = explode('|', $usuario->link_pessoal);//a lista de links pessoais é uma longa string com links separados por pipe (|), aqui a passamos para o formato de array
        $groups_select = Group::lists('name', 'id'); //Aqui passamos a coleção de grupos possíveis para um array, a fim de montar selects

        return view('meu_perfil.edit', compact('usuario', 'groups_select'));
    }

    public function ajaxMeuPerfilGet($object) {//este object nada mais é que um parâmetro na chamada do ajax
        //API para que o front possa atualizar a view de perfil de maneira pontual
        //executamos funções privadas desta controller para recuperar a informação correta
        $loggedUser = Auth::user();

        switch ($object) {
            case 'username': $response = $this->_retrieve_username($loggedUser);
                break;
            case 'email': $response = $this->_retrieve_email($loggedUser);
                break;
            case 'personal-links': $response = $this->_retrieve_personal_links($loggedUser);
                break;
            case 'names': $response = $this->_retrieve_names($loggedUser);
                break;
            case 'image': $response = $this->_retrieve_image($loggedUser);
                break;
            default : return array('code' => 400, 'msgs' => ['Bad Request - O valor objeto solicitado não foi encontrado']);
        }

        return $response;
    }

    public function ajaxMeuPerfilUpdate($object) {//este object nada mais é que um parâmetro na chamada do ajax
        //API para que o front possa atualizar informações do banco de maneira pontual
        //executamos funções privadas desta controller para validar e atualizar a informação correta
        //passamos para todas as funções o usuário logado, como um objeto. Mandamos também todos os dados do formulário, 
        //exceto para a função encarregada de salvar as imagens, que recebe somente o arquivo de imagem
        $formData = json_decode(file_get_contents("php://input"), true);
        $loggedUser = Auth::user();

        switch ($object) {
            case 'save-username': $response = $this->_save_username($loggedUser, $formData);
                break;
            case 'save-access-level': $response = $this->_save_access_level($loggedUser, $formData);
                break;
            case 'save-email': $response = $this->_save_email($loggedUser, $formData);
                break;
            case 'save-personal-links': $response = $this->_save_personal_links($loggedUser, $formData);
                break;
            case 'save-names': $response = $this->_save_names($loggedUser, $formData);
                break;
            case 'save-password': $response = $this->_save_password($loggedUser, $formData);
                break;
            case 'save-image': $response = $this->_save_image($loggedUser, Input::file('photo'));
                break;
            default : return array('code' => 500, 'msgs' => ['Erro interno de servidor']);
        }
        return $response;
    }

    private function _save_username($loggedUser, $formData) {
        $data = ['username' => $formData['username']];
        $rules = ['username' => 'required|min:3|unique:users,username,' . $loggedUser->id];
        $messages = array();
        $customAttributes = array();
        //as 4 variaveis acima servem para o uso da api de validação do laravel
            
        //a variavel de resposta (abaixo) sempre conterá um código e uma lista de mensagens, do seguinte modo: array('code'=>'int code', 'msgs'=>array())
        //os códigos de erro seguem o padrão de http requests
        $response = array();

        $validator = Validator::make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $response['code'] = 422;
            $response['msgs'] = $validator->messages()->all();
            return $response;
        } else {
            $response['code'] = 200;
            $response['msgs'] = array('Nome de usuário alterado com sucesso');
        }

        $loggedUser->username = $formData['username'];
        $loggedUser->save();

        return $response;
    }

    private function _save_access_level($loggedUser, $formData) {
        $data = ['username' => $formData['username']];
        $rules = ['username' => 'required|min:3|unique:users,username,' . $loggedUser->id];
        $messages = array();
        $customAttributes = array();
        //as 4 variaveis acima servem para o uso da api de validação do laravel
            
        //a variavel de resposta (abaixo) sempre conterá um código e uma lista de mensagens, do seguinte modo: array('code'=>'int code', 'msgs'=>array())
        //os códigos de erro seguem o padrão de http requests
        $response = array();

        $validator = Validator::make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $response['code'] = 422;
            $response['msgs'] = $validator->messages()->all();
            return $response;
        } else {
            $response['code'] = 200;
            $response['msgs'] = array('Nome de usuário alterado com sucesso');
        }

        $loggedUser->cliente()->attach(Request::input('cliente'));
        $loggedUser->save();

        return $response;
    }

    private function _save_email($loggedUser, $formData) {
        $data = ['email' => $formData['email']];
        $rules = ['email' => 'required|email'];
        $messages = array();
        $customAttributes = array();
        //as 4 variaveis acima servem para o uso da api de validação do laravel
            
        //a variavel de resposta (abaixo) sempre conterá um código e uma lista de mensagens, do seguinte modo: array('code'=>'int code', 'msgs'=>array())
        //os códigos de erro seguem o padrão de http requests
        $response = array();

        $validator = Validator::make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $response['code'] = 422;
            $response['msgs'] = $validator->messages()->all();
            return $response;
        } else {
            $response['code'] = 200;
            $response['msgs'] = array('E-mail alterado com sucesso');
        }

        $loggedUser->email = $formData['email'];
        $loggedUser->save();

        return $response;
    }

    private function _save_personal_links($loggedUser, $formData) {
        //os links pessoais são uma longa string com links separados por pipe (|)
        //aqui simplesmente limpamos o array que recebemos de um input serializado 
        //e o passamos para o formato mencionado
        $link_pessoal = array_filter(array_map('trim', $formData['link_pessoal']));
        $loggedUser->link_pessoal = implode("|", $formData['link_pessoal']);
        $loggedUser->save();

        $response = array();
        $response['code'] = 200;
        $response['msgs'] = array('Links pessoais alterados com sucesso');

        return $response;
    }

    private function _save_names($loggedUser, $formData) {
        $data = array('first_name' => $formData['first_name'], 'last_name' => $formData['last_name']);
        $rules = array('first_name' => 'required|min:3', 'last_name' => 'required|min:3');
        $messages = array();
        $customAttributes = array();
        //as 4 variaveis acima servem para o uso da api de validação do laravel
            
        //a variavel de resposta (abaixo) sempre conterá um código e uma lista de mensagens, do seguinte modo: array('code'=>'int code', 'msgs'=>array())
        //os códigos de erro seguem o padrão de http requests
        $response = array();

        $validator = Validator::make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $response['code'] = 422;
            $response['msgs'] = $validator->messages()->all();
            return $response;
        } else {
            $response['code'] = 200;
            $response['msgs'] = array('Nome alterado com sucesso');
        }

        $loggedUser->first_name = $formData['first_name'];
        $loggedUser->last_name = $formData['last_name'];
        $loggedUser->save();

        return $response;
    }

    private function _save_password($loggedUser, $formData) {
        $data = ['password' => $formData['password'], 'current_password' => $formData['current_password'], 'password_confirmation' => $formData['password_confirmation']];
        $rules = ['password' => 'required|confirmed|min:6', 'current_password' => 'required|passcheck'];
        $messages = ['password.confirmed' => 'Confirmação de senha inválida', 'current_password.passcheck' => 'Senha atual não confere'];
        $customAttributes = ['current_password' => 'Senha Atual'];
        //as 4 variaveis acima servem para o uso da api de validação do laravel
            
        //a variavel de resposta (abaixo) sempre conterá um código e uma lista de mensagens, do seguinte modo: array('code'=>'int code', 'msgs'=>array())
        //os códigos de erro seguem o padrão de http requests
        $response = array();

        //abaixo extendemos o validador para montarmos uma nova regra de validação
        //neste caso, o que queremos é verificar que o usuário entrou a sua senha atual correta
        //sem alterar o modo que usamos para validar informações normalmente
        Validator::extend('passcheck', function ($attribute, $value, $parameters) {
            return Hash::check($value, Auth::user()->getAuthPassword());
        });

        $validator = Validator::make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $response['code'] = 422;
            $response['msgs'] = $validator->messages()->all();
            return $response;
        } else {
            $response['code'] = 200;
            $response['msgs'] = array('Senha atualizada com sucesso');
        }

        $loggedUser->password = $formData['password'];
        $loggedUser->save();

        return $response;
    }

    private function _save_image($loggedUser, $image) {
        $data = ['photo' => $image];
        $rules = ['photo' => 'image|image_aspect:1'];
        $messages = ['photo.image_aspect' => 'A imagem deve ter a largura e altura iguais.'];
        $customAttributes = ['photo' => 'Upload de Imagem'];
        //as 4 variaveis acima servem para o uso da api de validação do laravel
            
        //a variavel de resposta (abaixo) sempre conterá um código e uma lista de mensagens, do seguinte modo: array('code'=>'int code', 'msgs'=>array())
        //os códigos de erro seguem o padrão de http requests
        $response = array();

        $validator = Validator::make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $response['code'] = 422;
            $response['msgs'] = $validator->messages()->all();
            return $response;
        } else {
            $response['code'] = 200;
            $response['msgs'] = array('Imagem de perfil alterada com sucesso');
        }
        
        $destinationPath = 'upload/usuario/' . $loggedUser->id . '/';
        $filename = date('Y-m-d_H-i-s') . '_' . md5(uniqid("")) . '.' . $image->guessExtension();
        Input::file('photo')->move($destinationPath, $filename);
        $loggedUser->photo = $filename;
        $loggedUser->save();

        return $response;
    }

    //abaixo, funções de entrega de dados, simples e separadas somente a fim de não termos na
    //função ajaxMeuPerfilGet, um aglomerado muito grande de código e mais de uma responsabilidade
    private function _retrieve_image($loggedUser) {
        return URL::to('/upload/usuario') . '/' . $loggedUser->id . '/' . $loggedUser->photo;
    }

    private function _retrieve_names($loggedUser) {
        return array('firstname' => $loggedUser->first_name, 'lastname' => $loggedUser->last_name);
    }

    private function _retrieve_username($loggedUser) {
        return $loggedUser->username;
    }

    private function _retrieve_email($loggedUser) {
        return $loggedUser->email;
    }

    private function _retrieve_personal_links($loggedUser) {
        return explode('|', $loggedUser->link_pessoal);
    }

}
