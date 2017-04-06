// CLIENTE 
$(document).ready( function() {

  // CLIENTE NOVO CREATE

  $('#cliente_novo_adicionar_link_cliente').on('click', function(e){
  	e.preventDefault();
  	link_cliente_count++;
  	$('.group-link-cliente').last().after('<div class="form-group group-link-cliente"><label for="link['+link_cliente_count+']" class="control-label col-sm-3">LINK '+(link_cliente_count+1)+'</label><div class="col-sm-6"><input class="form-control" name="link['+link_cliente_count+']" type="text" id="link['+link_cliente_count+']"></div><!-- /.col-sm-6 --></div><!-- /.form-group -->');	
  });

  $(document).on('change', '.cliente .btn-file :file', function() {
      var input = $(this),
          numFiles = input.get(0).files ? input.get(0).files.length : 1,
          label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
      input.trigger('fileselect', [numFiles, label]);
  });
 

  $('.cliente .btn-file :file').on('fileselect', function(event, numFiles, label) {
      //console.log(numFiles);
      $('#cliente_novo_imagem_selecionada').html(label);
      //console.log(label);
  });

  

  // CLIENTE  TODOS LIST

  $('.abre-editar-cliente').on('click', function(e){
       /* $('.box-editar-excluir').css('display','none');
        $(this).parent().parent().find('.box-editar-excluir').css('display','inline-block');*/
        $('.box-editar-excluir').slideUp();
        $(this).parent().parent().find('.box-editar-excluir').slideDown();
  });

  
  $('.muda-cliente-visivel').on('change',function(e){
      e.preventDefault();
      var valor_selecionado = $(this).val();
      if(valor_selecionado == 0){
        var vis = 'NÃO visível';
      } else {
        var vis = 'visível';
      }
      var msg = 'Deseja definir o cliente \''+$(this).data('namecliente')+'\' como '+vis+'?';

      modal_visiblidade = $('#modal-altera-visibilidade');
      modal_visiblidade.find('.modal-body').text(msg);
      modal_visiblidade.find('#info').data('visivel',valor_selecionado);
      modal_visiblidade.find('#info').data('idcliente',$(this).data('idcliente'));
      modal_visiblidade.modal({show:true});

  });

  // Ao caso NÃO ALTERAR visibilidade do cliente
  $("#modal-altera-visibilidade .btn-cancel").on('click', function(e){
      modal = $('#modal-altera-visibilidade');
      info = modal.find('#info');
      if(info.data('visivel') == 0){ // pega informação do status selecionado para repopular o select
        $(".muda-cliente-visivel[data-idcliente='"+info.data('idcliente')+"'").val('1');
      } else {
        $(".muda-cliente-visivel[data-idcliente='"+info.data('idcliente')+"'").val('0');
      }
  });

  // caso confirma ALTERAR visibilidade do cliente
  $('#modal-altera-visibilidade .btn-ok').on('click',function(e){

    info = $('#modal-altera-visibilidade').find('#info');
    cliente_id = info.data('idcliente');
    cliente_active = info.data('visivel');
    $.ajax({
      type: "POST",
      url : baseUrl+"/cliente/ajaxClienteVisivel",
      data : { 'cliente_id':cliente_id, 'cliente_active':cliente_active },
      success : function(data){
          //console.log(data);
      }
    });

    $('#modal-altera-visibilidade').modal('hide');
  });

});