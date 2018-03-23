
//Faz a validação no pagamento da conta
function conpagval() {
    //Calcula o total informado nos pagamentos
    informado_valor = 0;
    $('[id^="forma_valor_"]').each(function () {
        informado_valor += gervalper($(this).val());
    });

    a_pagar_valor = $("#a_pagar").val();
    //Verifica se o valor informado foi menor que o apagar
    callAjax('/ajax/ajaxconpagval1', {a_pagar_valor: a_pagar_valor, informado_valor: informado_valor},
    function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            //Significa que o valor informado condiz com o valor a pagar
            if (html == 1) {
                //Chama a execução da conpagcri
                $('#conpagcri_button').click();
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

                                            if (html == 'sessao_expirada')
                                                window.location.href = web_root_complete + 'geral/gertelpri';
                                            else {
                                                //O usuário possui permissão pra informar um valor menor
                                                if (html == '1') {
                                                    //Chama a execução da conpagcri
                                                    $('#conpagcri_button').click();
                                                } else {
                                                    alert(html);
                                                    closeDialog();
                                                    gerpagexi($("#url_redirect_after").val(), 1, {});
                                                }
                                            }
                                        });
                            }
                        }
                    ]
                });
                dialog.dialog('open');
            }
        }
    });
}

/*
 * Desabilita os campos na tela de busca de contas
 */
function condescam(linha) {
    for (var i = 1; i <= 3; i++) {
        if (i != linha) {
            $("#linha-" + i + " :input:not(.radio-item)").attr('disabled', 'disabled');
            $("#linha-" + i + " :input:not(.radio-item)").val('');
            $("#linha-" + i + " :input(radio)").attr('checked', false);
        }
        if (i == linha) {
            $("#linha-" + i + " :input:not(.radio-item)").attr('disabled', false);
            $("#linha-" + i + " :input(radio)").attr('checked', true);
        }
    }
}

//Função que recarrega os parâmetros na concliges quando se altera a empresa
function conmodemp(gerempcod) {
    callAjax('/ajax/ajaxgerdomqua', {gerempcod: gerempcod}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            selecionou = false;
            quarto_sel = $("#resquacod").val();
            $("#resquacod").empty();
            quarto_codigos = html.split("|");
            var select = document.getElementById("resquacod");
            for (var i = 0; i < quarto_codigos.length; i++) {
                var o = document.createElement("option");
                o.value = quarto_codigos[i];
                o.text = quarto_codigos[i];
                if (quarto_codigos[i] == quarto_sel) {
                    selecionou = true;
                    o.selected = true;
                }
                select.appendChild(o);
            }
        }
    });
}


//Carrega as informações do desconto das diárias
function coninfded(total_diaria, referencia) {
    $("#preco_anterior").val(gervalexi(total_diaria));
    $("#conpretot_desc").val(gervalper(total_diaria));
    valor_referencia = $("#tmp_" + referencia).val();
    info_desconto = valor_referencia.split(";");
    if (info_desconto.length > 1) {
        if (info_desconto[1] != '') {
            if (info_desconto[1] == 'c') {
                $("#gerdesfat").prop('disabled', 'disabled');
                $("#gerdestip").prop('disabled', 'disabled');
                $("#gerdestip").css('display', 'none');
                $("#gerdesval").css('display', 'none');
                $("#motivos-cortesia").css('display', 'block');
                $("#motivos-desconto").css('display', 'none');
                $("#label-moeda").css('display', 'none');
                $("input[name=desc_cortesia][value=c]").prop('checked', true);
                $("input[name=desc_cortesia][value=d]").prop('checked', false);
                $("#gertipmot_cort").val(info_desconto[5]);
            } else {
                $("input[name=desc_cortesia][value=c]").prop('checked', false);
                $("input[name=desc_cortesia][value=d]").prop('checked', true);
                $("#gertipmot_desc").val(info_desconto[5]);
            }
        }
        $("#gerdesfat").val(gervalexi(info_desconto[2]));
        $("#gerdestip").val(info_desconto[3]);
        $("#gerdesval").val(gervalexi(info_desconto[4]));
        $("#preco_posterior").val(gervalexi(gervalper(total_diaria) - info_desconto[4]));
        $("#gerobstit_condesapl").val(info_desconto[6]);
        $("#gerusucod").val("");
        $("#gerlogsen").val("");
    }
}

//Carrega as informações do desconto dos adicionais
function coninfdea(indice_adicional) {
    $("#preco_anterior").val(gervalexi($("#total_original_" + indice_adicional).val()));
    $("#conpretot_desc").val(gervalper($("#total_original_" + indice_adicional).val()));
    valor_referencia = $("#info_adicionais_" + indice_adicional).val();
    info_desconto = valor_referencia.split(";");
    if (info_desconto.length > 4) {
        if (info_desconto[3] != '') {
            if (info_desconto[3] == 'c') {
                $("#gerdesfat").prop('disabled', 'disabled');
                $("#gerdestip").prop('disabled', 'disabled');
                $("#gerdestip").css('display', 'none');
                $("#gerdesval").css('display', 'none');
                $("#motivos-cortesia").css('display', 'block');
                $("#motivos-desconto").css('display', 'none');
                $("#label-moeda").css('display', 'none');
                $("input[name=desc_cortesia][value=c]").prop('checked', true);
                $("input[name=desc_cortesia][value=d]").prop('checked', false);
                $("#gertipmot_cort").val(info_desconto[7]);
            } else {
                $("input[name=desc_cortesia][value=c]").prop('checked', false);
                $("input[name=desc_cortesia][value=d]").prop('checked', true);
                $("#gertipmot_desc").val(info_desconto[7]);
            }
        }
        $("#gerdesfat").val(gervalexi(info_desconto[4]));
        $("#gerdestip").val(info_desconto[5]);
        $("#gerdesval").val(gervalexi(info_desconto[6]));
        $("#preco_posterior").val(gervalexi(gervalper($("#total_original_" + indice_adicional).val()) - info_desconto[6]));
        $("#gerobstit_condesapl").val(info_desconto[8]);
        $("#gerusucod").val("");
        $("#gerlogsen").val("");
    }
}

function condesapl_diarias() {
    id_referencia = $("#id_referencia").val();
    empresa_codigo = $("#gerempcod").val();
    produto_codigo = 1;
    desconto_cortesia = $('input[name="desc_cortesia"]:checked').val();
    desconto_fator = gervalper($("#gerdesfat").val());
    desconto_tipo = $("#gerdestip").val();
    desconto_valor = gervalper($("#gerdesval").val());
    if (desconto_cortesia == 'c')
        desconto_tipo_motivo = $("#gertipmot_cort").val();
    else
        desconto_tipo_motivo = $("#gertipmot_desc").val();
    desconto_texto = $("#gerobstit_condesapl").val();
    preco_total = gervalper($("#conpretot_desc").val());
    usuario_autorizacao = $("#gerusucod").val();
    senha_autorizacao = $("#gerlogsen").val();
    callAjax('/documentocontas/condesapl', {empresa_codigo: empresa_codigo, produto_codigo: produto_codigo,
        preco_total: preco_total, desconto_cortesia: desconto_cortesia, desconto_fator: desconto_fator, desconto_tipo: desconto_tipo,
        desconto_valor: desconto_valor, usuario_autorizacao: usuario_autorizacao, senha_autorizacao: senha_autorizacao}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            html = JSON.parse(html);
            //Usuario pode aplicar esse desconto
            if (html.retorno == '1') {
                info_diarias = ";" + desconto_cortesia + ";" + desconto_fator + ";" + desconto_tipo + ";" + desconto_valor + ";" + desconto_tipo_motivo + ";" + desconto_texto + ";" + usuario_autorizacao;
                $("#tmp_" + id_referencia).val(info_diarias);
                $("#tmp_txt_" + id_referencia).text($("#preco_posterior").val());
                $("#gerbtndes_" + id_referencia).css("background-color", "#9bbef7");

                fechaDialogById('aplica-desconto-diarias-dialog');
            } else {
                alert(html.mensagem);
                $("#gerlogsen").val('');
            }
        }
    });
}

