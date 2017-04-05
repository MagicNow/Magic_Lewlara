<?php 
$header_title = 'Nova Newsletter';

$scripts = array(
    0 => array(
        'src' => URL::to('/').elixir('js/pages/newsletter_nova.js')
    )
); 


?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main newsletter pessoas">
					
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif
							NOVA NEWSLETTER
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

						<h2 class="h2-acao-sucesso">SUCESSO!</h2>

						<div class="row">
							<div class="col-sm-7 ">
								Disparada com sucesso <!-- loren ipsum -->						
							</div><!-- /.col-sm-7 -->
						</div><!-- /.row -->

						<br>
										
						<div class="row mb-10">
							<div class="col-sm-6 font-arial-narrow text-bold ">
								POSTS SELECIONADOS
							</div><!-- /.col-sm-6 -->
							
							<div class="col-sm-5 col-sm-offset-1 font-arial-narrow text-bold ">
								PESSOAS SELECIONADAS
							</div><!-- /.col-sm-5 -->
						</div><!-- /.row -->

						<div class="row">
							<div class="col-sm-6">
								@foreach ($newsletter->post()->get() as $post)
									<div class="col-sm-12 posts-selecionados">
										{{ _resumo(_limpaHtmlJs($post->desc)) }}
									</div><!-- /.col-sm-12 -->							
								@endforeach
							</div><!-- /.col-sm-6 -->

							<div class="col-sm-5 col-sm-offset-1">
								@foreach ($newsletter->pessoa as $pessoa)									
									<div class="col-sm-12 pessoas-selecionadas">
										<div class="borda">
											@if ($pessoa->photo)
												<img src="{{ URL::to('/upload/usuario').'/'.$pessoa->id.'/'.$pessoa->photo }}" class="abre-editar-cliente imagem-usuario">
											@endif
															
											{{ mb_strtoupper($pessoa->first_name . ' ' . $pessoa->last_name) }}
										</div><!-- /.borda -->
									</div><!-- /.col-sm-12 -->									
								@endforeach
							</div><!-- /.col-sm-5 -->
						</div><!-- /.row -->

						<br >

						<div class="row">
							<div class="col-sm-7">
								{!! Form::text('link_direto', URL::action('NewsletterController@show',array($newsletter->id)), ['class'=>'form-control seleciona-no-click']) !!}
							</div><!-- /.col-sm-7 -->
						</div><!-- /.row -->

						<br >

						<div class="row">
							<div class="col-sm-4 col-md-3 ">
								{!! link_to_action('NewsletterController@novaPosts','NOVA NEWSLETTER',array(),['class'=>'btn btn-amarelo btn-grande font-size-16 largura-completa']) !!}
								<br>
								<br>
								{!! link_to_action('NewsletterController@lista','TODAS NEWSLETTER',array(),['class'=>'btn btn-preto btn-medio largura-completa']) !!}
							</div><!-- /.col-sm-12 -->
						</div><!-- /.row -->


					
		</div><!-- /.main -->


@endsection