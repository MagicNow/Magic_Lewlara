<?php namespace App\Services;

class Validator extends \Illuminate\Validation\Validator {
   
    // ATENÇÃO ATENÇÃO ATENÇÃO ATENÇÃO ATENÇÃO
    // ATENÇÃO ATENÇÃO ATENÇÃO ATENÇÃO ATENÇÃO
    // PODEM HAVER MAIS VALIDAÇÕES sistema\app\Providers\ValidatorServiceProvider.php

    public function validateDataviaparametros($attribute, $value, $parameters)
    {
    	// $parameters[0] = dia
    	// $parameters[1] = mes
    	// $parameters[2] = ano
    	// $parameters[3] = hora
    	// $parameters[4] = minuto

    	if(!checkdate($parameters[1],$parameters[0],$parameters[2])){
    		return false;
    	}

		$data = strtotime($parameters[2].'-'.($parameters[1]).'-'.$parameters[0].' '.$parameters[3].':'.$parameters[4]);	

		if(!$data){
			return false;
		} 
    	
    	return true;
    }


}



