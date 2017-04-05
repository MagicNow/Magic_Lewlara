<?php namespace App\Providers;

use App\Services\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider{

    public function boot()
    {
        // valida Postdestaque
        \Validator::extendImplicit( 'Postdestaque', function($attribute, $value, $parameters)
        {
            // $parameters[0] = definir_destaque_imagem
            // $parameters[1] = definir_destaque_url
                        
            if(!$parameters[0] && !$parameters[1]){
                return false;
            }
            
            return true;
        });



        // PERMITE FAZER A VALIDAÇÃO validateWithCustomAttribute
        \Validator::resolver(function($translator, $data, $rules, $messages, $customAttributes)
        {
            return new Validator($translator, $data, $rules, $messages, $customAttributes);
        });


    }

    public function register()
    {
    }
}