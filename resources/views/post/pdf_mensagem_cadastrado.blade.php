<?php 
$header_title = 'GERAR PDF';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main usuario sucesso">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif
							GERAR PDF
						</h1>
						
						<br>

						<h2 class="novo-usuario-sucesso">SUCESSO!</h2>
						
						
						<p class="col-sm-8 novo-usuario-sucesso-descricao">
							PDF criado com sucesso!  <br />
						</p>
						
						<div class="row">
							<div class="col-sm-12">
								{!! link_to_action('PostController@todos','FAZER DOWNLOAD',array(),['class'=>'btn btn-amarelo btn-grande']) !!}
								<br><br>
								{!! link_to_action('PostController@pdfPosts','GERAR NOVO PDF',array(),['class'=>'btn btn-preto btn-medio']) !!}
							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

		</div><!-- /.main -->
@endsection