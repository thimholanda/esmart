var $listaReservas = $('.lista-reservas'),
        $boxHover = $('.box-hover'),
        $boxHoverMove = $('.box-hover-move'),
        $blocoAlocPendentes = $('.alocacoes-pendentes'),
        $blocoAlocPendentesTopo = $('.alocacoes-pendentes-topo'),
        $listaReservasPend = $('.lista-reservas-pend');

var quartosReservas = [],
        hoverAgora;

function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

function processaReservas() {
    var indexQuartos = 0;
    $.each(jsonReservas, function (k, v) {

        $.each(v, function (kk, vv) {

            quartosReservas.push({
                "id_tipo": k,
                "id_quarto": kk,
                "quarto_tipo_nome": $('#quarto_tipo_nome_' + k).val(),
                "reservas": {}
            });

            $.each(vv, function (kkk, vvv) {
                quartosReservas[indexQuartos]['reservas'][vvv.documento_numero + '-' + vvv.quarto_item] = {};
            });

            $.each(quartosReservas[indexQuartos]['reservas'], function (kkk, vvv) {

                var dadosReservas = {
                    "reserva_info": []
                };

                $.each(vv, function (kkkk, vvvv) {
                    if (kkk == vvvv.documento_numero + '-' + vvvv.quarto_item) {
                        dadosReservas['reserva_info'].push(vvvv);
                    }
                });
                quartosReservas[indexQuartos]['reservas'][kkk] = dadosReservas;
            });

            indexQuartos++;

        });

    });
    respaiatuSort(quartosReservas, $('#gerordatr').val());

    //Faz a criação das variáveis de alocação para quartos que podem ter sido realocados
    for (var i = 0; i < quartosReservas.length; i++) {
        $.each(quartosReservas[i]['reservas'], function (documento_numero, documento_info) {

            //Vetor de alocações, utilizando principalmente em casos de reservas com mais de um quarto codigo
            documento_info.alocacoes = [];
            var reserva_info_atual = [];
            var data_anterior = '';
            var alocacao_indice = 0;
            $.each(documento_info.reserva_info, function (key, value) {
                //Primeira execução
                if (data_anterior == '') {
                    reserva_info_atual.push(value);
                    data_anterior = value.data;
                    //Reserva de apenas uma diária
                    if (key == (documento_info.reserva_info.length - 1))
                        documento_info.alocacoes[alocacao_indice] = reserva_info_atual;
                } else {
                    if (value.data == moment(data_anterior, 'YYYY-MM-DD').add("1", "days").format('YYYY-MM-DD')) {
                        reserva_info_atual.push(value);
                        //Se foi a ultima execucao
                        if (key == (documento_info.reserva_info.length - 1))
                            documento_info.alocacoes[alocacao_indice] = reserva_info_atual;
                    } else {
                        //Chegou em um ponto onde a proxima data está em outra alocação, ou seja, um quarto foi realocado e voltou para o quarto original
                        documento_info.alocacoes[alocacao_indice] = reserva_info_atual;
                        alocacao_indice++;
                        reserva_info_atual = [];

                        if (key != (documento_info.reserva_info.length - 1)) {
                            reserva_info_atual = [];
                            reserva_info_atual.push(value);
                            //Ultima execucao
                        } else {
                            reserva_info_atual.push(value);
                            documento_info.alocacoes[alocacao_indice] = reserva_info_atual;
                        }
                    }
                    data_anterior = value.data;
                }
            });
        });
    }
}
processaReservas();

function shadeColor2(color, percent) {
    var cor = color.substr(1, 6);
    var f = parseInt(cor.slice(1), 16), t = percent < 0 ? 0 : 255, p = percent < 0 ? percent * -1 : percent, R = f >> 16, G = f >> 8 & 0x00FF, B = f & 0x0000FF;
    return "#" + (0x1000000 + (Math.round((t - R) * p) + R) * 0x10000 + (Math.round((t - G) * p) + G) * 0x100 + (Math.round((t - B) * p) + B)).toString(16).slice(1);
}

