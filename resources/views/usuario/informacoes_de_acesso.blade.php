<?php 
$header_title = 'Informações de Acesso';
?>

<?php
$scripts = array(
    0 => array(
        'src' => URL::to('/').elixir('js/pages/usuario.js')
    ),
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
							INFORMAÇÕES DE ACESSO
						</h1>

						<br />
						
						

						<div class="row">
							<div class="col-sm-8 sobre-linha">
								<span>USUÁRIOS CADASTRADOS | {{ $usuarios->total() }} | &nbsp;</span> 
							</div><!-- /.col-sm-8 -->
							
							<div class="col-sm-4 alinhado-direita-padding text-right">								
								<div class="col-sm-7 col-md-8 sem-padding">
									{!! Form::select('ordenar_por', $select_ordenar_por, $default_ordenar_por, ['class'=>'ordenar-por form-control arrow-preto-amarelo sem-padding']) !!}
								</div><!-- /.col-md-2 --> 
								<div class="col-sm-5 col-md-4 sem-padding">
									<button class="aplicar-ordem btn btn-preto btn-meio-medio btn-estreito">APLICAR</button>
								</div><!-- /.col-md-2 -->
							</div><!-- /.alinhado-direita-padding -->
						</div><!-- /.row -->
						<div class="row">
							<table class="col-sm-12 com-paginacao">
								<thead>
									<td class="col-sm-4">
										USUÁRIO
									</td>
									<td class="col-sm-4">
										NOME
									</td>
									<td class="col-sm-2">
										CLIENTE
									</td>
									<td class="col-sm-2">
										NÍVEL
									</td>
								</thead>
								
								@foreach ($usuarios as $usuario)
									<tbody>
										<tr>
											<td>
												<div class="row">
													{!! Form::radio('cliente', $usuario->id, null, ['class'=>'abre-box-child-usuario']) !!}
													@if ($usuario->photo)
														<img src="{{ URL::to('/upload/usuario').'/'.$usuario->id.'/'.$usuario->photo }}" class="abre-box-child-usuario imagem-usuario">
													@endif
													&nbsp; 
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
										<tr >
											<td colspan="4" class="box-child"> 
												<div class="box-child col-sm-offset-1 direita-padding">
													@foreach ($usuario->log_acoes as $acoes)			
														{{ date('d.m.Y',strtotime($acoes->created_at)) }} &nbsp; | &nbsp; {{ date('H:i',strtotime($acoes->created_at)) }} &nbsp; | &nbsp; {{ $acoes->descricao }} <br />
													@endforeach
												</div>
											</td>
										</tr>	
									</tbody>						
								@endforeach		
														
							</table>

								

							<!-- {!! str_replace('/?', '?', $usuarios->render()) !!} -->

						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-8">
								{!! str_replace('/?', '?', $usuarios->render()) !!}
							</div><!-- /.col-sm-8 -->
							
							<div class="col-sm-4 alinhado-direita-padding text-right">
								<div class="col-sm-7 col-md-8">
									{!! Form::select('ordenar_por', $select_ordenar_por, $default_ordenar_por, ['class'=>'ordenar-por form-control arrow-preto-amarelo']) !!}
								</div><!-- /.col-md-2 -->
								<div class="col-sm-5 col-md-4">
									<button class="aplicar-ordem btn btn-preto btn-meio-medio btn-estreito">APLICAR</button>
								</div><!-- /.col-md-2 -->
							</div><!-- /.alinhado-direita-padding -->
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