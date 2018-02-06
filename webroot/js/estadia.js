function estsavses(envio_status, retorno_msg, envio_data) {
    callAjax('/ajax/ajaxestsavses', {envio_status: envio_status, retorno_msg: retorno_msg,
        envio_data: envio_data}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
    });
}

//Quando clica em salvar a alocaçao
$(document).on("click", ".estquaalo", function () {
    if (!$(this).hasClass('click_disabled')) {
//$(this).addClass('click_disabled');
        var estadia_data = [];
        var quarto_codigo = [];
        var quarto_tipo_codigo = [];
        //Percorre o vetor de quartos habilitados e monta os vetores de estadia, quartos tipos e quarto_codigo
        $('select[name^="quarto_codigo_alocacao[]"]:enabled').each(function () {
            estadia_data.push($('#gerdatalo_' + $(this).attr('data-linha-atual')).val());
            quarto_codigo.push($(this).val());
            quarto_tipo_codigo.push($('#gerquatip_' + $(this).attr('data-linha-atual')).val());
        });
        estquaalo($("#empresa_codigo_aloc").val(), $("#documento_numero_aloc").val(), $("#quarto_item_aloc").val(), estadia_data, quarto_codigo, 1, quarto_tipo_codigo, $("#quarto_tipo_comprado").val(), $("#gertipmot").val(), $("#gerobstit").val(), 1);
    }
});
function estquaalo(empresa_codigo, documento_numero, quarto_item, estadia_data, quarto_codigo, quarto_tipo_valida, quarto_tipo_codigo,
        quarto_tipo_comprado, motivo_codigo, motivo_texto, exibe_mensagem_confirmacao) {
    callAjax('estadias/estquaalo', {empresa_codigo: empresa_codigo, documento_numero: documento_numero, quarto_item: quarto_item, estadia_data: estadia_data, quarto_codigo: "'" + quarto_codigo + "'", quarto_tipo_valida: quarto_tipo_valida,
        quarto_tipo_codigo: quarto_tipo_codigo, quarto_tipo_comprado: quarto_tipo_comprado, motivo_codigo: motivo_codigo, motivo_texto: motivo_texto, ajax: true},
            function (html) {

                console.log(html);
                //Faz as verificações nos diferentes tipos de retorno que podem ter vindos da estquaalo
                retorno_estquaalo = JSON.parse(html);
                //Verifica se teve retorno de mensagens de confirmação
                if (retorno_estquaalo.hasOwnProperty('mensagem_quarto_tipo_diferente')) {
                    //Exibe um prompt de comando solicitando a confirmação de alocação de quarto tipo codigo diferente
                    $('#germencri_mensagem').text(retorno_estquaalo.mensagem_quarto_tipo_diferente.mensagem);
                    dialog = $('#exibe-germencri').dialog({
                        title: retorno_estquaalo.titulo_texto,
                        dialogClass: 'no_close_dialog',
                        autoOpen: false, height: 200,
                        width: 530,
                        modal: true,
                        buttons: [
                            {
                                text: retorno_estquaalo.mensagem_quarto_tipo_diferente.botao_1_texto,
                                click: function () {
                                    //Verifica se o usuario ou perfil não foi encontrado
                                    if (retorno_estquaalo.hasOwnProperty('mensagem_acesso_perfil')) {
                                        $('#germencri_mensagem').text(retorno_estquaalo.mensagem_acesso_perfil.mensagem);
                                        dialog = $('#exibe-germencri').dialog({
                                            title: retorno_estquaalo.titulo_texto,
                                            dialogClass: 'no_close_dialog',
                                            autoOpen: false, height: 200,
                                            width: 530,
                                            modal: true,
                                            buttons: [
                                                {
                                                    text: retorno_estquaalo.mensagem_acesso_perfil.botao_1_texto,
                                                    click: function () {
                                                        closeDialog();
                                                        if ($('#atual_pagina').val() !== "")
                                                            gerpagexi($('#atual_pagina').val(), 1, {});
                                                        else
                                                            window.location.href = web_root_complete + "geral/gertelpri";
                                                        $(this).dialog('close');
                                                        return;
                                                    }
                                                }]
                                        });
                                        dialog.dialog('open');
                                        return;
                                        //Se o usuário e perfil foram encontrados
                                    } else {
                                        //Se o perfil do usuário encontrado não possui permissão pra tal ação
                                        if (retorno_estquaalo.hasOwnProperty('retorno_geracever') && retorno_estquaalo.retorno_geracever != "") {
                                            $('#germencri_mensagem').text(retorno_estquaalo.mensagem_geracever.mensagem);
                                            dialog = $('#exibe-germencri').dialog({
                                                title: retorno_estquaalo.titulo_texto,
                                                dialogClass: 'no_close_dialog',
                                                autoOpen: false, height: 200,
                                                width: 530,
                                                modal: true,
                                                buttons: [
                                                    {
                                                        text: retorno_estquaalo.mensagem_geracever.botao_1_texto,
                                                        click: function () {
                                                            closeDialog();
                                                            if ($('#atual_pagina').val().indexOf('/reservas/resdocpes') !== -1) {
                                                                gerpagexi('/reservas/resdocpes', 1, {});
                                                            } else
                                                                window.location.href = web_root_complete + "geral/gertelpri";
                                                            $(this).dialog('close');

                                                            return;
                                                        }
                                                    }]
                                            });
                                            dialog.dialog('open');
                                            return;
                                            //Se o usuário possui perfil adequado para alocação de quartos de tipos diferentes do comprado (chama a estquaalo sem validação de quarto tipo
                                        } else {
                                            $(this).dialog('close');
                                            estquaalo(empresa_codigo, documento_numero, quarto_item, estadia_data, quarto_codigo, 0, quarto_tipo_codigo, quarto_tipo_comprado, motivo_codigo, motivo_texto, exibe_mensagem_confirmacao);
                                            return;
                                        }
                                    }
                                }
                            },
                            {
                                text: retorno_estquaalo.mensagem_quarto_tipo_diferente.botao_2_texto,
                                click: function () {
                                    $(this).dialog("close");
                                }
                            }
                        ]
                    });
                    dialog.dialog('open');
                    return;
                }

                //Verifica se teve retorno de inconsistencia de datas
                if (retorno_estquaalo.hasOwnProperty('mensagem_inconsistencia_data')) {

                    //Se os tipos de quartos comprados e alocados forem iguais
                    $('#germencri_mensagem').text(retorno_estquaalo.mensagem_inconsistencia_data.mensagem);
                    dialog = $('#exibe-germencri').dialog({
                        title: retorno_estquaalo.titulo_texto,
                        dialogClass: 'no_close_dialog',
                        autoOpen: false, height: 200,
                        width: 530,
                        modal: true,
                        buttons: [
                            {
                                text: retorno_estquaalo.mensagem_inconsistencia_data.botao_1_texto,
                                click: function () {
                                    closeDialog();
                                    if ($('#atual_pagina').val() !== "")
                                        gerpagexi($('#atual_pagina').val(), 1, {});
                                    else
                                        window.location.href = web_root_complete + "geral/gertelpri";
                                    $(this).dialog('close');

                                    return;
                                }
                            }]
                    });
                    dialog.dialog('open');
                    return;
                }

                if (exibe_mensagem_confirmacao || retorno_estquaalo.retorno == -1) {
                    //A exibição na respaiatu é no rodape
                    if ($('#atual_pagina').val() !== "/reservas/respaiatu") {
                        //Só exibe mensagem via dialog em insucessos
                        if (retorno_estquaalo.retorno != 1) {
                            //Se os tipos de quartos comprados e alocados forem iguais
                            $('#germencri_mensagem').text(retorno_estquaalo.mensagem.mensagem);
                            dialog = $('#exibe-germencri').dialog({
                                title: retorno_estquaalo.titulo_texto,
                                dialogClass: 'no_close_dialog',
                                autoOpen: false, height: 200,
                                width: 530,
                                modal: true,
                                buttons: [
                                    {
                                        text: retorno_estquaalo.mensagem.botao_1_texto,
                                        click: function () {

                                            //Se deu indisponibilidade de quartos, atualiza o dialog
                                            if (retorno_estquaalo.retorno == -1) {
                                                closeDialog();
                                                if ($('#atual_pagina').val() !== "")
                                                    gerpagexi($('#atual_pagina').val(), 1, {});
                                                else
                                                    window.location.href = web_root_complete + "geral/gertelpri";
                                                $(this).dialog('close');
                                                /*callAjax('/geral/gerquadis', {empresa_codigo: $("#empresa_codigo_aloc").val(), datas: $("#datas_aloc").val(),
                                                 quarto_tipo_codigo: $("#estquaalo #gerquatip").val()},
                                                 function (html) {
                                                 //Necessário codigo para atualizar o dropdown com os aquartos disponiveis na chamada anterior
                                                 
                                                 $('#exibe-germencri').dialog('close');
                                                 });*/
                                                return;
                                            } else {
                                                closeDialog();
                                                if ($('#atual_pagina').val() !== "")
                                                    gerpagexi($('#atual_pagina').val(), 1, {});
                                                else
                                                    window.location.href = web_root_complete + "geral/gertelpri";
                                                $(this).dialog('close');
                                            }

                                        }
                                    }]
                            });
                            dialog.dialog('open');
                            return;
                            //Se a mensagem for de sucesso apenas recarrega
                        } else {
                            closeDialog();
                            gerpagexi($('#atual_pagina').val(), 1, {});
                        }
                    } else {
                        console.log('202');
                        $("#janela_atual").val(0);
                        $('#respaiatu_submit').click();
                    }
                    //Se não for pra exibir mensagem de alocação (ex:checkin) cancela a variável retorno_footer
                } else {
                    callAjax('ajax/ajaxgermendel', {}, function (html) {
                    });
                }
            });
}

