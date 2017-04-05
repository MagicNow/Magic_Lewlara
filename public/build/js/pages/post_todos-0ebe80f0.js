
 //POST TODOS
$(document).ready(function () {
    // filtros
    $('.aplicar-select-acao-post').on('click',function(e){
        var acao = $(this).prev('.select-acao').val();
        if(acao == 'excluir'){
            var postsIds = [];
            $('.post-checkbox:checked').each(function() {
                postsIds.push($(this).val());
            });            
            if(postsIds.length == 0){
                alert('selecione ao menos um post para aplicar');
                return false;
            } 
            
            $.ajax({
                type: "POST",
                dataType: "html",
                url: baseUrl + "/post/ajax_postDestroy",
                data: {'posts': postsIds},
                success: function (data) {
                    window.location.href=window.location.href;
                }
            });
        } else {
            alert('selecione uma ação para aplicar');
        }        
    });  
    $('.datepicker').datepicker();
    $('.bt-buscar-blog').on('click', function(e){
        $('.filtrar-posts-todos-posts').trigger('click');
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

    $('.filtrar-posts-todos-posts').on('click',function(e){
        var filtros = new Object();

        var data_inicio = $(this).parent().find('.select-data-inicio').val();
        
        if(data_inicio && data_inicio.trim() != ''){
            filtros.data_inicio = data_inicio;
        }

        var data_final = $(this).parent().find('.select-data-final').val();
        
        if(data_final && data_final.trim() != ''){
            filtros.data_final = data_final;
        }

        var categorias = $(this).parent().find('.select-categorias').val();
        
        if(categorias && categorias.trim() != ''){
            filtros.categorias = categorias;
        }

        var tag = $(this).parent().find('.select-tags').val();
        
        if(tag && tag.trim() != ''){
            filtros.tag = tag;
        }
        console.log('filtroe',filtros);
        var busca = $('.filtro-buscar-blog').val();

        if(busca && busca.trim() != ''){
            filtros.busca = busca;
        }

        $.ajax({
            type: "POST",
            dataType: "html",
            url: baseUrl + "/post/ajax_filtrosTodosPosts",
            data: filtros,
            success: function (data) {
                window.location.href=window.location.pathname;
            }
        });

    });

});