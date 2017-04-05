<div class="media-library">
    <!--stylized scroll-->
    <div class="gallery_scroll col-sm-12 col-md-6">

    	<?php
    	$folder = 'upload/posts/'.$cliente_default->id.'/';
    	$filetype = '*.*';
    	$files = glob($folder.$filetype);
    	$count = count($files);
    	 
    	$sortedArray = array();
    	for ($i = 0; $i < $count; $i++) {
    	    $sortedArray[date ('YmdHis', filemtime($files[$i]))] = $files[$i];
    	}
    	 
    	krsort($sortedArray);
        
    	foreach ($sortedArray as &$filename) {
    	    ?>
    	    <div class="col-sm-4 lista-imagens">
                <div class="selected-frame hide">
                    <img src="<?php echo URL::to('/').'/img/selected.jpg' ?>" />
                </div>
    	    	<div class="lista-cada-imagem" data-data="<?php echo date ('d/m/Y à\s H:i', filemtime($filename)); ?>" data-filesize="<?php echo _human_filesize(filesize($filename));?>" data-imageurl="<?php echo URL::to('/').'/'.$filename; ?>" data-imageuri="<?php echo $filename; ?>" style="background-image: url(<?php echo URL::to('/').'/'.$filename; ?>);">
    	    		
    	    	</div><!-- /.lista-cada-imagem -->
    	    </div>
    	    <?php
    	}
    	?>
    </div>
    <div class="gallery_info col-sm-12 col-md-6">
        <h5>DETALHES DO ANEXO</h5>
        <img class="detail" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"/>
        <div>
            <ul>
                <li class="detailName">- Nome da Imagem</li>
                <li class="detailTime">- Data de Cadastro</li>
                <li class="detailSize">- Tamanho</li>
                <li class="detailDimension">- <span class="detailWidth">0</span> x <span class="detailHeight">0</span> pixels</li>
            </ul>
        </div>
        <div class="row col-sm-12">
            <div>
                {!! Form::label('','URL DA IMAGEM', array()) !!}
                {!! Form::text('url_imagem', null, ['disabled', 'class'=>'library_url_imagem']) !!}
                {!! Form::hidden('uri_imagem',null, ['class'=>'library_uri_imagem']) !!}
            </div>
            <div>
                {!! Form::label('','TÍTULO', array()) !!}
                {!! Form::text('titulo', null, ["placeholder"=>"Título da imagem", 'class'=>'library_titulo']) !!}
            </div>
            <div>
                {!! Form::label('','DESCRIÇÃO', array()) !!}
                {!! Form::textarea('img_desc', null, ["placeholder"=>"Descrição da imagem", 'class'=>'library_img_desc']) !!}
            </div>
<!--            <div>
                {!! Form::label('','ALINHAMENTO', array()) !!}
                <span class='select-cont'>
                    {!! Form::select('alignment', array('alinha-direita'=>'Direita','alinha-esquerda'=>'Esquerda', 'alinha-centro'=>'Centro'), null, ['class'=>'arrow-preto-amarelo', 'class'=>'library_alignment']) !!}
                </span>
            </div>
            <div>
                {!! Form::label('','LINKAR PARA', array()) !!}

                <a onclick="javascript: document.querySelector('#library1 .library_url_espec').value='';" class="btn btn-medio btn-preto">NENHUM</a>

                <a onclick="javascript:  document.querySelector('#library1 .library_url_espec').value=document.querySelector('#library1 .library_url_imagem').value;"  class="btn btn-medio btn-preto">URL DA IMAGEM</a>

                <br>

                {!! Form::text('url_espec', null, ["placeholder"=>"Url específica",'class'=>'library_url_espec']) !!}
            </div>-->
        </div>
        <a id="definir_destaque_library3" class="btn btn-grande btn-amarelo inserir">DEFINIR IMAGEM DE DESTAQUE</a>
    </div>

    <div class="limp-selec">
        <p> <span class='libraryselected'>0</span> | Imagens selecionadas </p>
        <a class="limpaselect btn btn-grande btn-preto">LIMPAR SELEÇÃO</a>
    </div>

</div>