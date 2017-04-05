<?php 
$header_title = 'Todos os Posts';

$scripts = array(
    0 => array(
        'src' => URL::to('/').elixir('js/pages/post_todos.js')
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
							TODOS OS POSTS
						</h1>
						<br />
						

						<div class="row sobre-linha-acima">
							<div class="col-sm-8 sobre-linha">
								<span>TODOS | {{ $posts->total() }} | &nbsp;</span> 
								<span>PUBLICADOS | {{ $count_publicados }} | &nbsp;</span> 
								<span>AGENDADOS | {{ $count_agendados }} | &nbsp;</span> 
								<span>RASCUNHOS | {{ $count_rascunhos }} | &nbsp;</span> 
							</div><!-- /.col-sm-8 -->	

							<div class="col-sm-4 alinhado-esquerda-padding text-right">
								<div class="col-sm-7 col-md-8 sem-padding">
									{!! Form::text('buscar_blog', session('filtro_busca_todos_posts',null), ['class'=>'form-control font-cuprum font-size-16 filtro-buscar-blog','placeholder'=>'Buscar no blog']) !!}
								</div><!-- /.col-md-2 --> 
								<div class="col-sm-2">
									<button class="btn btn-amarelo btn-meio-medio btn-estreito bt-buscar-blog">BUSCAR</button>
								</div><!-- /.col-md-4 -->
							</div><!-- /.alinhado-direita-padding -->		

						</div><!-- /.row -->

						<div class="row form-inline sobre-linha-acima">
							<div class="col-sm-12 form-group">
								@if (Auth::user()->is_admin())	
									{!! Form::select('select_acao', array(''=>'Ação','excluir'=>'Excluir'), null, ['class'=>'select-acao form-control arrow-preto-amarelo']) !!}
									<button class="aplicar-select-acao-post btn btn-preto btn-meio-medio btn-estreito">APLICAR</button>
								@endif							

								<!-- {!! Form::select('select_data', [null=>'Todas as datas'] + $select_datas, session('filtro_data_todos_posts',null), ['class'=>'form-control arrow-preto-amarelo select-data']) !!} -->
								<span>de </span>{!! Form::input('select_data_inicio','', session('filtro_data_inicio_todos_posts',null), ['class'=>'form-control font-cuprum font-size-16 select-data-inicio datepicker','data-date-format'=>'dd-mm-yyyy']) !!}
								<span>até</span> {!! Form::input('select_data_final','', session('filtro_data_final_todos_posts',null), ['class'=>'form-control font-cuprum font-size-16 select-data-final datepicker','data-date-format'=>'dd-mm-yyyy']) !!}
								
								{!! Form::select('select_categorias', [null=>'Todas as categorias'] + $select_categorias,  session('filtro_categorias_todos_posts',null), ['class'=>'form-control arrow-preto-amarelo select-categorias']) !!}
							
								{!! Form::select('select_subcategorias', [null=>'Todas as subcategorias'] + $select_subcategorias, session('filtro_subcategorias_todos_posts',null), ['class'=>'form-control arrow-preto-amarelo select-subcategorias']) !!}
								
								{!! Form::select('select_tags', [null=>'Todas as tags'] + $select_tags,  session('filtro_tag_todos_posts',null), ['class'=>'form-control arrow-preto-amarelo select-tags']) !!}
								<button class="filtrar-posts-todos-posts btn btn-preto btn-meio-medio btn-estreito">FILTRAR</button>
								

							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-8">
								{!! str_replace('/?', '?', $posts->render()) !!}
							</div><!-- /.col-sm-8 -->														
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
								
								@foreach ($posts as $post)
									<tbody>
										<tr>
											<td>
												<div class="row">
													{!! Form::checkbox('post', $post->id, null, ['class'=>'post-checkbox']) !!}
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
													@if (Auth::user()->is_admin())	
														{!! link_to_action('PostController@edit',' &nbsp;  &nbsp;EDITAR&nbsp;  &nbsp; ',$post->id,['class'=>'btn btn-preto btn-extra-pequeno align-center']) !!}
														&nbsp;
														<a href="#excluir" 
															class="btn btn-preto btn-extra-pequeno align-center" 
															data-href="{{ route('post_destroy', $post->id) }}"
															data-header="Excluir Post"
															data-body="Deseja excluir definitivamente o post <?php echo $post->titulo; ?> ?"
															role="button" 
															data-toggle="modal" 
															data-target="#modal-confirm-delete"> &nbsp; EXCLUIR &nbsp; 
														</a>
													@endif
													&nbsp;
													@if($post->postAtivo())
														{!! link_to_action('blog\HomeBlogController@click_interna',' &nbsp;  &nbsp;VER POST&nbsp;  &nbsp; ',array($post->cliente->slug,$post->slug),['class'=>'btn btn-amarelo btn-extra-pequeno align-center','target'=>'_blank']) !!}
													@endif
												</div>
											</td>
										</tr>	
									</tbody>						
								@endforeach		
														
							</table>

								

						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-8">
								{!! str_replace('/?', '?', $posts->render()) !!}
							</div><!-- /.col-sm-8 -->														
						</div><!-- /.row -->
		</div><!-- /.main -->


@endsection