<?php 
$header_title = 'Editar Post';

$css_antes = array(
    0 => array(
        'href' => URL::to('/libs').'/jscrollpane/jquery.jscrollpane.css'
    ),
    1 => array(
    	'href' => URL::to('/libs').'/redactor/redactor.css'
    )
); 
?>


<?php
$scripts = array(
	0 => array(
		'src' => URL::to('/libs').'/redactor/redactor.js'
	),
    1 => array(
        'src' => URL::to('/libs').'/redactor/plugins/video.js'
    ),
    2 => array(
        'src' => URL::to('/libs').'/redactor/plugins/fontsize.js'
    ),
    3 => array(
        'src' => URL::to('/libs').'/redactor/plugins/alignment.js'
    ),
    4 => array(
        'src' => URL::to('/libs').'/redactor/plugins/fontcolor.js'
    ),
    5 => array(
    	'src' => URL::to('/libs').'/dropzone/dropzone.js',
    ),
    6 => array(
        'src' => URL::to('/libs').('/jscrollpane/jquery.jscrollpane.min.js')
    ),
    7 => array(
        'src' => URL::to('/libs').('/lightslider/js/lightslider.js')
    ),
    8 => array(
        'src' => URL::to('/').elixir('js/pages/post.js')
    )
); 
?>


@extends('base_sidebar')