function estchrcri() {
//Faz a validação do form
    var errors = [],
            conf = {
                onElementValidate: function (valid, $el, $form, errorMess) {
                    if (!valid) {
                        // gather up the failed validations
                        errors.push({el: $el, error: errorMess});
                    }
                }
            },
            lang = 'pt';
    if (!$("#estchicri").isValid(conf, false)) {
        $('.error').first().focus();
        return false;
    } else {
        callAjax('ajax/ajaxgerpagsal', {form: $("#" + $('#form_atual').val()).serialize(), back_page: $('#atual_pagina').val()}, function (html) {
        });
        callAjax('estadias/estchicri', {empresa_codigo: $("#gerempcod").val(), documento_numero: $('#documento_numero_checkin').val(), quarto_item: $('#quarto_item_checkin').val(), ajax: true},
                function (html) {
                    var retorno_estchicri = JSON.parse(html);
                    //Verifica se teve retorno de mensagens valor menor
                    if (retorno_estchicri.hasOwnProperty('mensagem_checkin_valor_menor')) {
                        //Exibe um prompt de comando solicitando a confirmação de valor menor
                        $('#germencri_mensagem').text(retorno_estchicri.mensagem_checkin_valor_menor.mensagem);
                        dialog = $('#exibe-germencri').dialog({
                            title: retorno_estchicri.mensagem_checkin_valor_menor.titulo_texto,
                            dialogClass: 'no_close_dialog',
                            autoOpen: false, height: 200,
                            width: 530,
                            modal: true,
                            buttons: [
                                {
                                    text: retorno_estchicri.mensagem_checkin_valor_menor.botao_1_texto,
                                    click: function () {
                                        $(this).dialog('close');
                                    }
                                },
                                {
                                    text: retorno_estchicri.mensagem_checkin_valor_menor.botao_2_texto,
                                    click: function () {
                                        //Se o perfil do usuário encontrado não possui permissão pra tal ação
                                        if (retorno_estchicri.hasOwnProperty('retorno_geracever') && retorno_estchicri.retorno_geracever != "") {
                                            $('#germencri_mensagem').text(retorno_estchicri.retorno_geracever);
                                            dialog = $('#exibe-germencri').dialog({
                                                title: '',
                                                dialogClass: 'no_close_dialog',
                                                autoOpen: false, height: 200,
                                                width: 530,
                                                modal: true,
                                                buttons: [
                                                    {
                                                        text: 'Ok',
                                                        click: function () {
                                                            $(this).dialog('close');
                                                            closeDialog();
                                                            gerpagexi($('#atual_pagina').val(), 1, {});
                                                        }
                                                    }]
                                            });
                                            dialog.dialog('open');
                                            //Se o usuário possui perfil adequado para checkin com valores pagos menores
                                        } else {
                                            callAjax('estadias/estchicri', {empresa_codigo: $("#gerempcod").val(), documento_numero: $('#documento_numero_checkin').val(), quarto_item: $('#quarto_item_checkin').val(),
                                                ajax: true, validado: 1},
                                                    function (html) {
                                                        var retorno_estchicri = JSON.parse(html);
                                                        //Só exibe mensagem via dialog se exibou erro
                                                        if (retorno_estchicri.retorno == 0) {
                                                            $('#germencri_mensagem').text(retorno_estchicri.mensagem);
                                                            dialog = $('#exibe-germencri').dialog({
                                                                title: '',
                                                                dialogClass: 'no_close_dialog',
                                                                autoOpen: false, height: 200,
                                                                width: 530,
                                                                modal: true,
                                                                buttons: [
                                                                    {
                                                                        text: 'Ok',
                                                                        click: function () {
                                                                            if (retorno_estchicri.retorno == 0)
                                                                                $(this).dialog('close');
                                                                            else {
                                                                                $(this).dialog('close');
                                                                                closeDialog();
                                                                                gerpagexi($('#atual_pagina').val(), 1, {});
                                                                            }
                                                                        }
                                                                    }]
                                                            });
                                                            dialog.dialog('open');
                                                        } else {
                                                            $('#exibe-germencri').dialog('close');
                                                            closeDialog();
                                                            gerpagexi($('#atual_pagina').val(), 1, {});
                                                        }
                                                    });
                                        }
                                    }
                                }
                            ]
                        });
                        dialog.dialog('open');
                    } else {
                        //Só exibe mensagem via dialog se exibou erro
                        if (retorno_estchicri.retorno == 0) {
                            $('#germencri_mensagem').text(retorno_estchicri.mensagem);
                            dialog = $('#exibe-germencri').dialog({
                                title: '',
                                dialogClass: 'no_close_dialog',
                                autoOpen: false, height: 200,
                                width: 530,
                                modal: true,
                                buttons: [
                                    {
                                        text: 'Ok',
                                        click: function () {
                                            $(this).dialog('close');
                                            closeDialog();
                                            gerpagexi($('#atual_pagina').val(), 1, {});
                                        }
                                    }]
                            });
                            dialog.dialog('open');
                            //Recarrega a pagina com a mensagem de sucesso gravada em sessão
                        } else {
                            closeDialog();
                            gerpagexi($('#atual_pagina').val(), 1, {});
                        }
                    }
                });
    }
}

function estchocri() {
    callAjax('ajax/ajaxgerpagsal', {form: $("#" + $('#form_atual').val()).serialize(), back_page: $('#atual_pagina').val()}, function (html) {
    });
    //Faz a validação do form
    var errors = [],
            conf = {
                onElementValidate: function (valid, $el, $form, errorMess) {
                    if (!valid) {
                        errors.push({el: $el, error: errorMess});
                    }
                }
            },
            lang = 'pt';
    if (!$("#estchocri").isValid(conf, false)) {
        $('.error').first().focus();
        return false;
    } else {
        callAjax('estadias/estchocri', {form: $("#estchocri").serialize(), ajax: true},
                function (html) {
                    var retorno_estchocri = JSON.parse(html);
                    //Só exibe mensagem via dialog se exibou erro
                    if (retorno_estchocri.retorno != 1) {
                        $('#germencri_mensagem').text(retorno_estchocri.mensagem.mensagem);
                        dialog = $('#exibe-germencri').dialog({
                            title: retorno_estchocri.mensagem.titulo_texto,
                            dialogClass: 'no_close_dialog',
                            autoOpen: false, height: 200,
                            width: 530,
                            modal: true,
                            buttons: [
                                {
                                    text: retorno_estchocri.mensagem.botao_1_texto,
                                    click: function () {
                                        if (retorno_estchocri.retorno != 1)
                                            $(this).dialog('close');
                                        else {
                                            $(this).dialog('close');
                                            closeDialog();
                                            gerpagexi($('#atual_pagina').val(), 1, {});
                                        }
                                    }
                                }]
                        });
                        dialog.dialog('open');
                        //Apenas recarrega a tela ja que a mensagem de sucesso está no rodapé
                    } else {
                        closeDialog();
                        gerpagexi($('#atual_pagina').val(), 1, {});
                    }
                });
    }
}

