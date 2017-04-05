<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token()}}">
	@if (isset($header_title))
		<title>{{ $header_title }}</title>
	@else 
		<title>Lewlara</title>
	@endif

	<link href="{{ asset('css/pdf_post.css') }}" rel='stylesheet' type='text/css'>

</head>
<body>

<div class="container-fluid gerar-pdf">

<div class="col-sm-12" >

	<div class="row">		
		<div class="col-sm-12">
			<img width="100%" src="{{ URL::to('/img/header_news.png')}}"><br />
			<h1 class="titlenews" style="width:'100%';margin-bottom: 20px;line-height: 61px;padding: 0 0 10px 0;height: 100px">
				@if (isset($cliente_default))
					<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}" />
				@endif
				<span class="sub-infos" style="">{{ mb_strtoupper($cliente_default->name) }} </span>
				<span class="date" style="">{{ date('d.m.Y')}}</span>
			</h1>
			
			<br />
		</div><!-- /.col-sm-12 -->
	</div><!-- /.row -->

	

	<?php $c = 1; ?>
	@foreach ($postsValidos as $post)		
		@if($c != 1)
			<div class="quebra_pagina"></div>			
		@endif 
		<?php $c++; ?>
		<div class="row" style="margin-top: 20px;">
			<div class="col-sm-12" class="box-post">
				<h2 class="post-titulo"><strong>{{ mb_strtoupper($post->titulo) }}</strong></h2>
				{{-- <span class="post-info">Publicado por: {{ mb_strtoupper($post->user->first_name.' '.$post->user->last_name) }} &nbsp;|&nbsp;  --}}{{-- Categoria: 
				@foreach($post->categoria as $key => $cat)
					@if($key == count($post->categoria)-1)
						{{ mb_strtoupper($cat->name)}}
					@else
						{{ mb_strtoupper($cat->name).','}}
					@endif
				@endforeach &nbsp;|&nbsp; --}}
				Data: {{ date('d/m/Y',strtotime($post->publicar_em)) }}</span>

				@if (count($post->midia))
					<?php $midia = $post->midia->first(); ?>
					<div class="box-img-post-destaque" align="center">
						<img class="img-post-destaque" src="{{ URL::to('/').'/'.$midia->imagem }}">
					</div><!-- /.box-img-post-destaque -->
				@endif

				{!! rtrim(_limpaHtmlJsPDF(_replaceGalleryPDF($post->desc),true)) !!}				
			</div><!-- /.col-sm-12 -->
		</div><!-- /.row -->
		<hr>
	@endforeach

</div><!-- /.col-sm-12 -->

</div><!-- /.container-fluid -->




<!-- FOOTER FOOTER  -->
<div class="row">
	<div class="col-sm-12">
		<footer class="footer rodape ">
			<img width="100%" src="{{ URL::to('/img/footer_news.png')}}">
		</footer>
	</div><!-- /.col-sm-12 -->
</div><!-- /.row -->



</body>
</html>