<?php 
$header_title = 'Atualizar Logo Do Sistema';
?>
@extends('base_sidebar')



@section('content')

<div class="col-sm-9  col-md-9 main usuario sucesso">
					
						<br />
						
						<h1>
                                                    ATUALIZAR LOGO DO SISTEMA
                                                </h1>
						
						<br />

						<h2 class="novo-usuario-sucesso">SUCESSO!</h2>
						
						
						<p class="col-sm-8 novo-usuario-sucesso-descricao">
                                                    Logo do sistema atualizado com sucesso.
                                                    <br />
						</p>
						
						<div class="row">
							<div class="col-sm-12">
                                                            {!! link_to_action('DashboardController@index','IR PARA O DASHBOARD',array(),['class'=>'btn btn-amarelo btn-medio']) !!}                                
							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->

		</div><!-- /.main -->
@endsection