//Imprime as fichas
function estfnrpri() {
    total_hospedes = $("#total_hospedes_checkin").val();
    //Se houve confirmação de checkin, fecha o dialog
    dialog_level_1 = $('#confirm_checkin_dialog').dialog({autoOpen: false});
    if ($('#confirm_checkin_dialog').dialog('isOpen'))
        $('#confirm_checkin_dialog').dialog('close');
    //Primeiro busca todos os clientes da reserva. É necessário, no caso de ter sido criado um novo cliente no checkin
    $.ajax({
        type: 'POST',
        url: web_root_complete + 'ajax/ajaxclicadres',
        data: {gerempcod: $("#empresa_codigo_checkin").val(), resdocnum: $("#documento_numero_checkin").val(), quarto_item: $("#quarto_item_checkin").val()},
        success: function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                vetor_clientes = html.split("|");
                for (var i = 0; i < vetor_clientes.length; i++) {
                    var $clone = $("#ficha_embratur_" + i).css("display", "block").clone().attr("id", "ficha_embratur_" + (i + 1));
                    $clone.find("#ficha_embratur_" + i).css("display", "block");
                    $.ajax({
                        type: 'POST',
                        url: web_root_complete + 'clientes/clicadexi',
                        async: false,
                        data: {cliente_codigo: vetor_clientes[i]},
                        success: function (html) {
                            if (html == 'sessao_expirada')
                                window.location.href = web_root_complete + 'geral/gertelpri';
                            else {
                                clienteJson = JSON.parse(html)[0];
                                if (clienteJson.cliprinom === null)
                                    clienteJson.cliprinom = "";
                                if (clienteJson.clisobnom == null)
                                    clienteJson.clisobnom = "";
                                if (clienteJson.clicelddi === null)
                                    clienteJson.clicelddi = "";
                                if (clienteJson.clicelnum == null)
                                    clienteJson.clicelnum = "";
                                if (clienteJson.clicadocu == null)
                                    clienteJson.clicadocu = "";
                                if (clienteJson.clicadbai == null)
                                    clienteJson.clicadbai = "";
                                if (clienteJson.clidocorg == null)
                                    clienteJson.clidocorg = "";
                                if (clienteJson.clicpfcnp == null)
                                    clienteJson.clicpfcnp = "";
                                if (clienteJson.clinacdat == null || clienteJson.clinacdat == '0000-00-00')
                                    clienteJson.clinacdat = "";
                                if (clienteJson.clicadema == null)
                                    clienteJson.clicadema = "";
                                if (clienteJson.clicadnac == null)
                                    clienteJson.clicadnac = "";
                                if (clienteJson.clidoctip == null || clienteJson.clidoctip == '')
                                    clienteJson.clidoctip = " ";
                                if (clienteJson.clidocnum == null || clienteJson.clidocnum == '')
                                    clienteJson.clidocnum = " ";
                                if (clienteJson.clicadend == null)
                                    clienteJson.clicadend = "";
                                if (clienteJson.clicadcid == null)
                                    clienteJson.clicadcid = "";
                                if (clienteJson.clicadest == null)
                                    clienteJson.clicadest = "";
                                if (clienteJson.clicadpai == null)
                                    clienteJson.clicadpai = "";
                                $clone.find("#nome_completo_" + i).attr("id", "nome_completo_" + (i + 1)).text(clienteJson.cliprinom + " " + clienteJson.clisobnom);
                                $clone.find("#telefone_" + i).attr("id", "telefone_" + (i + 1)).text(clienteJson.clitelddi + " " + clienteJson.clitelnum);
                                $clone.find("#celular_" + i).attr("id", "celular_" + (i + 1)).text(clienteJson.clicelddi + " " + clienteJson.clicelnum);
                                $clone.find("#ocupacao_" + i).attr("id", "ocupacao_" + (i + 1)).text(clienteJson.clicadocu);
                                $clone.find("#nacionalidade_" + i).attr("id", "nacionalidade_" + (i + 1)).text(clienteJson.clicadnac);
                                if (clienteJson.clinacdat != null && clienteJson.clinacdat.length > 0 && clienteJson.clinacdat != '0000-00-00') {
                                    $clone.find("#data_nascimento_" + i + "_1").attr("id", "data_nascimento_" + (i + 1) + "_1").text(clienteJson.clinacdat.substring(0, 1));
                                    $clone.find("#data_nascimento_" + i + "_2").attr("id", "data_nascimento_" + (i + 1) + "_2").text(clienteJson.clinacdat.substring(1, 2));
                                    $clone.find("#data_nascimento_" + i + "_3").attr("id", "data_nascimento_" + (i + 1) + "_3").text(clienteJson.clinacdat.substring(3, 4));
                                    $clone.find("#data_nascimento_" + i + "_4").attr("id", "data_nascimento_" + (i + 1) + "_4").text(clienteJson.clinacdat.substring(4, 5));
                                    $clone.find("#data_nascimento_" + i + "_5").attr("id", "data_nascimento_" + (i + 1) + "_5").text(clienteJson.clinacdat.substring(8, 9));
                                    $clone.find("#data_nascimento_" + i + "_6").attr("id", "data_nascimento_" + (i + 1) + "_6").text(clienteJson.clinacdat.substring(9, 10));
                                } else {
                                    $clone.find("#data_nascimento_" + i + "_1").attr("id", "data_nascimento_" + (i + 1) + "_1").text('');
                                    $clone.find("#data_nascimento_" + i + "_2").attr("id", "data_nascimento_" + (i + 1) + "_2").text('');
                                    $clone.find("#data_nascimento_" + i + "_3").attr("id", "data_nascimento_" + (i + 1) + "_3").text('');
                                    $clone.find("#data_nascimento_" + i + "_4").attr("id", "data_nascimento_" + (i + 1) + "_4").text('');
                                    $clone.find("#data_nascimento_" + i + "_5").attr("id", "data_nascimento_" + (i + 1) + "_5").text('');
                                    $clone.find("#data_nascimento_" + i + "_6").attr("id", "data_nascimento_" + (i + 1) + "_6").text('');
                                }

                                if (clienteJson.clicadcep != null && clienteJson.clicadcep.length > 0) {
                                    clienteJson.clicadcep = clienteJson.clicadcep.replace('-', '');
                                    $clone.find("#cep_" + i + "_1").attr("id", "cep_" + (i + 1) + "_1").text(clienteJson.clicadcep.substring(0, 1));
                                    $clone.find("#cep_" + i + "_2").attr("id", "cep_" + (i + 1) + "_2").text(clienteJson.clicadcep.substring(1, 2));
                                    $clone.find("#cep_" + i + "_3").attr("id", "cep_" + (i + 1) + "_3").text(clienteJson.clicadcep.substring(2, 3));
                                    $clone.find("#cep_" + i + "_4").attr("id", "cep_" + (i + 1) + "_4").text(clienteJson.clicadcep.substring(3, 4));
                                    $clone.find("#cep_" + i + "_5").attr("id", "cep_" + (i + 1) + "_5").text(clienteJson.clicadcep.substring(4, 5));
                                    $clone.find("#cep_" + i + "_6").attr("id", "cep_" + (i + 1) + "_6").text(clienteJson.clicadcep.substring(5, 6));
                                    $clone.find("#cep_" + i + "_7").attr("id", "cep_" + (i + 1) + "_7").text(clienteJson.clicadcep.substring(6, 7));
                                    $clone.find("#cep_" + i + "_8").attr("id", "cep_" + (i + 1) + "_8").text(clienteJson.clicadcep.substring(7, 8));
                                } else {
                                    $clone.find("#cep_" + i + "_1").attr("id", "cep_" + (i + 1) + "_1").text('');
                                    $clone.find("#cep_" + i + "_2").attr("id", "cep_" + (i + 1) + "_2").text('');
                                    $clone.find("#cep_" + i + "_3").attr("id", "cep_" + (i + 1) + "_3").text('');
                                    $clone.find("#cep_" + i + "_4").attr("id", "cep_" + (i + 1) + "_4").text('');
                                    $clone.find("#cep_" + i + "_5").attr("id", "cep_" + (i + 1) + "_5").text('');
                                    $clone.find("#cep_" + i + "_6").attr("id", "cep_" + (i + 1) + "_6").text('');
                                    $clone.find("#cep_" + i + "_7").attr("id", "cep_" + (i + 1) + "_7").text('');
                                    $clone.find("#cep_" + i + "_8").attr("id", "cep_" + (i + 1) + "_8").text('');
                                }

                                if (clienteJson.sexo != null) {
                                    if (clienteJson.sexo === 'M') {
                                        $clone.find("#sexo_" + i + "_1").attr("id", "sexo_" + (i + 1) + "_1").text('M');
                                    } else {
                                        $clone.find("#sexo_" + i + "_2").attr("id", "sexo_" + (i + 1) + "_2").text('F');
                                    }
                                } else {
                                    $clone.find("#sexo_" + i + "_1").attr("id", "sexo_" + (i + 1) + "_1").text('');
                                    $clone.find("#sexo_" + i + "_2").attr("id", "sexo_" + (i + 1) + "_2").text('');
                                }


                                $clone.find("#endereco_" + i).attr("id", "endereco_" + (i + 1)).text(clienteJson.clicadend);
                                $clone.find("#bairro_" + i).attr("id", "bairro_" + (i + 1)).text(clienteJson.clicadbai);
                                if (clienteJson.clidocnum != " ")
                                    $clone.find("#documento_numero_" + i).attr("id", "documento_numero_" + (i + 1)).text(clienteJson.clidocnum);
                                else {
                                    $clone.find("#documento_numero_" + i).attr("id", "documento_numero_" + (i + 1)).html("&nbsp;");
                                }
                                if (clienteJson.clidoctip != " ")
                                    $clone.find("#documento_tipo_" + i).attr("id", "documento_tipo_" + (i + 1)).text(clienteJson.clidoctip);
                                else {
                                    $clone.find("#documento_tipo_" + i).attr("id", "documento_tipo_" + (i + 1)).html("&nbsp;");
                                }
                                $clone.find("#orgao_expedidor_" + i).attr("id", "orgao_expedidor_" + (i + 1)).text(clienteJson.clidocorg);
                                $clone.find("#cpf_" + i).attr("id", "cpf_" + (i + 1)).text(clienteJson.clicpfcnp);
                                $clone.find("#email_" + i).attr("id", "email_" + (i + 1)).text(clienteJson.clicadema);
                                $clone.find("#residencia_logradouro_" + i).attr("id", "residencia_logradouro_" + (i + 1)).text(clienteJson.clicadend);
                                $clone.find("#residencia_cidade_" + i).attr("id", "residencia_cidade_" + (i + 1)).text(clienteJson.clicadcid);
                                $clone.find("#residencia_estado_" + i).attr("id", "residencia_estado_" + (i + 1)).text(clienteJson.clicadest);
                                $clone.find("#residencia_pais_" + i).attr("id", "residencia_pais_" + (i + 1)).text(clienteJson.clicadpai);
                                $clone.find("#entrada_" + i + "_1").attr("id", "entrada_" + (i + 1) + "_1").text($("#inicial_data_checkin").val().substring(8, 9));
                                $clone.find("#entrada_" + i + "_2").attr("id", "entrada_" + (i + 1) + "_2").text($("#inicial_data_checkin").val().substring(9, 10));
                                $clone.find("#entrada_" + i + "_3").attr("id", "entrada_" + (i + 1) + "_3").text($("#inicial_data_checkin").val().substring(5, 6));
                                $clone.find("#entrada_" + i + "_4").attr("id", "entrada_" + (i + 1) + "_4").text($("#inicial_data_checkin").val().substring(6, 7));
                                $clone.find("#entrada_" + i + "_5").attr("id", "entrada_" + (i + 1) + "_5").text($("#inicial_data_checkin").val().substring(2, 3));
                                $clone.find("#entrada_" + i + "_6").attr("id", "entrada_" + (i + 1) + "_6").text($("#inicial_data_checkin").val().substring(3, 4));
                                $clone.find("#entrada_" + i + "_7").attr("id", "entrada_" + (i + 1) + "_7").text($("#inicial_data_checkin").val().substring(11, 12));
                                $clone.find("#entrada_" + i + "_8").attr("id", "entrada_" + (i + 1) + "_8").text($("#inicial_data_checkin").val().substring(12, 13));
                                $clone.find("#entrada_" + i + "_9").attr("id", "entrada_" + (i + 1) + "_9").text($("#inicial_data_checkin").val().substring(14, 15));
                                $clone.find("#entrada_" + i + "_10").attr("id", "entrada_" + (i + 1) + "_10").text($("#inicial_data_checkin").val().substring(15, 16));
                                $clone.find("#saida_" + i + "_1").attr("id", "saida_" + (i + 1) + "_1").text($("#final_data_checkin").val().substring(8, 9));
                                $clone.find("#saida_" + i + "_2").attr("id", "saida_" + (i + 1) + "_2").text($("#final_data_checkin").val().substring(9, 10));
                                $clone.find("#saida_" + i + "_3").attr("id", "saida_" + (i + 1) + "_3").text($("#final_data_checkin").val().substring(5, 6));
                                $clone.find("#saida_" + i + "_4").attr("id", "saida_" + (i + 1) + "_4").text($("#final_data_checkin").val().substring(6, 7));
                                $clone.find("#saida_" + i + "_5").attr("id", "saida_" + (i + 1) + "_5").text($("#final_data_checkin").val().substring(2, 3));
                                $clone.find("#saida_" + i + "_6").attr("id", "saida_" + (i + 1) + "_6").text($("#final_data_checkin").val().substring(3, 4));
                                $clone.find("#saida_" + i + "_7").attr("id", "saida_" + (i + 1) + "_7").text($("#final_data_checkin").val().substring(11, 12));
                                $clone.find("#saida_" + i + "_8").attr("id", "saida_" + (i + 1) + "_8").text($("#final_data_checkin").val().substring(12, 13));
                                $clone.find("#saida_" + i + "_9").attr("id", "saida_" + (i + 1) + "_9").text($("#final_data_checkin").val().substring(14, 15));
                                $clone.find("#saida_" + i + "_10").attr("id", "saida_" + (i + 1) + "_10").text($("#final_data_checkin").val().substring(15, 16));
                                //Seta o numero do quarto

                                if ($('input[name=quarto_alocado]:checked', '#estchicri').length)
                                    quarto_codigo = $('input[name=quarto_alocado]:checked', '#estchicri').val();
                                else
                                    quarto_codigo = $("#quarto_alocado").val();
                                quarto_codigos = [];
                                len = quarto_codigo.length;
                                for (var j = 1; j <= len; j++) {
                                    quarto_codigos.push(+quarto_codigo.charAt(i));
                                }
                                tamanho_codigos = quarto_codigos.length;
                                for (var j = 0; j < len; j += 1) {
                                    $clone.find("#quarto_" + i + "_" + tamanho_codigos).attr("id", "quarto_" + (i + 1) + "_" + tamanho_codigos).text(quarto_codigo[j]);
                                    tamanho_codigos = tamanho_codigos - 1;
                                }

                                $clone.find("#acompanhantes_" + i).attr("id", "acompanhantes_" + (i + 1)).text(total_hospedes - 1);
                            }
                        }
                    });
                    $("#ficha_embratur_" + i).after($clone);
                }
                $("#ficha_embratur_0").remove();
                PrintElem('fichas_embratur');
            }
        },
        error: function (html) {
            console.log(html.responseText);
        }
    });
}


