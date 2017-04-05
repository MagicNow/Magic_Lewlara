// USUARIO
$(document).ready( function() {
  // USUARIO NOVO CREATE
  $('#usuario_novo_adicionar_link_pessoal').on('click', function(e){
    e.preventDefault();
    link_pessoal_count++;
    $('.group-link-pessoal').last().after('<div class="form-group group-link-pessoal"><label for="link_pessoal['+link_pessoal_count+']" class="control-label col-sm-3">Link Pessoal '+(link_pessoal_count+1)+'</label><div class="col-sm-6"><input class="form-control" name="link_pessoal['+link_pessoal_count+']" type="text" id="link_pessoal['+link_pessoal_count+']"></div><!-- /.col-sm-6 --></div><!-- /.form-group -->');   
  });



  $(document).on('change', '.usuario .btn-file :file', function() {
      var input = $(this),
          numFiles = input.get(0).files ? input.get(0).files.length : 1,
          label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
      input.trigger('fileselect', [numFiles, label]);
  });
  

  $('.usuario .btn-file :file').on('fileselect', function(event, numFiles, label) {
      //console.log(numFiles);
      $('#usuario_novo_imagem_selecionada').html(label);
      //console.log(label);
  });

  


  $('.abre-box-child-usuario').on('click', function(e){
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
