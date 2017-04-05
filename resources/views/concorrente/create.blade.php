<?php 
$header_title = 'Novo Concorrente';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main cliente">					
			<br />
			<h1>
				@if (isset($cliente_default))
				<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
				@endif
				NOVO CONCORRENTE
			</h1>
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

			
			<br />
			<div class="row">
				<div class="col-sm-11">
					{!! Form::open(['action'=>'ConcorrenteController@store', 'class'=>'form-horizontal', 'files' => true]) !!}
						{!! Form::hidden('cliente_id',$cliente_default->id) !!}
						<div class="form-group">
							{!! Form::label('name','Nome do Concorrente', ['class'=>'control-label col-sm-3']) !!}
							<div class="col-sm-6">
								{!! Form::text('name', null, ['class'=>'form-control']) !!}
							</div><!-- /.col-md-9 -->
						</div><!-- /.form-group -->
						
						<div class="form-group group-link">
							{!! Form::label('link[0]','Link do Concorrente', ['class'=>'control-label col-sm-3']) !!}
							<div class="col-sm-6">
								{!! Form::text('link[0]', null, ['class'=>'form-control']) !!}
							</div><!-- /.col-md-9 -->
							<div class="col-md-3">
								<a id="adicionar_novo_campo" data-label='LINK DO CONCORRENTE' data-campo='link' class="btn btn-cinza btn-pequeno btn-lateral-form">ADICIONAR NOVO LINK</a>
							</div><!-- /.col-md-3 -->
							<script type="text/javascript"> var campo_count=0; </script>
						</div><!-- /.form-group -->

						<div class="form-group">
								{!! Form::label('logo','UPLOAD DE LOGO', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									<div id="file_unico_imagem_selecionada" class="form-control"></div>
								</div><!-- /.col-sm-6 -->
								<div class="col-sm-3">
									<span class="btn btn-cinza btn-pequeno file-unico btn-file btn-lateral-form" id="usuario_novo_selecionar_imagem">
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
							<div class="col-sm-6 col-sm-offset-3">
								{!! Form::submit('CADASTRAR CONCORRENTE', ['class'=>'btn btn-amarelo btn-medio align-center']) !!}
							</div><!-- /.col-sm-6 col-sm-offset-3 -->
						</div><!-- /.form-group -->
						
					{!! Form::close() !!}
				</div>
			</div><!-- /.row -->
							
		</div><!-- /.main -->
@endsection