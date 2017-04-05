
$(document).ready(function () {
    if($("#adaptive").length ){
        $('#adaptive').lightSlider({
            addClass:"slider-home",
            adaptiveHeight: true,
            item: 1,
            slideMargin: 0,
            loop: true,
            onSliderLoad:  function (el) {
                $('.lSPager > li > a').each(function () {
                    if ($(this).html().length < 2)
                        $(this).html('0' + $(this).html());
                });
            }
        });
    }
    /*REGRAS NUVEM DE TAGS*/
     if( ! $('#myCanvas').tagcanvas({
    textColour : '#000',
     outlineColour : "transparent",
     radiusX: 2,
     radiusY: 1.5,
     radiusZ: 1.5,
     textHeight: 14,
     imagePosition: "left",
     zoom:1.0,
     zoomMax:1.0,
     zoomMin:1.0,
     reverse: true
   })) 
   {
     // TagCanvas failed to load
     $('#myCanvasContainer').hide();
   }
// setInterval(function(){ 
    if($('.post-galeria').not('.lightSlider').length > 0){ 
        $.each($('.post-galeria').not('.lightSlider'), function(e){
            $(this).lightSlider({ gallery: true, adaptiveHeight: false, item: 1, loop:true, slideMargin: 0, thumbItem: 5 }); 
        });
    } 
// }, 500);

//METODO COLOCAR PLACE HOLDER NA TEXTAREA DO NOVO COMENTÁRIO
$(function() {
   $("#novo").attr("placeholder", "DEIXE SEU COMENTÁRIO").val("");
});

$(function(){
    $(".comentario").on("click", function() {
        var id = $(this).attr('id');
        textarea_keyup(id, $(this));
        $("#"+id).on('keyup', function () {
            textarea_keyup(id, $(this));
        });
    });
    //função para quando dar o focus ele chama a função texarea_keyup para atualizar os caracteres
    $(".comentario").on("focus",function(){
        textarea_keyup(id, $(this));
    });

    var enviar = true; //variável global que contem para ver se minha texarea novo comentário vai dar submit
    $('#novo').keypress(function(event) {  
        if (event.keyCode == 13) {
            event.preventDefault();
            if(enviar == true){
                var $this = $(this),
                comentario = $this.data('action');
                conteudo = $this.val();
                
                var resposta = tem_conteudo(conteudo);//verificando se o usuário digitou pelo menos um caracter
                if(resposta==true){
                    $.ajax({
                        type: "POST",
                        dataType: "html",
                        url: $this.attr('href') + '?comentario=' + comentario,
                        data:{'comentario' :comentario, 'conteudo':conteudo},
                        success: function (data) {
                            window.location.href=window.location.href;
                        }
                    });
                    $("#novo + span").text("Comentário enviado!").css({color: 'blue'});
                    $("#novo + span").fadeOut(5000);
                    enviar=false;
                }else{
                    $("#novo + span").text("Digite algo!").css({color: 'red'});
                }
            }
        
    }
    });
});

function textarea_keyup(id, $this){
    // pega a span onde esta a quantidade máxima de caracteres.
    if(id=="novo"){
        var target = $("#novo-limite");
    }else{
        var target = $("#"+id+"-edit");
    }
    var max = 1578;
    // tamanho da string dentro da textarea.
    var len = $this.val().length;

    // quantidade de caracteres restantes dentro da textarea.
    var remain = max - len;

    // caso a quantidade dentro da textarea seja maior que
    // a quantidade maxima.
    if(len > max)
    {
        //  pegar tudo que tiver na string e limitar
        // a quantidade de caracteres para o máximo setado.
        // o máximo será cortado.
        var val = $this.val();
        $this.val(val.substr(0, max));

        // setando o restante para 0.
        remain = 0;
    }
    // atualizando a quantidade.
    target.html(remain);
}

/*FUNÇÃO PARA EDITAR COMENTARIO DO BLOG*/

var editar=true; //variável global que serve para deixar o usuário fazer apenas uma requisição
$(".comentario-blog-btn-edit").on("click", function() {
    //pegando o  id do btn
    var id = $(this).attr('id');    
    //pegando o id da classe pai
    var id_pai = id.replace('-btn', '');
    var url = $("#novo").attr('href');
    //excluindo o button editar comentário
    var comentario = document.getElementById(id_pai);
    var btn_comentario = document.getElementById(id); // Seleciona o elemento filho que vai ser excluido
    var excluir = comentario.removeChild(btn_comentario);

    var valorDaDiv = $('p', $( '#'+id_pai)).text();

    //criando uma textarea para editar o comentário
    var input = document.createElement("textarea");
    $(input).addClass('comentario-blog-tipo comentario-blog-tipo-novo comentario');
    input.id=id_pai;
    input.rows = "10";
    input.value=valorDaDiv;
    $(input).on('keyup', function () {
            textarea_keyup(id_pai, $(this));
    });
    $(input).on('focus', function () {
            textarea_keyup(id_pai, $(this));
    }); 
    $(input).keypress(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();   
            if(editar==true){
                var id_comentario = id_pai.replace('comentario-', '');
                var $this = $(this),
                comentario = "editar";
                conteudo = $(input).val();
                var mensagem = tem_conteudo(conteudo);//verificando se o usuário digitou pelo menos um caracter
                if(mensagem==true){
                    $.ajax({
                        type: "POST",
                        dataType: "html",
                        url: url + '?comentario='+comentario,
                        data:{'comentario' :comentario, 'conteudo':conteudo,'id_comentario':id_comentario},
                        success: function (data) {
                            window.location.href=window.location.href;
                        }
                    });

                    $("#"+ $(this).attr('id') + " + span").text("Comentário editado!").css({color: 'blue'});
                    $("#"+ $(this).attr('id') + " + span").fadeOut(5000);
                    
                    editar=false;
                 }else{
                    $("#"+ $(this).attr('id') + " + span").text("Comentário em branco").css({color: 'red'});
                 }

            } 
       
        }
     
    });
 
    //criando um span para mensagem do comentário
    var mensagem = document.createElement("span")
    $(mensagem)
            .addClass('mensagem');
    var caracteres = document.createElement("span")
    //criando um span com nome caracteres
    $(caracteres)
            .text('CARACTERES')
            .addClass('comentario-blog-caracter');
    //criando um span com a contagem de caracteres
    var limite_caracteres = document.createElement("span")
    limite_caracteres.id=id_pai+"-edit";
    $(limite_caracteres)
            .text("1578")
            .addClass('limite-caracteres comentario-blog-caracter');
    $( "#"+id_pai).replaceWith(input,mensagem,caracteres,limite_caracteres);
    
    $("#"+id_pai).focus();
    colocar_cursor_nofinal(id_pai);  
})
//esse metodo serve para colocar o curso no final da minha textarea
function colocar_cursor_nofinal(id){
    var obj = $("#"+id);
    // guardando co conteudo em uma variavel
    val = obj.val();
    // limpando o conteudo  e gravando de novo
    obj.focus().val("").val(val);
    // movendo o scroll para o final
    obj.scrollTop(obj[0].scrollHeight);
}


$(".click-favorito").on("click", function(e) {
    e.preventDefault();
    var $this = $(this),
        action = $this.data('action');
    $.post($this.attr('href') + '?action=' + action, {'action': action}).done(function(){ //chamando minha route, que chamar minha controller com a ação like ou unlike!
    
    });
    //pegando o numero de vezes favoritados
    num = $('.icons-titulo-favorito-num').text();

    //se for action like 
    if(action=="like"){
        num++;
        $this.data('action', 'unlike');
    }else{  //se for action unlike
        num--;
        $this.data('action', 'like');
    }
    $(".icons-titulo-favorito-num").replaceWith("<p class='icons-titulo-favorito-num'>"+num+"</p>");

});
//função para verificar se tem letras dentro de uma string

function tem_conteudo(texto){
   for(i=0; i<texto.length; i++){
      if (texto[i] !=" "){
        return true;
      }
   }
   return false;
} 



}); 


   