@section('content')

		<div class="col-sm-9  col-md-9 main post">
					
						<br />						

						<h1>
							<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
							EDITAR POST
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

						{!! Form::model($post, [ 'method' => 'PUT', 'action' => ['PostController@update', $post->id], 'class' => 'form-horizontal post-form', 'files' => true ]) !!}
							{!! Form::hidden('cliente_id', $cliente_default->id, array('id' => 'clienteId')) !!}
							{!! Form::hidden('post_id', $post->id) !!}
							<!-- ESQUERDA -->
							<div class="col-sm-8">
								<div class="col-sm-11">				
								
									<div class="form-group">						
										{!! Form::text('titulo', null, ['class'=>'form-control','placeholder'=>'Adicione o título aqui']) !!}									
									</div><!-- /.form-group -->
									
									<div class="form-group">	
										<div class="row">
											<div class="col-sm-12">
												<div class="row">
													<div class="col-sm-12">
														<a class="bg-textarea-editavel-toolbar bt-adicionar-midias">Adicionar Mídias</a>
														 &nbsp; 
														<a class="bg-textarea-editavel-toolbar bt-album-fotos">Adicionar Álbum de Fotos</a>
													</div><!-- /.col-sm-12 -->
												</div><!-- /.row -->
												<div class="row">
													<div class="col-sm-12 sem-padding toolbar-to-drop">
														<div class="col-sm-5 col-md-3 sem-padding">
															{!! Form::select('bt-fontSize', array(''=>'Fonte','s10'=>'Fonte | 10','s11'=>'Fonte | 11','s12'=>'Fonte | 12','s13'=>'Fonte | 13','s14'=>'Fonte | 14','s15'=>'Fonte | 15','s16'=>'Fonte | 16','s17'=>'Fonte | 17','s18'=>'Fonte | 18','s19'=>'Fonte | 19','s20'=>'Fonte | 20','s25'=>'Fonte | 25','s30'=>'Fonte | 30','remove'=>'Tamanho Padrão'), null, ['class'=>'bt-fontSize form-control arrow-preto-amarelo sem-padding']) !!}  
														</div>  
														&nbsp; 
														<a class="bg-textarea-editavel-toolbar bt-bold">B</a><a class="bg-textarea-editavel-toolbar bt-italic">I</a><a class="bg-textarea-editavel-toolbar bt-underline">U</a><a class="bg-textarea-editavel-toolbar bt-strikeThrough">S</a><a class="bg-textarea-editavel-toolbar bt-justifyLeft">JL</a><a class="bg-textarea-editavel-toolbar bt-justifyCenter">JC</a><a class="bg-textarea-editavel-toolbar bt-justifyRight">JR</a><a class="bg-textarea-editavel-toolbar bt-fontColor">Font Color</a><a class="bg-textarea-editavel-toolbar bt-hiperlink">Hiperlink</a>
													</div><!-- /.col-sm-12 -->
												</div><!-- /.row -->
											</div><!-- /.col-sm-12 -->
										</div><!-- /.row -->	
										<br>				
										<div class="row">
											<div class="col-sm-12 sem-padidng">
												{!! Form::textarea('desc', null, ['class'=>'form-control editavel','placeholder'=>'descricao']) !!}	
											</div><!-- /.col-sm-12 -->
										</div><!-- /.row -->
									</div><!-- /.form-group -->								
																		
								</div><!-- /.col-sm-11  -->
							</div><!-- /.col-sm-8 -->
							<!-- FIM ESQUERDA -->
							<!-- DIREITA -->
							<aside class="col-sm-4 menu-direita direita-padding">
								<div class="menu-direita-bloco publicacao"> 
									<span class="titulo">PUBLICAÇÃO</span>

									{!! Form::submit('SALVAR RASCUNHO',['name'=>'rascunho','class'=>'btn btn-amarelo btn-medio align-center largura-completa']) !!}
									<br>
									{!! Form::submit('PREVIEW',['name'=>'preview','class'=>'btn btn-amarelo btn-medio align-center largura-completa']) !!}
									<br><br>									
									<i class="status"></i> STATUS | {{ strtoupper($post->StatusName) }}    <br >

									<div id="div_publicar_em">
										<i class="publicar-em"></i>&nbsp;<span id="publicar-texto">PUBLICAR EM</span>
										<div class="div-publicar-em-child">
											{!! Form::hidden('publicar-em-obrigatorio',0,['id'=>'publicar-em-obrigatorio']) !!}
											<br><br>
											DATA &nbsp; &nbsp; {!! Form::text('dia', date('d',strtotime($post->publicar_em)), ['class'=>'form-control data','placeholder'=>'Dia']) !!} &nbsp; {!! Form::select('mes', array('1'=>'Jan','2'=>'Fev','3'=>'Mar','4'=>'Abr','5'=>'Mai','6'=>'Jun','7'=>'Jul','8'=>'Ago','9'=>'Set','10'=>'Out','11'=>'Nov','12'=>'Dez'), date('m',strtotime($post->publicar_em)), ['class'=>'form-control arrow-preto-amarelo data mes']) !!} &nbsp; {!! Form::text('ano',date('Y',strtotime($post->publicar_em)), ['class'=>'form-control data','placeholder'=>'Ano']) !!}
											<br>
											HORA &nbsp; &nbsp;{!! Form::text('hora',date('H',strtotime($post->publicar_em)), ['class'=>'form-control data','placeholder'=>'Hora']) !!} : {!! Form::text('minuto',date('i',strtotime($post->publicar_em)), ['class'=>'form-control data','placeholder'=>'Min']) !!}
										</div><!-- /.child-box -->
									</div><!-- /#div_publicar_em -->

									<br><br>

									<a href="#excluir" 
										class="btn btn-cinza btn-extra-pequeno align-center" 
										data-href="{{ route('post_destroy', $post->id) }}"
										data-header="Excluir Post"
										data-body="Deseja excluir definitivamente o post <?php echo $post->titulo; ?> ?"
										role="button" 
										data-toggle="modal" 
										data-target="#modal-confirm-delete"> &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; EXCLUIR &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; </a>
									<br>									
									{!! Form::submit('PUBLICAR POST', ['class'=>'btn btn-amarelo btn-medio align-center largura-completa text-bold btn-publicar']) !!}
								</div>	
								<div class="menu-direita-bloco"> 
									<span class="titulo">TAGS</span>
									{!! Form::select('tags[]', $select_tags, $post->TagList, ['class'=>'select2multiple form-control', 'multiple']) !!}
								</div>	
								<div class="menu-direita-bloco"> 
									<span class="titulo">CATEGORIA E SUBCATEGORIA</span>
									
									<?php /* <div class="categoria-grupo">
										<br>	
										{!! Form::select('categoria_id[]', $select_categorias, null, ['class'=>'form-control arrow-preto-amarelo onChangeAlteraSubcategoria']) !!}
										<br>
										{!! Form::select('subcategoria_id[]', array(''=>'Selecione a subcategoria'), null, ['class'=>'form-control arrow-preto-amarelo selectSubcategorias']) !!}
										<br>
									</div>/.categoria-grupo */ ?>

									{!! $categorias_subcategorias_html !!}



									<div id="categoria-grupo-bt">
										<br>
										<a id="btn-adicionar-categoria" class="btn btn-cinza btn-extra-pequeno align-center largura-completa">ADICIONAR CATEGORIA</a>
									</div><!-- /.categoria-grupo -->
																										
								</div>	
								<div class="menu-direita-bloco tipos-de-acao"> 
									<span class="titulo">TIPOS DE AÇÃO</span>
									@foreach ($checkbox_tiposAcao as $tpacaoid => $tpacaoname)	
										{!! Form::checkbox('tipos_acao[]', $tpacaoid, $post->tiposAcao->contains($tpacaoid)) !!}  {{ $tpacaoname }} <br />
									@endforeach									
								</div>	
								<div class="menu-direita-bloco"> 
									<span class="titulo">CONCORRENTE</span>
									{!! Form::select('concorrente', array(''=>'SEM CONCORRENTE')+$select_concorrentes, @$post->concorrente->first()->id, ['class'=>'form-control']) !!}						
								</div>	
                                <div class="menu-direita-bloco"> 
                                	<span class="titulo">IMAGEM DE DESTAQUE</span>
                                	<?php $src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; ?>
                                	@if (old('definir_destaque_url'))
                                		<?php $src = old('definir_destaque_url') ?>
                                	@elseif (old('definir_destaque_imagem'))
                                		<?php $src = URL::to('/').'/'.old('definir_destaque_imagem') ?>
                                	@elseif ($post->midia->first())
                                		<?php $src = URL::to('/').'/'.$post->midia->first()->imagem; ?>
                                	@endif
                                		<img src="{{ $src }}" class="imagem-destaque largura-completa">

                                    <a id="define-destaque" class="btn btn-amarelo btn-grande largura-completa">DEFINIR IMAGEM DE DESTAQUE</a>
									
                                    {!! Form::hidden('definir_destaque_flag_if_from_url', 0) !!}
                                   	{!! Form::hidden('definir_destaque_url', null) !!}
                                   	{!! Form::hidden('definir_destaque_imagem', null) !!}
                                   	{!! Form::hidden('definir_destaque_title', null) !!}
                                   	{!! Form::hidden('definir_destaque_desc', null) !!}
                                   	{!! Form::hidden('definir_destaque_linkar_para', null) !!}
                                   	{!! Form::hidden('definir_destaque_alignment', null) !!}
								</div>	
								<div class="menu-direita-bloco destaque-home"> 
									<span class="titulo">DESTAQUE ROTATIVO NA HOME</span>
									{!! Form::checkbox('destaque', 1) !!} Mostrar post no destaque
								</div>	
							</aside>
							<!-- FIM DIREITA -->
						{!! Form::close() !!}
		</div><!-- /.main -->

		@include('post.modal_midias')

		<script type="text/javascript">
			var categorias_select = <?php echo json_encode($select_categorias); ?>;
		</script>

		@if(@$open_preview)
			<script type="text/javascript">
				window.location.href = "<?php echo action('blog\HomeBlogController@click_interna', array($post->cliente->slug, $post->id, 'preview')) ?>";
			</script>
		@endif		

@endsection
