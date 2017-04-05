<?php 
$header_title = 'Tipos de Ação';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main usuario sucesso">
					
						<br />
						
						<h1>
							<img src="{{ URL::to('/upload/cliente').'/'.$tipos_acao->cliente->slug.'/'.$tipos_acao->cliente->logo }}"></img>
							NOVO TIPO DE AÇÃO
						</h1>
						
						<br>

						<h2 class="novo-usuario-sucesso">SUCESSO!</h2>
						
						
						<p class="col-sm-8 novo-usuario-sucesso-descricao">
							Tipo de Ação  {{$tipos_acao->name}} criado com sucesso!  <br />
						</p>
						
						<div class="row">
							<div class="col-sm-12">
								{!! link_to_action('TiposAcaoController@tiposAcao','IR PARA TIPOS DE AÇÃO',$tipos_acao->cliente->slug, ['class'=>'btn btn-amarelo btn-medio']) !!}
							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

		</div><!-- /.main -->
@endsection