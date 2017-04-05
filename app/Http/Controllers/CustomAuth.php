<?php namespace App\Http\Controllers;

use Auth;
use Request;
use Mail;


class CustomAuth extends Controller {

    public function getLogin() {
        return view('custom_auth.login'); //or just use the default login page
    }

    public function postLogin() {

        $email_username = Request::input('email');
        $password = Request::input('password');

        $campo = filter_var($email_username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            
        if (Auth::attempt([$campo => $email_username, 'password' => $password, 'active' => 1]))
        {
            registraLogAcao('Acessou a extranet',$id_ativo = Auth::user()->id);
            return redirect()->route('blog');
        }
        else {
            //return "fail";

            return redirect()->route('login')
                     #Flash input
                     ->withInput(Request::except('password'))
                     ->withErrors([
                    'email' => 'Credenciais inválidas.',
                ]);
            
        }
    }

    public function getLogout() {
        \Session::flush();
        Auth::logout();
        return redirect()->route('home');

    }



    public function solicitarCadastro(){
        return view('custom_auth.solicitar_cadastro');    
    }
    public function solicitarCadastroSend(){

        $email_destinatario = "monalisa.paduin@lewlaratbwa.com.br";


        $rules = array(
                'nome'=>'required|min:3',
                'email'=>'required|email',
                'cliente'=>'required|min:3',
                'areacargo'=>'required|min:3'
            );
        $customAttributes = array(
                'nome'=>'Nome',
                'cliente'=>'Qual o Cliente',
                'areacargo'=>'Área e cargo'
            );

        // efetua validação, se false volta automaticamente pra página do formulário mostrando os erros
        $this->validateWithCustomAttribute(Request::instance(),  $rules, $messages=array(), $customAttributes);


        // salva os dados do usuário para disparo do e-mail
        $dados_email = array(   
            'email_destinatario'=>$email_destinatario,
            'assunto'=>'Cadastro Solicitado - '.Request::input('nome'),
            'nome' => Request::input('nome'),
            'email' => Request::input('email'),     
            'cliente' => Request::input('cliente'),
            'areacargo' => Request::input('areacargo')
        );

        // DISPARA E-MAIL            parametro use para passar variáveis pra dentro da função
        Mail::send('emails.solicitar_cadastro', $dados_email, function($message) use ($dados_email)
        {               
            $message->to($dados_email['email_destinatario'])->subject($dados_email['assunto'])
                ->cc('stephanie.peart@lewlaratbwa.com.br')
                ->cc('fernando@pravy.com.br');
        });

        if(count(Mail::failures()) > 0){
            $errors = 'Falha ao enviar e-mail para '.$dados_email['email_destinatario'];
        }
        

        return view('custom_auth.mensagem_cadastrado');    
    }
    
}