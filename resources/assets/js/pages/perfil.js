(function ($) { //função de serialização de objeto para pegar dados de formulários submetidos por ajax com maior facilidade
    $.fn.serializeObject = function () {

        var self = this,
                json = {},
                push_counters = {},
                patterns = {
                    "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                    "key": /[a-zA-Z0-9_]+|(?=\[\])/g,
                    "push": /^$/,
                    "fixed": /^\d+$/,
                    "named": /^[a-zA-Z0-9_]+$/
                };


        this.build = function (base, key, value) {
            base[key] = value;
            return base;
        };

        this.push_counter = function (key) {
            if (push_counters[key] === undefined) {
                push_counters[key] = 0;
            }
            return push_counters[key]++;
        };

        $.each($(this).serializeArray(), function () {

            // skip invalid keys
            if (!patterns.validate.test(this.name)) {
                return;
            }

            var k,
                    keys = this.name.match(patterns.key),
                    merge = this.value,
                    reverse_key = this.name;

            while ((k = keys.pop()) !== undefined) {

                // adjust reverse_key
                reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                // push
                if (k.match(patterns.push)) {
                    merge = self.build([], self.push_counter(reverse_key), merge);
                }

                // fixed
                else if (k.match(patterns.fixed)) {
                    merge = self.build([], k, merge);
                }

                // named
                else if (k.match(patterns.named)) {
                    merge = self.build({}, k, merge);
                }
            }

            json = $.extend(true, json, merge);
        });

        return json;
    };
})(jQuery);

