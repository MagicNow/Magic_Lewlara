<?php 
$header_title = 'Newsletter Grupo';

$scripts = array(
    0 => array(
        'src' => URL::to('/').elixir('js/pages/newsletter_nova.js')
    )
); 


?>
@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main newsletter pessoas">
			{!! Form::open(['action'=>array('NewsletterController@grupoUpdate',$grupo->id), 'class'=>'form-horizontal', 'method'=>'PUT']) !!}
						<br />
						
						<h1>
							@if (isset($cliente_default))
								<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							@endif
							GERENCIAR GRUPO
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

								{!! link_to(action('NewsletterController@grupos'),'VOLTAR',['class'=>'btn btn-preto btn-medio']) !!}

								&nbsp; &nbsp;
								
								{!! Form::submit('SALVAR GRUPO', ['class'=>'btn btn-amarelo btn-medio']) !!}
							</div><!-- /.col-sm-7 -->
						</div><!-- /.row -->

						<br>
						
					

						<div class="row sobre-linha-acima">
							<div class="col-sm-8 sobre-linha">
								<span>TODOS | {{ count($pessoas) }} | &nbsp;</span>
								<span>ADMINISTRADORES | {{ $count_admin }} | &nbsp;</span> 
								<span>CLIENTES | {{ $count_cliente }} | &nbsp;</span> 
								<span>LEW LARA | {{ $count_lewlara }} |</span>
							</div><!-- /.col-sm-8 -->		
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
										<div class="<?php echo ($n%2) ? 'col-sm-5 col-sm-offset-1' : 'col-sm-6' ?> pessoa">		<div class="borda">
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

						<br>
						<br>

						<div class="row">
							{!! link_to(action('NewsletterController@grupos'),'VOLTAR',['class'=>'btn btn-preto btn-medio']) !!}

							&nbsp; &nbsp;

							{!! Form::submit('SALVAR GRUPO', ['class'=>'btn btn-amarelo btn-medio']) !!}
						</div><!-- /.row -->
			{!! Form::close() !!}			
		</div><!-- /.main -->


@endsection