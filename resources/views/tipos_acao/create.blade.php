<?php 
$header_title = 'Tipos de Ação';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main cliente modelo-cadastro-lista-2-colunas">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif
						TIPOS DE AÇÃO</h1>
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
						
						

						<div class="row">
							<div class="col-sm-6 sobre-linha">
								<span>CADASTRAR NOVO TIPO DE AÇÃO</span>
							</div><!-- /.col-sm-8 -->
							<div class="col-sm-5 col-sm-offset-1 sobre-linha direita-padding">
								<span>TIPOS DE AÇÃO CADASTRADOS</span>
							</div><!-- /.col-sm-8 -->
						</div><!-- /.row -->

						<br><br>
						<div class="row">
							<div class="col-sm-6">							
								{!! Form::open(['action'=>'TiposAcaoController@store', 'class'=>'form-horizontal', 'files' => true]) !!}
										{!! Form::hidden('cliente_id',$cliente_id) !!}
									<div class="form-group">
										{!! Form::label('name','Tipo de Ação', ['class'=>'control-label col-sm-5']) !!}
										<div class="col-sm-7">
											{!! Form::text('name', null, ['class'=>'form-control']) !!}
										</div><!-- /.col-sm-7 -->
									</div><!-- /.form-group -->

									<div class="form-group">
										{!! Form::label('icon','Upload de Ícone', ['class'=>'control-label col-sm-5']) !!}
										<div class="col-sm-7">
											<div id="file_unico_imagem_selecionada" class="form-control"></div>
										</div><!-- /.col-sm-7 -->
														
										<div class="row">
											<small class="col-sm-7 col-sm-offset-5 font-arial-narrow">
												<br />
												TAMANHO DO ÍCONE 25PX X 25PX  |  .PNG ou .JPG
											</small>
										</div><!-- /.row -->
										<br />
										<div class="row">
											<div class="col-sm-7 col-sm-offset-5 sem-padding">
												<span class="btn btn-cinza btn-pequeno btn-file file-unico">
													SELECIONAR ÍCONE   {!! Form::file('icon', ['class'=>'','accept'=>'image/*']) !!}
												</span>
											</div><!-- /.col-sm-7 col-sm-offset-5 -->			
										</div><!-- /.row -->
										
									</div><!-- /.form-group -->
									<br />
									<div class="form-group">
										<div class="col-sm-7 col-sm-offset-5 sem-padding">
											{!! Form::submit('CADASTRAR', ['class'=>'btn btn-amarelo btn-medio align-center']) !!}
										</div><!-- /.col-sm-7 col-sm-offset-5 sem-padding -->
									</div><!-- /.form-group -->

								{!! Form::close() !!}
							</div><!-- /.col-sm-6 -->

							<div class="col-sm-5 col-sm-offset-1">
								<ul class="row list">
									@foreach ($tipos_acao as $tpacao)
										<li>
										<img src="{{ URL::to('/upload/tiposAcao/').'/'.$tpacao->icon }} ">
										{{ $tpacao->name }}
										{!! link_to_action('TiposAcaoController@destroy','',array($tpacao->id),['class'=>'item-delete']) !!}
										</li>
									@endforeach								
								</ul><!-- /.row -->
								<div class="row">
									{!! str_replace('/?', '?', $tipos_acao->render()) !!}
								</div><!-- /.row -->
							</div><!-- /.col-sm-5 .col-sm-offset-1 -->
						</div><!-- /.row -->




		</div><!-- /.main -->
@endsection