function lightenDarkenColor(col, amt) {

    var usePound = false;

    if (col[0] == "#") {
        col = col.slice(1);
        usePound = true;
    }

    var num = parseInt(col, 16);

    var r = (num >> 16) + amt;

    if (r > 255)
        r = 255;
    else if (r < 0)
        r = 0;

    var b = ((num >> 8) & 0x00FF) + amt;

    if (b > 255)
        b = 255;
    else if (b < 0)
        b = 0;

    var g = (num & 0x0000FF) + amt;

    if (g > 255)
        g = 255;
    else if (g < 0)
        g = 0;

    return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16);

}

function montaReservasSemana() {

    $listaReservas
            .html('');

    $blocoAlocPendentesTopo
            .hide();

    $listaReservasPend
            .html('');

    var reservaHtml = "";
    var iteracoes = 1;

    for (var i = 0; i < quartosReservas.length; i++) {

        $.each(quartosReservas[i]['reservas'], function (k, v) {
            iteracoes++;
            //Percorre todas as alocações (tem casos que uma reserva pode ter sido realocada) 
            //Marca as diárias desse quarto com atributos de ocupado e com o documento_numero
            for (var ii = 0; ii < v.alocacoes.length; ii++) {
                for (var iii = 0; iii < v.alocacoes[ii].length; iii++) {
                    var $tdProcessa = $('td.marcavel[data-quarto="' + quartosReservas[i]['id_quarto'] + '"][data-format="' + v.alocacoes[ii][iii]['data'] + '"]');

                    $tdProcessa
                            .attr('data-ocupado', true)
                            .attr('data-reserva', v.alocacoes[ii][iii].documento_numero);
                }

                var $tdInicio = [],
                        contagemDiariasSemana = 0,
                        continuacao = false;

                //Pega o inicio da primeira diária, na variavel tdInicio
                for (var iii = 0; iii < v.alocacoes[ii].length; iii++) {

                    if ($('td.marcavel[data-format="' + v.alocacoes[ii][iii]['data'] + '"]').length > 0) {

                        $tdInicio = $('td.marcavel[data-quarto="' + quartosReservas[i]['id_quarto'] + '"][data-format="' + v.alocacoes[ii][iii]['data'] + '"]');
                        break;

                    } else {

                        contagemDiariasSemana++;
                        continuacao = true;

                    }

                }

                if ($tdInicio.length > 0) {

                    $tdInicio
                            .attr('data-inicio', true);

                    var limEsquerdo = $tdInicio.position().left + 10,
                            limSuperior = $tdInicio.position().top + 3.5,
                            altura = $tdInicio.height() - 6.5,
                            largura = (larguraTd * v.alocacoes[ii].length) - 11,
                            marginLeft = larguraTd / 2;

                    reservaHtml += '<li';

                    if (continuacao) {
                        limEsquerdo = limEsquerdo - (larguraTd * contagemDiariasSemana);

                    }

                    var cor1 = v.alocacoes[ii][0].cor,
                            cor2 = lightenDarkenColor(cor1, 40);

                    var redirecionamento_click = "";
                    if (v.alocacoes[ii][0].documento_tipo_nome == 'reserva')
                        redirecionamento_click = " class='resdocmod'  aria-documento-numero='" + v.alocacoes[ii][0].documento_numero + "' aria-quarto-item='" + v.alocacoes[ii][0].quarto_item + "'";
                    else if (v.alocacoes[ii][0].documento_tipo_nome == 'manutenção com bloqueio')
                        redirecionamento_click = " class='serdocmod'  aria-documento-numero='" + v.alocacoes[ii][0].documento_numero + "' aria-documento-tipo-codigo='mb'";
                    else if (v.alocacoes[ii][0].documento_tipo_nome == 'bloqueio comercial')
                        redirecionamento_click = " class='serdocmod'  aria-documento-numero='" + v.alocacoes[ii][0].documento_numero + "' aria-documento-tipo-codigo='bc'";

                    reservaHtml += ' data-tipoquarto="' + quartosReservas[i]['id_tipo'] + '"' +
                            ' data-quarto="' + quartosReservas[i]['id_quarto'] + '"' +
                            ' data-reserva="' + v.alocacoes[ii][0].documento_numero + '"' +
                            ' data-documentotipo="' + v.alocacoes[ii][0].documento_tipo_nome + '"' +
                            ' data-documentotipocodigo="' + v.alocacoes[ii][0].documento_tipo_codigo + '"' +
                            ' data-quartoitem="' + v.alocacoes[ii][0].quarto_item + '"' +
                            ' data-statusnome="' + v.alocacoes[ii][0].documento_status_nome + '"' +
                            ' data-statuscodigo="' + v.alocacoes[ii][0].documento_status_codigo + '"' +
                            ' data-diarias="' + v.alocacoes[ii].length + '"' +
                            ' data-entrada="' + v.alocacoes[ii][0].data + '"' +
                            ' data-saida="' + moment(v.alocacoes[ii][v.alocacoes[ii].length - 1].data, 'YYYY-MM-DD').add("1", "days").format('YYYY-MM-DD') + '"' +
                            ' data-entrada-geral="' + v.alocacoes[ii][0].inicial_data.split(' ')[0] + '"' +
                            ' data-saida-geral="' + v.alocacoes[ii][0].final_data.split(' ')[0] + '"' +
                            ' data-cancelamento_limite="' + v.alocacoes[ii][0].cancelamento_limite + '"' +
                            ' data-cancelamento_valor="' + v.alocacoes[ii][0].cancelamento_valor + '"' +
                            ' data-pax="' + v.alocacoes[ii][0].pax + '"' +
                            ' data-status="' + v.alocacoes[ii][0].documento_status_nome + '"' +
                            ' data-nome="' + v.alocacoes[ii][0].nome + '"' +
                            ' data-agenciacodigo="' + v.alocacoes[ii][0].agencia_codigo + '"' +
                            ' data-agencianome="' + $('#agencia_' + v.alocacoes[ii][0].agencia_codigo).val() + '"' +
                            ' data-motivonome="' + v.alocacoes[ii][0].motivo_nome + '"' +
                            ' data-cor="' + v.alocacoes[ii][0].cor + '"' +
                            ' style="left: ' + limEsquerdo + 'px; top: ' + limSuperior + 'px; width: ' + largura + 'px; height: ' + altura + 'px; margin-left: ' + marginLeft + 'px;' +
                            'background: ' + cor1 + '; ' +
                            'background: -moz-linear-gradient(left, ' + cor1 + ' 0%, ' + cor2 + ' 100%); ' +
                            'background: -webkit-linear-gradient(left, ' + cor1 + ' 0%,' + cor2 + ' 100%); ' +
                            'background: linear-gradient(to right, ' + cor1 + ' 0%,' + cor2 + ' 100%); ' +
                            'filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'' + cor1 + '\', endColorstr=\'' + cor2 + '\',GradientType=1 ); ' +
                            ' border: 0 solid ' + cor2 + ';"' +
                            '' + redirecionamento_click + ' "><div class="triangulo-topo" style="border-color: ' + cor1 + ' transparent transparent transparent;"></div><div class="triangulo-base" style="border-color: transparent transparent ' + cor1 + ' transparent;"></div></li>';
                }
            }
        });

    }

    $listaReservas
            .append(reservaHtml);
    reservaHtml = '';
    if (reservasPendAloc.length > 0) {

        for (var i = 0; i < reservasPendAloc.length; i++) {

            reservaHtml += '<li' +
                    ' class="aloc-pend"' +
                    ' data-reserva="' + reservasPendAloc[i].documento_numero + '"' +
                    ' data-documentotipo="' + reservasPendAloc[i].documento_tipo_nome + '"' +
                    ' data-quartoitem="' + reservasPendAloc[i].quarto_item + '"' +
                    ' data-quartotiponome="' + reservasPendAloc[i].quarto_tipo_curto_nome + '"' +
                    ' data-statusnome="' + reservasPendAloc[i].documento_status_nome + '"' +
                    ' data-statuscodigo="' + reservasPendAloc[i].documento_status_codigo + '"' +
                    ' data-diarias="' + reservasPendAloc[i].diarias_qtd + '"' +
                    ' data-entrada="' + reservasPendAloc[i].inicial_data.split(' ')[0] + '"' +
                    ' data-saida="' + reservasPendAloc[i].final_data.split(' ')[0] + '"' +
                    ' data-entrada-geral="' + reservasPendAloc[i].inicial_data.split(' ')[0] + '"' +
                    ' data-saida-geral="' + reservasPendAloc[i].final_data.split(' ')[0] + '"' +
                    ' data-pax="' + reservasPendAloc[i].pax + '"' +
                    ' data-nome="' + reservasPendAloc[i].nome + '"' +
                    ' data-agenciacodigo="' + reservasPendAloc[i].agencia_codigo + '"' +
                    ' data-agencianome="' + reservasPendAloc[i].agencia_nome + '"' +
                    ' data-cor="' + reservasPendAloc[i].cor + '"' +
                    ' style="background-color: ' + reservasPendAloc[i].cor + ';"' +
                    '><table><tbody><tr>' +
                    '<td>' + reservasPendAloc[i].documento_numero + '-' + reservasPendAloc[i].quarto_item + '</td>' +
                    '<td>' + reservasPendAloc[i].inicial_data.split(' ')[0].split('-')[2] + '/' + reservasPendAloc[i].inicial_data.split(' ')[0].split('-')[1] + '/' + reservasPendAloc[i].inicial_data.split(' ')[0].split('-')[0] + '</td>' +
                    '<td>' + reservasPendAloc[i].final_data.split(' ')[0].split('-')[2] + '/' + reservasPendAloc[i].final_data.split(' ')[0].split('-')[1] + '/' + reservasPendAloc[i].final_data.split(' ')[0].split('-')[0] + '</td>' +
                    '<td>' + reservasPendAloc[i].pax + '</td>' +
                    '<td>' + reservasPendAloc[i].quarto_tipo_curto_nome + '</td>' +
                    '</tr></tbody></table></li>';
        }

        $listaReservasPend
                .append(reservaHtml);

        $('.conta-pendencias')
                .html(reservasPendAloc.length);

        // $blocoAlocPendentes
        //     .height(1);

        $blocoAlocPendentesTopo
                // .width($blocoAlocPendentes.width())
                .show();

    }

    setTimeout(function () {

        var timeout;

        $('.lista-reservas li')
                .on('mouseenter', function (event) {

                    if ($('.lista-reservas-pend li.ativo').length == 0) {

                        clearTimeout(timeout);

                        var elem = $(this);

                        if ($('.lista-reservas li.ativo').length == 0) {

                            var X = $('.agenda-bloco').offset().left,
                                    mouseX = event.pageX - X;

                            $boxHover
                                    .css({
                                        'top': elem.position().top + elem.height() - 25 + 'px',
                                        'left': mouseX + 10 + 'px'
                                    });

                            var rgb = hexToRgb(elem.data('cor'));

                            if (elem.data('pax') && elem.data('pax').length > 0) {
                                boxHtmlQuartoItem = '';
                                boxHtmlQuartoItem += '-' + elem.data('quartoitem');
                                var boxHtml = '<div class="titulo"><span class="capital">' + elem.data('documentotipo') + '</span> ' + elem.data('reserva') + boxHtmlQuartoItem + '</div>' +
                                        '<div class="conteudo" style="background-color: rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ',0.10)">';

                                boxHtml += '<div class="lixeira reserva_cancelar" onclick="javascript:(function(event) { event.stopPropagation(); $(\'#doc_a_cancelar\').val(\'' + elem.data('reserva') + '-' + elem.data('quartoitem') +
                                        '-' + elem.data('statuscodigo') + '-' + elem.data('cancelamento_limite') + '-' + elem.data('cancelamento_valor') + '\').prop(\'checked\', true); processaReservasCancelamento(); })(event) "></div>';

                                //Verifica se foi um quarto que já teve realocação
                                if ((elem.data('entrada-geral') != elem.data('entrada')) || (elem.data('saida') != elem.data('saida-geral'))) {
                                    boxHtml += '<span class="nome">' + elem.data('nome') + '</span><br>' +
                                            'PAX ' + elem.data('pax') + '<br><b>' +
                                            elem.data('entrada-geral').split('-')[2] + '/' + elem.data('entrada-geral').split('-')[1]/*  + '/' + elem.data('entrada').split('-')[0][2]+ elem.data('entrada').split('-')[0][3]*/ +
                                            ' - ' +
                                            elem.data('saida-geral').split('-')[2] + '/' + elem.data('saida-geral').split('-')[1]/* + '/' + elem.data('saida').split('-')[0][2] + elem.data('saida').split('-')[0][3]*/ + '</b><br>';
                                } else
                                    boxHtml += '<span class="nome">' + elem.data('nome') + '</span><br>' +
                                            'PAX ' + elem.data('pax') + '<br>' +
                                            elem.data('entrada-geral').split('-')[2] + '/' + elem.data('entrada-geral').split('-')[1]/*  + '/' + elem.data('entrada').split('-')[0][2]+ elem.data('entrada').split('-')[0][3]*/ +
                                            ' - ' +
                                            elem.data('saida-geral').split('-')[2] + '/' + elem.data('saida-geral').split('-')[1]/* + '/' + elem.data('saida').split('-')[0][2] + elem.data('saida').split('-')[0][3]*/ + '<br>';

                                if (elem.data('agencianome') != 'undefined' && elem.data('agencianome') != undefined) {
                                    boxHtml += elem.data('agencianome') + '<br>';
                                }

                                boxHtml += elem.data('status') +
                                        '</div>';
                            } else {

                                var boxHtml = '<div class="titulo"><span class="capital">' + elem.data('documentotipo') + '</span> ' + elem.data('reserva') + '</div>' +
                                        '<div class="conteudo" style="background-color: rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ',0.15)">';

                                boxHtml += '<div class="lixeira" onclick="javascript:(function(event) { event.stopPropagation(); docpdrexc(\'' + elem.data('reserva') + '\', \'' + elem.data('quartoitem') + '\', \'' + elem.data('documentotipo') + '\', \'' + elem.data('statuscodigo') + '\', \'' + elem.data('quarto') + '\') })(event)"></div>' +
                                        elem.data('entrada-geral').split('-')[2] + '/' + elem.data('entrada-geral').split('-')[1] +
                                        ' - ' +
                                        elem.data('saida-geral').split('-')[2] + '/' + elem.data('saida-geral').split('-')[1] + '<br>' +
                                        elem.data('motivonome') + '<br>' +
                                        elem.data('status');
                            }

                            if (elem.data('documentotipo') == 'reserva')
                                $boxHover.attr('aria-documento-numero', elem.data('reserva')).attr('aria-quarto-item', elem.data('quartoitem')).removeClass('serdocmod').addClass('resdocmod');
                            else if (elem.data('documentotipo') == 'bloqueio comercial')
                                $boxHover.attr('aria-documento-numero', elem.data('reserva')).attr('aria-documento-tipo-codigo', 'bc').removeClass('resdocmod').addClass('serdocmod');
                            else if (elem.data('documentotipo') == 'manutenção com bloqueio')
                                $boxHover.attr('aria-documento-numero', elem.data('reserva')).attr('aria-documento-tipo-codigo', 'mb').removeClass('resdocmod').addClass('serdocmod');
                            $boxHover
                                    .html(boxHtml)
                                    .show();
                        }

                    }

                })
                .on('mouseleave', function () {

                    if ($('.lista-reservas-pend li.ativo').length == 0) {

                        timeout = setTimeout(function () {

                            $boxHover
                                    .removeAttr('onclick')
                                    .hide()
                                    .html('');

                        }, 100);

                    }

                });

        $boxHover
                .on('mouseenter', function () {

                    clearTimeout(timeout);

                })
                .on('mouseleave', function () {

                    if ($('.lista-reservas-pend li.ativo').length == 0) {

                        $(this)
                                .removeAttr('onclick')
                                .hide()
                                .html('');

                    }

                });

    }, 100);

}

