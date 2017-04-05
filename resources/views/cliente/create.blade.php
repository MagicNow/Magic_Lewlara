<?php 
$header_title = 'Novo Cliente';
?>

<?php
$scripts = array(
    0 => array(
        'src' => URL::to('/').elixir('js/pages/cliente.js')
    ),
); 
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main cliente">
					
						<br />
						

						<h1>NOVO CLIENTE</h1>
						<br />

						@if (count($errors) > 0)
							<div class="alert alert-danger">
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif


						{!! Form::open(['action'=>'ClienteController@store', 'class'=>'form-horizontal', 'files' => true]) !!}
							
							<div class="form-group">
								{!! Form::label('name','Marca', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::text('name', null, ['class'=>'form-control slug-source']) !!}
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->

							<div class="form-group group-link-cliente">
								{!! Form::label('link[0]','Link', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::text('link[0]', null, ['class'=>'form-control']) !!}
								</div><!-- /.col-md-9 -->
								<div class="col-md-3">
									<a id="cliente_novo_adicionar_link_cliente" class="btn btn-cinza btn-pequeno btn-lateral-form">ADICIONAR NOVO LINK</a>
								</div><!-- /.col-md-3 -->
								<script type="text/javascript"> var link_cliente_count=0; </script>
							</div><!-- /.form-group -->
							
							<div class="form-group">
								{!! Form::label('slug','Personalizar Url', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::text('slug', null, ['class'=>'form-control slug-source slug-target']) !!}
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->

							<div class="form-group">
									{!! Form::label('logo','UPLOAD DE LOGO', ['class'=>'control-label col-sm-3']) !!}
									<div class="col-sm-6">
										<div id="cliente_novo_imagem_selecionada" class="form-control"></div>
									</div><!-- /.col-sm-6 -->
									<div class="col-sm-3">
										<span class="btn btn-cinza btn-pequeno btn-file btn-lateral-form" id="cliente_novo_selecionar_imagem">
											SELECIONAR IMAGEM   {!! Form::file('logo', ['class'=>'','accept'=>'image/*']) !!}
										</span>
									</div><!-- /.col-sm-3 -->						
								<div class="row">
									<small class="col-sm-6 col-sm-offset-3">
										<br />
										TAMANHO DA IMAGEM 100PX X 85PX  |  .PNG ou .JPG
									</small>
								</div><!-- /.row -->
							</div><!-- /.form-group -->
	
							<div class="form-group">
								<div class="row">
									{!! Form::label('active','CLIENTE VISÍVEL', ['class'=>'control-label col-sm-3']) !!}
									<div class="col-sm-2">
										{!! Form::select('active', array('1'=>'Sim','0'=>'Não'), null, ['class'=>'form-control arrow-preto-amarelo']) !!}
									</div><!-- /.col-md-9 -->
								</div><!-- /.row -->
								<div class="row">
									<small class="col-sm-6 col-sm-offset-3">
										<br />
										AO DEIXAR O CLIENTE VISÍVEL, TODOS USUÁRIOS
										CADASTRADOS NESSE CLIENTE PODERÃO ACESSAR
										O CONTEÚDO. VOCÊ PODE ALTERAR A VISIBILIDADE APÓS
										O CADASTRO NO ITEM DO MENU "TODOS OS CLIENTES"
									</small>
								</div><!-- /.row -->
							</div><!-- /.form-group -->
							
							<div class="form-group">
								<div class="col-sm-6 col-sm-offset-3">
									{!! Form::submit('CADASTRAR CLIENTE', ['class'=>'btn btn-amarelo btn-medio align-center']) !!}
								</div><!-- /.col-sm-6 col-sm-offset-3 -->
							</div><!-- /.form-group -->

							
							
						{!! Form::close() !!}
		</div><!-- /.main -->
@endsection