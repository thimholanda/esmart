//Funções do painel de reservas

/*
 * Chamada quando se arrasta um documento no painel de reservas
 */
function docpdrmod(documento_numero, quarto_item, documento_tipo_codigo, quarto_tipo_codigo_destino, quarto_codigo_destino, data_inicial_atual, data_inicial_destino,
        data_final_atual, data_final_destino) {

    //Verifica se pode mover (aguardar pra ver se o plugin faz a validação)
    if (documento_tipo_codigo == 'reserva') {
        if (data_inicial_atual.split(' ')[0] != data_inicial_destino.split(' ')[0]) {
            //Caso deseja continuar, chama novamente a função com as datas inciais iguais
            docpdrmod(documento_numero, quarto_item, documento_tipo_codigo, quarto_tipo_codigo_destino, quarto_codigo_destino, data_inicial_atual, data_inicial_atual,
                    data_final_atual, data_final_destino);
            return 0;
        }
    }

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

    var datas_aloc = "";
    var quartos_codigos_aloc = "";
    var quartos_tipos_codigos_aloc = "";
    var dates = getDates(new Date(data_inicial_destino.split("-")[0], data_inicial_destino.split("-")[1] - 1, data_inicial_destino.split("-")[2]), new Date(data_final_atual.split("-")[0], data_final_atual.split("-")[1] - 1, data_final_atual.split("-")[2] - 1));

    dates.forEach(function (date) {
        datas_aloc += date.getFullYear() + "-" + ("0" + (date.getMonth() + 1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2) + "|";
        quartos_codigos_aloc += quarto_codigo_destino + ",";
        quartos_tipos_codigos_aloc += quarto_tipo_codigo_destino + ",";
    });
    datas_aloc = datas_aloc.slice(0, -1);

    callAjax('/geral/gerquadis', {empresa_codigo: $("#gerempcod").val(), datas: datas_aloc, quarto_tipo_codigo: quarto_tipo_codigo_destino, quarto_codigo: quarto_codigo_destino, saida_formato: 0},
            function (html) {
                var retorno = JSON.parse(html);
                //Verifica se o quarto está disponivel
                if (retorno.resultado) {
                    //chama a estquaalo pra reserva
                    if (documento_tipo_codigo == 'reserva') {
                        estquaalo($("#gerempcod").val(), documento_numero, quarto_item, datas_aloc, quartos_codigos_aloc, 1, quartos_tipos_codigos_aloc, quarto_tipo_codigo_destino, null, null, 1);
                        return 0;
                        //chama a serdocmod pro serviço
                    } else {
                        if (documento_tipo_codigo == 'bloqueio comercial') {
                            documento_tipo_codigo = 'bc';
                        } else if (documento_tipo_codigo == 'manutenção com bloqueio') {
                            documento_tipo_codigo = 'mb';
                        }
                        callAjax('/ajax/ajaxserdocmod', {empresa_codigo: $("#gerempcod").val(), serdoctip: documento_tipo_codigo, serdocnum: documento_numero, serquacod: quarto_codigo_destino, serinidat: data_inicial_destino,
                            serfindat: data_final_destino, anterior_inicial_data: data_inicial_atual, anterior_final_data: data_final_atual}, function (html) {
                            if (html == 'sessao_expirada')
                                window.location.href = web_root_complete + 'geral/gertelpri';
                            else {
                                $("#janela_atual").val(0);
                                $('#respaiatu_submit').click();
                                return 0;
                            }
                        });
                    }
                    //O quarto não está disponivel para aquela data
                } else {
                    $('#germencri_mensagem').text(retorno.mensagem);
                    var dialog = $('#exibe-germencri').dialog({
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
                                    return;
                                }
                            }
                        ]
                    });
                    dialog.dialog('open');
                    return;
                }
            });
}
/*
 * Chamada quando seleciona células vazia no painel de reserva
 */
