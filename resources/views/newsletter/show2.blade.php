@include('header')

<div class="container-fluid newsletter-gerada">

	<div class="col-sm-12">

		<div class="row">		
			<div class="col-sm-12">
				<img src="{{ URL::to('/img/header_news.png')}}">
				<h1 class="titlenews">
					@if (isset($cliente_default))
						<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}">
					@endif
					<span class="sub-infos">{{ mb_strtoupper($newsletter->cliente->name) }} </span>
					<span class="date">{{ date('d.m.Y',strtotime($newsletter->created_at))}}</span>
				</h1>
				
				<br />
			</div><!-- /.col-sm-12 -->
		</div><!-- /.row -->
		<div class="row">
			@foreach ($newsletter->post()->get() as $post)
				<a target="_blank" href="{{action('blog\HomeBlogController@click_interna',array($cliente_default->slug,$post->slug))}}" class="col-sm-6 box-post">
					@if (count($post->midia))
						<?php $midia = $post->midia->first(); ?>
						<div class="box-img-post-destaque" style="">
							<img class="img-post-destaque" src="{{ URL::to('/').'/'.$midia->imagem }}" width="100%" height="auto" style="width: 100%;">
						</div><!-- /.box-img-post-destaque -->
					@endif
					<table cellspacing="0" cellpadding="0" border="0">
						<tr>
							{!! $post->pivot->atualizacao ? '<td><img class="img-post-atualizao" alt="Atualização" src="'. URL::to('/img/update-icon.png') . '"></td>' : '' !!}
							<td><h2>{{ mb_strtoupper($post->titulo) }}</h2></td>
						</tr>
					</table>
					{{-- <p>{!! _resumo(_limpaHtmlJs($post->desc,true),0,280) !!}</p><br> --}}
				</a>	
			@endforeach
		</div>
	</div><!-- /.col-sm-12 -->
	<footer class="">
	    <img src="{{ URL::to('/img/footer_news.png')}}">
	</footer>
</div><!-- /.container-fluid -->
</body>
</html>