function condesapl_adicionais() {
    id_referencia = $("#id_referencia").val();
    empresa_codigo = $("#gerempcod").val();
    //ERRADO
    produto_codigo = $("#adicional_codigo_" + id_referencia).val();
    desconto_cortesia = $('input[name="desc_cortesia"]:checked').val();
    desconto_fator = gervalper($("#gerdesfat").val());
    desconto_tipo = $("#gerdestip").val();
    desconto_valor = gervalper($("#gerdesval").val());
    if (desconto_cortesia == 'c')
        desconto_tipo_motivo = $("#gertipmot_cort").val();
    else
        desconto_tipo_motivo = $("#gertipmot_desc").val();
    desconto_texto = $("#gerobstit_condesapl").val();
    preco_total = gervalper($("#conpretot_desc").val());
    usuario_autorizacao = $("#gerusucod").val();
    senha_autorizacao = $("#gerlogsen").val();
    callAjax('/documentocontas/condesapl', {empresa_codigo: empresa_codigo, produto_codigo: produto_codigo,
        preco_total: preco_total, desconto_cortesia: desconto_cortesia, desconto_fator: desconto_fator, desconto_tipo: desconto_tipo,
        desconto_valor: desconto_valor, usuario_autorizacao: usuario_autorizacao, senha_autorizacao: senha_autorizacao}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            html = JSON.parse(html);
            //Usuario pode aplicar esse desconto
            if (html.retorno == '1') {
                info_descontos_adicionais = ";" + desconto_cortesia + ";" + desconto_fator + ";" + desconto_tipo + ";" + desconto_valor + ";" + desconto_tipo_motivo + ";" + desconto_texto + ";" + usuario_autorizacao;
                info_adicional = $("#info_adicionais_" + id_referencia).val().split(";");
                $("#info_adicionais_" + id_referencia).val(info_adicional[0] + ";" + info_adicional[1] + ";" + info_adicional[2] + info_descontos_adicionais);
                $("#total_" + id_referencia).val($("#preco_posterior").val());
                $("#gerbtndes_" + id_referencia).css("background-color", "#9bbef7");
                $("#adicional_total_preco").val(gervalexi(restotadi()));
                $("#preco_total").val(gervalexi(gervalper($("#preco_quarto").val()) + restotadi()));
                $("#adicional_total_txt").text(gervalexi(restotadi()));
                $("#preco_total_txt").text(gervalexi(gervalper($("#preco_quarto").val()) + restotadi()));
                fechaDialogById('aplica-desconto-adicionais-dialog');
            } else {
                alert(html.mensagem);
                $("#gerlogsen").val('');
            }
        }
    });
}


//Atualiza o valor total a pagar
function conpagatu(quarto_item) {
    valor_atual = 0;
    total_partidas = $("#total_partidas_" + quarto_item).val();
    for (i = 1; i < total_partidas; i++) {
        if ($("#check_partida_" + quarto_item + "_" + i).prop('checked'))
            valor_atual = valor_atual + gervalper($("#partida_a_pagar_" + quarto_item + "_" + i).val());
    }

    if ($("#check_virtual_" + quarto_item).prop("checked"))
        valor_atual = valor_atual + gervalper($("#virtual_a_pagar_" + quarto_item).val());

    $("#convaapag_" + quarto_item).val(gervalexi(valor_atual));
}

function abreDialogLogs() {
    callAjax('/geral/gerlogexi', {tela_codigo: 'resdocmod', idioma: $("#log-idioma").val(), empresa_codigo: $("#empresa_codigo").val()}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            $("#exibe-logs").append(html);
            dialog = $("#exibe-logs").dialog({
                autoOpen: false,
                height: 550,
                width: 1050,
                modal: true,
                dialogClass: 'dialog-logs'
            });
            dialog.dialog("open");
        }
    });
}

function creatudex(linha_pagamento) {
    if ($("#credito_data_expiracao_" + linha_pagamento).length) {
        $("#credito_data_expiracao_" + linha_pagamento).val(gerdatsum($("#credito_data_" + linha_pagamento).val(),
                $("#credito_prazo_expiracao_" + linha_pagamento).val()));
    }
}

//Valida a data de expiracao de credito
function credatval(linha_pagamento) {
    if (!gerdpoval($("#credito_data_expiracao_" + linha_pagamento).val(), gerdatsum($("#credito_data_" + linha_pagamento).val(),
            $("#credito_prazo_expiracao_" + linha_pagamento).val())))
        $("#credito_data_expiracao_" + linha_pagamento).val(gerdatsum($("#credito_data_" + linha_pagamento).val(),
                $("#credito_prazo_expiracao_" + linha_pagamento).val()));
}

function gercreval(linha_pagamento) {
    //Se estiver consumindo credito
    if ($("#contabil_tipo").val() == 'C') {
        saldo_credito = $("#saldo_credito_" + linha_pagamento).val();
        if (gervalper($("#forma_valor_" + linha_pagamento).val()) > gervalper(saldo_credito)) {
            $("#forma_valor_" + linha_pagamento).val(saldo_credito);
        }
    }
}

//Limita o prazo de expiracao do crédito
function gerpraval(linha_pagamento, prazo_maximo_empresa) {
    //Se estiver consumindo credito
    if ($("#contabil_tipo").val() == 'D') {
        if (gervalper($("#credito_prazo_expiracao_" + linha_pagamento).val()) > gervalper(prazo_maximo_empresa)) {
            $("#credito_prazo_expiracao_" + linha_pagamento).val(prazo_maximo_empresa);
        } else if (gervalper($("#credito_prazo_expiracao_" + linha_pagamento).val()) < 0) {
            $("#credito_prazo_expiracao_" + linha_pagamento).val(0);
        }
    }
}

function concreexi_dialog(linha_pagamento) {
    callAjax('/ajax/ajaxconcreexidialog', {c_codigo: $("#pag_codigo_" + linha_pagamento).val(), cliprinom: $("#pagante_nome_" + linha_pagamento).val(),
        clicpfcnp: $("#pagante_cpf_cnpj_" + linha_pagamento).val()}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            $("#div_concreexidialog").empty();
            $("#div_concreexidialog").append(html);
            abreDialogById('concreexi_dialog', 1200, 400);
        }
    });
}

//Se há seleção de um quarto para transferencia de conta
$(document).on("change", ".conpagcri_quarto_transferencia", function () {
    quarto_selecionado = $(this).val();
    quarto_item = $(this).attr('aria-quarto-item');
    linha_pagamento = $(this).attr('aria-linha-pagamento');

    dados_quarto = quarto_selecionado.split("|");
    quarto_item_a_transferir = dados_quarto[0];
    documento_numero = dados_quarto[1];
    cliente_codigo = dados_quarto[2];
    cliente_nome = dados_quarto[3];
    cliente_sobrenome = dados_quarto[4];
    cliente_cpf = dados_quarto[5];
    cliente_cnpj = dados_quarto[6];

    //Altera nos dados do formulário da conpagcri as informações referentes ao cliente pagador e informações do documento e quarto_item
    $('#pag_codigo_' + linha_pagamento).val(cliente_codigo);
    $('#pagante_nome_' + linha_pagamento).val(cliente_nome + ' ' + cliente_sobrenome);
    $('#transferencia_documento_numero_' + linha_pagamento).val(documento_numero);
    $('#transferencia_quarto_item_' + linha_pagamento).val(quarto_item_a_transferir);
    $('#documento_numero_a_transferir_' + linha_pagamento).text(documento_numero + '-' + quarto_item_a_transferir);
    if (cliente_cpf != '')
        $('#pagante_cpf_cnpj_' + linha_pagamento).val(cliente_cpf);
    else
        $('#pagante_cpf_cnpj_' + linha_pagamento).val(cliente_cnpj);
});

//Se clicou em pagamento adicional na conpagcri
$(document).on("click", ".conitecri_pagamento_adicional", function () {
    $('.subtitulo_pagamento').css('display', 'block');

    var $div = $("div[id='pagamento_forma_dados_" + $('#total_pagamento_formas').val() + "']");
    var id_anterior = parseInt($('#total_pagamento_formas').val());
    var next_id = id_anterior + 1;
    var $klon = $div.clone().prop('id', 'pagamento_forma_dados_' + next_id);
    $klon.find('#respagfor_' + id_anterior).attr("id", 'respagfor_' + next_id).attr("name", 'respagfor_' + next_id).attr("aria-linha-pagamento", next_id);
    $klon.find('#clibtnpes').attr("aria-cliente-codigo-id", 'pag_codigo_' + next_id);
    $klon.find('#clibtnpes').attr("aria-cliente-nome-id", 'pagante_nome_' + next_id);
    $klon.find('#clibtnpes').attr("aria-cliente-cpf-cnpj-id", 'pagante_cpf_cnpj_' + next_id);
    $klon.find('#pag_codigo_' + id_anterior).attr("id", 'pag_codigo_' + next_id).attr("name", 'pag_codigo_' + next_id);
    $klon.find('#pagante_igual_contratante_' + id_anterior).attr("id", 'pagante_igual_contratante_' + next_id).attr("name", 'pagante_igual_contratante_' + next_id);
    $klon.find('#pagante_nome_' + id_anterior).attr("id", 'pagante_nome_' + next_id).attr("name", 'pagante_nome_' + next_id);
    $klon.find('#pagante_cpf_cnpj_' + id_anterior).attr("id", 'pagante_cpf_cnpj_' + next_id).attr("name", 'pagante_cpf_cnpj_' + next_id);
    $klon.find('#div_respagreg_' + id_anterior).attr("id", 'div_respagreg_' + next_id);
    $klon.find('#div_respagreg_' + next_id).empty();
    $klon.find('#fechar_acordion_' + id_anterior).attr("id", 'fechar_acordion_' + next_id).css("display", 'block').attr("aria-linha-pagamento", next_id);
    $klon.find('#rotulo_pagamento_' + id_anterior).attr("id", 'rotulo_pagamento_' + next_id).text(next_id);

    $klon.insertAfter($("div[id^='pagamento_forma_dados_']:last"));
    $('#total_pagamento_formas').val(parseInt($('#total_pagamento_formas').val()) + 1);
});


