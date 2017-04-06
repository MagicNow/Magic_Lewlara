$.Redactor.prototype.underline = function()
{
    return {
        init: function()
        {
            var button = this.button.addAfter('italic', 'underline', 'Underline');
            this.button.addCallback(button, this.underline.format);
        },
        format: function()
        {
            this.inline.format('u');
        }
    };
};


 //POST 
$(document).ready(function () {

    /*$('textarea.editavel').wysiwyg({ 
        autoSave: true,
        rmUnusedControls: true,
        css: baseUrl + '/css/textarea-editavel.css',
        iFrameClass: 'iframe-textarea-editavel',
        autoGrow: true,
        html: "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'><html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' style='margin:0'><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'><link rel='stylesheet'  href='"+baseUrl+"/libs/lightslider/css/lightslider.css'/></head><body style='margin:0;'>INITIAL_CONTENT "+scriptss+" </body></html>",
        controls: {
            colorpicker: {
                groupIndex: 11,
                visible: true,
                css: {
                    "color": function (cssValue, Wysiwyg) {
                        var document = Wysiwyg.innerDocument(),
                            defaultTextareaColor = $(document.body).css("color");
                            console.log(defaultTextareaColor);
                        if (cssValue !== defaultTextareaColor) {
                            return true;
                        }

                        return false;
                    }
                },
                exec: function() {
                    if ($.wysiwyg.controls.colorpicker) {
                        $.wysiwyg.controls.colorpicker.init(this);
                    }
                },
                tooltip: "Colorpicker"
            }
        }
        
    }); */


    $('textarea.editavel').redactor({
        buttonSource: true,
        cleanOnPaste: false,
        replaceDivs: false,
        removeDataAttr: false,
        preSpaces: 4,
        allowedTags: ['p', 'h1', 'h2', 'pre', 'div', 'span','ul','li', 'hr','img','a','strong','b','em','i','u','del','br','iframe'],
        allowedAttr:  [
            ['p', 'class'],
            ['span', 'style'],
            ['hr', 'class'],
            ['ul', 'class'],
            ['iframe',['src','style','width','height','allowfullscreen']],
            ['li', 'data-thumb'],
            ['div', ['unselectable','style','contenteditable','class','contenteditable']],
            ['img', ['src','style','rel','width','height']]
        ],
        buttonsHide: ['unorderedlist', 'orderedlist', 'outdent', 'indent'],
        plugins: ['underline', 'video', 'fontsize', 'fontcolor', 'alignment'],
        imageEditable: true,
        imageLink: true,
        imagePosition: true
    });

    $(".bg-textarea-editavel-toolbar.bt-bold").mousedown(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.re-bold').trigger('mousedown');
    });
    $(".bg-textarea-editavel-toolbar.bt-italic").mousedown(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.re-italic').trigger('mousedown');
    });
    $(".bg-textarea-editavel-toolbar.bt-underline").mousedown(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.re-underline').trigger('mousedown');
    });
    $(".bg-textarea-editavel-toolbar.bt-strikeThrough").mousedown(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.re-deleted').trigger('mousedown');
    });
    $(".bg-textarea-editavel-toolbar.bt-justifyLeft").mousedown(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.re-alignment').trigger('mousedown');
        $('.redactor-dropdown-left').trigger('mousedown');
    });
    $(".bg-textarea-editavel-toolbar.bt-justifyCenter").mousedown(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.re-alignment').trigger('mousedown');
        $('.redactor-dropdown-center').trigger('mousedown');
    });
    $(".bg-textarea-editavel-toolbar.bt-justifyRight").mousedown(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.re-alignment').trigger('mousedown');
        $('.redactor-dropdown-right').trigger('mousedown');
    }); 
    $(".bt-fontSize").on('change', function (e) {       
        $('.re-fontsize').trigger('mousedown');
        $('.redactor-dropdown-'+$(this).val()).trigger('mousedown');   
        $(this).val('');     
    }); 
    $(".bt-fontColor").mousedown(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.re-fontcolor').trigger('mousedown');
        $('.redactor-dropdown-box-fontcolor').detach().appendTo('.toolbar-to-drop');
        $('.redactor-dropdown-box-fontcolor').css({
          "top": "60px",
          "left": "0"
        });
    });
    $(".bg-textarea-editavel-toolbar.bt-hiperlink").mousedown(function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $self = $(this);
        $('.re-icon-link').trigger('mousedown');
        $('.redactor-dropdown-box-link').css({
            "top": $self.offset().top,
            "left": $self.offset().left
        });
    });

    $(".bt-fontColor").click(function (e) {
        e.preventDefault();
        e.stopPropagation();
    }); 


    
    /*$(".bg-textarea-editavel-toolbar.bt-fontIncrease").mousedown(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.re-bold').trigger('click');
    });
    $(".bg-textarea-editavel-toolbar.bt-fontDecrease").mousedown(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('.re-bold').trigger('click');
    });*/
