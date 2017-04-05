<?php namespace App\Http\Composers;

use App\Notification;
use Auth;

class TopoNavComposer {

	public function compose ($view){

		//$clientes_select = Auth::user()->cliente->lists('name','id');
		$select_escolha_o_cliente = _selectEscolhaOCliente();


		if(!isset($cliente_default)){
			$cliente_default = _clienteDefault(null);
		}
		// busca to user logado
		$na = Notification::where('to_user',Auth::user()->id)->where('to_cliente',$cliente_default->id)->where('read',0);

		/*if(Auth::user()->is_admin() or Auth::user()->is_usuario()){
			// busca to cliente do user logado
			$na = $na->orWhereIn('to_cliente',$clientes)->where('read',0);
		}*/
	
		$na = $na->count();		

		$view->with('notifications_count',$na)->with('select_escolha_o_cliente',$select_escolha_o_cliente);
	}
}