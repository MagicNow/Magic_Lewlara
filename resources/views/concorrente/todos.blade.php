<?php 
$header_title = 'Concorrentes Cadastrados';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main usuario">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif
							CONCORRENTES CADASTRADOS
						</h1>
						<br />
						
						

						<div class="row">
							<div class="col-sm-8 sobre-linha">
								<span>CONCORRENTES CADASTRADOS | {{ $concorrentes->total() }} | &nbsp;</span> 
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
									<td class="col-sm-6">
										CONCORRENTES
									</td>
									<td class="col-sm-6">
										
									</td>
								</thead>
								
								@foreach ($concorrentes as $concorrente)
									<tbody>
										<tr>
											<td>
												<div class="row">
													{!! Form::radio('concorrente', $concorrente->id, null, ['class'=>'abre-box-child']) !!}
													@if ($concorrente->logo)
														<img src="{{ URL::to('/upload/concorrente').'/'.$concorrente->logo }}" class="abre-box-child imagem-concorrente">
													@endif
													&nbsp; 
													<span class="abre-box-child"> 
														{{ mb_strtoupper($concorrente->name) }}
													</span>
												</div><!-- /.row -->											
											</td>											
											<td class="direita-padding text-right">									
											 	<a href="{{ action('ConcorrenteController@edit',$concorrente->id) }}" class="btn btn-amarelo btn-pequeno align-center ">EDITAR CONCORRENTE</a> 
												&nbsp;
												<a href="#excluir" 
													class="btn btn-preto btn-pequeno align-center" 
													data-href="{{ route('cliente_concorrente_destroy', $concorrente->id) }}"
													data-header="Excluir Concorrente"
													data-body="Deseja excluir definitivamente o concorrente <?php echo mb_strtoupper($concorrente->name); ?> ?"
													role="button" 
													data-toggle="modal" 
													data-target="#modal-confirm-delete">
														EXCLUIR CONCORRENTE
												</a>
											</td>
										</tr>	
										<tr >
											<td colspan="2" class="box-child"> 
												<div class="box-child col-sm-offset-1 direita-padding">
													<?php 
													$count = 1;
													?>
													@foreach ($concorrente->link as $link)
														Link {{ $count }} &nbsp; | &nbsp; <a href="{{ $link }}" target="_blank">{{ $link }}</a> <br />
														<?php $count++; ?>
													@endforeach
												</div>
											</td>
										</tr>	
									</tbody>						
								@endforeach		
														
							</table>

								

						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-8">
								{!! str_replace('/?', '?', $concorrentes->render()) !!}
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