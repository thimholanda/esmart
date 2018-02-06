//Função que carrega os dados do cliente para editar
function clicadcri_ajax() {
    linha_pagamento_atual = $("#linha_pgto_atual").val();
    $("#clicadcri")[0].reset();
    abreDialogById('clicadcri_dialog', 1200, 500);
}


function cliunival1(id_cliente_univoco_campo_1, id_cliente_univoco_campo_2, saida_tipo) {
    cliente_univoco_campo_1 = $('#' + id_cliente_univoco_campo_1).attr('data-univoco-campo-1');
    tela_cliente_univoco_campo_1 = $('#' + id_cliente_univoco_campo_1).val();
    cliente_univoco_campo_2 = $('#' + id_cliente_univoco_campo_2).attr('data-univoco-campo-2');
    tela_cliente_univoco_campo_2 = $('#' + id_cliente_univoco_campo_2).val();

    if (tela_cliente_univoco_campo_1 != "") {
        callAjax('/clientes/cliunival1', {empresa_grupo_codigo: $('#empresa_grupo_codigo_js').val(), cliente_univoco_campo_1: cliente_univoco_campo_1, tela_cliente_univoco_campo_1: tela_cliente_univoco_campo_1,
            cliente_univoco_campo_2: cliente_univoco_campo_2, tela_cliente_univoco_campo_2: tela_cliente_univoco_campo_2, retorno: 'json'}, function (html) {
            var obj_html = JSON.parse(html);
            if (obj_html.retorno == 0) {
                $('#germencri_mensagem').text(obj_html.mensagem.mensagem);
                if (saida_tipo == 'dialog') {

                    dialog = $('#exibe-germencri').dialog({
                        title: obj_html.mensagem.titulo_texto,
                        dialogClass: 'no_close_dialog',
                        autoOpen: false,
                        height: 200,
                        width: 530,
                        modal: true,
                        buttons: [
                            {
                                text: obj_html.mensagem.botao_1_texto,
                                click: function () {
                                    $("#" + id_cliente_univoco_campo_1).val('');
                                    $("#" + id_cliente_univoco_campo_2).val('');
                                    $(this).dialog('close');
                                }
                            },
                            {
                                text: obj_html.mensagem.botao_2_texto,
                                click: function () {
                                    cliunival2(obj_html.cliente_dados);
                                    $(this).dialog('close');
                                }
                            }]
                    });
                    dialog.dialog('open');
                } else {
                    dialog = $('#exibe-germencri').dialog({
                        title: obj_html.mensagem.titulo_texto,
                        dialogClass: 'no_close_dialog',
                        autoOpen: false,
                        height: 200,
                        width: 530,
                        modal: true,
                        buttons: [
                            {
                                text: obj_html.mensagem.botao_1_texto,
                                click: function () {
                                    $("#" + id_cliente_univoco_campo_1).val('');
                                    $("#" + id_cliente_univoco_campo_2).val('');
                                    $(this).dialog('close');
                                }
                            }]
                    });
                    dialog.dialog('open');
                }
            }
        });
    }
}


function cliunival2(cliente_dados) {
    $("#c_codigo").val(cliente_dados.cliente_codigo);
    //O nome pode aparecer em diferentes telas, com diferentes ids 
    $("#cliprinom").val(cliente_dados.nome);
    $("#c_nome_autocomplete").val(cliente_dados.nome);
    $("#clisobnom").val(cliente_dados.sobrenome);
    $("#clidoctip").val(cliente_dados.cliente_documento_tipo);
    $("#clidocnum").val(cliente_dados.cliente_documento_numero);
    $("#clicadema").val(cliente_dados.email);
    $("#clicelddi").val(cliente_dados.cel_ddi);
    $("#clicelnum").val(cliente_dados.cel_numero);
    $("#clitelddi").val(cliente_dados.tel_ddi);
    $("#clitelnum").val(cliente_dados.tel_numero);
    
    //Se estiver na tela de reservas, atualiza também o primeiro hóspede
    $('#h_codigo_1_1').val(cliente_dados.cliente_codigo);
    $('#h_nome_1_1').val(cliente_dados.nome);
    $('#h_sobrenome_1_1').val(cliente_dados.sobrenome);
    $('#h_email_1_1').val(cliente_dados.email);    
}

//Função que faz a busca de clientes por ajax, como em dialogs por exemplo
function clicadpes_ajax() {
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
    if (!$("#clicadpes").isValid(conf, false)) {
        $('.error').first().focus();
        return false;
    } else {
        callAjax('/ajax/ajaxclicadpes', {form: $("#clicadpes").serialize(), ajax: true, aria_cliente_codigo_id: $('#aria_cliente_codigo_id').val(),
            aria_cliente_nome_id: $('#aria_cliente_nome_id').val(), aria_cliente_cpf_cnpj_id: $('#aria_cliente_cpf_cnpj_id').val()}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {//colocar display block da tabela
                $("#table_clicadpes_ajax").css({"display": "block"});
                $("#table_clicadpes_ajax tbody").empty();
                $("#table_clicadpes_ajax tbody").append(html);
            }
        });
    }
}

