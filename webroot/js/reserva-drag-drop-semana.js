var scrollAlocPend;

function iniciaDragDropSemana() {

    $('.lista-reservas li')
            .add('.lista-reservas-pend li')
            .not('.continuacao')
            .draggable({
                cursor: "move",
                start: function (event, ui) {
                    $(this).removeClass('resdocmod');
                    $(this).removeClass('serdocmod');
                    var hoverOcupado,
                            hoverQuarto,
                            hoverReserva,
                            reservaOriginal,
                            $dragAtivo = $(this),
                            $blocoFinal,
                            $blocoAntFinal,
                            $tdHoverAgora;

                    dragAtivado = true;

                    $dragAtivo
                            .addClass('ativo');

                    var diarias = $(this).data('diarias');

                    $blocoAlocPendentesTopo
                            .removeClass('aberto');

                    $boxHover
                            .hide()
                            .html('');

                    if ($dragAtivo.hasClass('aloc-pend')) {

                        var Xs = $('.agenda-bloco').offset().left,
                                Ys = $('.agenda-bloco').offset().top,
                                mouseXs = event.pageX - Xs,
                                mouseYs = event.pageY - Ys;

                        $boxHoverMove
                                .css({
                                    'top': mouseYs + 10 + 'px',
                                    'left': mouseXs - 40 + 'px'

                                });

                        $(document)
                                .on('mousemove', function (event) {

                                    var mouseXs = event.pageX - Xs,
                                            mouseYs = event.pageY - Ys;

                                    $boxHoverMove
                                            .css({
                                                'top': mouseYs + 20 + 'px',
                                                'left': mouseXs - 40 + 'px'

                                            });

                                });

                        var elem = $dragAtivo,
                                rgb = hexToRgb(elem.data('cor'));

                        var boxHtml = '<div class="titulo"><span class="capital">' + elem.data('documentotipo') + '</span> ' + elem.data('status') + '</div>' +
                                '<div class="conteudo" style="background-color: rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ',0.25)">' +
                                elem.data('reserva');

                        if (elem.data('quartoitem') != '1')
                            boxHtml += ' - ' + elem.data('quartoitem');

                        //Verifica se foi um quarto que já teve realocação
                        if ((elem.data('entrada-geral') != elem.data('entrada')) || (elem.data('saida') != elem.data('saida-geral'))) {
                            boxHtml += '<br>' +
                                    '<span class="nome">' + elem.data('nome') + '<span><br><b>' +
                                    elem.data('entrada-geral').split('-')[2] + '/' + elem.data('entrada-geral').split('-')[1] + '/' + elem.data('entrada-geral').split('-')[0] +
                                    ' - ' +
                                    elem.data('saida-geral').split('-')[2] + '/' + elem.data('saida-geral').split('-')[1] + '/' + elem.data('saida-geral').split('-')[0] + '</b><br>';
                        } else
                            boxHtml += '<br>' +
                                    '<span class="nome">' + elem.data('nome') + '<span><br>' +
                                    elem.data('entrada-geral').split('-')[2] + '/' + elem.data('entrada-geral').split('-')[1] + '/' + elem.data('entrada-geral').split('-')[0] +
                                    ' - ' +
                                    elem.data('saida-geral').split('-')[2] + '/' + elem.data('saida-geral').split('-')[1] + '/' + elem.data('saida-geral').split('-')[0] + '<br>';


                        if (elem.data('agenciacodigo')) {

                            boxHtml += elem.data('agenciacodigo') + '<br>';

                        }

                        boxHtml += 'PAX ' + elem.data('pax') +
                                '</div>';

                        $boxHoverMove
                                .html(boxHtml)
                                .show();

                    }

                    $('td.marcavel')
                            .droppable({
                                tolerance: 'pointer',
                                over: function (event, ui) {
                                    console.log('over');

                                    $('.hover')
                                            .removeClass('hover');

                                    $('td.entrada')
                                            .removeClass('entrada');

                                    $('td.saida')
                                            .removeClass('saida');

                                    $(this)
                                            .addClass('hover')
                                            .addClass('entrada');

                                    var $thisFixo = $(this);

                                    //Limpa possiveis marcações
                                    $('.painel_quarto_codigo').css({'color': '#333', 'font-weight': 'normal'});
                                    $('.painel_quarto_tipo_codigo').css({'color': '#333', 'font-weight': 'normal'});
                                    $('.agenda-table td').not('.nao-util').css('background-color', '#d4d4d4');
                                    $('.agenda-table tr:nth-of-type(odd) td').not('.nao-util').css('background-color', '#f9f9f9');
                                    $('.agenda-table thead td').css('background-color', '#d4d4d4!important');
                                    $('.tdSemana').not('.nao-util').css({'color': '#333', 'font-weight': 'normal', 'background-color': 'initial'});
                                    $('.agenda-table thead td.nao-util').css({'color': '#2196F3!important', 'font-weight': 'normal'});
                                    $('.agenda-table thead td.cor-txt1').css({'color': '#FB4848!important', 'font-weight': 'bold'});

                                    //Atualiza as marcações
                                    $('#painel_quarto_codigo_' + $thisFixo.data('quarto')).css({'background-color': '#8296b4', 'color': 'white', 'font-weight': 'bold'});
                                    $('#painel_quarto_tipo_codigo_' + $thisFixo.data('quarto')).css({'background-color': '#8296b4', 'color': 'white', 'font-weight': 'bold'});
                                    $('#painel_data_' + $thisFixo.data('format')).css("cssText", "background-color: #8296b4 !important;color:white!important;font-weight:bold");

                                    var $thisNext = $thisFixo.next();

                                    for (var i = 0; i < diarias; i++) {

                                        if (i < diarias - 1) {

                                            $thisNext
                                                    .addClass('hover');

                                            $blocoAntFinal = $thisNext;
                                            $('#painel_data_' + $thisNext.data('format')).css("cssText", "background-color: #8296b4 !important;color:white!important;font-weight:bold");
                                        } else {

                                            $thisNext
                                                    .addClass('hover')
                                                    .addClass('saida');

                                            $('#painel_data_' + $thisNext.data('format')).css("cssText", "background-color: #8296b4 !important;color:white!important;font-weight:bold");
                                            $blocoFinal = $thisNext;

                                        }

                                        $thisNext = $thisNext.next();

                                    }

                                    $tdHoverAgora = $('td.marcavel[data-ocupado="true"].hover');

                                    hoverOcupado = $tdHoverAgora.length;
                                    hoverQuarto = $tdHoverAgora.data('quarto');
                                    hoverReserva = $tdHoverAgora.data('reserva');

                                },
                                out: function (event, ui) {
                                    console.log('out');
                                },
                                drop: function (event, ui) {
                                    console.log('drop');
                                    $boxHoverMove
                                            .html('')
                                            .hide();

                                    $thisFixo = $(this);

                                    function reposicionaReserva() {


                                        var top = $thisFixo.position().top,
                                                left = $thisFixo.position().left + 10;

                                        $('.lista-reservas li.ativo')
                                                .css({
                                                    'top': (top + 1.5) + 'px',
                                                    'left': left + 'px'

                                                })
                                                .removeClass('ativo');

                                        setTimeout(function () {

                                            scrollTop = $(window).scrollTop();
                                            if ($dragAtivo.data('documentotipo') == 'reserva')
                                                docpdrmod($dragAtivo.data('reserva'), $dragAtivo.data('quartoitem'), $dragAtivo.data('documentotipo'), $thisFixo.data('tipoquarto'), $thisFixo.data('quarto'), $dragAtivo.data('entrada'),
                                                        $thisFixo.data('format'), $dragAtivo.data('saida'), $blocoFinal.data('format'));
                                            else {
                                                //verifica se é uma modificação apenas de data
                                                if ($thisFixo.data('quarto') == $dragAtivo.data('quarto')) {
                                                    docpdrmod($dragAtivo.data('reserva'), $dragAtivo.data('quartoitem'), $dragAtivo.data('documentotipo'), $thisFixo.data('tipoquarto'), $thisFixo.data('quarto'), $dragAtivo.data('entrada'), $thisFixo.data('format'), $dragAtivo.data('saida'), $blocoFinal.data('format'));
                                                } else {
                                                    callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 113}, function (html) {
                                                        html = JSON.parse(html);
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
                                                                        $(this).dialog('close');
                                                                        scrollTop = $(window).scrollTop();
                                                                        montaAgendaSemana(datasPeriodo);
                                                                        return 0;
                                                                    }
                                                                }
                                                            ]
                                                        });
                                                        dialog.dialog('open');

                                                        inicioReserva = false;

                                                        fimReserva = false;

                                                        teclaAtual = undefined;
                                                    });
                                                }
                                            }
                                        }, 100);

                                    }
                                    if (hoverOcupado > 0) {

                                        if ($thisFixo.data('ocupado') && hoverQuarto != $dragAtivo.data('quarto') && hoverReserva != $dragAtivo.data('reserva')) {

                                            validaDrag = false;

                                        } else if (hoverQuarto == $dragAtivo.data('quarto') && hoverReserva == $dragAtivo.data('reserva')) {

                                            reposicionaReserva();

                                        } else if ($blocoFinal.data('inicio') == true && $blocoAntFinal.data('inicio') == false) {

                                            reposicionaReserva();

                                        } else {

                                            validaDrag = false;

                                        }

                                    } else {

                                        if ($dragAtivo.hasClass('aloc-pend') && $dragAtivo.data('entrada') != $('td.marcavel.hover').data('format')) {
                                            validaDrag = false;

                                            callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 114}, function (html) {
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
                                                                    montaAgendaSemana(datasPeriodo);
                                                                    return 0;
                                                                }
                                                            }
                                                        ]
                                                    });
                                                }
                                            });

                                        } else {
                                            //Valida se a reserva está sendo direcionada em datas diferentes mas na mesma linha
                                            if ($dragAtivo.data('documentotipo') == 'reserva') {
                                                if ($thisFixo.data('quarto') == $dragAtivo.data('quarto'))
                                                    validaDrag = false;
                                                //Caso a reserva esteja sendo redirecionada em quartos diferentes, precisa-se verificar o caso onde ela pode estar sendo direcionada na diagonal. Nesse caso verifica-se
                                                // se a data onde a reserva ficará efetivamente está livre
                                                else if (verificaPosicaoOcupada($thisFixo.data('quarto'), $dragAtivo.data('entrada'), $dragAtivo.data('saida'))) {
                                                    validaDrag = false;
                                                } else {
                                                    reposicionaReserva();
                                                }
                                            } else {
                                                //Se for algum serviço e estiver tentando mudar de quarto
                                                if ($thisFixo.data('quarto') != $dragAtivo.data('quarto'))
                                                    validaDrag = false;
                                                else
                                                    reposicionaReserva();
                                            }

                                        }
                                    }

                                    setTimeout(function () {

                                        $('td.marcavel')
                                                .removeClass('hover');

                                        $('td.entrada')
                                                .removeClass('entrada');

                                        $('td.saida')
                                                .removeClass('saida');

                                    }, 100);

                                    reservaOriginal = null;

                                    dragAtivado = false;

                                }

                            });

                },
                revert: function (is_valid_drop) {

                    if (!is_valid_drop || !validaDrag) {
                        //Limpa possiveis marcações
                        $('.painel_quarto_codigo').css({'color': '#333', 'font-weight': 'normal'});
                        $('.painel_quarto_tipo_codigo').css({'color': '#333', 'font-weight': 'normal'});
                        $('.agenda-table td').not('.nao-util').css('background-color', '#d4d4d4');
                        $('.agenda-table tr:nth-of-type(odd) td').not('.nao-util').css('background-color', '#f9f9f9');
                        $('.tdSemana').css({'color': '#333', 'font-weight': 'normal', 'background-color': 'initial'});
                        $('.agenda-table thead td.nao-util').css({'color': '#2196F3!important', 'font-weight': 'normal'});
                        $('.agenda-table thead td.cor-txt1').css({'color': '#FB4848!important', 'font-weight': 'bold'});

                        $('.lista-reservas li.ativo')
                                .removeClass('ativo');

                        $('.lista-reservas-pend li.ativo')
                                .removeClass('ativo');

                        $('td.marcavel')
                                .removeClass('hover');

                        $('td.entrada')
                                .removeClass('entrada');

                        $('td.saida')
                                .removeClass('saida');

                        if (reservasPendAloc.length > 0) {

                            // $blocoAlocPendentes
                            //         .show();

                            if ($(this).parent().prop('outerHTML').indexOf('-pend') > -1) {

                                setTimeout(function () {

                                    $blocoAlocPendentesTopo
                                            .scrollTop(scrollAlocPend)
                                            .addClass('aberto');

                                    $boxHoverMove
                                            .hide()
                                            .html('');

                                }, 50);

                            }

                        }

                        validaDrag = true;

                        return true;

                    }

                },
                cursorAt: {
                    left: 15
                },

                revertDuration: 100,
                // containment: 'window',

            });

}

