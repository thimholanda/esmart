//Quando se clica em um serviço para exibição/modificação
$(document).on("click", ".serdocmod", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        var documento_numero = $(this).attr('aria-documento-numero');
        var documento_tipo_codigo = $(this).attr('aria-documento-tipo-codigo');

        callAjax('ajax/ajaxgerpagsal', {form: $("#" + $('#form_atual').val()).serialize(), back_page: $('#atual_pagina').val()}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                gerpagexi('servicos/serdocmod/' + $('#gerempcod').val() + '/' + documento_numero + '/' + documento_tipo_codigo, 1, {});
            }
        });
    }
});

//Quando se clica em um link do painel de ocupação que possui mais de um serviço na mesma posicao
$(document).on("click", ".serdocpes", function () {
    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        var documento_tipo_codigo = $(this).attr('data-documento-tipo');
        var quarto_codigo = $(this).attr('data-quarto-codigo');
        var data_tipo = $(this).attr('data-data-tipo');
        var inicio_data = $(this).attr('data-inicio-data');
        var fim_data = $(this).attr('data-fim-data');
        var status = $(this).attr('data-status');

        callAjax('ajax/ajaxgerpagsal', {form: $("#estpaiatu").serialize(), back_page: 'estadias/estpaiatu'}, function (html) {
            if (html == 'sessao_expirada')
                window.location.href = web_root_complete + 'geral/gertelpri';
            else {
                callAjax('servicos/serdocpes', {gertiptit: documento_tipo_codigo, resquacod: quarto_codigo, gerdattip: data_tipo, gerdatini: inicio_data, gerdatfin: fim_data, gerdocsta: status,
                    ordenacao_coluna: 'inicial_data|final_data|quarto_codigo|documento_tipo_nome|', ordenacao_tipo: 'desc|desc|asc|asc|', pagina: 1}, function (html) {
                    if (html == 'sessao_expirada')
                        window.location.href = web_root_complete + 'geral/gertelpri';
                    else {
                        gerpagsal('estpaiatu', 'estadias/estpaiatu', 1);
                        $("#atual_pagina").val('servicos/serdocpes');
                        $("#content").html(html);
                    }
                });
            }
        });
    }
});