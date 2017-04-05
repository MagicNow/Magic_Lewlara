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

					<h1 class="anula-padding solicitar">SOLICITAR CADASTRO</h1>

					<p>
						<!-- loren ipsum -->
					</p>

					<br />
			
				</div><!-- /.row -->
			</div><!-- /.col-sm-8 col-sm-offset-2 -->
			
	</div><!-- /.row -->

	<div class="row">
		<div class="col-sm-10 anula-padding">
			<section  class="col-sm-6 col-sm-offset-1">

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

				{!! Form::open(array('route' => 'solicitar-cadastro-post', 'method'=>'POST', 'class' => 'form-horizontal')) !!}
				    
			        <div class="form-group">					
			    		{!! Form::text('nome', old('nome'), ['class' => 'form-control', 'placeholder'=>'Digite seu nome']) !!}
			    	</div>

				    <div class="form-group">					
						{!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder'=>'Digite seu e-mail']) !!}
					</div>

				    <div class="form-group">					
						{!! Form::text('cliente', old('cliente'), ['class' => 'form-control', 'placeholder'=>'Qual o cliente?']) !!}
					</div>
					
				    <div class="form-group">
				    	{!! Form::text('areacargo', old('areacargo'), ['class' => 'form-control', 'placeholder'=>'Área e cargo']) !!}
					</div>

				    <div class="form-group">						
						<button type="submit" class="btn  btn-amarelo btn-medio">
							ENVIAR
						</button>						
					</div>

				{!! Form::close() !!}
			</section>
		</div><!-- /.col-sm-4 -->

	</div><!-- /.row -->
</div><!-- /.container-fluid login -->





@extends('footer')