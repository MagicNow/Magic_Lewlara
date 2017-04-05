<?php
//infelizmente temos este pedaço de lógica na view
//em que testamos o último link do loop para que coloquemos ao lado deste
//o botão de adicionar novos campos
$ultimo = count($usuario->link_pessoal) - 1;
$link_pessoal_count = 0;
?>
<div class="edit-block col-sm-9 form-group group-link-pessoal">
    @foreach ($usuario->link_pessoal as $link_pessoal)
    <div class="col-sm-12">
        <div class="col-sm-5 pt7">
            {!! Form::text('link_pessoal['.$link_pessoal_count.']', $usuario->link_pessoal[$link_pessoal_count], ['class'=>'form-control']) !!}
        </div><!-- /.col-md-9 -->


        @if ($link_pessoal_count == $ultimo)
       <div class="col-md-3">
            <a id="perfil_adicionar_link_pessoal" class="btn btn-cinza btn-pequeno btn-lateral-form">ADICIONAR NOVO LINK</a>
        </div><!-- /.col-md-3 -->
        <?php //abaixo, varíavel global que cuida da casa de array que campos dinâmicos de link terão ?>
        <script type="text/javascript"> var link_pessoal_count =<?php echo $link_pessoal_count; ?>;</script>
        @endif

        <?php $link_pessoal_count++; ?>
    </div>
    @endforeach
    <div class='col-sm-12'>
        <div class="col-sm-3">
            <a id="save-personal-links" class="save-edit-block btn btn-cinza btn-pequeno btn-file btn-lateral-form">
                SALVAR
            </a>
        </div>
        <div class="col-sm-3">
            <a id="cancel-links" class="cancel-edit-block btn btn-cinza btn-pequeno btn-file btn-lateral-form">
                CANCELAR
            </a>
        </div>
    </div>
</div><!-- /.form-group -->