var teclaAtual,
        clicAtual;

function monitoraTecla(e) {

    var monitorTecla;

    if (!e)
        e = window.event;

    if (e.shiftKey) {
        /*shift is down*/
        monitorTecla = 'shift';

    } else if (e.altKey) {
        /*alt is down*/
        monitorTecla = 'alt';

    } else if (e.ctrlKey) {
        /*ctrl is down*/
        monitorTecla = 'ctrl';

    } else if (e.metaKey) {
        /*cmd is down*/
        monitorTecla = 'cmd';

    } else if (e.keyCode == 27) {

        scrollTop = $(window).scrollTop();

        montaAgendaSemana(datasPeriodo);

    } else {

        monitorTecla = undefined;

    }

    return monitorTecla;

}

var mouseDown = false,
        inicioReserva = false,
        inicioMarcacao = false,
        fimReserva = false,
        fimMarcacao = false;

function fazReserva() {

    teclaAtual = monitoraTecla(event);

    if (!mouseDown) {

        if (teclaAtual != 'ctrl') {

            if ($('td.marcavel.bloqueio').length > 1) {

                var tipo = 'cria_bloqueio',
                        msg = 'Novo bloqueio',
                        blocos = $('td.marcavel.bloqueio');

            } else if ($('td.marcavel.hover').length > 1) {

                var tipo = 'cria_reserva',
                        msg = 'Nova reserva',
                        blocos = $('td.marcavel.hover');

            } else {

                return;

            }

            dialog_level_1 = $('#dialog_level_1').dialog({autoOpen: false});
            germencri = $('#exibe-germencri').dialog({autoOpen: false});

            if (!(dialog_level_1.dialog('isOpen')) && blocos && !(germencri.dialog('isOpen'))) {

                var quartoTipoArr = [],
                        quartoArr = [],
                        quartoTipoTemp,
                        quartoTemp1,
                        quartoTemp2;

                for (var i = 0; i < blocos.length; i++) {

                    if (blocos.eq(i).data('tipoquarto') != quartoTipoTemp || blocos.eq(i).data('quarto') != quartoTemp1) {

                        quartoTemp1 = blocos.eq(i).data('quarto');

                        quartoTipoTemp = blocos.eq(i).data('tipoquarto');

                        quartoTipoArr.push(quartoTipoTemp);

                    }

                    if (blocos.eq(i).data('quarto') != quartoTemp2) {

                        quartoTemp2 = blocos.eq(i).data('quarto');

                        quartoArr.push(quartoTemp2);

                    }

                }

                var quarto_tipo_codigo = quartoTipoArr,
                        quarto_codigo = quartoArr,
                        data_inicial = blocos.eq(0).data('format'),
                        data_final = blocos.eq(blocos.length - 1).data('format'),
                        documento_tipo_codigo = blocos.eq(0).data('documentotipocodigo');

                if (tipo == 'cria_reserva') {

                    respdrcri(quarto_tipo_codigo, quarto_codigo, data_inicial, data_final);

                } else {

                    docpdrcri(quarto_codigo, data_inicial, data_final);

                }

                inicioReserva = false;

                fimReserva = false;

                teclaAtual = undefined;

            }

        }

    }

}

