$(document).ready(function () {

    // POST GERAR PDF
    // POST GERAR PDF
    // POST GERAR PDF

    $('.filtrar-posts-pdf').on('click',function(e){
        var filtros = new Object();

        var datas = $(this).parent().find('.select-data').val();
        
        if(datas && datas.trim() != ''){
            filtros.datas = datas;
        }

        var categorias = $(this).parent().find('.select-categorias').val();
        
        if(categorias && categorias.trim() != ''){
            filtros.categorias = categorias;
        }

        /*var subcategorias = $(this).parent().find('.select-subcategorias').val();
        
        if(subcategorias && subcategorias.trim() != ''){
            filtros.subcategorias = subcategorias;
        }*/

        var busca = $('.filtro-buscar-blog').val();

        if(busca && busca.trim() != ''){
            filtros.busca = busca;
        }

        $.ajax({
            type: "POST",
            dataType: "html",
            url: baseUrl + "/post/ajax_filtrosPDF",
            data: filtros,
            success: function (data) {
                window.location.href=window.location.pathname;
            }
        });

    });

    $('.select-categorias').on('change', function (e) {       
        var categoria_id = $(this).val();
        e.preventDefault();

        var selectSubcategorias = $('.select-subcategorias');

        selectSubcategorias.empty();
        selectSubcategorias.append('<option value="">Carregando &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</option>');

        $.ajax({
            type: "POST",
            dataType: "json",
            url: baseUrl + "/post/ajaxSubcategoria",
            data: {'categoria_id': categoria_id},
            success: function (data) {
                // popula o select com as subcategorias retornadas                
                var listitems = '';
                listitems += '<option value="">Todas as subcategorias</option>';
                $.each(data, function (key, value) {
                    listitems += '<option value=' + key + '>' + value + '</option>';
                });
                selectSubcategorias.empty();
                selectSubcategorias.append(listitems);
            }
        });
    });

    $('.bt-buscar-blog').on('click', function(e){
        $('.filtrar-posts-pdf').trigger('click');
    });

    $('.post-checkbox').on('click', function(e){
        var dados = new Object();

        dados.postId = $(this).val();

        if($(this).is(':checked')){
            dados.acao = 'set';
        } else {
            dados.acao = 'unset';
        }

        $.ajax({
            type: "POST",
            dataType: "html",
            url: baseUrl + "/post/ajax_postSelecionadoPDF",
            data: dados,
            success: function (data) {
                
            }
        });
    });

});