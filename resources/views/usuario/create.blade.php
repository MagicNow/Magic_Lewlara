<?php 
$header_title = 'Novo Usuário';
?>

<?php
$scripts = array(
    0 => array(
        'src' => URL::to('/').elixir('js/pages/usuario.js')
    ),
); 
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main usuario">
					
						<br />
						

						<h1>NOVO USUÁRIO</h1>
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


						{!! Form::open(['action'=>'UserController@store', 'class'=>'form-horizontal', 'files' => true]) !!}

							<div class="form-group">
								{!! Form::label('cliente','Escolha o(s) cliente(s)', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::select('cliente[]', $clientes_select, null, ['class'=>'select2multiple form-control', 'multiple']) !!}
								</div><!-- /.col-sm-6 -->
							</div><!-- /.form-group -->
							
							<div class="form-group">
								{!! Form::label('username','Nome de Usuário', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::text('username', null, ['class'=>'form-control']) !!}
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->

							<div class="form-group">
								{!! Form::label('email','E-mail', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::email('email', null, ['class'=>'form-control']) !!}
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->

							<div class="form-group">
								{!! Form::label('first_name','Nome', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::text('first_name', null, ['class'=>'form-control']) !!}
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->

							<div class="form-group">
								{!! Form::label('last_name','Sobrenome', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::text('last_name', null, ['class'=>'form-control']) !!}
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->
							
							<div class="form-group">
								{!! Form::label('password','Senha', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::text('password',  $random_pass, ['onclick'=>"this.type='password';",'class'=>'form-control']) !!}
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->

							<div class="form-group">
								{!! Form::label('password_confirmation','Repetir Senha', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::text('password_confirmation', $random_pass, ['onclick'=>"this.type='password';", 'class'=>'form-control']) !!}
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->
							
							<div class="form-group">
								{!! Form::label('group','Nível do Usuário', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::select('group', $groups_select, null, ['class'=>'form-control arrow-preto-amarelo']) !!}
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->
						
							<div class="form-group">
								<div class="col-sm-6 col-sm-offset-3">
									{!! Form::submit('CADASTRAR USUÁRIO', ['class'=>'btn btn-amarelo btn-medio align-center']) !!}
								</div><!-- /.col-sm-6 col-sm-offset-3 -->
							</div><!-- /.form-group -->

							
							
						{!! Form::close() !!}
		</div><!-- /.main -->
@endsection