function estfnrmoc() {
//Verifica se houveram alterações no cadastro do cliente
    if ($("#clicadalt").val() == '1') {
        callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 47, exibicao_tipo: 1}, function (html) {
            html = JSON.parse(html);
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                $('#germencri_mensagem').text(html.mensagem);
                dialog = $('#exibe-germencri').dialog({
                    title: html.titulo_texto,
                    dialogClass: 'no_close_dialog',
                    autoOpen: false, height: 200,
                    width: 530,
                    modal: true,
                    buttons: [
                        {
                            text: html.botao_1_texto,
                            click: function () {
                                //Submete o form apos atualizar o cliente
                                $("#estfnrmod").submit();
                                $(this).dialog('close');
                            }
                        },
                        {
                            text: html.botao_2_texto,
                            click: function () {
                                codigo_cliente = $("#clicadcod").val();
                                callAjax('/clientes/ajaxclicrimod', {clicadcod: codigo_cliente, cliprinom: $("#snnomecompleto").val(),
                                    clicadema: $("#snemail").val(), clidoctip: $("#sntipdoc").val(), clidocnum: $("#snnumdoc").val(),
                                    clicpfcnp: $("#snnumcpf").val(), clicelddi: $("#sncelularddi").val(),
                                    clicelnum: $("#sncelularnum").val(), clitelddi: $("#sntelefoneddi").val(),
                                    clitelnum: $("#sntelefonenum").val(), clidocorg: $("#snorgexp").val(),
                                    clicadocu: $("#snocupacao").val(),
                                    clicadnac: $("#snnacionalidade").val(), clinacdat: $("#sndtnascimento").val(), clicadend: $("#snresidencia").val(),
                                    clicadcid: $("#sncidaderes").val(), clicadest: $("#snestadores").val(), clicadpai: $("#snpaisres").val(),
                                    clicadcep: $("#sncep").val()}, function (html) {
                                    if (html == 'sessao_expirada')
                                        window.location.href = web_root_complete + 'geral/gertelpri';
                                    else {
                                        //Submete o form apos atualizar o cliente
                                        $("#estfnrmod").submit();
                                    }
                                });
                                $(this).dialog('close');
                            }
                        }
                    ]
                });
                dialog.dialog('open');
            }
        });
    } else {
        $("#estfnrmod").submit();
    }
}

