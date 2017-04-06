$(document).ready(function () {
    $('.aplicar-select-acao-newsletter').on('click',function(e){
        var acao = $(this).prev('.select-acao').val();
        if(acao == 'excluir'){
            var newsletterIds = [];
            $('.newsletter-checkbox:checked').each(function() {
                newsletterIds.push($(this).val());
            });            
            if(newsletterIds.length == 0){
                alert('selecione ao menos uma newsletter para aplicar');
                return false;
            } 
            
            $.ajax({
                type: "POST",
                dataType: "html",
                url: baseUrl + "/newsletter/ajax_newsletterDestroy",
                data: {'newsletter': newsletterIds},
                success: function (data) {
                    window.location.href=window.location.href;
                }
            });
        } else {
            alert('selecione uma ação para aplicar');
        }        
    });  

   $('#newsletter-check-todos').on('click', function(e){      
       if(this.checked) {
           check = true;
       } else {
           check = false;
       } 
       
       $('.newsletter-checkbox').each(function() {
           this.checked = check;               
       });        
   });


   $('.filtrar-newsletter').on('click',function(e){
       var filtros = new Object();

       var datas = $(this).parent().find('.select-data').val();
       
       if(datas && datas.trim() != ''){
           filtros.datas = datas; 
       }
      
       $.ajax({
           type: "POST",
           dataType: "html",
           url: baseUrl + "/newsletter/ajax_filtrosLista",
           data: filtros,
           success: function (data) {
               window.location.href=window.location.pathname;
           }
       });

   });



   
   // modais modals

  $('.btn-ver-posts').on('click', function () {
      var newsletter_id = $(this).data('newsletterid');
      
      // ATIVA LOADING

      // carrega tab posts
      $.ajax({
          type: "POST",
          dataType: "html",
          url: baseUrl + "/newsletter/ajax_verPosts",
          data: {'newsletter_id': newsletter_id }, 
          success: function (data) {
              // DESATIVA LOADING

              $('#tabcontent-ver-posts').html(data);
                           
               // abre modal
              $('#modal-ver-posts-pessoas').toggleClass('hide');
               // abre aba posts
              $('#tab-ver-posts').trigger('click');
          }
      });

      // carrega tab pessoas
      $.ajax({
          type: "POST",
          dataType: "html",
          url: baseUrl + "/newsletter/ajax_verPessoas",
          data: {'newsletter_id': newsletter_id }, 
          success: function (data) {
              $('#tabcontent-ver-pessoas').html(data);
          }
      });  
     
  });

  $('.btn-ver-pessoas').on('click', function () {
      var newsletter_id = $(this).data('newsletterid');
      
      // ATIVA LOADING

      $.ajax({
          type: "POST",
          dataType: "html",
          url: baseUrl + "/newsletter/ajax_verPessoas",
          data: {'newsletter_id': newsletter_id }, 
          success: function (data) {
              // DESATIVA LOADING

              $('#tabcontent-ver-pessoas').html(data);

               // abre modal
              $('#modal-ver-posts-pessoas').toggleClass('hide');
               // abre aba 
              $('#tab-ver-pessoas').trigger('click');
          }
      }); 

      // carrega tab posts
      $.ajax({
          type: "POST",
          dataType: "html",
          url: baseUrl + "/newsletter/ajax_verPosts",
          data: {'newsletter_id': newsletter_id }, 
          success: function (data) {
              $('#tabcontent-ver-posts').html(data);
          }
      });     
  });

  $('#close-modal-ver-posts-pessoas').on('click', function () {
     $('#modal-ver-posts-pessoas').toggleClass('hide');
  });


});