/*    $(".bg-textarea-editavel-toolbar.bt-colorPicker").click(function () {
        $("textarea.editavel").wysiwyg("triggerControl", "colorpicker");
    });*/


    // CATEGORIA E SUBCATEGORIA

    $('#btn-adicionar-categoria').on('click', function(e){
        e.preventDefault();

        var categoria_options_arr = [];
        var obj = categorias_select;

        for (var key in obj) {
            if (obj.hasOwnProperty(key)) {
                categoria_options_arr[key] = obj[key];
            }
        }
        categoria_options = '';
        $.each(categoria_options_arr, function (i, item) {
            if(typeof(item) != 'undefined'){
                categoria_options +=  "<option value='"+i+"'>"+item+"</option>";
            }
        });


        var html = "<div class='categoria-grupo'>"+
                        "<br>"+
                        "<a class='btn btn-cinza btn-extra-pequeno align-center float-right mb-10 btn-remover-categoria'>REMOVER</a>"+
                        "<br>"+
                        "<select class='form-control arrow-preto-amarelo onChangeAlteraSubcategoria' name='categoria_id[]'><option value='' selected>Selecione a Categoria</option>"+categoria_options+"</select>"+
                        "<br>"+
                        "<select class='form-control arrow-preto-amarelo selectSubcategorias hide' name='subcategoria_id[]'><option value='' selected>Selecione a Categoria</option></select>"+
                        "<br>"+
                    "</div><!-- /.categoria-grupo -->";

        $( html ).insertBefore("#categoria-grupo-bt");
    });

    $('body').on('click', '.btn-remover-categoria', function(e){
        e.preventDefault();

        $(this).parent().remove();
    });

    $('body').on('change','.onChangeAlteraSubcategoria', function (e) {
        // desabilita o botão de submit
        $('input[type="submit"]').attr('disabled', 'disabled');
        tthis = $(this);
        var categoria_id = $(this).val();
        e.preventDefault();

        $.ajax({
            type: "POST",
            dataType: "json",
            url: baseUrl + "/post/ajaxSubcategoria",
            data: {'categoria_id': categoria_id},
            success: function (data) {
                if(data == '') {
                    var selectSubcategorias = tthis.parent().find('.selectSubcategorias');
                    selectSubcategorias.empty()
                    selectSubcategorias.append("<option value=''></option>");
                    selectSubcategorias.addClass('hide');
                    $('input[type="submit"]').removeAttr('disabled');
                    return true;
                }
                // popula o select com as subcategorias retornadas
                var selectSubcategorias = tthis.parent().find('.selectSubcategorias');
                selectSubcategorias.removeClass('hide');
                var listitems = '';
                listitems += '<option value="">Selecione a subcategoria</option>';
                $.each(data, function (key, value) {
                    listitems += '<option value=' + key + '>' + value + '</option>';
                });
                selectSubcategorias.empty();//.find('option').remove();  
                selectSubcategorias.append(listitems);

                // se houver valor submetido no form que retornou de erro, define ele
                if (typeof (old_subcategoria_id) != 'undefined') {
                    if (old_subcategoria_id > 0) {
                        // verifica se existe o id nos options, se sim, seleciona
                        selectSubcategorias.find('option').each(function () {
                            if (this.value == old_subcategoria_id) {
                                selectSubcategorias.val(old_subcategoria_id);
                                return false;
                            }
                        });
                    }
                }

                // habilita o botÃ£o de submit
                $('input[type="submit"]').removeAttr('disabled');
            }
        });
    })


    // se houver valor submetido no form que retornou de erro repopula o select de subcategoria
    if (typeof (old_subcategoria_id) != 'undefined') {
        if (old_subcategoria_id > 0 || old_subcategoria_id == null) {
            $('#onChangeAlteraSubcategoria').trigger('change');
        }
    }

    $('#btn-editar').on('click', function (e) {
        $(this).hide();
        $('#publicar-texto').html('PUBLICAR EM');
        $('#publicar-em-obrigatorio').val('1');
        $(this).parent().find('.div-publicar-em-child').slideDown();
    });


    // MODAL MODAIS MODALS
    // MODAL MODAIS MODALS
    // MODAL MODAIS MODALS

    // GERAL

    $('#modal-midias').on('click', '.limpaselect ', function (e) {
        $('.selected-frame:not(.hide)').addClass('hide');
        var nselect = $('.selected-frame:not(.hide)').length;
        $('.libraryselected').html(nselect);
    });
    

    Dropzone.autoDiscover = false;

    // INSERIR INSERT MIDIAS
    // CRIAR GALERIA LIBRARY 1
    // CRIAR GALERIA LIBRARY 1
    // CRIAR GALERIA LIBRARY 1
    // CRIAR GALERIA LIBRARY 1
    // CRIAR GALERIA LIBRARY 1
    // CRIAR GALERIA LIBRARY 1
    // CRIAR GALERIA LIBRARY 1
    // CRIAR GALERIA LIBRARY 1
    //===========================================================================================================================

	$("#insertUploadDropzone").dropzone({
        url: $('#insertUploadDropzone').attr('action'),
        paramName: "image",
        clickable: '#insertUploadDropzone .dropzone-clickable',
        acceptedFiles: 'image/*',
        previewTemplate: '<div style="display:none"></div>',
        init: function () {
            this.on("success", function (file, retorno) {
                retorno = jQuery.parseJSON(retorno);

                $('#upload1 .percentage').hide();

                // se houve erro
                if (retorno.error == null) {
                    $('#library-tab1').trigger('click');
                } else {
                    $('#upload1 .error').text(retorno.error).show();
                    return false;
                }
            });
            this.on('uploadprogress', function (file, percentage, bytesSent) {
                $('#upload1 .error').hide();
                $('#upload1 .percentage').show().text(Math.round(percentage) + '%');
            });
        }
    });
	

	$('#library-tab1').on('click', function(e){ 
		e.preventDefault();
		var cliente_id = $('#modal_cliente_id').val();
		$.ajax({
			type: "POST",
			dataType: "html",
			url : baseUrl+"/post/ajax_insertLibrary",
			data : { 'cliente_id': cliente_id },
			success : function(data){	
					$('#library1').html(data);

					// jscroll na galeria
					var thiss = $('#library-tab1');
					var id = thiss.attr('href');
					var info = $(id).find('.gallery_info');
					var scroll = $(id).find('.gallery_scroll');
					var h = info.height();
					scroll.css('height', h);
					scroll.jScrollPane({showArrows: true,});
					scroll.css('width','');
					
					var w = $('#library1 .jspContainer').width() - 1;
					$('#library1 .jspContainer').css('width',w); 
					// fim jscroll na galeria
			}
		});
	});

    $('#modal-midias').on('click', '#library1 .lista-cada-imagem', function (e) {
        $('.limpaselect').trigger('click');

        $(this).parent().children('.selected-frame').toggleClass('hide');
        var nselect = $('#library1 .selected-frame:not(.hide)').length;
        $('#library1 .libraryselected').html(nselect);

            var imgUrl = $(this).data('imageurl');
            var imgDetail = $('#library1 img.detail');
            imgDetail.attr('src', imgUrl);
            var detailName = imgDetail.attr('src').split('/');
            var detailName = detailName[detailName.length - 1];
            var detailSize = $(this).data('filesize');
            var detailTime = $(this).data('data');
            var detailWidth = imgDetail.get(0).naturalWidth;
            var detailHeight = imgDetail.get(0).naturalHeight;

            $('#library1 .detailName').text('- ' + detailName);
            $('#library1 .detailSize').text('- ' + detailSize);
            $('#library1 .detailTime').text('- ' + detailTime);
            $('#library1 .detailWidth').text(detailWidth);
            $('#library1 .detailHeight').text(detailHeight);
            $('#library1 input[name="url_imagem"]').val(imgUrl);
            $('#library1 .library_url_espec').val(imgUrl);            

    });

    $('#modal-midias').on('click','#library1 a.inserir', function(e){
        var url_imagem = $('#library1 .library_url_imagem').val();
        if(!url_imagem){
            alert('Selecione uma imagem.');
            return false;
        }

        var titulo = $('#library1 .library_titulo').val();
        var descricao = $('#library1 .library_img_desc').val();
        //var alinhamento = $('#library1 .library_alignment').val();
        var alinhamento = '';
        var linkar_para = $('#library1 .library_url_espec').val();

        htmlInserir = "<a href='"+linkar_para+"' class='"+alinhamento+"' style='max-width: 100%;' ><img src='"+url_imagem+"' title='"+titulo+"' alt='"+descricao+"' style='max-width: 100%;' ></a> &nbsp; "; 

        $('textarea.editavel').redactor('selection.restore');
        $('textarea.editavel').redactor('insert.html',htmlInserir, false);

        $('#modal-midias').toggleClass('hide');
    });

   
    
    // CRIAR GALERIA LIBRARY 2
    // CRIAR GALERIA LIBRARY 2
    // CRIAR GALERIA LIBRARY 2
    // CRIAR GALERIA LIBRARY 2
    // CRIAR GALERIA LIBRARY 2
    // CRIAR GALERIA LIBRARY 2
    // CRIAR GALERIA LIBRARY 2
    // CRIAR GALERIA LIBRARY 2
    //===========================================================================================================================
    $("#createUploadDropzone").dropzone({
        url: $('#createUploadDropzone').attr('action'),
        paramName: "image",
        clickable: '#createUploadDropzone .dropzone-clickable',
        acceptedFiles: 'image/*',
        previewTemplate: '<div style="display:none"></div>',
        init: function () {
            this.on("success", function (file, retorno) {
                retorno = jQuery.parseJSON(retorno);

                $('#upload2 .percentage').hide();

                // se houve erro
                if (retorno.error == null) {
                    $('#library-tab2').trigger('click');
                } else {
                    $('#upload2 .error').text(retorno.error).show();
                    return false;
                }
            });
            this.on('uploadprogress', function (file, percentage, bytesSent) {
                $('#upload2 .error').hide();
                $('#upload2 .percentage').show().text(Math.round(percentage) + '%');
            });
        }
    });

    $('#library-tab2').on('click', function (e) {
        //e.preventDefault();
        $('.limpaselect').trigger('click');
        var cliente_id = $('#modal_cliente_id').val();
        $.ajax({
            type: "POST",
            dataType: "html",
            url: baseUrl + "/post/ajax_createLibrary",
            data: {'cliente_id': cliente_id},
            success: function (data) {
                $('#library2').html(data);

                // jscroll na galeria
                var thiss = $('#library-tab2');
                var id = thiss.attr('href');
                var info = $(id).find('.gallery_info');
                var scroll = $(id).find('.gallery_scroll');
                var h = info.height();
                scroll.css('height', h);
                scroll.jScrollPane({showArrows: true, });
                scroll.css('width', '');

                var w = $('#library2 .jspContainer').width() - 1;
                $('#library2 .jspContainer').css('width', w);
                // fim jscroll na galeria
            }
        });
    });

    $('#modal-midias').on('click', '#criar_galeria', function (e) {

        var totalImagens = $('#library2 .selected-frame:not(.hide)').length;
        if(!(totalImagens > 1)) {
            alert('Selecione no mínimo duas Imagens.');
            return false;
        }

        var date = Date.now();
        var images='', isso;

        var galeria = '<br ><br>\
                    <hr class="galeria-separador galeria-separador-top"><div style="display:none!important" contenteditable="false" unselectable="true">InicioDeGaleria</div> <div class="post-galeria-posicao" contenteditable="false" unselectable="true"> <ul class="post-galeria">';

                        $('#library2 .selected-frame:not(.hide)').each(function () {
                            isso = $(this).parent().children('.lista-cada-imagem');
                            //images += "<img rel='" + date + "' src='" + isso.data('imageurl') + "'>";

                            galeria += '<li data-thumb="' + isso.data('imageurl') + '">\
                                            <img src="' + isso.data('imageurl') + '" />\
                                        </li>';
                        });

            galeria += '</ul>\
                    </div><div style="display:none!important" contenteditable="false" unselectable="true">FimDeGaleria</div>\
                    <hr class="galeria-separador galeria-separador-bottom">';

        //$('textarea.editavel').wysiwyg('focus').wysiwyg('insertHtml', galeria);
        $('textarea.editavel').redactor('selection.restore');
        $('textarea.editavel').redactor('insert.html',galeria, false);

        $('#modal-midias').toggleClass('hide');
    });

    $('#modal-midias').on('click', '#library2 .lista-cada-imagem,#library2 .selected-frame', function (e) {
        
        
        $(this).parent().children('.selected-frame').toggleClass('hide');
        var nselect = $('#library2 .selected-frame:not(.hide)').length;
        $('#library2 .libraryselected').html(nselect);


        if(!$(this).parent().children('.selected-frame').hasClass('hide')){
            var imgUrl = $(this).data('imageurl');
            var imgDetail = $('#library2 img.detail');
            imgDetail.attr('src', imgUrl);
            var detailName = imgDetail.attr('src').split('/');
            var detailName = detailName[detailName.length - 1];
            var detailSize = $(this).data('filesize');
            var detailTime = $(this).data('data');
            var detailWidth = imgDetail.get(0).naturalWidth;
            var detailHeight = imgDetail.get(0).naturalHeight;

            $('#library2 .detailName').text('- ' + detailName);
            $('#library2 .detailSize').text('- ' + detailSize);
            $('#library2 .detailTime').text('- ' + detailTime);
            $('#library2 .detailWidth').text(detailWidth);
            $('#library2 .detailHeight').text(detailHeight);
            $('#library2 input[name="url_imagem"]').val(imgUrl);
        }
    });

    //END CRIAR GALERIA LIBRARY 2
    //END CRIAR GALERIA LIBRARY 2
    //END CRIAR GALERIA LIBRARY 2
    //END CRIAR GALERIA LIBRARY 2
    //END CRIAR GALERIA LIBRARY 2
    //END CRIAR GALERIA LIBRARY 2
    //END CRIAR GALERIA LIBRARY 2



    // DEFINE GALERIA LIBRARY 3
    // DEFINE GALERIA LIBRARY 3
    // DEFINE GALERIA LIBRARY 3
    // DEFINE GALERIA LIBRARY 3
    // DEFINE GALERIA LIBRARY 3
    // DEFINE GALERIA LIBRARY 3
    // DEFINE GALERIA LIBRARY 3
    // DEFINE GALERIA LIBRARY 3
    //===========================================================================================================================
    $("#defineUploadDropzone").dropzone({
        url: $('#defineUploadDropzone').attr('action'),
        paramName: "image",
        clickable: '#defineUploadDropzone .dropzone-clickable',
        acceptedFiles: 'image/*',
        previewTemplate: '<div style="display:none"></div>',
        init: function () {
            this.on("success", function (file, retorno) {
                retorno = jQuery.parseJSON(retorno);

                $('#upload3 .percentage').hide();

                // se houve erro
                if (retorno.error == null) {
                    $('#library-tab3').trigger('click');
                } else {
                    $('#upload3 .error').text(retorno.error).show();
                    return false;
                }
            });
            this.on('uploadprogress', function (file, percentage, bytesSent) {
                $('#upload3 .error').hide();
                $('#upload3 .percentage').show().text(Math.round(percentage) + '%');
            });
        }
    });

    $('#library-tab3').on('click', function (e) {
        //e.preventDefault();
        $('.limpaselect').trigger('click');
        var cliente_id = $('#modal_cliente_id').val();
        $.ajax({
            type: "POST",
            dataType: "html",
            url: baseUrl + "/post/ajax_defineLibrary",
            data: {'cliente_id': cliente_id},
            success: function (data) {
                $('#library3').html(data);

                // jscroll na galeria
                var thiss = $('#library-tab3');
                var id = thiss.attr('href');
                var info = $(id).find('.gallery_info');
                var scroll = $(id).find('.gallery_scroll');
                var h = info.height();
                scroll.css('height', h);
                scroll.jScrollPane({showArrows: true, });
                scroll.css('width', '');

                var w = $('#library3 .jspContainer').width() - 1;
                $('#library3 .jspContainer').css('width', w);
                // fim jscroll na galeria
            }
        });
    });

    $('#modal-midias').on('click', '#library3 .lista-cada-imagem,#library3 .selected-frame', function (e) {
        $('.limpaselect').trigger('click');
        
        $(this).parent().children('.selected-frame').toggleClass('hide');
        var nselect = $('#library3 .selected-frame:not(.hide)').length;
        $('#library3 .libraryselected').html(nselect);


        if(!$(this).parent().children('.selected-frame').hasClass('hide')){
            var imgUrl = $(this).data('imageurl');
            var imgUri = $(this).data('imageuri');
            var imgDetail = $('#library3 img.detail');
            imgDetail.attr('src', imgUrl);
            var detailName = imgDetail.attr('src').split('/');
            var detailName = detailName[detailName.length - 1];
            var detailSize = $(this).data('filesize');
            var detailTime = $(this).data('data');
            var detailWidth = imgDetail.get(0).naturalWidth;
            var detailHeight = imgDetail.get(0).naturalHeight;


            $('#library3 .detailName').text('- ' + detailName);
            $('#library3 .detailSize').text('- ' + detailSize);
            $('#library3 .detailTime').text('- ' + detailTime);
            $('#library3 .detailWidth').text(detailWidth);
            $('#library3 .detailHeight').text(detailHeight);
            $('#library3 input[name="url_imagem"]').val(imgUrl);
            $('#library3 input[name="uri_imagem"]').val(imgUri);
        }
    });

    $('#modal-midias').on('click', '#definir_destaque_library3', function (e) {
        var url_imagem = $('#library3 .library_url_imagem').val();
        if(!url_imagem){
            alert('Selecione uma imagem.');
            return false;
        }

        var uri_imagem = $('#library3 .library_uri_imagem').val();

        var titulo = $('#library3 .library_titulo').val();
        var descricao = $('#library3 .library_img_desc').val();

        document.querySelector('input[name="definir_destaque_flag_if_from_url"]').value = '0';

        document.querySelector('input[name="definir_destaque_url"]').value = '';
        document.querySelector('input[name="definir_destaque_imagem"]').value = uri_imagem;
        document.querySelector('input[name="definir_destaque_title"]').value = titulo;
        document.querySelector('input[name="definir_destaque_desc"]').value = descricao;        
        document.querySelector('input[name="definir_destaque_linkar_para"]').value = '';
        document.querySelector('input[name="definir_destaque_alignment"]').value = '';

        document.querySelector('.imagem-destaque').src = baseUrl+'/'+uri_imagem;

        $('#modal-midias').toggleClass('hide');
    });

    //END DEFINE GALERIA LIBRARY 3
    //END DEFINE GALERIA LIBRARY 3
    //END DEFINE GALERIA LIBRARY 3
    //END DEFINE GALERIA LIBRARY 3
    //END DEFINE GALERIA LIBRARY 3
    //END DEFINE GALERIA LIBRARY 3
    //END DEFINE GALERIA LIBRARY 3
    



    // CARREGAR GALERIA AUTOMATICAMENTE AO ADICIONAR NO POST
    var lightSliderInstance = new Array();
    setInterval(function(){ 
        if($('.post-galeria').not('.lightSlider').length > 0){ 
            $.each($('.post-galeria').not('.lightSlider'), function(e){
                var temp = $(this).lightSlider({ gallery: true, adaptiveHeight: false, item: 1, loop:true, auto:false, slideMargin: 0, thumbItem: 5 }); 
                lightSliderInstance.push(temp);
            });
        } 
    }, 500);

    $(".redactor-box").keyup(function(event) {
        if (event.keyCode == 8 || event.keyCode == 46) {
            var xx;
            for (xx = 0; xx < lightSliderInstance.length; xx++) {
                lightSliderInstance[xx].refresh();
            }
        }
    });
    
});









