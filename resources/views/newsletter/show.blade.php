@include('header')

<div class="container-fluid newsletter-gerada">

<div class="col-sm-10 col-sm-offset-1">

	<div class="row">		
		<div class="col-sm-12">
			<br />
			
			<h1>
				@if (isset($cliente_default))
					<!-- <img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img> -->
				@endif
				<span>MONITORAMENTO DA CONCORRÊNCIA</span><br>
			</h1>
			<span class="sub-infos">{{ mb_strtoupper($newsletter->cliente->name) }} | {{ mb_strtoupper($newsletter->mesNome.' '.$newsletter->ano) }}</span>
			<br />
		</div><!-- /.col-sm-12 -->
	</div><!-- /.row -->

	
	<?php $x = 0; $novalinha = 0; $totalposts = count($newsletter->post()->get()); ?>
	@foreach ($newsletter->post()->get() as $post)		
	<?php 
	if($x!=0) {$novalinha++;}
	if($x%2 || $x==0){ ?>
	<div class="row">
	<?php } ?>	

			<div class="col-sm-<?php if($x==0){ echo '12'; } else { echo '6'; } ?>  box-post">
				@if (count($post->midia))
					<?php $midia = $post->midia->first(); ?>
					<div class="box-img-post-destaque">
						<img class="img-post-destaque" src="{{ URL::to('/').'/'.$midia->imagem }}">
					</div><!-- /.box-img-post-destaque -->
				@endif

				<h2>{{ mb_strtoupper($post->titulo) }}</h2>
				<p>{!! _resumo(_limpaHtmlJs($post->desc,true),0,280) !!}</p><br>
				<a target="_blank" href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$post->slug))}}" class="btn btn-amarelo btn-pequeno btn-auto-width align-center"> &nbsp; VER POSTS &nbsp;&nbsp; </a>
			</div><!-- /.col-sm-x box-post -->
	
	<?php if($x==0 || $novalinha == 2 || ($x+1) == $totalposts){ if($x!=0) {$novalinha=0;} ?>	
	</div><!-- /.row -->
	<?php } ?>

	<?php $x++; ?>
	@endforeach

</div><!-- /.col-sm-11 -->

</div><!-- /.container-fluid -->


@extends('footer_news')