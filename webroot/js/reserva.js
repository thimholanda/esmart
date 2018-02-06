$(document).on("change", ".rescriida", function () {
    var quarto_item = $(this).attr('aria-quarto-item');
    var strAdd = '';
    var tamanho_input = 1;
    var classe_label = ' col-md-1 ';

    //Verifica se a pagina e a a rescriini (tela) ou respdrcri (dialog) para calcular o espaço dos inputs das idades
    if ($('#atual_pagina').val() == '/reservas/respaiatu') {
        tamanho_input = 5;
        classe_label = ' col-md-12 ';
    }
    if ($(this).val() > 0) {
        strAdd = "<label class='control-label " + classe_label + "'>" +
                document.getElementById('rot_rescriida_js').value + ":</label>";
        for (i = 0; i < $(this).val(); i++) {
            if (i == 0)
                strAdd = strAdd + "<div class='col-md-" + tamanho_input + " row'><div class='col-md-10'><select class='form-control respdrcri_criida' name='crianca_idade[]' id='crianca_idade_" + quarto_item + "_" + i + "' aria-quarto-item='" + quarto_item + "'>";
            else
                strAdd = strAdd + "<div class='col-md-" + tamanho_input + " row'><div class='col-md-10 row p-l-0'><select class='form-control respdrcri_criida' name='crianca_idade[]' id='crianca_idade_" + quarto_item + "_" + i + "'  aria-quarto-item='" + quarto_item + "'>";
            for (x = 0; x <= document.getElementById('pagante_crianca_idade_js').value; x++) {
                strAdd += "<option value='" + x + "'>" + x + "</option>";
            }
            strAdd = strAdd + "</select></div></div>";
        }
    }
    $('#quarto_item_' + quarto_item + ' .lista_c').html(strAdd);
});

$(document).on("change", ".rescrimax", function () {
    var quarto_item = $(this).attr('aria-quarto-item');
    var quarto_tipo_codigo = $(this).attr('aria-quarto-tipo-codigo');
    var qtd_adultos = $(this).val();
    callAjax('/ajax/ajaxrescrimax', {qtd_adultos: qtd_adultos, quarto_tipo_codigo: quarto_tipo_codigo}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            var selecionou = false;
            var criancas_sel = $('#quarto_item_' + quarto_item + ' #rescriqtd').val();
            $('#quarto_item_' + quarto_item + ' #rescriqtd').empty();
            var select = $('#quarto_item_' + quarto_item + ' #rescriqtd');
            for (var i = 0; i <= html; i++) {
                var o = document.createElement("option");
                o.value = i;
                o.text = i;
                if (i == criancas_sel) {
                    selecionou = true;
                    o.selected = true;
                }
                select.append(o);
            }
            if (!selecionou)
                $('#quarto_item_' + quarto_item + ' .lista_c').empty();
        }
    });
});



//Cancela o interval quando se clica no botão de reservar
function resbotclc() {
    window.clearInterval(var_interval);
}


//Verifica o contador de expiração da reserva
function restemcon() {
    if ($("#reserva_expiracao").is(':visible')) {
        var_reserva_expiracao = var_reserva_expiracao - var_reserva_intervalo;
        if (var_reserva_expiracao <= 0) {
            window.clearInterval(var_interval);
            document.getElementById('reserva_expiracao').innerHTML = '<font color="red">TEMPO ESGOTADO</font>';
            //Chama a germencri por ajax
            $.ajax({
                type: 'POST',
                url: web_root_complete + 'ajax/ajaxgermencri',
                data: {mensagem_codigo: 12, exibicao_tipo: 3},
                success: function (html) {
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
                                        $(this).dialog('close');
                                        if ($("#documento_numero_js").length != 0) {
                                            callAjax('reservas/restemexc', {empresa_codigo: $("#gerempcod").val(),
                                                documento_numero: $("#documento_numero_js").val()},
                                                    function (html) {
                                                    });
                                        }
                                        $('#bloqueia_tela').val(0);
                                        if ($('#atual_pagina').val() == 'reservas/rescliide')
                                            return gerpagexi('/reservas/rescriini', 1, {});
                                        else if ($('#atual_pagina').val() == '/reservas/respaiatu') {
                                            closeDialog();
                                            $("#janela_atual").val(0);
                                            $('#respaiatu_submit').click();
                                            return 0;
                                        }

                                    }
                                }
                            ]
                        });
                        dialog.dialog('open');
                    }
                }
            });
        }
//Atualiza o tempo em documento_sessao
        if ($("#documento_numero_js").length != 0) {
            $.ajax({
                type: 'POST',
                url: web_root_complete + 'reservas/ajaxatutmpses',
                data: {empresa_codigo: $('#gerempcod').val(), documento_numero: $('#documento_numero_js').val()},
                error: function (html) {
                    console.log(html.responseText);
                }
            });
        }

        if (var_reserva_expiracao > 0) {
            horas = Math.floor(parseInt(var_reserva_expiracao) / 60 / 60);
            minutos = Math.floor(parseInt(var_reserva_expiracao) / 60) - (horas * 60);
            segundos = parseInt(var_reserva_expiracao) - (60 * parseInt(minutos));
            if (horas <= 9)
                horas = '0' + horas;
            if (minutos <= 9)
                minutos = '0' + minutos;
            if (segundos <= 9)
                segundos = '0' + segundos;
            document.getElementById('reserva_expiracao').innerHTML = '<strong>tempo restante:<label class="reser_exp_inner">' + horas + ':' + minutos + ':' + segundos + '</label></strong>';
        } else
            document.getElementById('reserva_expiracao').innerHTML = '<strong>tempo restante:<label class="reser_exp_inner">00:00:00</label></strong>';

    }
}


//Marca as reservas expiradas
function resexpsel() {
    var indice = 0;
    $('.check_doc').each(
            function () {
                valor = $(this).val();
                day_diff = $("#day_diff_" + indice).val();
                hour_diff = $("#hour_diff_" + indice).val();
                if (day_diff < 0 || hour_diff < 0) {
                    $(this).prop("checked", true);
                }
                indice = indice + 1;
            });
}


function resdocval() {
// Array used as error collection
    var errors = [],
// Validation configuration
            conf = {
                onElementValidate: function (valid, $el, $form, errorMess) {
                    if (!valid) {
                        // gather up the failed validations
                        errors.push({el: $el, error: errorMess});
                    }
                }
            },
// Optional language object
            lang = 'pt';

    var form_atual = $('#form_atual').val();

    if ($('#atual_pagina').val().includes('respaiatu') || $('#atual_pagina').val().includes('estpaiatu'))
        form_atual = 'respdrcri';

    if (!$("#" + form_atual).isValid(conf, false)) {
        $('.error').first().focus();
        return false;
    } else {
        //Verifica se algum quarto foi confirmado. Se existe, calcula a soma das parcelas 1 desse quarto
        existe_quarto_confirmado = 0;
        total_a_pagar = 0;
        for (i = 1; i <= $('#resquaqtd').val(); i++) {
            if ($('#reserva_confirmacao_tipo_' + i).val() == 1 && !$('#rescnfdet_' + i).is('[readonly]') && ($('#reserva_confirmacao_tipo_' + i).val() != '0')) {

                if ($('#reserva_valor_tipo_' + i).val() == '1') {
                    total_a_pagar += gervalper($("#valor_parcela1_" + i + '_' + $('#prazo_' + i).val()).val());
                    existe_quarto_confirmado = 1;
                } //else if ($('#reserva_valor_tipo_' + i).val() == '2')
                //total_a_pagar = total_preco;
            }
        }

        //Se nada foi confirmado, nao precisa fazer validação de valores
        if (!existe_quarto_confirmado) {
            if (form_atual == 'rescliide')
                resdocsal();
            else if (form_atual == 'respdrcri') {
                gerpagsal('respaiatu', 'reservas/respaiatu', 1);
                $('#criar_reserva_painel').addClass('submit-button');
                $('#criar_reserva_painel').click();
            } else
                callAjax('/reservas/resdocmod', {data: $("#resdocmod").serialize()}, function (html) {
                    gerpagexi($("#resdocmod #url_redirect_after").val(), 1, {});
                });

            //Se algum quarto foi confirmado, precisa fazer a validação do total a pagar e do total pago
        } else {
            //Calcula o total informado nos pagamentos
            informado_valor = 0;
            $('[id^="forma_valor_"]').each(function () {
                informado_valor += gervalper($(this).val());
            });
            callAjax('/ajax/ajaxconpagval1', {a_pagar_valor: total_a_pagar, informado_valor: informado_valor},
                    function (html) {
                        //Significa que o valor informado condiz com o valor a pagar
                        if (html == 1) {
                            if (form_atual == 'rescliide')
                                resdocsal();
                            else if (form_atual == 'respdrcri') {
                                $('#criar_reserva_painel').addClass('submit-button');
                                $('#criar_reserva_painel').click();
                            } else
                                callAjax('/reservas/resdocmod', {data: $("#resdocmod").serialize()}, function (html) {
                                    gerpagexi($("#resdocmod #url_redirect_after").val(), 1, {});
                                });

                            //Se o valor informado for menor que o a pagar, faz as verificações de permissao
                        } else {
                            html = JSON.parse(html);
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
                                        text: html.botao_2_texto,
                                        click: function () {
                                            $(this).dialog('close');
                                        }
                                    },
                                    {
                                        text: html.botao_1_texto,
                                        click: function () {
                                            $(this).dialog('close');
                                            //Chama a resdocval2 para verificar a permissão deste usuário
                                            callAjax('/ajax/ajaxconpagval2', null,
                                                    function (html) {
                                                        //O usuário possui permissão pra informar um valor menor
                                                        if (html == '1')
                                                            if (form_atual == 'rescliide')
                                                                resdocsal();
                                                            else if (form_atual == 'respdrcri') {
                                                                $('#criar_reserva_painel').addClass('submit-button');
                                                                $('#criar_reserva_painel').click();
                                                            } else
                                                                callAjax('/reservas/resdocmod', {data: $("#resdocmod").serialize()}, function (html) {
                                                                    gerpagexi($("#resdocmod #url_redirect_after").val(), 1, {});
                                                                });
                                                        else {
                                                            alert(html);
                                                            if (form_atual == 'rescliide')
                                                                gerpagexi("reservas/rescriini", 1, {})
                                                            else
                                                                gerpagexi($("#resdocmod #url_redirect_after").val(), 1, {});
                                                        }
                                                    });
                                        }
                                    }
                                ]
                            });
                            dialog.dialog('open');
                        }
                    });
        }
    }
}
/*
 *Função que cria a reserva
 */
function resdocsal() {

    // The form is valid
    callAjax('/reservas/resdocsal', {data: $("#rescliide").serialize()}, function (html) {        
        retorno_resdoccri = JSON.parse(html);

        $('#bloqueia_tela').val(0);
        if (retorno_resdoccri.retorno == 1)
            gerpagexi("reservas/rescriini", 1, {});
        else {
            $('#germencri_mensagem').text(retorno_resdoccri.mensagem.mensagem);
            dialog = $('#exibe-germencri').dialog({
                title: retorno_resdoccri.mensagem.titulo_texto,
                dialogClass: 'no_close_dialog',
                autoOpen: false,
                height: 200,
                width: 530,
                modal: true,
                buttons: [
                    {
                        text: retorno_resdoccri.mensagem.botao_1_texto,
                        click: function () {
                            $(this).dialog('close');
                            if (retorno_resdoccri.retorno == 1) {
                                $('#bloqueia_tela').val(0);
                                gerpagexi("reservas/rescriini", 1, {});
                            }
                        }
                    }]
            });
            dialog.dialog("open");
        }
    });
}

function rescliexi() {
    callAjax('/reservas/resdocpes', {documento_numero: documento_numero, empresa_codigo: empresa_codigo}, function (html, event, ui) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            reservaJson = JSON.parse(html);
            $.each(reservaJson[0], function (key, value) {
                $("#exi" + key).text(value);
            });
            var dialog;
            dialog = $("#exibe-reserva").dialog({
                autoOpen: false,
                height: 400,
                width: 450,
                modal: true,
            });
            dialog.dialog("open");
        }
    });
}

//Monta um vetor com os docs-quarto_item a cancelar
var docs_a_cancelar;
$(document).on("click", ".reserva_cancelar", function () {
    processaReservasCancelamento();
});

function processaReservasCancelamento() {

    if (!docs_a_cancelar) {
        //Salva o status da pesquisa
        callAjax('ajax/ajaxgerpagsal', {form: $("#" + $('#form_atual').val()).serialize(), back_page: $('#atual_pagina').val()}, function (html) {
        });
        docs_a_cancelar = [];
        $('.check_doc').each(
                function () {

                    if ($(this).prop("checked")) {
                        value_checkbox = $(this).val();
                        documento = value_checkbox.split("-")[0];
                        quarto_item = value_checkbox.split("-")[1];
                        quarto_status_codigo = value_checkbox.split("-")[2];
                        cancelamento_limite = value_checkbox.split("-")[3];
                        cancelamento_valor = value_checkbox.split("-")[4];
                        docs_a_cancelar.push(documento + '-' + quarto_item + '-' + quarto_status_codigo + '-' + cancelamento_limite + '-' + cancelamento_valor);
                    }
                });
    }
    resdoccan();
}