$('.lista-reservas li')
        .on('click', function () {
            console.log('click');
        });

function abreFechaAlocPendentes() {

    $('.alocacoes-pendentes-topo')
            .toggleClass('aberto');

}

$('.alocacoes-pendentes-topo .titulo')
        .on('click', abreFechaAlocPendentes);

function outClickClose(e) {
    var container = $('.alocacoes-pendentes-topo');
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        scrollAlocPend = container.scrollTop();
        container
                .scrollTop(0)
                .removeClass('aberto');
    }
}

function verificaPosicaoOcupada(quarto, entrada_data, saida_data) {

    var entrada_data = new Date(entrada_data);
    var saida_data = new Date(saida_data);

    while (entrada_data < saida_data) {
        var newDate = entrada_data.setDate(entrada_data.getDate() + 1);
        entrada_data = new Date(newDate);
        var curr_date = entrada_data.getDate();
        var curr_month = entrada_data.getMonth() + 1; //Months are zero based
        var curr_year = entrada_data.getFullYear();
        if (curr_month <= 9)
            curr_month = '0' + curr_month;
        var data_test = curr_year + "-" + curr_month + "-" + curr_date;

        //se o quarto está ocupado
        if ($("[data-quarto=" + quarto + "][data-format=" + data_test + "][data-ocupado=true]").attr('data-ocupado') === 'true') {
            return 1;
        }
    }

    return 0;
}

$(document).on('click', outClickClose);