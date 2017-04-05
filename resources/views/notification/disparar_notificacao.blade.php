<?php 
$header_title = 'Disparar Notificação';
?>
@extends('base_sidebar')


@section('content')

		<div class="col-sm-9  col-md-9 main notificacoes disparar-notificacao pessoas">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif
							DISPARAR NOTIFICAÇÃO
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

						<div class="row">
							<div class="col-sm-7">
								<!-- loren ipsum -->
							</div><!-- /.col-sm-7 -->
						</div><!-- /.row -->

						<br>

						{!! Form::open(['method'=>'POST', 'action'=>'NotificationController@registrarNotificacao', 'class'=>'form-horizontal', 'files' => true]) !!}
						
						<div class="row">
							<div class="col-sm-4 sem-padding">
								{!! Form::select('assunto', $select_assuntos, null, ['class'=>'form-control arrow-preto-amarelo-single form-control-pequeno']) !!}
								<br>
								{!! Form::select('post', $select_posts, null, ['class'=>'form-control arrow-preto-amarelo-single form-control-pequeno']) !!}
							</div><!-- /.col-sm-4 -->
						</div><!-- /.row -->

						<br>

						<div class="row">

							<div class="col-sm-12 font-arial-narrow text-bold anula-padding">
								<div class="col-sm-6 anula-padding titulo">
									{!! Form::checkbox('check-todos-checkbox', 1, false, array('class'=>'check-todos-checkbox')) !!} PESSOAS
								</div><!-- /.col-sm-12 -->
							</div><!-- /.col-sm-12 -->


							<?php $n = 0; ?>
							@if (count($pessoas))								
								@foreach ($pessoas as $pessoa)
									<div class="<?php echo ($n%2) ? 'col-sm-5 col-sm-offset-1' : 'col-sm-6' ?> pessoa">		<div class="borda">
											{!! Form::checkbox('pessoas[]', $pessoa->id, false, ['class'=>'checkable']) !!}

											@if ($pessoa->photo)
												<img src="{{ URL::to('/upload/usuario').'/'.$pessoa->id.'/'.$pessoa->photo }}" class="abre-editar-cliente imagem-usuario">
											@endif
															
											{{ mb_strtoupper($pessoa->first_name . ' ' . $pessoa->last_name) }}
										</div><!-- /.top-borda -->								
									</div><!-- /.col-sm-8 -->
								<?php $n++; ?>		
								@endforeach		
							@else
								<div class="col-sm-12">
									Não há pessoas cadastradas para o cliente {{ $cliente_default->name }}</td>
								</div><!-- /.col-sm-12 -->
							@endif	
						</div><!-- /.row -->

						<br>
						<br>

						<div class="row">
							{!! Form::submit('DISPARAR NOTIFICAÇÃO',['class'=>'btn btn-amarelo btn-medio bt-disparar-newsletter']) !!}
						</div><!-- /.row -->
						
						{!! Form::close() !!}

		</div><!-- /.main -->

@endsection