function validaBloqueio() {
    if ($('td.marcavel.bloqueio').length > 0) {

        for (var i = 0; i < $('td.marcavel.bloqueio').length; i++) {

            dialog_level_1 = $('#dialog_level_1').dialog({autoOpen: false});
            germencri = $('#exibe-germencri').dialog({autoOpen: false});

            if ($('td.marcavel.bloqueio').eq(i).data('quarto') != $('td.marcavel.bloqueio').eq(i - 1).data('quarto') || $('td.marcavel.bloqueio').eq(i).data('tipoquarto') != $('td.marcavel.bloqueio').eq(i - 1).data('tipoquarto') && !(dialog_level_1.dialog('isOpen')) && !(germencri.dialog('isOpen'))) {

                callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 115}, function (html) {
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
                    }
                });

                return false;

            }

        }

        return true;

    } else {

        return true;

    }

}

document.getElementById("agenda_painel_reserva").onmousedown = function (e) {
    mouseDown = true;

    clicAtual = e.which;

    if (clicAtual == 1) {

        teclaAtual = monitoraTecla(event);

        if ($('td.marcavel:hover').length > 0) {

            var bgColor = $('td.marcavel:hover').css('backgroundColor');

            inicioMarcacao = $('td.marcavel:hover')[0];

            //Marca o quarto selecionado
            $('#painel_quarto_codigo_' + $(inicioMarcacao).data('quarto')).css({'background-color': '#8296b4', 'color': 'white', 'font-weight': 'bold'});
            $('#painel_quarto_tipo_codigo_' + $(inicioMarcacao).data('quarto')).css({'background-color': '#8296b4', 'color': 'white', 'font-weight': 'bold'});
            $('#painel_data_' + $(inicioMarcacao).data('format')).css("cssText", "background-color: #8296b4 !important;color:white !important;font-weight:bold");
            dialog_level_1 = $('#dialog_level_1').dialog({autoOpen: false});
            germencri = $('#exibe-germencri').dialog({autoOpen: false});

            if (inicioReserva && $(inicioReserva).data('format') != $('td.marcavel:hover').data('format') && !(dialog_level_1.dialog('isOpen')) &&
                    !(germencri.dialog('isOpen'))) {

                callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 114}, function (html) {
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
                    }
                });

                return;

            }

            if (teclaAtual == 'shift') {

                $('td.marcavel:hover')
                        .addClass('bloqueio')
                        .addClass('entrada');

            } else {

                $('td.marcavel:hover')
                        .addClass('hover')
                        .addClass('entrada');

            }

        }

    }

    if ($('.lista-reservas-pend li:hover').length > 0) {
        var Xs = $('.agenda-bloco').offset().left,
                Ys = $('.agenda-bloco').offset().top,
                mouseXs = event.pageX - Xs,
                mouseYs = event.pageY - Ys;

        $boxHoverMove
                .css({
                    'top': mouseYs - 18 + 'px',
                    'left': mouseXs + 15 + 'px'

                });

        var elem = $('.lista-reservas-pend li:hover'),
                rgb = hexToRgb(elem.data('cor'));

        var boxHtml = '<div class="titulo"><span class="capital">' + elem.data('documentotipo') + '</span> ' + elem.data('reserva') + ' - ' + elem.data('quartoitem') + '</div>' +
                '<div class="conteudo" style="background-color: rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ',0.25)">';

        boxHtml += '<span class="nome">' + elem.data('nome') + '<span><br>' + 'PAX ' + elem.data('pax') + '<br/>' +
                elem.data('entrada-geral').split('-')[2] + '/' + elem.data('entrada-geral').split('-')[1]/*  + '/' + elem.data('entrada').split('-')[0][2]+ elem.data('entrada').split('-')[0][3]*/ +
                ' - ' +
                elem.data('saida-geral').split('-')[2] + '/' + elem.data('saida-geral').split('-')[1] + '<br>';

        if (elem.data('agenciacodigo') != 'undefined' && elem.data('agenciacodigo') != undefined) {

            boxHtml += $('#agencia_' + elem.data('agenciacodigo')).val() + '<br>';

        }

        boxHtml += elem.data('statusnome') +
                '</div>';

        $boxHoverMove
                .html(boxHtml)
                .show();

    }

}

