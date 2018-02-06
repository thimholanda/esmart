
$(document).on("keyup", ".quantidade_folha_custo_item, .unitario_folha_custo_item", function () {
    var indice_custo_item = $(this).attr('data-indice-custo-item');
    console.log(indice_custo_item);
    //Busca qual a linha modificada
    $('#total_custo_' + indice_custo_item).val(gervalexi(gervalper($('#qtd_' + indice_custo_item).val()) * gervalper($('#unitario_custo_' + indice_custo_item).val())));

    //Atualiza o custo total
    var total_custo = 0;
    $('input[id^="total_custo_"]').not('.excluido').each(function () {
        total_custo += gervalper($(this).val());
    });

    $('#total_itens_custo_folha').val(gervalexi(total_custo));
});


/*
 $(document).on("change", ".unidade_medida_folha_custo_item", function () {
 //Busca qual a linha modificada
 var indice_custo_item = $(this).attr('data-indice-custo-item');
 $('#qtd_' + indice_custo_item).val('');
 $('#unitario_custo_' + indice_custo_item).val('');
 });
 */
$(document).on("click", ".novo_item_custo_folha", function () {
    var nova_linha_custo_folha = $('.linha_custo_folha_item').last().clone();
    nova_linha_custo_folha.find('input').val('');
    nova_linha_custo_folha.find('input').not('input[id^="total_custo_"]').attr("readonly", false);
    nova_linha_custo_folha.find('select').val('');
    nova_linha_custo_folha.find('label').remove();
    nova_linha_custo_folha.find('input[id^="proprofil_"]').attr('id', 'proprofil_' + parseInt($('.item_custo_folha').length + 1)).attr('data-produto-codigo', 'conprocod_' + parseInt($('.item_custo_folha').length + 1));
    nova_linha_custo_folha.find('input[id^="prolischk_"]').attr('id', 'prolischk_' + parseInt($('.item_custo_folha').length + 1)).attr('data-indice-custo-item', parseInt($('.item_custo_folha').length + 1));
    nova_linha_custo_folha.find('input[id^="prolisexc_"]').attr('id', 'prolisexc_' + parseInt($('.item_custo_folha').length + 1)).val(0);
    nova_linha_custo_folha.find('input[id^="conprocod_"]').attr('id', 'conprocod_' + parseInt($('.item_custo_folha').length + 1));
    nova_linha_custo_folha.find('select[id^="prounimed_"]').attr('id', 'prounimed_' + parseInt($('.item_custo_folha').length + 1)).attr('data-indice-custo-item', parseInt($('.item_custo_folha').length + 1));
    nova_linha_custo_folha.find('input[id^="qtd_"]').attr('id', 'qtd_' + parseInt($('.item_custo_folha').length + 1)).attr('data-indice-custo-item', parseInt($('.item_custo_folha').length + 1));
    nova_linha_custo_folha.find('input[id^="unitario_custo_"]').attr('id', 'unitario_custo_' + parseInt($('.item_custo_folha').length + 1)).attr('data-indice-custo-item', parseInt($('.item_custo_folha').length + 1));
    nova_linha_custo_folha.find('input[id^="total_custo_"]').attr('id', 'total_custo_' + parseInt($('.item_custo_folha').length + 1));
    nova_linha_custo_folha.insertAfter($('.linha_custo_folha_item').last());
});

