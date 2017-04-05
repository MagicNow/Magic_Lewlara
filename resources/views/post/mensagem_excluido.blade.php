<?php 
$header_title = 'Post Excluído';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main usuario sucesso">
					
						<br />
						
						<h1>
							<img src="{{ URL::to('/upload/cliente').'/'.$post->cliente->slug.'/'.$post->cliente->logo }}"></img>
							POST EXCLUÍDO
						</h1>
						
						<br>

						<h2 class="novo-usuario-sucesso">SUCESSO!</h2>
						
						
						<p class="col-sm-8 novo-usuario-sucesso-descricao">
							Post {{$post->titulo}} excluído com sucesso!  <br />
						</p>
						
						<div class="row">
							<div class="col-sm-12">
								{!! link_to_action('PostController@todos','IR PARA TODOS OS POSTS',array(),['class'=>'btn btn-amarelo btn-medio']) !!}
							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

		</div><!-- /.main -->
@endsection