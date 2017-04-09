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


						<div class="row">
							<div class="col-sm-7 font-arial-narrow">
								Selecione os usuários <!-- loren ipsum -->

								<br ><br >

								{!! link_to(URL::previous(),'VOLTAR',['class'=>'btn btn-preto btn-medio']) !!}

								&nbsp; &nbsp;

								{!! link_to_action('NewsletterController@novaDisparar','DISPARAR NEWSLETTER', $cliente_default->slug,['class'=>'btn btn-amarelo btn-medio bt-disparar-newsletter']) !!}
							</div><!-- /.col-sm-7 -->
						</div><!-- /.row -->

						<br>

						<div class="row sobre-linha-acima">
							<div class="col-sm-8 sobre-linha">
								<span>TODOS | {{ count($pessoas) }} | &nbsp;</span>
								<span>ADMINISTRADORES | {{ $count_admin }} | &nbsp;</span> 
								<span>CLIENTES | {{ $count_cliente }} | &nbsp;</span> 
								<span>LEW LARA | {{ $count_lewlara }} |</span>
							</div>
							<div class="col-sm-4 text-right form-filtra-usuario">
								<div class="col-sm-7 col-md-8">
									{!! Form::text('buscar_por', NULL, ['class'=>'buscar-por-inline form-control arrow-preto-amarelo sem-padding', 'placeholder' => 'Buscar por nome']) !!}
								</div>
								<button class="aplicar-ordem-inline btn btn-preto btn-meio-medio col-sm-5 col-md-4">APLICAR</button>
							</div>
						</div><!-- /.row -->

						<div class="lista-container">
							<div class="row">
								<div class="form-group">
									{!! Form::label('title','Título Newsletter', ['class'=>'control-label col-sm-2']) !!}
									<div class="col-sm-2">
										{!! Form::text('title', null, ['class'=>'form-control ','placeholder'=>'Título Newsletter']) !!}
									</div><!-- /.col-md-9 -->
								</div><!-- /.form-group -->
								<div class="col-sm-12 font-arial-narrow text-bold anula-padding">
									<div class="col-sm-6 anula-padding titulo">
										{!! Form::checkbox('grupos-check-todos', 1, false, array('id'=>'grupos-check-todos')) !!} GRUPOS
									</div><!-- /.col-sm-12 -->
								</div><!-- /.col-sm-12 -->

								<?php $n = 0; ?>
								@if (count($grupos))								
									@foreach ($grupos as $grupo)
										<div class="col-sm-6 pessoa lista-item">
											<div class="borda lista-item-label">
												{!! Form::checkbox('grupos[]', $grupo->id, array_key_exists($grupo->id, $gruposSelecionados), ['class'=>'grupo-checkbox']) !!}

												{{ mb_strtoupper($grupo->name) }}
											</div><!-- /.top-borda -->								
										</div><!-- /.col-sm-8 -->
									<?php $n++; ?>		
									@endforeach		
								@else
									<div class="col-sm-12">
										Não há grupos cadastrados para o cliente {{ $cliente_default->name }}</td>
									</div><!-- /.col-sm-12 -->
								@endif	
							</div><!-- /.row -->

							<div class="row">
								<div class="col-sm-12 font-arial-narrow text-bold anula-padding">
									<div class="col-sm-6 anula-padding titulo">
										{!! Form::checkbox('pessoas-check-todos', 1, false, array('id'=>'pessoas-check-todos')) !!} PESSOAS
									</div><!-- /.col-sm-12 -->
								</div><!-- /.col-sm-12 -->

								<?php $n = 0; ?>
								@if (count($pessoas))								
									@foreach ($pessoas as $pessoa)
										<div class="col-sm-6 pessoa lista-item">
											<div class="borda lista-item-label">
												{!! Form::checkbox('pessoas[]', $pessoa->id, array_key_exists($pessoa->id, $pessoasSelecionadas), ['class'=>'pessoa-checkbox']) !!}

												@if ($pessoa->photo)
													<img src="{{ URL::to('/upload/usuario').'/'.$pessoa->id.'/'.$pessoa->photo }}" class="abre-editar-cliente imagem-usuario">
												@endif

												{{ mb_strtoupper($pessoa->first_name . ' ' . $pessoa->last_name) }}
											</div><!-- /.top-borda -->								
										</div><!-- /.col-sm-8 -->
									<?php $n++; ?>		
									@endforeach		
								@else
									<div class="col-sm-12">
										Não há pessoas cadastradas para o cliente {{ $cliente_default->name }}</td>
									</div><!-- /.col-sm-12 -->
								@endif	
							</div><!-- /.row -->
						</div>
						<br>
						<br>

						<div class="row">
							{!! link_to(URL::previous(), 'VOLTAR', ['class'=>'btn btn-preto btn-medio']) !!}

							&nbsp; &nbsp;

							{!! link_to_action('NewsletterController@novaDisparar','DISPARAR NEWSLETTER', $cliente_default->slug,['class'=>'btn btn-amarelo btn-medio bt-disparar-newsletter']) !!}
						</div><!-- /.row -->
						
		</div><!-- /.main -->
@endsection