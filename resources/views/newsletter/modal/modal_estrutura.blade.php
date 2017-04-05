<div id="modal-ver-posts-pessoas" class='hide aberta'>
    <input type="hidden" id="modal_cliente_id" value="{{ $cliente_default->id }}" />
    <!--sidebar-->
    
    <div class="main-tab-content tab-content">
        <!----><!----><!----><!----><!----><!----><!----><!---->
        <a id="close-modal-ver-posts-pessoas">FECHAR</a>
        <!--=============================================================================================================================================-->
        <div id="ver-posts-pessoas-tab" role="tabpanel"  class="tab-pane fade active in col-sm-12 col-md-12" aria-labelledby="ver-posts-pessoas-tab">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tabcontent-ver-pessoas" id="tab-ver-pessoas" role="tab" data-toggle="tab" aria-controls="tabcontent-ver-pessoas" aria-expanded="true">PESSOAS</a>
                </li>
                <li role="presentation">
                    <a href="#tabcontent-ver-posts" id="tab-ver-posts" role="tab" data-toggle="tab" aria-controls="tabcontent-ver-posts">POSTS</a>
                </li>               
            </ul>

            <!----><!----><!----><!----><!----><!----><!----><!---->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="tabcontent-ver-pessoas" aria-labelledby="tab-ver-pessoas">
                    <div class="jscroll col-sm-12">
                        <br>
                        <br>
                        <br>
                        CARREGANDO PESSOAS...
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tabcontent-ver-posts" aria-labelledby="tab-ver-posts">
                    <div class="jscroll col-sm-12">
                        <br>
                        <br>
                        <br>
                        CARREGANDO POSTS...
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        
    </div> <!-- tab content -->
</div><!-- /#modal-midias -->