//Se clicou em remover um pagamento adicional na conpagcri
$(document).on("click", ".fechar_acordion", function () {
    linha_pagamento = parseInt($(this).attr('aria-linha-pagamento'));
    total_formas_pagamento = parseInt($('#total_pagamento_formas').val());

    //Se está removendo um item do meio
    if (linha_pagamento < total_formas_pagamento) {
        for (i = linha_pagamento + 1; i <= total_formas_pagamento; i++) {
            $('#conpagcri').find('#pagamento_forma_dados_' + i).attr('id', 'pagamento_forma_dados_' + (i - 1));
            $('#conpagcri').find('#respagfor_' + i).attr('id', 'respagfor_' + (i - 1)).attr("name", 'respagfor_' + (i - 1)).attr("aria-linha-pagamento", (i - 1));
            $('#conpagcri').find('#pag_codigo_' + i).attr('id', 'pag_codigo_' + (i - 1)).attr("name", 'pag_codigo_' + (i - 1));
            $('#conpagcri').find('#pagante_nome_' + i).attr('id', 'pagante_nome_' + (i - 1)).attr("name", 'pagante_nome_' + (i - 1));
            $('#conpagcri').find('#pagante_cpf_cnpj_' + i).attr('id', 'pagante_cpf_cnpj_' + (i - 1)).attr("name", 'pagante_cpf_cnpj_' + (i - 1));
            $('#conpagcri').find('#div_respagreg_' + i).attr('id', 'div_respagreg_' + (i - 1));
            $('#conpagcri').find('#fechar_acordion_' + i).attr('id', 'fechar_acordion_' + (i - 1)).attr("aria-linha-pagamento", (i - 1));
            $('#conpagcri').find('#rotulo_pagamento_' + i).attr("id", 'rotulo_pagamento_' + (i - 1)).text(i - 1);
            $('#conpagcri').find('#pagante_cpf_cnpj_' + i).attr("id", 'pagante_cpf_cnpj_' + (i - 1)).attr("name", 'pagante_cpf_cnpj_' + (i - 1));
            $('#conpagcri').find('#forma_referencia_' + i).attr("id", 'forma_referencia_' + (i - 1)).attr("name", 'forma_referencia_' + (i - 1));
            $('#conpagcri').find('#forma_valor_' + i).attr("id", 'forma_valor_' + (i - 1)).attr("name", 'forma_valor_' + (i - 1));
            $('#conpagcri').find('#forma_banco_' + i).attr("id", 'forma_banco_' + (i - 1)).attr("name", 'forma_banco_' + (i - 1));
            $('#conpagcri').find('#forma_agencia_' + i).attr("id", 'forma_agencia_' + (i - 1)).attr("name", 'forma_agencia_' + (i - 1));
            $('#conpagcri').find('#forma_conta_corrente_' + i).attr("id", 'forma_conta_corrente_' + (i - 1)).attr("name", 'forma_conta_corrente_' + (i - 1));
            $('#conpagcri').find('#forma_conta_corrente_dv_' + i).attr("id", 'forma_conta_corrente_dv_' + (i - 1)).attr("name", 'forma_conta_corrente_dv_' + (i - 1));
            $('#conpagcri').find('#credito_data_' + i).attr("id", 'credito_data_' + (i - 1)).attr("name", 'credito_data_' + (i - 1));
            $('#conpagcri').find('#credito_prazo_expiracao_' + i).attr("id", 'credito_prazo_expiracao_' + (i - 1)).attr("name", 'credito_prazo_expiracao_' + (i - 1));
            $('#conpagcri').find('#data_' + i).attr("id", 'data_' + (i - 1)).attr("name", 'data_' + (i - 1));
            $('#conpagcri').find('#quarto_' + i).attr("id", 'quarto_' + (i - 1)).attr("name", 'quarto_' + (i - 1));
            $('#conpagcri').find('#transferencia_documento_numero_' + i).attr("id", 'transferencia_documento_numero_' + (i - 1)).attr("name", 'transferencia_documento_numero_' + (i - 1));
            $('#conpagcri').find('#forma_pagante_nome_' + i).attr("id", 'forma_pagante_nome_' + (i - 1)).attr("name", 'forma_pagante_nome_' + (i - 1));
            $('#conpagcri').find('#forma_cartao_numero_' + i).attr("id", 'forma_cartao_numero_' + (i - 1)).attr("name", 'forma_cartao_numero_' + (i - 1));
            $('#conpagcri').find('#forma_cartao_validade_' + i).attr("id", 'forma_cartao_validade_' + (i - 1)).attr("name", 'forma_cartao_validade_' + (i - 1));
            $('#conpagcri').find('#pre_autorizacao_' + i).attr("id", 'pre_autorizacao_' + (i - 1)).attr("name", 'pre_autorizacao_' + (i - 1));
        }
    }
    $('#total_pagamento_formas').val(parseInt($('#total_pagamento_formas').val()) - 1);
    $("#pagamento_forma_dados_" + linha_pagamento).remove();

    if ($('#total_pagamento_formas').val() == 1)
        $('.subtitulo_pagamento').css('display', 'none');
});

//Quando clica em adicionar item
$(document).on("click", ".conitecri", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        var modo_exibicao = $(this).attr('aria-modo-exibicao');
        var quarto_item = $(this).attr('aria-quarto-item');
        var documento_numero = $(this).attr('aria-documento-numero');
        var redirect_page = $(this).attr('aria-redirect-page');
        var inicial_data = $(this).attr('aria-inicial-data');
        var final_data = $(this).attr('aria-final-data');
        var quarto_status_codigo = $(this).attr('aria-quarto-status');

        callAjax('ajax/ajaxgerpagsal', {form: $("#" + $('#form_atual').val()).serialize(), back_page: $('#atual_pagina').val()}, function (html) {
        });
        //Faz a chamada na propria tela
        if (modo_exibicao == 'tela')
            gerpagexi('documentocontas/conitecri', 1, {quarto_item: quarto_item, documento_numero: documento_numero,
                inicial_data: inicial_data, final_data: final_data, quarto_status_codigo: quarto_status_codigo, redirect_page: redirect_page});

        else if (modo_exibicao == 'dialog') {
            //Faz a chamada via dialog
            callAjax('/documentocontas/conitecri', {quarto_item: quarto_item, documento_numero: documento_numero,
                inicial_data: inicial_data, final_data: final_data, quarto_status_codigo: quarto_status_codigo, redirect_page: redirect_page}, function (html) {
                openDialog(html, '90%', '0.94%', 'Criação de item de conta');
            });
        }

    }
});


//Quando clica em um item já existente na conta
$(document).on("click", ".conitemod", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        var modo_exibicao = $(this).attr('aria-modo-exibicao');
        var quarto_item = $(this).attr('aria-quarto-item');
        var documento_numero = $(this).attr('aria-documento-numero');
        var redirect_page = $(this).attr('aria-redirect-page');
        var conta_item = $(this).attr('aria-item-numero');

        callAjax('ajax/ajaxgerpagsal', {form: $("#" + $('#form_atual').val()).serialize(), back_page: $('#atual_pagina').val()}, function (html) {
            //Faz a chamada na propria tela
            if (modo_exibicao == 'tela')
                gerpagexi('documentocontas/conitemod', 1, {quarto_item: quarto_item, documento_numero: documento_numero,
                    redirect_page: redirect_page, empresa_codigo: $('#gerempcod').val(), conta_item: conta_item});

            else if (modo_exibicao == 'dialog') {

                //Faz a chamada via dialog
                callAjax('/documentocontas/conitemod', {quarto_item: quarto_item, documento_numero: documento_numero,
                    redirect_page: redirect_page, empresa_codigo: $('#gerempcod').val(), conta_item: conta_item}, function (html) {
                    if (html == 'sessao_expirada')
                        window.location.href = web_root_complete + 'geral/gertelpri';
                    else
                        openDialog(html, '90%', '0.94%', 'Modificação de item de conta');
                });
            }
        });
    }

});

