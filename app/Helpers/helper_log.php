<?php

 	function registraLogAcao($msg=null,$id_ativo=null)
 	{	
 		if($msg == null){
 			return false;
 		}
 		$inserir = array(
 			'descricao' => $msg
 		);
 		if($id_ativo>0){
 			$inserir['id_ativo'] = $id_ativo;
 		}
 		
 		DB::table('log_acoes')->insert(
 		    $inserir
 		);
 	}
 	
 