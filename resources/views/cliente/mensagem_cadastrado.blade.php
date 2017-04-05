<?php 
$header_title = 'Novo Cliente';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main cliente sucesso">
					
						<br />
						
						<h1>NOVO CLIENTE</h1>
						
						<br>

						<h2 class="novo-cliente-sucesso">SUCESSO!</h2>
						
						
						<p class="col-sm-8 novo-cliente-sucesso-descricao">
							Cliente {{$cliente->name}} criado com sucesso!  <br />
						</p>
						
						<div class="row">
							<div class="col-sm-12">
								{!! link_to_action('ClienteController@create','CADASTRAR NOVO CLIENTE',array(),['class'=>'btn btn-amarelo btn-medio']) !!}
							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

		</div><!-- /.main -->
@endsection