function resdoccan() {
    if (docs_a_cancelar.length > 0) {
        var dados_primeira_posicao = docs_a_cancelar[0];
        var documento_numero = dados_primeira_posicao.split('-')[0];
        var quarto_item = dados_primeira_posicao.split('-')[1];
        var quarto_status_codigo = dados_primeira_posicao.split('-')[2];
        var cancelamento_limite = dados_primeira_posicao.split('-')[3];
        var cancelamento_valor = dados_primeira_posicao.split('-')[4];

        //Remove o elemento, sinalizando como já consumido
        docs_a_cancelar.splice(0, 1);
        //Verifica o status do quarto
        // if (quarto_status_codigo == 1) {
        //    resdoccan_2(documento_numero, quarto_item, cancelamento_limite, cancelamento_valor);
        //Se o status for diferente de 1, exibe o dialog da conta
        // } else {
        $('#atual_dialog').val('dialog_level_1');
        $('#atual_dialog_page').val('/reservas/resdoccan/');
        $('#atual_dialog_params').val("{'gerempcod': '" + $('#gerempcod').val() + "',  'resdocnum':'" + documento_numero + "','quarto_item':'" + quarto_item + "', 'opened_acordions':'" + quarto_item + '|' + "' }");
        callAjax('/reservas/resdoccan', {empresa_codigo: $('#gerempcod').val(), documento_numero: documento_numero, quarto_item: quarto_item, cancelamento_limite: cancelamento_limite, cancelamento_valor: cancelamento_valor,
            opened_acordions: quarto_item + '|'},
                function (html) {
                    if (html == 'sessao_expirada')
                        window.location.href = web_root_complete + 'geral/gertelpri';
                    else
                        openDialog(html, '90%', '0.94%', 'Informações de conta', undefined, "docs_a_cancelar = undefined; $('.check_doc').prop('checked', false)");
                });

        // }
        //Não ha mais docs para cancelar
    } else {
        docs_a_cancelar = undefined;
        gerpagexi($('#atual_pagina').val(), 1, {});
    }
}

function resdoccan_2(documento_numero, quarto_item, cancelamento_limite, cancelamento_valor) {
    //Exibe no dialog o documento_numero e quarto_item que estão sendo cancelados
    $('#documento_quarto_item_cancelar').text(documento_numero + '-' + quarto_item);
    //Remove o elemento, sinalizando como já consumido
    var dialog = $("#motivo-cancelamento").dialog({
        autoOpen: false,
        height: 400,
        width: 450,
        modal: true,
        buttons: [
            {
                text: 'Cancelar',
                click: function () {
                    $(this).dialog('close');
                    //Se ainda existirem elementos, chama recursivamente para exibir os motivos dos proximos
                    closeDialog();
                    resdoccan();

                    return 0;
                }
            },
            {
                text: 'Salvar',
                click: function () {
                    //Executa a chamada para resdoccan
                    var cancelamento = 0;
                    var cancelamento_motivo_codigo = $("#cancelamento-motivo-codigo").val();
                    var cancelamento_motivo_texto = $("#cancelamento-motivo-texto").val();

                    callAjax('/ajax/ajaxresdoccan',
                            {empresa_codigo: $('#gerempcod').val(), documento_numero: documento_numero, quarto_item: quarto_item, cancelamento_motivo_codigo: cancelamento_motivo_codigo,
                                cancelamento_motivo_texto: cancelamento_motivo_texto, cancelamento_limite: cancelamento_limite, cancelamento_valor: cancelamento_valor, total_pago: $('#total_pago_' + quarto_item).val()},
                            function (html) {
                                var resdoccan_resultado = JSON.parse(html);
                                //Se já executou totalmente
                                if (resdoccan_resultado.hasOwnProperty('retorno')) {
                                    //Mensagem de cancelamento executado com sucesso
                                    $('#germencri_mensagem').text(resdoccan_resultado.mensagem.mensagem);
                                    dialog = $('#exibe-germencri').dialog({
                                        title: html.titulo_texto,
                                        dialogClass: 'no_close_dialog',
                                        autoOpen: false,
                                        height: 200,
                                        width: 530,
                                        modal: true,
                                        buttons: [
                                            {
                                                text: 'Ok',
                                                click: function () {
                                                    $(this).dialog('close');
                                                    //Se ainda existirem elementos, chama recursivamente para exibir os motivos dos proximos
                                                    closeDialog();
                                                    resdoccan();
                                                    return 0;
                                                }
                                            }
                                        ]
                                    });

                                    dialog.dialog('open');

                                    //Passou o prazo de cancelamento ou o valor pago é diferente da multa
                                } else {
                                    //Verifica se o que ocorreu foi esgotamento do prazo de cancelamento ou diferença no valor da multa
                                    if (resdoccan_resultado.hasOwnProperty('mensagem_cancelamento_fora_prazo')) {
                                        $('#germencri_mensagem').text(resdoccan_resultado.mensagem_cancelamento_fora_prazo.mensagem);
                                        dialog = $('#exibe-germencri').dialog({
                                            title: html.titulo_texto,
                                            dialogClass: 'no_close_dialog',
                                            autoOpen: false,
                                            height: 200,
                                            width: 530,
                                            modal: true,
                                            buttons: [
                                                {
                                                    text: resdoccan_resultado.mensagem_cancelamento_fora_prazo.botao_2_texto,
                                                    click: function () {
                                                        $(this).dialog('close');
                                                        //Se ainda existirem elementos, chama recursivamente para exibir os motivos dos proximos
                                                        closeDialog();
                                                        resdoccan();
                                                        return 0;
                                                    }
                                                },
                                                {
                                                    text: resdoccan_resultado.mensagem_cancelamento_fora_prazo.botao_1_texto,
                                                    click: function () {
                                                        //Verifica se tem permissao de cancelar com o prazo excedido
                                                        //Usuario tem acesso
                                                        if (resdoccan_resultado.geracever === "") {
                                                            //cancela a reserva
                                                            callAjax('/ajax/ajaxresdocmod', {documento_numero: documento_numero, quarto_item: quarto_item, empresa_codigo: $('#gerempcod').val(),
                                                                cancelamento_motivo_codigo: cancelamento_motivo_codigo,
                                                                cancelamento_motivo_texto: cancelamento_motivo_texto,
                                                                documento_status_codigo: 3, exibicao_tipo: 2, eh_cancelamento: 1}, function (html) {
                                                                //Mensagem de cancelamento executado com sucesso
                                                                callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 24, texto_1: documento_numero, texto_2: quarto_item}, function (html) {
                                                                    var html = JSON.parse(html);
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
                                                                                    $(this).dialog('close');
                                                                                    //Se ainda existirem elementos, chama recursivamente para exibir os motivos dos proximos
                                                                                    closeDialog();
                                                                                    resdoccan();
                                                                                    return 0;
                                                                                }
                                                                            }
                                                                        ]
                                                                    });

                                                                    dialog.dialog('open');
                                                                });
                                                            });
                                                        } else {
                                                            //Exibe mensagem que o usário não tem acesso
                                                            $('#germencri_mensagem').text(resdoccan_resultado.geracever);
                                                            dialog = $('#exibe-germencri').dialog({
                                                                title: html.titulo_texto,
                                                                dialogClass: 'no_close_dialog',
                                                                autoOpen: false,
                                                                height: 200,
                                                                width: 530,
                                                                modal: true,
                                                                buttons: [
                                                                    {
                                                                        text: 'Ok',
                                                                        click: function () {
                                                                            $(this).dialog('close');
                                                                            //Se ainda existirem elementos, chama recursivamente para exibir os motivos dos proximos
                                                                            closeDialog();
                                                                            resdoccan();
                                                                            return 0;
                                                                        }
                                                                    }
                                                                ]
                                                            });

                                                            dialog.dialog('open');
                                                        }
                                                    }
                                                }
                                            ]
                                        });
                                        dialog.dialog('open');
                                    } else if (resdoccan_resultado.hasOwnProperty('mensagem_diferenca_multa')) {
                                        //Verifica se quer cancelar o valor pago pelo cliente
                                        $('#germencri_mensagem').text(resdoccan_resultado.mensagem_diferenca_multa.mensagem);
                                        dialog = $('#exibe-germencri').dialog({
                                            title: resdoccan_resultado.titulo_texto,
                                            dialogClass: 'no_close_dialog',
                                            autoOpen: false,
                                            height: 200,
                                            width: 530,
                                            modal: true,
                                            buttons: [
                                                {
                                                    text: resdoccan_resultado.mensagem_diferenca_multa.botao_2_texto,
                                                    click: function () {
                                                        $(this).dialog('close');
                                                        //Se ainda existirem elementos, chama recursivamente para exibir os motivos dos proximos
                                                        closeDialog();
                                                        resdoccan();
                                                        return 0;
                                                    }
                                                },
                                                {
                                                    text: resdoccan_resultado.mensagem_diferenca_multa.botao_1_texto,
                                                    click: function () {
                                                        //cancela a reserva
                                                        callAjax('/ajax/ajaxresdocmod', {documento_numero: documento_numero, quarto_item: quarto_item, empresa_codigo: $('#gerempcod').val(),
                                                            cancelamento_motivo_codigo: cancelamento_motivo_codigo,
                                                            cancelamento_motivo_texto: cancelamento_motivo_texto,
                                                            documento_status_codigo: 3, exibicao_tipo: 2, eh_cancelamento: 1}, function (html) {
                                                            //Mensagem de cancelamento executado com sucesso
                                                            callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 24, texto_1: documento_numero, texto_2: quarto_item}, function (html) {
                                                                var html = JSON.parse(html);
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
                                                                                $(this).dialog('close');
                                                                                //Se ainda existirem elementos, chama recursivamente para exibir os motivos dos proximos
                                                                                closeDialog();
                                                                                resdoccan();
                                                                                return 0;
                                                                            }
                                                                        }
                                                                    ]
                                                                });

                                                                dialog.dialog('open');
                                                            });

                                                        });
                                                    }
                                                }
                                            ]
                                        });
                                        dialog.dialog('open');
                                    }
                                }
                            });

                    $(this).dialog('close');
                }
            }
        ]
    });
    dialog.dialog("open");
}

function removeHospede(hospede_codigo, hospede_nome) {
    callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 34, texto_1: hospede_nome}, function (html) {

        var $focused = $(':focus');
        var quarto_item = $focused.attr('id').split("_")[2];
        var linha = $focused.attr('id').split("_")[3];
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            html = JSON.parse(html);
            var textS = html;
            $('#germencri_mensagem').text(textS.mensagem);
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
                            $(this).dialog('close');
                            return 0;
                        }
                    },
                    {
                        text: html.botao_2_texto,
                        click: function () {

                            $("#clean_entries_" + quarto_item + "_" + linha).val('1');
                            $("#h_codigo_" + quarto_item + "_" + linha).val('');
                            $("#h_nome_" + quarto_item + "_" + linha).val('');
                            $("#h_sobrenome_" + quarto_item + "_" + linha).val('');
                            $("#h_email_" + quarto_item + "_" + linha).val('');
                            callAjax('/clientes/clirelatu', {cliente_codigo_1: $("#c_codigo").val(), cliente_codigo_2: hospede_codigo, excluir: '1'},
                                    function (html) {
                                        if (html == 'sessao_expirada')
                                            window.location.href = web_root_complete + 'geral/gertelpri';
                                    });
                            $(this).dialog('close');
                        }
                    }
                ]
            });
            dialog.dialog('open');
        }
    });
}

function seleciona_reserva_cliente(documento_numero) {
    $("#documento_numero_selecionado").val(documento_numero);
    $("#documento_numero_conta").val(documento_numero);
    dialog = $("#exibe-reservas-cliente").dialog({});
    dialog.dialog("close");
    $("#btn_exi").click();
}

