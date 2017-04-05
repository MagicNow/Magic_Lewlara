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
    	    	<div class="lista-cada-imagem" data-data="<?php echo date ('d/m/Y à\s H:i', filemtime($filename)); ?>" data-filesize="<?php echo _human_filesize(filesize($filename));?>" data-imageurl="<?php echo URL::to('/').'/'.$filename; ?>" style="background-image: url(<?php echo URL::to('/').'/'.$filename; ?>);">
    	    		
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
                {!! Form::label('url_imagem','URL DA IMAGEM', array()) !!}
                {!! Form::text('url_imagem', null, ['disabled']) !!}
            </div>
            <div>
                {!! Form::label('titulo','TÍTULO', array()) !!}
                {!! Form::text('titulo', null, ["placeholder"=>"Título da imagem"]) !!}
            </div>
            <div>
                {!! Form::label('img_desc','DESCRIÇÃO', array()) !!}
                {!! Form::textarea('img_desc', null, ["placeholder"=>"Descrição da imagem"]) !!}
            </div>
            <!--<div>
                {!! Form::label('alignment','ALINHAMENTO', array()) !!}
                <span class='select-cont'>
                    {!! Form::select('alignment', array('direita'=>'Direita','esquerda'=>'Esquerda', 'centro'=>'Centro'), null, ['class'=>'arrow-preto-amarelo']) !!}
                </span>
            </div>-->
            <div>
                {!! Form::label('url_espec','LINKAR PARA', array()) !!}
                <a href="#" class="btn btn-medio btn-preto">NENHUM</a>
                <a href="#" class="btn btn-medio btn-preto">URL DA IMAGEM</a>
                {!! Form::text('url_espec', null, ["placeholder"=>"Url específica",'class'=>'library_url_espec']) !!}
            </div>
        </div>
        <a id="criar_galeria" class="createlibrary btn btn-grande btn-amarelo">CRIAR GALERIA NO POST</a>
    </div>

    <div class="limp-selec">
        <p> <span class='libraryselected'>0</span> | Imagens selecionadas </p>
        <a class="limpaselect btn btn-grande btn-preto">LIMPAR SELEÇÃO</a>
    </div>
</div>