$(document).on("click", ".novo_item_lista_tecnica", function () {
    var nova_linha_lista_tecnica = $('.linha_lista_tecnica_item').last().clone();
    nova_linha_lista_tecnica.find('input').val('');
    nova_linha_lista_tecnica.find('select').val('');
    nova_linha_lista_tecnica.find('label').remove();
    nova_linha_lista_tecnica.find('input[id^="proprofil_"]').attr('id', 'proprofil_' + parseInt($('.item_lista_tecnica').length + 1)).attr('data-produto-codigo', 'conprocod_' + parseInt($('.item_lista_tecnica').length + 1));
    nova_linha_lista_tecnica.find('input[id^="prolischk_"]').attr('id', 'prolischk_' + parseInt($('.item_lista_tecnica').length + 1));
    nova_linha_lista_tecnica.find('input[id^="prolisexc_"]').attr('id', 'prolisexc_' + parseInt($('.item_lista_tecnica').length + 1)).val(0);
    nova_linha_lista_tecnica.find('input[id^="conprocod_"]').attr('id', 'conprocod_' + parseInt($('.item_lista_tecnica').length + 1));
    nova_linha_lista_tecnica.find('input[id^="qtd_"]').attr('id', 'qtd_' + parseInt($('.item_lista_tecnica').length + 1));
    nova_linha_lista_tecnica.find('select[id^="prounimed_"]').attr('id', 'prounimed_' + parseInt($('.item_lista_tecnica').length + 1));
    nova_linha_lista_tecnica.insertAfter($('.linha_lista_tecnica_item').last());
});
$(document).on("click", ".seleciona_lista_item_excluidos", function () {
    $('input[id^="prolischk_"]').each(function () {
        var data_indice_lista_item = $(this).attr('data-indice-lista-item');
        if ($(this).is(':checked'))
            $('#prolisexc_' + data_indice_lista_item).val(1);
    });
});
$(document).on("click", ".reinicializar_folha_custo", function () {

    callAjax('/custos/cusfoldet', {empresa_codigo: $('#gerempcod').val(), produto_qtd: $('#pai_produto_qtd').val(), variavel_fator_codigo: $('#pai_variavel_fator_codigo').val(),
        produto_codigo: $('#conprocod').val()},
            function (html) {
                console.log(html);
                var lista_tecnica_produto = JSON.parse(html);
                //Remove os itens com exceção do primeiro
                console.log(lista_tecnica_produto);
                $('.linha_custo_folha_item').not(':first').remove();
                $('.linha_custo_folha_item').find('input').val('');
                $('.linha_custo_folha_item').find('select').val('');
                $('.itens_excluidos').remove();
                //Adiciona o primeiro produto
                if (lista_tecnica_produto.length > 0) {
                    $('.linha_custo_folha_item:first').find('input[id^="proprofil_"]').val(lista_tecnica_produto[0].custo_item_nome);
                    $('.linha_custo_folha_item:first').find('input[id^="prolisexc_"]').val(0);
                    $('.linha_custo_folha_item:first').find('input[id^="conprocod_"]').val(lista_tecnica_produto[0].custo_item_produto_codigo);
                    $('.linha_custo_folha_item:first').find('input[id^="qtd_"]').val(lista_tecnica_produto[0].custo_item_qtd);
                    $('.linha_custo_folha_item:first').find('select[id^="prounimed_"]').val(lista_tecnica_produto[0].custo_item_fator_codigo);
                    $('.linha_custo_folha_item:first').find('input[id^="unitario_custo_"]').val(gervalexi(lista_tecnica_produto[0].custo_item_unitario_custo));
                    $('.linha_custo_folha_item:first').find('input[id^="total_custo_"]').val(gervalexi(lista_tecnica_produto[0].custo_item_total_custo));
                }

                //Clona os demais elementos depois do primeiro
                for (var i = 1; i < lista_tecnica_produto.length; i++) {
                    item = i;
                    var clone_linha_custo = $('.linha_custo_folha_item:first').clone();
                    clone_linha_custo.find('label').remove();
                    clone_linha_custo.find('input[id^="proprofil_"]').attr('id', 'proprofil_' + (i + 1)).attr('data-produto-codigo', 'conprocod_' + (i + 1)).val(lista_tecnica_produto[i].custo_item_nome);
                    clone_linha_custo.find('input[id^="prolischk_"]').attr('id', 'prolischk_' + (i + 1));
                    clone_linha_custo.find('input[id^="prolisexc_"]').attr('id', 'prolisexc_' + (i + 1)).val(0).val(0);
                    clone_linha_custo.find('input[id^="conprocod_"]').attr('id', 'conprocod_' + (i + 1)).val(lista_tecnica_produto[i].custo_item_produto_codigo);
                    clone_linha_custo.find('input[id^="qtd_"]').attr('id', 'qtd_' + (i + 1)).val(lista_tecnica_produto[i].custo_item_qtd);
                    clone_linha_custo.find('select[id^="prounimed_"]').attr('id', 'prounimed_' + (i + 1)).val(lista_tecnica_produto[i].custo_item_fator_codigo);
                    clone_linha_custo.find('input[id^="unitario_custo_"]').attr('id', 'unitario_custo_' + (i + 1)).val(gervalexi(lista_tecnica_produto[i].custo_item_unitario_custo)).attr('data-indice-custo-item', (i + 1));
                    clone_linha_custo.find('input[id^="total_custo_"]').attr('id', 'total_custo_' + (i + 1)).val(gervalexi(lista_tecnica_produto[i].custo_item_total_custo));
                    clone_linha_custo.insertAfter($('.linha_custo_folha_item').last());
                }
                
                $(".quantidade_folha_custo_item, .unitario_folha_custo_item").trigger('keyup');
                /*
                 callAjax('/ajax/ajaxprolisexi', {produto_codigo: $('#conprocod').val()},
                 function (html) {
                 var lista_tecnica_produto = JSON.parse(html);
                 
                 //Remove os itens com exceção do primeiro
                 console.log(lista_tecnica_produto);
                 $('.linha_custo_folha_item').not(':first').remove();
                 $('.linha_custo_folha_item').find('input').val('');
                 $('.linha_custo_folha_item').find('select').val('');
                 $('.itens_excluidos').remove();
                 
                 //Adiciona o primeiro produto
                 if (lista_tecnica_produto.length > 0) {
                 $('.linha_custo_folha_item:first').find('input[id^="proprofil_"]').val(lista_tecnica_produto[0].filho_produto_nome);
                 $('.linha_custo_folha_item:first').find('input[id^="prolisexc_"]').val(lista_tecnica_produto[0].excluido);
                 $('.linha_custo_folha_item:first').find('input[id^="conprocod_"]').val(lista_tecnica_produto[0].produto_codigo);
                 $('.linha_custo_folha_item:first').find('input[id^="qtd_"]').val(lista_tecnica_produto[0].qtd);
                 $('.linha_custo_folha_item:first').find('select[id^="prounimed_"]').val(lista_tecnica_produto[0].fator_codigo);
                 
                 //Chama a função cuscomexi para alterar o custo unitario e unidade de medida
                 callAjax('/custos/cuscomexi', {produto_codigo: lista_tecnica_produto[0].produto_codigo}, function (html) {
                 var custo_folha = JSON.parse(html);
                 
                 //Busca qual a linha modificada
                 $('.linha_custo_folha_item:first').find('input[id^="unitario_custo_"]').val(gervalexi(custo_folha.unitario_custo));
                 // $('#prounimed_' + linha_modificada).val(custo_folha.custo_fator_codigo);
                 });
                 }
                 
                 //Clona os demais elementos depois do primeiro
                 for (var i = 1; i < lista_tecnica_produto.length; i++) {
                 item = i;
                 if (lista_tecnica_produto[i].excluido == 0) {
                 var clone_linha_custo = $('.linha_custo_folha_item:first').clone();
                 clone_linha_custo.find('label').remove();
                 clone_linha_custo.find('input[id^="proprofil_"]').attr('id', 'proprofil_' + (i + 1)).attr('data-produto-codigo', 'conprocod_' + (i + 1)).val(lista_tecnica_produto[i].filho_produto_nome);
                 clone_linha_custo.find('input[id^="prolischk_"]').attr('id', 'prolischk_' + (i + 1));
                 clone_linha_custo.find('input[id^="prolisexc_"]').attr('id', 'prolisexc_' + (i + 1)).val(0).val(lista_tecnica_produto[i].excluido);
                 clone_linha_custo.find('input[id^="conprocod_"]').attr('id', 'conprocod_' + (i + 1)).val(lista_tecnica_produto[i].produto_codigo);
                 clone_linha_custo.find('input[id^="qtd_"]').attr('id', 'qtd_' + (i + 1)).val(lista_tecnica_produto[i].qtd);
                 clone_linha_custo.find('input[id^="unitario_custo_"]').attr('id', 'unitario_custo_' + (i + 1)).val(gervalexi(0)).attr('data-indice-custo-item', (i + 1));
                 clone_linha_custo.find('input[id^="total_custo_"]').attr('id', 'total_custo_' + (i + 1)).val(gervalexi(0));
                 clone_linha_custo.find('select[id^="prounimed_"]').attr('id', 'prounimed_' + (i + 1)).val(lista_tecnica_produto[i].fator_codigo);
                 
                 clone_linha_custo.insertAfter($('.linha_custo_folha_item').last());
                 
                 //Chama a função cuscomexi para alterar o custo unitario e unidade de medida
                 callAjax('/custos/cuscomexi', {produto_codigo: lista_tecnica_produto[i].produto_codigo}, function (html) {
                 var custo_folha = JSON.parse(html);
                 
                 //Busca qual a linha modificada
                 $('#unitario_custo_' + (item + 1)).val(gervalexi(custo_folha.unitario_custo));
                 // $('#prounimed_' + linha_modificada).val(custo_folha.custo_fator_codigo);
                 $(".quantidade_folha_custo_item, .unitario_folha_custo_item").trigger('keyup');
                 });
                 }
                 }
                 });*/
            });
});