function resatuadi(resaduqtd, rescriqtd, diarias_qtd, adicional_codigo, fixo_fator_codigo, preco, quantidade, quarto_item, linha_adicional, adicional_nome) {
    callAjax('/reservas/resadipre', {resaduqtd: resaduqtd, rescriqtd: rescriqtd, diarias_qtd: diarias_qtd, adicional_codigo: adicional_codigo,
        quantidade: quantidade, fixo_fator_codigo: fixo_fator_codigo, preco: gervalper(preco)}, function (html) {
        html = gervalper(gervalexi(html));

        $("#adicional_total_" + quarto_item + "_" + linha_adicional).val(gervalexi(html));
        $("#adicional_total_original_" + quarto_item + "_" + linha_adicional).val(gervalexi(html));
        $('#total_adicionais_txt_' + quarto_item).text(gervalexi(restotadi(quarto_item)));
        $('#total_adicionais_' + quarto_item).val(restotadi(quarto_item));
        $('#total_preco_txt').text(gervalexi(gervalper(gervalexi($('#total_original').val())) + restotadi2()));
        $('#prazo_' + quarto_item).trigger("change");
        $('#rescandet_' + quarto_item).trigger("change");

        $('#total_preco').val(gervalper($('#total_preco_txt').text()));
        if (quantidade > 0) {
            $('#adicional_nome_' + quarto_item + '_' + linha_adicional + '_txt').text(adicional_nome);
            $('#adicional_total_' + quarto_item + '_' + linha_adicional + '_txt').text(gervalexi(html));
        } else {
            console.log('QTD = 0 ' + quantidade);
            $('#adicional_nome_' + quarto_item + '_' + linha_adicional + '_txt').text('');
            $('#adicional_total_' + quarto_item + '_' + linha_adicional + '_txt').text('');
            $('#adicional_qtd_' + quarto_item + '_' + linha_adicional).val('0');
        }

        //recalcula os extras, considerando agora os adicionais
        var total_tarifas = 0;
        for (i = 1; i <= $('#resquaqtd').val(); i++) {
            total_tarifas += parseFloat($('#tarifa_valor_' + i).val());
        }
        var total_adicionais_com_taxa = 0;
        $("[id^='adicional_qtd_']").each(function () {
            id = this.id;
            quarto_item = id.replace('adicional_qtd_', '').split('_')[0];
            linha_adicional = id.replace('adicional_qtd_', '').split('_')[1];
            if ($('#servico_taxa_incide_' + quarto_item + '_' + linha_adicional).val() == 1) {
                total_adicionais_com_taxa += parseInt($("#adicional_total_" + quarto_item + "_" + linha_adicional).val()) * parseInt($('#adicional_qtd_' + quarto_item + '_' + linha_adicional).val());
            }
        });

        var total_servico_taxa = ((total_tarifas + total_adicionais_com_taxa) * parseInt($('#servico_taxa').val())) / 100;

        $('#total_servico_taxa').text(gervalexi(total_servico_taxa));
    });
}

//Calcula o total dos adicionais por quarto
function restotadi(quarto_item) {
    var total_adicionais = $("#adicional_item_qtd_" + quarto_item).val();
    var valor_total_adicionais = 0;
    for (i = 1; i <= total_adicionais; i++)
        valor_total_adicionais = valor_total_adicionais + gervalper($("#adicional_total_" + quarto_item + "_" + i).val());
    return valor_total_adicionais;
}

//Calcula o total dos adicionais de todos os quartos
function restotadi2() {
    var valor_total_adicionais = 0;
    for (i = 1; i <= $("#resquaqtd").val(); i++) {
        if ($("#quarto_item_removido_" + i).val() == 0) {
            total_adicionais = $("#adicional_item_qtd_" + i).val();
            for (j = 1; j <= total_adicionais; j++)
                valor_total_adicionais = valor_total_adicionais + gervalper($("#adicional_total_" + i + "_" + j).val());
        }
    }
    return valor_total_adicionais;
}


//atualiza os valores no carrinho no processo de reserva
function rescaratu() {
    $('#total_preco_txt').text(gervalexi(0));
    for (i = 1; i <= $('#resquaqtd').val(); i++) {
        $('.reservar_' + i).each(function (element) {
            if ($(this).hasClass('reservado')) {
                $('#total_preco_txt').text(gervalexi(gervalper($('#total_preco_txt').text()) + gervalper($('#tarifa_' + $(this).attr('id').replace('btn_reservar_', '')).val())));
                $('#total_preco').val(gervalper($('#total_preco_txt').text()));
                $('#tarifa_valor_quarto_item_' + i).text($('#tarifa_' + $(this).attr('id').replace('btn_reservar_', '')).val());
                $('#tarifa_valor_' + i).val(gervalper($('#tarifa_' + $(this).attr('id').replace('btn_reservar_', '')).val()));
            }
        });
    }

    //atualiza a taxa de serviço, caso exista
    possui_tarifa_selecionada = 0;
    for (i = 1; i <= $('#resquaqtd').val(); i++) {
        //se ao menos um quarto possuir tarifa, deve exibir as taxas de serviço
        if ($('#tarifa_tipo_codigo_' + i).val() != '')
            possui_tarifa_selecionada = 1;
    }

    if (possui_tarifa_selecionada)
        $('#total_servico_taxa_div').css('display', 'block');
    else
        $('#total_servico_taxa_div').css('display', 'none');
    $('#total_servico_taxa').text(gervalexi(($('#total_preco').val() * $('#servico_taxa').val()) / 100));
}

//Verifica alterações na quantidade de quartos
$(document).on("change", "#f1 #resquaqtd", function () {
//exibe ou esconde o label do quarto

    if ($(this).val() > 1)
        $("#quarto_item_label_1").css('display', 'block');
    else
        $("#quarto_item_label_1").css('display', 'none');
    //Limpa todos com id > 1
    var save = $('#quarto_item_1').detach();
    $('#quarto_itens').empty().append(save);
    for (i = 2; i <= $(this).val(); i++) {
        var clone = $("#quarto_item_1").clone();
        clone.attr("id", "quarto_item_" + i);
        clone.find("#resaduqtd_1").attr("aria-campo-padrao-valor", "0");
        clone.find("#resaduqtd_1").attr("aria-quarto-item", i);
        clone.find("#rescriqtd").attr("aria-quarto-item", i);
        clone.find("#rescriqtd").attr("aria-campo-padrao-valor", "0");
        clone.find("#resaduqtd").attr("aria-padrao-valor", "");
        clone.find("label[for='resaduqtd']").attr("for", 'resaduqtd_' + i);
        clone.find("#quarto_item_label_1").attr("id", "quarto_item_label_" + i);
        $("#quarto_itens").append(clone);
        $("#quarto_item_" + i + " .lista_c").html($("#quarto_item_0 .lista_c").html());
        $("#quarto_item_" + i + " #rescriqtd").html($("#quarto_item_0 #rescriqtd").html());
        $("#quarto_item_label_" + i + " span").text(i);
    }
});

//Executa as ações de acordo com o cancelamento seleci0nado
$(document).on("change", ".rescandet", function () {
    var quarto_item = $(this).attr('aria-quarto-item');
    callAjax('/ajax/ajaxrescansel', {reserva_cancelamento_codigo: $('#rescandet_' + quarto_item).val(), total_preco: parseFloat($('#tarifa_valor_' + quarto_item).val()) + parseFloat($('#total_adicionais_' + quarto_item).val()),
        preco: $('#tarifa_valor_' + quarto_item).val(), inicial_data: $('#inicial_data_' + quarto_item).val(), empresa_codigo: $('#gerempcod').val(),
        final_data: $('#final_data_' + quarto_item).val(), quarto_item: quarto_item, total_diarias: $('#dias_estadia').val(), reserva_data: $('#resresdat').val()}, function (html) {
        dados_cancelamento = document.getElementById('div_dados_cancelamento_' + quarto_item);
        dados_cancelamento.innerHTML = html;
    });
});
//Executa as ações de acordo com a confirmacao seleci0nada
$(document).on("change", ".rescnfdet", function () {
    var quarto_item = $(this).attr('aria-quarto-item');
    var select_cnf = document.getElementById('rescnfdet_' + quarto_item);
    var prazo_pagamento = $('#prazo_' + quarto_item).val();
    var tipo_exibicao_data_limite = $(this).attr('aria-tipo-exibicao-data-limite');
    //Se ainda não foi selecionada uma confirmaçao que exiga forma de pagamento
    callAjax('/ajax/ajaxrescnfsel', {nome_cnf_selecionada: select_cnf.options[select_cnf.selectedIndex].text, inicial_data: $("#inicial_data_" + quarto_item).val(),
        final_data: $("#final_data_" + quarto_item).val(), quarto_item: quarto_item,
        tipo_tarifa_codigo: $("#tarifa_tipo_codigo_" + quarto_item).val(), reserva_data: $('#resresdat').val()
    }, function (html) {
        dados = html.split("|");
        dados_confirmacao = document.getElementById('div_dados_confirmacao_' + quarto_item);

        $("#div_dados_confirmacao_" + quarto_item).removeClass('col-md-12');
        $("#div_dados_confirmacao_" + quarto_item).css({'display': 'inline', 'padding-top': '0px'});

        //Verifica a tela onde está mudando a confirmação. A exibição na rescliide e resdocmod é diferente
        if ($('#atual_pagina').val() == 'reservas/rescliide' || $('#atual_pagina').val() == '/reservas/respaiatu') {
            dados_confirmacao.innerHTML = dados[0];
        } else {
            var html = $.parseHTML(dados[0]);
            var label_rescnfdet = $(html).find('#label_rescnfdet').text();
            var input_rescnfdet = $(html).find('#input_rescnfdet').val();
            //Se for reserva confirmada
            if (dados[1] == 1)
                dados[0] = "";
            else {
                //Se for confirmacao manual
                if (input_rescnfdet == "") {
                    dados[0] = dados[0].replace(label_rescnfdet, '&nbsp;&nbsp;&nbsp;&nbsp;');
                    dados[0] = "<label class='control-label col-md-6 col-sm-12' for='rescnfdet'>&nbsp;&nbsp;&nbsp;&nbsp;</label><label class='control-label col-md-6 col-sm-12' for='rescnfdet'><label style='float:left'>( até </label><input style='display: block; width: 150px; float: left; margin-left: 6px; margin-right: 6px; margin-top: -4px;' class='form-control data_hora datepicker_hour' type='text' name='confirmacao_limite_" + quarto_item + "' id='confirmacao_limite_" + quarto_item + "' data-validation='dateTime'  size=15  value='' placeholder='00/00/0000 00:00'> )</label>";
                } else {
                    //Exibição apenas do texto
                    dados[0] = "<label class='control-label col-md-6 col-sm-12' for='rescnfdet'>&nbsp;&nbsp;&nbsp;&nbsp;</label><label class='control-label col-md-6 col-sm-12' for='rescnfdet'>&nbsp;( até <b>" + dados[3] + " )</b></label>";
                }
            }
            dados_confirmacao.innerHTML = dados[0];
        }

        $("#reserva_confirmacao_tipo_" + quarto_item).val(dados[1]);
        $("#reserva_valor_tipo_" + quarto_item).val(dados[2]);

        //Verifica se existe alguma confirmação que exiga pagamento, para exibição da div
        exibe_pagamentos = 0;
        total_a_pagar = 0;
        for (i = 1; i <= $('#resquaqtd').val(); i++) {
            if ($('#reserva_confirmacao_tipo_' + i).val() == 1 && $('#reserva_valor_tipo_' + i).val() == 1 && !$('#rescnfdet_' + i).is('[readonly]')) {
                exibe_pagamentos = 1;
                total_a_pagar += gervalper($("#valor_parcela1_" + i + '_' + $('#prazo_' + i).val()).val());
            }
        }

        if (exibe_pagamentos) {
            if ($("#Pag_normal").css('display') == 'none') {
                $('#Pag_normal').css('display', 'block');
                $('#pagamentos').css('display', 'block');
                $('#pagamento_forma_dados_1').css('display', 'block');
                $('#total_pagamento_formas').val('1');
                $('#pag_codigo_1').val($("#c_codigo").val());
                $('#pagante_nome_1').val($("#c_nome_autocomplete").val() + ' ' + $("#clisobnom").val());
                $('#pagante_cpf_cnpj_1').val($("#clicpfcnp").val());
            }

            $('#somatoria_partida_valor').text(gervalexi(total_a_pagar));
            $('#total_a_pagar').text(gervalexi(total_a_pagar));
            //Calcula o total a pagar 
        } else {
            for (p = 2; p <= $('#total_pagamento_formas').val(); p++)
                $('#pagamento_forma_dados_' + p).remove();
            $('#Pag_normal').css('display', 'none');
            //$('#pagamentos').css('display', 'none');
        }
        //Verifica se existe alguma confirmação que exiga pagamento pré autorizado, para exibição da div
        exibe_pagamentos_pre_autorizados = 0;
        total_pre_autorizado = 0;
        for (i = 1; i <= $('#resquaqtd').val(); i++) {
            if ($('#reserva_confirmacao_tipo_' + i).val() == 1 && $('#reserva_valor_tipo_' + i).val() == 2) {
                exibe_pagamentos_pre_autorizados = 1;
                total_pre_autorizado += gervalper($("#valor_parcela1_" + i + '_' + $('#prazo_' + i).val()).val());
            }
        }
        if (exibe_pagamentos_pre_autorizados) {
            $('#pagamentos_pre_autorizados').css('display', 'block');
            $('#pre_pag_codigo').val($("#c_codigo").val());
            $('#pre_pagante_nome').val($("#c_nome_autocomplete").val() + ' ' + $("#clisobnom").val());
            $('#pre_pagante_cpf_cnpj').val($("#clicpfcnp").val());
            $('#total_a_pagar_pre_autorizado').text(gervalexi(total_pre_autorizado));
            $('#pre_forma_valor').val(gervalexi(total_pre_autorizado));
        } else
            $('#pagamentos_pre_autorizados').css('display', 'none');

        $('.datepicker_hour').datetimepicker({
            monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
            monthNamesShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
            dayNames: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sabado"],
            dayNamesShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
            dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
            dateFormat: "dd/mm/yy",
            numberOfMonths: 1,
            showButtonPanel: false,
            minDate: -180,
            showOn: "both",
            buttonImage: web_root_complete + "img/calendar-1.png",
            buttonImageOnly: true,
            timeText: 'Horário',
            hourText: 'Hora',
            minuteText: 'Minuto',
            secondText: 'Segundo',
        });
        $('.respagreg').trigger("change");
    });
});
//Se há modificação em algum campo na respaiatu
$(document).on("change", "#respaiatu", function () {
    $("#janela_atual").val(0);
    $("#respaiatu_submit").click();
});

