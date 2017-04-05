<div class="add">
    <div>
        {!! Form::label('definir_destaque_url_','URL DA IMAGEM', array()) !!}
        {!! Form::text('definir_destaque_url', null, ["placeholder"=>"HTTP://", 'id'=>'definir_destaque_url_']) !!}
    </div>
    <div>
        {!! Form::label('definir_destaque_title_','TÍTULO', array()) !!}
        {!! Form::text('definir_destaque_title', null, ["placeholder"=>"Título de imagem", 'id'=>'definir_destaque_title_']) !!}
    </div>
    <div class="post-modal-visibility-hidden">
        <img src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'/>
    </div>
    <div class="post-modal-visibility-hidden">
        {!! Form::label('definir_destaque_alignment_','ALINHAMENTO', array()) !!}
        <div class='select-cont'>
            {!! Form::select('definir_destaque_alignment', array('direita'=>'Direita','esquerda'=>'Esquerda', 'centro'=>'Centro'), null, ['class'=>'form-control arrow-preto-amarelo', 'id'=>'definir_destaque_alignment_']) !!}
        </div>
    </div>
    <div class="post-modal-visibility-hidden">
        <a onclick="javascript:  document.querySelector('#add #definir_destaque_linkar_para_').value=''" class="btn btn-medio btn-preto">NENHUM</a>
        <a onclick="javascript:  document.querySelector('#add #definir_destaque_linkar_para_').value=document.querySelector('#add #definir_destaque_url_').value;" class="btn btn-medio btn-preto">URL DA IMAGEM</a>
        {!! Form::text('definir_destaque_linkar_para', null, ["placeholder"=>"Url específica", 'id'=>'definir_destaque_linkar_para_']) !!}
    </div>
    <a id="add-img-via-url-to-library-submit" class="btn btn-grande btn-inactive">ADICIONAR À LIVRARIA</a>
    <a id="add-img-via-url-submit" class="btn btn-grande btn-inactive">DEFINIR IMAGEM DE DESTAQUE</a>
</div>