//Quando clica em adicionar pagamento
$(document).on("click", ".conpagcri", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        var documento_numero = $(this).attr('aria-documento-numero');
        var quarto_item = $(this).attr('aria-quarto-item');

        var pagante_codigo = $(this).attr('aria-pagante-codigo');
        var pagante_nome = $(this).attr('aria-pagante-nome');
        var pagante_cpf_cnpj = $(this).attr('aria-pagante-cpf-cnpj');
        var redirect_page = $(this).attr('aria-redirect-page');
        var evento = $(this).attr('aria-evento');
        var pagamento_total = $(this).attr('aria-pagamento-total');
        var modo_exibicao = $(this).attr('aria-modo-exibicao');

        prossegue_pagamento = 1;
        //Verifica se o status da reserva é preliminar, ou seja, não permite pagamentos em reserva preliminar
        if (pagamento_total == 1) {
            //verifica se pelo menos um quarto é não confirmado
            $("[id^='conpagcri_button_']").each(function () {
                id = this.id;
                quarto_item = id.replace('conpagcri_button_', '');
                if ($('#' + this.id).attr('aria-quarto-status') < 2) {
                    prossegue_pagamento = 0;
                }
            });
        } else {
            if ($(this).attr('aria-quarto-status') < 2) {
                prossegue_pagamento = 0;
            }
        }

        if (!prossegue_pagamento) {
            callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 112}, function (html) {
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
                                    dialog.dialog('close');
                                }
                            },
                            {
                                text: html.botao_2_texto,
                                click: function () {
                                    window.location.href = web_root_complete + '?page=reservas/resdocmod/' + documento_numero + '/' + $('#gerempcod').val() + '/' + quarto_item;
                                }
                            }
                        ]
                    });
                    dialog.dialog('open');
                }
            });
        } else {
            callAjax('/ajax/ajaxgerpagsal', {form: $("#" + $(this).attr('aria-form-id')).serialize(), back_page: $(this).attr('aria-back-page')}, function (html) {
                if (html == 'sessao_expirada')
                    window.location.href = web_root_complete + 'geral/gertelpri';
                else {
                    callAjax('/documentocontas/conpagcri', {documento_numero: documento_numero, quarto_item: quarto_item, pagante_codigo: pagante_codigo, pagante_nome: pagante_nome, pagante_cpf_cnpj: pagante_cpf_cnpj,
                        redirect_page: redirect_page, data: $('#atual_data').val(), evento: evento,
                        pagamento_total: pagamento_total, quarto_itens: $('#quarto_itens').val()}, function (html) {
                        console.log(html);
                        if (html == 'sessao_expirada')
                            window.location.href = web_root_complete + 'geral/gertelpri';
                        else
                            openDialog(html, '90%', '0.94%', 'Pagamento');
                    });
                }
            });
        }
    }
});

//Alterna a exibição dos campos entre desconto e cortesia
$(document).on("change", ".desconto_cortesia", function () {
    desconto_cortesia = $(this).val();
    if (desconto_cortesia == 'c') {
        $("#gerdesfat").prop('disabled', 'disabled');
        $("#gerdesfat").val($("#preco_anterior").val());
        $("#preco_posterior").val(gervalexi(0));
        $("#gerdestip").prop('disabled', 'disabled');
        $("#gerdestip").css('display', 'none');
        $("#gerdestip_label").css('display', 'none');
        $("#gerdestip_label").parent().css('display', 'none');
        $("#gerdestip").val('v');
        $("#gerdesval").css('display', 'none');
        $("#motivos-cortesia").css('display', 'block');
        $("#motivos-desconto").css('display', 'none');
        $("#motivos-acrescimo").css('display', 'none');
        $("#label-moeda").css('display', 'none');
        $("#label-moeda").parent().css('display', 'none');
        $("#gerdesval").val($("#preco_anterior").val());
    } else {
        $("#gerdesfat").removeAttr('disabled');
        $("#gerdestip").removeAttr('disabled');
        $("#preco_posterior").val($('#preco_anterior').val());
        $("#gerdesfat").val(gervalexi(0));
        $("#gerdesval").val(gervalexi(0));
        $("#label-moeda").css('display', 'block');
        $("#label-moeda").parent().css('display', 'block');
        $("#gerdestip").css('display', 'block');
        $("#gerdestip_label").parent().css('display', 'inline');
        $("#gerdestip_label").css('display', 'inline');
        $("#gerdesval").css('display', 'block');
        $("#motivos-cortesia").css('display', 'none');
        if (desconto_cortesia == 'd') {
            $("#motivos-desconto").css('display', 'block');
            $("#motivos-acrescimo").css('display', 'none');
            $("#motivos-cortesia").css('display', 'none');
        } else {
            $("#motivos-acrescimo").css('display', 'block');
            $("#motivos-desconto").css('display', 'none');
            $("#motivos-cortesia").css('display', 'none');
        }
    }
});

//Altera valores de campos que influenciam no total do desconto
$(document).on("keyup change", ".atualiza_valor_desconto", function () {
    var tipo_desconto = $("#gerdestip").val();
    var fator = gervalper($("#gerdesfat").val());
    var preco_total = $("#conpretot_desc").val();
    var desconto_cortesia = $('input[name="desc_cortesia"]:checked').val();
    if (tipo_desconto == 'p') {
        //Desconto em porcentagem não utiliza as casas decimais
        $('#gerdesfat').removeClass('moeda').addClass('moeda_sem_decimais');
        desconto_valor = (preco_total * fator) / 100;
    } else if (tipo_desconto == 'v') {
        //Desconto em valor precisa dos decimais
        $('#gerdesfat').removeClass('moeda_sem_decimais').addClass('moeda');
        desconto_valor = fator;
    }

    if (desconto_cortesia == 'd') {
        $("#gerdesval").val(gervalexi(desconto_valor));
        $("#preco_posterior").val(gervalexi(preco_total - desconto_valor));
    } else if (desconto_cortesia == 'c') {
        $("#gerdesval").val(gervalexi(desconto_valor));
        $("#preco_posterior").val(gervalexi(0));
    } else { //se for acréscimo
        $("#gerdesval").val(gervalexi(desconto_valor));
        $("#preco_posterior").val(gervalexi(parseFloat(preco_total) + parseFloat(desconto_valor)));
    }
});

//Atua como uma máscara limitando os valores de desconto de acordo com o fator
$(document).on("keyup", ".desconto_fator", function () {
    var desconto_cortesia = $('input[name="desc_cortesia"]:checked').val();

    if (desconto_cortesia == 'd') {
        if ($("#gerdestip").val() == 'p') {
            if (gervalper($("#gerdesfat").val()) > 100)
                $("#gerdesfat").val(100);
            if (gervalper($("#gerdesfat").val()) < 0)
                $("#gerdesfat").val(gervalexi(0));
        } else {
            if (gervalper($("#gerdesfat").val()) > gervalper($("#preco_anterior").val()))
                $("#gerdesfat").val(gervalexi($("#preco_anterior").val()));
            if (gervalper($("#gerdesfat").val()) < 0)
                $("#gerdesfat").val(gervalexi(0));
        }
    }
    $(".atualiza_valor_desconto").trigger('change');
});


$(document).on("change", "#gerdestip", function () {

    var desconto_cortesia = $('input[name="desc_cortesia"]:checked').val();

    if (desconto_cortesia == 'd') {
        if ($("#gerdestip").val() == 'p') {
            if (gervalper($("#gerdesfat").val()) > 100)
                $("#gerdesfat").val(0);
            else
                $("#gerdesfat").val(gervalper($("#gerdesfat").val()));
        } else {
            if (gervalper($("#gerdesfat").val()) > gervalper($("#preco_anterior").val()))
                $("#gerdesfat").val(gervalexi($("#preco_anterior").val()));
            else
                $("#gerdesfat").val(gervalexi($("#gerdesfat").val()));
            if (gervalper($("#gerdesfat").val()) < 0)
                $("#gerdesfat").val(gervalexi(0));
        }
    }
    $(".atualiza_valor_desconto").trigger('change');
});

//Quando clica em adicionar desconto
$(document).on("click", ".condesapl:not(.click_disabled)", function () {

    if ($("#desc_cortesia_tmp").val() == null || $("#desc_cortesia_tmp").val() == "")
        desconto_cortesia = 'd';
    else
        desconto_cortesia = $("#desc_cortesia_tmp").val();

    if ($("#gerdestip_tmp").val() == null || $("#gerdestip_tmp").val() == "")
        desconto_tipo = 'p';
    else
        desconto_tipo = $("#gerdestip_tmp").val();

    callAjax('/documentocontas/condesapl', {preco_anterior: gervalper($("#conproqtd").val()) * gervalper($("#conpreuni").val()),
        preco_posterior: gervalper($("#conproqtd").val()) * gervalper($("#conpreuni").val()) - $("#gerdesval_tmp").val(), preco_total: gervalper($("#conproqtd").val()) * gervalper($("#conpreuni").val()),
        desconto_cortesia: desconto_cortesia, desconto_tipo: desconto_tipo, motivo_cortesia: $("#gertipmot_tmp").val(), motivo_desconto: $("#gertipmot_tmp").val(), motivo_acrescimo: $("#gertipmot_tmp").val(), desconto_fator: $("#gerdesfat_tmp").val(),
        desconto_valor: $("#gerdesval_tmp").val(), observacao: $("#gerobstit_tmp").val(), usuario_codigo: '', usuario_senha: '', tipo_conta: 'item_conta'}, function (html) {

        openDialog(html, 750, 'auto', 'Modificar valores');
    });
});


