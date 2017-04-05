@include('header')

<div class="container-fluid newsletter-gerada">

<div class="col-sm-10 col-sm-offset-1">

	<div class="row">		
		<div class="col-sm-12">
			<br />
			
			<h1>
				<div class="box-img">
					<img src="{{ URL::to('/upload/cliente').'/'.$cliente_default->slug.'/'.$cliente_default->logo }}"></img>
				</div><!-- /.box-img -->
				<span>MONITORAMENTO DA CONCORRÊNCIA</span>
			</h1>
			<span class="sub-infos">{{ mb_strtoupper($newsletter->cliente->name) }} | {{ mb_strtoupper($newsletter->mesNome.' '.$newsletter->ano) }}</span>
			<br />
		</div><!-- /.col-sm-12 -->
	</div><!-- /.row -->

	
	<?php $x = 0; $novalinha = 0; $totalposts = count($newsletter->post()->get()); ?>
	@foreach ($newsletter->post()->get() as $post)		
	<?php 
	if($x!=0) {$novalinha++;}
	if($x%2 || $x==0){ ?>
	<div class="row">
	<?php } ?>	

			<div class="col-sm-<?php if($x==0){ echo '12'; } else { echo '6'; } ?>  box-post">
				@if (count($post->midia))
					<?php $midia = $post->midia->first(); ?>
					<div class="box-img-post-destaque">
						<img class="img-post-destaque" src="{{ URL::to('/').'/'.$midia->imagem }}">
					</div><!-- /.box-img-post-destaque -->
				@endif

				<h2>{{ mb_strtoupper($post->titulo) }}</h2>
				<p>{{ _resumo(_limpaHtmlJs($post->desc)) }}</p><br>
				<!-- <a href="#post-{{$post->id}}" class="btn btn-amarelo btn-pequeno btn-auto-width align-center"> &nbsp; VER POSTS &nbsp;&nbsp; </a> -->

					<table border="0" cellpadding="10" cellspacing="0" width="100">
				        <tr>
				            <td align="center" style="background: #ffd500; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px; " valign="top">
				                <a href="#post-{{$post->id}}" class="btn btn-amarelo btn-pequeno btn-auto-width align-center" style="color: #000000;  text-transform: uppercase; "> &nbsp; VER POSTS &nbsp;&nbsp; </a>
				            </td>
				        </tr>
				    </table>
			</div><!-- /.col-sm-x box-post -->
	
	<?php if($x==0 || $novalinha == 2 || ($x+1) == $totalposts){ if($x!=0) {$novalinha=0;} ?>	
	</div><!-- /.row -->
	<?php } ?>

	<?php $x++; ?>
	@endforeach

</div><!-- /.col-sm-11 -->

</div><!-- /.container-fluid -->




<!-- FOOTER FOOTER  -->
<div class="row">
	<div class="col-sm-12">
		<footer class="footer rodape">
		    <!-- <span class="col-sm-9">&nbsp; &nbsp; COPYRIGHT LEW LARA {{ date('Y') }}  //  TODOS OS DIREITOS RESERVADOS</span>	
		    <span id="rodape-link-lewlara" class="col-sm-3 text-right">WWW.LEWLARA.COM.BR</span>	 -->

		    <br />
		    <div id="rodape-quadrado-preto" class="text-right">
		            <img  class="text-right" src="{{ URL::to('/').'/img/logo.png'}}"/>
		    </div>

		</footer>
	</div><!-- /.col-sm-12 -->
</div><!-- /.row -->

</body>
</html>