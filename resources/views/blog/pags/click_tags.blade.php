
<?php 
$header_title = 'Tags';
?>


@extends('blog/baseblog')

@section('content')

<div class="container-fluid conteudo-blog">
	<div class="row-fluid">
		
		<div class="col-xs-12 col-sm-9 col-md-9 "> 
			<h4 class="titulo-conteudo" title="TAGS">TAGS </h4>
			@if (count($listar_tags))
				<div class="sidebar-columns">
					@foreach($listar_tags as $i => $tags)
						<ul class="nav-tags">
							<li class="sidebar-titulo nav-tags-titulo-tag title={{ $tags['letra'] }}">{{$tags['letra']}}</li>
							<!--LISTANDO AS DE TAGS -->
							@foreach($tags['tags'] as $j => $tag)
								<li class="nav-tags-item"><a class="nav-tags-item-link no-item-link" href="{{ action('blog\HomeBlogController@click_tag',array($cliente_default->slug,$tag['slug'])) }}" title="{{ $tag['nome'] }}">{{  $tag['nome']." (".$tag['total'].")" }}</a></li>
							@endforeach
						</ul>
					@endforeach
				</div>
			@else 
				Não há tags vinculadas à posts até o momento.
			@endif
		</div>
		<aside class="col-xs-12 col-sm-3 col-md-3 sidebar blog-sidebar">
			@include('blog/arquivos')
			@include('blog/top_posts')
		</aside>	

	</div>
</div>

@endsection
