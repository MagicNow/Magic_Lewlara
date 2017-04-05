<?php 
$header_title = 'Resetar Senha de Usuários';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main usuario">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif
							RESETAR SENHA DE USUÁRIOS
						</h1>

						<br />
						
						

						<div class="row">
							<div class="col-sm-8 sobre-linha">
								<span>TODOS | {{ $usuarios->total() }} | &nbsp;</span> 
								<span>ADMINISTRADORES | {{ $count_tipo_usuarios['admin'] }} | &nbsp;</span> 
								<span>CLIENTES | {{ $count_tipo_usuarios['cliente'] }} | &nbsp;</span> 
								<span>LEW LARA | {{ $count_tipo_usuarios['lewlara'] }} |</span>
							</div><!-- /.col-sm-8 -->
							
							
						</div><!-- /.row -->
						<div class="row">
							<table class="col-sm-12 com-paginacao">
								<thead>
									<td class="col-sm-3">
										USUÁRIO
									</td>
									<td class="col-sm-4">
										NOME
									</td>									
									<td class="col-sm-2">
										NÍVEL
									</td>
									<td class="col-sm-3">

									</td>
								</thead>
								
								@foreach ($usuarios as $usuario)
									<tbody>
										<tr>
											<td>
												<div class="row">
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
											 	@foreach ($usuario->group as $group)
											 		{{ mb_strtoupper($group->name) }}
											 	@endforeach
											</td>
											<td valign="middle" class="text-right direita-padding">
												<a href="#nova-senha" 
													class="btn btn-preto btn-pequeno align-center" 
													data-href="{{ action('UserController@resetarSenhaUsuariosEnvia', $usuario->id) }}"
													data-header="Enviar Nova Senha"
													data-btcancel="NÃO ENVIAR"
													data-btok="ENVIAR"
													data-body="Deseja enviar nova senha para o usuário <?php echo mb_strtoupper($usuario->username); ?> de nome <?php echo mb_strtoupper($usuario->first_name.' '.$usuario->last_name); ?> ?"
													role="button" 
													data-toggle="modal" 
													data-target="#modal-confirm">
														ENVIAR NOVA SENHA
												</a>
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
						</div><!-- /.row -->
		</div><!-- /.main -->




	

@endsection