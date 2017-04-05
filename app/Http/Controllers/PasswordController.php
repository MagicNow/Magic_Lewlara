<?php

namespace App\Http\Controllers;

use Mail;
use Auth;
use Hash;
use App\User;
use App\Cliente;
use App\Tag;
use App\Http\Controllers\Controller;
use Request; // for Request::all()
use Session;
use Input;

class PasswordController extends Controller {

    public function __construct() {
        session()->regenerate();
    }

    public function main($passtoken = null) {

        if ($passtoken) {//caso tenham nos passado um token, buscamos pelo usuário com tal token lá no banco
            $usuario = User::where('reset_password_code', $passtoken)->first();
            if (!$usuario) { //se não há ninguém com este token, redirecionamos o usuário para a tela raíz
                return redirect('/');
            } else { //do contrário podemos mandar o usuário para o form de redefinição com segurança
                return view('password.redefineform', compact('passtoken'));
            }
        } else {//se não estamos recebendo nenhum token apresentamos a tela do início do fluxo
            return view('password.redefine');
        }
    }

    public function email() {

        // efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
        $this->validateWithCustomAttribute(Request::instance(), $this->_rules('access'), $this->_messages(), $this->_customAttributes());
        
        //recolher email do usuário e salvar pra ele um token aleatório
        $useremail = Request::input('useremail');
        $user = User::where('email', $useremail)->first();

        if(!$user){
            return view('password.redefine');
        }

        $passtoken = str_random(100);
        $user->reset_password_code = $passtoken;
        $user->save();

        //enviar email para o usuário com o token criado
        Mail::send(
                'emails.password', ['passtoken' => $passtoken], function($message) {
                    $message->to(Request::input('useremail'), ' ')->subject('O seu link de recuperação de senha!');
                }
        );

        //apresentar tela assegurando o usuário de que o e-mail foi mandado
        return view('password.emailsent', compact('useremail'));
    }

    public function success() {

        // efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
        $this->validateWithCustomAttribute(Request::instance(), $this->_rules('redefine'), $this->_messages(), $this->_customAttributes());
        
        //receber dados de redefinição de senha
        $passtoken = Request::input('passtoken');
        $senha1 = Request::input('passwordRedefine');
        $senha2 = Request::input('passwordRedefine_confirmation');
        
        ////pegamos o usuário pelo token de redefinição
        //setamos a nova senha, e limpados o token dele
        $usuario = User::where('reset_password_code', $passtoken)->first();
        $usuario->password = $senha1;
        $usuario->reset_password_code = null;
        $usuario->save();

        //logamos o usuário antes de manda-lo para a tela de sucesso
        Auth::attempt(array('username' => $usuario->username, 'password' => $senha1));

        return view('password.success');
    }
    
    private function _rules($process = 'redefine'){
        if($process == 'access')
            return array('useremail'=>'required|email|exists:users,email');
        else //redefining
            return array(
                'passwordRedefine'=>'required|confirmed|min:6'
            );
    }
    
    private function _customAttributes(){
        return array(
                'passwordRedefine'=>'Senha',
                'useremail'=>'E-mail'
            );
    }
    
    private function _messages(){
        return array(
                'passwordRedefine.confirmed'    => 'Os campos de senha devem estar iguais.',
                'useremail.exists'    => 'Este email não está cadastrado no sistema.'
            );
    }

}