function estchionl2() {
//Verifica se algum cliente não foi visitado     var todas_abas_visitadas = true;
    $(".ui-tabs-nav li").each(function (index) {
        if (!$(this).hasClass('visitada'))
            todas_abas_visitadas = false;
    });
    //Verifica se houve alteração em clientes
    lista_fnrhs = $("#fnrhs").val().split("|");
    houve_alteracao_cliente = false;
    for (var i = 0; i < lista_fnrhs.length; i++) {
        if ($("#clicadalt_" + lista_fnrhs[i]).val() == '1' || $("#clicadalt_nome_" + lista_fnrhs[i]).val() == '1') {
            houve_alteracao_cliente = true;
        }
    }

    if (houve_alteracao_cliente) {
        for (var i = 0; i < lista_fnrhs.length; i++) {
            codigo_fnrh = lista_fnrhs[i];
            if ($("#clicadalt_" + lista_fnrhs[i]).val() == '1' && ($("#clicadalt_nome_" + codigo_fnrh).val() == '0')) {
                codigo_cliente = $("#clicadcod_" + codigo_fnrh).val();
                callAjax('/clientes/ajaxclicrimod', {empresa_grupo_codigo: $("#empresa_grupo_codigo").val(),
                    empresa_codigo: $("#empresa_codigo").val(),
                    clicadcod: codigo_cliente, cliprinom: $("#snnomecompleto_" + codigo_fnrh).val(),
                    clicadema: $("#snemail_" + codigo_fnrh).val(), clidoctip: $("#sntipdoc_" + codigo_fnrh).val(),
                    clidocnum: $("#snnumdoc_" + codigo_fnrh).val(), clicpfcnp: $("#snnumcpf_" + codigo_fnrh).val(),
                    clicelddi: $("#sncelularddi_" + codigo_fnrh).val(),
                    clicelnum: $("#sncelularnum_" + codigo_fnrh).val(), clitelddi: $("#sntelefoneddi_" + codigo_fnrh).val(),
                    clitelnum: $("#sntelefonenum_" + codigo_fnrh).val(),
                    clidocorg: $("#snorgexp_" + codigo_fnrh).val(), clicadocu: $("#snocupacao_" + codigo_fnrh).val(),
                    clicadnac: $("#snnacionalidade_" + codigo_fnrh).val(), clinacdat: $("#sndtnascimento_" + codigo_fnrh).val(),
                    clicadend: $("#snresidencia_" + codigo_fnrh).val(), clicadcid: $("#sncidaderes_" + codigo_fnrh).val(),
                    clicadest: $("#snestadores_" + codigo_fnrh).val(), clicadpai: $("#snpaisres_" + codigo_fnrh).val(),
                    clicadcep: $("#sncep_" + codigo_fnrh).val()}, function (html) {
                    if (html == 'sessao_expirada')
                        window.location.href = web_root_complete + 'geral/gertelpri';
                });
                //Se tiver que criar um novo cliente
            } else if ($("#clicadalt_nome_" + codigo_fnrh).val() == '1') {
                callAjax('/ajax/ajaxreshosatu', {empresa_grupo_codigo: $("#empresa_grupo_codigo").val(),
                    empresa_codigo: $("#empresa_codigo").val(), documento_numero: $("#documento_numero").val(),
                    contratante_codigo: $("#contratante_codigo").val(),
                    cliprinom: $("#snnomecompleto_" + codigo_fnrh).val(), clisobnom: '',
                    clicadema: $("#snemail_" + codigo_fnrh).val(), clidoctip: $("#sntipdoc_" + codigo_fnrh).val(),
                    clidocnum: $("#snnumdoc_" + codigo_fnrh).val(), clicpfcnp: $("#snnumcpf_" + codigo_fnrh).val(),
                    clicelddi: $("#sncelularddi_" + codigo_fnrh).val(),
                    clicelnum: $("#sncelularnum_" + codigo_fnrh).val(), clitelddi: $("#sntelefoneddi_" + codigo_fnrh).val(),
                    clitelnum: $("#sntelefonenum_" + codigo_fnrh).val(),
                    clidocorg: $("#snorgexp_" + codigo_fnrh).val(), clicadocu: $("#snocupacao_" + codigo_fnrh).val(),
                    clicadnac: $("#snnacionalidade_" + codigo_fnrh).val(), clinacdat: $("#sndtnascimento_" + codigo_fnrh).val(),
                    clicadend: $("#snresidencia_" + codigo_fnrh).val(), clicadcid: $("#sncidaderes_" + codigo_fnrh).val(),
                    clicadest: $("#snestadores_" + codigo_fnrh).val(), clicadpai: $("#snpaisres_" + codigo_fnrh).val(),
                    clicadcep: $("#sncep_" + codigo_fnrh).val(), hospede_codigo_anterior: $("#clicadcod_" + codigo_fnrh).val()}, function (html) {
                    if (html == 'sessao_expirada')
                        window.location.href = web_root_complete + 'geral/gertelpri';
                    else {
                        //Chama a estfnrcri para associar a fnrh ao cliente recem criado
                        codigo_cliente_criado = html.substring(0, html.length - 1);
                        callAjax('/ajax/ajaxestfnrcri', {empresa_codigo: $("#empresa_codigo").val(), documento_numero: $("#documento_numero").val(), cliente_codigo: codigo_cliente_criado,
                            procedencia_cidade_codigo: $("#bgstdsccidade_" + codigo_fnrh).val(), procedencia_cidade_nome: $("#bgstdsccidade_" + codigo_fnrh).val(),
                            procedencia_estado_codigo: $("#bgstdscestado_" + codigo_fnrh).val(), procedencia_pais_codigo: $("#bgstdscpais_" + codigo_fnrh).val(),
                            destino_cidade_codigo: $("#bgstdsccidadedest_" + codigo_fnrh).val(), destino_cidade_nome: $("#bgstdsccidadedest_" + codigo_fnrh).val(),
                            destino_estado_codigo: $("#bgstdscestadodest_" + codigo_fnrh).val(), destino_pais_codigo: $("#bgstdscpaisdest_" + codigo_fnrh).val(),
                            viagem_motivo_codigo: $("#snmotvia_" + codigo_fnrh).val(), transporte_meio_codigo: $("#sntiptran_" + codigo_fnrh).val(),
                            placa_veiculo: $("#snplacaveiculo_" + codigo_fnrh).val()},
                                function (html) {
                                    if (html == 'sessao_expirada')
                                        window.location.href = web_root_complete + 'geral/gertelpri';
                                });
                    }
                });
            }
        }
    }

    if (!todas_abas_visitadas) {
//Chama a função para confirmação
        callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 48, exibicao_tipo: 1, idioma: 'pt'}, function (html) {
            html = JSON.parse(html);
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                $('#germencri_mensagem').text(html.mensagem);
                dialog = $('#exibe-germencri').dialog({
                    title: html.titulo_texto,
                    dialogClass: 'no_close_dialog',
                    autoOpen: false,
                    height: 200,
                    width: 530,
                    modal: true,
                    buttons: [
                        {
                            text: html.botao_1_texto,
                            click: function () {
                                $("#estchionl").submit();
                                $(this).dialog('close');
                            }
                        },
                        {
                            text: html.botao_2_texto,
                            click: function () {
                                gernavaba(+1);
                                $(this).dialog('close');
                            }
                        }
                    ]
                });
                dialog.dialog('open');
            }
        });
    } else {
        $("#estchionl").submit();
    }
}