document.getElementById("agenda_painel_reserva").onmouseup = function () {

    mouseDown = false;

    $boxHoverMove
            .html('')
            .hide();

    if ($('td.marcavel.hover[data-ocupado="true"][data-inicio="false"]').length > 0 || $('td.marcavel.bloqueio[data-ocupado="true"]').length > 0) {

        $('td.marcavel.hover')
                .removeClass('entrada')
                .removeClass('saida')
                .removeClass('hover');

        $('td.marcavel.bloqueio')
                .removeClass('entrada')
                .removeClass('saida')
                .removeClass('bloqueio');

        return;
    }

    if (!dragAtivado) {

        if ($('td.marcavel.hover').length == 1) {
            removeMarcacao();

            return;

        }

        if ($('td.marcavel.hover').length > 0 || $('td.marcavel.bloqueio').length > 0) {

            if ($('td.marcavel.bloqueio').length > 0) {
                $elemHover
                        .find('.flecha')
                        .removeAttr('style');
            } else {
                $elemHover
                        .find('.flecha')
                        .css('border', 'border: 0 solid #8296b4');
            }

        }

        var validBloqueio = validaBloqueio();

        if (!validBloqueio) {

            return;

        }

        teclaAtual = monitoraTecla(event);
        if (!inicioReserva && teclaAtual == 'ctrl') {

            inicioReserva = $('td.marcavel.hover')[0];
            fimReserva = $('td.marcavel.hover')[ ($('td.marcavel.hover').length - 1) ];

        } else {

            dialog_level_1 = $('#dialog_level_1').dialog({autoOpen: false});
            germencri = $('#exibe-germencri').dialog({autoOpen: false});

            if (fimReserva && $(fimReserva).data('format') != hoverAgora.data('format') && !(dialog_level_1.dialog('isOpen')) &&
                    !(germencri.dialog('isOpen'))) {
                callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 114}, function (html) {
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

                return;

            } else if (teclaAtual != 'ctrl') {
                fazReserva();
            }
        }

    }
}

