<?php 
$header_title = 'Novo Concorrente';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main usuario sucesso">
					
						<br />
						
						<h1>
							<img src="{{ URL::to('/upload/cliente').'/'.$concorrente->cliente->slug.'/'.$concorrente->cliente->logo }}"></img>
							NOVO CONCORRENTE
						</h1>
						
						<br>

						<h2 class="novo-usuario-sucesso">SUCESSO!</h2>
						
						
						<p class="col-sm-8 novo-usuario-sucesso-descricao">
							Concorrente  {{$concorrente->name}} criado com sucesso!  <br />
						</p>
						
						<div class="row">
							<div class="col-sm-12">
								{!! link_to_action('ConcorrenteController@create','CADASTRAR NOVO CONCORRENTE',$concorrente->cliente->slug,['class'=>'btn btn-amarelo btn-medio']) !!}
							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

		</div><!-- /.main -->
@endsection