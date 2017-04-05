<?php 
$header_title = 'Cadastrar Tag';
?>
@extends('base_sidebar')



@section('content')

<div class="col-sm-9  col-md-9 main usuario sucesso">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img class="tag-client-img" src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif	                                                    
                            TAG
                        </h1>
						
						<br />

						<h2 class="novo-usuario-sucesso">SUCESSO!</h2>
						
						
						<p class="col-sm-8 novo-usuario-sucesso-descricao">
                                                    {{ $successMessage }}
                                                    <br />
						</p>
						
						<div class="row">
							<div class="col-sm-12">
								{!! link_to_action('TagController@edit',$callToAction,array(),['class'=>'btn btn-amarelo btn-medio']) !!}
							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

		</div><!-- /.main -->
@endsection
