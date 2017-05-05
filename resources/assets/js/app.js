// GERAL GERAL GERAL

if (!window.location.origin) {
  window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
}


$(document).ready( function() {
  // toda requisição ajax passa na header a csrf token pegando a partir da meta tag da header do documento
  $.ajaxSetup({
      headers: {
          'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $('body').on('click', '.lslide.active', function () {
    setTimeout(function () {
      $('#redactor-image-link-blank').trigger('click');
    }, 1000);
  }).on('click', '.redactor-layer a', function () {
    setTimeout(function () {
      $('#redactor-image-link-blank').trigger('click');
    }, 1000);
  });
  

  // DROPDOWN TOPO PERFIL - LOGOUT
  $("#topo-barra-perfil-dropdown-trigger").on('click', function(e){
    $('#topo-barra-perfil-dropdown').toggle();
  });

  // ao submeter formulário verifica se o input submit não está desativado
  $("form").on('submit',function(e){
      if($(this).find('input[type="submit"]').is(":disabled")){
        e.preventDefault();
        return false;
      }
      return true;
  });

  // submete formulário se tecla enter
  $('input').keydown(function(e) {
      if (e.keyCode == 13) {
          $(this).closest('form').submit();
      }
  });

  // ao selecionar o campo marca todo o texto
  $(".seleciona-no-click").focus(function() {
      var $this = $(this);
      $this.select();

      // Work around Chrome's little problem
      $this.mouseup(function() {
          // Prevent further mouseup intervention
          $this.unbind("mouseup");
          return false;
      });
  });

  $('.check-todos-checkbox').on('click', function(e){      
      if(this.checked) {
          check = true;
      } else {
          check = false;
      } 
      
      $('.checkable').each(function() {
          this.checked = check;               
      });        
  });


  $('.select2multiple').select2({
    language: 'pt-BR'
  });

  $(".slug-source").keyup(function(){
          var Text = $(this).val();
          Text = slug(Text);
          $(".slug-target").val(Text);        
  });
   
  $(".slug-source-2").keyup(function(){
          var Text = $(this).val();
          Text = slug(Text);
          $(".slug-target-2").val(Text);        
  });

  // gerar slug
  var slug = function(str) {
     str = str.replace(/^\s+|\s+$/g, ''); // trim
     str = str.toLowerCase();

     // remove accents, swap ñ for n, etc
     var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
     var to   = "aaaaaeeeeeiiiiooooouuuunc------";
     for (var i=0, l=from.length ; i<l ; i++) {
       str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
     }

     str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
       .replace(/\s+/g, '-') // collapse whitespace and replace by -
       .replace(/-+/g, '-'); // collapse dashes

     return str;
   };

  
  // ao clicar em link que abre modal de confirmação de delete / exclusão
  $('#modal-confirm-delete').on('show.bs.modal', function(e) {
      $(this).find('.modal-header').text($(e.relatedTarget).data('header')); // busca informações header
      $(this).find('.modal-body').text($(e.relatedTarget).data('body')); // busca informações body

      $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href')); // busca atributo data href
  });

  // ao clicar em link que abre modal de confirmação generica
  $('#modal-confirm').on('show.bs.modal', function(e) {
      $(this).find('.modal-header').text($(e.relatedTarget).data('header')); // busca informações header
      $(this).find('.modal-body').text($(e.relatedTarget).data('body')); // busca informações body
      $(this).find('.btcancel').text($(e.relatedTarget).data('btcancel'));
      $(this).find('.btok').text($(e.relatedTarget).data('btok'));

      $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href')); // busca atributo data href
  });

  $('.filtro-grupo').on('click', function(e){    
    e.preventDefault();
    var grupo = $(this).data('grupo'),
        list  = $('.usuario table tbody tr');
    if(grupo == 'Todos'){
        list.show();    
    }else{
        list.hide();      
        list.each(function() {
            list.filter('[data-group*="' + grupo + '"]').show();
        });
    }
  });

  $('.form-filtra-usuario').find('.buscar-por').on('keypress', function (e) {
    if (e.which === 13) {
      aplicarFiltro(e);
    }
  });

  // Ordenar por
  $('.aplicar-ordem').on('click', aplicarFiltro);

  $('.form-filtra-usuario').find('.buscar-por-inline').on('keypress', function (e) {
    if (e.which === 13) {
      aplicarFiltroInline(e);
    }
  });

  // Ordenar por em telas com checkbox
  $('.aplicar-ordem-inline').on('click', aplicarFiltroInline);

  // Somente Filtrar por Cliente no OnChange
  $('.filtrar_por_cliente.acaoOnChange').on('change', function(e){    
    e.preventDefault();

    // reseta sessões filtros
    $.ajax({
        type: "POST",
        dataType: "html",
        url: baseUrl + "/ajax_resetaFiltros",
        data: '',
        success: function (data) {
          
           var filtrarporcliente = false;
           
           if($('.filtrar_por_cliente').length){ // se tem filtro por cliente
             filtrarporcliente = $('.filtrar_por_cliente').val();
           } else {
             return false;
           }

           var url = window.location.origin+window.location.pathname;

           // urlParams está na view footer, é um array contendo todos os parametros da url
           for (var i = urlParams.length - 1; i >= 0; i--) {
             url = url.replace('/'+urlParams[i],'');
           };

           // se houver filtro por cliente , adiciona na url
           if(filtrarporcliente){
            
             if(window.location.href.substr(window.location.href.length - 1) == '/')
                  url = url+''+filtrarporcliente;
              else
                   url = url+'/'+filtrarporcliente;
           }
           // adiciona na url o searh ( ?page=2 )
           //url = url+window.location.search;

           window.location = url;       
        }
    });    
  });

  // Somente Filtrar por Cliente no OnChange TOPO
  $('.topo_filtrar_por_cliente.acaoOnChange').on('change', function(e){    
    e.preventDefault();
    
    // reseta sessões filtros
    $.ajax({
        type: "POST",
        dataType: "html",
        url: baseUrl + "/ajax_resetaFiltros",
        data: '',
        success: function (data) {
          
           var filtrarporcliente = false;

           if($('.topo_filtrar_por_cliente').length){ // se tem filtro por cliente
             filtrarporcliente = $('.topo_filtrar_por_cliente').val();
           } else {
             return false;
           }
           
           if (typeof(cliente_onchange_to_url) !== 'undefined') {
                var url = cliente_onchange_to_url+'/'+filtrarporcliente;
                window.location = url; 
                return;
           }

           var url = window.location.origin+window.location.pathname;

           // urlParams está na view footer, é um array contendo todos os parametros da url
           for (var i = urlParams.length - 1; i >= 0; i--) {
              
              if(i==0) { // verifica o primeiro parametro
                if(parameterNames[0] != 'ordenar_por_default' && parameterNames[0] != 'default_ordenar_por'){ // se não for parametro de ordem, remove
                  url = url.replace('/'+urlParams[i],'');
                }
              } else { // se não for o primeiro parametro - remove da url
                url = url.replace('/'+urlParams[i],'');
              }
             
           };

           // se houver filtro por cliente , adiciona na url
           if(filtrarporcliente){
            
             if(window.location.href.substr(window.location.href.length - 1) == '/')
                  url = url+''+filtrarporcliente;
              else
                  url = url+'/'+filtrarporcliente;
           }
           // adiciona na url o searh ( ?page=2 )
           //url = url+window.location.search;
           //console.log(url);
           window.location = url;       
        }
    });    
  });

  $(document).on('change', '.btn-file.file-unico :file', function() {
      var input = $(this),
          numFiles = input.get(0).files ? input.get(0).files.length : 1,
          label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
      input.trigger('fileselect', [numFiles, label]);
  });

  $('#file_unico_imagem_selecionada').on('click', function(){
      $('.btn-file.file-unico :file').trigger('click');
  });
  $('.btn-file.file-unico :file').on('fileselect', function(event, numFiles, label) {
      $('#file_unico_imagem_selecionada').html(label);
  });


  // Adicionar campo dinamico
  $('#adicionar_novo_campo').on('click', function(e){
    e.preventDefault();
    label = $(this).data('label');
    campo = $(this).data('campo');
    campo_count++;
    $('.group-link').last().after('<div class="form-group group-link"><label for="'+campo+'['+campo_count+']" class="control-label col-sm-3">'+label+' '+(campo_count+1)+'</label><div class="col-sm-6"><input class="form-control" name="'+campo+'['+campo_count+']" type="text" id="'+campo+'['+campo_count+']"></div><!-- /.col-sm-6 --></div><!-- /.form-group -->');   
  });

  // abre box child 
  $('.abre-box-child').on('click', function(e){
        var tthis = $(this);
        $.when(
            $('td div.box-child').each(function(i,o) {
                    $(o).slideUp('400');
                })
        )
        .done(function() {
            tthis.parent().parent().parent().next('tr').find('div.box-child').slideDown('400');
        });        
  });

});

function atualizaParametroUrl(uri, key, value) {
  var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
  var separator = uri.indexOf('?') !== -1 ? "&" : "?";
  if (uri.match(re)) {
    return uri.replace(re, '$1' + key + "=" + value + '$2');
  } else {
    return uri + separator + key + "=" + value;
  }
}

function aplicarFiltro(e) {
  e.preventDefault();

  var form = $('.form-filtra-usuario');
  var ordenarpor = form.find('.ordenar-por').val();
  var buscarpor = form.find('.buscar-por').val();
  var filtrarporcliente = false;

  if($('.topo_filtrar_por_cliente').length){ // se tem filtro por cliente
    filtrarporcliente = $('.topo_filtrar_por_cliente').val();
  }

  if(ordenarpor == ''){
    ordenarpor = 'az';
  }

  var url = window.location.origin+window.location.pathname;

  // urlParams está na view footer, é um array contendo todos os parametros da url
  for (var i = urlParams.length - 1; i >= 0; i--) {
    url = url.replace('/'+urlParams[i],'');
  };
  // adiciona na url o ordenar por
  url = url+'/'+ordenarpor;
  // se houver filtro por cliente , adiciona na url
  if(filtrarporcliente){
    url = url+'/'+filtrarporcliente;
  }
  // adiciona na url o searh ( ?page=2 )
  //url = url+window.location.search;

  if (buscarpor) {
    url = atualizaParametroUrl(url, 'nome', buscarpor);
  }

  window.location = url;
}

function aplicarFiltroInline (e) {
  e.preventDefault();
  e.stopPropagation();

  var form = $('.form-filtra-usuario');
  var lista = $('.lista-container');
  var itens = lista.find('.lista-item');
  var buscarpor = form.find('.buscar-por-inline').val();

  if (buscarpor.length > 0) {
    itens.hide();
    itens.find('.lista-item-label:contains("' + buscarpor.toUpperCase() + '")').parent('.lista-item').show();
  } else {
    itens.show();
  }

  return false;
}