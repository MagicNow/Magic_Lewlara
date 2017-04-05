<?php 
$header_title = 'Disparar Notificação';
?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main usuario sucesso">
					
						<br />
						
						<h1>
							DISPARAR NOTIFICAÇÃO
						</h1>
						
						<br>

						<h2 class="novo-usuario-sucesso">SUCESSO!</h2>
						
						
						<p class="col-sm-8 novo-usuario-sucesso-descricao">
							Notificação disparada com sucesso!  <br />
						</p>
						
						<div class="row">
							<div class="col-sm-12">
								{!! link_to_action('NotificationController@dispararNotificacao','VOLTAR',array(),['class'=>'btn btn-amarelo btn-medio']) !!}
							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

		</div><!-- /.main -->
@endsection