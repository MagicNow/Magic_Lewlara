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
										<span>CADASTRAR NOVA CATEGORIA / SUBCATEGORIA</span>
									</div><!-- /.col-sm-8 -->
								</div><!-- /.row -->

								<br><br>
								<div class="row">						
								{!! Form::open(['action'=>'CategoriaController@categoriaStore', 'class'=>'form-horizontal', 'files' => true]) !!}
									
									{!! Form::hidden('cliente_id',$cliente_default->id) !!}
									
									<div class="form-group">
										{!! Form::label('name','Nome da Categoria', ['class'=>'control-label col-sm-5']) !!}
										<div class="col-sm-7">
											{!! Form::text('name', null, ['class'=>'form-control slug-source']) !!}
										</div><!-- /.col-sm-7 -->
									</div><!-- /.form-group -->

									<div class="form-group">
										{!! Form::label('slug','URL Da Categoria', ['class'=>'control-label col-sm-5']) !!}
										<div class="col-sm-7">
											{!! Form::text('slug', null, ['class'=>'form-control slug-target']) !!}
										</div><!-- /.col-sm-7 -->
									</div><!-- /.form-group -->
									
									<br />
									<div class="form-group">
										<div class="col-sm-7 col-sm-offset-5 sem-padding">
											{!! Form::submit('CADASTRAR CATEGORIA', ['class'=>'btn btn-amarelo btn-medio align-center']) !!}
										</div><!-- /.col-sm-7 col-sm-offset-5 sem-padding -->
									</div><!-- /.form-group -->

								{!! Form::close() !!}
								</div><!-- /.row -->

								<br >

								<div class="row">						
									{!! Form::open(['action'=>'CategoriaController@subcategoriaStore', 'class'=>'form-horizontal', 'files' => true]) !!}
										
										{!! Form::hidden('cliente_id',$cliente_default->id) !!}
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
										
										<br />
										<div class="form-group">
											<div class="col-sm-7 col-sm-offset-5 sem-padding">
												{!! Form::submit('CADASTRAR SUBCATEGORIA', ['class'=>'btn btn-amarelo btn-medio align-center']) !!}
											</div><!-- /.col-sm-7 col-sm-offset-5 sem-padding -->
										</div><!-- /.form-group -->

									{!! Form::close() !!}
								</div><!-- /.row -->
							</div><!-- /.col-sm-6 -->
							<!-- FIM ESQUERDA -->
							<!-- DIREITA -->
							<div class="col-sm-6 direita-padding">
								<div class="col-sm-11 col-sm-offset-1">								
									<div class="sobre-linha">
										<span>CATEGORIAS / SUBCATEGORIAS CADASTRADAS</span>
									</div><!-- /.sobre-linha -->

									<br><br>			

									<ul class="row list list-categorias">
										@foreach ($categorias as $cat)
											<li class="categoria">
												{{ $cat->name }}

												{!! link_to_action('CategoriaController@categoriaEdit','',array($cat->id),['class'=>'item-edit']) !!}

												<a href="#excluir" 
													class="item-delete" 
													data-href="{{ route('categoria_destroy', $cat->id) }}"
													data-header="Excluir Categoria"
													data-body="Deseja excluir definitivamente a categoria <?php echo $cat->name; ?> ?"
													role="button" 
													data-toggle="modal" 
													data-target="#modal-confirm-delete">
												</a>
											</li>
											@foreach ($cat->subcategoria as $subcat)
												<li class="subcategoria">
													{{ $subcat->name }}

													{!! link_to_action('CategoriaController@subcategoriaEdit','',array($subcat->id),['class'=>'item-edit']) !!}

													<a href="#excluir" 
														class="item-delete" 
														data-href="{{ route('subcategoria_destroy', $subcat->id) }}"
														data-header="Excluir Subcategoria"
														data-body="Deseja excluir definitivamente a subcategoria <?php echo $subcat->name; ?> ?"
														role="button" 
														data-toggle="modal" 
														data-target="#modal-confirm-delete">
													</a>
												</li>
											@endforeach
										@endforeach			
									</ul><!-- /.row -->
									<div class="row">
										{!! str_replace('/?', '?', $categorias->render()) !!}
									</div><!-- /.row -->
								</div><!-- /.col-sm-11 col-sm-offset-1 -->
							</div><!-- /.col-sm-5 .col-sm-offset-1 -->
							<!-- FIM DIREITA -->
						</div><!-- /.row -->




		</div><!-- /.main -->
@endsection