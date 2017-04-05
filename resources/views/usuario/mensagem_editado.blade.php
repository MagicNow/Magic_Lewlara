<?php 
$header_title = 'Usuário Editado';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main usuario sucesso">
					
						<br />
						
						<h1>USUÁRIO EDITADO</h1>
						
						<br>

						<h2 class="novo-usuario-sucesso">SUCESSO!</h2>
						
						
						<p class="col-sm-8 novo-usuario-sucesso-descricao">
							Usuário {{$usuario->first_name}} editado com sucesso!  <br />
						</p>
						
						<div class="row">
							<div class="col-sm-12">
								{!! link_to_route('usuario_list','IR PARA USUÁRIOS POR CLIENTE',array(),['class'=>'btn btn-amarelo btn-medio']) !!}
							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

		</div><!-- /.main -->
@endsection