$.Redactor.prototype.underline=function(){return{init:function(){var e=this.button.addAfter("italic","underline","Underline");this.button.addCallback(e,this.underline.format)},format:function(){this.inline.format("u")}}},$(document).ready(function(){$("textarea.editavel").redactor({buttonSource:!0,cleanOnPaste:!1,replaceDivs:!1,removeDataAttr:!1,preSpaces:4,allowedTags:["p","h1","h2","pre","div","span","ul","li","hr","img","a","strong","b","em","i","u","del","br","iframe"],allowedAttr:[["p","class"],["span","style"],["hr","class"],["ul","class"],["iframe",["src","style","width","height","allowfullscreen"]],["li","data-thumb"],["div",["unselectable","style","contenteditable","class","contenteditable"]],["img",["src","style","rel","width","height"]]],buttonsHide:["unorderedlist","orderedlist","outdent","indent"],plugins:["underline","video","fontsize","fontcolor"],imageEditable:!0,imageLink:!0,imagePosition:!0}),$(".bg-textarea-editavel-toolbar.bt-bold").mousedown(function(e){e.preventDefault(),e.stopPropagation(),$(".re-bold").trigger("click")}),$(".bg-textarea-editavel-toolbar.bt-italic").mousedown(function(e){e.preventDefault(),e.stopPropagation(),$(".re-italic").trigger("click")}),$(".bg-textarea-editavel-toolbar.bt-underline").mousedown(function(e){e.preventDefault(),e.stopPropagation(),$(".re-underline").trigger("click")}),$(".bg-textarea-editavel-toolbar.bt-strikeThrough").mousedown(function(e){e.preventDefault(),e.stopPropagation(),$(".re-deleted").trigger("click")}),$(".bg-textarea-editavel-toolbar.bt-justifyLeft").mousedown(function(e){e.preventDefault(),e.stopPropagation(),$(".re-alignment").trigger("click"),$(".redactor-dropdown-left").trigger("click")}),$(".bg-textarea-editavel-toolbar.bt-justifyCenter").mousedown(function(e){e.preventDefault(),e.stopPropagation(),$(".re-alignment").trigger("click"),$(".redactor-dropdown-center").trigger("click")}),$(".bg-textarea-editavel-toolbar.bt-justifyRight").mousedown(function(e){e.preventDefault(),e.stopPropagation(),$(".re-alignment").trigger("click"),$(".redactor-dropdown-right").trigger("click")}),$(".bt-fontSize").on("change",function(e){$(".re-fontsize").trigger("click"),$(".redactor-dropdown-"+$(this).val()).trigger("click"),$(this).val("")}),$(".bt-fontColor").mousedown(function(e){e.preventDefault(),e.stopPropagation(),$(".re-fontcolor").trigger("click"),$(".redactor-dropdown-box-fontcolor").detach().appendTo(".toolbar-to-drop"),$(".redactor-dropdown-box-fontcolor").css({top:"60px",left:"0"})}),$(".bt-fontColor").click(function(e){e.preventDefault(),e.stopPropagation()}),$("#btn-adicionar-categoria").on("click",function(e){e.preventDefault();var t=[],a=categorias_select;for(var r in a)a.hasOwnProperty(r)&&(t[r]=a[r]);categoria_options="",$.each(t,function(e,t){"undefined"!=typeof t&&(categoria_options+="<option value='"+e+"'>"+t+"</option>")});var i="<div class='categoria-grupo'><br><a class='btn btn-cinza btn-extra-pequeno align-center float-right mb-10 btn-remover-categoria'>REMOVER</a><br><select class='form-control arrow-preto-amarelo onChangeAlteraSubcategoria' name='categoria_id[]'><option value='' selected>Selecione a Categoria</option>"+categoria_options+"</select><br><select class='form-control arrow-preto-amarelo selectSubcategorias hide' name='subcategoria_id[]'><option value='' selected>Selecione a Categoria</option></select><br></div><!-- /.categoria-grupo -->";$(i).insertBefore("#categoria-grupo-bt")}),$("body").on("click",".btn-remover-categoria",function(e){e.preventDefault(),$(this).parent().remove()}),$("body").on("change",".onChangeAlteraSubcategoria",function(e){$('input[type="submit"]').attr("disabled","disabled"),tthis=$(this);var t=$(this).val();e.preventDefault(),$.ajax({type:"POST",dataType:"json",url:baseUrl+"/post/ajaxSubcategoria",data:{categoria_id:t},success:function(e){if(""==e){var t=tthis.parent().find(".selectSubcategorias");return t.empty(),t.append("<option value=''></option>"),t.addClass("hide"),$('input[type="submit"]').removeAttr("disabled"),!0}var t=tthis.parent().find(".selectSubcategorias");t.removeClass("hide");var a="";a+='<option value="">Selecione a subcategoria</option>',$.each(e,function(e,t){a+="<option value="+e+">"+t+"</option>"}),t.empty(),t.append(a),"undefined"!=typeof old_subcategoria_id&&old_subcategoria_id>0&&t.find("option").each(function(){if(this.value==old_subcategoria_id)return t.val(old_subcategoria_id),!1}),$('input[type="submit"]').removeAttr("disabled")}})}),"undefined"!=typeof old_subcategoria_id&&(old_subcategoria_id>0||null==old_subcategoria_id)&&$("#onChangeAlteraSubcategoria").trigger("change"),$("#btn-editar").on("click",function(e){$(this).hide(),$("#publicar-texto").html("PUBLICAR EM"),$("#publicar-em-obrigatorio").val("1"),$(this).parent().find(".div-publicar-em-child").slideDown()}),$("#modal-midias").on("click",".limpaselect ",function(e){$(".selected-frame:not(.hide)").addClass("hide");var t=$(".selected-frame:not(.hide)").length;$(".libraryselected").html(t)}),Dropzone.autoDiscover=!1,$("#insertUploadDropzone").dropzone({url:$("#insertUploadDropzone").attr("action"),paramName:"image",clickable:"#insertUploadDropzone .dropzone-clickable",acceptedFiles:"image/*",previewTemplate:'<div style="display:none"></div>',init:function(){this.on("success",function(e,t){return t=jQuery.parseJSON(t),$("#upload1 .percentage").hide(),null!=t.error?($("#upload1 .error").text(t.error).show(),!1):void $("#library-tab1").trigger("click")}),this.on("uploadprogress",function(e,t,a){$("#upload1 .error").hide(),$("#upload1 .percentage").show().text(Math.round(t)+"%")})}}),$("#library-tab1").on("click",function(e){e.preventDefault();var t=$("#modal_cliente_id").val();$.ajax({type:"POST",dataType:"html",url:baseUrl+"/post/ajax_insertLibrary",data:{cliente_id:t},success:function(e){$("#library1").html(e);var t=$("#library-tab1"),a=t.attr("href"),r=$(a).find(".gallery_info"),i=$(a).find(".gallery_scroll"),l=r.height();i.css("height",l),i.jScrollPane({showArrows:!0}),i.css("width","");var o=$("#library1 .jspContainer").width()-1;$("#library1 .jspContainer").css("width",o)}})}),$("#modal-midias").on("click","#library1 .lista-cada-imagem",function(e){$(".limpaselect").trigger("click"),$(this).parent().children(".selected-frame").toggleClass("hide");var t=$("#library1 .selected-frame:not(.hide)").length;$("#library1 .libraryselected").html(t);var a=$(this).data("imageurl"),r=$("#library1 img.detail");r.attr("src",a);var i=r.attr("src").split("/"),i=i[i.length-1],l=$(this).data("filesize"),o=$(this).data("data"),n=r.get(0).naturalWidth,d=r.get(0).naturalHeight;$("#library1 .detailName").text("- "+i),$("#library1 .detailSize").text("- "+l),$("#library1 .detailTime").text("- "+o),$("#library1 .detailWidth").text(n),$("#library1 .detailHeight").text(d),$('#library1 input[name="url_imagem"]').val(a),$("#library1 .library_url_espec").val(a)}),$("#modal-midias").on("click","#library1 a.inserir",function(e){var t=$("#library1 .library_url_imagem").val();if(!t)return alert("Selecione uma imagem."),!1;var a=$("#library1 .library_titulo").val(),r=$("#library1 .library_img_desc").val(),i="",l=$("#library1 .library_url_espec").val();htmlInserir="<a href='"+l+"' class='"+i+"' style='max-width: 100%;' ><img src='"+t+"' title='"+a+"' alt='"+r+"' style='max-width: 100%;' ></a> &nbsp; ",$("textarea.editavel").redactor("selection.restore"),$("textarea.editavel").redactor("insert.html",htmlInserir,!1),$("#modal-midias").toggleClass("hide")}),$("#createUploadDropzone").dropzone({url:$("#createUploadDropzone").attr("action"),paramName:"image",clickable:"#createUploadDropzone .dropzone-clickable",acceptedFiles:"image/*",previewTemplate:'<div style="display:none"></div>',init:function(){this.on("success",function(e,t){return t=jQuery.parseJSON(t),$("#upload2 .percentage").hide(),null!=t.error?($("#upload2 .error").text(t.error).show(),!1):void $("#library-tab2").trigger("click")}),this.on("uploadprogress",function(e,t,a){$("#upload2 .error").hide(),$("#upload2 .percentage").show().text(Math.round(t)+"%")})}}),$("#library-tab2").on("click",function(e){$(".limpaselect").trigger("click");var t=$("#modal_cliente_id").val();$.ajax({type:"POST",dataType:"html",url:baseUrl+"/post/ajax_createLibrary",data:{cliente_id:t},success:function(e){$("#library2").html(e);var t=$("#library-tab2"),a=t.attr("href"),r=$(a).find(".gallery_info"),i=$(a).find(".gallery_scroll"),l=r.height();i.css("height",l),i.jScrollPane({showArrows:!0}),i.css("width","");var o=$("#library2 .jspContainer").width()-1;$("#library2 .jspContainer").css("width",o)}})}),$("#modal-midias").on("click","#criar_galeria",function(e){var t=$("#library2 .selected-frame:not(.hide)").length;if(!(t>1))return alert("Selecione no mínimo duas Imagens."),!1;var a,r=(Date.now(),'<br ><br>                    <hr class="galeria-separador galeria-separador-top"><div style="display:none!important" contenteditable="false" unselectable="true">InicioDeGaleria</div> <div class="post-galeria-posicao" contenteditable="false" unselectable="true"> <ul class="post-galeria">');$("#library2 .selected-frame:not(.hide)").each(function(){a=$(this).parent().children(".lista-cada-imagem"),r+='<li data-thumb="'+a.data("imageurl")+'">                                            <img src="'+a.data("imageurl")+'" />                                        </li>'}),r+='</ul>                    </div><div style="display:none!important" contenteditable="false" unselectable="true">FimDeGaleria</div>                    <hr class="galeria-separador galeria-separador-bottom">',$("textarea.editavel").redactor("selection.restore"),$("textarea.editavel").redactor("insert.html",r,!1),$("#modal-midias").toggleClass("hide")}),$("#modal-midias").on("click","#library2 .lista-cada-imagem,#library2 .selected-frame",function(e){$(this).parent().children(".selected-frame").toggleClass("hide");var t=$("#library2 .selected-frame:not(.hide)").length;if($("#library2 .libraryselected").html(t),!$(this).parent().children(".selected-frame").hasClass("hide")){var a=$(this).data("imageurl"),r=$("#library2 img.detail");r.attr("src",a);var i=r.attr("src").split("/"),i=i[i.length-1],l=$(this).data("filesize"),o=$(this).data("data"),n=r.get(0).naturalWidth,d=r.get(0).naturalHeight;$("#library2 .detailName").text("- "+i),$("#library2 .detailSize").text("- "+l),$("#library2 .detailTime").text("- "+o),$("#library2 .detailWidth").text(n),$("#library2 .detailHeight").text(d),$('#library2 input[name="url_imagem"]').val(a)}}),$("#defineUploadDropzone").dropzone({url:$("#defineUploadDropzone").attr("action"),paramName:"image",clickable:"#defineUploadDropzone .dropzone-clickable",acceptedFiles:"image/*",previewTemplate:'<div style="display:none"></div>',init:function(){this.on("success",function(e,t){return t=jQuery.parseJSON(t),$("#upload3 .percentage").hide(),null!=t.error?($("#upload3 .error").text(t.error).show(),!1):void $("#library-tab3").trigger("click")}),this.on("uploadprogress",function(e,t,a){$("#upload3 .error").hide(),$("#upload3 .percentage").show().text(Math.round(t)+"%")})}}),$("#library-tab3").on("click",function(e){$(".limpaselect").trigger("click");var t=$("#modal_cliente_id").val();$.ajax({type:"POST",dataType:"html",url:baseUrl+"/post/ajax_defineLibrary",data:{cliente_id:t},success:function(e){$("#library3").html(e);var t=$("#library-tab3"),a=t.attr("href"),r=$(a).find(".gallery_info"),i=$(a).find(".gallery_scroll"),l=r.height();i.css("height",l),i.jScrollPane({showArrows:!0}),i.css("width","");var o=$("#library3 .jspContainer").width()-1;$("#library3 .jspContainer").css("width",o)}})}),$("#modal-midias").on("click","#library3 .lista-cada-imagem,#library3 .selected-frame",function(e){$(".limpaselect").trigger("click"),$(this).parent().children(".selected-frame").toggleClass("hide");var t=$("#library3 .selected-frame:not(.hide)").length;if($("#library3 .libraryselected").html(t),!$(this).parent().children(".selected-frame").hasClass("hide")){var a=$(this).data("imageurl"),r=$(this).data("imageuri"),i=$("#library3 img.detail");i.attr("src",a);var l=i.attr("src").split("/"),l=l[l.length-1],o=$(this).data("filesize"),n=$(this).data("data"),d=i.get(0).naturalWidth,s=i.get(0).naturalHeight;$("#library3 .detailName").text("- "+l),$("#library3 .detailSize").text("- "+o),$("#library3 .detailTime").text("- "+n),$("#library3 .detailWidth").text(d),$("#library3 .detailHeight").text(s),$('#library3 input[name="url_imagem"]').val(a),$('#library3 input[name="uri_imagem"]').val(r)}}),$("#modal-midias").on("click","#definir_destaque_library3",function(e){var t=$("#library3 .library_url_imagem").val();if(!t)return alert("Selecione uma imagem."),!1;var a=$("#library3 .library_uri_imagem").val(),r=$("#library3 .library_titulo").val(),i=$("#library3 .library_img_desc").val();document.querySelector('input[name="definir_destaque_flag_if_from_url"]').value="0",document.querySelector('input[name="definir_destaque_url"]').value="",document.querySelector('input[name="definir_destaque_imagem"]').value=a,document.querySelector('input[name="definir_destaque_title"]').value=r,document.querySelector('input[name="definir_destaque_desc"]').value=i,document.querySelector('input[name="definir_destaque_linkar_para"]').value="",document.querySelector('input[name="definir_destaque_alignment"]').value="",document.querySelector(".imagem-destaque").src=baseUrl+"/"+a,$("#modal-midias").toggleClass("hide")});var e=new Array;setInterval(function(){$(".post-galeria").not(".lightSlider").length>0&&$.each($(".post-galeria").not(".lightSlider"),function(t){var a=$(this).lightSlider({gallery:!0,adaptiveHeight:!1,item:1,loop:!0,auto:!1,slideMargin:0,thumbItem:5});e.push(a)})},500),$(".redactor-box").keyup(function(t){if(8==t.keyCode||46==t.keyCode){var a;for(a=0;a<e.length;a++)e[a].refresh()}})}),window.addEventListener("dragover",function(e){e=e||event,e.preventDefault()},!1),window.addEventListener("drop",function(e){e=e||event,e.preventDefault()},!1),$('a[data-toggle="tab"]').on("shown.bs.tab",function(e){var t=$(this).attr("href"),a=$(t).find(".gallery_info"),r=$(t).find(".gallery_scroll"),i=a.height();r.css("height",i),r.jScrollPane({showArrows:!0}),r.css("width","");var l=$(".jspContainer").width()-1;$(".jspContainer").css("width",l)}),$(".bt-adicionar-midias, #close-media-modal").on("mousedown",function(e){e.preventDefault(),e.stopPropagation(),$("textarea.editavel").redactor("selection.save"),$("#modal-midias").toggleClass("hide"),$("#insert-tab").trigger("click"),$("#upload-tab1").trigger("click")}),$(".bt-album-fotos").on("mousedown",function(e){e.preventDefault(),e.stopPropagation(),$("textarea.editavel").redactor("selection.save"),$("#modal-midias").toggleClass("hide"),$("#create-tab").trigger("click"),$("#upload-tab2").trigger("click")}),$("#define-destaque").on("mousedown",function(e){e.preventDefault(),e.stopPropagation(),$("textarea.editavel").redactor("selection.save"),$("#modal-midias").toggleClass("hide"),$("#define-tab").trigger("click"),$("#upload-tab").trigger("click")});var redefinir_destaque_first=1;$("#definir_destaque_url_").on("blur",function(){var e=$("#definir_destaque_url_").val();$("#add img").attr("src",e),e&&1==redefinir_destaque_first&&($("#add-img-via-url-submit").toggleClass("btn-inactive"),$("#add-img-via-url-submit").toggleClass("btn-amarelo"),$(".add > div:nth-of-type(3), .add > div:nth-of-type(4), .add > div:nth-of-type(5)").toggleClass("post-modal-visibility-hidden"),redefinir_destaque_first=0),document.querySelector("#add #definir_destaque_linkar_para_").value=e}),$("#add-img-via-url-submit").on("click",function(){document.querySelector('input[name="definir_destaque_flag_if_from_url"]').value="1",document.querySelector('input[name="definir_destaque_url"]').value=document.querySelector(".add #definir_destaque_url_").value,document.querySelector('input[name="definir_destaque_imagem"]').value="",document.querySelector('input[name="definir_destaque_title"]').value=document.querySelector(".add #definir_destaque_title_").value,document.querySelector('input[name="definir_destaque_linkar_para"]').value=document.querySelector(".add #definir_destaque_linkar_para_").value,document.querySelector('input[name="definir_destaque_alignment"]').value=document.querySelector(".add #definir_destaque_alignment_").value,document.querySelector(".imagem-destaque").src=document.querySelector(".add #definir_destaque_url_").value,$("#modal-midias").toggleClass("hide")}),$("#add-img-via-url-to-library-submit").on("click",function(){var e=$("#modal_cliente_id").val(),t={cliente_id:e,definir_destaque_url:document.querySelector(".add #definir_destaque_url_").value,definir_destaque_title:document.querySelector(".add #definir_destaque_title_").value,definir_destaque_linkar_para:document.querySelector(".add #definir_destaque_linkar_para_").value,definir_destaque_alignment:document.querySelector(".add #definir_destaque_alignment_").value};$.ajax({type:"POST",dataType:"html",url:baseUrl+"/post/ajax_upload_viaurl",data:t,success:function(e){$("#insert-tab").trigger("click"),$("#library-tab1").trigger("click")}})});