//Verifica as permissões do usuário para aplicação do desconto no item de conta
$(document).on("click", ".verifica_acesso_desconto_item_conta", function () {
    empresa_codigo = $("#gerempcod").val();
    produto_codigo = $("#conprocod").val();
    preco_total = gervalper($("#conpretot_desc").val());
    desconto_cortesia = $('input[name="desc_cortesia"]:checked').val();
    desconto_fator = gervalper($("#gerdesfat").val());
    desconto_tipo = $("#gerdestip").val();
    desconto_valor = $("#gerdesval").val();
    usuario_autorizacao = $("#gerusucod").val();
    senha_autorizacao = $("#gerlogsen").val();

    callAjax('/documentocontas/condesapl', {empresa_codigo: empresa_codigo, produto_codigo: produto_codigo,
        preco_total: preco_total, desconto_cortesia: desconto_cortesia, desconto_fator: desconto_fator, desconto_tipo: desconto_tipo,
        desconto_valor: desconto_valor, usuario_autorizacao: usuario_autorizacao, senha_autorizacao: senha_autorizacao, ajax: true}, function (html) {

        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            html = JSON.parse(html);
            //Usuario pode aplicar esse desconto
            if (html.retorno == '1') {
                $("#desc_cortesia_tmp").val($('input[name="desc_cortesia"]:checked').val());
                $("#gerdesfat_tmp").val(gervalper($("#gerdesfat").val()));
                $("#gerdestip_tmp").val($("#gerdestip").val());
                if ($('input[name="desc_cortesia"]:checked').val() == 'c')
                    $("#gertipmot_tmp").val($("#gertipmot_cort").val());
                else
                    $("#gertipmot_tmp").val($("#gertipmot_desc").val());

                $("#gerobstit_tmp").val($("#gerobstit_condesapl").val());
                $("#gerusucod_tmp").val($("#gerusucod").val());
                $("#conpretot").val($("#preco_posterior").val());
                if ($('input[name="desc_cortesia"]:checked').val() == 'a')
                    $("#gerdesval_tmp").val((-1) * gervalper($("#gerdesval").val()));
                else
                    $("#gerdesval_tmp").val(gervalper($("#gerdesval").val()));

                //$("#gerdesval_tmp").val(gervalper($("#gerdesval").val()));
                closeDialog();
                $("#conbtndes").css("background-color", "#9bbef7");
            } else {
                alert(html.mensagem);
                $("#desc_cortesia_tmp").val('');
                $("#gerdesfat_tmp").val('');
                $("#gerdestip_tmp").val('');
                $("#gertipmot_tmp").val('');
                $("#gerobstit_tmp").val('');
                $("#gerusucod_tmp").val('');
                $("#gerdesval_tmp").val('');
                $("#gerlogsen").val('');
            }
        }
    });
});

//Quando clica em salvar (em html) os descontos dados nas diárias via painel
$(document).on("click", ".condessal_diarias_respdrcri", function () {
    var total_diarias = $("#dias_estadia").val();
    if ($('#codigo_referencia_atual_total').val() == "")
        var codigo_buscado = $('#codigo_referencia_atual_diarias').val().substring(0, $('#codigo_referencia_atual_diarias').val().length - 2);
    else
        var codigo_buscado = $('#codigo_referencia_atual_total').val();

    var codigo_todas_diarias = codigo_buscado.split('_')[0] + '_' + codigo_buscado.split('_')[1] + '_' + codigo_buscado.split('_')[2];

    //Atualiza a variável info_diaria de todas as diárias
    if ($('#tarifa_manual_entrada').val() == '1') {
        console.log(codigo_todas_diarias);
        $('input[id^="tarifa_txt_' + codigo_todas_diarias + '"]').each(function () {
            var id = $(this).attr('id').replace('tarifa_txt_', '');
            $("#info_diarias_" + id).val($("#info_diarias_" + id).val().split('|')[0] + "|" + gervalper($(this).val()));
        });
    } else
        $('span[id^="tarifa_txt_' + codigo_todas_diarias + '"]').each(function () {
            var id = $(this).attr('id').replace('tarifa_txt_', '');
            $("#info_diarias_" + id).val($("#info_diarias_" + id).val().split('|')[0] + "|" + gervalper($(this).text()));
        });

    var soma_valores_diarias = 0;
    if (codigo_buscado != '') {
        for (i = 1; i <= total_diarias; i++) {
            var tarifa_desconto_tmp = $("#tarifa_desconto_tmp_" + codigo_buscado + "_" + i).val();
            $("#info_diarias_" + codigo_buscado + "_" + i).val($("#info_diarias_" + codigo_buscado + "_" + i).val() + "|" + tarifa_desconto_tmp);
            if ($('#tarifa_manual_entrada').val() == '1')
                soma_valores_diarias = soma_valores_diarias + gervalper($("#tarifa_txt_" + codigo_buscado + "_" + i).val());
            else
                soma_valores_diarias = soma_valores_diarias + gervalper($("#tarifa_txt_" + codigo_buscado + "_" + i).text());
            $("#restardia_" + codigo_buscado).css("background", "url(../img/lupa-2.png) no-repeat center center");
        }
        if ($('#atual_pagina').val() == 'reservas/resquatar') {
            $("#tarifa_" + codigo_buscado.substring(0, codigo_buscado.length)).val(gervalexi(soma_valores_diarias));
        } else
            $("#tarifa_valor_" + codigo_buscado.substring(0, codigo_buscado.length)).val(soma_valores_diarias);
    }
    //atualiza o carrinho de compras
    if ($('#atual_pagina').val() == 'reservas/resquatar')
        rescaratu();
    else
        rescaratu_respdrcri();
    closeDialog();
});


//Quando clica em adicionar desconto em uma diária na respdrcri
$(document).on("click", ".condesapl_diarias_respdrcri", function () {
    var desconto_total = $(this).attr('aria-desconto-total');

    if (desconto_total == 0) {
        var codigo_referencia = $(this).attr('aria-tarifa-referencia-id');
        $('#codigo_referencia_atual_diarias').val(codigo_referencia);
        var tarifa_desconto_tmp = $('#tarifa_desconto_tmp_' + codigo_referencia).val();
        var produto_codigo = 1;
        var desc_cortesia_tmp = tarifa_desconto_tmp.split('|')[0];
        var desc_fator_tmp = tarifa_desconto_tmp.split('|')[1];
        var desc_tipo_tmp = tarifa_desconto_tmp.split('|')[2];
        var desc_valor_tmp = tarifa_desconto_tmp.split('|')[3];
        var desc_motivo_codigo_tmp = tarifa_desconto_tmp.split('|')[4];
        var desc_observacao_tmp = tarifa_desconto_tmp.split('|')[5];
        if (desc_cortesia_tmp == 'd' || desc_cortesia_tmp == 'c')
            var preco_posterior = gervalper($('#tarifa_valor_original_' + codigo_referencia).val()) - desc_valor_tmp;
        else
            var preco_posterior = gervalper($('#tarifa_valor_original_' + codigo_referencia).val()) + parseFloat(desc_valor_tmp);

        callAjax('/documentocontas/condesapl', {preco_anterior: gervalper($('#tarifa_valor_original_' + codigo_referencia).val()),
            preco_posterior: preco_posterior,
            preco_total: gervalper($('#tarifa_valor_original_' + codigo_referencia).val()),
            desconto_cortesia: desc_cortesia_tmp, desconto_tipo: desc_tipo_tmp, motivo_cortesia: desc_motivo_codigo_tmp, motivo_desconto: desc_motivo_codigo_tmp, motivo_acrescimo: desc_motivo_codigo_tmp, desconto_fator: desc_fator_tmp,
            desconto_valor: desc_valor_tmp, observacao: desc_observacao_tmp, usuario_codigo: '', usuario_senha: '', tipo_conta: 'diaria_respdrcri', total_desconto: 0}, function (html) {
            openDialog(html, 750, 'auto', 'Modificar valores');
            $('#gerdesfat').select();
        });
        //Se for um desconto geral nas diarias
    } else {
        var codigo_referencia = $(this).attr('id').replace('total_btn_', '');
        var total_desconto_tmp = $('#total_desconto_tmp_' + codigo_referencia).val();
        var produto_codigo = 1;
        var desc_cortesia_tmp = total_desconto_tmp.split('|')[0];
        var desc_fator_tmp = total_desconto_tmp.split('|')[1];
        var desc_tipo_tmp = total_desconto_tmp.split('|')[2];
        var desc_valor_tmp = total_desconto_tmp.split('|')[3];
        var desc_motivo_codigo_tmp = total_desconto_tmp.split('|')[4];
        var desc_observacao_tmp = total_desconto_tmp.split('|')[5];

        $('#codigo_referencia_atual_total').val(codigo_referencia);

        if (desc_cortesia_tmp == 'd' || total_desconto_tmp == 'c')
            var preco_posterior = gervalper($('#total_original_' + codigo_referencia).val()) - desc_valor_tmp;
        else
            var preco_posterior = gervalper($('#total_original_' + codigo_referencia).val()) + parseFloat(desc_valor_tmp);
        callAjax('/documentocontas/condesapl', {preco_anterior: gervalper($('#total_original_' + codigo_referencia).val()),
            preco_posterior: preco_posterior,
            preco_total: gervalper($('#total_original_' + codigo_referencia).val()),
            desconto_cortesia: desc_cortesia_tmp, desconto_tipo: desc_tipo_tmp, motivo_cortesia: desc_motivo_codigo_tmp, motivo_desconto: desc_motivo_codigo_tmp, motivo_acrescimo: desc_motivo_codigo_tmp, desconto_fator: desc_fator_tmp,
            desconto_valor: desc_valor_tmp, observacao: desc_observacao_tmp, usuario_codigo: '', usuario_senha: '', tipo_conta: 'diaria_respdrcri', total_desconto: 1}, function (html) {
            openDialog(html, 750, 'auto', 'Modificar valores');
            $('#gerdesfat').select();
        });

    }
});

