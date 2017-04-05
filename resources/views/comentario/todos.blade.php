<?php 
$header_title = 'Todos os Comentários';
?>

<?php
$scripts = array(
    0 => array(
        'src' => URL::to('/').elixir('js/pages/comentario.js')
    ),
); 
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main comentario">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif							
							TODOS OS COMENTÁRIOS
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
						

						<div class="row">	
							<div class="col-sm-8 sobre-linha">
								@if(!Auth::user()->is_cliente())								
									<span>TODOS | {{ $count_todos }} | &nbsp;</span> 
									<span>ADMINISTRADORES | {{ $count_admin }} | &nbsp;</span> 
									<span>CLIENTES | {{ $count_cliente }} | &nbsp;</span> 
									<span>LEW LARA | {{ $count_lewlara }} |</span>
								@endif
							</div><!-- /.col-sm-8 -->

							<div class="col-sm-4 alinhado-direita-padding text-right">								
								<div class="col-sm-7 col-md-8 sem-padding">
									{!! Form::select('ordenar_por', $select_ordenar_por, $ordenar_por_default, ['class'=>'ordenar-por form-control arrow-preto-amarelo sem-padding']) !!}
								</div><!-- /.col-md-2 --> 
								<div class="col-sm-5 col-md-4 sem-padding">
									<button class="aplicar-ordem btn btn-preto btn-meio-medio btn-estreito">APLICAR</button>
								</div><!-- /.col-md-2 -->
							</div><!-- /.alinhado-direita-padding -->
						</div><!-- /.row -->
						
						<div class="row">
							<table class="col-sm-12 com-paginacao">
								<thead>
									<td class="col-sm-5">
										USUÁRIO
									</td>
									<td class="col-sm-3">
										NOME
									</td>
									<td class="col-sm-2">
										CLIENTE
									</td>
									<td class="col-sm-2">
										NÍVEL
									</td>
								</thead>
								
								@foreach ($comentarios as $comentario)									
									<?php $usuario = $comentario->user; ?>
									<tbody>
										<tr>
											<td>
												<div class="row">
													@if ($usuario->photo)
														<img src="{{ URL::to('/upload/usuario').'/'.$usuario->id.'/'.$usuario->photo }}" class="imagem-usuario">
														&nbsp; 
													@endif													
													{{ mb_strtoupper($usuario->username) }}
												</div><!-- /.row -->
											</td>
											<td>
												{{ mb_strtoupper($usuario->first_name.' '.$usuario->last_name) }}
											</td>
											<td>
												@if ($usuario->cliente()->count())												
													@foreach ($usuario->cliente as $cliente)
														{{ mb_strtoupper($cliente->name) }} <br />
													@endforeach		
												@else
														{{ mb_strtoupper('NENHUM') }}
												@endif	
											</td>
											<td>									
											 	@foreach ($usuario->group as $group)
											 		{{ mb_strtoupper($group->name) }}
											 	@endforeach
											</td>
										</tr>	
										<tr class="sem-padding-top">
											<td>
												<div class="separador-contexto">
													{{ $comentario->post->titulo }}
												</div>
											</td>
											<td colspan="3">
												<div class="box-exibir">
													{{ $comentario->comentario }}

													<br><br>

													<div class="col-sm-12">
														<a class="btn btn-amarelo btn-pequeno align-center bt-editar-comentario">EDITAR  COMENTÁRIO</a>
														&nbsp;
														<a href="#excluir" 
															class="btn btn-preto btn-pequeno align-center" 
															data-href="{{ route('comentario_destroy', $comentario->id) }}"
															data-header="Excluir Comentário"
															data-body="Deseja excluir definitivamente o comentário do usuário <?php echo mb_strtoupper($usuario->first_name.' '.$usuario->last_name); ?> ?"
															role="button" 
															data-toggle="modal" 
															data-target="#modal-confirm-delete">
																EXCLUIR COMENTÁRIO
														</a>
													</div><!-- /.col-sm-12 -->
												</div>
												<div class="box-editar">
													{!! Form::open(['action'=>['ComentarioController@update',$comentario->id], 'method'=>'PUT', 'class'=>'form-horizontal']) !!}
													
														{!! Form::textarea('comentario', $comentario->comentario , ['class'=>'form-control textarea-editar-comentario']) !!}	

													
													
														<br>

														<div class="col-sm-12">
															{!! Form::submit('SALVAR  COMENTÁRIO', ['class'=>'btn btn-amarelo btn-pequeno align-center']) !!}	
															&nbsp;
															<a 
																class="btn btn-preto btn-pequeno align-center bt-cancelar-editar-comentario" 
																>
																	CANCELAR EDIÇÃO
															</a>
														</div><!-- /.col-sm-12 -->
													{!! Form::close() !!}
												</div>
												
											</td>
										</tr>
									</tbody>								
								@endforeach															
							</table>

								
						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-8">
								{!! str_replace('/?', '?', $comentarios->render()) !!}
							</div><!-- /.col-sm-8 -->

							
						</div><!-- /.row -->
		</div><!-- /.main -->




		<!-- MODAL MODALS MODAIS  -->
		<!-- MODAL MODALS MODAIS  -->
		<!-- MODAL MODALS MODAIS  -->

		<div class="modal fade" id="modal-altera-visibilidade" tabindex="-1" role="dialog" aria-labelledby="Altera Visibilidade" aria-hidden="true">
		    <div class="modal-dialog">
		        <div class="modal-content">
		            <div class="modal-header">
		                Alterar Visibilidade
		            </div>
		            <div class="modal-body">
		                Deseja realmente alterar a visibilidade?
		            </div>
		            <div class="modal-footer">		            	
		            	<input id="info" type="hidden" data-visivel='1' data-idcliente='0' />
		                <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal">NÃO ALTERAR</button>
		                <a class="btn btn-danger btn-ok">ALTERAR</a>
		            </div>
		        </div>
		    </div>
		</div>


@endsection