function estfnrmod_todos() {
//Verifica se houveram alterações no cadastro de algum cliente
    lista_fnrhs = $("#fnrhs").val().split("|");
    houve_alteracao_cliente = false;
    for (var i = 0; i < lista_fnrhs.length; i++) {
        if ($("#clicadalt_" + lista_fnrhs[i]).val() == '1') {
            houve_alteracao_cliente = true;
        }
    }

    if (houve_alteracao_cliente) {
        callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 47, exibicao_tipo: 1}, function (html) {
            html = JSON.parse(html);
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                $('#germencri_mensagem').text(html.mensagem);
                dialog = $('#exibe-germencri').dialog({
                    title: html.titulo_texto,
                    dialogClass: 'no_close_dialog',
                    autoOpen: false,
                    height: 200,
                    width: 530,
                    modal: true,
                    buttons: [
                        {
                            text: html.botao_1_texto,
                            click: function () {
                                //Submete o form apos atualizar o cliente
                                $("#estfnrmoc").submit();
                                $(this).dialog('close');
                            }
                        },
                        {
                            text: html.botao_2_texto,
                            click: function () {
                                for (var i = 0; i < lista_fnrhs.length; i++) {
                                    codigo_cliente = $("#clicadcod_" + lista_fnrhs[i]).val();
                                    callAjax('/clientes/ajaxclicrimod', {clicadcod: codigo_cliente, cliprinom: $("#snnomecompleto_" + lista_fnrhs[i]).val(),
                                        clicadema: $("#snemail_" + lista_fnrhs[i]).val(), clidoctip: $("#sntipdoc_" + lista_fnrhs[i]).val(),
                                        clidocnum: $("#snnumdoc_" + lista_fnrhs[i]).val(), clicpfcnp: $("#snnumcpf_" + lista_fnrhs[i]).val(),
                                        clicelddi: $("#sncelularddi_" + lista_fnrhs[i]).val(),
                                        clicelnum: $("#sncelularnum_" + lista_fnrhs[i]).val(), clitelddi: $("#sntelefoneddi_" + lista_fnrhs[i]).val(),
                                        clitelnum: $("#sntelefonenum_" + lista_fnrhs[i]).val(),
                                        clidocorg: $("#snorgexp_" + lista_fnrhs[i]).val(), clicadocu: $("#snocupacao_" + lista_fnrhs[i]).val(),
                                        clicadnac: $("#snnacionalidade_" + lista_fnrhs[i]).val(), clinacdat: $("#sndtnascimento_" + lista_fnrhs[i]).val(),
                                        clicadend: $("#snresidencia_" + lista_fnrhs[i]).val(), clicadcid: $("#sncidaderes_" + lista_fnrhs[i]).val(),
                                        clicadest: $("#snestadores_" + lista_fnrhs[i]).val(), clicadpai: $("#snpaisres_" + lista_fnrhs[i]).val(),
                                        clicadcep: $("#sncep_" + lista_fnrhs[i]).val()}, function (html) {
                                        if (html == 'sessao_expirada')
                                            window.location.href = web_root_complete + 'geral/gertelpri';
                                        else {
                                            //Submete o form apos atualizar o cliente
                                            $("#estfnrmoc").submit();
                                        }
                                    });
                                }
                                $(this).dialog('close');
                            }
                        }
                    ]
                });
                dialog.dialog('open');
            }
        });
    } else {
        $("#estfnrmoc").submit();
    }
}

function gerestdet(id_dropdown_estados, pais_codigo) {
    callAjax('/ajax/ajaxgerestdet', {pais_codigo: pais_codigo}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            $("#" + id_dropdown_estados).empty();
            if (html != '') {
                jsonDC = JSON.parse(html);
                var select = document.getElementById(id_dropdown_estados);
                $.each(jsonDC, function (key, value) {
                    var o = document.createElement("option");
                    o.value = value.estado_codigo;
                    o.text = value.estado_nome;
                    select.appendChild(o);
                });
            }
        }
    });
}


//Repete os dados do hóspede anterior na modificação de fnrh
function estrepdad(checkbox, primeiro_hospede, hospede_atual) {
    if (checkbox.checked) {
        $("#bgstdsccidade_" + hospede_atual).val($("#bgstdsccidade_" + primeiro_hospede).val());
        $("#bgstdscestado_" + hospede_atual).val($("#bgstdscestado_" + primeiro_hospede).val());
        $("#bgstdscpais_" + hospede_atual).val($("#bgstdscpais_" + primeiro_hospede).val());
        $("#bgstdsccidadedest_" + hospede_atual).val($("#bgstdsccidadedest_" + primeiro_hospede).val());
        $("#bgstdscestadodest_" + hospede_atual).val($("#bgstdscestadodest_" + primeiro_hospede).val());
        $("#bgstdscpaisdest_" + hospede_atual).val($("#bgstdscpaisdest_" + primeiro_hospede).val());
        $("#snmotvia_" + hospede_atual).val($("#snmotvia_" + primeiro_hospede).val());
        $("#sntiptran_" + hospede_atual).val($("#sntiptran_" + primeiro_hospede).val());
        $("#snplacaveiculo_" + hospede_atual).val($("#snplacaveiculo_" + primeiro_hospede).val());
    }

}

