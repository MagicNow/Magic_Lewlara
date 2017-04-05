<?php 
$header_title = 'Categorias / Subcategorias';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main cliente modelo-cadastro-lista-2-colunas">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif
						CATEGORIAS / SUBCATEGORIAS</h1>
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
							<!-- ESQUERDA -->
							<div class="col-sm-6">	
							
								<div class="row">
									<div class="col-sm-12 sobre-linha">
										<span>EDITAR SUBCATEGORIA</span>
									</div><!-- /.col-sm-8 -->
								</div><!-- /.row -->

								<br><br>
								<div class="row">						
									{!! Form::model($subcategoria,['method' => 'PUT','action'=>['CategoriaController@subcategoriaUpdate',$subcategoria->id], 'class'=>'form-horizontal', 'files' => true]) !!}
										
										
										{!! Form::hidden('slug','url') !!}
										
										<div class="form-group">
											{!! Form::label('name','Nome da Subcategoria', ['class'=>'control-label col-sm-5']) !!}
											<div class="col-sm-7">
												{!! Form::text('name', null, ['class'=>'form-control']) !!}
											</div><!-- /.col-sm-7 -->
										</div><!-- /.form-group -->

										<div class="form-group">
											{!! Form::label('categoria_id','Qual Categoria Pertence', ['class'=>'control-label col-sm-5']) !!}
											<div class="col-sm-7">
												{!! Form::select('categoria_id', array('' => 'Escolha a categoria') + $select_categorias, null, ['class'=>'form-control arrow-preto-amarelo sem-padding']) !!}
											</div><!-- /.col-sm-7 -->
										</div><!-- /.form-group -->
										<div class="form-group">
											{!! Form::label('order','Ordem', ['class'=>'control-label col-sm-5']) !!}
											<div class="col-sm-7">
												{!! Form::text('order', null, ['class'=>'form-control slug-source']) !!}
											</div><!-- /.col-sm-7 -->
										</div><!-- /.form-group -->
										<br />
										<div class="form-group">
											<div class="col-sm-7 col-sm-offset-5 sem-padding">
												{!! Form::submit('ATUALIZAR SUBCATEGORIA', ['class'=>'btn btn-amarelo btn-medio align-center']) !!}
											</div><!-- /.col-sm-7 col-sm-offset-5 sem-padding -->
										</div><!-- /.form-group -->

									{!! Form::close() !!}
								</div><!-- /.row -->
							</div><!-- /.col-sm-6 -->
							<!-- FIM ESQUERDA -->
						</div><!-- /.row -->




		</div><!-- /.main -->
@endsection