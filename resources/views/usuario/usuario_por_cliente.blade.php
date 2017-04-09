<?php 
$header_title = 'Usuários por Cliente';
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
							USUÁRIOS POR CLIENTE
						</h1>
						
						<br />

						<div class="row">
							<div class="col-sm-6 sobre-linha">
								<a href="{{ action('UserController@usuariosPorCliente', array($default_ordenar_por, $cliente_default->slug, 'nome' => $nome)) }}" class="link-filtro">TODOS | {{ $count_total }} | &nbsp;</a> 
								<a href="{{ action('UserController@usuariosPorCliente', array($default_ordenar_por, $cliente_default->slug, 'filtro' => 'Admin', 'nome' => $nome)) }}" class="link-filtro">ADMINISTRADORES | {{ $count_admin }} | &nbsp;</a> 
								<a href="{{ action('UserController@usuariosPorCliente', array($default_ordenar_por, $cliente_default->slug, 'filtro' => 'Cliente', 'nome' => $nome)) }}" class="link-filtro">CLIENTES | {{ $count_cliente }} | &nbsp;</a> 
								<a href="{{ action('UserController@usuariosPorCliente', array($default_ordenar_por, $cliente_default->slug, 'filtro' => 'LewLara', 'nome' => $nome)) }}" class="link-filtro">LEW LARA | {{ $count_lewlara }} |</a>
							</div><!-- /.col-sm-8 -->

							<div class="col-sm-6 text-right form-filtra-usuario">
								<div class="col-sm-4 col-md-4">
									{!! Form::text('buscar_por', $nome, ['class'=>'buscar-por form-control arrow-preto-amarelo sem-padding', 'placeholder' => 'Buscar por nome']) !!}
								</div><!-- /.col-md-2 --> 
								<div class="col-sm-5 col-md-6">
									{!! Form::select('ordenar_por', $ordenar_por, $default_ordenar_por, ['class'=>'ordenar-por form-control arrow-preto-amarelo sem-padding']) !!}
								</div><!-- /.col-md-2 --> 
								<button class="aplicar-ordem btn btn-preto btn-meio-medio col-sm-3 col-md-2">APLICAR</button>
							</div>
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
								<tbody>
								@foreach ($usuarios as $usuario)
									
										<tr data-group='{!! $usuario->group[0]->name !!}'>
											<td>
												<div class="row">
													{!! Form::radio('cliente', $usuario->id, null, ['class'=>'abre-box-child-usuario']) !!}
													@if ($usuario->photo)
														<img src="{{ URL::to('/upload/usuario').'/'.$usuario->id.'/'.$usuario->photo }}" class="abre-editar-cliente imagem-usuario">
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
												<div class="box-child">
													<a href="{{ action('UserController@edit',$usuario->id) }}" class="btn btn-amarelo btn-pequeno align-center ">EDITAR USUÁRIO</a>
													&nbsp;
													<a href="#excluir" 
														class="btn btn-preto btn-pequeno align-center" 
														data-href="{{ route('usuario_destroy', $usuario->id) }}"
														data-header="Excluir Usuário"
														data-body="Deseja excluir definitivamente o usuário <?php echo mb_strtoupper($usuario->username); ?> de nome <?php echo mb_strtoupper($usuario->first_name.' '.$usuario->last_name); ?> ?"
														role="button" 
														data-toggle="modal" 
														data-target="#modal-confirm-delete">
															EXCLUIR USUÁRIO
													</a>
												</div>
											</td>
										</tr>	
								@endforeach	
								</tbody>														
							</table>

								

							<!-- {!! str_replace('/?', '?', $usuarios->render()) !!} -->

						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-8">
								{!! str_replace('/?', '?', $usuarios->render()) !!}
							</div><!-- /.col-sm-8 -->
							
							<div class="col-sm-4 alinhado-direita-padding text-right">
								<div class="col-sm-7 col-md-8">
									{!! Form::select('ordenar_por', $ordenar_por, $default_ordenar_por, ['class'=>'ordenar-por form-control arrow-preto-amarelo']) !!}
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