$(document).on('ready', function () {


    //clique de editar troca bloco de informações por um block editável
    $('.toggle-edit-block').on('click', function (e) {
        $(this).parent().parent().find('.edit-block').slideToggle();
        $(this).parent().parent().find('.edit-bt, .info-placeholder').slideToggle();
    });




    //clique de cancelar troca bloco editável por block de informações 
    $('.cancel-edit-block').on('click', function (e) {
        $(this).parent().parent().parent().parent().find('.edit-block').slideToggle();
        $(this).parent().parent().parent().parent().find('.edit-bt, .info-placeholder').slideToggle();
    });

    //se cancela-se a edição de links pessoais, inputs criados dinâmicamente para inserção de novos valores são removidos
    $('#cancel-links').on('click', function () {
        $('.group-link-pessoal:not(.edit-block)').remove();
    });


    $(document).on('change', '.perfil .btn-file :file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });
    
    //no selecionar da imagem, já fazemos o upload dela
    $('#perfil_imagem_upload_bt :file').on('fileselect', function (event, numFiles, label) {

        $('#usuario_novo_imagem_selecionada').html(label);

        var length = 0, i = 0, postData = new FormData($('#ajaxProfile')[0]);

        $.ajax({
            type: "POST",
            url: baseUrl + "/meu-perfil/api/update/save-image",
            contentType: false,
            processData: false,
            data: postData
        }).done(function (data) {
            $('#ajax-msgs').html(''); //certificar-se de limpar o container de mensagens de ajax das operações
            if (data.code == 200) { //200 ok, mostrar mensagens de sucesso
                $('#ajax-msg').removeClass('alert-danger').addClass('alert alert-success');
                length = data.msgs.length;
                for (i; i < length; i++) {
                    $('#ajax-msgs').append('<li>' + data.msgs[i] + '</li>');
                }
                updateImage(); //chamada da função que cuida de atualizar a imagem em display pela que acaba de ser enviada
            } else { //else: qualquer outro código que não 200, implica erro na operação
                $('#ajax-msg').removeClass('alert-success').addClass('alert alert-danger');
                if (!data.msgs) { //se não temos dados, a API falhou
                    $('#ajax-msgs').append('<li>Erro interno no Servidor</li>');
                    return 0;
                }

                length = data.msgs.length;
                for (i; i < length; i++) {
                    $('#ajax-msgs').append('<li>' + data.msgs[i] + '</li>');
                }
            }
        });
    });

    //adicionar o espaço para que o usuário insira um novo links pessoal
    $('#perfil_adicionar_link_pessoal').on('click', function (e) {
        e.preventDefault();
        link_pessoal_count++; //varíavel global que trata de guardar o número atual de links pessoais
        var html = '<div class="col-sm-12"><div class="col-sm-5 pt7"><input class="form-control" name="link_pessoal['+link_pessoal_count+']" type="text"></div></div>'; //html do novo input
        var inputs = $('.group-link-pessoal > div');
        inputs.eq( inputs.length - 2).after(html); //inserindo o novo input logo antes dos botões de salvar alteração
    });


    //no click do botão de salvar alterações, salvamos o que quer que tenha sido alterado
    $('.save-edit-block').on('click', function (e) {

        var mainparent = $(this).parent().parent().parent().parent(), //raíz da qual usamos muito para alterar estados de display de elementos
                length = 0, i = 0,
                postData = JSON.stringify($('#ajaxProfile').serializeObject()), //mandaremos um JSON do nosso form serializado para a nossa API
                object = $(this).attr('id'); //cada botão de salvar tem um id referente a propriedade que está alterando, usamos essa identificação para dizer a API o que estamos alterando

        $.ajax({
            type: "POST",
            url: baseUrl + "/meu-perfil/api/update/" + object,
            data: postData
        }).done(function (data) {
            $('#ajax-msgs').html('');//certificar-se de limpar o container de mensagens de ajax das operações
            if (data.code == 200) {
                $('#ajax-msg').removeClass('alert-danger').addClass('alert alert-success');
                length = data.msgs.length;
                for (i; i < length; i++) {
                    $('#ajax-msgs').append('<li>' + data.msgs[i] + '</li>');
                }
                //em caso de sucesso da operação queremos esconder novamente o bloco de edição
                mainparent.find('.edit-block').slideToggle();
                mainparent.find('.edit-bt, .info-placeholder').slideToggle();
                
                switch (object) { //de acordo com o objeto chamamos a função encarregada de fazer com que o DOM reflita as mudanças recém concretizadas pelo back-end
                    case 'save-username':
                        updateUsername();
                        break;
                    case 'save-email':
                        updateEmail();
                        break;
                    case 'save-personal-links':
                        updatePersonalLinks();
                        break;
                    case 'save-names':
                        updateNames();
                        break;
                    default:
                        alert(object);
                }

            } else {//else: qualquer outro código que não 200, implica erro na operação
                $('#ajax-msg').removeClass('alert-success').addClass('alert alert-danger');

                if (!data.msgs) {//se não temos dados, a API falhou
                    $('#ajax-msgs').append('<li>Erro interno no Servidor</li>');
                    return 0;
                }

                length = data.msgs.length;
                for (i; i < length; i++) {
                    $('#ajax-msgs').append('<li>' + data.msgs[i] + '</li>');
                }
            }
        });
    });

});













//funções simples de atualização do DOM
//para informação sobre como a API retorna esses dados
//referenciar a mesma.

function updateImage() {
    
    $.ajax({
        type: "POST", url: baseUrl + "/meu-perfil/api/get/image"
    }).done(function (data) {
        $('.img-perfil').attr('src', data);
    });
}

function updateNames() {
    
    $.ajax({
        type: "POST", url: baseUrl + "/meu-perfil/api/get/names"
    }).done(function (data) {
        $('#label-names').html(data.firstname + ' ' + data.lastname);
    });
}

function updateUsername() {
    
    $.ajax({
        type: "POST", url: baseUrl + "/meu-perfil/api/get/username"
    }).done(function (data) {
        $('#label-username').html(data);
    });
}

function updatePersonalLinks() {
    var  length = 0, i = 0;
    $.ajax({
        type: "POST", url: baseUrl + "/meu-perfil/api/get/personal-links"
    }).done(function (data) {
        length = data.length;
         $('#label-personal-links').html('');
        for (i; i < length; i++) {
            $('#label-personal-links').append(data[i] + '<br/>');
        }
    });
}

function updateEmail() {
    
    $.ajax({
        type: "POST", url: baseUrl + "/meu-perfil/api/get/email"
    }).done(function (data) {
        $('#label-email').html(data);
    });
}