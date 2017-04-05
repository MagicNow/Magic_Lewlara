<footer class="footer rodape">

    	

    <br />    
    
    @if (Route::currentRouteAction() == 'App\Http\Controllers\NewsletterController@show' || Route::currentRouteAction() == 'App\Http\Controllers\NewsletterController@novaDisparar')
        <div id="rodape-quadrado-preto" class="text-right news">        
                <img id="rodape-quadrado-preto-img" src="{{ URL::to('/').'/img/logo.png'}}"/>               
        </div>
    @else 
        <div id="rodape-quadrado-preto">
            <div id="rodape-copyright" class="col-sm-9">
                COPYRIGHT LEW’LARA {{ date('Y') }} &nbsp;//&nbsp; TODOS OS DIREITOS RESERVADOS
                <img src="{{asset('img/lewlara-icone-amarelo.png')}}"> 
            </div>   
            <a id="rodape-link-lewlara" class="col-sm-3 text-right" href="http://www.lewlara.com.br" target="_blank">WWW.LEWLARA.COM.BR</a>

        </div>
    @endif 

</footer>
<!-- Scripts -->
<?php
$paramaters = Route::current()->parameters();
$parameterNames = Route::current()->parameterNames();

if(isset($paramaters['post_id'])){ unset($paramaters['post_id']); } // se tiver parametro post_id, REMOVE

$urlParamsArray = '';
foreach ($paramaters as $key => $value) { $urlParamsArray .= "'" . $value . "',"; }

$parameterNamesArray = '';
foreach ($parameterNames as $key => $value) { $parameterNamesArray .= "'" . $value . "',"; }

?>

<script type="text/javascript">
    var baseUrl = '<?php echo url('/'); ?>';
    // parametros da url ( serve para excluir os parametros atuais ao fazer novos filtros )
    var urlParams = [<?=rtrim($urlParamsArray, ',');?>];
    var parameterNames = [<?=rtrim($parameterNamesArray, ',');?>];

    <?php if(isset($cliente_onchange_to_url)){ ?> var cliente_onchange_to_url = '<?php echo $cliente_onchange_to_url;?>'; <?php } ?>
</script>

<script src="{{ url('/') }}{{ elixir('js/all.js') }}"></script>

<?php
if (!empty($scripts) && is_array($scripts)) {
    foreach ($scripts as $v) {
        ?>
        <script type="text/javascript" src="<?php echo $v['src']; ?>"></script>
        <?php
    }
}
?>

</body>
</html>