$(document).on("click", ".ui-datepicker-current", function () {
    $("#janela_atual").val(0);
    $("#respaiatu_submit").click();
});
//monitora as duas setinhas de anterior e posterior da respaiatu
$(document).on("click", "#janela_anterior_respaiatu", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        $("#respaiatu_submit").click();
    }
});

$(document).on("click", "#respaiatu_sort_quarto_tipo", function () {
    if ($('#gerordatr').val() == 'quarto_tipo_codigo')
        $('#gerordatr').val('quarto_codigo');
    else
        $('#gerordatr').val('quarto_tipo_codigo');

    $("#janela_atual").val(0);
    $("#respaiatu_submit").click();
});


function respaiatuSort(quartosReservas, ordenacao_atributo) {
    if (ordenacao_atributo == 'quarto_tipo_codigo')
        quartosReservas.sort(dynamicSortMultiple('id_tipo', "id_quarto"));
    else
        quartosReservas.sort(dynamicSort('id_quarto'));
}

$(document).on("click", "#janela_posterior_respaiatu", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        $("#respaiatu_submit").click();
    }
});

$(document).on("click", "#respaiatu_hoje", function () {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!

    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    var today = dd + '/' + mm + '/' + yyyy;
    $('#respaidat').val(today);
    $("#respaiatu_submit").click();
});
//verifica se houve alteração na forma de pagamento
$(document).on("change", ".respagreg", function () {
    var form_atual_id = $(this).closest("form").attr('id');
    var quarto_item = $("#" + form_atual_id + " #quarto_item_atual").val();
    var linha_pagamento = $(this).attr('aria-linha-pagamento');
    var pagamento_forma_codigo = $("#" + form_atual_id + " #respagfor_" + linha_pagamento).val().split("|")[0];
    var contabil_tipo = $("#" + form_atual_id + " #respagfor_" + linha_pagamento).val().split("|")[1];
    var pagante_codigo = $('#pag_codigo_' + linha_pagamento).val();

    //Calcula quanto ja foi pago
    var valor_ja_pago = 0;
    $('[id^="forma_valor_"]').each(function () {
        linha_pagamento_atual = $(this).attr('id').replace('forma_valor_', '');
        if (linha_pagamento_atual != linha_pagamento)
            valor_ja_pago += gervalper($(this).val());
    });


    var valor_a_pagar = ((gervalper($("#" + form_atual_id + " #somatoria_partida_valor").text()) - valor_ja_pago) > 0) ? (gervalper($("#" + form_atual_id + " #somatoria_partida_valor").text()) - valor_ja_pago) : 0;
    callAjax('/ajax/ajaxrespagreg2', {sel_respagfor: pagamento_forma_codigo,
        h_nome: $("#c_nome_autocomplete").val(), h_sobrenome: $("#clisobnom").val(), quarto_item: quarto_item,
        valor_parcela1: gervalexi(valor_a_pagar), linha_pagamento: linha_pagamento,
        cliente_codigo: $("#" + form_atual_id + " #pag_codigo_" + linha_pagamento).val(), contabil_tipo: contabil_tipo
        , credito_data: $("#" + form_atual_id + " #credito_data_" + linha_pagamento).val(), reserva_dados: $("#resresdat").val()}, function (html) {

        //Chama o total de crédito
        /*if (pagamento_forma_codigo == '5' && $("#" + form_atual_id + "#pag_codigo_" + "_" + linha_pagamento).val() != '0' &&
         $("#" + form_atual_id + " #pag_codigo_" + "_" + linha_pagamento).val() != '') {
         callAjax('/ajax/ajaxconcreexi', {cliente_codigo: $("#pag_codigo_" + linha_pagamento).val()},
         function (html_concreexi) {
         if (html == 'sessao_expirada')
         window.location.href = web_root_complete + 'geral/gertelpri';
         else {
         jsonDC = JSON.parse(html_concreexi);
         $("#saldo_credito_" + linha_pagamento).val(gervalexi(jsonDC.credito_saldo));
         dados_forma_pgto = document.getElementById('div_respagreg_' + linha_pagamento);
         dados_forma_pgto.innerHTML = html;
         //se estiver doando um crédito
         if ($("#conpagcri_dialog #contabil_tipo").val() == 'D') {
         $("#forma_valor_" + linha_pagamento).val(gervalexi(0));
         } else {
         if (jsonDC.credito_saldo < gervalper(valor_a_pagar))
         $("#forma_valor_" + linha_pagamento).val(gervalexi(jsonDC.credito_saldo));
         else
         $("#forma_valor_" + linha_pagamento).val(valor_a_pagar);
         }
         $("#div_saldo_credito_" + linha_pagamento).css('display', 'block');
         }
         });
         } else {
         $("#" + form_atual_id + " #div_saldo_credito_" + linha_pagamento).css('display', 'none');*/
        /*    dados_forma_pgto = document.getElementById('div_respagreg_' + linha_pagamento);
         dados_forma_pgto.innerHTML = html;*/
        $("#" + form_atual_id + " #div_respagreg_" + linha_pagamento).html(html);

        //Usanda em elementos que utilizam o plugin selectpicker  e precisam ser recarregados na respagreg
        $('.no-select-all-with-search').selectpicker({
            iconBase: 'fa',
            tickIcon: 'fa-check',
            title: '',
            liveSearch: true
        });
        $('.no-select-all-with-search').selectpicker('refresh');

        //Recarrega o datepicker para a data do pagamento
        $(".datepicker_pagamento").datepicker({
            monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
            monthNamesShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
            dayNames: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sabado"],
            dayNamesShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
            dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
            dateFormat: "dd/mm/yy",
            numberOfMonths: 1,
            showButtonPanel: true,
            currentText: "Hoje",
            minDate: -180,
            showOn: "both",
            buttonImage: web_root_complete + "img/calendar-1.png",
            buttonImageOnly: true,
            buttonText: "Selecione a data de saida",
        });

        //Valida se a data inserido está dentro de um limite
        $.formUtils.addValidator({
            name: 'datalimite',
            validatorFunction: function (value, $el, config, language, $form) {
                if (value !== "") {
                    var limite_dias = $('#' + $el[0].id).attr('aria-limite-dias');
                    var data_informada = $('#' + $el[0].id).val().split('/');
                    data_informada = new Date(data_informada[2], data_informada[1] - 1, data_informada[0]);
                    var data_comparacao = new Date();
                    var oneDay = 24 * 60 * 60 * 1000;
                    var diferenca_dias = Math.round(Math.abs((data_informada.getTime() - data_comparacao.getTime()) / (oneDay)));

                    if (diferenca_dias > limite_dias)
                        return false;
                    else
                        return true;
                } else
                    return false;
            },
            errorMessage: 'A data informada está fora do limite permitido de 180 dias',
            errorMessageKey: 'badEvenNumber'
        });
    });
});

$(document).on("click", ".restardia", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        var quarto_item = $(this).attr('aria-quarto-item');
        var quarto_tipo_codigo = $(this).attr('aria-quarto-tipo-codigo');
        var tarifa_tipo_codigo = $(this).attr('aria-tarifa-tipo-codigo');
        var acesso_sequencia_tipo = $(this).attr('aria-acesso-sequencia-tipo');
        var inicial_data = $('#inicial_data_' + quarto_item).val();
        var final_data = $('#final_data_' + quarto_item).val();
        var adulto_qtd = $('#resaduqtd_' + quarto_item).val();

        //Passa as informações da diária, caso o usuário reabra o dialog da restardia
        var info_diarias = [];
        $('[id^=info_diarias_' + quarto_item + '_' + quarto_tipo_codigo + '_' + tarifa_tipo_codigo + '_]').each(function () {
            info_diarias.push($(this).val());
        });

        //Faz a chamada via dialog
        callAjax('/reservas/restardia', {quarto_tipo_codigo: quarto_tipo_codigo, tarifa_tipo_codigo: tarifa_tipo_codigo,
            inicial_data: inicial_data, final_data: final_data, quarto_item: quarto_item, info_diarias: info_diarias, adulto_qtd: adulto_qtd, acesso_sequencia_tipo: acesso_sequencia_tipo}, function (html) {
            openDialog(html, 600, 400, 'Valores das Diárias');

            //Coloca o foco no primeiro input e o cursor no fim do texto
            $('#restardia form').find('*').filter(':input:visible:first').select();
            /*var tmpStr = $('#restardia form').find('*').filter(':input:visible:first').val();
             $('#restardia form').find('*').filter(':input:visible:first').val('');
             $('#restardia form').find('*').filter(':input:visible:first').val(tmpStr);*/
        });
    }
});

//remove o z-index do content pelo btn x e btn cancelar
$(document).on("click", ".ui-dialog-titlebar-close", function () {
    $("#content").removeClass("false_content");
});
$(document).on("click", ".ui-dialog-btn-close", function () {
    $("#content").removeClass("false_content");
});

//Cancela as validações quando submete o formulário para voltar na página
$(document).on("click", "#reserva_voltar", function () {
    $("input[data-validation='required']").each(function () {
        $(this).attr('data-validation-optional', 'true');
    });
    $("input[data-validation='cpfcnpj']").each(function () {
        $(this).attr('data-validation-optional', 'true');
    });
    $("input[data-validation-optional='false']").each(function () {
        $(this).attr('data-validation-optional', 'true');
    });
    $("select[data-validation='required']").each(function () {
        $(this).attr('data-validation-optional', 'true');
    });
});


