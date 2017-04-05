<?php 
$header_title = 'Gerar PDF';

$scripts = array(
    0 => array(
        'src' => URL::to('/').elixir('js/pages/post_gerarpdf.js')
    )
); 

?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main usuario">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif
							GERAR PDF
						</h1>
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
						
			

						<div class="row sobre-linha-acima">
							<div class="col-sm-8 sobre-linha">
								<span>TODOS | {{ $posts->total() }} | &nbsp;</span>
							</div><!-- /.col-sm-8 -->		

							<div class="col-sm-4 alinhado-direita-padding text-right">
								<div class="col-sm-7 col-md-8 sem-padding">
									{!! Form::text('buscar_blog', session('filtro_busca_pdf',null), ['class'=>'form-control font-cuprum font-size-16 filtro-buscar-blog','placeholder'=>'Buscar no blog']) !!}
								</div><!-- /.col-md-2 --> 
								<div class="col-sm-5 col-md-4">
									<button class="btn btn-amarelo btn-meio-medio btn-estreito bt-buscar-blog">BUSCAR</button>
								</div><!-- /.col-md-4 -->
							</div><!-- /.alinhado-direita-padding -->		
						</div><!-- /.row -->
						
						<div class="row form-inline sobre-linha-acima">
							<div class="col-sm-12 form-group">
								
								{!! Form::select('select_data', [null=>'Todas as datas'] + $select_datas, session('filtro_data_pdf',null), ['class'=>'form-control arrow-preto-amarelo select-data']) !!}

								{!! Form::select('select_categorias', [null=>'Todas as categorias'] + $select_categorias,  session('filtro_categorias_pdf',null), ['class'=>'form-control arrow-preto-amarelo select-categorias']) !!}

								<?php /*
								{!! Form::select('select_subcategorias', [null=>'Todas as subcategorias'] + $select_subcategorias, session('filtro_subcategorias_todos_posts',null), ['class'=>'form-control arrow-preto-amarelo select-subcategorias']) !!}
								*/ ?>

								<button class="filtrar-posts-pdf btn btn-preto btn-meio-medio btn-estreito">FILTRAR</button>

							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-8">
								{!! str_replace('/?', '?', $posts->render()) !!}
							</div><!-- /.col-sm-8 -->	
							<div class="col-sm-4 alinhado-direita-padding text-right">
								<a href="{{ URL::action('PostController@pdfGerar',$cliente_default->slug) }}" target="_blank" class="btn btn-amarelo btn-meio-medio btn-estreito bt-gerar-newsletter">GERAR PDF</a>
							</div><!-- /.col-md-4 -->

						</div><!-- /.row -->


						<div class="row">
							<table class="col-sm-12 com-paginacao-superior com-paginacao-inferior">
								<thead>
									<td class="col-sm-5">
										TÍTULO
									</td>
									<td>
										AUTOR
									</td>
									<td>
										CATEGORIA
									</td>
									<td>
										DATA
									</td>
								</thead>
								@if (count($posts))								
									@foreach ($posts as $post)
										<tbody>
											<tr>
												<td>
													<div class="row">
														{!! Form::checkbox('posts[]', $post->id, array_key_exists($post->id, $postsSelecionados_pdf), ['class'=>'post-checkbox']) !!}
														@if ($post->logo)
															

														@endif
														&nbsp; 
														<span> 
															{{ $post->titulo }}
														</span>
													</div><!-- /.row -->											
												</td>				
												<td>
													{{ mb_strtoupper($post->user->first_name . ' ' . $post->user->last_name) }}
												</td>	
												<td>
													@foreach ($post->categoria as $categoria)
						                                {{ mb_strtoupper($categoria->name) }} <br>
						                                @if ($categoria->pivot->sub_categoria_id)
						                                    &nbsp; <small>{{ $post->subcategoriaName($categoria->pivot->sub_categoria_id) }}</small><br>
						                                @endif
						                            @endforeach
												</td>						
												<td class="direita-padding">									
												 	{{ date('d/m/Y H:i',strtotime($post->publicar_em)) }} <br>
												 	{{ strtoupper($post->StatusName) }}
												</td>
											</tr>	
											<tr >
												<td colspan="2" class="infos"> 
													<div class="infos col-sm-12 direita-padding">
														{!! link_to_action('blog\HomeBlogController@click_interna',' &nbsp;  &nbsp;VER POST&nbsp;  &nbsp; ',array($post->cliente->slug,$post->slug),['class'=>'btn btn-amarelo btn-extra-pequeno align-center']) !!}	
													</div>
												</td>
											</tr>	
										</tbody>						
									@endforeach		
								@else
									<tbody>
										<tr>
											<td colspan="4">Não há posts com o filtro selecionado</td>
										</tr>
									</tbody>
								@endif						
							</table>

								

						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-8">
								{!! str_replace('/?', '?', $posts->render()) !!}
							</div><!-- /.col-sm-8 -->	
							<div class="col-sm-4 alinhado-direita-padding text-right">
								<a href="{{ URL::action('PostController@pdfGerar',$cliente_default->slug) }}"  target="_blank" class="btn btn-amarelo btn-meio-medio btn-estreito bt-gerar-newsletter">GERAR PDF</a>
							</div><!-- /.col-md-4 -->

						</div><!-- /.row -->


		</div><!-- /.main -->


@endsection