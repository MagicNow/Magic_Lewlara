<?php 
$header_title = 'Notificações';

?>
@extends('base_sidebar')


@section('content')

		<div class="col-sm-9  col-md-9 main notificacoes">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif
							NOTIFICAÇÕES
						</h1>
						<br />
						
						

						<div class="row">
							<div class="col-sm-8">
								{!! str_replace('/?', '?', $notifications_paginate->render()) !!}
							</div><!-- /.col-sm-8 -->														
						</div><!-- /.row -->

						<div class="row">
							<?php $data = ''; ?>
							<table class="col-sm-12 com-paginacao-superior com-paginacao-inferior">				
						@foreach ($notifications as $nt)
								
								<?php
									if($data != $nt['data']){ 
										$data = $nt['data']; 
									?>
									<tbody class="sem-borda">
										<tr>
											<td class="text-bold td-subtitulo">
												{{ $nt['data'] }}
											</td>
										</tr>
									</tbody>
									<?php
									}
								?>

								<tbody>
									<tr>
										<td class="notificacao-item">
											<i class="icon-notificacao {{ $nt['icon'] }}"></i> {!! $nt['desc'] !!}
										</td>
									</tr>
								</tbody>	
							@endforeach								
							</table>
						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-8">
								{!! str_replace('/?', '?', $notifications_paginate->render()) !!}
							</div><!-- /.col-sm-8 -->														
						</div><!-- /.row -->
		</div><!-- /.main -->


@endsection