//Calcula a acomodação na tela de respdrcri quando se altera a quantidade de adultos
$(document).on("change", ".respdrcri_aduqtd, .respdrcri_criqtd, .respdrcri_criida", function () {
    var quarto_item = $(this).attr('aria-quarto-item');
    var idades = [];
    $('[id^="crianca_idade_' + quarto_item + '_"]').each(function () {
        idades.push(this.value);
    });

    callAjax('/ajax/ajaxresquaaco', {empresa_codigo: $('#gerempcod').val(), adulto_qtd: $('#resaduqtd_' + quarto_item).val(),
        criancas_idades: idades}, function (html) {
        var acomodacao_dados = JSON.parse(html);
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            //  $('#pax_ajustado_adultos_txt_' + quarto_item).text(acomodacao_dados[0].adulto_qtd_ajustada);
            $('#pax_ajustado_criancas_txt_' + quarto_item).text(acomodacao_dados[0].crianca_qtd_ajustada);
            $('#carrinho_pax_ajustado_adultos_txt_' + quarto_item).text(acomodacao_dados[0].adulto_qtd_ajustada);
            $('#carrinho_pax_ajustado_criancas_txt_' + quarto_item).text(acomodacao_dados[0].crianca_qtd_ajustada);
            $('#pax_ajustado_adultos_' + quarto_item).val(acomodacao_dados[0].adulto_qtd_ajustada);
            $('#pax_ajustado_criancas_' + quarto_item).val(acomodacao_dados[0].crianca_qtd_ajustada);
            //Atualiza as idades no carrinho
            var idades_carrinho = '(';
            $('[id^="crianca_idade_' + quarto_item + '_"]').each(function () {
                idades_carrinho += this.value + ',';
            });
            idades_carrinho = idades_carrinho.substr(0, idades_carrinho.length - 1);
            idades_carrinho += ')';
            $('#carrinho_idade_criancas_txt_' + quarto_item).text(idades_carrinho);


            //Recalcula o máximo de adicionais

            $('.respdrcri_adicional').each(function () {
                var linha_adicional = $(this).attr('aria-linha-adicional');
                var adicional_codigo = $(this).val();

                //Limpa os options
                $('#adicional_qtd_' + quarto_item + '_' + linha_adicional).empty();

                callAjax('/ajax/ajaxresadimax', {empresa_codigo: $('#gerempcod').val(), adicional_codigo: adicional_codigo, tarifa_tipo_codigo: $('#tarifa_tipo_codigo_' + quarto_item).val(), adulto_qtd: acomodacao_dados[0].adulto_qtd_ajustada,
                    crianca_qtd: acomodacao_dados[0].crianca_qtd_ajustada, dias_estadia: $('#dias_estadia_js').val()}, function (html) {
                    var resadimax_dados = JSON.parse(html);
                    jQuery.each(resadimax_dados, function () {
                        $('#adicional_qtd_' + quarto_item + '_' + linha_adicional).append('<option value="' + this.valor + '">' + this.texto + '</option>');
                    });

                    callAjax('/ajax/ajaxresadipro', {empresa_codigo: $('#gerempcod').val(), tarifa_tipo_codigo: $('#tarifa_tipo_codigo_' + quarto_item).val()}, function (html) {
                        var adicionais_dados = JSON.parse(html);
                        jQuery.each(adicionais_dados, function () {
                            if (this.adicional_codigo == adicional_codigo) {
                                $('#servico_taxa_incide_' + quarto_item + '_' + linha_adicional).val(this.servico_taxa_incide);
                                $('#produto_codigo_' + quarto_item + '_' + linha_adicional).val(this.adicional_codigo);
                                $('#adicional_fixo_fator_codigo_' + quarto_item + '_' + linha_adicional).val(this.fixo_fator_codigo);
                                $('#adicional_fator_fixo_valor_' + quarto_item + '_' + linha_adicional).val(this.preco_fator_codigo);
                                $('#lancamento_tempo_' + quarto_item + '_' + linha_adicional).val(this.lancamento_tempo);
                                $('#preco_' + quarto_item + '_' + linha_adicional).val(gervalexi(this.preco));
                                console.log(this.horario_modificacao.horario_modificacao_tipo);
                                $('#horario_modificacao_tipo_' + quarto_item + '_' + linha_adicional).val(this.horario_modificacao.horario_modificacao_tipo);
                                $('#horario_modificacao_valor_' + quarto_item + '_' + linha_adicional).val(this.horario_modificacao.horario_modificacao_valor);
                            }
                        });

                        //Recalcula as tarifas
                        callAjax('/ajax/ajaxrestartip', {empresa_codigo: $('#gerempcod').val(), inicial_data: $('#inicial_data_' + quarto_item).val(), final_data: $('#final_data_' + quarto_item).val(),
                            adulto_qtd: acomodacao_dados[0].adulto_qtd_ajustada, quarto_tipo_codigo: $('#quarto_tipo_codigo_' + quarto_item).val()}, function (html) {
                            var tarifas = JSON.parse(html);
                            jQuery.each(tarifas, function () {
                                tarifa_info = this;
                                jQuery.each(tarifa_info, function () {
                                    var tarifa_tipo_codigo_atual = this.tarifa_tipo_codigo;
                                    $('#tarifa_valor_' + quarto_item).val(this.total_tarifa);
                                    $('#tarifa_valor_' + quarto_item + '_' + $('#quarto_tipo_codigo_' + quarto_item).val() + '_' + tarifa_tipo_codigo_atual).val(this.total_tarifa);

                                    var indice_diaria = 1;
                                    //Percorre todas as informações da tarifa
                                    $.each(this, function (key, value) {
                                        if (key != 'tarifa_tipo_codigo' && key != 'tarifa_tipo_nome' && key != 'condicao' && key != 'total_tarifa') {
                                            $('#tarifa_valor_original_' + quarto_item + '_' + $('#quarto_tipo_codigo_' + quarto_item).val() + '_' + tarifa_tipo_codigo_atual + '_' + indice_diaria).val(gervalexi(value));
                                            $('#info_diarias_' + quarto_item + '_' + $('#quarto_tipo_codigo_' + quarto_item).val() + '_' + tarifa_tipo_codigo_atual + '_' + indice_diaria).val(key + '|' + value);
                                            indice_diaria++
                                        }
                                    });
                                });
                            });

                            $('.respdrcri_tarifa').trigger("change");
                            rescaratu_respdrcri();


                            //Atualiza o total original das tarifas
                            $('#total_original_' + quarto_item + '_' + $('#quarto_tipo_codigo_' + quarto_item).val() + '_' + $('#tarifa_tipo_codigo_1').val()).val($('#tarifa_valor_' + quarto_item + '_' + $('#quarto_tipo_codigo_' + quarto_item).val() + '_' + $('#tarifa_tipo_codigo_1').val()).val());

                        });
                    });
                });
            });
        }
    });



    /*} else {
     $('#pax_ajustado_adultos_txt_' + quarto_item).text($('#resaduqtd_' + quarto_item).val());
     $('#pax_ajustado_criancas_txt_' + quarto_item).text($('#quarto_item_' + quarto_item + ' #rescriqtd').val());
     $('#carrinho_pax_ajustado_adultos_txt_' + quarto_item).text($('#resaduqtd_' + quarto_item).val());
     $('#carrinho_pax_ajustado_criancas_txt_' + quarto_item).text($('#quarto_item_' + quarto_item + ' #rescriqtd').val());
     $('#pax_ajustado_adultos_' + quarto_item).val($('#resaduqtd_' + quarto_item).val());
     $('#pax_ajustado_criancas_' + quarto_item).val($('#quarto_item_' + quarto_item + ' #rescriqtd').val());
     $('#carrinho_idade_criancas_txt_' + quarto_item).text('');
     }*/

});

//Mostra ou esconde as linhas de hóspedes de acordo com a quantidade
$(document).on("change", ".respdrcri_aduqtd, .respdrcri_criqtd", function () {
    var quarto_item = $(this).attr('aria-quarto-item');
    var total_hospedes = parseInt($('#resaduqtd_' + quarto_item).val()) + parseInt($('#quarto_item_' + quarto_item + ' #rescriqtd').val());
    //limpa os hóspedes que já existiam antes
    $('[id^="hospede_linha_' + quarto_item + '_"]').each(function () {
        if ($(this).attr('id') != 'hospede_linha_' + quarto_item + '_1')
            $(this).remove();
    });
    //Cria novamente as linhas
    for (i = 2; i <= total_hospedes; i++) {
        var clone = $('#hospede_linha_' + quarto_item + '_1').clone();
        clone.attr("id", 'hospede_linha_' + quarto_item + '_' + i);
        clone.find('#label_linha_hospede_' + quarto_item + '_1').text(i);
        clone.find('label').remove();
        clone.find('input').prop('readonly', false);
        clone.find('#h_has_changed_' + quarto_item + '_1').attr('id', 'h_has_changed_' + quarto_item + '_' + i).attr('name', 'h_has_changed_' + quarto_item + '_' + i).val('0');
        clone.find('#h_codigo_antigo_' + quarto_item + '_1').attr('id', 'h_codigo_antigo_' + quarto_item + '_' + i).attr('name', 'h_codigo_antigo_' + quarto_item + '_' + i).val(null);
        clone.find('#h_codigo_' + quarto_item + '_1').attr('id', 'h_codigo_' + quarto_item + '_' + i).attr('name', 'h_codigo_' + quarto_item + '_' + i).val(null);
        clone.find('#clean_entries_' + quarto_item + '_1').attr('id', 'clean_entries_' + quarto_item + '_' + i).attr('name', 'clean_entries_' + quarto_item + '_' + i).val('0');
        clone.find('#h_nome_' + quarto_item + '_1').attr('id', 'h_nome_' + quarto_item + '_' + i).attr('name', 'h_nome_' + quarto_item + '_' + i).attr('aria-linha-hospede', i).val('').attr('data-validation', '');
        clone.find('#h_sobrenome_' + quarto_item + '_1').attr('id', 'h_sobrenome_' + quarto_item + '_' + i).attr('name', 'h_sobrenome_' + quarto_item + '_' + i).attr('aria-linha-hospede', i).val('').attr('data-validation', '');
        clone.find('#h_email_' + quarto_item + '_1').attr('id', 'h_email_' + quarto_item + '_' + i).attr('name', 'h_email_' + quarto_item + '_' + i).attr('aria-linha-hospede', i).val('').attr('data-validation', '').prop("disabled", false);
        clone.insertAfter('#hospede_linha_' + quarto_item + '_' + (i - 1));
    }

});
//Calcula os adicionais propostos com base no tipo de tarifa
$(document).on("change", ".respdrcri_tarifa", function () {
    var quarto_item = $(this).attr('aria-quarto-item');

    //Limpa todos os botoes de desconto
    $('[id^="restardia_' + quarto_item + '_"]').each(function () {
        $(this).css('display', 'none');
    });

    //Limpa todos os adicionais dessa quarto item , caso existam
    $('[id^="resadisel_' + quarto_item + '_"]').each(function () {
        if ($(this).attr('id') != 'resadisel_' + quarto_item + '_1')
            $(this).remove();
    });
    $('[id^="adicional_qtd_' + quarto_item + '_"]').each(function () {
        if ($(this).attr('id') != 'adicional_qtd_' + quarto_item + '_1')
            $(this).remove();
    });

    $('[id^="tarifa_adicionais_linha_' + quarto_item + '_"]').each(function () {
        if ($(this).attr('id') != 'tarifa_adicionais_linha_' + quarto_item + '_1')
            $(this).remove();
    });

    $('[id^="div_info_adicionais_' + quarto_item + '_"]').each(function () {
        if ($(this).attr('id') != 'div_info_adicionais_' + quarto_item + '_1')
            $(this).remove();
    });

    //Habilita a opção de desconto dessa tarifa
    $('#restardia_' + quarto_item + '_' + $('#quarto_tipo_codigo_' + quarto_item).val() + '_' + $(this).val()).css('display', 'block');

    $('#adicional_item_qtd_' + quarto_item).val(1);

    callAjax('/ajax/ajaxresadipro', {empresa_codigo: $('#gerempcod').val(), tarifa_tipo_codigo: $(this).val()}, function (html) {
        var adicionais_dados = JSON.parse(html);
        $('#resadisel_' + quarto_item + '_1').empty();
        $('#resadisel_' + quarto_item + '_1').append('<option></option>');
        var selected = '';
        if (adicionais_dados.length <= 1)
            selected = 'selected';

        jQuery.each(adicionais_dados, function () {
            if (this.incluido == 0)
                $('#resadisel_' + quarto_item + '_1').append('<option ' + selected + ' value="' + this.adicional_codigo + '">' + this.nome + ' ' + this.preco + '/' + this.preco_fator_nome + '</option>');
        });

        if (adicionais_dados.length <= 1)
            $('#resadisel_' + quarto_item + '_1').trigger("change");
        rescaratu_respdrcri();
    });

    //Monta as opções de prazo de pagamento
    callAjax('/ajax/ajaxrespagpar', {inicial_data: $('#inicial_data_' + quarto_item).val(), final_data: $('#final_data_' + quarto_item).val(), empresa_codigo: $('#gerempcod').val(), tarifa_tipo_codigo: $(this).val(),
        tarifa_valor: $('#tarifa_valor_' + quarto_item + '_' + $('#quarto_tipo_codigo_' + quarto_item).val() + '_' + $(this).val()).val(), total_adicionais: $('#total_adicionais_' + quarto_item).val(), dias_estadia: $('#dias_estadia_js').val()}, function (html) {
        var pagamento_prazo_dados = JSON.parse(html);
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            $('#prazo_' + quarto_item).empty();
            $('#prazo_' + quarto_item).append('<option></option>');
            var selected = '';
            if (pagamento_prazo_dados.length <= 1)
                selected = 'selected';

            //Faz uma verificação para limpar prazos repetidos
            var pagamentos_exibir = [];
            jQuery.each(pagamento_prazo_dados, function () {
                if (pagamentos_exibir.indexOf(this.pagamento_prazo_codigo) == -1) {
                    pagamentos_exibir.push(this.pagamento_prazo_codigo);

                    var texto_pagamento_prazo = '';
                    //Define o texto dentro do input
                    if (this.tarifa_variacao == 0)
                        texto_pagamento_prazo = '';
                    else if (this.tarifa_variacao > 0)
                        texto_pagamento_prazo = '(juros de ' + this.tarifa_variacao + '%)';
                    else if (this.tarifa_variacao < 0)
                        texto_pagamento_prazo = '(desconto de ' + this.tarifa_variacao + '%)';

                    $('#prazo_' + quarto_item).append('<option ' + selected + ' value="' + this.pagamento_prazo_codigo + '">' + this.pagamento_prazo_nome + texto_pagamento_prazo + '</option>');
                }
            });

            if (pagamento_prazo_dados.length <= 1)
                $('#prazo_' + quarto_item).trigger("change");
            else
                $('#pagamento_prazo_partidas_' + quarto_item).css('display', 'none');
        }
    });

    //Monta as opções de cancelamento
    callAjax('/ajax/ajaxrescandet', {empresa_codigo: $('#gerempcod').val(), tarifa_tipo_codigo: $(this).val(), inicial_data_completa: $('#inicial_data_completa').val(),
        final_data_completa: $('#final_data_completa').val()}, function (html) {
        var cancelamento_dados = JSON.parse(html);
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            $('#rescandet_' + quarto_item).empty();
            $('#rescandet_' + quarto_item).append('<option></option>');
            var selected = '';

            if (cancelamento_dados.length <= 1)
                selected = 'selected';
            jQuery.each(cancelamento_dados, function () {
                $('#rescandet_' + quarto_item).append('<option ' + selected + ' value="' + this.reserva_cancelamento_codigo + '">' + this.reserva_cancelamento_nome + '</option>');
            });

            if (cancelamento_dados.length <= 1)
                $('#rescandet_' + quarto_item).trigger("change");
            else
                $('#div_dados_cancelamento_' + quarto_item).empty();
        }
    });

    //Monta as opções de confirmação
    callAjax('/ajax/ajaxrescnfdet', {empresa_codigo: $('#gerempcod').val(), tarifa_tipo_codigo: $(this).val(), inicial_data_completa: $('#inicial_data_completa').val(),
        final_data_completa: $('#final_data_completa').val()}, function (html) {
        var confirmacao_dados = JSON.parse(html);
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            $('#rescnfdet_' + quarto_item).empty();
            $('#rescnfdet_' + quarto_item).append('<option></option>');
            var selected = '';
            if (confirmacao_dados.length <= 1)
                selected = 'selected';
            jQuery.each(confirmacao_dados, function () {
                $('#rescnfdet_' + quarto_item).append('<option ' + selected + ' value="' + this.reserva_confirmacao_codigo + '">' + this.reserva_confirmacao_nome + '</option>');
            });

            if (confirmacao_dados.length <= 1)
                $('#rescnfdet_' + quarto_item).trigger("change");
            else
                $('#div_dados_confirmacao_' + quarto_item).empty();
        }
    });
});