//Seleciona o cliente escolhido em clicadpes no dialog
function clicadsel(cliente_codigo, cliente_nome_sobrenome, cliente_cpfcnpj) {
    linha_pagamento_atual = $("#linha_pgto_atual").val();
    $("#pag_codigo_" + linha_pagamento_atual).val(cliente_codigo);
    $("#pagante_nome_" + linha_pagamento_atual).val(cliente_nome_sobrenome);
    if (cliente_cpfcnpj != '' && typeof cliente_cpfcnpj != 'undefined')
        $("#pagante_cpf_cnpj_" + linha_pagamento_atual).val(cliente_cpfcnpj);
    if ($("#respagfor_" + linha_pagamento_atual).val() == '5' && $("#pag_codigo_" + linha_pagamento_atual).val() != '0' && $("#pag_codigo_" + linha_pagamento_atual).val() != '') {
        callAjax('/ajax/ajaxconcreexi', {cliente_codigo: $("#pag_codigo_" + linha_pagamento_atual).val()},
                function (html_concreexi) {
                    if (html == 'sessao_expirada')
                        window.location.href = web_root_complete + 'geral/gertelpri';
                    else {
                        jsonDC = JSON.parse(html_concreexi);
                        $("#saldo_credito_" + linha_pagamento_atual).val(gervalexi(jsonDC.credito_saldo));
                        $("#forma_valor_" + linha_pagamento_atual).val(gervalexi(jsonDC.credito_saldo));
                    }
                }
        );
    }
    fechaDialogById('clicadpes_dialog');
}


//Função que carrega os dados do cliente para editar
function clicadmod_ajax() {
    linha_pagamento_atual = $("#linha_pgto_atual").val();
    callAjax('/clientes/clicadexi', {cliente_codigo: $("#pag_codigo_" + linha_pagamento_atual).val()}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            clienteJson = JSON.parse(html);
            $.each(clienteJson[0], function (key, value) {
                $("#clicadmod #mod_" + key).val(value);
            });
            $("#clicadcod_mod").val($("#pag_codigo_" + linha_pagamento_atual).val());
            abreDialogById('clicadmod_dialog', 1200, 500);
        }
    });
}


function clicadmod_sal() {
    callAjax('/ajax/ajaxclicadmod', {form: $("#clicadmod").serialize()}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            alert(html);
            clicadsel($("#clicadcod_mod").val(), $("#clicadmod #mod_cliprinom").val() + " " + $("#clicadmod #mod_clisobnom").val(),
                    $("#clicadmod #mod_clicpfcnp").val())
            fechaDialogById('clicadmod_dialog');
        }
    });
}



function clicadcri_sal() {
    callAjax('/ajax/ajaxclicadcri', {form: $("#clicadcri").serialize()}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            mensagem = html.split("|")[0];
            cliente_codigo = html.split("|")[1];
            //Atualiza o relacionamento entre os clientes
            callAjax('/clientes/clirelatu', {cliente_codigo_1: $("#conpagcri #clicadcod").val(), cliente_codigo_2: cliente_codigo, excluido: null},
                    function (html) {
                        if (html == 'sessao_expirada')
                            window.location.href = web_root_complete + 'geral/gertelpri';
                        else {
                            clicadsel(cliente_codigo, $("#clicadcri #cri_cliprinom").val() + " " + $("#clicadcri #cri_clisobnom").val(),
                                    $("#clicadcri #cri_clicpfcnp").val());
                            eval(mensagem);
                            fechaDialogById('clicadcri_dialog');
                        }
                    });
        }
    });
}

//Quando clica no icone de pesquisar clientes
$(document).on("click", ".clicadpes", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        callAjax('/clientes/clicadpes', {is_dialog: true, aria_cliente_codigo_id: $(this).attr('aria-cliente-codigo-id'),
            aria_cliente_nome_id: $(this).attr('aria-cliente-nome-id'), aria_cliente_cpf_cnpj_id: $(this).attr('aria-cliente-cpf-cnpj-id')},
                function (html) {
                    if (html == 'sessao_expirada')
                        window.location.href = web_root_complete + 'geral/gertelpri';
                    else
                        openDialog(html, 750, 'auto', 'Pesquisar cliente');
                });
    }
});

//Quando clica no icone de pesquisar clientes
$(document).on("click", ".clicadmod", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        var cliente_codigo = $(this).attr('aria-cliente-codigo');
        callAjax('ajax/ajaxgerpagsal', {form: $("#" + $('#form_atual').val()).serialize(), back_page: $('#atual_pagina').val()}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                gerpagexi('clientes/clicadmod/' + $('#gerempcod').val() + '/' + cliente_codigo, 1, {});
            }
        });
    }
});

//Quando está no dialog e clica em uma linha de cliente
$(document).on("click", ".dialog_linha_cliente", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        closeDialog();
        $("#" + $(this).attr('aria-cliente-nome-id')).val($(this).attr('aria-cliente-nome')+' '+$(this).attr('aria-cliente-sobrenome'));
        $("#" + $(this).attr('aria-cliente-codigo-id')).val($(this).attr('aria-cliente-codigo'));
        $("#" + $(this).attr('aria-cliente-cpf-cnpj-id')).val($(this).attr('aria-cliente-cpf-cnpj'));
        if ($(this).attr('aria-dialog-id') == 'dialog_level_1')
            $('#' + $('#form_atual').val() + ' input[type="submit"]').trigger('click');
    }
});