$(document).on("click", ".seleciona_custo_folha_excluidos", function () {
    $('input[id^="prolischk_"]').each(function () {
        var data_indice_custo = $(this).attr('data-indice-custo-item');
        if ($(this).is(':checked')) {
            $('#prolisexc_' + data_indice_custo).val(1);

            //Se ainda não for a ultima linha, remove
            if ($('.linha_custo_folha_item').length > 1) {
                $(this).css('display', 'none');
                $('#proprofil_' + data_indice_custo).closest('.linha_custo_folha_item').remove();
            } else {
                //se for a ultima linha apenas limpa
                $('input[id^="proprofil_"]').val('');
                $('input[id^="prolischk_"]').prop('checked', false);
                $('input[id^="prolisexc_"]').val(0);
                $('input[id^="conprocod_"]').val('');
                $('select[id^="prounimed_"]').val('');
                $('input[id^="qtd_"]').val('');
                $('input[id^="unitario_custo_"]').val('');
                $('input[id^="total_custo_"]').val('');
            }

        }
    });

    //Faz uma reordenaçao nos indices
    var novo_indice = 1;
    $('input[id^="conprocod_"]').each(function () {
        $(this).attr('id', 'conprocod_' + novo_indice);
        novo_indice++;
    });

    novo_indice = 1;
    $('input[id^="proprofil_"]').each(function () {
        $(this).attr('id', 'proprofil_' + novo_indice);
        $(this).attr('data-produto-codigo', 'conprocod_' + novo_indice);
        novo_indice++;
    });

    novo_indice = 1;
    $('input[id^="prolischk_"]').each(function () {
        $(this).attr('id', 'prolischk_' + novo_indice);
        $(this).attr('data-indice-custo-item', novo_indice);
        novo_indice++;
    });

    novo_indice = 1;
    $('input[id^="prolisexc_"]').each(function () {
        $(this).attr('id', 'prolisexc_' + novo_indice);
        novo_indice++;
    });

    novo_indice = 1;
    $('select[id^="prounimed_"]').each(function () {
        $(this).attr('id', 'prounimed_' + novo_indice);
        $(this).attr('data-indice-custo-item', novo_indice);
        novo_indice++;
    });

    novo_indice = 1;
    $('input[id^="qtd_"]').each(function () {
        $(this).attr('id', 'qtd_' + novo_indice);
        $(this).attr('data-indice-custo-item', novo_indice);
        novo_indice++;
    });

    novo_indice = 1;
    $('input[id^="unitario_custo_"]').each(function () {
        $(this).attr('id', 'unitario_custo_' + novo_indice);
        $(this).attr('data-indice-custo-item', novo_indice);
        novo_indice++;
    });

    novo_indice = 1;
    $('input[id^="total_custo_"]').each(function () {
        $(this).attr('id', 'total_custo_' + novo_indice);
        novo_indice++;
    });

    $(".quantidade_folha_custo_item, .unitario_folha_custo_item").trigger('keyup');
});

/*
 * Exibe a folha de custo de um determinado item de conta
 */
$(document).on("click", ".cusfolexi", function () {
    var empresa_codigo = $('#gerempcod').val();
    var documento_numero = $(this).attr('aria-documento-numero');
    var quarto_item = $(this).attr('aria-quarto-item');
    var conta_item = $(this).attr('aria-conta-item');
    var documento_tipo_codigo = $(this).attr('aria-documento-tipo-codigo');
    var pai_produto_codigo = $(this).attr('aria-pai-produto-codigo');
    var pai_produto_nome = $(this).attr('aria-pai-produto-nome');
    var pai_unidade_medida = $(this).attr('aria-pai-unidade-medida');
    var pai_produto_qtd = $(this).attr('aria-pai-produto-qtd');
    var pai_variavel_fator_codigo = $(this).attr('aria-pai-variavel-fator-codigo');

    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        callAjax('/custos/cusfolexi', {empresa_codigo: empresa_codigo, documento_numero: documento_numero, quarto_item: quarto_item, conta_item: conta_item, documento_tipo_codigo: documento_tipo_codigo,
            pai_produto_codigo: pai_produto_codigo, pai_produto_nome: pai_produto_nome, pai_unidade_medida: pai_unidade_medida, pai_produto_qtd: pai_produto_qtd, pai_variavel_fator_codigo: pai_variavel_fator_codigo,
            url_redirect_after: $('#atual_pagina').val()},
                function (html) {
                    console.log(html);
                    openDialog(html, '65%', '0.64%', 'Modificação de folha de custo');
                });
    }

});


/*
 * Exibe os custos da reserva selecioada
 */
$(document).on("click", ".cusresexi", function () {
    var empresa_codigo = $('#gerempcod').val();
    var documento_numero = $(this).attr('aria-documento-numero');
    var quarto_item = $(this).attr('aria-quarto-item');

    $('#atual_dialog').val('dialog_level_1');
    $('#atual_dialog_page').val('/custos/cusresexi/');
    $('#atual_dialog_params').val("{'documento_numero':'" + documento_numero + "', 'quarto_item':'" + $(this).attr('aria-quarto-item') + "' }");

    if (!$(this).hasClass('click_disabled')) {
        $(this).addClass('click_disabled');
        callAjax('/custos/cusresexi', {empresa_codigo: empresa_codigo, documento_numero: documento_numero, quarto_item: quarto_item},
                function (html) {
                    console.log(html);
                    openDialog(html, '65%', '0.64%', 'Custos');
                });
    }

});