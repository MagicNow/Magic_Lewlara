<?php 
$header_title = 'Atualizar Logo Do Sistema';
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
						

						<h1>ATUALIZAR LOGO DO SISTEMA</h1>
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


						{!! Form::open(['action'=>'ConfiguracoesController@logoUpdate', 'class'=>'form-horizontal', 'files' => true]) !!}
							
                                                        <div class="form-group">
									{!! Form::label('logo','UPLOAD DE LOGO', ['class'=>'control-label col-sm-3']) !!}
									<div class="col-sm-6">
										<div id="cliente_novo_imagem_selecionada" class="form-control"></div>
									</div><!-- /.col-sm-6 -->
									<div class="col-sm-3">
										<span class="btn btn-cinza btn-pequeno btn-file btn-lateral-form">
											SELECIONAR IMAGEM   {!! Form::file('logo', ['class'=>'','accept'=>'image/*']) !!}
										</span>
									</div><!-- /.col-sm-3 -->						
								<div class="row">
									<small class="col-sm-6 col-sm-offset-3">
										<br />
										TAMANHO DA IMAGEM 220PX X 55PX  |  .PNG ou .JPG
									</small>
								</div><!-- /.row -->
							</div><!-- /.form-group -->
							
							<div class="form-group">
								<div class="col-sm-6 col-sm-offset-3">
									{!! Form::submit('ATUALIZAR LOGO', ['class'=>'btn btn-amarelo btn-medio align-center']) !!}
								</div><!-- /.col-sm-6 col-sm-offset-3 -->
							</div><!-- /.form-group -->
                                                
                                                {!! Form::close() !!}
		</div><!-- /.main -->
@endsection