window.addEventListener("dragover", function (e) {
    e = e || event;
    e.preventDefault();
}, false);
window.addEventListener("drop", function (e) {
    e = e || event;
    e.preventDefault();
}, false);


$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var id = $(this).attr('href');
    var info = $(id).find('.gallery_info');
    var scroll = $(id).find('.gallery_scroll');
    var h = info.height();
    scroll.css('height', h);
    scroll.jScrollPane({showArrows: true, });
    scroll.css('width', '');

    var w = $('.jspContainer').width() - 1;
    $('.jspContainer').css('width', w);
});

$('.bt-adicionar-midias, #close-media-modal').on('mousedown', function (e) {
    e.preventDefault();
    e.stopPropagation();
    $('textarea.editavel').redactor('selection.save');

    $('#modal-midias').toggleClass('hide');
    $('#insert-tab').trigger('click');
    $('#upload-tab1').trigger('click');
});

$('.bt-album-fotos').on('mousedown', function (e) {
    e.preventDefault();
    e.stopPropagation();
    $('textarea.editavel').redactor('selection.save');

    $('#modal-midias').toggleClass('hide');
    $('#create-tab').trigger('click');
    $('#upload-tab2').trigger('click');
});

$('#define-destaque').on('mousedown', function (e) {
    e.preventDefault();
    e.stopPropagation();
    $('textarea.editavel').redactor('selection.save');

    $('#modal-midias').toggleClass('hide');
    $('#define-tab').trigger('click');
    $('#upload-tab').trigger('click');
});