//Verifica as permissões do usuário para aplicação do desconto na diária
$(document).on("click", ".verifica_acesso_desconto_diaria_respdrcri", function () {
    var empresa_codigo = $("#gerempcod").val();
    var produto_codigo = 1;
    var preco_total = gervalper($("#conpretot_desc").val());
    var desconto_cortesia = $('input[name="desc_cortesia"]:checked').val();
    var desconto_fator = gervalper($("#gerdesfat").val());
    var desconto_tipo = $("#gerdestip").val();
    var desconto_valor = $("#gerdesval").val();
    var usuario_autorizacao = $("#gerusucod").val();
    var senha_autorizacao = $("#gerlogsen").val();

    callAjax('/documentocontas/condesapl', {empresa_codigo: empresa_codigo, produto_codigo: produto_codigo,
        preco_total: preco_total, desconto_cortesia: desconto_cortesia, desconto_fator: desconto_fator, desconto_tipo: desconto_tipo,
        desconto_valor: desconto_valor, usuario_autorizacao: usuario_autorizacao, senha_autorizacao: senha_autorizacao, ajax: true}, function (html) {

        //Usuario pode aplicar esse desconto
        html = JSON.parse(html);
        if (html.retorno == '1') {
            var desc_cortesia_aplicado = $('input[name="desc_cortesia"]:checked').val();
            var desc_tipo_aplicado = $("#gerdestip").val();
            var desc_fator_aplicado = $("#gerdesfat").val();
            var desc_valor_aplicado = $("#gerdesval").val();

            if (desc_cortesia_aplicado == 'c')
                motivo_tipo_codigo = $("#gertipmot_cort").val();
            else if (desc_cortesia_aplicado == 'd')
                motivo_tipo_codigo = $("#gertipmot_desc").val();
            else if (desc_cortesia_aplicado == 'a')
                motivo_tipo_codigo = $("#gertipmot_acre").val();

            //Se nao for desconto em todas as diarias
            if ($('#total_desconto').val() == 0) {
                $('#tarifa_desconto_tmp_' + $('#codigo_referencia_atual_diarias').val()).
                        val(desc_cortesia_aplicado + "|" + gervalper(desc_fator_aplicado) + "|" + desc_tipo_aplicado + "|" +
                                gervalper(desc_valor_aplicado) + "|" + motivo_tipo_codigo + "|" + $("#gerobstit_condesapl").val() + "|" +
                                $("#gerusucod").val());
                $('#tarifa_btn_' + $('#codigo_referencia_atual_diarias').val()).css('background', 'url(../img/lapis-2.png) no-repeat center center');
                if ($('#tarifa_manual_entrada').val() == '1')
                    $('#tarifa_txt_' + $('#codigo_referencia_atual_diarias').val()).val($('#preco_posterior').val());
                else
                    $('#tarifa_txt_' + $('#codigo_referencia_atual_diarias').val()).text($('#preco_posterior').val());

                //Limpa possiveis descontos no valor total
                $('#total_desconto_tmp_' + $('#codigo_referencia_atual_diarias').val().substring(0, $('#codigo_referencia_atual_diarias').val().length - 2)).val('d|0.00|p|0.00|||');
                $('#codigo_referencia_atual_total').val('');
                $('#total_btn_' + $('#codigo_referencia_atual_diarias').val().substring(0, $('#codigo_referencia_atual_diarias').val().length - 2)).css('background', 'url(../img/lapis-1.png) no-repeat center center');
            } else {
                //Se for desconto em todas as diárias
                $('#total_desconto_tmp_' + $('#codigo_referencia_atual_total').val()).
                        val(desc_cortesia_aplicado + "|" + gervalper(desc_fator_aplicado) + "|" + desc_tipo_aplicado + "|" +
                                gervalper(desc_valor_aplicado) + "|" + motivo_tipo_codigo + "|" + $("#gerobstit_condesapl").val() + "|" +
                                $("#gerusucod").val());
                $('#total_btn_' + $('#codigo_referencia_atual_total').val()).css('background', 'url(../img/lapis-2.png) no-repeat center center');

                //Atualiza cada diária
                var total_diarias = $("#dias_estadia").val();
                var codigo_referencia_parcial = $('#codigo_referencia_atual_total').val().split('_')[0] + '_' + $('#codigo_referencia_atual_total').val().split('_')[1] + '_' + $('#codigo_referencia_atual_total').val().split('_')[2];
                var item_base_preco = [];

                for (var i = 1; i <= total_diarias; i++) {
                    codigo_referencia_diaria_completo = codigo_referencia_parcial + '_' + i;
                    item_base_preco.push($('#tarifa_valor_original_' + codigo_referencia_diaria_completo).val());
                }

                var retorno_condesdis = condesdis($('#total_original_' + codigo_referencia_parcial).val(), item_base_preco, desc_cortesia_aplicado, desc_fator_aplicado, desc_tipo_aplicado);

                for (var i = 1; i <= total_diarias; i++) {
                    codigo_referencia_diaria_completo = codigo_referencia_parcial + '_' + i;

                    $('#tarifa_desconto_tmp_' + codigo_referencia_diaria_completo).val(desc_cortesia_aplicado + "|" + retorno_condesdis['item_desconto_fator'][i - 1] + "|" + retorno_condesdis['item_desconto_tipo'][i - 1] + "|" +
                            retorno_condesdis['item_desconto_valor'][i - 1] + "|" + motivo_tipo_codigo + "|" + $("#gerobstit_condesapl").val() + "|" + $("#gerusucod").val());

                    if ($('#tarifa_manual_entrada').val() == '1')
                        $('#tarifa_txt_' + codigo_referencia_diaria_completo).val(gervalexi(retorno_condesdis['item_posterior_preco'][i - 1]));
                    else
                        $('#tarifa_txt_' + codigo_referencia_diaria_completo).text(gervalexi(retorno_condesdis['item_posterior_preco'][i - 1]));

                    $('#tarifa_btn_' + codigo_referencia_diaria_completo).css('background', 'url(../img/lapis-2.png) no-repeat center center');
                }
            }
            closeDialog();
        } else {
            alert(html.mensagem);
            $('#tarifa_desconto_tmp_' + $('#codigo_referencia_atual_diarias').val()).
                    val('d|0.00|p|0.00|||');
        }

        //Atualiza a exibição do total na restardia
        var total_tarifas_restardia = 0;
        if ($('#tarifa_manual_entrada').val() == '1') {
            $('.tarifa_manual_entrada').each(function () {
                total_tarifas_restardia += gervalper($(this).val());
            });
        } else {
            $('.tarifa_txt').each(function () {
                total_tarifas_restardia += gervalper($(this).text());
            });
        }
        $('#total_tarifas_restardia').text(gervalexi(total_tarifas_restardia));
    });

});

//Distribuir a aplicação de desconto aplicado a um valor total para os itens de valor que compoem esse total
function condesdis(total_base_preco, item_base_preco, total_desconto_cortesia_acrescimo, total_desconto_fator, total_desconto_tipo) {
    if (total_desconto_cortesia_acrescimo == 'c') {
        total_desconto_fator = 100;
        total_desconto_tipo = 'p';
    }

    if (total_desconto_tipo == 'v') {
        var desconto_percentual = gervalper(total_desconto_fator) / gervalper(total_base_preco);
        var ultimo_indice = item_base_preco.length - 1;
    }

    var item_desconto_cortesia_acrescimo = [];
    var item_desconto_tipo = [];
    var item_desconto_fator = [];
    var item_desconto_valor = [];
    var item_posterior_preco = [];

    for (var n = 0; n < item_base_preco.length; n++) {
        item_desconto_cortesia_acrescimo.push(total_desconto_cortesia_acrescimo);
        item_desconto_tipo.push(total_desconto_tipo);
        if (total_desconto_tipo == 'v') {
            if (n != ultimo_indice) {
                item_desconto_fator.push(parseFloat((desconto_percentual * gervalper(item_base_preco[n])).toFixed(2)));
            } else {
                var somatoria_desconto_fator = 0;
                for (var k = 0; k < item_desconto_fator.length; k++)
                    somatoria_desconto_fator = somatoria_desconto_fator + item_desconto_fator[k];
                item_desconto_fator.push(parseFloat(gervalper(total_desconto_fator) - somatoria_desconto_fator).toFixed(2));
            }
            item_desconto_valor.push(item_desconto_fator[n]);
        } else {  //Desconto de tipo percentual
            item_desconto_fator.push(total_desconto_fator);
            item_desconto_valor.push(total_desconto_fator * gervalper(item_base_preco[n]) / 100);
        }

        if (total_desconto_cortesia_acrescimo == 'a')
            item_desconto_valor[n] = item_desconto_valor[n] * (-1);

        item_posterior_preco.push(gervalper(item_base_preco[n]) - parseFloat(item_desconto_valor[n]));
    }
    var retorno = {item_desconto_cortesia_acrescimo: item_desconto_cortesia_acrescimo, item_desconto_tipo: item_desconto_tipo, item_desconto_fator: item_desconto_fator, item_desconto_valor: item_desconto_valor, item_posterior_preco: item_posterior_preco};
    return retorno;
}