//Calcula os valores dos adicionais quando se altera a quantidade
$(document).on("change", ".adicional_qtd", function () {
    rescaratu_respdrcri();
});


//Calcula os valores máximos para o adicional selecionado
$(document).on("change", ".respdrcri_adicional", function () {
    var quarto_item = $(this).attr('aria-quarto-item');
    var linha_adicional = $(this).attr('aria-linha-adicional');
    var adicional_codigo = $(this).val();
    //Limpa os options
    $('#adicional_qtd_' + quarto_item + '_' + linha_adicional).empty();

    callAjax('/ajax/ajaxresadimax', {empresa_codigo: $('#gerempcod').val(), adicional_codigo: adicional_codigo, tarifa_tipo_codigo: $('#tarifa_tipo_codigo_' + quarto_item).val(), adulto_qtd: $('#resaduqtd_' + quarto_item).val(),
        crianca_qtd: $('#quarto_item_' + quarto_item + ' #rescriqtd').val(), dias_estadia: $('#dias_estadia_js').val()}, function (html) {
        var resadimax_dados = JSON.parse(html);
        jQuery.each(resadimax_dados, function () {
            $('#adicional_qtd_' + quarto_item + '_' + linha_adicional).append('<option value="' + this.valor + '">' + this.texto + '</option>');
        });

        callAjax('/ajax/ajaxresadipro', {empresa_codigo: $('#gerempcod').val(), tarifa_tipo_codigo: $('#tarifa_tipo_codigo_' + quarto_item).val()}, function (html) {
            var adicionais_dados = JSON.parse(html);
            jQuery.each(adicionais_dados, function () {
                if (this.adicional_codigo == adicional_codigo) {
                    $('#servico_taxa_incide_' + quarto_item + '_' + linha_adicional).val(this.servico_taxa_incide);
                    $('#produto_codigo_' + quarto_item + '_' + linha_adicional).val(this.adicional_codigo);
                    $('#adicional_fixo_fator_codigo_' + quarto_item + '_' + linha_adicional).val(this.fixo_fator_codigo);

                    var fator_fixo_valor = 0;
                    switch (this.fixo_fator_codigo) {
                        case '1':
                            fator_fixo_valor = 1;
                            break;
                        case '2':
                            fator_fixo_valor = 1;
                            break;
                        case '3':
                            fator_fixo_valor = $('#resaduqtd_' + quarto_item).val() + $('#rescriqtd_' + quarto_item).val();
                            break;
                        case '4':
                            fator_fixo_valor = $('#resaduqtd_' + quarto_item).val();
                            break;
                        case '5':
                            fator_fixo_valor = $('#dias_estadia').val();
                            break;
                        case '6':
                            fator_fixo_valor = ($('#resaduqtd_' + quarto_item).val() + $('#rescriqtd_' + quarto_item).val()) * $('#dias_estadia').val();
                            break;
                        case '7':
                            fator_fixo_valor = $('#resaduqtd_' + quarto_item).val() * $('#dias_estadia').val();
                            break;
                        default:
                            fator_fixo_valor = 1;
                            break;
                    }

                    $('#adicional_fator_fixo_valor_' + quarto_item + '_' + linha_adicional).val(fator_fixo_valor);
                    $('#lancamento_tempo_' + quarto_item + '_' + linha_adicional).val(this.lancamento_tempo);
                    $('#preco_' + quarto_item + '_' + linha_adicional).val(gervalexi(this.preco));
                    $('#horario_modificacao_tipo_' + quarto_item + '_' + linha_adicional).val(this.horario_modificacao.horario_modificacao_tipo);
                    $('#horario_modificacao_valor_' + quarto_item + '_' + linha_adicional).val(this.horario_modificacao.horario_modificacao_valor);
                }
            });

            rescaratu_respdrcri();
        });
    });
});


//Lista as partidas do pagamento prazo selecionado
$(document).on("change", ".respdrcri_pagamento_prazos", function () {
    var quarto_item = $(this).attr('aria-quarto-item');
    var pagamento_prazo_codigo = $(this).val();

    //O valor da tarifa fica em diferentes ids, quando está no painel de reservas ou na rescliide
    if ($('#tarifa_valor_' + quarto_item + '_' + $('#quarto_tipo_codigo_' + quarto_item).val() + '_' + $('#tarifa_tipo_codigo_' + quarto_item).val()).length != 0)
        var tarifa_valor = $('#tarifa_valor_' + quarto_item + '_' + $('#quarto_tipo_codigo_' + quarto_item).val() + '_' + $('#tarifa_tipo_codigo_' + quarto_item).val()).val();
    else
        var tarifa_valor = $('#tarifa_valor_' + quarto_item).val();

    //Faz a busca nas partidas do prazo de pagamento selecionado se tiver tarifa selecionada
    if ($('#tarifa_tipo_codigo_' + quarto_item).val() !== "") {
        callAjax('/ajax/ajaxrespagptd', {inicial_data: $('#inicial_data_' + quarto_item).val(), final_data: $('#final_data_' + quarto_item).val(), empresa_codigo: $('#gerempcod').val(), tarifa_tipo_codigo: $('#tarifa_tipo_codigo_' + quarto_item).val(),
            tarifa_valor: tarifa_valor, total_adicionais: $('#total_adicionais_' + quarto_item).val(), dias_estadia: $('#dias_estadia_js').val(),
            pagamento_prazo_codigo: pagamento_prazo_codigo, reserva_data: $('#resresdat').val()}, function (html) {
            var pagamento_prazo_partidas = JSON.parse(html);
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                //Limpa as linhas existentes
                $("#pagamento_prazo_partidas_" + quarto_item + " table").find("tbody tr").not(':last').remove();
                $("#pagamento_prazo_partidas_" + quarto_item + " table").find("[id^='valor_parcela1_']").remove();
                $('#pagamento_prazo_partidas_' + quarto_item).css('display', 'block');
                //Preenche os dados da tabela que exibe as partidas
                var partida_info = '';
                var partidas_total = 0;
                var numero_partidas = 0;
                jQuery.each(pagamento_prazo_partidas, function () {
                    partida_info += '<tr>' +
                            '<td>' +
                            '<input type="hidden" name="partida_item_' + quarto_item + '_' + this.pagamento_prazo_codigo + '_' + this.partida_codigo + '" value="' + this.partida_codigo + '">' +
                            '<input type="hidden" name="partida_data_' + quarto_item + '_' + this.pagamento_prazo_codigo + '_' + this.partida_codigo + '" value="' + this.partida_data + '">' +
                            '<input type="hidden" name="partida_valor_' + quarto_item + '_' + this.pagamento_prazo_codigo + '_' + this.partida_codigo + '" value="' + this.partida_valor + '">' +
                            '<input type="hidden" name="partida_pagamento_requerido_evento_' + quarto_item + '_' + this.pagamento_prazo_codigo + '_' + this.partida_codigo + '" value="' + this.pagamento_requerido_evento + '">' +
                            +this.partida_codigo +
                            ' </td>' +
                            '<td>' + this.partida_data_formatada + '</td>' +
                            '<td>R$ ' + this.partida_valor + '</td>' +
                            '</tr>';
                    partidas_total += gervalper(this.partida_valor);
                    numero_partidas++;

                    if (this.partida_codigo == 1)
                        partida_info += '<input type="hidden" id="valor_parcela1_' + quarto_item + '_' + this.pagamento_prazo_codigo + '" name="valor_parcela1_' + quarto_item + '_' + this.pagamento_prazo_codigo + '" value="' + this.partida_valor + '">';
                });
                partida_info += '<input type="hidden" id="total_partidas_' + quarto_item + '_' + pagamento_prazo_codigo + '" name="total_partidas_' + quarto_item + '_' + pagamento_prazo_codigo + '" value="' + numero_partidas + '">';

                $('#pagamento_prazo_partidas_' + quarto_item + ' table > tbody:last').prepend(partida_info);
                $('#total_pagamento_prazo_' + quarto_item).text(gervalexi(partidas_total));

            }
            //Dispara as triggers nos campos dependentes dos valores, pois pode ter havido alteraeções de valores
            $('#rescnfdet_' + $('#restardia_quarto_item_atual').val()).trigger("change");
            $('.respagreg').trigger("change");
        });
    }
});

//atualiza os valores no carrinho no processo de reserva pelo painel
function rescaratu_respdrcri() {
    $('#total_preco_txt').text(gervalexi(0));

    //Atualiza os nomes e valores de cada tarifa selecionada
    var total_valor = 0;
    $('[id^="tarifa_tipo_codigo_"]').each(function () {
        //se tem a tarifa selecionada
        if ($(this).val() != "") {
            var quarto_item = $(this).attr('aria-quarto-item');
            $('#tarifa_nome_quarto_item_' + quarto_item).text($('#tarifa_tipo_codigo_' + quarto_item + ' option:selected').html());
            $('#tarifa_moeda_quarto_item_' + quarto_item).text($('#moeda').val());
            $('#tarifa_valor_quarto_item_' + quarto_item).text(gervalexi($('#tarifa_valor_' + quarto_item + '_' + $('#quarto_tipo_codigo_' + quarto_item).val() + '_' + $(this).val()).val()));
            $('#tarifa_valor_' + quarto_item).val($('#tarifa_valor_' + quarto_item + '_' + $('#quarto_tipo_codigo_' + quarto_item).val() + '_' + $(this).val()).val());
            total_valor += parseFloat($('#tarifa_valor_' + quarto_item + '_' + $('#quarto_tipo_codigo_' + quarto_item).val() + '_' + $(this).val()).val());

        }
    });

    $('#total_preco_txt').text(gervalexi(total_valor));
    $('.respdrcri_pagamento_prazos ').trigger("change");
    $('.rescandet').trigger("change");

    $('#total_original').val(total_valor);
    $('#total_servico_taxa').text(gervalexi((gervalper($('#total_preco_txt').text()) * $('#servico_taxa').val()) / 100));

    //Calcula o total da taxa de turismo    
    var total_hospedes = 0;
    $('[id^="resaduqtd_"]').each(function () {
        if ($(this).val() != "")
            total_hospedes += parseInt($(this).val());
    });
    $('[id^="rescriqtd_"]').each(function () {
        if ($(this).val() != "")
            total_hospedes += parseInt($(this).val());
    });

    if ($('#turismo_taxa').val() > 0) {
        if ($('#hospede_taxa').val() == 1 && $('#diaria_taxa').val() == 1) {
            $('#total_turismo_taxa').text(gervalexi(total_hospedes * parseInt($('#dias_estadia_js').val()) * parseFloat($('#turismo_taxa').val())));
        } else {
            $('#total_turismo_taxa').text(gervalexi((total_hospedes * parseFloat($('#hospede_taxa').val()) * parseFloat($('#turismo_taxa').val())) +
                    (parseFloat($('#turismo_taxa').val()) * parseFloat($('#diaria_taxa').val()) * parseInt($('#dias_estadia_js').val()))));
        }
    }

    //Atualiza os dados de adicionais
    $('[id^="adicional_qtd_"]').each(function () {
        var quarto_item = $(this).attr('aria-quarto-item');
        var linha_adicional = $(this).attr('aria-linha-adicional');
        var adicional_codigo = $('#resadisel_' + quarto_item + '_' + linha_adicional).val();
        var quantidade = $(this).val();

        //Se não houve desconto naquele adicional
        if ($('#adicional_desconto_tmp_' + quarto_item + '_' + linha_adicional).val() == "d|0.00|p|0.00|||") {
            callAjax('/ajax/ajaxresadipro', {empresa_codigo: $('#gerempcod').val(), tarifa_tipo_codigo: $('#tarifa_tipo_codigo_' + quarto_item).val()}, function (html) {
                var adicionais_dados = JSON.parse(html);
                jQuery.each(adicionais_dados, function () {
                    if (this.adicional_codigo == adicional_codigo) {
                        $('#servico_taxa_incide_' + quarto_item + '_' + linha_adicional).val();
                        resatuadi($('#resaduqtd_' + quarto_item).val(), $('#quarto_item_' + quarto_item + ' #rescriqtd').val(), $('#dias_estadia_js').val(), adicional_codigo, this.fixo_fator_codigo, gervalexi(this.preco),
                                quantidade, quarto_item, linha_adicional, this.nome);

                        //Exibe/esconde o botao de desconto
                        if (quantidade > 0 && this.preco > 0)
                            $('#adicional_btn_' + quarto_item + '_' + linha_adicional).css('display', 'block');
                        else
                            $('#adicional_btn_' + quarto_item + '_' + linha_adicional).css('display', 'none');
                    }
                });
            });
        }
    });
    /*for (i = 1; i <= $('#resquaqtd').val(); i++) {
     
     }*/

    //Dispara as triggers nos campos dependentes dos valores, pois pode ter havido alteraeções de valores
    $('#prazo_' + $('#restardia_quarto_item_atual').val()).trigger("change");
    $('#rescandet_' + $('#restardia_quarto_item_atual').val()).trigger("change");
}


