<?php 
$header_title = 'Categoria / Subcategoria Editada';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main cliente sucesso">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif
						CATEGORIA / SUBCATEGORIA EDITADA
						</h1>
						
						<br>

						<h2 class="novo-cliente-sucesso">SUCESSO!</h2>
						
						
						<p class="col-sm-8 novo-cliente-sucesso-descricao">
							Categoria / Subcategoria  {{$categoria->name}} editada com sucesso!  <br />
						</p>
						
						<div class="row">
							<div class="col-sm-12">
								{!! link_to_action('CategoriaController@lista','IR PARA CATEGORIAS / SUBCATEGORIAS',array(),['class'=>'btn btn-amarelo btn-medio']) !!}
							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

		</div><!-- /.main -->
@endsection