var redefinir_destaque_first = 1;

$('#definir_destaque_url_').on('blur', function () {
    var src = $('#definir_destaque_url_').val();
    $('#add img').attr('src', src);

    if (src && redefinir_destaque_first == 1) {
        $('#add-img-via-url-submit').toggleClass('btn-inactive');
        $('#add-img-via-url-submit').toggleClass('btn-amarelo');
        $('.add > div:nth-of-type(3), .add > div:nth-of-type(4), .add > div:nth-of-type(5)').toggleClass('post-modal-visibility-hidden');
        redefinir_destaque_first = 0;
    }

    document.querySelector('#add #definir_destaque_linkar_para_').value=src;
});

$('#add-img-via-url-submit').on('click', function () {
    document.querySelector('input[name="definir_destaque_flag_if_from_url"]').value = '1';

    document.querySelector('input[name="definir_destaque_url"]').value = document.querySelector('.add #definir_destaque_url_').value;
    document.querySelector('input[name="definir_destaque_imagem"]').value = '';
    document.querySelector('input[name="definir_destaque_title"]').value = document.querySelector('.add #definir_destaque_title_').value;
    document.querySelector('input[name="definir_destaque_linkar_para"]').value = document.querySelector('.add #definir_destaque_linkar_para_').value;
    document.querySelector('input[name="definir_destaque_alignment"]').value = document.querySelector('.add #definir_destaque_alignment_').value;

    document.querySelector('.imagem-destaque').src = document.querySelector('.add #definir_destaque_url_').value;

    $('#modal-midias').toggleClass('hide');
    //$('.add input, .add select').clone().appendTo('.main.post > form').css('display', 'none');
});


$('#add-img-via-url-to-library-submit').on('click', function () {

    var cliente_id = $('#modal_cliente_id').val();
    
    var data = {
            'cliente_id': cliente_id,
            'definir_destaque_url' : document.querySelector('.add #definir_destaque_url_').value,
            'definir_destaque_title' : document.querySelector('.add #definir_destaque_title_').value,   
            'definir_destaque_linkar_para' : document.querySelector('.add #definir_destaque_linkar_para_').value,
            'definir_destaque_alignment' : document.querySelector('.add #definir_destaque_alignment_').value
    };

    $.ajax({
        type: "POST",
        dataType: "html",
        url : baseUrl+"/post/ajax_upload_viaurl",
        data : data,
        success : function(data){   
            //console.log(data);
            // carregando
            $('#insert-tab').trigger('click');
            $('#library-tab1').trigger('click');
        }
    });

    
});





/*$( window ).load(function() { // ao carregar a página por completo
  $(document).trigger('textareaGrow'); // verifica tamanho altura do textarea 
});*/


/*setInterval(function(){
    $(document).trigger('textareaGrow');
},500);*/

   
    
