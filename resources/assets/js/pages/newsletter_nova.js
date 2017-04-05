$(document).ready(function () {

    // NOVA NEWSLETTER TELA POSTS
    // NOVA NEWSLETTER TELA POSTS
    // NOVA NEWSLETTER TELA POSTS

    $('.filtrar-newsletter').on('click',function(e){
        var filtros = new Object();

        var datas = $(this).parent().find('.select-data').val();
        
        if(datas && datas.trim() != ''){
            filtros.datas = datas;
        }

        var categorias = $(this).parent().find('.select-categorias').val();
        
        if(categorias && categorias.trim() != ''){
            filtros.categorias = categorias;
        }

        var subcategorias = $(this).parent().find('.select-subcategorias').val();
        
        if(subcategorias && subcategorias.trim() != ''){
            filtros.subcategorias = subcategorias;
        }

        var busca = $('.filtro-buscar-blog').val();

        if(busca && busca.trim() != ''){
            filtros.busca = busca;
        }

        $.ajax({
            type: "POST",
            dataType: "html",
            url: baseUrl + "/newsletter/ajax_filtros",
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
        $('.filtrar-newsletter').trigger('click');
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
            url: baseUrl + "/newsletter/ajax_postSelecionado",
            data: dados,
            success: function (data) {
                
            }
        });
    });

    // NOVA NEWSLETTER TELA PESSOAS
    // NOVA NEWSLETTER TELA PESSOAS
    // NOVA NEWSLETTER TELA PESSOAS

    $('.pessoa-checkbox').on('click', function(e){
        var dados = new Object();

        dados.pessoaId = $(this).val();

        if($(this).is(':checked')){
            dados.acao = 'set';
        } else {
            dados.acao = 'unset';
        }

        $.ajax({
            type: "POST",
            dataType: "html",
            url: baseUrl + "/newsletter/ajax_pessoaSelecionada",
            data: dados,
            success: function (data) {
            }
        });
    });

    $('.bt-disparar-newsletter').on('click', function(e){
        e.preventDefault();
        var link = $(this).attr('href');
        var dados = new Object();

        dados.title = $('#title').val();

        console.log('dados',dados);

        $.ajax({
            type: "POST",
            dataType: "html",
            url: baseUrl + "/newsletter/ajax_title",
            data: dados,
            success: function (data) {
                console.log('result',data);
               window.location.href = link;
            }
        });
    });
    $('#pessoas-check-todos').on('click', function(e){
        var dados = new Object();

        if(this.checked) {
            check = true;
            dados.acao = 'set';
        } else {
            check = false;
            dados.acao = 'unset';
        }
        
        var pessoasId = [];

        $('.pessoa-checkbox').each(function() {
            this.checked = check;  
            pessoasId.push($(this).val());                
        });  

        
        dados.pessoaId = pessoasId;


        $.ajax({
            type: "POST",
            dataType: "html",
            url: baseUrl + "/newsletter/ajax_pessoaSelecionada",
            data: dados,
            success: function (data) {
            }
        });  
    });


    $('.grupo-checkbox').on('click', function(e){
        var dados = new Object();

        dados.grupoId = $(this).val();

        if($(this).is(':checked')){
            dados.acao = 'set';
        } else {
            dados.acao = 'unset';
        }

        $.ajax({
            type: "POST",
            dataType: "html",
            url: baseUrl + "/newsletter/ajax_grupoSelecionado",
            data: dados,
            success: function (data) {
            }
        });
    });

    $('#grupos-check-todos').on('click', function(e){
        var dados = new Object();

        if(this.checked) {
            check = true;
            dados.acao = 'set';
        } else {
            check = false;
            dados.acao = 'unset';
        }
        
        var gruposId = [];

        $('.grupo-checkbox').each(function() {
            this.checked = check;  
            gruposId.push($(this).val());                
        });  

        
        dados.grupoId = gruposId;


        $.ajax({
            type: "POST",
            dataType: "html",
            url: baseUrl + "/newsletter/ajax_grupoSelecionado",
            data: dados,
            success: function (data) {
            }
        });  
    });

});