//Quando se clica em uma reserva e precisa direcionar para tela de exibição/modificação
$(document).on("click", ".resdocmod", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        var documento_numero = $(this).attr('aria-documento-numero');
        var quarto_item = $(this).attr('aria-quarto-item');

        callAjax('ajax/ajaxgerpagsal', {form: $("#" + $('#form_atual').val()).serialize(), back_page: $('#atual_pagina').val()}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                gerpagexi('reservas/resdocmod/' + documento_numero + '/' + $('#gerempcod').val() + '/' + quarto_item, 1, {});
            }
        });
    }
});

//Quando se clica em adicionar novos adicionais na respdccri
$(document).on("click", ".add_adicional", function () {
    var quarto_item = $(this).attr('aria-quarto-item');

    //Calcula o valor do indice da proxima linha de adicionais
    var proxima_linha_adicional = 1;
    $('[id^="resadisel_' + quarto_item + '_"]').each(function () {
        proxima_linha_adicional++;
    });

    var clone = $('#tarifa_adicionais_linha_' + quarto_item + '_1').clone();
    clone.attr('id', 'tarifa_adicionais_linha_' + quarto_item + '_' + proxima_linha_adicional);
    clone.find('#linha_tarifa_quarto_item_' + quarto_item + ' label, #linha_tarifa_quarto_item_' + quarto_item + ' select, #linha_tarifa_quarto_item_' + quarto_item + ' input, #linha_tarifa_quarto_item_' + quarto_item + ' a, .add_adicional').remove();
    clone.find('#resadisel_' + quarto_item + '_1').prop('id', 'resadisel_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'resadisel_' + quarto_item + '_' + proxima_linha_adicional).attr("aria-linha-adicional", proxima_linha_adicional);
    clone.find('#adicional_qtd_' + quarto_item + '_1').prop('id', 'adicional_qtd_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'adicional_qtd_' + quarto_item + '_' + proxima_linha_adicional).attr("aria-linha-adicional", proxima_linha_adicional);
    clone.find('#produto_codigo_' + quarto_item + '_1').prop('id', 'produto_codigo_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'produto_codigo_' + quarto_item + '_' + proxima_linha_adicional).val('');
    clone.find('#adicional_fixo_fator_codigo_' + quarto_item + '_1').prop('id', 'adicional_fixo_fator_codigo_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'adicional_fixo_fator_codigo_' + quarto_item + '_' + proxima_linha_adicional).val('');
    clone.find('#preco_' + quarto_item + '_1').prop('id', 'preco_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'preco_' + quarto_item + '_' + proxima_linha_adicional).val('');
    clone.find('#adicional_nome_' + quarto_item + '_1').prop('id', 'adicional_nome_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'adicional_nome_' + quarto_item + '_' + proxima_linha_adicional).val('');
    clone.find('#adicional_total_' + quarto_item + '_1').prop('id', 'adicional_total_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'adicional_total_' + quarto_item + '_' + proxima_linha_adicional).val('0');
    clone.find('#adicional_total_original_' + quarto_item + '_1').prop('id', 'adicional_total_original_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'adicional_total_original_' + quarto_item + '_' + proxima_linha_adicional).val('0');
    clone.find('#servico_taxa_incide_' + quarto_item + '_1').prop('id', 'servico_taxa_incide_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'servico_taxa_incide_' + quarto_item + '_' + proxima_linha_adicional).val('');
    clone.find('#adicional_fator_fixo_valor_' + quarto_item + '_1').prop('id', 'adicional_fator_fixo_valor_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'adicional_fator_fixo_valor_' + quarto_item + '_' + proxima_linha_adicional).val('');
    clone.find('#lancamento_tempo_' + quarto_item + '_1').prop('id', 'lancamento_tempo_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'lancamento_tempo_' + quarto_item + '_' + proxima_linha_adicional).val('');
    clone.find('#horario_modificacao_tipo_' + quarto_item + '_1').prop('id', 'horario_modificacao_tipo_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'horario_modificacao_tipo_' + quarto_item + '_' + proxima_linha_adicional).val('');
    clone.find('#horario_modificacao_valor_' + quarto_item + '_1').prop('id', 'horario_modificacao_valor_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'horario_modificacao_valor_' + quarto_item + '_' + proxima_linha_adicional).val('');
    clone.find('#adicional_desconto_tmp_' + quarto_item + '_1').prop('id', 'adicional_desconto_tmp_' + quarto_item + '_' + proxima_linha_adicional).
            attr("name", 'adicional_desconto_tmp_' + quarto_item + '_' + proxima_linha_adicional).val('d|0.00|p|0.00|||');
    clone.find('#adicional_btn_' + quarto_item + '_1').prop('id', 'adicional_btn_' + quarto_item + '_' + proxima_linha_adicional).attr("aria-adicional-referencia-id", quarto_item + '_' + proxima_linha_adicional).
            css({'background': 'url(../img/lapis-1.png) no-repeat center center', 'display': 'none'});
    clone.insertAfter('#tarifa_adicionais_linha_' + quarto_item + '_' + (parseInt(proxima_linha_adicional) - 1));
    $('#adicional_item_qtd_' + quarto_item).val(parseInt($('#adicional_item_qtd_' + quarto_item).val()) + 1);

    //Clona os elementos para exibição no carrinho
    var clone_info_adicionais_carrinho = $('#div_info_adicionais_' + quarto_item + '_1').clone();
    clone_info_adicionais_carrinho.attr('id', 'div_info_adicionais_' + quarto_item + '_' + proxima_linha_adicional);
    clone_info_adicionais_carrinho.find('#adicional_nome_' + quarto_item + '_1_txt').prop('id', 'adicional_nome_' + quarto_item + '_' + proxima_linha_adicional + '_txt').text('');
    clone_info_adicionais_carrinho.find('#adicional_total_' + quarto_item + '_1_txt').prop('id', 'adicional_total_' + quarto_item + '_' + proxima_linha_adicional + '_txt').text('');
    clone_info_adicionais_carrinho.insertAfter('#div_info_adicionais_' + quarto_item + '_' + (parseInt(proxima_linha_adicional) - 1));
});



//Quando se clica em um link do painel de ocupação que possui mais de um checkin na mesma posicao
$(document).on("click", ".resdocpes", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        var quarto_codigo = $(this).attr('data-quarto-codigo');
        var data_tipo = $(this).attr('data-data-tipo');
        var inicio_data = $(this).attr('data-inicio-data');
        var fim_data = $(this).attr('data-fim-data');
        var status = $(this).attr('data-status');

        callAjax('ajax/ajaxgerpagsal', {form: $("#estpaiatu").serialize(), back_page: 'estadias/estpaiatu'}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                callAjax('reservas/resdocpes', {resquacod: quarto_codigo, gerdattip: data_tipo, gerdatini: inicio_data, gerdatfin: fim_data, gerdocsta: status,
                    ordenacao_coluna: 'inicial_data|quarto_tipo_nome|quarto_codigo|', ordenacao_tipo: 'asc|asc|asc|', pagina: 1}, function (html) {
                    if (html == 'sessao_expirada')
                        window.location.href = web_root_complete + 'geral/gertelpri';
                    else {
                        gerpagsal('estpaiatu', 'estadias/estpaiatu', 1);
                        $("#atual_pagina").val('reservas/resdocpes');
                        $("#content").html(html);
                    }
                });
            }
        });
    }
});

//Quando se clica em um botão de criar reserva
$(document).on("click", ".rescriini", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        callAjax('ajax/ajaxgerpagsal', {form: $("#" + $('#form_atual').val()).serialize(), back_page: $('#atual_pagina').val()}, function (html) {

            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                gerpagexi('reservas/rescriini/', 1, {});
            }
        });
    }
});


//Quando se altera um produto nos adicionais, que deve alterar o horario de entrada / saida
$(document).on('change', '.produto_adicional', function () {
    var produtos_modificaram_horario = false;
    // $('.produto_adicional').each(function () {
    var quarto_item = $(this).attr('id').split('_')[2];
    var linha_item = $(this).attr('id').split('_')[3];
    //Verifica se o produto altera o horario
    if ($('#horario_modificacao_tipo_' + quarto_item + '_' + linha_item).val() != null && $('#horario_modificacao_tipo_' + quarto_item + '_' + linha_item).val() != '') {

        //if ($('#adicional_qtd_' + quarto_item + '_' + linha_item).val() > 0) {
        produtos_modificaram_horario = true;
        callAjax('reservas/reshordet', {empresa_codigo: $('#gerempcod').val(), horario_modificacao_tipo: $('#horario_modificacao_tipo_' + quarto_item + '_' + linha_item).val(),
            horario_modificacao_valor: $('#horario_modificacao_valor_' + quarto_item + '_' + linha_item).val(), inicial_data: $('#inicial_data_completa_original').val(),
            final_data: $('#final_data_completa_original').val(), adicional_qtd: $('#adicional_qtd_' + quarto_item + '_' + linha_item).val()}, function (html) {
            html = JSON.parse(html);
            if (html.retorno == 1) {
                if ($('#horario_modificacao_tipo_' + quarto_item + '_' + linha_item).val() == 'is') {
                    $('#inicial_data_carrinho').text(html.inicial_data);
                    $('#inicial_data_completa').val(html.inicial_data);
                } else {
                    $('#final_data_carrinho').text(html.final_data);
                    $('#final_data_completa').val(html.final_data);
                }
            }
        });
        //}
    }
    //});

    //Se nenhum produto tiver modificado o horario, volta pro inicial
    if (!produtos_modificaram_horario) {
        var inicial_data_vetor = $('#inicial_data_completa').val().split('-');
        var final_data_vetor = $('#final_data_completa').val().split('-');

        $('#inicial_data_carrinho').text(inicial_data_vetor[2].split(' ')[0] + '/' + inicial_data_vetor[1] + '/' + inicial_data_vetor[0] + ' ' + inicial_data_vetor[2].split(' ')[1]);
        $('#final_data_carrinho').text(final_data_vetor[2].split(' ')[0] + '/' + final_data_vetor[1] + '/' + final_data_vetor[0] + ' ' + final_data_vetor[2].split(' ')[1]);
        $('#inicial_data_completa').val($('#inicial_data_completa_original').val());
        $('#final_data_completa').val($('#final_data_completa_original').val());
    }
});

//Verifica alterações nos dados do contratante durante a criação da reserva, para que sejam replicados para o hóspede 1
$(document).on("keyup change", ".dados_contratante_replicacao", function () {
    $('[id^="hospede_mesmo_contratante_"]').each(function () {
        if ($(this).is(':checked')) {
            var id_atual = $(this).attr('id').replace('hospede_mesmo_contratante_', '');
            $('#h_codigo_' + id_atual + '_1').val($("#c_codigo").val());
            $('#h_nome_' + id_atual + '_1').val($('#c_nome_autocomplete').val());
            $('#h_sobrenome_' + id_atual + '_1').val($('#clisobnom').val());
            $('#h_email_' + id_atual + '_1').val($('#clicadema').val());
        }
    });
});

//Verifica alterações nos dados do primeiro hóspede, ele é considerado diferente do contratante
$(document).on("click", "#altera_agencia_codigo", function () {
    $("#resviaage").css('display', 'block');
    $("#agencia_codigo_label").css('display', 'none');
});

$(document).on("click", "#altera_externo_documento_numero", function () {
    $("#docnumage").css('display', 'block');
    $("#externo_documento_numero_label").css('display', 'none');
});

$(document).on("change", ".pagante_nome", function () {
    var id = $(this).attr('id');
    var linha_pagante = id.split('_')[2];
    $("#pagante_igual_contratante_" + linha_pagante).val(0);
});

$(document).on("change", ".pre_pagante_nome", function () {
    $("#pre_pagante_igual_contratante").val(0);
});

//Ação quando se tecla o enter na restardia da respdrcri
$(document).on("keyup", "#restardia form", function (event) {
    if (event.keyCode === 13) {
        $(".condessal_diarias_respdrcri").focus();
        $(".condessal_diarias_respdrcri").trigger("click");

        $(".condessal_diarias").focus();
        $(".condessal_diarias").trigger("click");
    }
});

//Verifica alterações em algum dado do formulario da resdocmod, para habilitar o botão salvar
$(document).on('keyup change', '#resdocmod input,#resdocmod  select,#resdocmod  textarea', function (event) {
    $('#resmodbtn').css('display', 'block');
});

//Verifica alterações na data da reserva para disparar os triggers necessários
$(document).on('change', '#resresdat', function (event) {
    var quartos_quantidade = $('#resquaqtd').val();

    //for (var quarto_item = 1; quarto_item < quartos_quantidade; quarto_item++) {
    $('.respdrcri_pagamento_prazos').trigger("change");
    $('.rescandet').trigger("change");
    $('.rescnfdet').trigger("change");
    $('.respagreg').trigger("change");
    //}
});

//Quando se clica em modificar hóspedes
$(document).on('click', '.reshosatu', function () {
    var empresa_codigo = $(this).attr('data-empresa-codigo');
    var documento_numero = $(this).attr('data-documento-numero');
    var quarto_item = $(this).attr('data-quarto-item');

    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        callAjax('/reservas/reshosatu', {empresa_codigo: empresa_codigo, documento_numero: documento_numero, quarto_item: quarto_item,
            url_redirect_after: $('#atual_pagina').val()},
                function (html) {
                    if (html == 'sessao_expirada')
                        window.location.href = web_root_complete + 'geral/gertelpri';
                    else
                        openDialog(html, '95%', '0.94%', 'Revisar hóspedes');
                });
    }
});

function cadastraTarifas() {
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
    if (!$("#restarmod").isValid(conf, false)) {
        $('.error').first().focus();
        return false;
    } else {
        //Submete o formulario
        inicia_loading();
        callAjax('reservas/restarmod', {form: $("#restarmod").serialize(), back_page: 'geral/gertelpri'}, function (html) {
            try {
                var retorno = JSON.parse(html);

                if (retorno.retorno == 1) {
                    $('#germencri_mensagem').text(retorno.mensagem.mensagem);

                    dialog = $('#exibe-germencri').dialog({
                        dialogClass: 'no_close_dialog',
                        autoOpen: false,
                        height: 200,
                        width: 530,
                        modal: true,
                        buttons: [
                            {
                                text: 'OK',
                                click: function () {
                                    $(this).dialog('close');
                                    gerpagexi('reservas/restarmod', 1, {});
                                }
                            }
                        ]
                    });
                    dialog.dialog('open');
                } else {
                    $('#germencri_mensagem').text(retorno.mensagem.mensagem);

                    dialog = $('#exibe-germencri').dialog({
                        dialogClass: 'no_close_dialog',
                        autoOpen: false,
                        height: 200,
                        width: 530,
                        modal: true,
                        buttons: [
                            {
                                text: 'OK',
                                click: function () {
                                    $(this).dialog('close');
                                }
                            }
                        ]
                    });
                    dialog.dialog('open');
                }
            } catch (e) {
                $('#germencri_mensagem').text(html);

                dialog = $('#exibe-germencri').dialog({
                    dialogClass: 'no_close_dialog',
                    autoOpen: false,
                    height: 600,
                    width: 900,
                    modal: true,
                    buttons: [
                        {
                            text: 'OK',
                            click: function () {
                                $(this).dialog('close');
                            }
                        }
                    ]
                });
                dialog.dialog('open');
            }
            $('#bloqueia_tela').val(0);
            finaliza_loading();
        });
    }
}

$(document).on("click", ".mais_tarifas", function () {
    var total_tarifas = $('.linha_tarifa').length;
    var nova_linha_tarifa = $('.linha_tarifa').last().clone();
    nova_linha_tarifa.find('#resquatip_' + parseInt(total_tarifas)).attr('id', 'resquatip_' + parseInt(total_tarifas + 1)).val($('#resquatip_' + parseInt(total_tarifas)).val());
    nova_linha_tarifa.find('#restartip_' + parseInt(total_tarifas)).attr('id', 'restartip_' + parseInt(total_tarifas + 1)).val($('#restartip_' + parseInt(total_tarifas)).val());
    nova_linha_tarifa.find('#resaduqtd_' + parseInt(total_tarifas)).attr('id', 'resaduqtd_' + parseInt(total_tarifas + 1)).val(0);
    nova_linha_tarifa.insertAfter($('.linha_tarifa').last());
});

//Exibe o dialog com a exibição dos veículos
$(document).on("click", ".resveiexi", function () {
    var empresa_codigo = $('#gerempcod').val();
    var documento_numero = $(this).attr('aria-documento-numero');
    var quarto_item = $(this).attr('aria-quarto-item');

    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        callAjax('/reservas/resveiexi', {empresa_codigo: empresa_codigo, documento_numero: documento_numero, quarto_item: quarto_item,
            url_redirect_after: $('#atual_pagina').val()},
                function (html) {
                    openDialog(html, '65%', '0.64%', 'Veículos');
                });
    }
});

$(document).on("click", ".novo_veiculo", function () {
    var nova_linha_veiculo = $('.linha_veiculo').last().clone();
    nova_linha_veiculo.find('input').val('');
    nova_linha_veiculo.find('select').val('');
    nova_linha_veiculo.find('.resveiite').val($('.resveiite').length + 1);
    nova_linha_veiculo.find('input[id^="resveiite_"]').attr('id', 'resveiite_' + parseInt($('.resveiite').length + 1));
    nova_linha_veiculo.find('input[id^="resveichk_"]').attr('id', 'resveichk_' + parseInt($('.resveiite').length + 1));
    nova_linha_veiculo.find('input[id^="resveiexc_"]').attr('id', 'resveiexc_' + parseInt($('.resveiite').length + 1)).val(0);
    nova_linha_veiculo.find('input[id^="resveipla_"]').attr('id', 'resveipla_' + parseInt($('.resveiite').length + 1));
    nova_linha_veiculo.find('input[id^="resveimar_"]').attr('id', 'resveimar_' + parseInt($('.resveiite').length + 1));
    nova_linha_veiculo.find('input[id^="resveicor_"]').attr('id', 'resveicor_' + parseInt($('.resveiite').length + 1));
    nova_linha_veiculo.insertAfter($('.linha_veiculo').last());
});

//Quando clica em imprimir os hóspedes na reshosatu
$(document).on('click', '#listar_hospedes', function (e) {
    //Primeiro salva os dados da reshosatu
    $('#reshosatu_submit').click();
    $('#reslishos').submit();
    e.preventDefault();
});

//Quando se clica em criar reserva a partir da estpaiatu
$(document).on("click", ".solicita_reserva_datas", function () {
    //Abre um dialog solicitando as datas
    var quarto_tipo_codigo = $(this).attr('data-quarto-tipo-codigo');
    var quarto_codigo = $(this).attr('data-quarto-codigo');

    callAjax('/ajax/ajaxreservadatas', {quarto_codigo: quarto_codigo, quarto_tipo_codigo: quarto_tipo_codigo},
            function (html) {
                openDialog(html, '35%', '0.34%', 'Criar reserva');
            });
});


$(document).on("click", ".reserva_criar", function () {

    var quarto_tipo_codigo = $(this).attr('data-quarto-tipo-codigo');
    var quarto_codigo = $(this).attr('data-quarto-codigo');
    var data_inicial = $('#resentdat').val();
    var data_final = $('#ressaidat').val();
    //Transforma as datas para formato americano
    data_inicial = data_inicial.split('/')[2] + '-' + data_inicial.split('/')[1] + '-' + data_inicial.split('/')[0];
    data_final = data_final.split('/')[2] + '-' + data_final.split('/')[1] + '-' + data_final.split('/')[0];

    var datas_aloc = "";
    var quartos_codigos_aloc = "";
    var quartos_tipos_codigos_aloc = "";
    // Returns an array of dates between the two dates
    var getDates = function (startDate, endDate) {
        var dates = [],
                currentDate = startDate,
                addDays = function (days) {
                    var date = new Date(this.valueOf());
                    date.setDate(date.getDate() + days);
                    return date;
                };

        while (currentDate <= endDate) {
            dates.push(currentDate);
            currentDate = addDays.call(currentDate, 1);
        }
        return dates;
    };

    var dates = getDates(new Date(data_inicial.split("-")[0], data_inicial.split("-")[1] - 1, data_inicial.split("-")[2]),
            new Date(data_final.split("-")[0], data_final.split("-")[1] - 1, data_final.split("-")[2] - 1));

    dates.forEach(function (date) {
        datas_aloc += date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2) + "|";
        quartos_codigos_aloc += quarto_codigo + ",";
        quartos_tipos_codigos_aloc += quarto_tipo_codigo + ",";
    });
    datas_aloc = datas_aloc.slice(0, -1);


    //Deve-se checar a disponibilidade
    callAjax('/geral/gerquadis', {empresa_codigo: $("#gerempcod").val(), datas: datas_aloc, quarto_tipo_codigo: quarto_tipo_codigo,
        quarto_codigo: quarto_codigo, saida_formato: 0},
            function (html) {
                var retorno = JSON.parse(html);
                //Verifica se o quarto está disponivel
                if (retorno.resultado) {
                    var quartoTipoArr = [],
                            quartoArr = [];
                    quartoTipoArr.push(quarto_tipo_codigo);
                    quartoArr.push(quarto_codigo);

                    Date.prototype.addDays = function (days) {
                        var dat = new Date(this.valueOf());
                        dat.setDate(dat.getDate() + days);
                        return dat;
                    };

                    closeDialog();
                    respdrcri(quartoTipoArr, quartoArr, data_inicial, data_final);
                    //O quarto não está disponivel para aquela data (mensagem no rodape)
                } else {
                    $('#mensagem_indisponibilidade').css('display', 'none');

                    var el = $('#mensagem_indisponibilidade'),
                            newone = el.clone(true);
                    el.before(newone);

                    $("." + el.attr("msg_error") + ":last").remove();
                    $('#mensagem_indisponibilidade').css('display', 'inline');
                    $('#mensagem_indisponibilidade').text(retorno.mensagem);
                }
            });


});

$(document).on("change", ".hospede_mesmo_contratante", function () {
    var quarto_item = $(this).attr('id').replace('hospede_mesmo_contratante_', '');

    //É o mesmo cliente do contratante
    if ($(this).is(':checked')) {
        $('#h_codigo_' + quarto_item + '_1').val($("#c_codigo").val());
        $('#h_nome_' + quarto_item + '_1').val($('#c_nome_autocomplete').val());
        $('#h_nome_' + quarto_item + '_1').prop('readonly', true);
        $('#h_sobrenome_' + quarto_item + '_1').val($('#clisobnom').val());
        $('#h_sobrenome_' + quarto_item + '_1').prop('readonly', true);
        $('#h_email_' + quarto_item + '_1').val($('#clicadema').val());
        $('#h_email_' + quarto_item + '_1').prop('readonly', true);
    } else {
        $('#h_codigo_' + quarto_item + '_1').val('');
        $('#h_nome_' + quarto_item + '_1').val('');
        $('#h_nome_' + quarto_item + '_1').prop('readonly', false);
        $('#h_sobrenome_' + quarto_item + '_1').val('');
        $('#h_sobrenome_' + quarto_item + '_1').prop('readonly', false);
        $('#h_email_' + quarto_item + '_1').val('');
        $('#h_email_' + quarto_item + '_1').prop('readonly', false);
    }
});

$(document).on("change", ".primeiro_hospede", function () {
    //Compara o nome e sobrenome do primeiro hospede para verificar se ele  não é o contratante
    var quarto_item = $(this).attr('data-quarto-item');

    if ($('#h_nome_' + quarto_item + '_1').val().trim() == $('#c_nome_autocomplete').val().trim() &&
            $('#h_sobrenome_' + quarto_item + '_1').val().trim() == $('#clisobnom').val().trim()) {
        $('#hospede_mesmo_contratante_' + quarto_item).prop('checked', true);
        $('.hospede_mesmo_contratante').trigger('change');
    }
});