//Quando clica em adicionar desconto nos adicionais
$(document).on("click", ".condesapl_adicionais", function () {
    var codigo_referencia = $(this).attr('aria-adicional-referencia-id');
    $('#codigo_referencia_atual').val(codigo_referencia);
    var adicional_desconto_tmp = $('#adicional_desconto_tmp_' + codigo_referencia).val();
    var produto_codigo = 1;
    var desc_cortesia_tmp = adicional_desconto_tmp.split('|')[0];
    var desc_fator_tmp = adicional_desconto_tmp.split('|')[1];
    var desc_tipo_tmp = adicional_desconto_tmp.split('|')[2];
    var desc_valor_tmp = adicional_desconto_tmp.split('|')[3];
    var desc_motivo_codigo_tmp = adicional_desconto_tmp.split('|')[4];
    var desc_observacao_tmp = adicional_desconto_tmp.split('|')[5];
    if (desc_cortesia_tmp == 'd' || desc_cortesia_tmp == 'c')
        var preco_posterior = gervalper($('#adicional_total_original_' + codigo_referencia).val()) - desc_valor_tmp;
    else
        var preco_posterior = gervalper($('#adicional_total_original_' + codigo_referencia).val()) + parseFloat(desc_valor_tmp);

    callAjax('/documentocontas/condesapl', {preco_anterior: $('#adicional_total_original_' + codigo_referencia).val(),
        preco_posterior: preco_posterior,
        preco_total: gervalper($('#adicional_total_original_' + codigo_referencia).val()),
        desconto_cortesia: desc_cortesia_tmp, desconto_tipo: desc_tipo_tmp, motivo_cortesia: desc_motivo_codigo_tmp, motivo_desconto: desc_motivo_codigo_tmp, motivo_acrescimo: desc_motivo_codigo_tmp, desconto_fator: desc_fator_tmp,
        desconto_valor: desc_valor_tmp, observacao: desc_observacao_tmp, usuario_codigo: '', usuario_senha: '', tipo_conta: 'adicional'}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else
            openDialog(html, 750, 'auto', 'Modificar valores');
    });
});


//Verifica as permissões do usuário para aplicação do desconto nos adicionais
$(document).on("click", ".verifica_acesso_desconto_adicional", function () {
    var empresa_codigo = $("#gerempcod").val();
    var produto_codigo = 1;
    var preco_total = gervalper($("#conpretot_desc").val());
    var desconto_cortesia = $('input[name="desc_cortesia"]:checked').val();
    var desconto_fator = gervalper($("#gerdesfat").val());
    var desconto_tipo = $("#gerdestip").val();
    var desconto_valor = $("#gerdesval").val();
    var usuario_autorizacao = $("#gerusucod").val();
    var senha_autorizacao = $("#gerlogsen").val();
    callAjax('/documentocontas/condesapl', {empresa_codigo: empresa_codigo, produto_codigo: produto_codigo,
        preco_total: preco_total, desconto_cortesia: desconto_cortesia, desconto_fator: desconto_fator, desconto_tipo: desconto_tipo,
        desconto_valor: desconto_valor, usuario_autorizacao: usuario_autorizacao, senha_autorizacao: senha_autorizacao, ajax: true}, function (html) {

        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            html = JSON.parse(html);
            //Usuario pode aplicar esse desconto
            if (html.retorno == '1') {
                if ($('input[name="desc_cortesia"]:checked').val() == 'c')
                    var motivo_tipo_codigo = $("#gertipmot_cort").val();
                else if ($('input[name="desc_cortesia"]:checked').val() == 'd')
                    var motivo_tipo_codigo = $("#gertipmot_desc").val();
                else if ($('input[name="desc_cortesia"]:checked').val() == 'a')
                    var motivo_tipo_codigo = $("#gertipmot_acre").val();

                $('#adicional_desconto_tmp_' + $('#codigo_referencia_atual').val()).
                        val($('input[name="desc_cortesia"]:checked').val() + "|" + gervalper($("#gerdesfat").val()) + "|" + $("#gerdestip").val() + "|" +
                                gervalper($("#gerdesval").val()) + "|" + motivo_tipo_codigo + "|" + $("#gerobstit_condesapl").val() + "|" +
                                $("#gerusucod").val());
                $('#adicional_btn_' + $('#codigo_referencia_atual').val()).css('background', 'url(../img/lapis-2.png) no-repeat center center');
                $("#adicional_total_" + $('#codigo_referencia_atual').val()).val($('#preco_posterior').val());

                //Atualiza dados do carrinho e hiddens
                var quarto_item = $('#codigo_referencia_atual').val().split("_")[0];

                $('#total_adicionais_txt_' + quarto_item).text(gervalexi(restotadi(quarto_item)));
                $('#total_adicionais_' + quarto_item).val(restotadi(quarto_item));
                $('#total_preco_txt').text(gervalexi(gervalper(gervalexi($('#total_original').val())) + restotadi2()));
                $('#total_preco').val(gervalper($('#total_preco_txt').text()));
                if ($('#adicional_qtd_' + $('#codigo_referencia_atual').val()).val() > 0) {
                    $('#adicional_total_' + $('#codigo_referencia_atual').val() + '_txt').text($('#preco_posterior').val());
                } else
                    $('#adicional_total_' + $('#codigo_referencia_atual').val() + '_txt').text('');

                //recalcula os extras, considerando agora os adicionais
                var total_tarifas = 0;
                for (i = 1; i <= $('#resquaqtd').val(); i++)
                    total_tarifas += parseInt($('#tarifa_valor_' + i).val());

                var total_adicionais_com_taxa = 0;
                $("[id^='adicional_qtd_']").each(function () {
                    id = this.id;
                    quarto_item = id.replace('adicional_qtd_', '').split('_')[0];
                    linha_adicional = id.replace('adicional_qtd_', '').split('_')[1];
                    if ($('#servico_taxa_incide_' + quarto_item + '_' + linha_adicional).val() == 1) {
                        total_adicionais_com_taxa += gervalper($("#adicional_total_" + quarto_item + '_' + linha_adicional).val()) * $('#adicional_qtd_' + quarto_item + '_' + linha_adicional).val();
                    }
                });

                var total_servico_taxa = ((total_tarifas + total_adicionais_com_taxa) * parseInt($('#servico_taxa').val())) / 100;
                $('#total_servico_taxa').text(gervalexi(total_servico_taxa));
                closeDialog();
            } else {
                alert(html.mensagem);
                $('#adicional_desconto_tmp_' + $('#codigo_referencia_atual').val()).
                        val('d|0.00|p|0.00|||');
            }
        }
    });
});

//Quando clica no botao de excluir uma conta
$(document).on("click", ".excluir_conta", function () {
    callAjax('/documentocontas/coniteexc', {conta_item: $('#geritetit').val(), documento_numero: $('#resdocnum_modificar').val(), quarto_item: $('#quarto_item').val(), pagina_referencia: $('#url_redirect_after').val(),
        servico_taxa_incide: $('#servico_taxa_incide').val()},
    function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else
            openDialog(html, 850, 300);
    });
});

//Verifica se foi selecionado um produto no autocomplete. Caso nao tenha, tenta buscá-lo pelo código
$(document).on("blur", ".produto_autocomplete", function () {
    var codigo_produto_id = $(this).attr('data-produto-codigo');

    if ($('#' + codigo_produto_id).val() == '' && $('#has_select').val() == '0' && $('#conpronom').val().trim().length > 0) {
        callAjax('/geral/progeraut', {empresa_codigo: $('#gerempcod').val(), produto_codigo: $(this).val(),
            busca_apenas_adicionais: $('#busca_apenas_adicionais').val(),
            venda_ponto_codigo: $('#convenpon').val(), vendavel: $('#vendavel').val(), quarto_status_codigo: $('#quarto_status_codigo_atual').val()},
        function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                var dados = JSON.parse(html);
                //encontrou um produto com esse código
                if (dados.length > 0) {
                    $("#conpronom").val(dados[0].nome);
                    $('#' + codigo_produto_id).val(dados[0].produto_codigo);
                    $("#concontip").val(dados[0].contabil_tipo);
                    $("#conpreuni").val(gervalexi(dados[0].preco));
                    $('#variavel_fator_nome').text(dados[0].variavel_fator_nome);
                    $('#servico_taxa_incide').val(dados[0].servico_taxa_incide);
                    $('#horario_modificacao_tipo').val(dados[0].horario_modificacao_tipo);
                    $('#horario_modificacao_valor').val(dados[0].horario_modificacao_valor);
                    $('#conpretot').val(gervalexi($('#conproqtd').val() * dados[0].preco));
                    $('#has_select').val('1');

                    if (dados[0].conta_editavel_preco == 1) {
                        //Libera o preço para edição/desconto e exibe outros campos pertinentes
                        $('#conpreuni').prop('readonly', false);
                        $('#item_desconto_geral').css('display', 'block');
                        if (ui.item.contabil_tipo == 'C') {
                            $('#gertipmot_geral_desc').css('display', 'block');
                            $('#gertipmot_geral_acre').css('display', 'none');
                        } else {
                            $('#gertipmot_geral_desc').css('display', 'none');
                            $('#gertipmot_geral_acre').css('display', 'block');
                        }
                    } else {
                        $('#conpreuni').prop('readonly', true);
                    }

                    //Verifica se o desconto é habilitado
                    if (dados[0].desconto_habilita == 1)
                        $('#conbtndes').css('display', 'block');
                    else
                        $('#conbtndes').css('display', 'none');
                } else {
                    $("#conpronom").val('');
                    $("#conpronom").val('');
                    $('#' + codigo_produto_id).val('');
                    $("#concontip").val('');
                    $("#conpreuni").val('');
                    $('#variavel_fator_nome').text('');
                    $('#conpretot').val(gervalexi(0));
                    $('#servico_taxa_incide').val('0');
                    $('#has_select').val('0');
                    $('#conpreuni').prop('readonly', true);
                    $('#conbtndes').css('display', 'block');
                    $('#item_desconto_geral').css('display', 'none');
                    $('#gertipmot_geral_desc').css('display', 'none');
                    $('#gertipmot_geral_acre').css('display', 'none');
                    $('#horario_modificacao_tipo').val(null);
                    $('#horario_modificacao_valor').val(null);
                }


            }
        });
    }
});


