@include('header')






<div class="container-fluid login">

	<div class="row">		
			<div class="col-sm-8 col-sm-offset-1">
				<div class="alinha-esquerda">
					<br />
					<br />
					<header>
						<h1 id='login-titulo'><a href="{{ URL::to('/') }}">LEW'LARA\TBWA</a></h1>
						<h3 id='login-subtitulo'>[ Pulso da Comunicação ]</h3>
					</header>

					<br />
					<br />

					<p id="login-titulo-descricao">
						<!-- loren ipsum -->
					</p>
				</div><!-- /.row -->
			</div><!-- /.col-sm-8 col-sm-offset-2 -->
			
	</div><!-- /.row -->

	<div class="row">
		<div class="col-sm-5 separador-horizontal-cinza">
			<section  class="col-sm-8 col-sm-offset-2">
				<header>
					<h2 class="anula-padding">JÁ TENHO CADASTRO</h2>
				</header>

				@if (count($errors) > 0)	
					<div class="alert alert-danger anula-padding">
						<!-- <strong>Whoops!</strong> There were some problems with your input.<br><br> -->
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif 

				{!! Form::open(array('route' => 'login', 'method'=>'POST', 'class' => 'form-horizontal')) !!}
				    					    
				    <div class="form-group">					
						{!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder'=>'Digite seu e-mail']) !!}
					</div>

				    <div class="form-group">
						{!! Form::password('password', ['class' => 'form-control', 'placeholder'=>'Digite sua senha']) !!}
					</div>

					{{-- <div class="form-group">
						<div class="col-sm-6 col-sm-offset-4">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="remember"> Remember Me
								</label>
							</div>
						</div>
					</div> --}}

					<div class="form-group">
                                            	<i class="icon-interrogacao">&nbsp;</i>{!! link_to_action('PasswordController@main','ESQUECI MINHA SENHA',array(),['id'=>'link-esqueci-minha-senha']) !!}
					</div><!-- /.form-group -->

				    <div class="form-group">						
						<button type="submit" class="btn  btn-amarelo btn-medio">
							ENTRAR
						</button>						
					</div>

				{!! Form::close() !!}
			</section>
		</div><!-- /.col-sm-4 -->







		<div class="col-sm-7">			
			<section class="col-sm-8 col-sm-offset-1">
					<header>
						<h2>NÃO TENHO CADASTRO</h2>
					</header>


					<a href="{{ action('CustomAuth@solicitarCadastro') }}" class="btn btn-amarelo btn-medio">SOLICITAR CADASTRO</a>
			</section>
		</div><!-- /.col-sm-4 -->
	</div><!-- /.row -->
</div><!-- /.container-fluid login -->





@extends('footer')