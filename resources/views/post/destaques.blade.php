<?php 
$header_title = 'Posts em Destaque';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main usuario">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif
							POSTS EM DESTAQUE
						</h1>
						<br />
						
						

						<div class="row">
							<div class="col-sm-8 sobre-linha">
								<span>POSTS EM DESTAQUE | {{ $posts->total() }} | &nbsp;</span> 
							</div><!-- /.col-sm-8 -->
						</div><!-- /.row -->
						<div class="row">
							<table class="col-sm-12 com-paginacao">
								<thead>
									<td class="col-sm-6">
										POSTS
									</td>
									<td class="col-sm-6">
										
									</td>
								</thead>
								@foreach ($posts as $post)
									<tbody>
										<tr>
											<td>
												<div class="row">
													<span class="abre-box-child"> 
														{{ mb_strtoupper($post->titulo) }}
													</span>
												</div><!-- /.row -->											
											</td>											
											<td class="direita-padding text-right">	
												{!! link_to_action('PostController@edit',' &nbsp;  &nbsp;EDITAR POST&nbsp;  &nbsp; ',$post->id,['class'=>'btn btn-amarelo btn-extra-pequeno align-center']) !!}
												&nbsp;
												<a href="#excluir" 
													class="btn btn-preto btn-pequeno align-center" 
													data-href="{{ route('post_destaque_retirar', $post->id) }}"
													data-header="Retirar Destaque"
													data-body='Deseja retirar o destaque do post "<?php echo mb_strtoupper($post->titulo); ?>" ?'
													role="button" 
													data-toggle="modal" 
													data-target="#modal-confirm"
													data-btcancel="NÃO, MANTER COMO DESTAQUE"
													data-btok="SIM">
														RETIRAR DESTAQUE
												</a>
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