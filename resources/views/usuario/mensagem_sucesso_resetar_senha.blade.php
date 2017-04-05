<?php 
$header_title = 'Resetar Senha';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main usuario sucesso">
					
						<br />
						
						<h1>RESETAR SENHA</h1>
						
						<br>

						<h2 class="novo-usuario-sucesso">SUCESSO!</h2>
						
						
						<p class="col-sm-8 novo-usuario-sucesso-descricao">
							Nova senha <!-- loren ipsum -->  <br />
						</p>
						
						<div class="row">
							<div class="col-sm-12">
								{!! link_to_action('UserController@resetarSenhaUsuarios','VOLTAR',array(),['class'=>'btn btn-amarelo btn-medio']) !!}
							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

		</div><!-- /.main -->
@endsection