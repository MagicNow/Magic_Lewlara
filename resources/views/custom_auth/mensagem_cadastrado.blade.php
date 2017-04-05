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

					<h1 class="anula-padding">SUCESSO!</h1>
					<h2></h2>
					<p>
						Sua solicitação de cadastro foi enviada. Em breve você receberá um e-mail com as instruções
					</p>

					<br />
			
				</div><!-- /.row -->
			</div><!-- /.col-sm-8 col-sm-offset-2 -->
			
	</div><!-- /.row -->

	
</div><!-- /.container-fluid login -->





@extends('footer')