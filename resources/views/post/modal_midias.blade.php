<div id="modal-midias" class='hide aberta'>
    <input type="hidden" id="modal_cliente_id" value="{{ $cliente_default->id }}" />
    <!--sidebar-->
    <div class="sidebar col-sm-12 col-md-2">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#insert" id="insert-tab" role="tab" data-toggle="tab" aria-controls="insert" aria-expanded="true">INSERIR MÍDIA</a>
            </li>
            <li role="presentation">
                <a href="#create" id="create-tab" role="tab" data-toggle="tab" aria-controls="create">CRIAR GALERIA</a>
            </li>
            <li role="presentation">
                <a href="#define" id="define-tab" role="tab" data-toggle="tab" aria-controls="define">DEFINIR IMAGEM DE DESTAQUE</a>
            </li>
            <li role="presentation">
                <a href="#add" id="add-tab" role="tab" data-toggle="tab" aria-controls="add">ADICIONAR UMA URL</a>
            </li>
        </ul>
    </div>
    <div class="main-tab-content tab-content">
        <!----><!----><!----><!----><!----><!----><!----><!---->
        <a id="close-media-modal" href="#">FECHAR</a>
        <!--=============================================================================================================================================-->
        <div id="insert" role="tabpanel"  class="tab-pane fade active in col-sm-12 col-md-10" aria-labelledby="insert-tab">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#upload1" id="upload-tab1" role="tab" data-toggle="tab" aria-controls="upload1" aria-expanded="true">UPLOAD DE ARQUIVOS</a>
                </li>
                <li role="presentation">
                    <a href="#library1" id="library-tab1" role="tab" data-toggle="tab" aria-controls="library1">LIVRARIA</a>
                </li>
                <span class="select-cont">
                    {!! Form::select('filter', array('0'=>'Todos arquivos de mídia','1'=>'Alguns arquivos de mídia'), null, ['class'=>'form-control arrow-preto-amarelo']) !!}
                </span>
            </ul>

            <!----><!----><!----><!----><!----><!----><!----><!---->
            <div class="tab-content">
            <p class="aviso">É necessário que a imagem de Destaque tenha <span>970x520</span> pixels.<br> Dimensões diferentes dessa irão comprometer a qualidade visual do Site.</p>
                <div role="tabpanel" class="tab-pane fade active in" id="upload1" aria-labelledby="upload-tab1">
                    @include('post/modal/insert-upload')
                </div>
                <div role="tabpanel" class="tab-pane fade" id="library1" aria-labelledby="library-tab1">
                    <br>
                    <br>
                    <br>
                    CARREGANDO...
                    <br>
                    <br>
                    <br>
                    {{-- @include('post/modal/insert-library') --}}
                </div>
            </div>
        </div>
        <!--=============================================================================================================================================-->
        <div id="create" role="tabpanel"  class="tab-pane fade col-sm-12 col-md-10" aria-labelledby="create-tab">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#upload2" id="upload-tab2" role="tab" data-toggle="tab" aria-controls="upload2" aria-expanded="true">UPLOAD DE ARQUIVOS</a>
                </li>
                <li role="presentation">
                    <a href="#library2" id="library-tab2" role="tab" data-toggle="tab" aria-controls="library2">LIVRARIA</a>
                </li>
                <span class="select-cont">
                    {!! Form::select('filter', array('0'=>'Todos arquivos de mídia','1'=>'Alguns arquivos de mídia'), null, ['class'=>'form-control arrow-preto-amarelo']) !!}
                </span>
            </ul>
            <!----><!----><!----><!----><!----><!----><!----><!---->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="upload2" aria-labelledby="upload-tab2">
                    @include('post/modal/create-upload')
                </div>
                <div role="tabpanel" class="tab-pane fade" id="library2" aria-labelledby="library-tab2">
                    <br>
                    <br>
                    <br>
                    CARREGANDO...
                    <br>
                    <br>
                    <br>
                </div>
            </div>
        </div>
        <!--=============================================================================================================================================-->
        <div id="define" role="tabpanel"  class="tab-pane fade col-sm-12 col-md-10" aria-labelledby="define-tab">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#upload3" id="upload-tab3" role="tab" data-toggle="tab" aria-controls="upload3" aria-expanded="true">UPLOAD DE ARQUIVOS</a>
                </li>
                <li role="presentation">
                    <a href="#library3" id="library-tab3" role="tab" data-toggle="tab" aria-controls="library3">LIVRARIA</a>
                </li>
                <span class="select-cont">
                    {!! Form::select('filter', array('0'=>'Todos arquivos de mídia','1'=>'Alguns arquivos de mídia'), null, ['class'=>'form-control arrow-preto-amarelo']) !!}
                </span>
            </ul>
            <!----><!----><!----><!----><!----><!----><!----><!---->
            <div class="tab-content">
            <p class="aviso">É necessário que a imagem de Destaque tenha <span>970x520</span> pixels.<br> Dimensões diferentes dessa irão comprometer a qualidade visual do Site.</p>
                <div role="tabpanel" class="tab-pane fade active in" id="upload3" aria-labelledby="upload-tab3">
                    @include('post/modal/define-upload')
                </div>
                <div role="tabpanel" class="tab-pane fade" id="library3" aria-labelledby="library-tab3">
                    <br>
                    <br>
                    <br>
                    CARREGANDO...
                    <br>
                    <br>
                    <br>
                </div>
            </div>
        </div>
        <!--=============================================================================================================================================-->
        <div id="add" role="tabpanel"  class="tab-pane fade col-sm-12 col-md-10" aria-labelledby="add-tab">
            <!----><!----><!----><!----><!----><!----><!----><!---->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="upload" aria-labelledby="upload-tab4">
                    @include('post/modal/add')
                </div>
            </div>
        </div>
    </div>
</div><!-- /#modal-midias -->