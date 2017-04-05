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
						

						<h1>EDITAR USUÁRIO</h1>
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


						{!! Form::model($usuario,['method'=>'PUT','action'=>['UserController@update',$usuario->id], 'class'=>'form-horizontal', 'files' => true]) !!}

							<div class="form-group">
								{!! Form::label('cliente','Escolha o(s) cliente(s)', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::select('cliente[]', $clientes_select, $usuario->ClienteList, ['class'=>'select2multiple form-control', 'multiple']) !!}
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
									{!! Form::label('photo','UPLOAD DE IMAGEM', ['class'=>'control-label col-sm-3']) !!}
									<div class="col-sm-6">
										<div id="usuario_novo_imagem_selecionada" class="form-control"></div>
									</div><!-- /.col-sm-6 -->
									<div class="col-sm-3">
										<span class="btn btn-cinza btn-pequeno btn-file btn-lateral-form" id="usuario_novo_selecionar_imagem">
											SELECIONAR IMAGEM   {!! Form::file('photo', ['class'=>'','accept'=>'image/*']) !!}
										</span>
									</div><!-- /.col-sm-3 -->						
								<div class="row">
									<small class="col-sm-6 col-sm-offset-3">
										<br />
										TAMANHO DA IMAGEM 100PX X 100PX  |  .PNG ou .JPG
									</small>
								</div><!-- /.row -->
							</div><!-- /.form-group -->

							
							<?php 							
							$ultimo = array_last($usuario->link_pessoal,function($key,$value){ return $value; });
							$link_pessoal_count = 0; 
							?>
							@foreach ($usuario->link_pessoal as $link_pessoal)	
								<div class="form-group group-link-pessoal">
									{!! Form::label('link_pessoal['.$link_pessoal_count.']','Link Pessoal '.($link_pessoal_count+1), ['class'=>'control-label col-sm-3']) !!}
									<div class="col-sm-6">
										{!! Form::text('link_pessoal['.$link_pessoal_count.']', $usuario->link_pessoal[$link_pessoal_count], ['class'=>'form-control']) !!}
									</div><!-- /.col-md-9 -->


									@if ($link_pessoal == $ultimo)
										<div class="col-md-3">
											<a id="usuario_novo_adicionar_link_pessoal" class="btn btn-cinza btn-pequeno btn-lateral-form">ADICIONAR NOVO LINK</a>
										</div><!-- /.col-md-3 -->
										<script type="text/javascript"> var link_pessoal_count=<?php echo $link_pessoal_count; ?>; </script>
									@endif
								</div><!-- /.form-group -->
								<?php $link_pessoal_count++; ?>
							@endforeach
							
							<div class="form-group">
								{!! Form::label('password','Senha', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::password('password', ['class'=>'form-control']) !!}
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->

							<div class="form-group">
								{!! Form::label('password_confirmation','Repetir Senha', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::password('password_confirmation', ['class'=>'form-control']) !!}
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->

							<div class="form-group">
								{!! Form::label('enviar_email','ENVIAR LOGIN + SENHA', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									&nbsp; {!! Form::checkbox('enviar_email', 1, false, ['class'=>'align-checkbox']) !!} &nbsp; <span class="texto-cinza desc">Enviar esse login e senha para o e-mail cadastrado</span>
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->
							
							<div class="form-group">
								{!! Form::label('group','Nível do Usuário', ['class'=>'control-label col-sm-3']) !!}
								<div class="col-sm-6">
									{!! Form::select('group', $groups_select, $usuario->group()->lists('id','name'), ['class'=>'form-control arrow-preto-amarelo']) !!}
								</div><!-- /.col-md-9 -->
							</div><!-- /.form-group -->
						
							<div class="form-group">
								<div class="col-sm-6 col-sm-offset-3">
									{!! Form::submit('EDITAR USUÁRIO', ['class'=>'btn btn-amarelo btn-medio align-center']) !!}
								</div><!-- /.col-sm-6 col-sm-offset-3 -->
							</div><!-- /.form-group -->

							
							
						{!! Form::close() !!}
		</div><!-- /.main -->
@endsection