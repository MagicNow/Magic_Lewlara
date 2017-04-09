<?php 
$header_title = 'Todos os Clientes';
?>

<?php
$scripts = array(
    0 => array(
        'src' => URL::to('/').elixir('js/pages/cliente.js')
    ),
); 
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main cliente">
					
						<br />
						

						<h1>TODOS OS CLIENTES</h1>
						<br />
						
						<div class="row">
							<div class="col-sm-8 sobre-linha">
								CLIENTES CADASTRADOS | {{ $clientes->total() }} |
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
						<div class="row">
							<table class="col-sm-12 com-paginacao">
								<thead>
									<td class="col-sm-6">
										CLIENTE
									</td>
									<td class="col-sm-3">
										ADM RESPONSÁVEL
									</td>
									<td class="col-sm-3">
										CLIENTE VISÍVEL
									</td>
								</thead>
								
								@foreach ($clientes as $cliente)
									<tbody>
										<tr>
											<td>
												<div class="row">
													{!! Form::radio('cliente', $cliente->id, null, ['class'=>'abre-editar-cliente']) !!}
													@if ($cliente->logo)
														<img src="{{ URL::to('/upload/cliente').'/'.$cliente->slug.'/'.$cliente->logo }}" class="abre-editar-cliente imagem-cliente">
													@endif
													&nbsp; 
													{{ mb_strtoupper($cliente->name) }}
												</div><!-- /.row -->
												<div class="row box-editar-excluir">
													<br />
													<a href="{{ action('ClienteController@edit',$cliente->slug) }}" class="btn btn-amarelo btn-pequeno align-center ">EDITAR CLIENTE</a>
													&nbsp;
													<a href="#excluir" 
														class="btn btn-preto btn-pequeno align-center" 
														data-href="{{ route('cliente_destroy', $cliente->slug) }}"
														data-header="Excluir Cliente"
														data-body="Deseja excluir definitivamente o cliente <?php echo $cliente->name; ?> ?"
														role="button" 
														data-toggle="modal" 
														data-target="#modal-confirm-delete">
															EXCLUIR CLIENTE
													</a>
												</div><!-- /.box-editar-excluir -->
											</td>
											<td>
												@if (!empty($cliente->user))
													@foreach ($cliente->user as $user)
														{{ mb_strtoupper($user->first_name . ' ' . $user->last_name) }} <br />
													@endforeach
												@else
														{{ mb_strtoupper('NENHUM') }}
												@endif	
											</td>
											<td>
												<div class="col-sm-6">
													{!! Form::select('active', array('1'=>'Sim','0'=>'Não'), $cliente->active, ['class'=>'muda-cliente-visivel form-control arrow-preto-amarelo', 'data-idcliente'=>$cliente->id, 'data-namecliente'=>$cliente->name]) !!}
												</div><!-- /.col-sm-6 -->
											</td>
										</tr>
									</tbody>								
								@endforeach		
														
							</table>

						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-8">
								{!! str_replace('/?', '?', $clientes->render()) !!}
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