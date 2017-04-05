@include('header')

<div class="container-fluid login">

	<div class="row">		
			<div class="col-sm-8 col-sm-offset-1">
				<div class="alinha-esquerda">
					<br />
					<br />
					<header>
						<h1 id='login-titulo'><a href="{{ URL::to('/') }}">LEW'LARA\TBWA</a></h1>
						<h3 id='login-subtitulo'>[ EXTRANET ]</h3>
					</header>

					<br />
					<br />
                                        <p class="esqueci-senha-main-p">
                                            <i class="icon-interrogacao-35">&nbsp;</i>
                                            <span>ESQUECI MINHA SENHA</span>
                                        </p>
					<p id="login-titulo-descricao">
						Identifique-se para receber um e-mail com as instruções e o link para criar uma nova senha.
					</p>
				</div><!-- /.row -->
			</div><!-- /.col-sm-8 col-sm-offset-2 -->
			
	</div><!-- /.row -->

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
		<div class="col-sm-5">
			<section  class="col-sm-8 col-sm-offset-2">
				 
				{!! Form::open(['id'=>'redefine-form','method' => 'POST', 'action' => 'PasswordController@email', 'class' => 'form-horizontal']) !!}				    
                                        <div class="form-group">						
                                            {!! Form::email('useremail', '',['class'=>'form-control','placeholder' => 'Digite seu e-mail','id'=>'user-email']); !!}
                                        </div>
                                        <div class="form-group">						
                                            {!! Form::submit('ENVIAR', ['name'=>'cadastrar','class'=>'btn btn-amarelo btn-medio align-center']) !!}						
					</div>
                                        {!! Form::token(); !!}
				{!! Form::close() !!}
			</section>
		</div><!-- /.col-sm-4 -->
	</div><!-- /.row -->
</div><!-- /.container-fluid login -->





@extends('footer')