//Recalcula o preço pela geracesseq quando se muda o produto 
$(document).on("change", ".recalcula_preco", function () {
    if ($("#conprocod").val() != '') {
        $.ajax({
            type: 'POST',
            url: web_root_complete + 'geral/geraceseq',
            data: {produto_codigo: $("#conprocod").val().split("|")[0], empresa_codigo: $('#gerempcod').val(), venda_ponto_codigo: $("#convenpon").val()},
            success: function (html) {
                if (html == 'sessao_expirada')
                    window.location.href = web_root_complete + 'geral/gertelpri';
                else {
                    var dados_produto = JSON.parse(html);
                    $("#conpreuni").val(gervalexi(dados_produto.produto_preco));
                    $("#servico_taxa_incide").val(dados_produto.servico_taxa_incide);
                    $("#conpretot").val(gervalexi(gervalper($("#conproqtd").val()) * gervalper($("#conpreuni").val())));
                }
            },
            error: function (html) {
                console.log(html);
            }
        });
    }
});

$(document).on('click', '.accordion-conta', function () {
    if ($("#opened_acordions").val().indexOf($(this).attr('aria-accordion-item') + "|") < 0) {
        $("#opened_acordions").val($("#opened_acordions").val() + ($(this).attr('aria-accordion-item') + "|"));
    } else {
        $("#opened_acordions").val($("#opened_acordions").val().replace($(this).attr('aria-accordion-item') + "|", ""));
    }

    this.classList.toggle("active");
    this.nextElementSibling.classList.toggle("show");
});

//Quando clica em visualizar a conta de uma reserva (quarto item)
$(document).on("click", ".conta", function (e) {
    e.preventDefault();

    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');

        var documento_numero = $(this).attr('aria-documento-numero');

        $('#atual_dialog').val('dialog_level_1');
        $('#atual_dialog_page').val('/documentocontas/conresexi/');
        $('#atual_dialog_params').val("{'resdocnum':'" + $(this).attr('aria-documento-numero') + "', 'permite_busca':'0', 'forca_busca':'1', 'opened_acordions':'" + $(this).attr('aria-quarto-item') + '|' + "' }");
        callAjax('/documentocontas/conresexi', {resdocnum: documento_numero, ajax: 1, permite_busca: 0, opened_acordions: $(this).attr('aria-quarto-item') + '|'}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else
                openDialog(html, '90%', '0.94%', 'Conta ' + documento_numero);
        });
    }
});

//Quando se clica em uma linha de pagamento
$(document).on("click", ".conpagexi", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        var documento_numero = $(this).attr('data-documento-numero');
        var quarto_item = $(this).attr('data-quarto-item');
        var conta_item = $(this).attr('data-item-numero');

        callAjax('ajax/ajaxgerpagsal', {form: $("#" + $('#form_atual').val()).serialize(), back_page: $('#atual_pagina').val()}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                gerpagexi('documentocontas/conpagexi/' + $('#gerempcod').val() + '/' + documento_numero + '/' + quarto_item + '/' + conta_item, 1, {});
            }
        });
    }
});

//Responsável por alterar os labels e divs na tela de pagamento da conpagcri
$(document).on("change", "#conpagcri .pagamento_reembolso", function () {
    if ($(this).val() == 'pagamento') {
        $('.ui-dialog-title').text("Pagamento");
        $('.conitecri_pagamento_adicional').text("Pagamento adicional");
    } else {
        $('.ui-dialog-title').text("Reembolso");
        $('.conitecri_pagamento_adicional').text("Reembolso adicional");
    }

});


//Responsável por alterar os valores na restardia
$(document).on("keyup", ".tarifa_manual_entrada", function () {
    var codigo_referencia = $(this).attr('aria-tarifa-referencia-id');
    $('#codigo_referencia_atual').val(codigo_referencia);
    $('#tarifa_diaria_' + codigo_referencia).val($(this).val());

    var info_diaria_data = $("#info_diarias_" + codigo_referencia).val().split('|')[0];

    // $("#info_diarias_" + codigo_referencia).val($("#info_diarias_" + codigo_referencia).val().split('|')[0] + "|" + gervalper($(this).val()));
    $('#tarifa_txt_' + codigo_referencia).attr('value', $(this).val());

    //Atualiza informações da respdrcri
    $('#codigo_referencia_atual_diarias').val(codigo_referencia);
    var quarto_item = codigo_referencia.split('_')[0];
    var quarto_tipo_codigo = codigo_referencia.split('_')[1];
    var tarifa_tipo_codigo = codigo_referencia.split('_')[2];
    var linha_diaria = codigo_referencia.split('_')[3];
    $('#tarifa_valor_' + quarto_item + '_' + quarto_tipo_codigo + '_' + tarifa_tipo_codigo).val($(this).val());
    $('#tarifa_valor_original_' + quarto_item + '_' + quarto_tipo_codigo + '_' + tarifa_tipo_codigo + '_' + linha_diaria).val($(this).val());

    //Atualiza a exibição do total na restardia
    var total_tarifas_restardia = 0;
    $('.tarifa_manual_entrada').each(function () {
        total_tarifas_restardia += gervalper($(this).val());
    });
    $('#total_tarifas_restardia').text(gervalexi(total_tarifas_restardia));
    //$('#total_tarifas_restardia_original').val(gervalexi(total_tarifas_restardia));
    $('#total_original_' + quarto_item + '_' + quarto_tipo_codigo + '_' + tarifa_tipo_codigo).val(gervalexi(total_tarifas_restardia));

    //Habilita/desabilita o botao de desconto
    if (gervalper($(this).val()) > 0)
        $('#tarifa_btn_' + quarto_item + '_' + quarto_tipo_codigo + '_' + tarifa_tipo_codigo + '_' + linha_diaria).css('display', 'block');
    else
        $('#tarifa_btn_' + quarto_item + '_' + quarto_tipo_codigo + '_' + tarifa_tipo_codigo + '_' + linha_diaria).css('display', 'none');

    //Habilita/desabilita o botao de desconto geral
    if (total_tarifas_restardia > 0)
        $('#total_btn_' + quarto_item + '_' + quarto_tipo_codigo + '_' + tarifa_tipo_codigo).css('display', 'block');
    else
        $('#total_btn_' + quarto_item + '_' + quarto_tipo_codigo + '_' + tarifa_tipo_codigo).css('display', 'none');

});

//Verifica as alterações da restardia na resquatar
$(document).on("keyup", "#form_restardia .tarifa_manual_entrada", function () {
    var codigo_referencia = $(this).attr('aria-tarifa-referencia-id');
    var quarto_item = codigo_referencia.split('_')[0];
    var quarto_tipo_codigo = codigo_referencia.split('_')[1];
    var tarifa_tipo_codigo = codigo_referencia.split('_')[2];

    //Atualiza a exibição do total na restardia
    var total_tarifas_restardia = 0;
    $("input[id^='tarifa_txt_" + quarto_item + "_" + quarto_tipo_codigo + "_" + tarifa_tipo_codigo + "_']").each(function () {
        total_tarifas_restardia += gervalper($(this).val());
    });
    $('#total_tarifas_restardia_' + quarto_item + "_" + quarto_tipo_codigo + "_" + tarifa_tipo_codigo).text(gervalexi(total_tarifas_restardia));
});

//Verifica se o usuário pediu pra esconder os itens estornados
$(document).on("change", "#ocultar_estornados", function () {
    if ($(this).is(":checked")) {
        $('.item_estornado').css('display', 'none');
        $('#atual_dialog_params').val($('#atual_dialog_params').val().substring(0, $('#atual_dialog_params').val().length - 1) + ',\'ocultar_estornados\':\'1\'}');
    } else {
        $('.item_estornado').css('display', 'table-row');
        $('#atual_dialog_params').val($('#atual_dialog_params').val().substring(0, $('#atual_dialog_params').val().length - 1) + ',\'ocultar_estornados\':\'0\'}');
    }
});
