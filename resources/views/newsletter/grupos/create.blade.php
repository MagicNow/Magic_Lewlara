<?php 
$header_title = 'Grupos';
?>

@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main newsletter newsletter-grupos">
					
						<br />
						

						<h1>
							<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							GERENCIAR GRUPOS
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


						{!! Form::open(['action'=>'NewsletterController@grupoStore', 'class'=>'form-horizontal']) !!}
							
							<div class="form-group">
								{!! Form::label('newsletter_group_name','Nome do Grupo', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::text('newsletter_group_name', null, ['class'=>'form-control']) !!}
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->

							<div class="form-group">
								<div class="col-sm-6 col-sm-offset-3">
									{!! Form::submit('CADASTRAR GRUPO', ['class'=>'btn btn-amarelo btn-medio align-center']) !!}
								</div><!-- /.col-sm-6 col-sm-offset-3 -->
							</div><!-- /.form-group -->							
							
						{!! Form::close() !!}

						<br>

						@if (count($grupos))
							<hr>
							<br>

							<div class="col-sm-12 col-md-9 col-md-offset-1">
								<h4>GRUPOS</h4>							
								<ul class="row list">
									@foreach ($grupos as $grupo)
										<li>									
											{{ $grupo->name }}
											{!! link_to_action('NewsletterController@grupoEdit','',array($grupo->id),['class'=>'item-edit']) !!}
										</li>
									@endforeach								
								</ul><!-- /.row -->
								
							</div><!-- /.col-sm-5 .col-sm-offset-1 -->
						@endif
		</div><!-- /.main -->
@endsection