var $elemHover

function arrastaNovaReserva(elem) {

    hoverAgora = elem;

    if (mouseDown && clicAtual == 1 && $('.lista-reservas li.ativo').length == 0 && $('.lista-reservas-pend li.ativo').length == 0) {
        $elemHover = $('td.marcavel[data-quarto="' + $(inicioMarcacao).data('quarto') + '"][data-tipoquarto="' + $(inicioMarcacao).data('tipoquarto') + '"][data-format="' + elem.data('format') + '"]');
        $('#painel_data_' + elem.data('format')).css("cssText", "background-color: #8296b4 !important;color:white!important;font-weight:bold");


        if (teclaAtual == 'shift') {

            var classe = 'bloqueio';

        } else {

            var classe = 'hover';

        }

        $elemHover
                .addClass(classe);

        var $elemLinha = $('td.marcavel.' + classe + '[data-quarto="' + $(inicioMarcacao).data('quarto') + '"][data-tipoquarto="' + $(inicioMarcacao).data('tipoquarto') + '"]')

        $elemLinha
                .removeClass('entrada')
                .removeClass('saida');

        inicioMarcacao = $elemLinha[0];

        $elemLinha
                .eq(0)
                .addClass('entrada');

        if ($elemLinha.length > 1) {

            $elemLinha
                    .eq($elemLinha.length - 1)
                    .addClass('saida');

        }

    }

}