function respdrcri(quarto_tipo_codigo, quarto_codigo, data_inicial, data_final) {

    var now = new Date();
    now.setHours(0, 0, 0, 0);
    var data_inicial_comparacao = new Date(data_inicial.split("-")[0], data_inicial.split("-")[1] - 1, data_inicial.split("-")[2]);
    data_inicial_comparacao.setHours(0, 0, 0, 0);
    //if (data_inicial_comparacao < now) {
    if (0) {
        //Exibe um dialog informando que a data nao pode estar no passado
        $.ajax({
            type: 'POST',
            url: web_root_complete + '/ajax/ajaxgermencri',
            data: {mensagem_codigo: 5, exibicao_tipo: 1},
            async: false,
            success: function (html) {
                html = JSON.parse(html);
                $('#germencri_mensagem').text(html.mensagem);
                var dialog = $('#exibe-germencri').dialog({
                    title: html.titulo_texto,
                    dialogClass: 'no_close_dialog',
                    autoOpen: false, height: 200,
                    width: 530,
                    modal: true,
                    buttons: [
                        {
                            text: html.botao_1_texto,
                            click: function () {
                                $(this).dialog('close');
                                $("#janela_atual").val(0);
                                $('#respaiatu_submit').click();
                                return 0;
                            }
                        }
                    ],
                });
                dialog.dialog('open');
            }

        });
    } else {
        var loading = setTimeout(function () {
            $("#overlay").css("display", "block");
            $("#loading").css("display", "block");
        }, 500);

        callAjax('/reservas/respdrcri', {quarto_tipo_codigo: quarto_tipo_codigo, quarto_codigo: quarto_codigo, inicial_data: data_inicial,
            final_data: data_final, atual_pagina: $('#atual_pagina').val()}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                //Se não houve erros na função, por exemplo falta de tarifas
                if (html != 0) {

                    openDialog(html, '90%', '0.94%', 'Criar Reserva');
                    //Simula mudanças nos selects, caso tenham vindo preenchidos
                    $('[id^="prazo_"]').each(function () {
                        $(this).trigger('change');
                    });
                    $('[id^="rescandet_"]').each(function () {
                        $(this).trigger('change');
                    });
                    $('[id^="rescnfdet_"]').each(function () {
                        $(this).trigger('change');
                    });
                    $('[id^="resadisel_"]').each(function () {
                        $(this).trigger('change');
                    });
                } else {
                    //Exibe um prompt de comando solicitando a confirmação de alocação na data original
                    $.ajax({
                        type: 'POST',
                        url: web_root_complete + '/ajax/ajaxgermencri',
                        data: {mensagem_codigo: 82, exibicao_tipo: 1},
                        async: false,
                        success: function (html) {
                            html = JSON.parse(html);
                            if (html == 'sessao_expirada')
                                window.location.href = web_root_complete + 'geral/gertelpri';
                            else {
                                $('#germencri_mensagem').text(html.mensagem);

                                var dialog = $('#exibe-germencri').dialog({
                                    title: html.titulo_texto,
                                    dialogClass: 'no_close_dialog',
                                    autoOpen: false, height: 200,
                                    width: 530,
                                    modal: true,
                                    buttons: [
                                        {
                                            text: html.botao_1_texto,
                                            click: function () {
                                                $(this).dialog('close');
                                                $("#janela_atual").val(0);
                                                $('#respaiatu_submit').click();
                                                return 0;
                                            }
                                        },
                                        {
                                            text: html.botao_2_texto,
                                            click: function () {
                                                $(this).dialog('close');
                                                $("#janela_atual").val(0);
                                                $('#respaiatu_submit').click();
                                                return 0;
                                            }
                                        }
                                    ],
                                });
                                dialog.dialog('open');
                            }
                        }

                    });
                }
                $("#overlay").css("display", "none");
                $("#loading").css("display", "none");
                clearTimeout(loading);
            }
        });
    }
}
/*
 * Chamada quando seleciona células vazia (pressionando shift) no painel de reserva - cria documento de manutenção ou bloqueio
 */
function docpdrcri(quarto_codigo, data_inicial, data_final) {
    loading = setTimeout(function () {
        $("#overlay").css("display", "block");
        $("#loading").css("display", "block");
    }, 500);

    callAjax('/documentos/docpdrcri', {quarto_codigo: quarto_codigo, inicial_data: data_inicial, final_data: data_final, empresa_codigo: $('#gerempcod').val()}, function (html) {
        if (html == 'sessao_expirada')
            window.location.href = web_root_complete + 'geral/gertelpri';
        else {
            //Se não houve erros na função
            if (html != 0) {
                openDialog(html, 850, 500, '');
                $('#resblocri #resdocsta').focus();
            }
        }

        $("#overlay").css("display", "none");
        $("#loading").css("display", "none");
        clearTimeout(loading);
    });
}
/*
 * Chamada quando se arrasta um documento para a lixeira no painel de reservas
 */
function docpdrexc(documento_numero, quarto_item, documento_tipo_codigo, documento_status_codigo, quarto_codigo, cancelamento_limite, cancelamento_valor) {

    //Verifica se é uma reserva (resdocmod - status cancelado) ou um servico (serdocmod - status cancelado)
    if (documento_tipo_codigo == 'reserva') {
        documento_tipo_codigo = 'rs';
        docs_a_cancelar.push(documento_numero + '-' + quarto_item + '-' + documento_status_codigo + '-' + cancelamento_limite + '-' + cancelamento_valor);
        resdoccan();

    } else {
        if (documento_tipo_codigo == 'bloqueio comercial') {
            documento_tipo_codigo = 'bc';
            status_cancelado = 2;
        } else if (documento_tipo_codigo == 'manutenção com bloqueio') {
            documento_tipo_codigo = 'mb';
            status_cancelado = 4;
        }

        callAjax('/ajax/ajaxserdocmod', {empresa_codigo: $("#gerempcod").val(), serdoctip: documento_tipo_codigo, serdocnum: documento_numero, serquacod: quarto_codigo, documento_status_codigo: status_cancelado,
            anterior_documento_status_codigo: documento_status_codigo}, function (html) {


            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                /* $('#germencri_mensagem').text(html.mensagem);
                 
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
                 $(this).dialog('close');*/
                $("#janela_atual").val(0);
                $('#respaiatu_submit').click();
                /*  }
                 }
                 ]
                 });
                 dialog.dialog('open');
                 
                 $("#overlay").css("display", "none");
                 $("#loading").css("display", "none");
                 clearTimeout(loading);*/
            }
        });
    }
}



