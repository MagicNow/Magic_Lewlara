<?php 
$header_title = 'Nova Newsletter';

$css_antes = array(
    0 => array(
        'href' => URL::to('/libs').'/jscrollpane/jquery.jscrollpane.css'
    )
); 

$scripts = array(
	0 => array(
	    'src' => URL::to('/libs').('/jscrollpane/jquery.jscrollpane.min.js')
	),
    1 => array(
        'src' => URL::to('/').elixir('js/pages/newsletter_lista.js')
    )
); 

?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main newsletter">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif
							TODAS NEWSLETTERS
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
								<span>TODOS | {{ $newsletters->total() }} | &nbsp;</span>
							</div><!-- /.col-sm-8 -->		
						</div><!-- /.row -->
						
						<div class="row form-inline sobre-linha-acima">
							<div class="col-sm-12 form-group">
								
								{!! Form::select('select_acao', array(''=>'Ação','excluir'=>'Excluir'), null, ['class'=>'select-acao form-control arrow-preto-amarelo']) !!}
								<button class="aplicar-select-acao-newsletter btn btn-preto btn-meio-medio btn-estreito">APLICAR</button>
								&nbsp;
								{!! Form::select('select_data', [null=>'Todas as datas'] + $select_datas, session('filtro_data_newsletter_lista',null), ['class'=>'form-control arrow-preto-amarelo select-data']) !!}

								<button class="filtrar-newsletter btn btn-preto btn-meio-medio btn-estreito">FILTRAR</button>

							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-8">
								{!! str_replace('/?', '?', $newsletters->render()) !!}
							</div><!-- /.col-sm-8 -->								
						</div><!-- /.row -->


						<div class="row">
							<table class="col-sm-12 com-paginacao-superior com-paginacao-inferior">
								<thead>
									<td class="col-sm-8">
										{!! Form::checkbox('newsletter-check-todos', 1, false, array('id'=>'newsletter-check-todos')) !!} TÍTULO
									</td>
									<td>
										AUTOR
									</td>
									<td>
										DATA
									</td>
								</thead>
								@if (count($newsletters))								
									@foreach ($newsletters as $newsletter)
										<tbody>
											<tr>
												<td>
													<div class="row">
														<div class="col-sm-8">
															{!! Form::checkbox('newsletters[]', $newsletter->id, false, ['class'=>'newsletter-checkbox']) !!}
															
															<span> 															
																{{ mb_strtoupper($newsletter->assunto) }} <br>
																&nbsp; &nbsp; - POSTS SELECIONADOS: {{ count($newsletter->post) }} <br>
																&nbsp; &nbsp; - USUÁRIOS SELECIONADOS: {{ count($newsletter->pessoa) }} <br>
															</span>
														</div><!-- /.col-sm-10 -->
														<div class="col-sm-3 text-right">
															<a class="btn-ver-posts btn btn-amarelo btn-pequeno btn-auto-width align-center mb-10" data-newsletterid='{{$newsletter->id}}'> &nbsp; VER POSTS &nbsp;&nbsp; </a><br>

															<a class="btn-ver-pessoas btn btn-preto btn-pequeno btn-auto-width align-center " data-newsletterid='{{$newsletter->id}}'>VER PESSOAS</a>
														</div><!-- /.col-sm-2 -->

													</div><!-- /.row -->
													<br>		
													<div class="row">
														<div class="col-sm-11">
															{!! Form::text('link_direto', URL::action('NewsletterController@show',array($newsletter->id)), ['class'=>'form-control seleciona-no-click']) !!}
														</div><!-- /.col-sm-11 -->
													</div><!-- /.row -->									
												</td>				
												<td>
													{{ mb_strtoupper($newsletter->autor()->first()->first_name . ' ' . $newsletter->autor()->first()->last_name) }}
												</td>																		
												<td class="direita-padding">									
												 	{{ date('d/m/Y H:i',strtotime($newsletter->created_at)).'H' }}
												</td>
											</tr>	
										</tbody>						
									@endforeach		
								@else
									<tbody>
										<tr>
											<td colspan="3">Não há newsletters</td>
										</tr>
									</tbody>
								@endif						
							</table>

						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-8">
								{!! str_replace('/?', '?', $newsletters->render()) !!}
							</div><!-- /.col-sm-8 -->	
						</div><!-- /.row -->


		</div><!-- /.main -->

		@include('newsletter.modal.modal_estrutura')
@endsection