document.body.onkeydown = function () {

    teclaAtual = monitoraTecla(event);

    if (teclaAtual == 'shift' && $('td.marcavel.hover').length > 0) {

        $('td.marcavel.hover')
                .addClass('bloqueio')
                .removeClass('hover');

    }

}

function removeMarcacao() {
    $('td.marcavel.hover')
            .removeClass('entrada')
            .removeClass('saida')
            .removeClass('hover');

    $('td.marcavel.bloqueio')
            .removeClass('entrada')
            .removeClass('saida')
            .removeClass('bloqueio');

    inicioReserva = false;

    fimReserva = false;

    teclaAtual = undefined;

    //Desfaz os destaques de quartos e datas selecionadas
    $('#painel_quarto_codigo_' + $(inicioMarcacao).data('quarto')).css({'color': '#333', 'font-weight': 'normal'});
    $('#painel_quarto_tipo_codigo_' + $(inicioMarcacao).data('quarto')).css({'color': '#333', 'font-weight': 'normal'});
    $('td[id^="painel_data_"] ').not('.cor-txt1').css({'color': '#333!important', 'font-weight': 'normal'});
    $('.cor-txt1').css({'color': '#FB4848!important', 'font-weight': 'bold'});
    $('.nao-util').css({'color': '#2196F3!important', 'font-weight': 'normal'});
    $('.agenda-table td').not('.nao-util').css('background-color', '#d4d4d4');
    $('.agenda-table thead td').css('background-color', '#d4d4d4');
    $('.agenda-table tr:nth-of-type(odd) td').not('.nao-util').css('background-color', '#f9f9f9');
    $('.painel_quarto_tipo_codigo, .painel_quarto_codigo').css({'color': 'rgb(51, 51, 51) !impotant', 'font-weight': 'normal'});
}

document.body.onkeyup = function () {

    dialog_level_1 = $('#dialog_level_1').dialog({autoOpen: false});
    germencri = $('#exibe-germencri').dialog({autoOpen: false});

    if (mouseDown && fimReserva && $(fimReserva).data('format') != hoverAgora.data('format') && !(dialog_level_1.dialog('isOpen')) && !(germencri.dialog('isOpen'))) {
        var validBloqueio = validaBloqueio();

        callAjax('/ajax/ajaxgermencri', {mensagem_codigo: 114}, function (html) {
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

        teclaAtual = undefined;

        if (validBloqueio) {
            return true;
        }

        return;

    } else if (!mouseDown) {
        if (teclaAtual == 'ctrl') {
            fazReserva();
        }
    }

    if ($('td.marcavel.bloqueio').length > 0) {
        $elemHover
                .find('.flecha')
                .removeAttr('style');

    } else if ($elemHover) {

        $elemHover
                .find('.flecha')
                .css('border', 'border: 0 solid #8296b4');
    }
    return true;

}