//verifica alterações do tipo de quarto na alocação da reserva
$(document).on("change", ".alocacao_multipla_quarto_tipo", function () {

    var linha_modificada = $(this).attr('data-linha-atual');

    console.log(linha_modificada);
    var quarto_tipo_codigo = $(this).val();
    if (linha_modificada == 0) {
        data_linha_atual = $('#datas_aloc').val();
    } else {
        var data_linha_atual = $('#gerdatalo_' + linha_modificada).val();
        var vetor_datas = data_linha_atual.split("/");
        var data_linha_atual = vetor_datas[2] + '-' + vetor_datas[1] + '-' + vetor_datas[0];
    }
    callAjax('/geral/gerquadis', {empresa_codigo: $("#gerempcod").val(), datas: data_linha_atual,
        quarto_tipo_codigo: quarto_tipo_codigo, documento_numero: $('#documento_numero_aloc').val(), quarto_item: $('#quarto_item_aloc').val()},
            function (html) {
                $("#quarto_codigo_alocacao_" + linha_modificada).find('option').remove();
                var quartos = JSON.parse(html).quarto_codigo;
                var options = $("#quarto_codigo_alocacao_" + linha_modificada);
                options.append($("<option />").val('').text(''));
                $.each(quartos, function (key, quarto) {
                    options.append($("<option />").val(quarto.quarto_codigo).text(quarto.quarto_codigo));
                });
                //Verifica se a movimentação foi no primeiro quarto, se for, deve atualizar todos os da alocação multipla
                if (linha_modificada == 0) {
                    $("select.alocacao_multipla_quarto_tipo:not([readonly])").each(function () {
                        $(this).val(quarto_tipo_codigo);
                        var linha_atual = $(this).attr('data-linha-atual');
                        $("#quarto_codigo_alocacao_" + linha_atual).find('option').remove();
                        var options = $("#quarto_codigo_alocacao_" + linha_atual);
                        options.append($("<option />").val('').text(''));
                        $.each(quartos, function (key, quarto) {
                            options.append($("<option />").val(quarto.quarto_codigo).text(quarto.quarto_codigo));
                        });
                    });
                    $('select.alocacao_multipla_quarto_codigo:not([disabled])').each(function () {
                        $(this).val($('#quarto_codigo_alocacao_0').val());
                    });
                }


                // $('.no-select-all-with-search').selectpicker('refresh');
                if (linha_modificada != 0 && typeof linha_modificada !== "undefined") {
                    //Verifica se os campos quartos e tipos de quartos da parte da sanfona possuem valores diferentes
                    var possuiDiferencaQuartoTipo = false;
                    var possuiDiferencaQuartoTipoComprado = false;
                    var possuiDiferencaQuartoCodigo = false;
                    $("select.alocacao_multipla_quarto_tipo").each(function () {
                        var linha_atual = $(this).attr('data-linha-atual');
                        if (linha_atual != 0) {
                            var valor_quarto_tipo = $(this).val();
                            $("select.alocacao_multipla_quarto_tipo").each(function () {
                                var linha_atual = $(this).attr('data-linha-atual');
                                if (linha_atual != 0) {
                                    var valor_quarto_tipo_2 = $(this).val();
                                    if (valor_quarto_tipo != valor_quarto_tipo_2)
                                        possuiDiferencaQuartoTipo = true;
                                }
                            });
                        }
                    });
                    $("select.alocacao_multipla_quarto_codigo").each(function () {
                        var linha_atual = $(this).attr('data-linha-atual');
                        if (linha_atual != 0) {
                            var valor_quarto_tipo = $(this).val();
                            $("select.alocacao_multipla_quarto_codigo").each(function () {
                                var linha_atual = $(this).attr('data-linha-atual');
                                if (linha_atual != 0) {
                                    var valor_quarto_tipo_2 = $(this).val();
                                    if (valor_quarto_tipo != valor_quarto_tipo_2)
                                        possuiDiferencaQuartoCodigo = true;
                                }
                            });
                        }
                    });
                    //Verifica se possui algum quarto tipo codigo diferente do comprado
                    $("select.alocacao_multipla_quarto_tipo").each(function () {
                        var linha_atual = $(this).attr('data-linha-atual');
                        if (linha_atual != 0) {
                            var valor_quarto_tipo = $(this).val();
                            if (valor_quarto_tipo != $('#quarto_tipo_comprado').val()) {
                                possuiDiferencaQuartoTipoComprado = true;
                            }
                        }
                    });

                    //Se tiver diferenças desabilita os campos iniciais                    
                    if (possuiDiferencaQuartoTipo) {
                        $('#gerquatip_0').prop('disabled', true);
                        $('#gerquatip_0').val('');
                    } else {
                        $('#gerquatip_0').val($('#gerquatip_1').val());
                        $('#gerquatip_0').prop('disabled', false);
                        //Remonta os options para o quarto_0
                        $('#gerquatip_0').trigger('change');
                        $('#motivos_quartos_diferentes').css('display', 'none');
                    }

                    if (possuiDiferencaQuartoCodigo) {
                        $('#quarto_codigo_alocacao_0').val('');
                        $('#quarto_codigo_alocacao_0').prop('disabled', true);
                    } else {
                        $('#quarto_codigo_alocacao_0').val($('#quarto_codigo_alocacao_1').val());
                        $('#quarto_codigo_alocacao_0').prop('disabled', false);


                    }

                    if (possuiDiferencaQuartoTipoComprado)
                        $('#motivos_quartos_diferentes').css('display', 'block');
                    else
                        $('#motivos_quartos_diferentes').css('display', 'none');
                }

                $('.no-select-all-with-search').selectpicker('refresh');
            });
});
$(document).on("change", ".alocacao_multipla_quarto_codigo", function () {
    var linha_modificada = $(this).attr('data-linha-atual');
    if (typeof linha_modificada !== "undefined") {
        if (linha_modificada == 0) {

            $('select.alocacao_multipla_quarto_codigo:not([disabled])').each(function () {
                $(this).val($('#quarto_codigo_alocacao_0').val());
            });
            $('select.alocacao_multipla_quarto_tipo:not([readonly])').each(function () {
                var quarto_tipo_codigo = $(this).val();
                var linha_modificada = $(this).attr('data-linha-atual');
                var quarto_codigo_alocado = $('#quarto_codigo_alocacao_' + linha_modificada).val();
                if (linha_modificada != 0) {
//Dispara a gerquadis para cada linha diferente, considerando a data daquela linha
                    var data_linha_atual = $('#gerdatalo_' + linha_modificada).val();
                    var vetor_datas = data_linha_atual.split("/");
                    var data_linha_atual = vetor_datas[2] + '-' + vetor_datas[1] + '-' + vetor_datas[0];
                    callAjax('/geral/gerquadis', {empresa_codigo: $("#gerempcod").val(), datas: data_linha_atual,
                        quarto_tipo_codigo: quarto_tipo_codigo, documento_numero: $('#documento_numero_aloc').val(), quarto_item: $('#quarto_item_aloc').val()},
                            function (html) {

                                $("#quarto_codigo_alocacao_" + linha_modificada).find('option').remove();
                                var quartos = JSON.parse(html).quarto_codigo;
                                var options = $("#quarto_codigo_alocacao_" + linha_modificada);
                                // if (quarto_codigo_alocado != '')
                                options.append($("<option />").val('').text(''));
                                $.each(quartos, function (key, quarto) {
                                    options.append($("<option />").val(quarto.quarto_codigo).text(quarto.quarto_codigo));
                                });
                                $("#quarto_codigo_alocacao_" + linha_modificada).val(quarto_codigo_alocado);
                                $('.no-select-all-with-search').selectpicker('refresh');
                            });
                }
            });
        }
        if (linha_modificada != 0) {
//Verifica se os campos quartos e tipos de quartos da parte da sanfona possuem valores diferentes
            var possuiDiferencaQuartoTipo = false;
            var possuiDiferencaQuartoTipoComprado = false;
            var possuiDiferencaQuartoCodigo = false;
            $("select.alocacao_multipla_quarto_tipo").each(function () {
                var linha_atual = $(this).attr('data-linha-atual');
                if (linha_atual != 0) {
                    var valor_quarto_tipo = $(this).val();
                    $("select.alocacao_multipla_quarto_tipo").each(function () {
                        var linha_atual = $(this).attr('data-linha-atual');
                        if (linha_atual != 0) {
                            var valor_quarto_tipo_2 = $(this).val();
                            if (valor_quarto_tipo != valor_quarto_tipo_2) {
                                possuiDiferencaQuartoTipo = true;
                            }
                        }
                    });
                }
            });
            $("select.alocacao_multipla_quarto_codigo").each(function () {
                var linha_atual = $(this).attr('data-linha-atual');
                if (linha_atual != 0) {
                    var valor_quarto_tipo = $(this).val();
                    $("select.alocacao_multipla_quarto_codigo").each(function () {
                        var linha_atual = $(this).attr('data-linha-atual');
                        if (linha_atual != 0) {
                            var valor_quarto_tipo_2 = $(this).val();
                            if (valor_quarto_tipo != valor_quarto_tipo_2) {
                                possuiDiferencaQuartoCodigo = true;
                            }
                        }
                    });
                }
            });
            //Verifica se possui algum quarto tipo codigo diferente do comprado
            $("select.alocacao_multipla_quarto_tipo").each(function () {
                var linha_atual = $(this).attr('data-linha-atual');
                if (linha_atual != 0) {
                    var valor_quarto_tipo = $(this).val();
                    if (valor_quarto_tipo != $('#quarto_tipo_comprado').val()) {
                        possuiDiferencaQuartoTipoComprado = true;
                    }
                }
            });
            //Se tiver diferenças desabilita os campos iniciais                    
            if (possuiDiferencaQuartoTipo) {
                $('#gerquatip_0').prop('disabled', true);
                $('#gerquatip_0').val('');
            } else {
                $('#gerquatip_0').val($('#gerquatip_1').val());
                $('#gerquatip_0').prop('disabled', false);
                $('#motivos_quartos_diferentes').css('display', 'none');
            }

            if (possuiDiferencaQuartoCodigo) {
                $('#quarto_codigo_alocacao_0').val('');
                $('#quarto_codigo_alocacao_0').prop('disabled', true);
            } else {
                $('#quarto_codigo_alocacao_0').val($('#quarto_codigo_alocacao_1').val());
                $('#quarto_codigo_alocacao_0').prop('disabled', false);

            }

            if (possuiDiferencaQuartoTipoComprado)
                $('#motivos_quartos_diferentes').css('display', 'block');
            else
                $('#motivos_quartos_diferentes').css('display', 'none');
        }
        $('.no-select-all-with-search').selectpicker('refresh');
    }
}
);
//Quando clica em realizar checkout
$(document).on("click", ".checkout", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        $('#atual_dialog').val('dialog_level_1');
        $('#atual_dialog_page').val('/estadias/estchocri/');
        $('#atual_dialog_params').val("{'documento_numero':'" + $(this).attr('aria-documento-numero') + "', 'opened_acordions':'" + $(this).attr('aria-quarto-item') + '|' + "' }");
        callAjax('/estadias/estchocri', {documento_numero: $(this).attr('aria-documento-numero'), opened_acordions: $(this).attr('aria-quarto-item') + '|'}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else
                openDialog(html, '90%', '0.94%', 'Informações checkout');
        });
    }
});
//Quando clica em realizar checkin
$(document).on("click", ".checkin", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        $('#atual_dialog').val('dialog_level_1');
        $('#atual_dialog_page').val('/estadias/estchicri/');
        $('#atual_dialog_params').val("{'empresa_codigo': '" + $('#gerempcod').val() + "', 'quarto_codigo': '" + $(this).attr('aria-quarto-codigo') + "', 'documento_numero':'" + $(this).attr('aria-documento-numero') + "', 'quarto_item': '" + $(this).attr('aria-quarto-item') + "', 'quartos_alocados': '" + $(this).attr('aria-string-alocacao') + "'}");
        callAjax('/estadias/estchicri', {empresa_codigo: $('#gerempcod').val(), quarto_codigo: $(this).attr('aria-quarto-codigo'),
            documento_numero: $(this).attr('aria-documento-numero'), quarto_item: $(this).attr('aria-quarto-item'), quartos_alocados: $(this).attr('aria-string-alocacao'),
            quartos_tipos_alocados: $(this).attr('aria-string-tipos-alocados'), quarto_tipo_comprado: $(this).attr('aria-quarto-tipo-comprado')},
                function (html) {
                    if (html == 'sessao_expirada')
                        window.location.href = web_root_complete + 'geral/gertelpri';
                    else
                        openDialog(html, '90%', '0.94%', 'Realizar checkin');
                });
    }
});
//Quando clica em realizar alocacao de quarto
$(document).on("click", ".alocacao", function () {

    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        if ($(this).attr('aria-salva-pagina') === "1") {
            callAjax('ajax/ajaxgerpagsal', {form: $("#" + $('#form_atual').val()).serialize(), back_page: $('#atual_pagina').val()}, function (html) {
            });
        }
        callAjax('/estadias/estquaalo', {empresa_codigo: $('#gerempcod').val(), quarto_codigo: $(this).attr('aria-quarto-codigo'),
            documento_numero: $(this).attr('aria-documento-numero'), quarto_item: $(this).attr('aria-quarto-item'), quartos_alocados: $(this).attr('aria-string-alocacao')
            , quartos_tipos_alocados: $(this).attr('aria-string-tipos-alocados'),
            quarto_tipo_comprado: $(this).attr('aria-quarto-tipo-comprado')},
                function (html) {
                    if (html == 'sessao_expirada')
                        window.location.href = web_root_complete + 'geral/gertelpri';
                    else
                        openDialog(html, '90%', '0.94%', 'Alocação de quartos');
                    /* $('.alocacao_multipla_quarto_tipo').each(function () {
                     var quarto_tipo_codigo = $(this).val();
                     var linha_modificada = $(this).attr('data-linha-atual');
                     var quarto_codigo_alocado = $('#quarto_codigo_alocado_' + linha_modificada).val();
                     if (linha_modificada != 0) {
                     //Dispara a gerquadis para cada linha diferente, considerando a data daquela linha
                     var data_linha_atual = $('#gerdatalo_' + linha_modificada).val();
                     var vetor_datas = data_linha_atual.split("/");
                     var data_linha_atual = vetor_datas[2] + '-' + vetor_datas[1] + '-' + vetor_datas[0];
                     callAjax('/geral/gerquadis', {empresa_codigo: $("#gerempcod").val(), datas: data_linha_atual,
                     quarto_tipo_codigo: quarto_tipo_codigo, documento_numero: $('#documento_numero_aloc').val(), quarto_item: $('#quarto_item_aloc').val()},
                     function (html) {
                     
                     $("#quarto_codigo_alocacao_" + linha_modificada).find('option').remove();
                     var quartos = JSON.parse(html).quarto_codigo;
                     var options = $("#quarto_codigo_alocacao_" + linha_modificada);
                     // if (quarto_codigo_alocado != '')
                     options.append($("<option />").val('').text(''));
                     $.each(quartos, function (key, quarto) {
                     options.append($("<option />").val(quarto.quarto_codigo).text(quarto.quarto_codigo));
                     });
                     $("#quarto_codigo_alocacao_" + linha_modificada).val(quarto_codigo_alocado);
                     $('.no-select-all-with-search').selectpicker('refresh');
                     });
                     }
                     });*/
                });
    }
});
//Quando clica em conferir conta no processo de checkin
$(document).on("click", ".conferir_conta_checkin", function () {

//Chama a reshosatu para atualização de hóspedes
    var quarto_item = $("#quarto_item_checkin").val();
    var hospedes_modificados = [];
    var hospedes_codigo_antigos = [];
    var hospedes_cliente_itens = [];
    var hospedes_codigos = [];
    var hospedes_nomes = [];
    var hospedes_sobrenomes = [];
    var hospedes_emails = [];
    var hospedes_cpfs = [];
    var hospedes_telefones = [];
    var hospedes_documento_tipos = [];
    var hospedes_documento_numeros = [];
    $("input[name^='h_has_changed_" + quarto_item + "_']").each(function () {
        hospedes_modificados.push($(this).val());
    });
    $("input[name^='h_codigo_antigo_" + quarto_item + "_']").each(function () {
        hospedes_codigo_antigos.push($(this).val());
    });
    $("input[name^='h_codigo_" + quarto_item + "_']").each(function () {
        hospedes_codigos.push($(this).val());
    });
    $("input[name^='h_nome_" + quarto_item + "_']").each(function () {
        hospedes_nomes.push($(this).val());
    });
    $("input[name^='h_sobrenome_" + quarto_item + "_']").each(function () {
        hospedes_sobrenomes.push($(this).val());
    });
    $("input[name^='h_email_" + quarto_item + "_']").each(function () {
        hospedes_emails.push($(this).val());
    });
    $("input[name^='h_cpfnum_" + quarto_item + "_']").each(function () {
        hospedes_cpfs.push($(this).val());
    });
    $("input[name^='h_cel_" + quarto_item + "_']").each(function () {
        hospedes_telefones.push($(this).val());
    });
    $("select[name^='h_doctip_" + quarto_item + "_']").each(function () {
        hospedes_documento_tipos.push($(this).val());
    });
    $("input[name^='h_docnum_" + quarto_item + "_']").each(function () {
        hospedes_documento_numeros.push($(this).val());
    });
    $("input[name^='h_cliente_itens_" + quarto_item + "_']").each(function () {
        hospedes_cliente_itens.push($(this).val());
    });
    callAjax('/ajax/ajaxreshosatu', {empresa_codigo: $("#gerempcod").val(), documento_numero: $("#documento_numero_checkin").val(), quarto_item: $("#quarto_item_checkin").val(),
        contratante_codigo: $("#c_codigo_checkin").val(), hospedes_modificados: hospedes_modificados, hospedes_codigo_antigos: hospedes_codigo_antigos, hospedes_codigos: hospedes_codigos,
        hospedes_nomes: hospedes_nomes, hospedes_sobrenomes: hospedes_sobrenomes, hospedes_emails: hospedes_emails, hospedes_cpfs: hospedes_cpfs, hospedes_telefones: hospedes_telefones, hospedes_documento_tipos: hospedes_documento_tipos,
        hospedes_documento_numeros: hospedes_documento_numeros, hospedes_cliente_itens: hospedes_cliente_itens}, function (html) {
        cliente_codigos = JSON.parse(html);
        cliente_codigos = cliente_codigos.cliente_codigos;
        for (var i = 1; i <= cliente_codigos.length; i++) {
            $('#h_codigo_' + quarto_item + '_' + i).val(cliente_codigos[i - 1].cliente_codigo);
            $('#h_codigo_antigo_' + quarto_item + '_' + i).val(cliente_codigos[i - 1].cliente_codigo);
        }
        //Chama a estfnrcri para associar a fnrh ao cliente recem criado
        codigo_cliente_criado = html.substring(0, html.length - 1);
        //Chama a estquaalo
        var estadia_data = [];
        var quarto_codigo = [];
        var quarto_tipo_codigo = [];
        //Percorre o vetor de quartos habilitados e monta os vetores de estadia, quartos tipos e quarto_codigo
        $('select[name^="quarto_codigo_alocacao[]"]:enabled').each(function () {
            estadia_data.push($('#gerdatalo_' + $(this).attr('data-linha-atual')).val());
            quarto_codigo.push($(this).val());
            quarto_tipo_codigo.push($('#gerquatip_' + $(this).attr('data-linha-atual')).val());
        });
        estquaalo($("#empresa_codigo_aloc").val(), $("#documento_numero_aloc").val(), $("#quarto_item_aloc").val(), estadia_data, quarto_codigo, 1,
                quarto_tipo_codigo, $("#quarto_tipo_comprado").val(), $('#gertipmot').val(), $('#gerobstit').val(), 0);
        $('#checkin_revisao_e_alocacao').css('display', 'none');
        $('#checkin_revisao_contas').css('display', 'block');
        $('#atual_dialog_params').val($('#atual_dialog_params').val().substring(0, $('#atual_dialog_params').val().length - 1) + ',\'tela_exibicao\':\'checkin_revisao_contas\'}');
    });
});