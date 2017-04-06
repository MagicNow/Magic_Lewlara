$(document).ready(function () {
    $('.bt-editar-comentario').on('click',function(e){
        var td = $(this).parents('td');
        td.find('.box-exibir').css('display','none');
        td.find('.box-editar').css('display','block');
    });

    $('.bt-cancelar-editar-comentario').on('click',function(e){
        var td = $(this).parents('td');
        td.find('.box-editar').css('display','none');
        td.find('.box-exibir').css('display','block');
    });
});